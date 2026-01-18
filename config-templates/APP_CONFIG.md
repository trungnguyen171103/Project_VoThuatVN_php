# Application Configuration for VoThuatVN

## config/app.php

Update the application name:

```php
'name' => env('APP_NAME', 'VoThuatVN'),
```

Or set in `.env`:
```
APP_NAME=VoThuatVN
```

## .env Settings

Recommended settings for VoThuatVN:

```
APP_NAME=VoThuatVN
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Ho_Chi_Minh
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
```

## Notes:
- Run `php artisan key:generate` to set APP_KEY
- Change APP_ENV to `production` when deploying
- Set APP_DEBUG to `false` in production
- APP_TIMEZONE should match your server/region timezone







