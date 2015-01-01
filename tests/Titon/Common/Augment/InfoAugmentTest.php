<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common\Augment;

use Titon\Common\Augment\InfoAugment;
use Titon\Test\Stub\AugmentStub;
use Titon\Test\TestCase;

/**
 * Test class for Titon\Common\Augment\InfoAugment.
 *
 * @property \Titon\Common\Augment\InfoAugment $object
 */
class InfoAugmentTest extends TestCase {

    /**
     * This method is called before a test is executed.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new InfoAugment(new AugmentStub());
    }

    /**
     * Test that reflection() returns the ReflectionClass object.
     */
    public function testReflection() {
        $this->assertInstanceOf('ReflectionClass', $this->object->reflection());
    }

    /**
     * Test that className() returns the full class name with namespace.
     */
    public function testClassName() {
        $this->assertEquals('Titon\Test\Stub\AugmentStub', $this->object->className);
        $this->assertEquals('Titon\Test\Stub\AugmentStub', $this->object->className());
    }

    /**
     * Test that shortClassName() returns the class name.
     */
    public function testShortClassName() {
        $this->assertEquals('AugmentStub', $this->object->shortClassName);
        $this->assertEquals('AugmentStub', $this->object->shortClassName());
    }

    /**
     * Test that namespaceName() returns the namespace name.
     */
    public function testNamespaceName() {
        $this->assertEquals('Titon\Test\Stub', $this->object->namespaceName);
        $this->assertEquals('Titon\Test\Stub', $this->object->namespaceName());
    }

    /**
     * Test that filePath() returns the relative file path location.
     */
    public function testFilePath() {
        $this->assertEquals('Titon' . DS . 'Test' . DS . 'Stub' . DS . 'AugmentStub.php', $this->object->filePath);
        $this->assertEquals('Titon' . DS . 'Test' . DS . 'Stub' . DS . 'AugmentStub.php', $this->object->filePath());
    }

    /**
     * Test that methods() returns public, protected, private and static method names.
     */
    public function testMethods() {
        $methods = [
            'publicMethod', 'protectedMethod', 'privateMethod', 'staticPublicMethod', 'staticProtectedMethod', 'staticPrivateMethod',
            'serialize', 'unserialize', 'jsonSerialize', 'initialize', 'noop', 'toString', '__toString', '__construct', 'allCache',
            'getCache', 'setCache', 'toggleCache', 'removeCache', 'hasCache', 'createCacheKey', 'flushCache', 'cache',
            'addConfig', 'allConfig', 'applyConfig', 'setConfig', 'getConfig', 'getConfigAugment', 'removeConfig', 'hasConfig',
            'inform', 'loadInfo', 'getInfoAugment'
        ];

        $this->assertArraysEqual($methods, $this->object->methods);
        $this->assertArraysEqual($methods, $this->object->methods());
    }

    /**
     * Test that publicMethods() returns public method names.
     */
    public function testPublicMethods() {
        $methods = [
            'publicMethod', 'staticPublicMethod',
            'serialize', 'unserialize', 'jsonSerialize', 'initialize', 'noop', 'toString', '__toString', '__construct', 'allCache',
            'getCache', 'setCache', 'toggleCache', 'removeCache', 'hasCache', 'createCacheKey', 'flushCache', 'cache',
            'addConfig', 'allConfig', 'applyConfig', 'setConfig', 'getConfig', 'getConfigAugment', 'removeConfig', 'hasConfig',
            'inform', 'loadInfo', 'getInfoAugment'
        ];

        $this->assertArraysEqual($methods, $this->object->publicMethods);
        $this->assertArraysEqual($methods, $this->object->publicMethods());
    }

    /**
     * Test that protectedMethods() returns protected method names.
     */
    public function testProtectedMethods() {
        $methods = ['protectedMethod', 'staticProtectedMethod'];

        $this->assertArraysEqual($methods, $this->object->protectedMethods);
        $this->assertArraysEqual($methods, $this->object->protectedMethods());
    }

    /**
     * Test that privateMethods() returns private method names.
     */
    public function testPrivateMethods() {
        $methods = ['privateMethod', 'staticPrivateMethod'];

        $this->assertArraysEqual($methods, $this->object->privateMethods);
        $this->assertArraysEqual($methods, $this->object->privateMethods());
    }

    /**
     * Test that staticMethods() returns static method names.
     */
    public function testStaticMethods() {
        $methods = ['staticPublicMethod', 'staticProtectedMethod', 'staticPrivateMethod'];

        $this->assertArraysEqual($methods, $this->object->staticMethods);
        $this->assertArraysEqual($methods, $this->object->staticMethods());
    }

    /**
     * Test that properties() returns public, protected, private and static property names.
     */
    public function testProperties() {
        $props = [
            'publicProp', 'protectedProp', 'privateProp', 'staticPublicProp', 'staticProtectedProp', 'staticPrivateProp',
            '_config', '_cache', '__cacheEnabled'
        ];

        $this->assertArraysEqual($props, $this->object->properties);
        $this->assertArraysEqual($props, $this->object->properties());
    }

    /**
     * Test that publicProperties() returns public property names.
     */
    public function testPublicProperties() {
        $props = ['publicProp', 'staticPublicProp'];

        $this->assertArraysEqual($props, $this->object->publicProperties);
        $this->assertArraysEqual($props, $this->object->publicProperties());
    }

    /**
     * Test that protectedProperties() returns protected property names.
     */
    public function testProtectedProperties() {
        $props = ['protectedProp', 'staticProtectedProp', '_config', '_cache'];

        $this->assertArraysEqual($props, $this->object->protectedProperties);
        $this->assertArraysEqual($props, $this->object->protectedProperties());
    }

    /**
     * Test that privateProperties() returns private property names.
     */
    public function testPrivateProperties() {
        $props = ['privateProp', 'staticPrivateProp', '__cacheEnabled'];

        $this->assertArraysEqual($props, $this->object->privateProperties);
        $this->assertArraysEqual($props, $this->object->privateProperties());
    }

    /**
     * Test that staticProperties() returns static property names.
     */
    public function testStaticProperties() {
        $props = ['staticPublicProp', 'staticProtectedProp', 'staticPrivateProp'];

        $this->assertArraysEqual($props, $this->object->staticProperties);
        $this->assertArraysEqual($props, $this->object->staticProperties());
    }

    /**
     * Test that constants() return all constants as a key value pair array.
     */
    public function testConstants() {
        $constants = ['NO' => false, 'YES' => true];

        $this->assertArraysEqual($constants, $this->object->constants, true);
        $this->assertArraysEqual($constants, $this->object->constants(), true);
    }

    /**
     * Test that traits() returns an array of traits used on the class.
     */
    public function testTraits() {
        $traits = [
            'Titon\Common\Traits\Cacheable',
            'Titon\Common\Traits\Configurable',
            'Titon\Common\Traits\Informable'
        ];

        $this->assertArraysEqual($traits, $this->object->traits);
        $this->assertArraysEqual($traits, $this->object->traits());
    }

    /**
     * Test that interfaces() returns an array of interfaces the class implements.
     */
    public function testInterfaces() {
        $interfaces = ['Serializable', 'JsonSerializable'];

        // HHVM has a Stringish interface that defines __toString()
        if (defined('HHVM_VERSION')) {
            $interfaces[] = 'Stringish';
        }

        $this->assertArraysEqual($interfaces, $this->object->interfaces);
        $this->assertArraysEqual($interfaces, $this->object->interfaces());
    }

    /**
     * Test that parent() returns the name of the parent class.
     */
    public function testParent() {
        $this->assertEquals('Titon\Common\Base', $this->object->parent);
        $this->assertEquals('Titon\Common\Base', $this->object->parent());
    }

}