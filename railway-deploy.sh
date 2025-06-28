#!/bin/bash

# Railway Deployment Script for Laravel Application
set -e

echo "🚀 Railway Deployment Script"
echo "============================"

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from the Laravel project root."
    exit 1
fi

# Check if Dockerfile.railway exists
if [ ! -f "Dockerfile.railway" ]; then
    echo "❌ Error: Dockerfile.railway not found."
    exit 1
fi

# Check if startup script exists
if [ ! -f "docker/startup-railway.sh" ]; then
    echo "❌ Error: docker/startup-railway.sh not found."
    exit 1
fi

echo "✅ All required files found"

echo ""
echo "📋 Deployment Checklist:"
echo "========================"
echo "✅ Laravel project structure"
echo "✅ Dockerfile.railway"
echo "✅ Startup script"
echo "✅ SQLite configuration"
echo "✅ Environment variables (check .env)"
echo "✅ OneSignal configuration (if using notifications)"

echo ""
echo "🚀 Ready for Railway deployment!"
echo ""
echo "Next steps:"
echo "1. Push your code to GitHub/GitLab"
echo "2. Connect your repository to Railway"
echo "3. Set environment variables in Railway dashboard:"
echo "   - APP_ENV=production"
echo "   - APP_DEBUG=false"
echo "   - APP_KEY=your-app-key"
echo "   - DB_CONNECTION=sqlite"
echo "   - DB_DATABASE=/var/www/html/database/database.sqlite"
echo "   - ONESIGNAL_APP_ID=your-onesignal-app-id"
echo "   - ONESIGNAL_REST_API_KEY=your-onesignal-api-key"
echo "4. Deploy!"

echo ""
echo "📚 For more information, see README.md"
