<?php namespace JennyRaider\ObjectBuilder;

use Exception;

class ObjectNameDefinition {
    
    /**
     * This represents the fully qualified name of the object being defined.
     * 
     * E.g...
     * 
     *    "MyApp\Users\User"
     *    "MyApp\Comments\CommentRepository"
     *    "MyApp\Email\MailTransportInterface"
     * 
     * @var string
     */
    protected $fullyQualifiedName;
    
    /**
     * An array containing the individual parts of the name. This array is
     * generated from $this->fullyQualifiedName by $this->getNameParts().
     * 
     * @var array
     */
    protected $nameParts;
    
    /**
     * Aliases are used when generating "Use" statements in the import section
     * of the object. E.g...
     * 
     * use Namespace\ObjectName as Alias;
     *
     * @var string
     */
    protected $alias;
    
    /**
     * See the definition for $this->fullyQualifiedName.
     *
     * @param string $fullyQualifiedName
     */
    public function __construct($fullyQualifiedName = null)
    {
        $this->fullyQualifiedName = $fullyQualifiedName;
    }

    /**
     * Returns a formatted / trimmed version of the fully qualified name
     * of this object including the class name.
     * 
     * @return string
     */
    public function getFullyQualifiedName()
    {
        return trim($this->fullyQualifiedName, '\\');
    }

    /**
     * Aliases are used when generating "Use" statements in the import section
     * of the object. E.g...
     * 
     * use Namespace\ObjectName as Alias;
     *
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias ?: $this->getName();
    }
    
    /**
     * Retrieves a part of the object name, valid parameters are...
     * 
     * "name", "namespace", "varName", "namespaces"
     *
     * @param string $partKey
     * @return mixed
     */
    protected function getPart($partKey)
    {
        $parts = $this->getNameParts();
        return $parts[$partKey];
    }

    /**
     * Returns the object name sans namespaces.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getPart('name');
    }
    
    /**
     * See: $this->getVarNameFrom()
     *
     * @return string
     */
    public function getVarName()
    {
        return $this->getPart('varName');
    }
    
    /**
     * Will return an array of namespaces if they exist or false
     * if the object is not in a namespace.
     *
     * @return mixed
     */
    public function getNamespaces()
    {
        return $this->getPart('namespaces');
    }
    
    /**
     * Returns the fully qualified namespace that the object will
     * be defined in if it exists or false if the object
     * does not have a namespace defined.
     * 
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->getPart('namespace');
    }
    
    /**
     * Explodes and parses $this->nameParts string into the name parts
     * needed to generate nice object definitions like the namespace,
     * the variable name representation for the object name and
     * a nested utility array containing all of the namespaces
     * the object resides in.
     *
     * @return array
     */
    protected function getNameParts()
    {
        if($this->nameParts) return $this->nameParts;
        if(!$nameParts = explode('\\', $this->fullyQualifiedName)) throw new Exception('ClassName: Could not parse parts from class name "'.$this->fullyQualifiedName.'"');
        if(count($nameParts) == 1)
        {
            $this->nameParts = array(
                'name' => trim($this->fullyQualifiedName, '\\'),
                'varName' => $this->getVarNameFrom(trim($this->fullyQualifiedName, '\\')),
                'namespaces' => array(),
                'namespace' => false,
            );
        } else {
            $namespaces = $nameParts;
            array_pop($namespaces);
            $this->nameParts = array(
                'name' => end($nameParts),
                'varName' => $this->getVarNameFrom(end($nameParts)),
                'namespaces' => $namespaces,
                'namespace' => implode('\\', $namespaces),
            );
        }
        return $this->nameParts;
    }
    
    /**
     * Takes an array of search parameters, searches for them inside
     * $subject and returns a string with with the search terms
     * replaced with whatever is specified in $replace.
     * Always case insensitive.
     * 
     * @param  array     $search
     * @param  string    $replace
     * @param  string    $subject
     * @return string
     */
    protected function strGroupReplace($search = array(), $replace = "", $subject)
    {
        foreach($search as $term) $subject = str_ireplace($term, $replace, $subject);
        return $subject;
    }

    /**
     * Takes an object name (without the namespace) and strips some common
     * words that are typically used in class name definitions but not
     * desired as a part of the variable name.
     * 
     * E.g...
     * 
     *    $this->getVarNameFrom("RepositoryInterface") returns "repository"
     *    $this->getVarNameFrom("ConcreteInstrument") returns "instrument"
     *    $this->getVarNameFrom("AbstractObjectDefinition") returns "objectDefinition"
     *    $this->getVarNameFrom("UserRepositoryInterface") returns "userRepository"
     * 
     * @param string    $objectName
     * @return string
     */
    protected function getVarNameFrom($objectName)
    {
        $words = array('interface', 'abstract', 'concrete');
        return lcfirst($this->strGroupReplace($words, "", $objectName));
    }

    /**
     * Takes a scope and description and returns a variable definition
     * that represents this object name.
     * 
     * @param string    $scope ("public", "private" or "protected")
     * @param string    $description
     * @return JennyRaider\ObjectBuilder\VariableDefinition
     */
    public function getVariableDefinition($scope, $description = null)
    {
        return new VariableDefinition($scope, $this->getVarName(), 'object', $this, $description);
    }
    
    public function toArray()
    {
        return array_merge($this->getNameParts(), array('fullName' => $this->getFullyQualifiedName()));
    }
    
    public function __toString()
    {
        $string = "";
        $string .= "Name: " . $this->getName() . "\r\n";
        $string .= "Var:  " . $this->getVarName() . "\r\n";
        $string .= "NS:   " . $this->getNamespace() . "\r\n";
        $string .= "Full: " . $this->getFullyQualifiedName() . "\r\n";
        return $string;
    }
    
    public function setFullyQualifiedName($fullyQualifiedName)
    {
        $this->fullyQualifiedName = $fullyQualifiedName;
    }
    
    public function setNamespace($namespace)
    {
        $this->setFullyQualifiedName(trim($namespace, '\\') . $this->getName());
    }

    public function setNamespaces(array $namespaces)
    {
        $namespace = implode('\\', $namespaces);
        $this->setNamespace($namespace);
    }

}
