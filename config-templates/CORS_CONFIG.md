# CORS Configuration for VoThuatVN

After Laravel installation, update `config/cors.php` with these settings for API access:

## Key Settings:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],

'allowed_methods' => ['*'],

'allowed_origins' => ['*'], // Change to specific domains in production

'allowed_origins_patterns' => [],

'allowed_headers' => ['*'],

'exposed_headers' => [],

'max_age' => 0,

'supports_credentials' => false,
```

## Notes:
- For development: `allowed_origins` can be `['*']`
- For production: Specify exact frontend domains, e.g., `['https://vothuatvn.com', 'https://www.vothuatvn.com']`
- The configuration script (`configure-project.ps1`) will set this automatically







