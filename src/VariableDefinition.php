<?php

/**
 * A variable definition represents the parts of a class variable declaration in
 * a fashion that makes it simple to output real code. The variable definition
 * contains the core parts of a declaration (e.g. - scope, name, type) and
 * also allows an ObjectDefinition to be specified as the typehint for
 * the variable. This is used to generate the appropriate docblocks
 * that contain IDE friendly typehints for auto-completion.
 *
 * @category    JennyRaider
 * @package     ObjectBuilder
 * @author      Gary Saunders <gary@codenamegary.com>
 * @copyright   2014 JennyRaider
 * @license     http://opensource.org/licenses/MIT   MIT License
 * @link        https://bitbucket.org/jennyraider/objectbuilder
 */
 
namespace JennyRaider\ObjectBuilder;

use InvalidArgumentException;

class VariableDefinition {

    /**
     * @var string
     */    
    public $scope;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var type
     */
    public $type;

    /**
     * @var JennyRaider\ObjectBuilder\ObjectNameDefinition
     */
    public $objectNameDefinition;
    
    /**
     * @var string
     */
    public $description;
    
    /**
     * @param string                                                $scope                  "public", "protected" or "private"
     * @param string                                                $name                   The name of the variable.
     * @param string                                                $type                   The native type of the variable, e.g. - "string", "array", "int", "float".
     * @param JennyRaider\ObjectBuilder\ObjectNameDefinition|null   $objectNameDefinition   (optional) The Object this variable is representing.
     * @param string|null                                           $description            (optional) The description is used when generating a docblock for the variable.
     */
    public function __construct($scope, $name, $type, ObjectNameDefinition $objectNameDefinition = null, $description = null)
    {
        $this->scope = $scope;
        $this->name = $name;
        $this->type = $type;
        $this->objectNameDefinition = $objectNameDefinition;
        $this->description = $description;
    }
    
    /**
     * If the variable represents an objectNameDefinition, this function
     * will return the fully qualified name of the object it represents.
     * If the variable does NOT represent an object, this function will
     * return false.
     * 
     * @return string
     */
    public function getTypeHint()
    {
        if(null !== $this->objectNameDefinition) return $this->objectNameDefinition->getFullyQualifiedName();
        return false;
    }

    /**
     * If the variable represents an objectNameDefinition, this function
     * will return the fully qualified name of the object it represents.
     * If the variable does NOT represent an object, this function will
     * return the native type defined for this variable (string, etc).
     * 
     * @return string
     */
    public function getHintOrType()
    {
        if(false !== $typeHint = $this->getTypeHint()) return $typeHint;
        return $this->type;
    }
    
    /**
     * Utility method for accessing the contents of the definition.
     * 
     * @return array
     */
    public function toArray()
    {
        return array(
            'scope' => $this->scope,
            'name' => $this->name,
            'type' => $this->type,
            'object' => ($this->objectNameDefinition) ? $this->objectNameDefinition->toArray() : null,
            'description' => $this->description,
        );
    }
    
    /**
     * Utility method for getting properties of the definition. This is the
     * method used by a template / view to get data.
     * 
     * @return mixed
     */
    public function get($name, $default = false)
    {
        $attributes = $this->toArray();
        if(!array_key_exists($name, $attributes)) throw new InvalidArgumentException(get_called_class() . ': Attribute "' . $name . '" is not defined.');
        if(array_key_exists($name, $attributes) and $attributes[$name] !== null) return $attributes[$name];
        return $default;
    }
    
}
