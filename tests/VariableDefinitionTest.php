<?php

use JennyRaider\ObjectBuilder\VariableDefinition;
use JennyRaider\ObjectBuilder\ObjectBuilder;

class VariableDefinitionTest extends PHPUnit_Framework_TestCase {
    
    /**
     * @var JennyRaider\ObjectBuilder\VariableDefinition
     */
    protected $definition;
    
    /**
     * @var JennyRaider\ObjectBuilder\ObjectBuilder
     */
    protected $builder;
    
    public function setUp()
    {
        // Making an abstract class called booger interface because why not!
        $this->definition = new VariableDefinition('public', 'boogerFace', 'object');
    }
    
    public function testGetTypeHint()
    {
        $this->definition->type = 'string';
        $this->assertEquals('string', $this->definition->getHintOrType());
        $this->assertEquals(false, $this->definition->getTypeHint());
        $this->builder = new ObjectBuilder('MyApp\Component\Picker', 'class');
        $this->definition->objectNameDefinition = $this->builder->make();
        $this->assertEquals('MyApp\Component\Picker', $this->definition->getTypeHint());
        $this->assertEquals('MyApp\Component\Picker', $this->definition->getHintOrType());
    }
    
    public function testToArray()
    {
        $this->assertEquals(
            array('scope' => 'public', 'name' => 'boogerFace', 'type' => 'object', 'object' => null, 'description' => null),
            $this->definition->toArray()
        );
    }
    
    public function testGetReturnsName()
    {
        $this->assertEquals('boogerFace', $this->definition->get('name'));
    }
    
    public function testGetBoogerReturnsDefault()
    {
        $this->assertEquals('Definition goes here.', $this->definition->get('description', 'Definition goes here.'));
    }
    
    /**
     * @expectedException InvalidArgumentException 
     */
    public function testGettingInvalidAttributeThrowsException()
    {
        $this->definition->get('booger', true);
    }
    
}