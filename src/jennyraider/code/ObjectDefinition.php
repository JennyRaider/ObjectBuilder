<?php namespace codenamegary\l4generators;

use Exception;

class ObjectDefinition extends ObjectNameDefinition {
    
    /**
     * The type of object to define, can be any valid object type.
     * 
     * @var string  May be "class", "trait", "abstract class" or "interface", any could be prefixed with "final" if desired.
     */
    public $type = "class";
    
    /**
     * A nested array containing all the parts of the class that get populated
     * using definitions of other classes.
     *  
     * @var array
     */    
    protected $attributes = array(
        'import' => array(),
        'use' => array(),
        'variable' => array(),
        'inject' => array(),
        'extend' => array(),
        'implement' => array(),
    );
    
    /**
     * @param string $fullyQualifiedName
     */
    public function __construct($fullyQualifiedName = null)
    {
        parent::__construct($fullyQualifiedName);
    }
    
    /**
     * Adds an item to one of the attribute arrays. Examples....
     * 
     *    $this->import($someClassNameDefintion);
     *    $this->variable($someVariableDefinition);
     *    $this->inject($someClassNameDefinition);
     *    $this->use($someClassNameDefinition);
     * 
     * @param string $attributeName
     * @param object $item
     */
    public function add($attributeName, $item)
    {
        if(!isset($this->attributes[$attributeName])) throw new Exception('ClassDefinition: Invalid attribute "'.$attributeName.'" passed to add method.');
        $this->attributes[$attributeName][] = $item;
    }
    
    /**
     * Magic method to handle dispatching method calls for adding items
     * to the attributes array. Will be triggered if $name matches
     * one of the array keys from $this->attributes
     */
    public function __call($name, $arguments)
    {
        $name = trim(strtolower($name));
        if(in_array($name, array_keys($this->attributes))) $this->add($name, $arguments[0]);
        if(strlen($name) > 3 and substr($name, 0, 3) == "get" and in_array(substr($name, 2), array_keys($this->attributes)) !== false) return $this->attributes[substr($name, 2)];
    }

    /**
     * @return codenamegary\l4generators\ObjectPresenter
     */
    public function presenter()
    {
        return new ObjectPresenter($this);
    }
    
    public function toArray()
    {
        $parentArray = parent::toArray();
        $thisArray = array('type' => $this->type);
        foreach($this->attributes as $key => $objects)
        {
            $thisArray[$key] = array();
            foreach($objects as $o) $thisArray[$key][] = $o->toArray();
        }
        return array_merge($parentArray, $thisArray);
    }
    
    public function __toString()
    {
        return print_r($this->toArray(), true);
    }
    
    public function __get($name)
    {
        if(in_array($name, array_keys($this->attributes))) return $this->attributes[$name];
    }
    
    public function get($key)
    {
        return $this->attributes[$key];
    }
    
    public function getType()
    {
        return $this->type;
    }
    
}
