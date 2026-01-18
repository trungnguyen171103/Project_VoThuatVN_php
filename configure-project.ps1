# VoThuatVN Project Configuration Script
# Run this AFTER Laravel is installed

param(
    [string]$DbPassword = "",
    [string]$DbName = "vothuatvn",
    [string]$DbUser = "postgres",
    [string]$DbHost = "127.0.0.1",
    [string]$DbPort = "5432"
)

Write-Host "Configuring VoThuatVN Laravel Project..." -ForegroundColor Green

# Check if .env exists
if (-not (Test-Path ".env")) {
    Write-Host "ERROR: .env file not found. Please run Laravel installation first." -ForegroundColor Red
    exit 1
}

# Backup .env
Copy-Item ".env" ".env.backup" -ErrorAction SilentlyContinue

# Update .env with PostgreSQL configuration
Write-Host "`nUpdating .env file..." -ForegroundColor Yellow

$envContent = Get-Content ".env" -Raw

# Update database configuration
$envContent = $envContent -replace "DB_CONNECTION=.*", "DB_CONNECTION=pgsql"
$envContent = $envContent -replace "DB_HOST=.*", "DB_HOST=$DbHost"
$envContent = $envContent -replace "DB_PORT=.*", "DB_PORT=$DbPort"
$envContent = $envContent -replace "DB_DATABASE=.*", "DB_DATABASE=$DbName"
$envContent = $envContent -replace "DB_USERNAME=.*", "DB_USERNAME=$DbUser"

if ($DbPassword) {
    $envContent = $envContent -replace "DB_PASSWORD=.*", "DB_PASSWORD=$DbPassword"
}

Set-Content -Path ".env" -Value $envContent
Write-Host "✓ .env file updated" -ForegroundColor Green

# Update config/app.php
Write-Host "`nUpdating config/app.php..." -ForegroundColor Yellow
if (Test-Path "config/app.php") {
    $appConfig = Get-Content "config/app.php" -Raw
    $appConfig = $appConfig -replace "('name' => env\('APP_NAME', ')([^']+)('\)\))", "`$1VoThuatVN`$3"
    Set-Content -Path "config/app.php" -Value $appConfig
    Write-Host "✓ Application name set to VoThuatVN" -ForegroundColor Green
}

# Configure CORS
Write-Host "`nConfiguring CORS..." -ForegroundColor Yellow
if (Test-Path "config/cors.php") {
    $corsConfig = Get-Content "config/cors.php" -Raw
    # Enable CORS for API access
    $corsConfig = $corsConfig -replace "'paths' => \['api/\*', 'sanctum/csrf-cookie'\],", "'paths' => ['api/*', 'sanctum/csrf-cookie'],"
    $corsConfig = $corsConfig -replace "'allowed_origins' => \[\],", "'allowed_origins' => ['*'],"
    $corsConfig = $corsConfig -replace "'allowed_origins_patterns' => \[\],", "'allowed_origins_patterns' => [],"
    $corsConfig = $corsConfig -replace "'allowed_headers' => \[\],", "'allowed_headers' => ['*'],"
    $corsConfig = $corsConfig -replace "'exposed_headers' => \[\],", "'exposed_headers' => [],"
    $corsConfig = $corsConfig -replace "'max_age' => 0,", "'max_age' => 0,"
    $corsConfig = $corsConfig -replace "'supports_credentials' => false,", "'supports_credentials' => false,"
    Set-Content -Path "config/cors.php" -Value $corsConfig
    Write-Host "✓ CORS configured for API access" -ForegroundColor Green
}

Write-Host "`n✓ Project configuration complete!" -ForegroundColor Green
Write-Host "`nNext steps:" -ForegroundColor Yellow
Write-Host "1. Create PostgreSQL database: psql -U postgres -c 'CREATE DATABASE $DbName;'"
Write-Host "2. Run migrations: php artisan migrate"
Write-Host "3. Generate app key (if needed): php artisan key:generate"







