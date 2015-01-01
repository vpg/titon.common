# Registry #

The `Registry` class allows you to manage and create instances of objects. Every time an instance is retrieved, it will return the same instance instead of creating new ones.

The easiest way to create an instance is to factory it.

```php
use Titon\Common\Registry;

$base = Registry::factory('Titon\Common\Base');
$base = Registry::factory('Titon\Common\Base', [['foo' => 'bar']]); // with configuration
```

You can also set and retrieve objects manually.

```php
use Titon\Http\Request;

Registry::set(new Request());
$request = Registry::get('Titon\Http\Request');

// Or with custom keys
Registry::set(new Request(), 'request');
$request = Registry::get('request');
```

Or you can register objects through a callback. These objects will be lazy-loaded and initialized once they are called.

```php
Registry::register('request', function() {
    // Do some processing
    return new Request();
});

$request = Registry::get('request');
```