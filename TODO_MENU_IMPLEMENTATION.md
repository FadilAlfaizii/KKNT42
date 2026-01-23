# TODO: Menu Statistik dan Artikel Implementation

## Plan untuk menambahkan menu Statistik dan Artikel beserta fungsinya

### 1. Indonesian Language Files
- [x] Create `lang/id/menu.php` - Indonesian menu translations
- [x] Create `lang/id/page.php` - Indonesian page translations

### 2. Article Model & Controller
- [x] Create `app/Models/Article.php` - Article Eloquent model
- [x] Create `app/Http/Controllers/ArticleController.php` - Article CRUD controller

### 3. Routes (sudah ada, perlu diverifikasi)
- [x] Route `/statistik` -> Statistik page
- [x] Route `/artikel` -> Artikel list page  
- [x] Route `/artikel/{id}` -> Detail artikel page

### 4. Navbar Menu Items (sudah ada, perlu diverifikasi)
- [x] Menu "Statistik" dengan link ke `/statistik`
- [x] Menu "Artikel" dengan link ke `/artikel`

### 5. Page Components (sudah ada, perlu diverifikasi)
- [x] `resources/js/Pages/Statistik.jsx` - Statistik page (enhanced)
- [x] `resources/js/Pages/Artikel.jsx` - Artikel list page
- [x] `resources/js/Pages/DetailArtikel.jsx` - Detail artikel page (enhanced)

### 6. Database untuk Artikel
- [x] Migration `2026_01_15_000001_create_articles_table.php` - created
- [x] Migration `2026_01_16_095316_add_image_color_to_articles_table.php` - created

### 7. Filament Resources for Articles
- [x] `app/Filament/Resources/ArticleResource.php` - created
- [x] `app/Filament/Resources/ArticleResource/Pages/ListArticles.php` - created
- [x] `app/Filament/Resources/ArticleResource/Pages/CreateArticle.php` - created
- [x] `app/Filament/Resources/ArticleResource/Pages/EditArticle.php` - created

### 8. Database Seeders
- [x] `database/seeders/ArticleSeeder.php` - created

## Catatan Penggunaan
- Jalankan `php artisan migrate` untuk membuat tabel articles
- Jalankan `php artisan db:seed --class=ArticleSeeder` untuk seed data sample
- Menu "Statistik" dan "Artikel" sudah ada di Navbar
- Akses manajemen artikel di Filament Admin: `/admin/articles`

