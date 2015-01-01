<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common\Traits;

use Titon\Common\Base;
use Titon\Common\Registry;
use Titon\Common\Augment\ConfigAugment;
use Titon\Test\TestCase;
use Titon\Test\Stub\TraitStub;
use \Exception;

/**
 * Test class for Titon\Common\Traits\Attachable.
 *
 * @property \Titon\Test\Stub\TraitStub $object
 */
class AttachableTest extends TestCase {

    /**
     * This method is called before a test is executed.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new TraitStub();

        // by closure
        $this->object->attachObject('base', function() {
            return new Base();
        });

        // by class
        $this->object->attachObject([
            'alias' => 'conf',
            'class' => 'Titon\Common\Config'
        ]);

        // by property
        $this->object->registry = function() {
            return new Registry();
        };
    }

    /**
     * Test that attachObject() and __set() will create relations and lazy load.
     */
    public function testAttachObject() {
        $this->assertInstanceOf('Titon\Common\Base', $this->object->base);
        $this->assertInstanceOf('Titon\Common\Config', $this->object->conf);
        $this->assertInstanceOf('Titon\Common\Registry', $this->object->registry);

        // with interface requirement
        $this->object->attachObject([
            'alias' => 'augment1',
            'class' => 'Titon\Common\Augment\ConfigAugment',
            'interface' => 'ArrayAccess'
        ]);

        $this->object->attachObject([
            'alias' => 'augment2',
            'interface' => 'Iterator',
        ], function() {
            return new ConfigAugment([]);
        });

        $this->object->attachObject([
            'alias' => 'augment3',
            'class' => 'Titon\Common\Augment\ConfigAugment',
            'register' => false
        ]);

        $this->object->attachObject('augment4', new ConfigAugment());

        $this->assertInstanceOf('Titon\Common\Augment\ConfigAugment', $this->object->augment1);
        $this->assertInstanceOf('Titon\Common\Augment\ConfigAugment', $this->object->augment3);
        $this->assertInstanceOf('Titon\Common\Augment\ConfigAugment', $this->object->augment4);

        try {
            $this->assertInstanceOf('Titon\Common\Augment\ConfigAugment', $this->object->augment2);
            $this->assertTrue(false);

        } catch (Exception $e) {
            $this->assertTrue(true);
        }

        // error states
        try {
            $this->object->attachObject(['class' => 'Titon\Common\Base']);
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }

        try {
            $this->object->attachObject(['register' => false]);
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }

        try {
            $this->object->attachObject(['alias' => 'something']);
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test that detachObject() and __unset() will unset relations.
     */
    public function testDetachObject() {
        $this->assertTrue(isset($this->object->relation));
        $this->object->detachObject('relation');
        $this->assertFalse(isset($this->object->relation));

        $this->assertTrue(isset($this->object->conf));
        unset($this->object->conf);
        $this->assertFalse(isset($this->object->conf));
    }

    /**
     * Test that getObject and __get() will initialize and return the object relation.
     */
    public function testGetObject() {
        $this->assertInstanceOf('Titon\Common\Base', $this->object->base);
        $this->assertInstanceOf('Titon\Common\Base', $this->object->getObject('base'));

        $this->assertInstanceOf('Titon\Common\Config', $this->object->conf);
        $this->assertInstanceOf('Titon\Common\Config', $this->object->getObject('conf'));

        $this->assertInstanceOf('Titon\Common\Registry', $this->object->registry);
        $this->assertInstanceOf('Titon\Common\Registry', $this->object->getObject('registry'));

        // not attached
        try {
            $this->object->getObject('fake');
            $this->assertTrue(false);

        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test that hasObject() and __isset() will return true of the relation has been defined.
     */
    public function testHasObject() {
        $this->object->base->setConfig('key', 'Forcing this object to be instantiated!');

        $this->assertTrue(isset($this->object->base));
        $this->assertTrue($this->object->hasObject('base'));

        $this->assertFalse($this->object->hasObject('fake'));
    }

    /**
     * Test allowing, restricting, and notifying objects.
     */
    public function testNotifyObjects() {
        $this->object->attachObject('mock1', new NotifyMock());
        $this->object->attachObject('mock2', new NotifyMock());
        $this->object->attachObject('mock3', new NotifyMock());

        // Call method
        $this->object->notifyObjects('doNotify');
        $this->assertEquals(1, $this->object->mock1->count);
        $this->assertEquals(1, $this->object->mock2->count);
        $this->assertEquals(1, $this->object->mock3->count);

        // Restrict and call with argument
        $this->object->restrictObject('mock3');
        $this->object->notifyObjects('doNotify', [3]);
        $this->assertEquals(4, $this->object->mock1->count);
        $this->assertEquals(4, $this->object->mock2->count);
        $this->assertEquals(1, $this->object->mock3->count);

        // Allow
        $this->object->allowObject('mock3');
        $this->object->notifyObjects('doNotify', [1]);
        $this->assertEquals(5, $this->object->mock1->count);
        $this->assertEquals(5, $this->object->mock2->count);
        $this->assertEquals(2, $this->object->mock3->count);
    }

    /**
     * Test that deep nested relations will chain correctly.
     */
    public function testChaining() {
        $this->assertInstanceOf('Titon\Test\Stub\TraitStub', $this->object->relation);

        // we can go as deep as we want
        $this->assertInstanceOf('Titon\Test\Stub\TraitStub', $this->object->relation->relation->relation->relation->relation->relation->relation->relation->relation->relation->relation);
    }

}

class NotifyMock {

    public $count = 0;

    public function doNotify($step = 1) {
        $this->count += $step;
    }

}