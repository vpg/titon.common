# Instanceable #

The `Instanceable` trait gives classes the ability to use multiton or singleton instances.

```php
class Example {
    use Titon\Common\Traits\Instanceable;
}
```

Each `getInstance()` call will return a new instance dependent on the key.

```php
$one = Example::getInstance(); // different than
$two = Example::getInstance('other');
```

You can also remove instances.

```php
Example::removeInstance('other');
Example::flushInstances();
```