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

### License Verification System (October 2025)
- **Mock Mode Support**: The system includes a license mock mode for development and testing
  - Set `LICENSE_MOCK_MODE=true` in `.env` to enable mock verification
  - When enabled, any purchase code will be accepted without internet connection
  - Mock mode works in both installer and admin settings
  - License data is stored in `public/license.json` file
- **Implementation Details**:
  - `InstallService::checkLicense()` - Handles license verification with mock support
  - `InstallService::getMockLicenseResponse()` - Generates mock license data
  - Mock data includes: buyer info, purchase code, support dates, verified_at, domain info
  - Both `InstallController` and `Admin\Settings\LicenseController` use consistent file path
  - When mock mode is disabled, system connects to remote API for verification
- **Usage**:
  - Development/Testing: Set `LICENSE_MOCK_MODE=true` in `.env`
  - Production: Set `LICENSE_MOCK_MODE=false` and configure real license server URL
  - The mock mode indicator is displayed on the license page when enabled
- **File Locations**:
  - Service: `app/Services/InstallService.php`
  - Installer: `app/Http/Controllers/InstallController.php`
  - Admin Panel: `app/Http/Controllers/Admin/Settings/LicenseController.php`
  - License Storage: `public/license.json`

### Install State Management System (October 2025)
- **Problem Solved**: Fixed infinite installation loop in Replit environment where `.env` file was reset on restart
- **Solution**: Persistent JSON-based state management instead of relying on `.env` variables
  - Installation state stored in `storage/app/install_state.json` (persistent across restarts)
  - `.env` variables synchronized only when installation completes
  - Automatic migration from old `.env`-based system on first run
- **Implementation Details**:
  - `InstallStateRepository` - Service for managing installation state via JSON file
  - Helper functions: `installState()`, `getInstallState()`, `setInstallState()`, `isSystemInstalled()`, `syncInstallStateToEnv()`
  - All installation steps now use JSON state instead of `env()` calls
  - Middleware updated to use `isSystemInstalled()` helper
  - `AppServiceProvider` updated to check JSON state instead of `.env`
- **State Variables Tracked**:
  - `INSTALL_WELCOME`, `INSTALL_REQUIREMENTS`, `INSTALL_FILE_PERMISSIONS`
  - `INSTALL_LICENSE`, `INSTALL_DATABASE_INFO`, `INSTALL_DATABASE_IMPORT`
  - `INSTALL_SITE_INFO`, `SYSTEM_INSTALLED`
  - Additional metadata: `installed_at`, `last_updated`
- **File Locations**:
  - Repository: `app/Services/InstallStateRepository.php`
  - Helpers: `app/Helpers/Helper.php` (install state functions)
  - State Storage: `storage/app/install_state.json`
  - Updated files: `InstallController`, `RedirectIfNotInstalled`, `PreventAccessIfInstalled`, `AppServiceProvider`

### Site Information Installation Step Enhancements (October 2025)
- **Problem 1 - Pre-existing Admin Account**: Database import creates a default admin account, causing unique constraint violations on re-runs
  - **Solution**: Modified `siteInfoPost()` to detect existing admin and update credentials instead of creating duplicate
  - Validation rules adjusted to ignore existing admin email
  - Clear English warning message displayed when admin already exists
  - User can update credentials by entering new email/password
- **Problem 2 - Password Visibility Toggle**: Password show/hide button wasn't working in installation pages
  - **Solution**: Added `main.js` to `install/layout.blade.php`
  - Fixed JavaScript null reference errors in `main.js` for installer compatibility
  - Added null checks for `.dashboard-sidebar .overlay`, `.sidebar-toggle`, and `.sidebar-close` elements
- **Problem 3 - Replit Domain Compatibility**: Clarified URL format requirements for Replit domains
  - **Solution**: Added helper text explaining HTTPS requirement
  - Confirmed Laravel's URL validation accepts Replit domain format (e.g., `https://xxx-xxx.replit.dev`)
  - Default value uses `url('/')` which automatically includes protocol
- **Files Modified**:
  - `app/Http/Controllers/InstallController.php` - Added admin detection and update logic
  - `resources/views/install/site_info.blade.php` - Added warning message and URL helper text
  - `resources/views/install/layout.blade.php` - Added main.js script
  - `public/assets/js/main.js` - Fixed null reference errors for installer compatibility

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