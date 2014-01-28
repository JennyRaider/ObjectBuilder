<?php

use JennyRaider\ObjectBuilder\ObjectNameDefinition;

class ObjectNameDefinitionTest extends PHPUnit_Framework_TestCase {
    
    protected $definition;
    
    public function setUp()
    {
        $this->definition = new ObjectNameDefinition('MyApp\Component\Qux');
    }
    
    public function testNamespace()
    {
        $this->assertTrue($this->definition->getNamespace() === 'MyApp\Component');
    }
    
    public function testNamespaces()
    {
        $this->assertTrue($this->definition->getNamespaces() === array('MyApp', 'Component'));
    }
    
    public function testNoAlias()
    {
        $this->assertTrue($this->definition->getAlias() === 'Qux');
    }
    
    public function testAliasBaz()
    {
        $this->definition->setAlias('Baz');
        $this->assertTrue($this->definition->getAlias() === 'Baz');
        $this->assertTrue($this->definition->getName() === 'Qux');
    }
    
    public function testGetName()
    {
        $this->assertTrue($this->definition->getName() === 'Qux');
    }
    
    public function testVarName()
    {
        $this->assertTrue($this->definition->getVarName() === 'qux');
    }
    
    public function testVarNameStripsInterface()
    {
        
    }
    
}