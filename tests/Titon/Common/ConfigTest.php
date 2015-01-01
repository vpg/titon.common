<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Common;

use Titon\Common\Config;
use Titon\Io\Reader\PhpReader;
use Titon\Test\TestCase;
use \Exception;

/**
 * Test class for Titon\Common\Config.
 */
class ConfigTest extends TestCase {

    /**
     * app array.
     */
    public $app = [
        'name' => 'Titon',
        'salt' => '66c63d989368170aff46040ab2353923',
        'seed' => 'nsdASDn7012dn1dsjSa',
        'encoding' => 'UTF-8'
    ];

    /**
     * debug array.
     */
    public $debug = [
        'level' => 2,
        'email' => ''
    ];

    /**
     * Test array.
     */
    public $test = [
        'integer' => 1234567890,
        'number' => '1234567890',
        'string' => 'abcdefg',
        'emptyArray' => [],
        'array' => [
            'one' => true,
            'two' => false,
        ],
        'false' => false,
        'true' => true,
        'null' => null,
        'zero' => 0
    ];

    /**
     * This method is called before a test is executed.
     */
    protected function setUp() {
        parent::setUp();

        Config::set('app', $this->app);
        Config::set('debug', $this->debug);
        Config::set('test', $this->test);
    }

    /**
     * Test that add() inserts into an array.
     */
    public function testAdd() {
        $this->assertEquals('Titon', Config::get('app.name'));

        Config::add('app.name', 'Framework');
        $this->assertEquals(['Titon', 'Framework'], Config::get('app.name'));

        Config::add('app.foobar', 'Titon');
        $this->assertEquals(['Titon'], Config::get('app.foobar'));
    }

    /**
     * Test that all() returns all configs.
     */
    public function testAll() {
        $this->assertEquals([
            'app' => $this->app,
            'debug' => $this->debug,
            'test' => $this->test
        ], Config::all());
    }

    /**
     * Test that the encoding is set and returns the correct value; default UTF-8 if empty.
     */
    public function testEncoding() {
        $this->assertEquals(Config::encoding(), 'UTF-8');

        Config::set('app.encoding', 'UTF-16');
        $this->assertEquals(Config::encoding(), 'UTF-16');

        Config::set('app.encoding', '');
        $this->assertEquals(Config::encoding(), 'UTF-8');
    }

    /**
     * Test that get() returns the correct values and types.
     */
    public function testGet() {
        $this->assertEquals(Config::get('app.name'), $this->app['name']);
        $this->assertEquals(Config::get('app.seed'), $this->app['seed']);

        $this->assertEquals(Config::get('debug'), $this->debug);
        $this->assertEquals(Config::get('debug.level'), $this->debug['level']);

        $this->assertTrue(is_integer(Config::get('test.integer')));
        $this->assertTrue(is_numeric(Config::get('test.number')));
        $this->assertTrue(is_string(Config::get('test.string')));
        $empty = Config::get('test.emptyArray');
        $this->assertTrue(empty($empty));
        $this->assertTrue(is_array(Config::get('test.array')));
        $this->assertTrue(Config::get('test.array.one') === true);
        $this->assertTrue(Config::get('test.false') === false);
        $this->assertTrue(Config::get('test.true') === true);
        $this->assertTrue(Config::get('test.zero') === 0);
        $this->assertTrue(Config::get('test.fakeKey') === null);

        $this->assertEquals(Config::get('test.string'), $this->test['string']);

        // Default
        $this->assertEquals('bar', Config::get('app.foo', 'bar'));
        Config::set('app.foo', 'baz');
        $this->assertEquals('baz', Config::get('app.foo', 'bar'));
    }

    /**
     * Test that has() returns a true or false statement.
     */
    public function testHas() {
        $this->assertTrue(Config::has('app.salt'));
        $this->assertTrue(Config::has('debug.email'));
        $this->assertTrue(Config::has('test.number'));
        $this->assertTrue(Config::has('test.true'));
        $this->assertTrue(Config::has('test.false'));
        $this->assertTrue(Config::has('test.zero'));

        $this->assertFalse(Config::has('app.id'));
        $this->assertFalse(Config::has('debug.id'));
        $this->assertFalse(Config::has('test.fakeKey'));
        $this->assertFalse(Config::has('test.deep.deep.deep.deep.array'));
    }

    /**
     * Test that loading a config set works correctly.
     */
    public function testLoad() {
        if (!class_exists('Titon\Io\Reader\PhpReader')) {
            $this->markTestSkipped('Test skipped; Please install titon/io via Composer');
        }

        $reader = new PhpReader(TEMP_DIR . '/config.php');

        Config::load('Php', $reader);
        $this->assertArrayHasKey('Php', Config::all());

        $data = Config::get('Php');
        $this->assertEquals($data, $this->test);
    }

    /**
     * Test that name() returns the correct app.name.
     */
    public function testName() {
        $this->assertEquals(Config::name(), $this->app['name']);

        Config::set('app.name', 'TestName');
        $this->assertEquals(Config::name(), 'TestName');

        Config::set('app.name', '');
        $this->assertEquals(Config::name(), '');
    }

    /**
     * Test that remove() removes a key.
     */
    public function testRemove() {
        $this->assertTrue(Config::has('app.salt'));
        $this->assertTrue(Config::has('debug.email'));

        Config::remove('debug');
        $this->assertTrue(Config::has('app.salt'));
        $this->assertFalse(Config::has('debug.email'));
    }

    /**
     * Test that salt() returns the correct app.salt.
     */
    public function testSalt() {
        $this->assertEquals(Config::salt(), $this->app['salt']);

        Config::set('app.salt', md5('TestSalt'));
        $this->assertEquals(Config::salt(), md5('TestSalt'));

        Config::set('app.salt', '');
        $this->assertEquals(Config::salt(), '');
    }

    /**
     * Test that set() correctly sets values at the correct depths.
     */
    public function testSet() {
        Config::set('Set.level1', 1);
        $this->assertEquals(Config::get('Set.level1'), 1);

        Config::set('Set.level2.level2', 2);
        $this->assertEquals(Config::get('Set.level2.level2'), 2);

        Config::set('Set.level3.level3.level3', 3);
        $this->assertEquals(Config::get('Set.level3.level3.level3'), 3);

        Config::set('Set.level4.level4.level4.level4', 4);
        $this->assertEquals(Config::get('Set.level4.level4.level4.level4'), 4);
        $this->assertTrue(is_array(Config::get('Set.level4.level4.level4')));
        $this->assertFalse(Config::get('Set.level4.level4') === 'falsey');

        Config::set('Set.level4.array', ['key' => 'value']);
        $this->assertEquals(Config::get('Set.level4.array'), ['key' => 'value']);
        $this->assertEquals(Config::get('Set.level4.array.key'), 'value');

        Config::set('Set.level4', 'Flattened!');
        $this->assertEquals(Config::get('Set.level4'), 'Flattened!');
        $this->assertEquals(Config::get('Set.level4.level4.level4.level4'), null);
    }

}