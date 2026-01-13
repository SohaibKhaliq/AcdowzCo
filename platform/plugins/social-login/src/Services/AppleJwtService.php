<?php

namespace Botble\SocialLogin\Services;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AppleJwtService
{
    protected string $jwksUri = 'https://appleid.apple.com/auth/keys';
    protected string $issuer = 'https://appleid.apple.com';

    public function verifyToken(string $identityToken): ?array
    {
        try {
            // Decode the JWT header to get the key ID
            $decodedHeader = JWT::urlsafeB64Decode(
                explode('.', $identityToken)[0]
            );
            $header = json_decode($decodedHeader, true);

            if (! isset($header['kid'])) {
                throw new Exception('Missing key ID in token header');
            }

            $kid = $header['kid'];

            // Get Apple's signing key
            $signingKey = $this->getAppleSigningKey($kid);

            if (! $signingKey) {
                throw new Exception('Unable to retrieve Apple signing key');
            }

            // Verify the JWT token
            $verifiedToken = JWT::decode($identityToken, new Key($signingKey, 'RS256'));

            // Convert to array for easier handling
            $tokenData = json_decode(json_encode($verifiedToken), true);

            // Validate issuer
            if ($tokenData['iss'] !== $this->issuer) {
                throw new Exception('Invalid token issuer');
            }

            // Validate expiration
            if ($tokenData['exp'] < time()) {
                throw new Exception('Token has expired');
            }

            return $tokenData;

        } catch (Exception $e) {
            logger()->error('Apple JWT verification failed: ' . $e->getMessage());

            return null;
        }
    }

    protected function getAppleSigningKey(string $kid): ?string
    {
        try {
            // Cache the JWKS for 1 hour to avoid repeated requests
            $jwks = Cache::remember('apple_jwks', 3600, function () {
                $response = Http::timeout(10)->get($this->jwksUri);

                if (! $response->successful()) {
                    throw new Exception('Failed to fetch Apple JWKS');
                }

                return $response->json();
            });

            if (! isset($jwks['keys'])) {
                throw new Exception('Invalid JWKS response format');
            }

            // Find the key with matching kid
            foreach ($jwks['keys'] as $key) {
                if ($key['kid'] === $kid) {
                    return $this->convertJwkToPem($key);
                }
            }

            throw new Exception('Key not found in JWKS');

        } catch (Exception $e) {
            logger()->error('Failed to get Apple signing key: ' . $e->getMessage());

            return null;
        }
    }

    protected function convertJwkToPem(array $jwk): string
    {
        if (! isset($jwk['n']) || ! isset($jwk['e'])) {
            throw new Exception('Invalid JWK format');
        }

        // Decode the modulus and exponent
        $n = $this->base64UrlDecode($jwk['n']);
        $e = $this->base64UrlDecode($jwk['e']);

        // Create RSA public key
        $rsa = [
            'modulus' => $n,
            'publicExponent' => $e,
        ];

        // Convert to PEM format
        return $this->rsaPublicKeyToPem($rsa);
    }

    protected function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $data .= str_repeat('=', $padlen);
        }

        return base64_decode(strtr($data, '-_', '+/'));
    }

    protected function rsaPublicKeyToPem(array $rsa): string
    {
        // This is a simplified version. For production, consider using a proper ASN.1 encoder
        $modulus = $rsa['modulus'];
        $exponent = $rsa['publicExponent'];

        // Create ASN.1 DER encoding for RSA public key
        $der = $this->createRsaPublicKeyDer($modulus, $exponent);

        // Convert to PEM format
        $pem = "-----BEGIN PUBLIC KEY-----\n";
        $pem .= chunk_split(base64_encode($der), 64, "\n");
        $pem .= "-----END PUBLIC KEY-----\n";

        return $pem;
    }

    protected function createRsaPublicKeyDer(string $modulus, string $exponent): string
    {
        // Create ASN.1 DER encoding for RSA public key
        // This is a basic implementation - for production use, consider using phpseclib

        $modulusHex = bin2hex($modulus);
        $exponentHex = bin2hex($exponent);

        // Add leading zero if first bit is 1 (to ensure positive integer)
        if (hexdec(substr($modulusHex, 0, 2)) > 127) {
            $modulusHex = '00' . $modulusHex;
        }
        if (hexdec(substr($exponentHex, 0, 2)) > 127) {
            $exponentHex = '00' . $exponentHex;
        }

        // Create INTEGER for modulus
        $modulusLength = strlen($modulusHex) / 2;
        $modulusLengthHex = $this->encodeLength($modulusLength);
        $modulusAsn1 = '02' . $modulusLengthHex . $modulusHex;

        // Create INTEGER for exponent
        $exponentLength = strlen($exponentHex) / 2;
        $exponentLengthHex = $this->encodeLength($exponentLength);
        $exponentAsn1 = '02' . $exponentLengthHex . $exponentHex;

        // Create SEQUENCE for RSA public key
        $rsaSequence = $modulusAsn1 . $exponentAsn1;
        $rsaSequenceLength = strlen($rsaSequence) / 2;
        $rsaSequenceLengthHex = $this->encodeLength($rsaSequenceLength);
        $rsaSequenceAsn1 = '30' . $rsaSequenceLengthHex . $rsaSequence;

        // Create BIT STRING
        $bitStringLength = strlen($rsaSequenceAsn1) / 2 + 1;
        $bitStringLengthHex = $this->encodeLength($bitStringLength);
        $bitStringAsn1 = '03' . $bitStringLengthHex . '00' . $rsaSequenceAsn1;

        // Create algorithm identifier for RSA
        $algorithmId = '300d06092a864886f70d0101010500'; // RSA algorithm OID

        // Create final SEQUENCE
        $finalSequence = $algorithmId . $bitStringAsn1;
        $finalSequenceLength = strlen($finalSequence) / 2;
        $finalSequenceLengthHex = $this->encodeLength($finalSequenceLength);
        $finalAsn1 = '30' . $finalSequenceLengthHex . $finalSequence;

        return hex2bin($finalAsn1);
    }

    protected function encodeLength(int $length): string
    {
        if ($length < 128) {
            return sprintf('%02x', $length);
        }

        $lengthBytes = [];
        while ($length > 0) {
            array_unshift($lengthBytes, $length & 0xFF);
            $length >>= 8;
        }

        $lengthOfLength = count($lengthBytes);
        $result = sprintf('%02x', 0x80 | $lengthOfLength);

        foreach ($lengthBytes as $byte) {
            $result .= sprintf('%02x', $byte);
        }

        return $result;
    }
}
