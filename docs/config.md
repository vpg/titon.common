# Config #

The Config class allows you to store and retrieve configuration settings.

```php
use Titon\Common\Config;
use Titon\Io\Reader\PhpReader;

Config::set('app', [
    'name' => 'Titon',
    'salt' => '940a8df7d359963b805f92e125dabecf',
    'encoding' => 'UTF-8'
]);

// Or from a file
Config::load('app', new PhpReader('/resources/configs/app.php'));
```

Once a value is set, you can retrieve it or overwrite it. You can also drill into arrays using a dot notated structure.

```php
Config::get('app'); // [...]
Config::get('app.name'); // Titon

Config::set('app.name', 'Common');
Config::get('app.name'); // Common

// Add to an array
Config::add('app.list', $value);
```

If you define an "App" configuration, the following convenience methods are available.

```php
Config::name();
Config::salt();
Config::encoding();
```
