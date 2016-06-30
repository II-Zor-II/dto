<?php
class DeclareTypeTest extends PHPUnit_Framework_Testcase
{
    /**
     * Make sure we're good with case insensitivity
     */
    public function testMutator()
    {
        $D = new \Dto\Dto();
        $this->assertTrue(method_exists($D, 'settypeinteger'));
    }

    public function testWriteInteger()
    {
        $D = new TestDeclareTypeTesttDto();
        $D->integer = 5;
        $this->assertEquals(5, $D->integer);

        $D->integer = '6x';
        $this->assertEquals(6, $D->integer);

        $D->set('integer', 7);
        $this->assertEquals(7, $D->integer);

        $D->set('integer', 'Not an Integer');
        $this->assertEquals(0, $D->integer);

        $D->set('integer', 'Not an Integer', true); // Bypass checks
        $this->assertEquals('Not an Integer', $D->integer);
    }

    public function testWriteFloat()
    {
        $D = new TestDeclareTypeTesttDto();
        $D->float = 1.1;
        $this->assertEquals(1.1, $D->float);

        $D->float = 2;
        $this->assertEquals(2, $D->float);

        $D->set('float', 3.3);
        $this->assertEquals(3.3, $D->float);

        $D->float = 'Not a float';
        $this->assertEquals(0, $D->float);

        $D->set('float', 'Not a float', true); // Bypass checks
        $this->assertEquals('Not a float', $D->float);
    }

    public function testBoolean()
    {
        $D = new TestDeclareTypeTesttDto();
        $D->boolean = true;
        $this->assertTrue($D->boolean);

        $D->boolean = false;
        $this->assertFalse($D->boolean);

        $D->set('boolean', true);
        $this->assertTrue($D->boolean);

        $D->set('boolean', 'Not a bool', true); // Bypass checks
        $this->assertEquals('Not a bool', $D->boolean);
    }

    public function testScalar()
    {
        $D = new TestDeclareTypeTesttDto();

        $D->string = 'something';
        $this->assertEquals('something', $D->string);

        $D->string = true;
        $this->assertEquals('1', $D->string);

        $D->string = false;
        $this->assertEquals('', $D->string);

        $D->string = ['an', 'array'];
        $this->assertEquals('Array', $D->string);
    }

    public function testArray1()
    {
        $D = new TestDeclareTypeTesttDto();
        //$D = new \Dto\Dto([], ['array' => []], ['array' => ['type' => 'array']]);
        //exit;
        $D->array = ['x','y','z'];
        $this->assertEquals(['x','y','z'], $D->array->toArray());


        $D->array = ['x','y'];
//        $D->array[] = 'z';
        $D->array->append('z');

        $this->assertEquals(['x','y','z'], (array) $D->array);

        $erratic_index = [
            'a' => 'apple',
            'b' => 'banana',
            'c' => 'cherry'
        ];

        $D->array = $erratic_index;
        $this->assertEquals(['apple','banana','cherry'], $D->array->toArray());
    }

    public function testArray2()
    {
        $D = new \Dto\Dto([], ['array' => []], ['array' => ['type' => 'array']]);

        $D->array = ['x','y'];
        $D->array[] = 'z';

        // Both options are possible, but strictly speaking, the Dto is NOT an array
        $this->assertEquals(['x','y','z'], $D->array->toArray());
        $this->assertEquals(['x','y','z'], (array) $D->array);

        $D->array->append('aa');
        $this->assertEquals(['x','y','z', 'aa'], $D->array->toArray());
        $this->assertEquals(['x','y','z', 'aa'], (array) $D->array);

    }

    public function testHash()
    {
        $D = new TestDeclareTypeTesttDto();
        $D->hash->x = 'y';
        $this->assertEquals('y', $D->hash->x);
        $this->assertEquals('y', $D->hash['x']);
        $this->assertEquals('y', $D['hash']['x']);

        $D['hash']['z'] = 'aa';
        $this->assertEquals('aa', $D->hash->z);
        $this->assertEquals('aa', $D->hash['z']);
        $this->assertEquals('aa', $D['hash']['z']);
    }

}

class TestDeclareTypeTesttDto extends \Dto\Dto
{
    protected $template = [
        'integer' => null,
        'float' => null, // i.e. number
        'boolean' => null,
        'string' => null, // strval - true/false converts to 1/0, arrays to "Array"
        'array' => [],
        'hash' => null,
    ];

    protected $meta = [
        'integer' => [
            'type' => 'integer'
        ],
        'float' => [
            'type' => 'float'
        ],
        'boolean' => [
            'type' => 'boolean'
        ],
        'string' => [
            'type' => 'scalar'
        ],
        'array' => [
            'type' => 'array'
        ],
        'hash' => [
            'type' => 'hash'
        ],
    ];
}