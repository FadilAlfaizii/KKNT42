# Fix: Dusun Normalization (Roman to Arabic Numerals)

**Date:** January 2025  
**Issue:** Duplicate dusun entries & kadus data visibility problems

## Problem Statement

### Root Cause

1. **KK documents** use Roman numerals: "DUSUN I", "DUSUN III", "DUSUN VII"
2. **Database seeder** created Arabic format: "Dusun 1", "Dusun 2", "Dusun 3"
3. **Import service** used exact string match → created duplicates
4. **Kadus users** had `dusun_id = NULL` → couldn't see any data
5. **Query filtering** failed due to name mismatch

### Impact

- Duplicate dusun entries (Roman + Arabic)
- Kadus users couldn't access their assigned area data
- Data fragmentation across different dusun records
- Confusion between "DUSUN I" vs "Dusun 1"

## Solution

### 1. Import Service Enhancement

**File:** `app/Services/KKExtraction/KKImportService.php`

Added `normalizeDusunName()` method that:

- Trims whitespace
- Converts Roman numerals (I-XV) to Arabic (1-15)
- Standardizes format to "Dusun {number}"
- Handles case-insensitive matching

```php
private function normalizeDusunName(string $name): string
{
    // Roman to Arabic mapping
    $romanToArabic = [
        'I' => '1', 'II' => '2', 'III' => '3', 'IV' => '4', 'V' => '5',
        // ... up to XV
    ];

    // Pattern matching: "DUSUN I" → "Dusun 1"
    foreach ($romanToArabic as $roman => $arabic) {
        if (preg_match('/^dusun\s+' . $roman . '$/i', $name)) {
            return 'Dusun ' . $arabic;
        }
    }

    return $name;
}
```

### 2. Cleanup Seeder

**File:** `database/seeders/CleanupDusunDataSeeder.php`

Three-step process:

1. **Merge duplicates:** Roman dusuns → Arabic dusuns
2. **Update FKs:** Move all keluargas/penduduks/users to Arabic dusun
3. **Assign kadus:** Link kadus users to their proper dusun_id

**Execution:**

```bash
php artisan db:seed --class=CleanupDusunDataSeeder
```

**Results:**

- ✅ Merged 4 duplicate dusuns (DUSUN I, III, VII, IX)
- ✅ Assigned 14 kadus users to their respective dusun
- ✅ Reduced from 19 to 15 dusuns total

### 3. Unit Tests

**File:** `tests/Feature/DusunNormalizationTest.php`

Test coverage:

- ✅ Roman → Arabic conversion (7 test cases)
- ✅ Duplicate prevention on import
- ✅ Existing dusun reuse

## Implementation Guide

### Step 1: Apply Code Changes

```bash
# Code already updated in:
# - app/Services/KKExtraction/KKImportService.php (normalization logic)
```

### Step 2: Run Cleanup

```bash
# Clean existing data
php artisan db:seed --class=CleanupDusunDataSeeder
```

### Step 3: Verify

```bash
# Run tests
php artisan test --filter=DusunNormalizationTest

# Check database state
sqlite3 database/database.sqlite "SELECT name FROM dusuns ORDER BY name"
```

## Test Cases

### Normalization Examples

| Input        | Output    |
| ------------ | --------- |
| `DUSUN I`    | `Dusun 1` |
| `DUSUN III ` | `Dusun 3` |
| `dusun ix`   | `Dusun 9` |
| `Dusun 1`    | `Dusun 1` |

### Import Behavior

**Before fix:**

```
Input: "DUSUN VII" → Creates NEW dusun (duplicate)
Result: 2 entries: "Dusun 7" (ID 11) + "DUSUN VII" (ID 21)
```

**After fix:**

```
Input: "DUSUN VII" → Normalized to "Dusun 7" → Reuses existing (ID 11)
Result: 1 entry: "Dusun 7" (ID 11)
```

## Database State

### Before Cleanup

```
dusuns table:
- Dusun 1-14 (IDs 5-18, from seeder)
- DUSUN I, III, VII, IX (IDs 20-23, from import)
Total: 19 dusuns

users table:
- All kadus: dusun_id = NULL
```

### After Cleanup

```
dusuns table:
- Dusun 1-14 (IDs 5-18)
- Dusun Test (ID 19)
Total: 15 dusuns

users table:
- dusun01@sindanganom.id → dusun_id = 5 (Dusun 1)
- dusun02@sindanganom.id → dusun_id = 6 (Dusun 2)
- ... (14 kadus assigned)
```

## Kadus User Assignments

| Email                  | Dusun ID | Dusun Name |
| ---------------------- | -------- | ---------- |
| dusun01@sindanganom.id | 5        | Dusun 1    |
| dusun02@sindanganom.id | 6        | Dusun 2    |
| dusun03@sindanganom.id | 7        | Dusun 3    |
| ...                    | ...      | ...        |
| dusun14@sindanganom.id | 18       | Dusun 14   |

**Password for all kadus:** `password`

## Query Scope Behavior

### forAuthUser() Scope

**File:** `app/Models/Penduduk.php`

```php
public function scopeForAuthUser($query)
{
    $user = auth()->user();

    if ($user->hasRole('kadus')) {
        // Filter by user's assigned dusun_id
        return $query->where('dusun_id', $user->dusun_id);
    }

    return $query;
}
```

**Now works correctly because:**

- Kadus has valid `dusun_id` (not NULL)
- Dusun names are consistent (all Arabic)
- New imports auto-normalize to Arabic format

## Future Imports

### Automatic Handling

All future KK imports will:

1. Extract dusun name (e.g., "DUSUN V")
2. Normalize to "Dusun 5"
3. Find existing "Dusun 5" record
4. Reuse existing dusun (no duplicates)

### Supported Formats

- ✅ `DUSUN I` → `Dusun 1`
- ✅ `DUSUN III ` (with spaces) → `Dusun 3`
- ✅ `dusun ix` (lowercase) → `Dusun 9`
- ✅ `Dusun 7` (already correct) → `Dusun 7`

## Rollback (If Needed)

If issues occur:

```bash
# Restore database from backup
cp database/database.sqlite.backup database/database.sqlite

# Re-run migrations
php artisan migrate:fresh --seed
```

## Related Files

1. **Service:** `app/Services/KKExtraction/KKImportService.php`
2. **Seeder:** `database/seeders/CleanupDusunDataSeeder.php`
3. **Test:** `tests/Feature/DusunNormalizationTest.php`
4. **Model:** `app/Models/Dusun.php`
5. **Scope:** `app/Models/Penduduk.php` (forAuthUser)

## Verification Checklist

- [x] Normalization logic implemented
- [x] Cleanup seeder executed successfully
- [x] Unit tests passing (2 tests, 11 assertions)
- [x] Kadus users assigned to dusun
- [x] No duplicate dusuns in database
- [x] Future imports will auto-normalize

## Notes

1. **Roman Numerals:** Support up to XV (15)
2. **Case-Insensitive:** Works with DUSUN, Dusun, dusun
3. **Whitespace:** Automatically trimmed
4. **Backwards Compatible:** Existing Arabic names unchanged

## Testing Instructions

1. **Test normalization:**

    ```bash
    php artisan test --filter=DusunNormalizationTest
    ```

2. **Test kadus login:**
    - Login: `dusun01@sindanganom.id` / `password`
    - Navigate to Kependudukan menu
    - Should see only Dusun 1 data

3. **Test import:**
    - Upload KK with "DUSUN VII"
    - Check database: Should merge to "Dusun 7" (not create new)

## Success Criteria

✅ All tests pass  
✅ No duplicate dusuns  
✅ Kadus can see their assigned data  
✅ Future imports auto-normalize  
✅ Consistent naming across system
