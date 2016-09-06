# Controlling Hardware via the Cloud

## Installing

```
composer install
php artisan migrate
```

## API

An API endpoint is exposed at /api/devices and accepts the
querystring/parameter/header `api_token`. The `api_token` is unique
per device and used to query and post job information.
