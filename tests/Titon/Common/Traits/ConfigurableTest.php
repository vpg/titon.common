<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common\Traits;

use Titon\Test\Stub\BaseStub;
use Titon\Test\TestCase;

/**
 * Test class for Titon\Common\Traits\Configurable.
 *
 * @property \Titon\Test\Stub\TraitStub $object
 */
class ConfigurableTest extends TestCase {

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
    public function testApplyAndAllConfig() {
        $this->assertEquals(['initialize' => true, 'foo' => 'bar', 'cfg' => true], $this->object->allConfig());
    }

    /**
     * Test adding multiple configs.
     */
    public function testAddAllConfig() {
        $this->object->setConfig('foo', 'bar');
        $this->object->setConfig('key', 'value');

        $this->assertEquals([
            'initialize' => true,
            'foo' => 'bar',
            'cfg' => true,
            'key' => 'value'
        ], $this->object->allConfig());

        $this->object->addConfig([
            'foo' => 'baz',
            'cfg' => false
        ]);

        $this->assertEquals([
            'initialize' => true,
            'foo' => 'baz',
            'cfg' => false,
            'key' => 'value'
        ], $this->object->allConfig());
    }

    /**
     * Test that get and set config work.
     */
    public function testGetSetConfig() {
        $this->assertEquals('bar', $this->object->getConfig('foo'));
        $this->object->setConfig('foo', 'baz');
        $this->assertEquals('baz', $this->object->getConfig('foo'));

        $this->assertEquals(null, $this->object->getConfig('key'));
        $this->object->setConfig('key.key', 'value');
        $this->assertEquals(['key' => 'value'], $this->object->getConfig('key'));
    }

    /**
     * Test checking and removing configs.
     */
    public function testHasRemoveConfig() {
        $this->object->setConfig('foo', 'bar');
        $this->assertTrue($this->object->hasConfig('foo'));

        $this->object->removeConfig('foo');
        $this->assertFalse($this->object->hasConfig('foo'));
    }

}