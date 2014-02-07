<?php

/**
 * The ObjectBuilder is used to construct ObjectDefinitions. The definition objects can
 * be constructed by hand but the interface and methods are tedious to consume. The
 * ObjectBuilder provides a fluent interface to construct ObjectDefinitions using
 * mostly strings, rather than passing objects around, and has a much more user
 * friendly syntax.
 *
 * @category    JennyRaider
 * @package     ObjectBuilder
 * @author      Gary Saunders <gary@codenamegary.com>
 * @copyright  2014 JennyRaider
 * @license    http://opensource.org/licenses/MIT   MIT License
 * @link       https://bitbucket.org/jennyraider/objectbuilder
 */

namespace JennyRaider\ObjectBuilder;

class ObjectBuilder implements ObjectBuilderInterface {
    
    /**
     * The object being built.
     * 
     * @var JennyRaider\ObjectBuilder\ObjectDefinition
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
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function imports(ObjectNameDefinition $objectDefintion)
    {
        $this->objectDefinition->add('import', $objectDefintion);
        return $this;
    }

    /**
     * @param  string   $fullyQualifiedName
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function importsName($fullyQualifiedName)
    {
        $objectDefinition = new ObjectDefinition($fullyQualifiedName);
        return $this->imports($objectDefinition);
    }

    /**
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
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
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function injectsName($fullyQualifiedName, $alias = null)
    {
        $objectDefinition = new ObjectDefinition($fullyQualifiedName);
        $objectDefinition->setAlias($alias);
        return $this->injects($objectDefinition);
    }

    /**
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function uses(ObjectDefinition $objectDefinition)
    {
        $this->imports($objectDefinition);
        $this->objectDefinition->add('use', $objectDefinition);
        return $this;
    }

    /**
     * @param  string   $fullyQualifiedName
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function usesName($fullyQualifiedName)
    {
        $objectDefinition = new ObjectDefinition($fullyQualifiedName);
        return $this->uses($objectDefinition);
    }

    /**
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function extend(ObjectDefinition $objectDefinition)
    {
        $this->imports($objectDefinition);
        $this->objectDefinition->add('extend', $objectDefinition);
        return $this;
    }

    /**
     * @param  string   $fullyQualifiedName
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function extendName($fullyQualifiedName)
    {
        $objectDefinition = new ObjectDefinition($fullyQualifiedName);
        return $this->extend($objectDefinition);
    }

    /**
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function implement(ObjectDefinition $objectDefinition)
    {
        $this->imports($objectDefinition);
        $this->objectDefinition->add('implement', $objectDefinition);
        return $this;
    }

    /**
     * @param  string   $fullyQualifiedName
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function implementName($fullyQualifiedName)
    {
        $objectDefinition = new ObjectDefinition($fullyQualifiedName);
        return $this->implement($objectDefinition);
    }

    /**
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function objectVariable($scope, ObjectDefinition $objectDefinition)
    {
        $this->defineVariable($objectDefinition->getVariableDefinition($scope));
        return $this;
    }

    /**
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function objectVariableName($scope, $fullyQualifiedName)
    {
        $objectDefinition = new ObjectDefinition($fullyQualifiedName);
        return $this->defineVariable($objectDefinition->getVariableDefinition($scope));
    }

    /**
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function defineVariable(VariableDefinition $variableDefinition)
    {
        $this->objectDefinition->add('variable', $variableDefinition);
        return $this;
    } 

    /**
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function defineVariableName($scope, $name, $type, $description = null)
    {
        $variableDefinition = new VariableDefinition($scope, $name, $type, null, $description);
        return $this->defineVariable($variableDefinition);
    }

    /**
     * @return JennyRaider\ObjectBuilder\ObjectDefinition
     */
    public function make()
    {
        return $this->objectDefinition;
    }
    
    /**
     * @return JennyRaider\ObjectBuilder\ObjectPresenter
     */
    public function present()
    {
        return $this->make()->presenter();
    }
    
}
