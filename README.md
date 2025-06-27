# Multi-Step Lead Capture Form

A modern, responsive multi-step lead capture form built with Laravel, Inertia.js, and Vue.js. This application collects business information from potential clients through an intuitive 4-step process with real-time validation, user feedback, and OneSignal push notification integration.

## 🚀 Live Demo

- **Production URL**: [https://your-domain.com](https://your-domain.com)

## 📋 Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [OneSignal Integration](#onesignal-integration)
- [Testing](#testing)
- [Architecture](#architecture)
- [Deployment](#deployment)
- [Contributing](#contributing)

## ✨ Features

- **Multi-step Form Process**: 4-step lead capture with progress tracking
- **Real-time Validation**: Client-side and server-side validation
- **Email Duplication Prevention**: Prevents duplicate email submissions
- **OneSignal Push Notifications**: Automatic notifications on successful form submission
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Session Management**: Form data persistence across steps
- **User Feedback**: Success and error messages with proper UX
- **Comprehensive Testing**: Full test coverage with Pest PHP

### Form Steps

1. **Basic Information**: Name, email, company name, website URL (optional)
2. **Website Details**: Website type selection (E-commerce, Blog, Corporate, etc.)
3. **Platform Selection**: Conditional platform options based on website type
4. **Review & Submit**: Summary review with edit capabilities and notification status

## 🔧 Requirements

- **PHP**: 8.1 or higher
- **Composer**: Latest version
- **Node.js**: 18.x or higher
- **npm** or **yarn**: Package manager
- **MySQL**: 8.0 or higher
- **Docker** (optional): For containerized development

## 📦 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/mobiloud-interview.git
cd mobiloud-interview
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Update your `.env` file with database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mobiloud_interview
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start the Application

```bash
# Using Laravel Sail (Docker)
./vendor/bin/sail up

# Using PHP's built-in server
php artisan serve
```

The application will be available at `http://localhost:8000`

## ⚙️ Configuration

### Environment Variables

Key environment variables to configure:

```env
APP_NAME="Lead Capture Form"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mobiloud_interview
DB_USERNAME=your_username
DB_PASSWORD=your_password

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### Mail Configuration (Optional)

If you want to send email notifications:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🚀 Usage

### Starting the Application

```bash
# Development with hot reload
npm run dev

# Production build
npm run build

# Start server
php artisan serve
```

### Running Tests

```bash
# Run all tests
./vendor/bin/sail test

# Run specific test file
./vendor/bin/sail test tests/Feature/FormTest.php

# Run with coverage
./vendor/bin/sail test --coverage
```

### Database Operations

```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Seed database (if seeders exist)
php artisan db:seed

# Reset database
php artisan migrate:fresh
```

## 📚 API Documentation

### Form Endpoints

#### GET `/form`
**Description**: Display the form index page with success messages

**Response**: Inertia page with success message if available

#### GET `/form/step1`
**Description**: Display step 1 of the form

**Response**: Inertia page with form data and validation errors

**Query Parameters**:
- `errors`: Validation errors (from session)
- `success`: Success message (from session)
- `old`: Old input data (from session)

#### POST `/form/step1`
**Description**: Submit step 1 form data

**Request Body**:
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "company_name": "Acme Corp",
  "website_url": "https://acme.com" // optional
}
```

**Validation Rules**:
- `name`: required, min:3 characters
- `email`: required, valid email format
- `company_name`: required, min:3 characters
- `website_url`: optional, valid URL format

**Response**: Redirect to `/form/step2` on success

#### GET `/form/step2`
**Description**: Display step 2 of the form

**Prerequisites**: Step 1 data must be in session

**Response**: Inertia page with website type options

#### POST `/form/step2`
**Description**: Submit step 2 form data

**Request Body**:
```json
{
  "website_type": "ecommerce"
}
```

**Validation Rules**:
- `website_type`: required, in: ecommerce,blog,corporate,portfolio,other

**Response**: Redirect to `/form/step3` on success

#### GET `/form/step3`
**Description**: Display step 3 of the form

**Prerequisites**: Step 1 and 2 data must be in session

**Response**: Inertia page with platform options based on website type

#### POST `/form/step3`
**Description**: Submit step 3 form data

**Request Body**:
```json
{
  "platform": "shopify"
}
```

**Validation Rules**:
- `platform`: required, valid platform for selected website type

**Response**: Redirect to `/form/step4` on success

#### GET `/form/step4`
**Description**: Display step 4 (review) of the form

**Prerequisites**: All previous steps data must be in session

**Response**: Inertia page with form summary

#### POST `/form/submit`
**Description**: Submit the complete form

**Prerequisites**: All form steps data must be in session

**Response**:
- Success: Redirect to `/form` with success message and OneSignal notification sent
- Error: Redirect to `/form/step1` with error message

### Error Responses

All endpoints return appropriate HTTP status codes:

- `200`: Success
- `302`: Redirect (form submission)
- `422`: Validation errors
- `500`: Server error

## 🔔 OneSignal Integration

The application includes comprehensive OneSignal push notification integration that automatically sends notifications when users successfully submit the lead capture form.

### Features

- **Automatic Notifications**: Sends push notifications on successful form submission
- **User Segmentation**: Segments users by website type and platform
- **Real-time Status**: Shows notification status in the UI
- **Error Handling**: Graceful handling of notification failures
- **Comprehensive Testing**: Full test coverage for OneSignal integration

### Configuration

#### 1. OneSignal Account Setup

1. Create a OneSignal account at [onesignal.com](https://onesignal.com)
2. Create a new web app in your OneSignal dashboard
3. Note your **App ID** and **REST API Key**

#### 2. Environment Configuration

Add the following variables to your `.env` file:

```env
ONESIGNAL_APP_ID=your-onesignal-app-id
ONESIGNAL_REST_API_KEY=your-onesignal-rest-api-key
ONESIGNAL_USER_AUTH_KEY=your-onesignal-user-auth-key
```

#### 3. Frontend Integration

The OneSignal JavaScript SDK is automatically loaded in the application layout:

```html
<!-- OneSignal SDK -->
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" defer></script>
```

#### 4. Backend Service

The application includes a dedicated `OneSignalService` class:

```php
// app/Services/OneSignalService.php
class OneSignalService
{
    public function sendNotification(array $data): array
    {
        // Sends notification to OneSignal API
        // Returns response with success/error status
    }
}
```

### How It Works

1. **Form Submission**: When a user submits the form successfully
2. **Data Collection**: Form data is collected and prepared for notification
3. **OneSignal API Call**: The backend service sends a notification to OneSignal
4. **User Segmentation**: Users are segmented by website type and platform
5. **Notification Delivery**: OneSignal delivers the notification to subscribed users
6. **Status Feedback**: The UI shows the notification status to the user

### Notification Content

The notification includes:

- **Title**: "New Lead Captured!"
- **Message**: Customized based on user's website type and platform
- **Data**: Additional user information for segmentation
- **URL**: Link back to your application

### User Segmentation

Users are automatically segmented based on:

- **Website Type**: E-commerce, Blog, Corporate, Portfolio, Other
- **Platform**: Shopify, WooCommerce, WordPress, etc.
- **Company Size**: Based on company name analysis

### Error Handling

The integration includes robust error handling:

- **API Failures**: Graceful handling of OneSignal API errors
- **Network Issues**: Retry logic for network failures
- **Invalid Data**: Validation of notification data
- **User Feedback**: Clear status messages in the UI
- **Form Submission Reliability**: Users are always saved even if notifications fail
- **Transaction Safety**: Database transactions ensure data integrity
- **Comprehensive Logging**: Detailed logging for debugging notification issues

### Reliability Improvements

The OneSignal integration has been enhanced with several reliability improvements:

#### 1. **Separated Concerns**
- User creation and notification sending are now separate operations
- Users are always saved to the database regardless of notification status
- Notification failures do not break the form submission flow

#### 2. **Database Transactions**
- User creation uses database transactions for data integrity
- If user creation fails, the entire transaction is rolled back
- If notification fails, the user data remains saved

#### 3. **Enhanced Error Handling**
- Detailed error logging for debugging notification issues
- Graceful degradation when OneSignal service is unavailable
- User-friendly error messages that don't expose technical details

#### 4. **Improved Logging**
- Comprehensive logging of notification attempts and results
- Detailed error information for troubleshooting
- Success logging with notification IDs and recipient counts

#### 5. **User Experience**
- Clear status messages during form submission
- Notification status indicators in the UI
- Form submission always succeeds even if notifications fail

### Troubleshooting

If notifications are not being received:

1. **Check Configuration**: Verify OneSignal credentials in `.env`
2. **Review Logs**: Check Laravel logs for notification errors
3. **Test Service**: Use the test command to verify OneSignal connectivity
4. **Check Browser**: Ensure OneSignal SDK is properly loaded
5. **Verify Subscription**: Check if users are subscribed to notifications

### Testing

The integration includes comprehensive testing:

```bash
# Run OneSignal integration tests
php artisan test --filter=OneSignalIntegrationTest

# Test OneSignal service directly
php artisan onesignal:test --message="Test notification"
```

### Monitoring and Analytics

- **Notification Status**: Track successful/failed notifications
- **User Engagement**: Monitor notification open rates
- **Segmentation Analytics**: Analyze user behavior by segments
- **Error Logging**: Log notification failures for debugging

### Security Considerations

- **API Key Protection**: OneSignal API keys are stored securely in environment variables
- **Data Privacy**: Only necessary data is sent to OneSignal
- **User Consent**: Notifications are sent automatically on form submission
- **Rate Limiting**: Respects OneSignal API rate limits

### Future Enhancements

Potential improvements for the OneSignal integration:

- **User Consent Management**: Add opt-in/opt-out functionality
- **Custom Notification Templates**: Dynamic notification content
- **A/B Testing**: Test different notification messages
- **Advanced Segmentation**: More sophisticated user segmentation
- **Analytics Dashboard**: Built-in notification analytics
- **Scheduled Notifications**: Send follow-up notifications
- **Multi-language Support**: Localized notification messages

## 🧪 Testing

### Running Tests

```bash
# Run all tests
./vendor/bin/sail test

# Run specific test suite
./vendor/bin/sail test tests/Feature/FormTest.php

# Run with verbose output
./vendor/bin/sail test --verbose

# Run specific test
./vendor/bin/sail test --filter="can submit complete form successfully"
```

### Test Coverage

The application includes comprehensive test coverage:

- **34 tests** with **253 assertions**
- **Feature tests** for all form steps
- **Validation tests** for all form fields
- **Error handling tests** for edge cases
- **Email duplication tests**
- **Success/error message tests**
- **OneSignal integration tests**

### Test Structure

```
tests/
├── Feature/
│   ├── FormTest.php                    # Complete form workflow tests
│   └── OneSignalIntegrationTest.php    # OneSignal integration tests
└── Unit/
    └── (Unit tests for models and services)
```

## 🏗️ Architecture

### Technology Stack

- **Backend**: Laravel 11 (PHP 8.1+)
- **Frontend**: Vue.js 3 + Inertia.js
- **Styling**: Tailwind CSS
- **Database**: MySQL 8.0
- **Testing**: Pest PHP
- **Build Tool**: Vite
- **Push Notifications**: OneSignal

### Database Structure

#### Users Table
```sql
users (
  id (primary key)
  name (string)
  email (string, unique)
  company_name (string, nullable)
  website_url (string, nullable)
  website_detail_id (foreign key, nullable)
  password (string)
  email_verified_at (timestamp, nullable)
  remember_token (string, nullable)
  created_at (timestamp)
  updated_at (timestamp)
)
```

#### Website Details Table
```sql
website_details (
  id (primary key)
  name (string)
  slug (string)
  type (string)
  created_at (timestamp)
  updated_at (timestamp)
)
```

### Frontend State Management

The application uses **session-based state management** for form data persistence:

- **Session Storage**: Form data is stored in Laravel sessions
- **Step-by-step Validation**: Each step validates and stores data
- **Data Persistence**: Form data persists across browser sessions
- **Cleanup**: Session data is cleared after successful submission

### Form Flow Architecture

```
Step 1 (Basic Info) → Session Storage → Step 2 (Website Type)
     ↓                                           ↓
Validation                                    Validation
     ↓                                           ↓
Step 2 (Website Type) → Session Storage → Step 3 (Platform)
     ↓                                           ↓
Validation                                    Validation
     ↓                                           ↓
Step 3 (Platform) → Session Storage → Step 4 (Review)
     ↓                                           ↓
Validation                                    Final Submit
     ↓                                           ↓
Step 4 (Review) → Database Storage → OneSignal Notification
     ↓                                           ↓
Success Response ← Notification Status ← API Response
```

### Security Considerations

- **CSRF Protection**: All forms include CSRF tokens
- **Input Validation**: Server-side validation for all inputs
- **Email Uniqueness**: Prevents duplicate email submissions
- **Session Security**: Secure session configuration
- **SQL Injection Prevention**: Eloquent ORM with parameterized queries
- **API Key Security**: OneSignal API keys stored in environment variables

## 🚀 Deployment

### Production Deployment

#### Using Laravel Forge (Recommended)

1. **Server Setup**:
   ```bash
   # Install dependencies
   composer install --optimize-autoloader --no-dev
   npm install --production
   npm run build

   # Set permissions
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

2. **Environment Configuration**:
   ```bash
   # Set production environment
   APP_ENV=production
   APP_DEBUG=false

   # Configure database
   DB_HOST=your-db-host
   DB_DATABASE=your-db-name
   DB_USERNAME=your-db-user
   DB_PASSWORD=your-db-password

   # Configure OneSignal
   ONESIGNAL_APP_ID=your-onesignal-app-id
   ONESIGNAL_REST_API_KEY=your-onesignal-rest-api-key
   ONESIGNAL_USER_AUTH_KEY=your-onesignal-user-auth-key
   ```

3. **Queue Configuration** (if using queues):
   ```bash
   # Start queue worker
   php artisan queue:work --daemon
   ```

#### Using Docker

```bash
# Build and run with Docker Compose
docker-compose up -d

# Or using Laravel Sail
./vendor/bin/sail up -d
```

### Deployment Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Configure OneSignal API keys
- [ ] Run `php artisan migrate`
- [ ] Run `npm run build`
- [ ] Set proper file permissions
- [ ] Configure web server (Nginx/Apache)
- [ ] Set up SSL certificate
- [ ] Configure backup strategy
- [ ] Test OneSignal integration

## 🐳 Docker Deployment

The application includes a complete Docker setup for production deployment with PHP 8.4, Nginx, and MySQL.

### Prerequisites

- Docker and Docker Compose installed
- Production environment variables configured

### Quick Deployment

1. **Prepare Environment Variables**
   ```bash
   cp .env.example .env
   # Edit .env with your production values
   ```

2. **Run Deployment Script**
   ```bash
   ./deploy.sh
   ```

### Manual Deployment

1. **Build and Start Services**
   ```bash
   docker-compose -f docker-compose.prod.yml up --build -d
   ```

2. **Run Database Migrations**
   ```bash
   docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
   ```

3. **Optimize Application**
   ```bash
   docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
   docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
   docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
   ```

### Docker Configuration

#### Production Dockerfile
- **Base Image**: Ubuntu 24.04 (same as Laravel Sail)
- **PHP Version**: 8.4 with all necessary extensions
- **Web Server**: Nginx with PHP-FPM
- **Process Manager**: Supervisor
- **Node.js**: 22.x for asset compilation

#### Services
- **app**: Laravel application with Nginx + PHP-FPM
- **mysql**: MySQL 8.0 database

#### Key Features
- Multi-stage build optimization
- Production-ready Nginx configuration
- Proper file permissions
- Health checks for database
- Automatic restart policies
- Optimized for Laravel + Inertia.js

### Environment Variables

Required environment variables for production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-domain.com

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

ONESIGNAL_APP_ID=your_onesignal_app_id
ONESIGNAL_REST_API_KEY=your_onesignal_rest_api_key
```

### Useful Commands

```bash
# View logs
docker-compose -f docker-compose.prod.yml logs -f

# Stop services
docker-compose -f docker-compose.prod.yml down

# Restart services
docker-compose -f docker-compose.prod.yml restart

# Access application container
docker-compose -f docker-compose.prod.yml exec app bash

# Run artisan commands
docker-compose -f docker-compose.prod.yml exec app php artisan migrate
```

### Production Considerations

1. **SSL/TLS**: Use a reverse proxy (like Traefik) for SSL termination
2. **Database**: Consider using managed database services for production
3. **Storage**: Use persistent volumes for file uploads
4. **Monitoring**: Add monitoring and logging solutions
5. **Backup**: Implement regular database and file backups

## 🤝 Contributing

### Development Workflow

1. **Fork** the repository
2. **Create** a feature branch: `git checkout -b feature/amazing-feature`
3. **Commit** your changes: `git commit -m 'Add amazing feature'`
4. **Push** to the branch: `git push origin feature/amazing-feature`
5. **Open** a Pull Request

### Code Standards

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation for API changes
- Use meaningful commit messages

## 📝 Assumptions and Decisions

### Design Decisions

1. **Session-based State Management**: Chose sessions over database for form data to avoid incomplete submissions cluttering the database
2. **Multi-step Validation**: Each step validates data before proceeding to ensure data integrity
3. **Email Uniqueness**: Prevent duplicate submissions by checking email uniqueness
4. **Responsive Design**: Mobile-first approach for better user experience
5. **Progressive Enhancement**: Form works without JavaScript but enhanced with Vue.js
6. **OneSignal Integration**: Automatic push notifications for better user engagement

### Trade-offs and Compromises

1. **Session Dependency**: Form requires session support, limiting stateless API usage
2. **No Real-time Validation**: Client-side validation is basic; could be enhanced with real-time API calls
3. **Limited Email Integration**: No immediate email notifications; would require additional setup
4. **Simple State Management**: No complex state management library; uses simple session storage
5. **OneSignal Dependency**: Requires OneSignal account and API keys for full functionality

### Future Improvements

With more time, I would implement:

1. **Real-time Validation**: API endpoints for real-time field validation
2. **Email Notifications**: Automatic email notifications on form submission
3. **Admin Dashboard**: Admin interface to view and manage submissions
4. **Analytics Integration**: Google Analytics or similar for form analytics
5. **A/B Testing**: Support for testing different form variations
6. **Export Functionality**: CSV/Excel export of form submissions
7. **API Rate Limiting**: Implement rate limiting for form submissions
8. **Enhanced Security**: Additional security measures like CAPTCHA
9. **Multi-language Support**: Internationalization for multiple languages
10. **Progressive Web App**: PWA features for mobile users
11. **Advanced OneSignal Features**: User consent management, custom templates, A/B testing

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

For support and questions:

- **Email**: support@your-domain.com
- **Issues**: [GitHub Issues](https://github.com/your-username/mobiloud-interview/issues)
- **Documentation**: [Wiki](https://github.com/your-username/mobiloud-interview/wiki)

---

**Built with ❤️ using Laravel, Vue.js, Tailwind CSS, and OneSignal**
