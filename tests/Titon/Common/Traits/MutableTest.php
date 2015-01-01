<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common\Traits;

use Titon\Test\TestCase;
use \Exception;
use \ArrayAccess;
use \IteratorAggregate;
use \Countable;

/**
 * Test class for Titon\Common\Traits\Mutable.
 *
 * @property \Titon\Common\Traits\Mutable $object
 */
class MutableTest extends TestCase {

    /**
     * This method is called before a test is executed.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new MutableMock();
    }

    /**
     * Test adding multi data.
     */
    public function testAddAll() {
        $this->object->set('foo', 'bar');

        $this->assertEquals(['foo' => 'bar'], $this->object->all());

        $this->object->add([
            'foo' => 'baz',
            'depth.key' => 'value'
        ]);

        $this->assertEquals([
            'foo' => 'baz',
            'depth' => ['key' => 'value']
        ], $this->object->all());
    }

    /**
     * Test resetting data.
     */
    public function testFlush() {
        $this->object->set('foo', 'bar');

        $this->assertEquals(['foo' => 'bar'], $this->object->all());

        $this->object->flush();

        $this->assertEquals([], $this->object->all());
    }

    /**
     * Test getting data.
     */
    public function testGet() {
        $this->object->set('foo', 'bar');

        $this->assertEquals('bar', $this->object->get('foo'));
        $this->assertEquals('bar', $this->object->foo);
        $this->assertEquals('bar', $this->object['foo']);

        $this->assertEquals('default', $this->object->get('missing', 'default'));
    }

    /**
     * Test checking and removing data.
     */
    public function testHasRemove() {
        $this->object->set('foo', 'bar');
        $this->object->set('key', 'value');

        $this->assertTrue($this->object->has('foo'));
        $this->assertTrue(isset($this->object['key']));

        $this->object->remove('foo');
        unset($this->object['key']);

        $this->assertFalse($this->object->has('foo'));
        $this->assertFalse(isset($this->object['key']));
    }

    /**
     * Test setting data.
     */
    public function testSet() {
        $this->object->set('a', 1);
        $this->object->b = 2;
        $this->object['c'] = 3;

        $this->assertEquals(['a' => 1, 'b' => 2, 'c' => 3], $this->object->toArray());
        $this->assertEquals(['a', 'b', 'c'], $this->object->keys());
    }

}

class MutableMock implements ArrayAccess, IteratorAggregate, Countable {
    use Mutable;
}