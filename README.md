Jaxon Library for Laravel
=========================

This package integrates the [Jaxon library](https://github.com/jaxon-php/jaxon-core) into the Laravel 5 framework.

Features
--------

- Automatically register Jaxon classes from a preset directory.
- Read Jaxon options from a file in Laravel config format.

Installation
------------

Add the following lines in the `composer.json` file, and run the `composer update` command.
```json
"require": {
    "jaxon-php/jaxon-laravel": "~2.0"
}
```

Add the following line to the `providers` entry in the `app.php` config file.
```php
Jaxon\Laravel\JaxonServiceProvider::class
```

Add the following line to the `aliases` entry in the `app.php` config file.
```php
'LaravelJaxon' => Jaxon\Laravel\Facades\Jaxon::class
```

Publish the package configuration.
```php
php artisan vendor:publish --tag=config
```

Edit the `config/jaxon.php` file to suit the needs of your application.

Configuration
------------

The settings in the jaxon.php config file are separated into two sections.
The options in the `lib` section are those of the Jaxon core library, while the options in the `app` sections are those of the Laravel application.

The following options can be defined in the `app` section of the config file.

| Name | Default value | Description |
|------|---------------|-------------|
| request.route | jaxon | The named route to the Jaxon request processor |
| controllers.directory | app_path('Jaxon/Controllers') | The directory of the Jaxon classes |
| controllers.namespace | \Jaxon\App  | The namespace of the Jaxon classes |
| controllers.separator | .           | The separator in Jaxon class names |
| controllers.protected | empty array | Prevent Jaxon from exporting some methods |
| | | |

The `route` option is overriden by the `core.request.uri` option of the Jaxon library.

Usage
-----

This is an example of a Laravel controller using the Jaxon library.
```php
use LaravelJaxon;

class DemoController extends Controller
{
    public function __construct()
    {
        // parent::__construct();
    }

    public function index()
    {
        // Register the Jaxon classes
        LaravelJaxon::register();

        // Print the page
        return view('index', array(
            'JaxonCss' => LaravelJaxon::css(),
            'JaxonJs' => LaravelJaxon::js(),
            'JaxonScript' => LaravelJaxon::script()
        ));
    }
}
```

Before it prints the page, the controller makes a call to `LaravelJaxon::register()` to export the Jaxon classes.
Then it calls the `LaravelJaxon::css()`, `LaravelJaxon::js()` and `LaravelJaxon::script()` functions to get the CSS and javascript codes generated by Jaxon, which it inserts in the page.

### The Jaxon classes

The Jaxon classes must inherit from `\Jaxon\Laravel\Controller`.

The Jaxon classes of the application must all be located in the directory indicated by the `app.controllers.directory` option in the `jaxon.php` config file.
If there is a namespace associated, the `app.controllers.namespace` option should be set accordingly.

By default, the Jaxon classes are located in the `app/Jaxon/Controllers` dir of the Laravel application, and the associated namespace is `\Jaxon\App`.

Contribute
----------

- Issue Tracker: github.com/jaxon-php/jaxon-laravel/issues
- Source Code: github.com/jaxon-php/jaxon-laravel

License
-------

The package is licensed under the BSD license.
