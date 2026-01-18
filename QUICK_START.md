# VoThuatVN - Quick Start Guide

## Exact Commands to Run (Copy & Paste)

### Step 1: Verify Prerequisites
```powershell
php -v
composer --version
psql --version
```
**Expected:** All should show version numbers. If any fail, install that tool first.

---

### Step 2: Install Laravel 11
```powershell
composer create-project laravel/laravel . --prefer-dist
```
**Time:** 2-5 minutes | **Wait for:** "Application key set successfully"

---

### Step 3: Create PostgreSQL Database
```powershell
psql -U postgres -c "CREATE DATABASE vothuatvn;"
```
**Note:** Enter your PostgreSQL password when prompted.

---

### Step 4: Configure Project
```powershell
.\configure-project.ps1 -DbPassword "your_postgres_password"
```
**Replace:** `your_postgres_password` with your actual PostgreSQL password.

**OR manually edit `.env`:**
- Change `DB_CONNECTION=mysql` to `DB_CONNECTION=pgsql`
- Update: `DB_DATABASE=vothuatvn`
- Update: `DB_USERNAME=postgres`
- Update: `DB_PASSWORD=your_password`

---

### Step 5: Enable PHP PostgreSQL Extension
```powershell
# Find php.ini
php --ini

# Edit php.ini, uncomment:
# extension=pdo_pgsql
# extension=pgsql

# Verify:
php -m | findstr pgsql
```
**Should show:** `pdo_pgsql` and `pgsql`

---

### Step 6: Generate Application Key
```powershell
php artisan key:generate
```

---

### Step 7: Run Migrations
```powershell
php artisan migrate
```
**Expected:** Creates 5 tables (users, password_reset_tokens, sessions, cache, cache_locks)

---

### Step 8: Start Server & Verify
```powershell
php artisan serve
```
**Visit:** http://127.0.0.1:8000

---

## One-Line Installation (After Prerequisites)
```powershell
composer create-project laravel/laravel . --prefer-dist && .\configure-project.ps1 -DbPassword "your_password" && psql -U postgres -c "CREATE DATABASE vothuatvn;" && php artisan key:generate && php artisan migrate
```

---

## Troubleshooting

**"composer not found"** → Install Composer and add to PATH  
**"php not found"** → Install PHP 8.2+ and add to PATH  
**"psql not found"** → Install PostgreSQL and add to PATH  
**"Extension pdo_pgsql not found"** → Enable in php.ini (see Step 5)  
**"Database connection failed"** → Check .env credentials and PostgreSQL service

---

## Files Created

- `install-laravel.ps1` - Automated Laravel installation script
- `configure-project.ps1` - Project configuration automation
- `IMPLEMENTATION_STEPS.md` - Detailed step-by-step guide
- `SETUP_COMMANDS.md` - All commands reference
- `README.md` - Project overview







