<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common;

use Titon\Test\TestCase;
use \Exception;

/**
 * Test class for Titon\Common\Container.
 *
 * @property \Titon\Common\Container $object
 */
class ContainerTest extends TestCase {

    /**
     * Set the container.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new Container();
    }

    /**
     * Flush registry each test.
     */
    protected function tearDown() {
        $this->object->flush();
    }

    /**
     * Test that all() returns all objects.
     */
    public function testAll() {
        $base = new Base();
        $config = new Config();
        $registry = new Registry();

        $this->object->set($base);
        $this->object->set($config);
        $this->object->set($registry);

        $this->assertEquals([
            'Titon\Common\Base' => $base,
            'Titon\Common\Config' => $config,
            'Titon\Common\Registry' => $registry
        ], $this->object->all());
    }

    /**
     * Test that flush resets all data and that keys returns the correct keys.
     */
    public function testFlushAndKeys() {
        $test = [];

        for ($i = 1; $i <= 10; $i++) {
            $this->object->set(new Base(), 'key' . $i);
            $test[] = 'key' . $i;
        }

        $registered = $this->object->keys();

        $this->assertEquals($test, $registered);
        $this->assertEquals(10, count($registered));

        $this->object->flush();

        $registered = $this->object->keys();

        $this->assertEquals(0, count($registered));
    }

    /**
     * Test that has returns a boolean if the correct object has been set.
     */
    public function testHasAndSet() {
        for ($i = 1; $i <= 10; $i++) {
            $this->object->set(new Base(), 'key' . $i);
        }

        $this->assertTrue($this->object->has('key1'));
        $this->assertTrue($this->object->has('key4'));
        $this->assertTrue($this->object->has('key8'));
        $this->assertFalse($this->object->has('key20'));
        $this->assertFalse($this->object->has('key25'));
        $this->assertFalse($this->object->has('key28'));

        try {
            $this->object->set(12345);
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test that removing a registered object returns a correct boolean.
     */
    public function testRemove() {
        for ($i = 1; $i <= 10; $i++) {
            $this->object->set(new Base(), 'key' . $i);
        }

        $this->assertTrue($this->object->has('key1'));
        $this->assertTrue($this->object->has('key4'));
        $this->assertTrue($this->object->has('key8'));

        $this->object->remove('key1');
        $this->object->remove('key4');
        $this->object->remove('key8');

        $this->assertFalse($this->object->has('key1'));
        $this->assertFalse($this->object->has('key4'));
        $this->assertFalse($this->object->has('key8'));
    }

    /**
     * Test that register() and get() lazy load callbacks.
     */
    public function testRegisterAndGet() {
        $this->object->register('base', function() {
            return new Base(['key' => 'registry']);
        });

        $object = $this->object->get('base');

        $this->assertInstanceOf('Titon\Common\Base', $object);
        $this->assertEquals('registry', $object->getConfig('key'));

        $this->assertInstanceOf('Titon\Common\Base', $this->object->get('base'));

        try {
            $this->object->get('missingKey');
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

}
