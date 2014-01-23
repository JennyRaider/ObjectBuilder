<?php

use JennyRaider\ObjectBuilder\ObjectBuilder;
use JennyRaider\ObjectBuilder\ObjectDefinition;

class ObjectBuilderTest extends PHPUnit_Framework_TestCase {
    
    protected $builder;
    
    public function setUp()
    {
        $this->builder = new ObjectBuilder('MyApp\Component\Object', 'class');
    }
    
    public function testReturnsObjectDefinition()
    {
        $this->assertInstanceOf('JennyRaider\ObjectBuilder\ObjectDefinition', $this->builder->make());
    }
    
    public function testDefinitionExtendsFoo()
    {
        $this->builder->extendName('MyApp\Component\Foo');
        $extends = $this->builder->make()->get('extend');
        $foo = $extends[0];
        $this->assertTrue($foo->get('name') === 'Foo');
    }
    
}