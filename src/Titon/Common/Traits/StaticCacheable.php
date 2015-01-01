<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common\Traits;

use \Closure;

/**
 * The Cacheable trait provides functionality to cache any data from the static class layer.
 * All data is unique and represented by a generated cache key.
 *
 * @package Titon\Common\Traits
 */
trait StaticCacheable {

    /**
     * Cached items indexed by key.
     *
     * @type array
     */
    protected static $_cache = [];

    /**
     * Return all the current cached items.
     *
     * @return array
     */
    public static function allCache() {
        return static::$_cache;
    }

    /**
     * Dynamically read and write from the cache at once. If the cache exists with the key return it, else execute and save the result.
     * If the value happens to be a closure, evaluate the closure and save the result.
     *
     * @param array|string $key
     * @param mixed|\Closure $value
     * @return mixed
     */
    public static function cache($key, $value) {
        $key = static::createCacheKey($key);

        if ($cache = static::getCache($key)) {
            return $cache;
        }

        if ($value instanceof Closure) {
            $value = call_user_func($value);
        }

        return static::setCache($key, $value);
    }

    /**
     * Generate a cache key. If an array is passed, drill down and form a key.
     *
     * @param string|array $keys
     * @return string
     */
    public static function createCacheKey($keys) {
        if (is_array($keys)) {
            $key = array_shift($keys);

            if ($keys) {
                foreach ($keys as $value) {
                    if (is_array($value)) {
                        $key .= '-' . md5(json_encode($value));
                    } else if ($value) {
                        $key .= '-' . $value;
                    }
                }
            }
        } else {
            $key = $keys;
        }

        return (string) $key;
    }

    /**
     * Empty the cache.
     */
    public static function flushCache() {
        static::$_cache = [];
    }

    /**
     * Return a cached item if it exists, else return null.
     *
     * @param string|array $key
     * @return mixed
     */
    public static function getCache($key) {
        $key = static::createCacheKey($key);

        if (static::hasCache($key)) {
            return static::$_cache[$key];
        }

        return null;
    }

    /**
     * Check to see if the cache key exists.
     *
     * @param string|array $key
     * @return bool
     */
    public static function hasCache($key) {
        return isset(static::$_cache[static::createCacheKey($key)]);
    }

    /**
     * Remove an item from the cache. Return true if the item was removed.
     *
     * @param string|array $key
     */
    public static function removeCache($key) {
        unset(static::$_cache[static::createCacheKey($key)]);
    }

    /**
     * Set a value to the cache with the defined key.
     * This will overwrite any data with the same key.
     * The value being saved will be returned.
     *
     * @param string|array $key
     * @param mixed $value
     * @return mixed
     */
    public static function setCache($key, $value) {
        static::$_cache[static::createCacheKey($key)] = $value;

        return $value;
    }

}