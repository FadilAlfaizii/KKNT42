# Livestock Catalog Management System - AI Agent Instructions

## Architecture Overview

This is a **dual-interface Laravel application** combining:
- **Filament v3 admin panel** (`/admin`) - Full CRUD management with role-based access control
- **React + Inertia.js public frontend** - Public-facing livestock catalog with farm and user profiles

**Core Domain**: Livestock farm management with IoT sensor monitoring (temperature, humidity, ammonia, light intensity).

## Key Technologies

- **Backend**: Laravel 11 (PHP 8.2+), Filament v3 panels
- **Frontend**: React 19 with Inertia.js (SSR-style), Vite, Tailwind CSS
- **Auth & Permissions**: Filament Shield (Spatie permissions), Filament Breezy (profile management)
- **Notable Packages**: Filament exceptions tracking, media library, settings management, map picker

## Critical Patterns & Conventions

### 1. UUID Primary Keys
All models use UUIDs instead of auto-increment IDs via `HasUuids` trait:
```php
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Farm extends Model {
    use HasUuids;
    public $timestamps = false; // Many models disable timestamps
}
```

### 2. Dual Route Structure
- **Web routes** (`routes/web.php`): Inertia controllers returning `Inertia::render()` for public pages
- **API routes** (`routes/api.php`): RESTful API with separate controllers in `app/Http/Controllers/Api/`
- **Admin**: Filament auto-manages at `/admin` via `app/Filament/Resources/`

### 3. Filament Resource Conventions
- Resources in `app/Filament/Resources/` define CRUD interfaces
- Forms use custom field components like `Dotswan\MapPicker\Fields\Map` for coordinates
- **Authorization**: Controlled by policies (`app/Policies/`) + Filament Shield roles
- Global table defaults configured in `AppServiceProvider`:
  ```php
  Table::configureUsing(fn (Table $table) => $table
      ->emptyStateHeading('No data yet')
      ->defaultSort('created_at', 'desc'));
  ```

### 4. Inertia.js Pages
- React components in `resources/js/Pages/` (e.g., `Home.jsx`, `Peternakan.jsx`)
- Controllers pass data via `Inertia::render('PageName', ['data' => $data])`
- Shared layout: `resources/js/Layouts/Layout.jsx`

### 5. Model Relationships
```php
// Farm -> User (belongsTo), IotSensors (hasMany)
Farm::with(['user', 'iot_sensors'])->get();
```

## Essential Development Commands

### Setup (From README.md)
```bash
cp .env.example .env
# Edit .env: Fix APP_URL (defaults to malformed "http://")
# Set DB_CONNECTION=sqlite or configure MySQL
touch database/database.sqlite  # If using SQLite
php artisan key:generate
php artisan migrate:fresh --seed
npm install && npm run dev  # or npm run build for production
php artisan serve
```

**Default superadmin**: `superadmin@starter-kit.com` / `superadmin`

**Common setup gotchas**:
- `.env.example` has malformed `APP_URL=http://` (missing host) - must set to `http://localhost:8000`
- SQLite database file must be created manually: `touch database/database.sqlite`
- Composer dependencies must be installed first: `composer install`
- **npm install requires `--legacy-peer-deps`** flag due to React 19 peer dependency conflicts with older packages

### Filament Performance Optimization
```bash
php artisan icons:cache        # Cache FluentUI icons (required for production)
php artisan filament:upgrade   # Auto-runs after composer update
```

### Key Seeders
- `UsersTableSeeder`: Creates superadmin + 10 users per role with `shield:super-admin` binding
- `RolesAndPermissionsSeeder`: Initializes Filament Shield roles/permissions

### Building Frontend
```bash
npm install --legacy-peer-deps  # Required: use legacy flag for React 19
npm run dev                      # Development with HMR
npm run build                    # Production build for resources/js/app.jsx and resources/css/app.css
```

## Database Schema Specifics

- **Farms**: Store location (address + lat/lng), capacity, pan dimensions (`pan_width`, `pan_height`, `pan_length`)
- **IotSensors**: Linked to farms via `farm_id`, stores real-time environmental data
- **Users**: Extended with `fullname`, `phone_number`, `bio` (not standard Laravel fields)

### Data Storage & Seeding

**Critical**: The default seeders (`RolesAndPermissionsSeeder`, `UsersTableSeeder`) only create users, roles, and permissions. **NO farm or IoT sensor data is seeded by default.**

**Data Location**:
- **Database**: `database/database.sqlite` (SQLite default) or MySQL/PostgreSQL as configured in `.env`
- **User Data**: Created by `UsersTableSeeder` - generates 1 superadmin + 10 users per role (farmer, etc.)
- **Farm Data**: Must be created manually via Filament admin panel (`/admin`) or API endpoints
- **IoT Sensor Data**: Must be posted via API (`POST /api/iot-sensors`) or created through admin panel

**To populate farm data**:
```bash
# Option 1: Use Filament admin panel at /admin (login as superadmin)
# Navigate to Farms resource and create records

# Option 2: Use the API
curl -X POST http://localhost:8000/api/farms \
  -H "Content-Type: application/json" \
  -d '{"user_id":"uuid","name":"Farm Name","location":"Address",...}'

# Option 3: Create a custom seeder
php artisan make:seeder FarmSeeder
```

**Database file locations**:
- SQLite: `database/database.sqlite` (binary file, use `sqlite3` command or DB tools to inspect)
- Uploads: `storage/app/public/` (farm images, etc.) - symlinked to `public/storage`
- Media library: `storage/media-library/` (managed by Spatie Media Library)

## Authorization & Access Control

- **Filament Shield**: Super admin role (`super_admin`) bypasses all policies
- **Policies**: All resources have policies in `app/Policies/` (e.g., `FarmPolicy`, `UserPolicy`)
- **LogViewer**: Restricted to super_admin role via `LogViewer::auth()` in `AppServiceProvider`

## File Organization

- **Filament Resources**: `app/Filament/Resources/{ModelName}Resource.php`
- **React Components**: `resources/js/component/` (shared), `resources/js/Pages/` (page-level)
- **API Controllers**: `app/Http/Controllers/Api/Api{ModelName}Controller.php`
- **Livewire**: Custom components in `app/Livewire/` (e.g., `MyProfileExtended.php`)

## Common Gotchas

1. **Timestamps disabled**: Many models have `public $timestamps = false;` - don't rely on `created_at`/`updated_at`
2. **Map coordinates**: Forms use `Map::make('location')` from `dotswan/filament-map-picker` - updates `latitude`/`longitude` fields
3. **Vite manifest**: Frontend changes require `npm run dev` or `build` - PHP alone won't reload assets
4. **Shield permissions**: After adding Filament resources, run `php artisan shield:generate` to create permissions
5. **Inertia props**: Controllers must eager-load relations (`->with()`) or frontend will miss data

## Testing & Debugging

- **Logs**: Check `storage/logs/` or use Filament's log-viewer at `/admin` (requires super_admin)
- **Filament Exceptions**: Tracks errors at `/admin` via `filament-exceptions` package
- **Activity Log**: User actions logged via `spatie/laravel-activitylog` (configured in `config/activitylog.php`)

## Multi-Language Support

- Translations in `lang/` (ar, en, es, fr, pt-BR, pt-PT, zh-CN, zh-TW)
- Filament respects Laravel's `app.locale` setting

---

**When implementing features**: Always check if a Filament package already handles the requirement (Shield for permissions, Breezy for profiles, etc.). Follow the existing UUID + policy + resource pattern for new models.
