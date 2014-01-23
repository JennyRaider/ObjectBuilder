<?php namespace JennyRaider\ObjectBuilder;

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
     * return a blank string.
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
     * return the type defined for this variable (string, array, etc).
     * 
     * @return string
     */
    public function getHintOrType()
    {
        if(false !== $typeHint = $this->getTypeHint()) return $typeHint;
        return $this->type;
    }
    
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
    
}
