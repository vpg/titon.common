<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common\Traits;

use Titon\Test\Stub\BaseStub;
use Titon\Test\TestCase;
use \Exception;

/**
 * Test class for Titon\Common\Traits\Informable.
 *
 * @property \Titon\Test\Stub\TraitStub $object
 */
class InformableTest extends TestCase {

    /**
     * This method is called before a test is executed.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new BaseStub();
    }

    /**
     * Test that config is inherited from parent classes.
     */
    public function testLoadInfo() {
        $this->assertInstanceOf('Titon\Common\Augment\InfoAugment', $this->object->getInfoAugment());
    }

    /**
     * Test that info is reflected.
     */
    public function testInform() {
        $this->assertEquals('Titon\Test\Stub\BaseStub', $this->object->inform('className'));

        try {
            $this->object->inform('foobar');
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

}