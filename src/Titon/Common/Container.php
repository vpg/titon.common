<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common;

use Titon\Common\Exception\InvalidObjectException;
use Titon\Common\Exception\MissingObjectException;
use \Closure;

/**
 * The Container works exactly the same as the Registry class with the following exceptions.
 * The Container provides a non-static approach to managing objects, without the use of factory.
 *
 * @package Titon\Common
 */
class Container {

    /**
     * Objects that have been registered into memory. The array index is represented by the namespace convention,
     * where as the array value would be the matching instantiated object.
     *
     * @type object[]
     */
    protected $_registered = [];

    /**
     * Return all registered objects.
     *
     * @return object[]
     */
    public function all() {
        return $this->_registered;
    }

    /**
     * Flush the container by removing all stored objects.
     *
     * @return $this
     */
    public function flush() {
        $this->_registered = [];

        return $this;
    }

    /**
     * Return the object assigned to the given key.
     *
     * @param string $key
     * @return object
     * @throws \Titon\Common\Exception\MissingObjectException
     */
    public function &get($key) {
        if ($this->has($key)) {
            $object = $this->_registered[$key];

            if ($object instanceof Closure) {
                $object = $this->set(call_user_func($object), $key);
            }

            return $object;
        }

        throw new MissingObjectException(sprintf('Object %s does not exist in the container', $key));
    }

    /**
     * Checks to see if an object has been registered (instantiated).
     *
     * @param string $key
     * @return bool
     */
    public function has($key) {
        return isset($this->_registered[$key]);
    }

    /**
     * Returns an array of all objects that have been registered; returns the keys and not the objects.
     *
     * @return string[]
     */
    public function keys() {
        return array_keys($this->_registered);
    }

    /**
     * Register a callback that will be lazily loaded when called.
     *
     * @param string $key
     * @param \Closure $callback
     * @return $this
     */
    public function register($key, Closure $callback) {
        $this->set($callback, $key);

        return $this;
    }

    /**
     * Remove an object from the container.
     *
     * @param string $key
     * @return $this
     */
    public function remove($key) {
        unset($this->_registered[$key]);

        return $this;
    }

    /**
     * Store an object into the container.
     *
     * @param object $object
     * @param string $key
     * @return object
     * @throws \Titon\Common\Exception\InvalidObjectException
     */
    public function set($object, $key = null) {
        if (!is_object($object)) {
            throw new InvalidObjectException('The object to register must be instantiated');
        }

        if (!$key) {
            $key = get_class($object);
        }

        $this->_registered[$key] = $object;

        return $object;
    }

}
