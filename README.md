# Translation API – Laravel Sanctum Auth + Vue Frontend

A starter API project using Laravel Sanctum for authentication and a Vue frontend.

---

## ⚙️ Backend Setup (Laravel)

### 1. Clone the repository
```bash
git clone https://github.com/gerardosocias29/translation-api.git
cd translation-api
````

### 2. Install dependencies

```bash
composer install
cp .env.example .env
php artisan key:generate
```

### 3. Configure `.env`

Set your database credentials:

```
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Install Sanctum

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 5. User Seeder (Optional)

```bash
php artisan db:seed --class=UserSeeder
```

This seeds a default test user.

### 6. Run the server

```bash
php artisan serve
```