<?php

use JennyRaider\ObjectBuilder\ObjectDefinition;

class ObjectDefinitionTest extends PHPUnit_Framework_TestCase {
    
    protected $definition;
    
    public function setUp()
    {
        $this->definition = new ObjectDefinition('MyApp\Component\BoogerFace');
    }
    
    public function testToArray()
    {
        $expectedArray = array(
            'name' => 'BoogerFace',
            'varName' => 'boogerFace',
            'namespaces' => array('MyApp', 'Component'),
            'namespace' => 'MyApp\Component',
            'fullName' => 'MyApp\Component\BoogerFace',
            'type' => 'class',
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
        $this->assertEquals('BoogerFace', $this->definition->get('name'));
    }
    
    public function testGetBooger()
    {
        $this->assertEquals(false, $this->definition->get('booger', false));
    }
    
}