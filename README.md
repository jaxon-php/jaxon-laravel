Jaxon integration for Laravel
=============================

This package integrates the [Jaxon library](https://github.com/jaxon-php/jaxon-core) into the Laravel framework.
It works with Laravel version 7 or newer.

Installation
------------

Add the following lines in the `composer.json` file, and run the `composer update` command.
```json
"require": {
    "jaxon-php/jaxon-laravel": "^5.0"
}
```

Publish the package configuration.
```php
php artisan vendor:publish --tag=config
```

Routing and middlewares
-----------------------

The library automatically registers two middlewares, `jaxon.config` and, 'jaxon.ajax'.

The `jaxon.config` middleware must be added to the routes to pages that need to show Jaxon related content.

```php
Route::get('/', [DemoController::class, 'index'])->name('demo')->middleware(['web', 'jaxon.config']);
```

It can also be configured to register its route and the associated middlewares by adding the `route` and `middlewares` options in the `config/jaxon.php` file.

```php
    'app' => [
        'request' => [
            'route' => 'jaxon',
            'middlewares' => ['web'],
        ],
    ],
```

Configuration
-------------

The settings in the `jaxon.php` config file are separated into two sections.
The options in the `lib` section are those of the Jaxon core library, while the options in the `app` sections are those of the Laravel application.

The following options can be defined in the `app` section of the config file.

| Name | Description |
|------|---------------|
| directories | An array of directory containing Jaxon application classes |
| views   | An array of directory containing Jaxon application views |
| | | |

By default, the `views` array is empty. Views are rendered from the framework default location.
There's a single entry in the `directories` array with the following values.

| Name | Default value | Description |
|------|---------------|-------------|
| directory | app_path('Jaxon/App') | The directory of the Jaxon classes |
| namespace | \Jaxon\App  | The namespace of the Jaxon classes |
| separator | .           | The separator in Jaxon class names |
| protected | empty array | Prevent Jaxon from exporting some methods |
| | | |

The `route` option is overriden by the `core.request.uri` option of the Jaxon library.

Usage
-----

Insert Jaxon js and css codes in the pages that need to show Jaxon related content, using the `Blade` functions provided by the Jaxon package.

```php
class DemoController extends Controller
{
    public function index()
    {
        // Print the page
        return view('demo/index', [
            'pageTitle' => "Laravel Framework",
        ]);
    }
}
```

```php
// resources/views/demo/index.blade.php

<!-- In page header -->

@jxnCss()
</head>

<body>

<!-- Page content here -->

</body>

<!-- In page footer -->

@jxnJs()

@jxnScript()
```

### The Jaxon classes

The Jaxon classes can inherit from `\Jaxon\App\CallableClass`.
By default, they are located in the `app/Jaxon/App` dir of the Laravel application, and the associated namespace is `\Jaxon\App`.

This is a simple example of a Jaxon class, defined in the `app/Jaxon/App/HelloWorld.php` file.

```php
namespace Jaxon\App;

class HelloWorld extends \Jaxon\App\CallableClass
{
    public function sayHello()
    {
        $this->response->assign('div2', 'innerHTML', 'Hello World!');
        return $this->response;
    }
}
```

Contribute
----------

- Issue Tracker: github.com/jaxon-php/jaxon-laravel/issues
- Source Code: github.com/jaxon-php/jaxon-laravel

License
-------

The package is licensed under the BSD license.
