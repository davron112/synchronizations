## Installation

Requires Laravel 5.6
### Composer

Add the following text to your composer.json:

``` bash
"repositories": [
  {
    "type": "vcs",
    "url": ""
  }
],
```

Then run the following command:

``` bash
composer require Davron112/synchronizations
```

### Provider

Add the package to your application service providers in config/app.php file.

``` bash
'providers' => [

    ...

    /**
     * Third Party Service Providers...
     */
    Davron112\Synchronizations\Providers\SynchronizationsServiceProvider::class,

],
```

Then run the following commands:

``` bash
php artisan vendor:publish --provider="Davron112\Synchronizations\Providers\SynchronizationsServiceProvider"
composer dump-autoload
```

### Database

Finally run migrations and seeders.

``` bash
php artisan migrate
```
