# Multi-Tenancy System - Dusun Management Guide

## Overview
The system now supports **decentralized data management** where each **Kadus (Kepala Dusun)** manages their own dusun's data, while **Kades (Kepala Desa)** and **SuperAdmin** can oversee all dusuns.

## Key Features

### 1. **Dusun Model**
- Each village can have multiple dusuns (Dusun 1, Dusun 2, Dusun 3, etc.)
- Managed via `Admin > Settings > Data Dusun`
- Fields:
  - Name (e.g., "Dusun 1")
  - Code (e.g., "DSN1") - unique identifier
  - Description
  - Active status

### 2. **User-Dusun Assignment**
- **Kadus users** are assigned to specific dusuns
- **Kades/SuperAdmin** have no dusun assignment (can access all)
- Managed via `Admin > Access > Users` → Edit user → "Dusun" field

### 3. **Automatic Data Filtering**

#### For Kadus (Kepala Dusun):
- Only sees/manages data from their assigned dusun
- Cannot see or modify other dusuns' data
- Dusun field is auto-filled and disabled when creating records

#### For Kades/SuperAdmin:
- Sees all data from all dusuns
- Can filter by dusun using table filters
- Must select dusun when creating new records
- Can reassign data between dusuns

### 4. **Affected Resources**
Currently implemented:
- ✅ **Map Points** (`Peta Interaktif`)
  - Each map point belongs to one dusun
  - Kadus can only add/edit points in their dusun
  - Kades sees all points with dusun badge in table

Future KK/Population data will follow the same pattern.

## How to Use

### As Kades/SuperAdmin:
1. **Setup Dusuns**
   - Go to `Admin > Settings > Data Dusun`
   - Add your village's dusuns (Dusun 1, 2, 3, etc.)

2. **Assign Users to Dusuns**
   - Go to `Admin > Access > Users`
   - Edit each Kadus user
   - Select their dusun from dropdown
   - Save

3. **Manage Data**
   - View all data across dusuns
   - Use "Dusun" filter to focus on specific dusun
   - Assign new records to appropriate dusuns

### As Kadus:
1. **Login to Admin Panel**
   - Your dusun is automatically assigned

2. **Add Data (e.g., Map Points)**
   - Go to `Peta Interaktif > Create`
   - Dusun field is pre-filled with your dusun (cannot change)
   - Add location details
   - Submit

3. **View Your Data**
   - Table shows only your dusun's data
   - No visibility into other dusuns

## Technical Implementation

### Models
```php
// Dusun Model
- name, code, description, is_active
- hasMany: users, mapPoints

// User Model
- dusun_id (nullable)
- belongsTo: dusun
- isKadus(): bool
- canAccessAllDusuns(): bool

// MapPoint Model  
- dusun_id (nullable, required in form)
- belongsTo: dusun
- scopeForAuthUser(): filters by user's dusun
```

### Scopes
```php
// Automatic filtering in resources
->modifyQueryUsing(fn($query) => $query->forAuthUser())

// Manual filtering by dusun
MapPoint::forDusun($dusunId)->get()
```

### Form Behavior
```php
Forms\Components\Select::make('dusun_id')
    ->default(fn() => auth()->user()->dusun_id) // Auto-fill for Kadus
    ->disabled(fn() => !auth()->user()->canAccessAllDusuns()) // Lock for Kadus
    ->dehydrated(true) // Submit even when disabled
```

## Adding Multi-Tenancy to New Models

When creating future models (KK, Population, etc.):

1. **Add migration:**
   ```php
   $table->foreignId('dusun_id')->nullable()->constrained('dusuns')->onDelete('cascade');
   ```

2. **Update model:**
   ```php
   protected $fillable = ['dusun_id', ...];
   
   public function dusun() {
       return $this->belongsTo(Dusun::class);
   }
   
   public function scopeForAuthUser($query) {
       $user = auth()->user();
       if ($user && !$user->canAccessAllDusuns()) {
           return $query->where('dusun_id', $user->dusun_id);
       }
       return $query;
   }
   ```

3. **Update Filament Resource:**
   ```php
   // Form
   Forms\Components\Select::make('dusun_id')
       ->relationship('dusun', 'name')
       ->required()
       ->default(fn() => auth()->user()->dusun_id)
       ->disabled(fn() => !auth()->user()->canAccessAllDusuns())
       ->dehydrated(true);
   
   // Table
   public static function table(Table $table) {
       return $table
           ->modifyQueryUsing(fn($query) => $query->forAuthUser())
           ->columns([
               Tables\Columns\TextColumn::make('dusun.name')
                   ->visible(fn() => auth()->user()?->canAccessAllDusuns()),
               ...
           ])
           ->filters([
               Tables\Filters\SelectFilter::make('dusun_id')
                   ->relationship('dusun', 'name')
                   ->visible(fn() => auth()->user()?->canAccessAllDusuns()),
           ]);
   }
   ```

## Benefits
✅ **True decentralization** - Each Kadus manages their own area
✅ **Data isolation** - Kadus cannot interfere with other dusuns
✅ **Centralized oversight** - Kades sees everything for reports
✅ **No data silos** - Single database, easy village-wide statistics
✅ **Scalable** - Add unlimited dusuns without code changes
✅ **Maintainable** - One backup, one migration system

## Future Extensions
- Add RW/RT sub-divisions under each dusun
- Dashboard stats per dusun
- Export data filtered by dusun
- Bulk data import with dusun assignment
