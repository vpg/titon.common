# Common v0.13.5 [![Build Status](https://travis-ci.org/titon/common.png)](https://travis-ci.org/titon/common) #

Provides common functionality for external packages, such as dependency management through registries and containers,
global configuration with a static configuration management layer, and a base class for all modular abstract classes to inherit.

On top of those features, the common package provides multiple traits that allow horizontal inheritance of powerful
patterns that solve basic use cases, like caching within class instances, class configurations, class dependencies, and more.
Classes can be further enhanced through augmentations, which are self contained inner classes.

For example, we can inherit common class functionality like serialization, configuration, and more.

```php
class Object extends Titon\Common\Base {
    use Titon\Common\Attachable,    \\ Provides lazy-loaded inner class dependencies
        Titon\Common\Cacheable,     \\ Provides memoization (method caching)
        Titon\Common\Instanceable;  \\ Provides multiton instance support
}
```

We can also lazy-load dependencies through the registry.

```php
use Titon\Common\Registry;

Registry::register('foo.bar', function() {
    return new Foo\Bar();
});

$foobar = Registry::get('foo.bar');
$foobar = Registry::factory('Foo\Bar'); // by namespace
```

And finally, configuration management has never been easier.

```php
use Titon\Common\Config;

Config::set('foo.bar', 'baz');
$baz = Config::get('foo.bar');
```

### Features ###

* `Base` - Primary base class
* `Traits` - Horizontal inheritance
* `Augments` - Class functionality encapsulation
* `Registry` - Static dependency container
* `Container` - Dependency container
* `Config` - Configuration management

### Dependencies ###

* `Utility`
* `Io` (optional for Config)

### Requirements ###

* PHP 5.4.0