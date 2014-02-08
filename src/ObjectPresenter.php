<?php

/**
 * Object definitions can be difficult to consume, this presenter contains logic
 * that makes it easier and more consistent to access the content of a defined
 * object and output various parts including the constructor argument list,
 * the object definition statement and various other convenience methods.
 * Calls to properties and methods not defined on the presenter are
 * passed through to the underlying ObjectDefinition.
 *
 * @category    JennyRaider
 * @package     ObjectBuilder
 * @author      Gary Saunders <gary@codenamegary.com>
 * @copyright   2014 JennyRaider
 * @license     http://opensource.org/licenses/MIT   MIT License
 * @link        https://bitbucket.org/jennyraider/objectbuilder
 */
 
namespace JennyRaider\ObjectBuilder;

class ObjectPresenter {
    
    /**
     * @var JennyRaider\ObjectBuilder\ObjectDefinition
     */
    protected $objectDefinition;
    
    /**
     * @param JennyRaider\ObjectDefinition  $objectDefinition
     */
    public function __construct(ObjectDefinition $objectDefinition)
    {
        $this->objectDefinition = $objectDefinition;
    }
    
    /**
     * Compiles and returns a string containing the "implements FooInterface, BazInterface"
     * etc. for all of the interfaces that the object implements. Returns a blank string
     * if the object does not implement any interfaces.
     *
     * @return string
     */
    public function getImplementsStatement()
    {
        $interfaces = $this->implement;
        $glue = ' implements ';
        $statement = '';
        foreach($interfaces as $interface)
        {
            $statement .= $glue . $interface->getAlias();
            $glue = ', ';
        }
        return $statement;
    }
    
    /**
     * Compiles and returns the "extends OtherClass" part of the object definition.
     * If the object does not extend another class, returns a blank string.
     * 
     * @return string
     */
    public function getExtendsStatement()
    {
        if(!$this->objectDefinition->extend) return '';
        return ' extends ' . $this->objectDefinition->extend[0]->getAlias();
    }
    
    /**
     * Returns a compiled list of arguments to be injected into the constructor. If no
     * dependencies (imports) are defined, returns a blank string.
     * 
     * @return string
     */
    public function getConstructorArgumentStatement()
    {
        $statement = '';
        $glue = '';
        foreach($this->inject as $dependency)
        {
            $statement .= $glue . $dependency->getAlias() . ' $' . $dependency->getVarName();
            $glue = ', ';
        }
        return $statement;
    }
    
    /**
     * Passes through references to undefined properties to the underlying ObjectDefinition.
     * 
     * @return mixed
     */
    public function __get($name)
    {
        return $this->objectDefinition->{$name};
    }
    
    /**
     * Passes through calls to undefined methods to the underlying ObjectDefinition.
     * 
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->objectDefinition, $name), $arguments);
    }
    
}
