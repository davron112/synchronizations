## LAravel 1C product auto syncronization


### Install


Then run the following command:

``` bash
composer require davron112/synchronizations
```

### Add to provider

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

Then run the following commands for publish package files:

``` bash
php artisan vendor:publish --provider="Davron112\Synchronizations\Providers\SynchronizationsServiceProvider"
composer dump-autoload
```

### Run Database Migration

Finally run migrations and seeders.

``` bash
php artisan migrate
```
