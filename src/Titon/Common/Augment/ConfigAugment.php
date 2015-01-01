<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common\Augment;

use Titon\Utility\Hash;

/**
 * An augment that supplies configuration options for primary classes.
 * The augment can take a optional secondary default configuration,
 * which can be used to autobox values anytime a config is written.
 *
 * @package Titon\Common\Augment
 */
class ConfigAugment extends ParamAugment {

    /**
     * Default configuration.
     *
     * @type array
     */
    protected $_defaults = [];

    /**
     * Apply defaults and merge the custom configuration in.
     *
     * @uses Titon\Utility\Hash
     *
     * @param array $config
     * @param array $defaults
     */
    public function __construct(array $config = [], array $defaults = []) {
        parent::__construct(Hash::merge($defaults, $config));

        $this->_defaults = $defaults;
    }

    /**
     * Set a configuration by key. Autobox the value if a default exists.
     *
     * @uses Titon\Utility\Hash
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value = null) {
        if (($default = Hash::extract($this->_defaults, $key)) !== null) {
            if (is_float($default)) {
                $value = (float) $value;

            } else if (is_numeric($default)) {
                $value = (int) $value;

            } else if (is_bool($default)) {
                $value = (bool) $value;

            } else if (is_string($default)) {
                $value = (string) $value;

            } else if (is_array($default)) {
                $value = (array) $value;
            }
        }

        $this->_data = Hash::insert($this->_data, $key, $value);

        return $this;
    }

}