<?php

use JennyRaider\ObjectBuilder\ObjectBuilder;
use JennyRaider\ObjectBuilder\ObjectPresenter;

class ObjectPresenterTest extends PHPUnit_Framework_TestCase {
    
    protected $builder;
    protected $presenter;
    
    public function setUp()
    {
        $this->builder = new ObjectBuilder('MyApp\Component\Printer', 'class');
        $this->builder->injectsName('MyApp\Component\DocumentRepository');
        $this->builder->injectsName('MyApp\Component\InkRepository');
        $this->builder->extendName('MyApp\Component\BaseObject');
        $this->builder->implementName('MyApp\Component\PrinterInterface');
        $this->builder->usesName('MyApp\Component\PrintableTrait');
        $this->presenter = $this->builder->present();
    }
    
    public function testConstructorArguments()
    {
        $this->assertEquals('DocumentRepository $documentRepository, InkRepository $inkRepository', $this->presenter->getConstructorArgumentStatement());
    }
    
    public function testImplementsStatement()
    {
        $this->assertEquals(' implements PrinterInterface', $this->presenter->getImplementsStatement());
    }
    
    public function testExtendsStatement()
    {
        $this->assertEquals(' extends BaseObject', $this->presenter->getExtendsStatement());
    }

    public function testGetMagic()
    {
        $this->assertEquals('class', $this->presenter->type);
    }
    
    public function testCallMagic()
    {
        $this->assertEquals('Printer', $this->presenter->get('name'));
    }
    
}
