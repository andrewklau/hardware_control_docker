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

## TODO

- Implement broadcast notifications for live streaming of results (pusher)
- Implement level based access for limiting permission scope
- Add device management and token generation
