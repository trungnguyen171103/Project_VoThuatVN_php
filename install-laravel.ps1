# Laravel VoThuatVN Installation Script
# Run this script after PHP and Composer are installed and in PATH

Write-Host "Installing Laravel 11..." -ForegroundColor Green

# Check if prerequisites are available
if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "ERROR: PHP is not installed or not in PATH" -ForegroundColor Red
    Write-Host "Please install PHP 8.2+ and add it to your PATH" -ForegroundColor Yellow
    exit 1
}

if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "ERROR: Composer is not installed or not in PATH" -ForegroundColor Red
    Write-Host "Please install Composer and add it to your PATH" -ForegroundColor Yellow
    exit 1
}

# Check PHP version
$phpVersion = php -v | Select-String -Pattern "PHP (\d+\.\d+)" | ForEach-Object { $_.Matches[0].Groups[1].Value }
if ([version]$phpVersion -lt [version]"8.2") {
    Write-Host "ERROR: PHP 8.2+ is required. Current version: $phpVersion" -ForegroundColor Red
    exit 1
}

Write-Host "PHP version: $phpVersion" -ForegroundColor Green
Write-Host "Composer version: $(composer --version)" -ForegroundColor Green

# Install Laravel in current directory
Write-Host "`nCreating Laravel project..." -ForegroundColor Yellow
composer create-project laravel/laravel . --prefer-dist

if ($LASTEXITCODE -eq 0) {
    Write-Host "`nLaravel installed successfully!" -ForegroundColor Green
    Write-Host "Next steps:" -ForegroundColor Yellow
    Write-Host "1. Configure .env file with PostgreSQL settings"
    Write-Host "2. Run: php artisan migrate"
    Write-Host "3. Run: php artisan key:generate"
} else {
    Write-Host "`nERROR: Laravel installation failed" -ForegroundColor Red
    exit 1
}







