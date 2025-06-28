#!/bin/bash

# Test Docker Build Script for Railway
set -e

echo "🧪 Testing Railway Docker build locally..."

# Check if required files exist
echo "📁 Checking required files..."
required_files=(
    "artisan"
    "composer.json"
    "composer.lock"
    "package.json"
    "package-lock.json"
    "docker/startup-railway.sh"
)

for file in "${required_files[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file exists"
    else
        echo "❌ $file missing!"
        exit 1
    fi
done

# Test Railway Docker build
echo "🔨 Testing Railway Docker build..."
docker build -f Dockerfile.railway -t laravel-railway-test .

if [ $? -eq 0 ]; then
    echo "✅ Railway Docker build successful!"
    echo "🧹 Cleaning up test image..."
    docker rmi laravel-railway-test
    echo "✅ Ready for Railway deployment!"
else
    echo "❌ Railway Docker build failed!"
    exit 1
fi
