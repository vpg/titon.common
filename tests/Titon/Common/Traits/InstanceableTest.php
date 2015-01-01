<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common\Traits;

use Titon\Test\TestCase;
use Titon\Test\Stub\TraitStub;

/**
 * Test class for Titon\Common\Traits\Instanceable.
 */
class InstanceableTest extends TestCase {

    /**
     * Test that multiton functionality works.
     */
    public function testMultiton() {
        $object = TraitStub::getInstance();
        $object->setConfig('key', 1);

        $this->assertInstanceOf('Titon\Test\Stub\TraitStub', $object);
        $this->assertEquals(1, TraitStub::countInstances());

        $object2 = TraitStub::getInstance('alternate');
        $object2->setConfig('key', 2);

        $this->assertInstanceOf('Titon\Test\Stub\TraitStub', $object);
        $this->assertEquals(2, TraitStub::countInstances());

        // Test differences
        $this->assertEquals(1, TraitStub::getInstance()->getConfig('key'));
        $this->assertEquals(2, TraitStub::getInstance('alternate')->getConfig('key'));

        // Remove
        TraitStub::removeInstance('alternate');
        $this->assertEquals(1, TraitStub::countInstances());

        // Flush
        TraitStub::flushInstances();
        $this->assertEquals(0, TraitStub::countInstances());
    }

    /**
     * Test that singleton functionality works.
     */
    public function testSingleton() {
        $object = TraitStub::getInstance();
        $object->setConfig('key', 1);

        $this->assertInstanceOf('Titon\Test\Stub\TraitStub', $object);
        $this->assertEquals(1, TraitStub::countInstances());

        $object2 = TraitStub::getInstance();

        $this->assertInstanceOf('Titon\Test\Stub\TraitStub', $object);
        $this->assertEquals(1, TraitStub::countInstances());
        $this->assertEquals(1, TraitStub::getInstance()->getConfig('key'));

        $object2->setConfig('key', 2);

        // Test differences
        $this->assertEquals(2, $object->getConfig('key'));
        $this->assertEquals(2, TraitStub::getInstance()->getConfig('key'));

        // Flush
        TraitStub::flushInstances();
        $this->assertEquals(0, TraitStub::countInstances());
    }

}