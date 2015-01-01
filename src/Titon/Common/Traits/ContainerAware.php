<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common\Traits;

use Titon\Common\Container;

/**
 * Permits classes to use a container instance within the class layer.
 *
 * @package Titon\Common\Traits
 */
trait ContainerAware {

    /**
     * Container instance.
     *
     * @type \Titon\Common\Container
     */
    protected $_container;

    /**
     * Return the container. Create an instance if one has not been set.
     *
     * @return \Titon\Common\Container
     */
    public function getContainer() {
        if (!$this->_container) {
            $this->setContainer(new Container());
        }

        return $this->_container;
    }

    /**
     * Set the container.
     *
     * @param \Titon\Common\Container $container
     * @return $this
     */
    public function setContainer(Container $container) {
        $this->_container = $container;

        return $this;
    }

}