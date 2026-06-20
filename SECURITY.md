# Security Policy

## Reporting a Vulnerability

If you discover a security vulnerability, **do not open a public issue**. Report privately by emailing the repository maintainer.

**Response timeline:**
- Acknowledgment within 48 hours
- Investigation within 5 business days

## Best Practices for Production

- **Change `ADMIN_DIR`** from default `admin` to a custom value
- **Disable installer**: Set `CMS_ENABLE_INSTALLER=false`
- **Disable debug**: Set `APP_DEBUG=false`
- **Enable HTTPS**: Set `SESSION_SECURE_COOKIE=true` and `FORCE_SCHEMA=https`
- **Security headers**: Keep `ENABLE_HTTP_SECURITY_HEADERS=true`
- **Rotate APP_KEY**: Run `php artisan key:generate` on each deployment
- **Run `composer audit`** regularly for dependency vulnerabilities
- **Never commit `.env`** files or production database dumps
