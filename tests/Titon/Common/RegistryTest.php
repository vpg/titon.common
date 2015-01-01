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
 * Test class for Titon\Common\Registry.
 */
class RegistryTest extends TestCase {

    /**
     * Flush registry each test.
     */
    protected function tearDown() {
        Registry::flush();
    }

    /**
     * Test that all() returns all objects.
     */
    public function testAll() {
        $base = new Base();
        $config = new Config();
        $registry = new Registry();

        Registry::set($base);
        Registry::set($config);
        Registry::set($registry);

        $this->assertEquals([
            'Titon\Common\Base' => $base,
            'Titon\Common\Config' => $config,
            'Titon\Common\Registry' => $registry
        ], Registry::all());
    }

    /**
     * Test that factory returns the correct object for the supplied namespace.
     */
    public function testFactory() {
        $this->assertInstanceOf('Titon\Common\Base', Registry::factory('Titon\Common\Base', [], false));
        $this->assertInstanceOf('Titon\Common\Base', Registry::factory('Titon/Common/Base', [], false));
        $this->assertInstanceOf('Titon\Common\Base', Registry::factory('Titon\Common\Base', [], false));
        $this->assertInstanceOf('Titon\Common\Base', Registry::factory('/Titon/Common/Base', [], false));
    }

    /**
     * Test that flush resets all data and that keys returns the correct keys.
     */
    public function testFlushAndKeys() {
        $test = [];

        for ($i = 1; $i <= 10; $i++) {
            Registry::set(new Base(), 'key' . $i);
            $test[] = 'key' . $i;
        }

        $registered = Registry::keys();

        $this->assertEquals($test, $registered);
        $this->assertEquals(10, count($registered));

        Registry::flush();

        $registered = Registry::keys();

        $this->assertEquals(0, count($registered));
    }

    /**
     * Test that has returns a boolean if the correct object has been set.
     */
    public function testHasAndSet() {
        for ($i = 1; $i <= 10; $i++) {
            Registry::set(new Base(), 'key' . $i);
        }

        $this->assertTrue(Registry::has('key1'));
        $this->assertTrue(Registry::has('key4'));
        $this->assertTrue(Registry::has('key8'));
        $this->assertFalse(Registry::has('key20'));
        $this->assertFalse(Registry::has('key25'));
        $this->assertFalse(Registry::has('key28'));

        try {
            Registry::set(12345);
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
            Registry::set(new Base(), 'key' . $i);
        }

        $this->assertTrue(Registry::has('key1'));
        $this->assertTrue(Registry::has('key4'));
        $this->assertTrue(Registry::has('key8'));

        Registry::remove('key1');
        Registry::remove('key4');
        Registry::remove('key8');

        $this->assertFalse(Registry::has('key1'));
        $this->assertFalse(Registry::has('key4'));
        $this->assertFalse(Registry::has('key8'));
    }

    /**
     * Test that register() and get() lazy load callbacks.
     */
    public function testRegisterAndGet() {
        Registry::register('base', function() {
            return new Base(['key' => 'registry']);
        });

        $object = Registry::get('base');

        $this->assertInstanceOf('Titon\Common\Base', $object);
        $this->assertEquals('registry', $object->getConfig('key'));

        $this->assertInstanceOf('Titon\Common\Base', Registry::get('base'));


        try {
            Registry::get('missingKey');
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

}
