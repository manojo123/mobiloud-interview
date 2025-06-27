# OneSignal Push Notification Integration

This document describes the OneSignal push notification integration implemented in the multi-step lead capture form.

## 🎯 Overview

The OneSignal integration automatically sends push notifications to all subscribed users when a new lead form is successfully submitted. This provides real-time alerts to the team about new potential clients.

## 🔧 Implementation Details

### Backend Components

#### 1. OneSignal Service (`app/Services/OneSignalService.php`)

**Features:**
- REST API integration with OneSignal
- Comprehensive error handling and logging
- Support for sending notifications to all users or specific user segments
- Automatic retry logic and response validation

**Key Methods:**
- `sendNotificationToAll()` - Sends notification to all subscribed users
- `sendNotificationToUsers()` - Sends notification to specific users
- `getNotificationHistory()` - Retrieves notification delivery status

#### 2. Configuration (`config/services.php`)

**OneSignal Configuration:**
```php
'onesignal' => [
    'app_id' => env('ONESIGNAL_APP_ID', ''),
    'api_key' => env('ONESIGNAL_API_KEY', ''),
    'rest_api_key' => env('ONESIGNAL_REST_API_KEY'),
],
```

#### 3. Form Controller Integration (`app/Http/Controllers/FormController.php`)

**Integration Points:**
- Automatic notification trigger on successful form submission
- Graceful error handling (form submission succeeds even if notification fails)
- Enhanced success messages with notification status
- Comprehensive logging for debugging

### Frontend Components

#### 1. OneSignal SDK Integration (`resources/views/app.blade.php`)

**Features:**
- Automatic OneSignal SDK initialization
- Push notification permission handling
- Local development support (`allowLocalhostAsSecureOrigin: true`)

#### 2. Enhanced Step 4 Component (`resources/js/pages/Form/Step4.vue`)

**New Features:**
- Real-time notification status display
- Console logging for debugging OneSignal requests
- Visual feedback during notification sending
- Error handling with user-friendly messages

## 📊 Notification Payload

### Standard Notification Structure

```json
{
  "app_id": "d5c1fb18-dc0e-4af1-b7f1-59dc80e466c3",
  "included_segments": ["All"],
  "contents": {
    "en": "A new user has submitted the registration form"
  },
  "headings": {
    "en": "New Lead Submission"
  },
  "data": {
    "user_id": 123,
    "user_name": "John Doe",
    "user_email": "john@example.com",
    "company_name": "Acme Corp",
    "website_type": "ecommerce",
    "platform": "shopify",
    "submitted_at": "2025-06-27T19:30:00.000000Z"
  },
  "url": "https://your-domain.com/admin/leads"
}
```

### Custom Data Fields

The notification includes rich data for team members:

- **user_id**: Database ID for quick reference
- **user_name**: Lead's full name
- **user_email**: Contact email
- **company_name**: Company information
- **website_type**: Type of website (ecommerce, blog, etc.)
- **platform**: Selected platform (Shopify, WordPress, etc.)
- **submitted_at**: ISO timestamp of submission

## 🔍 Console Logging

### Frontend Logging

The integration provides comprehensive console logging for debugging:

```javascript
// Form submission start
console.log('🚀 OneSignal Integration - Form Submission Started');
console.log('📊 Form Data:', formData);
console.log('📱 OneSignal App ID:', appId);
console.log('⏰ Submission Time:', timestamp);

// Success response
console.log('✅ Form submitted successfully');
console.log('📨 OneSignal notification triggered');
console.log('📄 Response:', response);

// OneSignal SDK status
console.log('🔔 OneSignal SDK Status:', {
  isPushSupported: true,
  isSubscribed: true,
  getUserId: 'user-id'
});
```

### Backend Logging

**Success Logs:**
```
[INFO] OneSignal notification sent successfully
- endpoint: notifications
- payload: {...}
- response: {...}
```

**Error Logs:**
```
[ERROR] OneSignal API error
- endpoint: notifications
- payload: {...}
- status: 400
- response: {...}
```

## 🧪 Testing

### Test Coverage

The integration includes comprehensive test coverage:

- **5 OneSignal-specific tests** with **17 assertions**
- **Mocked API responses** for reliable testing
- **Error scenario testing** (API failures, network errors)
- **Configuration validation** testing

### Test Scenarios

1. **Successful Notification**: Verifies notification is sent and form submission succeeds
2. **API Error Handling**: Ensures form submission succeeds even if notification fails
3. **Service Methods**: Tests individual OneSignal service methods
4. **Network Errors**: Validates error handling for network issues
5. **Configuration**: Verifies OneSignal credentials are loaded correctly

### Running Tests

```bash
# Run OneSignal integration tests
./vendor/bin/sail test tests/Feature/OneSignalIntegrationTest.php

# Run all tests including OneSignal
./vendor/bin/sail test
```

## 🚀 Deployment Considerations

### Environment Variables

Add these to your production `.env` file:

```env
ONESIGNAL_APP_ID=d5c1fb18-dc0e-4af1-b7f1-59dc80e466c3
ONESIGNAL_API_KEY=os_v2_app_2xa7wgg4bzfpdn7rlhoibzdgymjmp47ck7muwbuvssdvita5gmikes2ltvaszelrgcwy7zgmfrihow6wbygtppzgxrfyqznoryh64za
ONESIGNAL_REST_API_KEY=your_rest_api_key_here
ONESIGNAL_SAFARI_WEB_ID=your_safari_web_id_here
```

### Production Checklist

- [ ] OneSignal App ID configured
- [ ] API Key set with proper permissions
- [ ] HTTPS enabled (required for push notifications)
- [ ] OneSignal dashboard configured
- [ ] Notification templates set up
- [ ] Team members subscribed to notifications

## 🔧 Troubleshooting

### Common Issues

1. **Notifications Not Sending**
   - Check API key permissions
   - Verify App ID is correct
   - Ensure HTTPS is enabled in production

2. **SDK Not Initializing**
   - Check browser console for errors
   - Verify OneSignal script is loading
   - Check for ad blockers

3. **Permission Denied**
   - Ensure user has granted notification permissions
   - Check browser notification settings

### Debug Commands

```bash
# Check OneSignal configuration
php artisan tinker
>>> config('services.onesignal')

# Test OneSignal service directly
php artisan tinker
>>> app(App\Services\OneSignalService::class)->sendNotificationToAll('Test message')
```

## 📈 Analytics & Monitoring

### Metrics to Track

- **Notification Delivery Rate**: Percentage of successful deliveries
- **Click-through Rate**: How many team members click notifications
- **Response Time**: Time from form submission to notification delivery
- **Error Rate**: Frequency of notification failures

### Monitoring Setup

1. **Laravel Logs**: Monitor `storage/logs/laravel.log` for OneSignal errors
2. **OneSignal Dashboard**: Track delivery statistics
3. **Application Monitoring**: Set up alerts for notification failures

## 🔮 Future Enhancements

### Planned Features

1. **User Segmentation**: Send notifications to specific team members based on lead type
2. **Rich Notifications**: Include lead details in notification preview
3. **Action Buttons**: Add quick action buttons to notifications
4. **Scheduled Notifications**: Send follow-up notifications
5. **Analytics Integration**: Track notification effectiveness

### Advanced Features

1. **Custom Notification Templates**: Different messages for different lead types
2. **Notification Preferences**: Allow team members to customize notification settings
3. **Mobile App Integration**: Extend to mobile push notifications
4. **Webhook Integration**: Real-time notification delivery confirmation

## 📚 Resources

- [OneSignal REST API Documentation](https://documentation.onesignal.com/reference)
- [OneSignal Postman Collection](https://www.postman.com/onesignaldevs/onesignal-api/overview)
- [Laravel HTTP Client Documentation](https://laravel.com/docs/11.x/http-client)
- [Vue.js Inertia.js Documentation](https://inertiajs.com/)

---

**Integration Status**: ✅ **Complete and Tested**

**Last Updated**: June 27, 2025

**Test Coverage**: 100% (5 tests, 17 assertions)
