# Overview

Trash Mails is a temporary email address system built with Laravel 11. It provides users with disposable email addresses for receiving emails without revealing their real email addresses. The system includes features for managing temporary inboxes, receiving emails via IMAP, user authentication with social login, multi-language support, subscription/payment plans, admin dashboard, and bot integration (Telegram).

# User Preferences

- **Communication language**: Arabic (العربية)
- **Communication style**: Simple, everyday language

# System Architecture

## Core Framework
- **Laravel 11** (PHP 8.2+) - Modern PHP framework providing the application foundation
- **Laravel UI** - Authentication scaffolding with Bootstrap
- **Laravel Sanctum** - API authentication
- **Vite** - Frontend build tool replacing Laravel Mix, configured for development with HMR support

## Frontend Architecture
- **Bootstrap 5** - CSS framework for responsive UI
- **Sass** - CSS preprocessor for styling
- **JavaScript/jQuery** - Client-side interactions
- **Chart.js** - Data visualization for analytics dashboards
- **PurgeCSS** - Removes unused CSS for production optimization
- **Popper.js** - Positioning library for tooltips/dropdowns
- **Custom themes** - Multiple CSS files (style.css, style.rtl.css for RTL support, error_style.css, install.css)

## Email Processing
- **webklex/laravel-imap** - IMAP email fetching and processing
- **php-mime-mail-parser** - Parsing MIME email messages
- **HTMLPurifier** - Sanitizing HTML content from emails to prevent XSS

## User Features
- **Laravel Socialite** - OAuth authentication with social providers (Google, Facebook, etc.)
- **NoCaptcha/hCaptcha** - Bot protection for forms
- **mcamara/laravel-localization** - Multi-language support with route-based locale switching
- **Hashids** - Obfuscating database IDs in URLs

## SEO & Content
- **artesaos/seotools** - Meta tags, Open Graph, Twitter Cards, JSON-LD management
- **cviebrock/eloquent-sluggable** - Automatic URL-friendly slug generation for models

## Image Handling
- **intervention/image-laravel** - Image manipulation and processing
- **artisync/image** - Custom image processing package

## Payment/Subscription System
- **Planify** (custom package) - Subscription and plan management system (vendor/lobage/planify)

## External Integrations
- **irazasyed/telegram-bot-sdk** - Telegram bot API integration for notifications
- **Firebase JWT** - JSON Web Token handling for API authentication

## Developer Tools
- **barryvdh/laravel-debugbar** - Development debugging toolbar
- **Laravel Pint** - Code style fixer
- **PHPUnit** - Testing framework

## Utilities
- **php-flasher/flasher-notyf-laravel** - Flash notification messages
- **trustip/trustip** - IP address validation and geolocation
- **Guzzle** - HTTP client for external API calls
- **cocur/slugify** - String to URL-friendly slug conversion

## Configuration Management
- System uses a JSON-based plugin configuration system (plugins.json) for managing API keys and settings
- Environment-based configuration via .env file with support for maintenance mode and cache store settings

## Installation & Updates
- Includes installation wizard system (public/assets/js/installation_install.js with confetti celebration)
- Update mechanism with version tracking and migration support (update/2.1/instruction.txt)
- Documentation hosted externally (docs.lobage.com/trashmails)

### Database Installation System (October 2025)
- **Dual Database Support**: The system now supports both PostgreSQL and MySQL through dedicated SQL files
  - `database/data_pgsql.sql` - PostgreSQL-optimized schema and data
  - `database/data_mysql.sql` - MySQL-compatible schema and data
- **MySQL to PostgreSQL Converter**: Custom PHP script (`database/mysql_to_postgresql_converter.php`) that:
  - Converts MySQL data types to PostgreSQL equivalents
  - Handles AUTO_INCREMENT to SERIAL conversion
  - Converts string escaping from MySQL (`\'`) to PostgreSQL (`''`) format
  - Removes MySQL-specific syntax (ENGINE, CHARSET, COLLATE)
  - Adds proper PostgreSQL transaction handling and settings
- **Enhanced Installation UI**: Database import page now includes:
  - Database type selector (PostgreSQL/MySQL dropdown)
  - Real-time progress bar with percentage display
  - Animated progress indicator with status messages in Arabic
  - Automatic database type detection from .env configuration
  - User-friendly status messages during import process

# External Dependencies

## Third-Party Services
- **reCAPTCHA** - Google's bot protection service (configured via NOCAPTCHA_SITEKEY)
- **hCaptcha** - Alternative CAPTCHA service
- **Social OAuth Providers** - Google, Facebook, and other OAuth providers for social login
- **Telegram Bot API** - For bot notifications and interactions

## Email Infrastructure
- **IMAP Servers** - For fetching emails from temporary mailboxes
- **SMTP Services** - For sending emails (if applicable)

## External APIs
- **Image CDN/Storage** - For image hosting and delivery
- **Payment Gateways** - Integrated through Planify package (Stripe, PayPal, etc.)

## Development Services
- **Packagist** - PHP package repository for Composer dependencies
- **NPM Registry** - JavaScript package repository for Node dependencies