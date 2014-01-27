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
    
    public function testExtendsFoo()
    {
        $this->builder->extendName('MyApp\Component\Foo');
        $objectDefinition = $this->builder->make();
        $extends = $objectDefinition->get('extend');
        $foo = $extends[0];
        $this->assertTrue($foo->get('name') === 'Foo');
    }
    
    public function testInjectsFoo()
    {
        $this->builder->injectsName('MyApp\Component\Foo');
        $injects = $this->builder->make()->get('inject');
        $foo = $injects[0];
        $this->assertTrue($foo->get('name') === 'Foo');
    }
    
    public function testImportsFoo()
    {
        $this->builder->importsName('MyApp\Component\Foo');
        $imports = $this->builder->make()->get('import');
        $foo = $imports[0];
        $this->assertTrue($foo->get('name') === 'Foo');
    }
    
    public function testUsesFoo()
    {
        $this->builder->usesName('MyApp\Component\Foo');
        $uses = $this->builder->make()->get('use');
        $foo = $uses[0];
        $this->assertTrue($foo->get('name') === 'Foo');
    }
    
    public function testImplementsFooInterface()
    {
        $this->builder->implementName('MyApp\Component\FooInterface');
        $implements = $this->builder->make()->get('implement');
        $foo = $implements[0];
        $this->assertTrue($foo->get('name') === 'FooInterface');
    }
    
    public function testDefinesObjectFoo()
    {
        $this->builder->objectVariableName('public', 'MyApp\Component\FooObject');
        $variables = $this->builder->make()->get('variable');
        $object = $variables[0];
        $this->assertTrue($object->objectNameDefinition->get('name') === 'FooObject');
        $this->assertTrue($object->getTypeHint() === 'MyApp\Component\FooObject');
        $this->assertTrue($object->get('name') === 'fooObject');
    }
    
    public function testDefinesVariableFoo()
    {
        $this->builder->defineVariableName('public', 'foo', 'string', 'The variable that holds foo.');
        $variables = $this->builder->make()->get('variable');
        $foo = $variables[0];
        $this->assertTrue($foo->get('name') === 'foo');
    }
    
    public function testStripInterface()
    {
        $this->builder->injectsName('MyApp\Component\FooObjectInterface');
        $injects = $this->builder->make()->get('inject');
        $foo = $injects[0];
        $this->assertTrue($foo->get('varName') === 'fooObject');
    }

}