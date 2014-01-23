<?php namespace JennyRaider\ObjectBuilder;

class ObjectPresenter {
    
    /**
     * @var JennyRaider\ObjectBuilder\ObjectDefinition
     */
    protected $objectDefinition;
    
    public function __construct(ObjectDefinition $objectDefinition)
    {
        $this->objectDefinition = $objectDefinition;
    }
    
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
    
    public function getExtendsStatement()
    {
        if(!$this->objectDefinition->extend) return '';
        return ' extends ' . $this->objectDefinition->extend[0]->getAlias();
    }

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
    
    public function __get($name)
    {
        return $this->objectDefinition->{$name};
    }
    
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->objectDefinition, $name), $arguments);
    }
    
}
