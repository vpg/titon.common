<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common\Augment;

use Titon\Common\Augment\ParamAugment;
use Titon\Test\TestCase;
use \Exception;

/**
 * Test class for Titon\Common\Augment\ParamAugment.
 *
 * @property \Titon\Common\Augment\ParamAugment $object
 */
class ParamAugmentTest extends TestCase {

    /**
     * Default config used for autoboxing.
     */
    public $defaults = [
        'boolean' => true,
        'integer' => 12345,
        'string' => 'foobar',
        'float' => 50.25,
        'array' => [
            'key' => 'value'
        ]
    ];

    /**
     * This method is called before a test is executed.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new ParamAugment($this->defaults);
    }

    /**
     * Test that add() merges arrays.
     */
    public function testAdd() {
        $this->object->add([
            'boolean' => false,
            'foo' => 'bar'
        ]);

        $defaults = $this->defaults;
        $defaults['boolean'] = false;
        $defaults['foo'] = 'bar';

        $this->assertEquals($defaults, $this->object->all());
    }

    /**
     * Test that all() returns all.
     */
    public function testAll() {
        $this->assertEquals($this->defaults, $this->object->all());
    }

    /**
     * Test that get(), and __get() all return a value defined by a key.
     */
    public function testGet() {
        $this->assertEquals(true, $this->object->boolean);
        $this->assertEquals(true, $this->object->get('boolean'));
        $this->assertEquals(true, $this->object['boolean']);
        $this->object->boolean = false;
        $this->assertEquals(false, $this->object->get('boolean'));

        $this->assertEquals('foobar', $this->object->string);
        $this->assertEquals('foobar', $this->object->get('string'));
        $this->assertEquals('foobar', $this->object['string']);
        $this->object->string = 'barbaz';
        $this->assertEquals('barbaz', $this->object->get('string'));

        // nested
        // can't use object notation for nested
        // nor can you set values on nested using array access
        $this->assertEquals('value', $this->object->get('array.key'));
        $this->assertEquals('value', $this->object['array']['key']);
        $this->object->set('array.key', 'var');
        $this->assertEquals('var', $this->object['array']['key']);

        // non-existent keys throw exceptions
        try {
            $this->object->get('fakeKey');
            $this->assertTrue(false);

        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test that keys() returns all the array keys.
     */
    public function testKeys() {
        $this->assertEquals(['boolean', 'integer', 'string', 'float', 'array'], $this->object->keys());
    }

    /**
     * Test that set() and __set() will set a value and autobox if the default exists.
     */
    public function testSet() {
        $this->object->set('boolean', false);
        $this->assertEquals(false, $this->object->boolean);

        $this->object->boolean = 1;
        $this->assertEquals(true, $this->object->boolean);

        $this->object->set('integer', 666);
        $this->assertEquals(666, $this->object->integer);

        $this->object->set('string', '1988');
        $this->assertEquals('1988', $this->object->string);

        $this->object->string = 'string';
        $this->assertEquals('string', $this->object->string);

        $this->object->set('array.key', 50);
        $this->assertEquals('50', $this->object['array']['key']);

        $this->object->set('array.key', true);
        $this->assertEquals('1', $this->object['array']['key']);

        $this->object->set('array', ['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $this->object->array);

        $this->object->set('custom', 'test');
        $this->assertEquals('test', $this->object->get('custom'));
    }

    /**
     * Test that has() and __isset() returns true if the key exists.
     */
    public function testHas() {
        $this->assertTrue($this->object->has('integer'));
        $this->assertTrue(isset($this->object->integer));

        $this->assertTrue($this->object->has('array.key'));
        $this->assertTrue(isset($this->object['array']['key']));

        $this->assertFalse($this->object->has('fakeKey'));
        $this->assertFalse(isset($this->object->fakeKey));
    }

    /**
     * Test that remove() and __unset() remove a key from the array.
     */
    public function testRemove() {
        $this->object->remove('string')->remove('array');
        $this->assertEquals([
            'boolean' => true,
            'integer' => 12345,
            'float' => 50.25
        ], $this->object->all());

        unset($this->object->float, $this->object['boolean']);
        $this->assertEquals(['integer' => 12345], $this->object->all());
    }

    /**
     * Test that looping over the object works.
     */
    public function testIterator() {
        $config = [];

        foreach ($this->object as $key => $value) {
            $config[$key] = $value;
        }

        $this->assertEquals($this->defaults, $config);
    }

    /**
     * Test that count() returns the length of the array.
     */
    public function testCount() {
        $this->assertEquals(5, $this->object->count());

        unset($this->object->string);
        $this->assertEquals(4, $this->object->count());
    }

}