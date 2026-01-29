# PDF Kartu Keluarga Extraction - Integration Guide

## Overview
This document explains the integration of the "Ekstrak PDF Kartu Keluarga" feature into the Laravel/Filament admin panel.

## What Was Added

### 1. Filament Custom Page
- **Location:** `app/Filament/Pages/EkstrakKartuKeluarga.php`
- **URL:** `/admin/ekstrak-kartu-keluarga`
- **Navigation:** Appears in "Content" group in admin sidebar
- **Icon:** Document text icon

### 2. Blade View Template
- **Location:** `resources/views/filament/pages/ekstrak-kartu-keluarga.blade.php`
- Embeds the React application into the Filament admin panel
- Uses `#ekstrak-kk-root` as mount point for React app

### 3. Vite Configuration Updates
- **File:** `vite.config.js`
- Added entry point: `ekstrak-pdf-kartu-keluarga/index.tsx`
- Added Gemini API key to environment defines
- Added alias: `@ekstrak` → `./ekstrak-pdf-kartu-keluarga/`

### 4. Dependencies Added
New packages in `package.json`:
- `@google/genai` - Gemini AI integration
- `jszip` - ZIP file handling
- `pdfjs-dist` - PDF parsing
- `xlsx` - Excel export
- `typescript` & `@types/node` - TypeScript support

### 5. Environment Configuration
- **File:** `.env.example`
- Added: `GEMINI_API_KEY` (required for AI extraction)
- Get your key from: https://aistudio.google.com/app/apikey

### 6. React Entry Point
- **File:** `ekstrak-pdf-kartu-keluarga/index.tsx`
- Modified to support both standalone and embedded modes
- Mounts to `#ekstrak-kk-root` (Filament) or `#root` (standalone)

### 7. Styling
- **File:** `ekstrak-pdf-kartu-keluarga/index.css`
- Created CSS file with Tailwind imports and custom animations
- Works seamlessly with Filament's existing styles

## Setup Instructions

### 1. Install Dependencies
```bash
npm install
```

### 2. Add Gemini API Key
```bash
# Copy .env.example to .env if you haven't already
cp .env.example .env

# Edit .env and add your Gemini API key:
# GEMINI_API_KEY=your_api_key_here
```

Get your API key from: https://aistudio.google.com/app/apikey

### 3. Build Assets
```bash
# Development mode with hot reload
npm run dev

# Production build
npm run build
```

### 4. Access the Feature
1. Log into admin panel: `/admin`
2. Navigate to **Content** → **Ekstrak Kartu Keluarga**
3. Upload PDF files of Indonesian Family Cards (Kartu Keluarga)
4. Choose extraction mode:
   - **AI Mode**: Uses Gemini AI for smart extraction
   - **Manual Mode**: Uses pattern-based parsing
5. Download results as Excel file

## Features

### AI-Powered Extraction
- Automatically extracts family data from KK PDFs
- Recognizes various KK formats
- Extracts child data, parent info, and addresses

### Flexible Upload
- Single PDF files
- Multiple PDF files
- ZIP archives containing PDFs

### Results Display
- Structured table view
- Tab navigation for multiple files
- Excel export (`.xlsx`)

### Error Handling
- Per-file error reporting
- Graceful fallback for failed extractions
- Progress indicators during processing

## Permissions

By default, all authenticated admin users can access this feature. To restrict access:

1. Go to **Settings** → **Roles & Permissions**
2. Edit the desired role
3. Find `view_ekstrak::kartu::keluarga` permission (auto-generated)
4. Toggle access as needed

## Troubleshooting

### Build Errors
If you encounter TypeScript errors:
```bash
# Ensure TypeScript is installed
npm install -D typescript @types/node

# Clean cache and rebuild
rm -rf node_modules/.vite
npm run build
```

### API Key Issues
- Verify `GEMINI_API_KEY` is set in `.env`
- Ensure key is valid (test at AI Studio)
- Check browser console for API errors

### PDF Processing Fails
- AI mode requires valid API key
- Try Manual mode as fallback
- Check PDF is valid Indonesian Kartu Keluarga format
- Ensure PDF is not encrypted/password-protected

### Styling Issues
If Tailwind styles conflict:
```bash
# Rebuild with fresh cache
npm run build
php artisan optimize:clear
```

## Technical Notes

### Dual-Mode Operation
The React app works in two contexts:
1. **Standalone** (`ekstrak-pdf-kartu-keluarga/index.html`)
2. **Embedded** (Filament page at `/admin/ekstrak-kartu-keluarga`)

### Environment Variables
Gemini API key is exposed to frontend via Vite's `define` option:
```js
define: {
    'process.env.GEMINI_API_KEY': JSON.stringify(env.GEMINI_API_KEY)
}
```

### Asset Loading
Filament page uses `@vite()` directive to load compiled assets:
```blade
@vite(['ekstrak-pdf-kartu-keluarga/index.tsx'])
```

## Future Enhancements

Potential improvements:
- [ ] Backend API for processing (move AI logic server-side)
- [ ] Database storage of extracted data
- [ ] Batch processing queue for large uploads
- [ ] Export to multiple formats (CSV, JSON)
- [ ] OCR fallback for scanned documents
- [ ] Integration with existing resident/family data models

## Support

For issues specific to:
- **Filament integration**: Check [BLUE_THEME_GUIDE.md](../BLUE_THEME_GUIDE.md)
- **PDF extraction logic**: See `ekstrak-pdf-kartu-keluarga/README.md`
- **Gemini AI**: Visit https://ai.google.dev/docs

