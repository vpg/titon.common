# Cacheable #

The `Cacheable` and `StaticCacheable` traits allow the class to cache data and method responses within the object itself.

The cache() method allows the class to cache the return values of methods.
The cache key is dependent on the method name and its arguments, so it should always be unique.

```php
class Example {
    use Titon\Common\Traits\Cacheable;

    public function doSomething($arg1, $arg2) {
        return $this->cache([__METHOD__, $arg1, $arg2], function() use ($arg1, $arg2) {
            return ($arg1 + $arg2);
        });
    }
}
```

Each call to doSomething() will be cached.

```php
$example = new Example();
$example->doSomething(1, 2); // 3
$example->doSomething(1, 2); // 3 from cache
$example->doSomething(3, 2); // 5
```

Or you can manually get and set data.

```php
$example->setCache('data', []);
$example->getCache('data'); // []
```

Or to remove something from cache.

```php
$example->removeCache('data');
$example->flushCache();
```

Caching can also be turned on and off.

```php
$example->toggleCaching(false); // off
```