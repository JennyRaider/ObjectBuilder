<?php namespace codenamegary\l4generators;

class ObjectDefinitionBuilder {
    
    /**
     * The object being built.
     * 
     * @var codenamegary\l4generators\ObjectDefinition
     */
    protected $objectDefinition;
    
    /**
     * @param string    $fullyQualifiedName
     */
    public function __construct($fullyQualifiedName, $type)
    {
        $this->objectDefinition = new ObjectDefinition($fullyQualifiedName);
        $this->objectDefinition->type = $type;
    }
    
    /**
     * @param  codenamegary\l4generators\ObjectDefinition $objectDefinition
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function imports(ObjectNameDefinition $objectDefintion)
    {
        $this->objectDefinition->add('import', $objectDefintion);
        return $this;
    }

    /**
     * @param  string   $fullyQualifiedName
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function importsName($fullyQualifiedName)
    {
        $objectDefinition = new ObjectDefinition($fullyQualifiedName);
        return $this->imports($objectDefinition);
    }

    /**
     * @param  codenamegary\l4generators\ObjectDefinition $objectDefinition
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function injects(ObjectDefinition $objectDefinition)
    {
        $this->imports($objectDefinition);
        $this->objectVariable('protected', $objectDefinition);
        $this->objectDefinition->add('inject', $objectDefinition);
        return $this;
    }

    /**
     * @param  string   $fullyQualifiedName
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function injectsName($fullyQualifiedName, $alias = null)
    {
        $objectDefinition = new ObjectDefinition($fullyQualifiedName);
        $objectDefinition->setAlias($alias);
        return $this->injects($objectDefinition);
    }

    /**
     * @param  codenamegary\l4generators\ObjectDefinition $objectDefinition
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function uses(ObjectDefinition $objectDefinition)
    {
        $this->imports($objectDefinition);
        $this->objectDefinition->add('use', $objectDefinition);
        return $this;
    }

    /**
     * @param  string   $fullyQualifiedName
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function usesName($fullyQualifiedName)
    {
        $objectDefinition = new ObjectDefinition($fullyQualifiedName);
        return $this->uses($objectDefinition);
    }

    /**
     * @param  codenamegary\l4generators\ObjectDefinition $objectDefinition
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function extend(ObjectDefinition $objectDefinition)
    {
        $this->imports($objectDefinition);
        $this->objectDefinition->add('extend', $objectDefinition);
        return $this;
    }

    /**
     * @param  string   $fullyQualifiedName
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function extendName($fullyQualifiedName)
    {
        $objectDefinition = new ObjectDefinition($fullyQualifiedName);
        return $this->extend($objectDefinition);
    }

    /**
     * @param  codenamegary\l4generators\ObjectDefinition $objectDefinition
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function implement(ObjectDefinition $objectDefinition)
    {
        $this->imports($objectDefintion);
        $this->objectDefinition->add('implement', $objectDefinition);
        return $this;
    }

    /**
     * @param  string   $fullyQualifiedName
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function implementName($fullyQualifiedName)
    {
        $objectDefinition = new ObjectDefinition($fullyQualifiedName);
        return $this->implement($objectDefinition);
    }

    /**
     * @param  codenamegary\l4generators\ObjectDefinition $objectDefinition
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function objectVariable($scope, ObjectDefinition $objectDefinition)
    {
        $this->defineVariable($objectDefinition->getVariableDefinition($scope));
        return $this;
    }

    /**
     * @param  codenamegary\l4generators\ObjectDefinition $objectDefinition
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function objectVariableName($scope, $fullyQualifiedName)
    {
        $objectDefinition = new ObjectDefinition($fullyQualifiedName);
        return $this->defineVariable($objectDefinition->getVariableDefinition($scope));
    }

    /**
     * @param  codenamegary\l4generators\ObjectDefinition $objectDefinition
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function defineVariable(VariableDefinition $variableDefinition)
    {
        $this->objectDefinition->add('variable', $variableDefinition);
        return $this;
    } 

    /**
     * @param  codenamegary\l4generators\ObjectDefinition $objectDefinition
     * @return codenamegary\l4generators\ObjectDefinitionBuilder
     */
    public function defineVariableName($scope, $name, $type, $description = null)
    {
        $variableDefinition = new VariableDefinition($scope, $name, $type, null, $description);
        return $this->defineVariable($variableDefinition);
    }

    /**
     * @return codenamegary\l4generators\ObjectDefinition
     */
    public function make()
    {
        return $this->objectDefinition;
    }
    
}
