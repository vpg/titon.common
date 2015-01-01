<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common;

use Titon\Common\Base;
use Titon\Test\Stub\BaseStub;
use Titon\Test\TestCase;

/**
 * Test class for Titon\Common\Base.
 *
 * @property \Titon\Common\Base $object
 */
class BaseTest extends TestCase {

    /**
     * This method is called before a test is executed.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new Base();
    }

    /**
     * Test that augments are being loaded.
     */
    public function testAugments() {
        $this->assertInstanceOf('Titon\Common\Augment\ConfigAugment', $this->object->getConfigAugment());
        $this->assertInstanceOf('Titon\Common\Augment\InfoAugment', $this->object->getInfoAugment());
    }

    /**
     * Test that serialize() returns the config serialized.
     */
    public function testSerialize() {
        $this->assertEquals('a:1:{s:10:"initialize";b:1;}', $this->object->serialize());
        $this->assertEquals(['initialize' => true], $this->object->jsonSerialize());
    }

    /**
     * Test that toString() returns the class name as a string.
     */
    public function testToString() {
        $this->assertEquals('Titon\Common\Base', $this->object->toString());
        $this->assertEquals('Titon\Common\Base', (string) $this->object);
    }

    /**
     * Test that unserialize() will unserialize and set the config.
     */
    public function testUnserialize() {
        $this->object->unserialize('a:1:{s:10:"initialize";b:1;}');

        $this->assertEquals(['initialize' => true], $this->object->allConfig());
    }

    /**
     * Test noop function.
     */
    public function testNoop() {
        $this->assertEquals(null, $this->object->noop());
    }

}