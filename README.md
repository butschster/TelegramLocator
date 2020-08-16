### Installation

#### Enable PostGIS
```
CREATE EXTENSION postgis;
```

#### Configure mongodb connection
```
MONGODB_DSN=mongodb+srv://...
```

#### Set telegram manager token
```
TELEGRAM_MANAGER_TOKEN=...
```

#### Register webhook for manager
```
php artisan telegram:register-manager
```
