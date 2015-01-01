<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common\Traits;

use Titon\Common\Augment\ConfigAugment;
use Titon\Utility\Hash;

/**
 * Provides a configuration layer within classes.
 *
 * @package Titon\Common
 */
trait Configurable {

    /**
     * The configuration object.
     *
     * @type \Titon\Common\Augment\ConfigAugment
     */
    private $__config;

    /**
     * Add multiple configurations.
     *
     * @param array $data
     * @return $this
     */
    public function addConfig(array $data) {
        $this->getConfigAugment()->add($data);

        return $this;
    }

    /**
     * Return all current configurations.
     *
     * @return array
     */
    public function allConfig() {
        return $this->getConfigAugment()->all();
    }

    /**
     * Merge the custom configuration with the defaults and inherit from parent classes.
     *
     * @uses Titon\Utility\Hash
     *
     * @param array $config
     * @return $this
     */
    public function applyConfig(array $config = []) {
        $parent = $this;
        $defaults = isset($this->_config) ? $this->_config : [];

        // Inherit config from parents
        while ($parent = get_parent_class($parent)) {
            $props = get_class_vars($parent);

            if (isset($props['_config'])) {
                $defaults = Hash::merge($props['_config'], $defaults);
            }
        }

        $this->__config = new ConfigAugment($config, $defaults);

        return $this;
    }

    /**
     * Get a configuration by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfig($key, $default = null) {
        return $this->getConfigAugment()->get($key, $default);
    }

    /**
     * Return the augment.
     *
     * @return \Titon\Common\Augment\ConfigAugment
     */
    public function getConfigAugment() {
        return $this->__config;
    }

    /**
     * Check if config exists.
     *
     * @param string $key
     * @return bool
     */
    public function hasConfig($key) {
        return $this->getConfigAugment()->has($key);
    }

    /**
     * Remove a configuration by key.
     *
     * @param string $key
     * @return $this
     */
    public function removeConfig($key) {
        $this->getConfigAugment()->remove($key);

        return $this;
    }

    /**
     * Set a configuration value.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setConfig($key, $value) {
        $this->getConfigAugment()->set($key, $value);

        return $this;
    }

}