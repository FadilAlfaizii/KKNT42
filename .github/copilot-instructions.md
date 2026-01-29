# AI Coding Agent Instructions - Village Information System (SID)

## Project Overview
This is a Laravel 11 + Filament 3 + Inertia.js (React) hybrid application for village (desa) information management. The backend is Filament-based admin panel at `/admin`, while the public frontend uses Inertia.js with React components.

**Key Tech Stack:**
- **Backend:** Laravel 11, Filament 3 Admin Panel, Spatie packages (Media Library, Settings, Tags, Permissions)
- **Frontend:** React 19, Inertia.js, TailwindCSS with custom themes, Leaflet maps, Chart.js
- **Database:** MySQL with standard Laravel migrations
- **Auth:** Filament Breezy (profile management), Filament Shield (role-based permissions)

## Architecture & Data Flow

### Dual-Frontend Architecture
1. **Admin Panel (`/admin`)**: Filament 3 resources at `app/Filament/Resources/` - uses Blade/Livewire
2. **Public Frontend (`/`)**: Inertia.js pages at `resources/js/Pages/` - uses React components

Routes split by purpose:
- `routes/web.php`: Public Inertia routes (Home, Artikel, Dashboard, PetaInteraktif, etc.)
- Admin routes auto-registered by Filament via `AdminPanelProvider`

### Key Models & Resources
- **Article** (`app/Models/Article.php`): Auto-generates slugs from title, has published scope, image_url accessor
  - Resource: `app/Filament/Resources/ArticleResource.php` with RichEditor, FileUpload
- **MapPoint** (`app/Models/MapPoint.php`): Geo-located points for interactive map
  - Uses Filament Map Picker plugin (`dotswan/filament-map-picker`)
- **User**: Extended with Filament Shield roles/permissions via Spatie Permission package

### Settings Management
- Uses `spatie/laravel-settings` plugin integrated with Filament
- `GeneralSettings` controls brand logo, theme colors, site favicon (see `AdminPanelProvider`)
- Settings accessible via dependency injection: `fn (GeneralSettings $settings) => $settings->brand_name`

## Development Workflows

### Initial Setup
```bash
# Clone and setup
cp .env.example .env
composer install && npm install
php artisan key:generate

# Database setup
php artisan migrate:fresh --seed
# Seeds: RolesAndPermissionsSeeder, UsersTableSeeder, auto-runs shield:generate

# Build assets
npm run dev  # or npm run build for production

# Cache icons (recommended for performance)
php artisan icons:cache
```

**Default Admin Credentials:**
- Email: `superadmin@starter-kit.com`
- Password: `superadmin`

### Asset Compilation
- Vite config (`vite.config.js`) builds 3 entry points:
  1. `resources/css/app.css` (public frontend)
  2. `resources/js/app.jsx` (Inertia React app)
  3. `resources/css/filament/admin/theme.css` (Filament admin theme)

### Creating Filament Resources
Follow Filament conventions:
```bash
php artisan make:filament-resource ModelName --generate
```
- Add to navigation groups in `AdminPanelProvider->panel()` under `navigationGroups`
- Use Section components for grouping form fields (see `ArticleResource` example)
- For media uploads: Use `FileUpload::make()` with `->directory('folder_name')`

### Creating Public Pages
1. Add route in `routes/web.php` with Inertia::render()
2. Create React component in `resources/js/Pages/ComponentName.jsx`
3. Use `resources/js/Layouts/` for shared layouts (NavBar, Footer)

## Project-Specific Conventions

### Theme System (Tailwind)
**Two distinct theme palettes in `tailwind.config.js`:**

1. **Homepage/Public** (Forest/Natural Green):
   - `forest-*`: Primary green (#16a34a at 600)
   - `sage-*`: Muted green accents
   - `wood-*`: Warm earth tones

2. **Dashboard/Admin** (Modern Blue):
   - `primary-*`: Blue (#2563eb at 600)
   - `accent-*`: Cyan (#0284c7 at 600)
   - `nature-*`: Green for positive metrics

**Usage Pattern:** Homepage uses forest/sage colors; Dashboard uses primary/accent. See `BLUE_THEME_GUIDE.md` and `DASHBOARD_GUIDE.md` for detailed design specs.

### Slug Auto-Generation Pattern
Models with slugs (e.g., `Article`) use boot hooks:
```php
static::creating(fn($model) => $model->slug = static::generateUniqueSlug($model->title));
```
Never manually set slugs in forms - handled automatically.

### Image Handling
- Uses Spatie Media Library for advanced media (not all models)
- Simple images use Laravel storage with accessors: `protected $appends = ['image_url'];`
- Example: `Article->image_url` accessor constructs `Storage::url($this->image)`

### Permission System
- Filament Shield generates permissions per resource automatically
- SuperAdmin check: `$role == config('filament-shield.super_admin.name')` (see `AppServiceProvider`)
- Seeder runs `shield:generate --all` after role/user creation
- Navigation items can use `->visible(fn() => auth()->user()->can('access_log_viewer'))`

## Integration Points

### Filament Plugins
Configured in `AdminPanelProvider->plugins()`:
- **FilamentShield**: Role-based access control (auto-generates permissions)
- **FilamentBreezy**: User profile management with avatars
- **FilamentExceptions**: Exception tracking in admin
- **FilamentMediaManager**: Media browser with subfolder support
- **FilamentMenuBuilder**: Dynamic menu management (MenuResource)

### Map Integration
- Frontend: React Leaflet (`react-leaflet` + `leaflet` packages)
- Backend: Filament Map Picker for lat/lng input
- Data source: `MapPoint` model with categories (Office, Education, Health, etc.)
- Controller: `MapController` fetches all points for frontend map

### Chart/Stats
- Frontend: `chart.js` + `react-chartjs-2` for Dashboard visualizations
- See `Dashboard.jsx` for demographics (bar chart) and education (donut chart) examples
- Backend stats: Query Eloquent models in route closures (see `/dashboard` route)

## Common Pitfalls

1. **Don't edit public routes in Filament resources** - Filament is admin-only. Public pages go through `web.php` + Inertia
2. **Vite asset references:** Use `@vite(['resources/css/app.css', 'resources/js/app.jsx'])` in Blade, or reference via Inertia page props
3. **Shield permissions:** After creating new resources, run `php artisan shield:generate --all` to create policies
4. **Leaflet CSS:** Already imported in `app.jsx` globally - don't re-import in components
5. **Inertia page resolution:** Components must be in `resources/js/Pages/` - uses glob pattern `./Pages/**/*.jsx`

## Key Files Reference
- **Admin config:** `app/Providers/Filament/AdminPanelProvider.php` (panel setup, plugins, navigation)
- **Public routes:** `routes/web.php` (Inertia page routes)
- **Theme config:** `tailwind.config.js` (dual color schemes)
- **Settings model:** `app/Settings/GeneralSettings.php` (site branding/theme)
- **React entry:** `resources/js/app.jsx` (Inertia setup)
- **Design docs:** `BLUE_THEME_GUIDE.md`, `DASHBOARD_GUIDE.md` (UI/UX specifications)

## Testing & Quality
- PHPUnit configured: `phpunit.xml`
- Code style: Laravel Pint (run `./vendor/bin/pint`)
- Test structure: `tests/Feature/` and `tests/Unit/`
