<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common\Augment;

use Titon\Common\Traits\Mutable;
use \ArrayAccess;
use \IteratorAggregate;
use \Countable;

/**
 * Provides object level access to an array of parameters.
 *
 * @package Titon\Common\Augment
 */
class ParamAugment implements ArrayAccess, IteratorAggregate, Countable {
    use Mutable;

    /**
     * Set the parameters.
     *
     * @param array $params
     */
    public function __construct(array $params = []) {
        $this->_data = $params;
    }

}