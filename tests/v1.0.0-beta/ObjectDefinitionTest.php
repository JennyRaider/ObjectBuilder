<?php

use JennyRaider\ObjectBuilder\ObjectDefinition;

class ObjectDefinitionTest extends PHPUnit_Framework_TestCase {
    
    protected $definition;
    
    public function setUp()
    {
        // Making an abstract class called booger interface because why not!
        $this->definition = new ObjectDefinition('MyApp\Component\AbstractBoogerInterface', 'abstract class');
    }
    
    public function testToArray()
    {
        $expectedArray = array(
            'name' => 'AbstractBoogerInterface',
            'varName' => 'booger',
            'namespaces' => array('MyApp', 'Component'),
            'namespace' => 'MyApp\Component',
            'fullName' => 'MyApp\Component\AbstractBoogerInterface',
            'type' => 'abstract class',
            'import' => array(),
            'use' => array(),
            'variable' => array(),
            'inject' => array(),
            'extend' => array(),
            'implement' => array(),
        );
        $this->assertEquals(
            $expectedArray,
            $this->definition->toArray()
        );
    }
    
    public function testGetName()
    {
        $this->assertEquals('AbstractBoogerInterface', $this->definition->get('name'));
    }
    
    /**
     * @expectedException InvalidArgumentException 
     */
    public function testGetBooger()
    {
        $this->definition->get('booger', false);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddInvalidAttributeThrowsException()
    {
        $this->definition->add('booger', 'stuff');
    }
    
    public function testGetVarName()
    {
        $this->assertEquals('booger', $this->definition->get('varName'));
    }
    
}