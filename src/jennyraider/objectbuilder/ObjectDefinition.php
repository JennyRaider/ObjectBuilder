<?php

/**
 * An ObjectDefinition is a group of meta informatino that describes a real PHP object.
 *
 * It can describe any native PHP object, a class, an interface or a trait etc.. After
 * fully describing an object as an ObjectDefinion, the ObjectDefinition can then be
 * passed to a view or template. The ObjectDefinition will contain all of the info
 * required in order for the view to create a fully defined object including
 * namespace, imports, extends, implements, variables and other information.
 * 
 * @category    JennyRaider
 * @package     ObjectBuilder
 * @author      Gary Saunders <gary@codenamegary.com>
 * @copyright  2014 JennyRaider
 * @license    http://opensource.org/licenses/MIT   MIT License
 * @link       https://bitbucket.org/jennyraider/objectbuilder
 */
 
namespace JennyRaider\ObjectBuilder;

use Exception;

class ObjectDefinition extends ObjectNameDefinition {
    
    /**
     * The type of object to define, can be any valid object type.
     * 
     * @var string  May be "class", "trait", "abstract class" or "interface", can be prefixed with "final" if desired.
     */
    public $type = "class";
    
    /**
     * A nested array containing all the parts of the class that get populated
     * using definitions of other classes.
     *  
     * @var array
     */    
    protected $attributes = array(
        // These are classes from other namespaces to be used
        'import' => array(),
        // These are traits to compose into the class
        'use' => array(),
        // Variables to be defined as class vars
        'variable' => array(),
        // Classes to import and inject into the constructor
        'inject' => array(),
        // Google it
        'extend' => array(),
        // Google it
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
        if(!isset($this->attributes[$attributeName])) throw new Exception('ObjectDefinition: Invalid attribute "'.$attributeName.'" passed to add method.');
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
     * Returns the presenter for this definition which makes it easier to consume
     * by a template or other front-end view.
     *
     * @return JennyRaider\ObjectBuilder\ObjectPresenter
     */
    public function presenter()
    {
        return new ObjectPresenter($this);
    }
    
    /**
     * Makes a simple array representing all the attributes of the object and returns it.
     * This is useful for consuming an ObjectDefinition from a template or other view.
     * "toArray()" is not used internally for any reason, it is here simply as a
     * helper function for views that use ObjectDefinitions.
     *
     * @return array
     */
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
    
    /**
     * Returns a string representation of the object, currently this happens simply
     * by taking the toArray() function and converting the results into a string
     * using print_r.
     * 
     * @return string
     */
    public function __toString()
    {
        return print_r($this->toArray(), true);
    }
    
    /**
     * Looks through the $attributes property and returns the associated value.
     * 
     * @return mixed
     */
    public function __get($name)
    {
        if(in_array($name, array_keys($this->attributes))) return $this->attributes[$name];
    }
    
    /**
     * Looks for a getter method matching the name "get" . $key and returns the
     * result if found. If not, look for $key in the attributes property and if
     * all else fails, returns $default.
     * 
     * @return mixed
     */
    public function get($key, $default = false)
    {
        $getterName = 'get' . ucfirst($key);
        if(method_exists($this, $getterName)) return $this->{$getterName}();
        if(isset($this->attributes[$key])) return $this->attributes[$key];
        return $default;
    }
    
    /**
     * Simple getter that returns the type of object, e.g. - "class", "interface",
     * "final class", "trait" etc..
     * 
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
}
