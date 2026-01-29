#!/bin/bash
# Quick setup script for Ekstrak Kartu Keluarga integration

echo "🚀 Setting up Ekstrak Kartu Keluarga integration..."
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found. Creating from .env.example..."
    cp .env.example .env
    echo "✅ Created .env file"
fi

# Check if GEMINI_API_KEY is set
if ! grep -q "GEMINI_API_KEY=" .env; then
    echo "📝 Adding GEMINI_API_KEY to .env..."
    echo "" >> .env
    echo "# Gemini API Key for PDF Extraction" >> .env
    echo "GEMINI_API_KEY=" >> .env
    echo "✅ Added GEMINI_API_KEY placeholder to .env"
fi

# Check if API key has a value
API_KEY=$(grep "GEMINI_API_KEY=" .env | cut -d '=' -f2)
if [ -z "$API_KEY" ]; then
    echo ""
    echo "⚠️  GEMINI_API_KEY is not set in .env file!"
    echo "📋 To get your API key:"
    echo "   1. Visit: https://aistudio.google.com/app/apikey"
    echo "   2. Create/copy your API key"
    echo "   3. Add it to .env: GEMINI_API_KEY=your_api_key_here"
    echo ""
fi

# Check if dependencies are installed
if [ ! -d "node_modules" ] || [ ! -d "node_modules/@google" ]; then
    echo "📦 Installing dependencies..."
    npm install
    echo "✅ Dependencies installed"
else
    echo "✅ Dependencies already installed"
fi

# Build assets
echo "🔨 Building assets..."
npm run build
echo "✅ Assets built successfully"

echo ""
echo "✨ Integration complete!"
echo ""
echo "📖 Next steps:"
echo "   1. Make sure GEMINI_API_KEY is set in .env"
echo "   2. Start the development server: php artisan serve"
echo "   3. In another terminal: npm run dev"
echo "   4. Login to admin panel: http://localhost:8000/admin"
echo "   5. Navigate to: Content > Ekstrak Kartu Keluarga"
echo ""
echo "📚 For detailed documentation, see: EKSTRAK_KK_INTEGRATION.md"
echo ""
