# ParamAugment #

The ParamAugment allows for manipulation of an array of parameters. This is usually used in conjunction with POST or GET data.

```php
$params = new Titon\Common\Augment\ParamAugment($_POST);
```

Provides getters and setters.

```php
$params->all();
$params->get($key);
$params->set($key, $value);
$params->add($values);
$params->has($key);
$params->remove($key);
```

get(), set(), has() and remove() support dot notations.

```php
$params->get('some.deep.nested.key');
```

The class also supports iteration.

```php
foreach ($params as $key => $value) {
}
```