# VoThuatVN - Laravel SaaS Project

Martial Arts Center Management System - Backend API

## Prerequisites

Before proceeding, ensure you have:
- **PHP 8.2+** installed and in PATH
- **Composer** installed and in PATH  
- **PostgreSQL** installed and running

## Quick Start - Exact Commands

### Step 1: Verify Prerequisites
```powershell
php -v              # Should show PHP 8.2 or higher
composer --version  # Should show Composer version
psql --version      # Should show PostgreSQL version
```

### Step 2: Install Laravel 11
```powershell
composer create-project laravel/laravel . --prefer-dist
```

**OR** use the automated script:
```powershell
.\install-laravel.ps1
```

### Step 3: Configure PostgreSQL in .env
After Laravel is installed, edit the `.env` file and update:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=vothuatvn
DB_USERNAME=postgres
DB_PASSWORD=your_postgres_password_here
```

### Step 4: Create PostgreSQL Database
```powershell
psql -U postgres
```

Then in psql prompt:
```sql
CREATE DATABASE vothuatvn;
\q
```

### Step 5: Enable PHP PostgreSQL Extension
1. Locate your `php.ini` file: `php --ini`
2. Edit `php.ini` and uncomment these lines:
   ```
   extension=pdo_pgsql
   extension=pgsql
   ```
3. Verify: `php -m | findstr pgsql`

### Step 6: Generate Application Key
```powershell
php artisan key:generate
```

### Step 7: Run Migrations
```powershell
php artisan migrate
```

### Step 8: Start Development Server
```powershell
php artisan serve
```

Visit: http://127.0.0.1:8000

## Project Configuration

- **Application Name**: VoThuatVN
- **Laravel Version**: 11.x (latest)
- **PHP Version**: 8.2+
- **Database**: PostgreSQL
- **Architecture**: Backend-first, API-ready

## Next Steps

1. Configure CORS for API access
2. Set up authentication (Laravel Sanctum recommended)
3. Create models and migrations for martial arts centers
4. Set up API routes and controllers







