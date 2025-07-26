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

Got it! Here's your updated, short and clear README section with **test migration**, **seeding**, and **env setup** for the `test_json_export_performance`:

---

### ✅ Translation API Tests

Feature tests to verify core Translation API functionality:

* **Create**: Add new translations
* **Update**: Modify entries
* **Search**: Filter by key/tag/locale
* **Delete**: Soft delete
* **Export**: Ensure JSON export is fast (<500ms)

#### Setup

Make sure `.env.testing` is configured:

```env
DB_CONNECTION=mysql
DB_DATABASE=translations-api
DB_USERNAME=root
DB_PASSWORD=
TRANSLATION_SEED_COUNT=10000
```

#### Run tests

```bash
php artisan migrate --env=testing
php artisan db:seed --env=testing
php artisan test --env=testing
```

All tests should pass. Seeding ensures `test_json_export_performance` has enough data to simulate production load.
