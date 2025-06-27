# Multi-Step Lead Capture Form

A modern, responsive multi-step lead capture form built with Laravel, Inertia.js, and Vue.js. This application collects business information from potential clients through an intuitive 4-step process with real-time validation and user feedback.

## 🚀 Live Demo

- **Production URL**: [https://your-domain.com](https://your-domain.com)

## 📋 Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Architecture](#architecture)
- [Deployment](#deployment)
- [Contributing](#contributing)

## ✨ Features

- **Multi-step Form Process**: 4-step lead capture with progress tracking
- **Real-time Validation**: Client-side and server-side validation
- **Email Duplication Prevention**: Prevents duplicate email submissions
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Session Management**: Form data persistence across steps
- **User Feedback**: Success and error messages with proper UX
- **Comprehensive Testing**: Full test coverage with Pest PHP

### Form Steps

1. **Basic Information**: Name, email, company name, website URL (optional)
2. **Website Details**: Website type selection (E-commerce, Blog, Corporate, etc.)
3. **Platform Selection**: Conditional platform options based on website type
4. **Review & Submit**: Summary review with edit capabilities

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
- Success: Redirect to `/form` with success message
- Error: Redirect to `/form/step1` with error message

### Error Responses

All endpoints return appropriate HTTP status codes:

- `200`: Success
- `302`: Redirect (form submission)
- `422`: Validation errors
- `500`: Server error

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

### Test Structure

```
tests/
├── Feature/
│   └── FormTest.php          # Complete form workflow tests
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
Step 4 (Review) → Database Storage → Success/Error Response
```

### Security Considerations

- **CSRF Protection**: All forms include CSRF tokens
- **Input Validation**: Server-side validation for all inputs
- **Email Uniqueness**: Prevents duplicate email submissions
- **Session Security**: Secure session configuration
- **SQL Injection Prevention**: Eloquent ORM with parameterized queries

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
- [ ] Run `php artisan migrate`
- [ ] Run `npm run build`
- [ ] Set proper file permissions
- [ ] Configure web server (Nginx/Apache)
- [ ] Set up SSL certificate
- [ ] Configure backup strategy

## 🔄 OneSignal Integration (Future Enhancement)

### Planned Integration Approach

The application is designed to support OneSignal push notification integration:

1. **User Consent**: Add consent checkbox in step 1
2. **OneSignal SDK**: Integrate OneSignal JavaScript SDK
3. **Notification Triggers**: Send notifications on form completion
4. **User Segmentation**: Segment users by website type/platform

### Implementation Notes

- OneSignal integration would be added as a future enhancement
- User consent would be collected in the form
- Notifications would be sent for successful submissions
- User data would be synced with OneSignal for segmentation

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

### Trade-offs and Compromises

1. **Session Dependency**: Form requires session support, limiting stateless API usage
2. **No Real-time Validation**: Client-side validation is basic; could be enhanced with real-time API calls
3. **Limited Email Integration**: No immediate email notifications; would require additional setup
4. **Simple State Management**: No complex state management library; uses simple session storage

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

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

For support and questions:

- **Email**: support@your-domain.com
- **Issues**: [GitHub Issues](https://github.com/your-username/mobiloud-interview/issues)
- **Documentation**: [Wiki](https://github.com/your-username/mobiloud-interview/wiki)

---

**Built with ❤️ using Laravel, Vue.js, and Tailwind CSS**
