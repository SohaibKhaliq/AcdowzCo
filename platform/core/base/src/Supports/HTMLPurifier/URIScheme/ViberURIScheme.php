<?php

namespace Botble\Base\Supports\HTMLPurifier\URIScheme;

use HTMLPurifier_URIScheme;

/**
 * Validates viber (for Viber messaging app links).
 *
 * This class handles Viber URI scheme validation for links like:
 * viber://chat?number=+1234567890
 * viber://add?number=1234567890
 * viber://contact?number=1234567890
 * viber://forward?text=Hello%20World
 * viber://pa?chatURI=viber://pa/chatURI
 */
class ViberURIScheme extends HTMLPurifier_URIScheme
{
    public $browsable = false;

    public $may_omit_host = false;

    protected array $allowedHosts = [
        'chat',     // viber://chat?number=+1234567890
        'add',      // viber://add?number=1234567890
        'contact',  // viber://contact?number=1234567890
        'forward',  // viber://forward?text=Hello%20World
        'pa',       // viber://pa?chatURI=viber://pa/chatURI
    ];

    public function doValidate(&$uri, $config, $context): bool
    {
        $uri->userinfo = null;
        $uri->port = null;

        if ($uri->host && ! $this->isValidHost($uri->host)) {
            return false;
        }

        if ($uri->host && $uri->query) {
            return $this->validateQueryForAction($uri->host, $uri->query);
        }

        return true;
    }

    protected function isValidHost(string $host): bool
    {
        return in_array($host, $this->allowedHosts, true);
    }

    protected function validateQueryForAction(string $action, string $query): bool
    {
        parse_str($query, $params);

        return match ($action) {
            'chat', 'add', 'contact' => $this->validateNumberParameter($params),
            'forward' => $this->validateForwardParameter($params),
            'pa' => $this->validatePaParameter($params),
            default => true,
        };
    }

    protected function validateNumberParameter(array $params): bool
    {
        if (! isset($params['number'])) {
            return false;
        }

        $number = $params['number'];

        return preg_match('/^\+?[0-9\s\-\(\)]+$/', $number) === 1;
    }

    protected function validateForwardParameter(array $params): bool
    {
        return ! empty($params['text']);
    }

    protected function validatePaParameter(array $params): bool
    {
        return ! empty($params['chatURI']);
    }
}
