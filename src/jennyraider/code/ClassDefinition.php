<?php namespace JennyRaider\Code;

class ClassDefinition extends ObjectDefinition {
    
    public $type = "class";
    
    /**
     * @param string $fullyQualifiedClassName
     */
    public function __construct($fullyQualifiedClassName = null)
    {
        parent::__construct($fullyQualifiedClassName);
    }
    
}
