<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common\Traits;

use Titon\Common\Augment\InfoAugment;

/**
 * Provides a reflection layer within classes.
 *
 * @package Titon\Common
 * @property \Titon\Common\Augment\InfoAugment $info
 */
trait Informable {

    /**
     * The information object.
     *
     * @type \Titon\Common\Augment\InfoAugment
     */
    private $__info;

    /**
     * Return the augment.
     *
     * @return \Titon\Common\Augment\InfoAugment
     */
    public function getInfoAugment() {
        if (!$this->__info) {
            $this->loadInfo();
        }

        return $this->__info;
    }

    /**
     * Load the information reflector.
     *
     * @return $this
     */
    public function loadInfo() {
        $this->__info = new InfoAugment($this);

        return $this;
    }

    /**
     * Return reflection information by key.
     *
     * @param string $key
     * @return mixed
     */
    public function inform($key) {
        return $this->getInfoAugment()->{$key};
    }

}