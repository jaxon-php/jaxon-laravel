Jaxon integration for Laravel
=============================

This package is an extension to integrate the [Jaxon library](https://github.com/jaxon-php/jaxon-core) into the Laravel framework.
It works with Laravel version 7 or newer.

Installation
------------

Add the following lines in the `composer.json` file, and run the `composer update jaxon-php/*` command.
```json
"require": {
    "jaxon-php/jaxon-laravel": "^5.0"
}
```

Publish the package configuration.
```bash
php artisan vendor:publish --tag=config
```

Configuration
-------------

The library configuration is located in the `config/jaxon.php` file.
It must contain both the `app` and `lib` sections defined in the documentation (https://www.jaxon-php.org/docs/v5x/about/configuration.html).

An example is presented in the `config/config.php` file of this repo.

Routing and middlewares
-----------------------

The extension automatically registers two middlewares, `jaxon.config` and, `jaxon.ajax`.

The `jaxon.config` middleware calls the Jaxon library setup function. It must be added to the routes to pages that need to show Jaxon related content.

```php
Route::get('/', [DemoController::class, 'index'])
    ->middleware(['web', 'jaxon.config'])
    ->name('demo');
```

The extension also registers the Jaxon requests route and the associated middlewares.

The route url is the value of the `lib.core.request.uri` option, the `app.request.route` gives an optional name to the route, and the `app.request.middlewares` option defines additional middlewares.

```php
    'app' => [
        'request' => [
            'route' => 'jaxon', // The route name
            'middlewares' => ['web'],
        ],
    ],
    'lib' => [
        'core' => [
            'request' => [
                'uri' => '/jaxon', // The route url
            ],
        ],
    ],
```

Usage
-----

This extension registers the following Blade directives to insert Jaxon js and css codes in the pages that need to show Jaxon related content.

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

Call factories
--------------

This extension registers the following Blade directives for Jaxon [call factories](https://www.jaxon-php.org/docs/v5x/ui-features/call-factories.html) functions.

> [!NOTE]
> In the following examples, the `$rqAppTest` template variable is set to the value `rq(Demo\Ajax\App\AppTest::class)`.

The `jxnBind` directive attaches a UI component to a DOM element, while the `jxnHtml` directive displays a component HTML code in a view.

```php
    <div class="col-md-12" @jxnBind($rqAppTest)>
        @jxnHtml($rqAppTest)
    </div>
```

The `jxnPagination` directive displays pagination links in a view.

```php
    <div class="col-md-12" @jxnPagination($rqAppTest)>
    </div>
```

The `jxnOn` directive binds an event on a DOM element to a Javascript call defined with a `call factory`.

```php
    <select class="form-select"
        @jxnOn('change', $rqAppTest->setColor(jq()->val()))>
        <option value="black" selected="selected">Black</option>
        <option value="red">Red</option>
        <option value="green">Green</option>
        <option value="blue">Blue</option>
    </select>
```

The `jxnClick` directive is a shortcut to define a handler for the `click` event.

```php
    <button type="button" class="btn btn-primary"
        @jxnClick($rqAppTest->sayHello(true))>Click me</button>
```

The `jxnEvent` directive defines a set of events handlers on the children of a DOM element, using `jQuery` selectors.

```php
    <div class="row" @jxnEvent([
        ['.app-color-choice', 'change', $rqAppTest->setColor(jq()->val())]
        ['.ext-color-choice', 'change', $rqExtTest->setColor(jq()->val())]
    ])>
        <div class="col-md-12">
            <select class="form-control app-color-choice">
                <option value="black" selected="selected">Black</option>
                <option value="red">Red</option>
                <option value="green">Green</option>
                <option value="blue">Blue</option>
            </select>
        </div>
        <div class="col-md-12">
            <select class="form-control ext-color-choice">
                <option value="black" selected="selected">Black</option>
                <option value="red">Red</option>
                <option value="green">Green</option>
                <option value="blue">Blue</option>
            </select>
        </div>
    </div>
```

The `jxnEvent` directive takes as parameter an array in which each entry is an array with a `jQuery` selector, an event and a `call factory`.

Contribute
----------

- Issue Tracker: github.com/jaxon-php/jaxon-laravel/issues
- Source Code: github.com/jaxon-php/jaxon-laravel

License
-------

The package is licensed under the BSD license.
