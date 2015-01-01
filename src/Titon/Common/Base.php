<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common;

use Titon\Common\Traits\Configurable;
use Titon\Common\Traits\Informable;
use \Serializable;
use \JsonSerializable;

/**
 * Primary class for all framework classes to extend. All child classes will inherit the $_config property,
 * allowing any configuration settings to be automatically passed and set through the constructor.
 *
 * @package Titon\Common
 * @property \Titon\Common\Augment\ConfigAugment $config
 * @property \Titon\Common\Augment\InfoAugment $info
 */
class Base implements Serializable, JsonSerializable {
    use Configurable, Informable;

    /**
     * An array of configuration settings for the current class.
     *
     * @type array
     */
    protected $_config = ['initialize' => true];

    /**
     * Merges the custom configuration with the defaults.
     * Trigger initialize method if setting is true.
     *
     * @uses Titon\Utility\Hash
     *
     * @param array $config
     */
    public function __construct(array $config = []) {
        $this->applyConfig($config);

        // Trigger initializer
        if ($this->getConfig('initialize')) {
            $this->initialize();
        }
    }

    /**
     * Magic method for toString().
     *
     * @return string
     */
    public function __toString() {
        return $this->toString();
    }

    /**
     * Serialize the configuration.
     *
     * @return string
     */
    public function serialize() {
        return serialize($this->allConfig());
    }

    /**
     * Reconstruct the class once unserialized.
     *
     * @param array $data
     */
    public function unserialize($data) {
        $this->__construct(unserialize($data));
    }

    /**
     * Return the values for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize() {
        return $this->allConfig();
    }

    /**
     * Primary initialize method that is triggered during instantiation.
     */
    public function initialize() {
        return;
    }

    /**
     * A dummy function for no operation.
     */
    public function noop() {
        return;
    }

    /**
     * Return the class name when called as a string.
     *
     * @return string
     */
    public function toString() {
        return $this->inform('className');
    }

}