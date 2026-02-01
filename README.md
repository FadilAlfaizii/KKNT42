#### Getting Started

Create project with this composer command:

```bash
git clone https://github.com/EgiStr/livestock_kambing_management_kkn_sidang_anom.git
```

Setup your env:

```bash
cd livestock_kambing_management_kkn_sidang_anom
cp .env.example .env
```

setup your env file, and run this command:

````bash
Run migration & seeder:

```bash
php artisan migrate
php artisan db:seed
````

> **Note:** Database seeder will automatically import:
>
> - 5 Dusuns (administrative regions)
> - 4 User roles (SuperAdmin, Kades, Kadus, RT)
> - Default users with permissions
> - **55 Map Points** from `Data Lokasi Desa Sindang Anom.csv` (UMKM, Pendidikan, Ibadah, Olahraga, Kesehatan)

<p align="center">or</p>

```bash
php artisan migrate:fresh --seed
```

Generate key:

```bash
php artisan key:generate
```

Run :

```bash
npm run dev
OR
npm run build
```

```bash
php artisan serve
```

Now you can access with `/admin` path, using:

```bash
email: superadmin@starter-kit.com
password: superadmin
```

#### Performance

_It's recommend to run below command as suggested in [Filament Documentation](https://filamentphp.com/docs/3.x/panels/installation#improving-filament-panel-performance) for improving panel perfomance._

```bash
php artisan icons:cache
```

Please see this [Improving Filament panel performance](https://filamentphp.com/docs/3.x/panels/installation#improving-filament-panel-performance) documentation for further improvement
