# VoThuatVN - Complete Implementation Steps

Follow these steps in order to set up your Laravel project.

## Phase 1: Prerequisites Installation

### 1.1 Install PHP 8.2+
**Windows:**
```powershell
# Option 1: Download from https://windows.php.net/download/
# Option 2: Using winget (if available)
winget install PHP.PHP.8.2

# Add PHP to PATH:
# 1. Find PHP installation directory (usually C:\php or C:\tools\php)
# 2. Add to System Environment Variables → Path
# 3. Restart PowerShell/terminal
```

**Verify:**
```powershell
php -v
# Should show: PHP 8.2.x or higher
```

### 1.2 Install Composer
**Windows:**
```powershell
# Download Composer-Setup.exe from https://getcomposer.org/download/
# Run installer and follow prompts

# OR using winget:
winget install Composer.Composer
```

**Verify:**
```powershell
composer --version
# Should show: Composer version 2.x.x
```

### 1.3 Install PostgreSQL
**Windows:**
```powershell
# Download from https://www.postgresql.org/download/windows/
# Run installer and note the password you set for 'postgres' user

# OR using winget:
winget install PostgreSQL.PostgreSQL
```

**Verify:**
```powershell
psql --version
# Should show: psql (PostgreSQL) version number
```

### 1.4 Enable PHP PostgreSQL Extension
```powershell
# Find php.ini location
php --ini

# Edit php.ini and uncomment these lines:
# extension=pdo_pgsql
# extension=pgsql

# Verify extension is loaded:
php -m | findstr pgsql
# Should show: pdo_pgsql and pgsql
```

## Phase 2: Laravel Installation

### 2.1 Install Laravel 11
```powershell
# Navigate to project directory
cd D:\Project_VoThuatVN_php

# Install Laravel (latest version, PHP 8.2+ compatible)
composer create-project laravel/laravel . --prefer-dist

# OR use the automated script:
.\install-laravel.ps1
```

**Expected Output:**
- Laravel files will be downloaded and installed
- Installation takes 2-5 minutes depending on internet speed

### 2.2 Verify Installation
```powershell
php artisan --version
# Should show: Laravel Framework 11.x.x
```

## Phase 3: Database Configuration

### 3.1 Create PostgreSQL Database
```powershell
# Connect to PostgreSQL
psql -U postgres

# In psql prompt:
CREATE DATABASE vothuatvn;
\q

# OR using command line directly:
psql -U postgres -c "CREATE DATABASE vothuatvn;"
```

### 3.2 Configure .env File
```powershell
# Run configuration script:
.\configure-project.ps1 -DbPassword "your_postgres_password"

# OR manually edit .env file:
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=vothuatvn
# DB_USERNAME=postgres
# DB_PASSWORD=your_postgres_password
```

**Verify Database Connection:**
```powershell
php artisan tinker
# Then in tinker:
DB::connection()->getPdo();
# Should connect successfully
```

## Phase 4: Laravel Configuration

### 4.1 Generate Application Key
```powershell
php artisan key:generate
```

### 4.2 Configure Application Name
The configuration script already updates this, but verify in `config/app.php`:
```php
'name' => env('APP_NAME', 'VoThuatVN'),
```

### 4.3 Configure Timezone (Optional)
Edit `.env`:
```
APP_TIMEZONE=Asia/Ho_Chi_Minh
```

## Phase 5: Run Initial Setup

### 5.1 Run Migrations
```powershell
php artisan migrate
```

**Expected Output:**
- Creates users, password_reset_tokens, sessions, cache tables
- All migrations should run successfully

### 5.2 Verify Installation
```powershell
# Start development server
php artisan serve

# Visit http://127.0.0.1:8000
# Should see Laravel welcome page
```

## Phase 6: Backend-First Configuration

### 6.1 Verify CORS Configuration
The configuration script sets up CORS. Verify in `config/cors.php`:
- `paths` includes `['api/*', 'sanctum/csrf-cookie']`
- `allowed_origins` is set to `['*']` for development

### 6.2 API Routes Setup
Laravel 11 includes API routes by default in `routes/api.php`. Verify it exists.

### 6.3 Remove Frontend Scaffolding (Optional)
Since this is backend-first, you can remove:
- `resources/js/` directory (if not needed)
- `resources/css/` directory (if not needed)
- `vite.config.js` (if not using frontend)

**Note:** Keep `resources/views/` if you need API documentation views.

## Verification Checklist

- [ ] PHP 8.2+ installed and in PATH
- [ ] Composer installed and in PATH
- [ ] PostgreSQL installed and running
- [ ] PHP PostgreSQL extension enabled
- [ ] Laravel 11 installed successfully
- [ ] Database `vothuatvn` created
- [ ] `.env` file configured with PostgreSQL
- [ ] Application key generated
- [ ] Migrations run successfully
- [ ] Development server starts without errors
- [ ] CORS configured for API access
- [ ] Application name set to VoThuatVN

## Troubleshooting

### Issue: Composer command not found
**Solution:** Add Composer to PATH or use full path to composer.phar

### Issue: PHP extension pdo_pgsql not found
**Solution:** 
1. Check php.ini location: `php --ini`
2. Uncomment extension lines in php.ini
3. Restart terminal/web server

### Issue: Database connection failed
**Solution:**
1. Verify PostgreSQL service is running
2. Check credentials in .env file
3. Verify database exists: `psql -U postgres -l`
4. Check PostgreSQL accepts connections from localhost

### Issue: Migration fails
**Solution:**
1. Verify database connection: `php artisan tinker` then `DB::connection()->getPdo()`
2. Check PostgreSQL user has CREATE privileges
3. Verify database name in .env matches created database

## Next Steps After Setup

1. **Install Laravel Sanctum** (for API authentication):
   ```powershell
   composer require laravel/sanctum
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   php artisan migrate
   ```

2. **Create Models and Migrations** for:
   - Martial Arts Centers
   - Students/Members
   - Classes/Sessions
   - Instructors
   - Subscriptions/Payments

3. **Set up API Routes** in `routes/api.php`

4. **Create Controllers** using:
   ```powershell
   php artisan make:controller Api/CenterController --api
   ```

5. **Configure Authentication** for API endpoints







