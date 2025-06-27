Jaxon integration for Laravel
=============================

This package is an extension to integrate the [Jaxon library](https://github.com/jaxon-php/jaxon-core) into the Laravel framework.
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

Configuration
-------------

The library configuration is located in the `config/jaxon.php` file.
It must contain both the `app` and `lib` sections defined in the documentation (https://www.jaxon-php.org/docs/v5x/about/configuration.html).

An example is presented in the `config/config.php` file of this repo.

Routing and middlewares
-----------------------

The extension automatically registers two middlewares, `jaxon.config` and, 'jaxon.ajax'.

The `jaxon.config` middleware must be added to the routes to pages that need to show Jaxon related content.

```php
Route::get('/', [DemoController::class, 'index'])
    ->middleware(['web', 'jaxon.config'])
    ->name('demo');
```

The extension can also be configured to register its route and the associated middlewares by adding the `route` and `middlewares` options in the `config/jaxon.php` file.

```php
    'app' => [
        'request' => [
            'route' => 'jaxon',
            'middlewares' => ['web'],
        ],
    ],
```

The `route` option is overriden by the `core.request.uri` option of the Jaxon library.

Usage
-----

Insert Jaxon js and css codes in the pages that need to show Jaxon related content, using the `Blade` functions provided by the Jaxon package.

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
> In the following examples, the `$rqAppTest` var in the template is set to the value `rq(Demo\Ajax\App\AppTest::class)`.

The `@jxnBind` directive attaches a UI component to a DOM node, while the `@jxnHtml` directive displays a component HTML code in a view.

```php
    <div class="col-md-12" @jxnBind($rqAppTest)>
        @jxnHtml($rqAppTest)
    </div>
```

The `@jxnPagination` directive displays pagination links in a view.

```php
    <div class="col-md-12" @jxnPagination($rqAppTest)>
    </div>
```

The `@jxnOn` directive binds an event on a DOM node to a Javascript call defined with a `call factory`.

```php
    <select class="form-control"
        @jxnOn('change', $rqAppTest->setColor(jq()->val()))>
        <option value="black" selected="selected">Black</option>
        <option value="red">Red</option>
        <option value="green">Green</option>
        <option value="blue">Blue</option>
    </select>
```

The `@jxnClick` directive is a shortcut to define a handler for the `click` event on a DOM node.

```php
    <button type="button" class="btn btn-primary"
        @jxnClick($rqAppTest->sayHello(true))>Click me</button>
```

The `@jxnEvent` directive defines a set of events handlers on the children of a DOM nodes, using `jQuery` selectors.

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

The `@jxnEvent` directive takes s parameter an array in which each entry is an array with a `jQuery` selector, an event and a `call factory`.

Contribute
----------

- Issue Tracker: github.com/jaxon-php/jaxon-laravel/issues
- Source Code: github.com/jaxon-php/jaxon-laravel

License
-------

The package is licensed under the BSD license.
