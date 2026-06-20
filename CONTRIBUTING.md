# Contributing to AcdowzCo

First off, thank you for considering contributing! 🎉

## Code of Conduct

This project adheres to a [Code of Conduct](CODE_OF_CONDUCT.md). By participating, you agree to uphold it.

## Development Setup

```bash
# Fork and clone
git clone https://github.com/YOUR_USERNAME/AcdowzCo.git
cd AcdowzCo

# PHP dependencies
composer install

# Environment
cp .env.example .env
php artisan key:generate

# Database
mysql -u root -p -e "CREATE DATABASE acdowzco"
mysql -u root -p acdowzco < database.sql

# Frontend
npm install
npm run production
```

## Coding Standards

- Follow PSR-12 PHP coding standards
- Use Laravel conventions (eloquent, blade, service providers)
- Vue 3 Composition API for frontend components
- Run `./vendor/bin/phpunit` before submitting PRs

## Pull Request Process

1. Fork and create a feature branch (`feature/your-feature`)
2. Make changes with clear commit messages
3. Ensure tests pass: `phpunit`
4. Push and open PR against `master`

**Thank you for contributing!** 🚀
