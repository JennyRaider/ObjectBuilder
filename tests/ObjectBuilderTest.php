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
        $this->assertInstanceOf(ObjectDefinition, $this->builder->make());
    }
    
}