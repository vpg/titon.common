# Attachable #

The Attachable trait allows classes to lazy-load class level dependencies during runtime.

```php
class Example extends Titon\Common\Base {
    use Titon\Common\Traits\Attachable;

    public function initialize() {
        $this->attachObject('request', function() {
            return new Titon\Http\Request();
        });

        // Or with array
        $this->attachObject([
            'alias' => 'response',
            'class' => 'Titon\Http\Response'
        ]);
    }
}
```

The Request and Response objects will be instantiated once they are called. Each subsequent call with return the same instance.

```php
$example = new Example();
$example->request; // Request instance

// Alternate method
$example->getObject('response'); // Response instance
```

You can also trigger all attached objects to execute a method.

```php
$example->notifyObjects('methodName');
```

Furthermore, you can enable or disable objects from being accessed. Both methods accept a string or an array.

```php
$example->restrictObject(['request', 'response']);
$example->allowObject('response');
```

Lastly, you can provide advanced configuration when attaching objects.

```php
$this->attachObject([
    'alias' => 'html',
    'register' => true, // Cache the object in Titon\Common\Registry
    'callback' => true, // Allow method execution through notifyObjects()
    'interface' => 'Titon\View\Helper' // The class must implement this interface
], function() {
    return new Titon\View\Helper\Html\HtmlHelper();
}
```