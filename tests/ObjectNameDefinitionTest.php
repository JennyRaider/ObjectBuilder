<?php

use JennyRaider\ObjectBuilder\ObjectNameDefinition;
use JennyRaider\ObjectBuilder\VariableDefinition;

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
         $this->definition = new ObjectNameDefinition('MyApp\Component\QuxInterface');
         $this->assertEquals('qux', $this->definition->getVarName());
    }
    
    public function testGetNamespaces()
    {
        $this->definition = new ObjectNameDefinition('Ns1\Ns2\Ns3\Quxxer');
        $this->assertEquals(
            array('Ns1', 'Ns2', 'Ns3'),
            $this->definition->getNamespaces()
        );
    }
    
    public function testGetNamespace()
    {
        $this->assertEquals('MyApp\Component', $this->definition->getNamespace());
    }
    
    public function testGetVariableDefinition()
    {
        $varDefinition = new VariableDefinition('public', 'qux', 'object', $this->definition, 'Description goes here.');
        $this->assertEquals($varDefinition, $this->definition->getVariableDefinition('public', 'Description goes here.'));
    }
    
    public function testToArray()
    {
        $this->assertEquals(
            array(
                'name' => 'Qux',
                'varName' => 'qux',
                'namespaces' => array('MyApp', 'Component'),
                'namespace' => 'MyApp\Component',
                'fullName' => 'MyApp\Component\Qux',
            ),
            $this->definition->toArray()
        );
    }
    
    public function testSetFullyQualifiedName()
    {
        $this->definition->setFullyQualifiedName('Ns1\Ns2\Ns3\Booger');
        $this->assertEquals('Ns1\Ns2\Ns3\Booger', $this->definition->getFullyQualifiedName());
    }
    
    public function testSetNamespace()
    {
        $this->definition->setNamespace('Ns1\Ns2');
        $this->assertEquals('Ns1\Ns2', $this->definition->getNamespace());
        $this->assertEquals('Ns1\Ns2\Qux', $this->definition->getFullyQualifiedName());
    }
    
    public function testSetNamespaces()
    {
        $this->definition->setNamespaces(array('Ns1', 'Ns2'));
        $this->assertEquals(array('Ns1', 'Ns2'), $this->definition->getNamespaces());
        $this->assertEquals('Ns1\Ns2\Qux', $this->definition->getFullyQualifiedName());
    }
    
}