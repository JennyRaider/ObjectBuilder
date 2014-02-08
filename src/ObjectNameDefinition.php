<?php

/**
 * Any given object made by the object builder is comprised of 3 main types of pseudo
 * objects; the name definition, variable definitions and the object definition
 * itself. The name definition handles processing of names including parsing
 * out "Interface", "Abstract" and "Concrete" from object names when making
 * corresponding variable names. The object definition is extended from
 * a name definition, and a variable definition will contain a name
 * definition if the variable represents an object.
 *
 * @category    JennyRaider
 * @package     ObjectBuilder
 * @author      Gary Saunders <gary@codenamegary.com>
 * @copyright   2014 JennyRaider
 * @license     http://opensource.org/licenses/MIT   MIT License
 * @link        https://bitbucket.org/jennyraider/objectbuilder
 */

namespace JennyRaider\ObjectBuilder;

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
     * Sets a new fully qualified name to be parsed by the definition.
     */
    public function setFullyQualifiedName($fullyQualifiedName)
    {
        // Nameparts is used to get the various pieces of a name, here
        // we null it out as it is cached by the getNameParts method
        // for minor performance optimization.
        $this->nameParts = null;
        $this->fullyQualifiedName = $fullyQualifiedName;
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
     * Aliases are used when generating "Use" statements in the import section
     * of the object. E.g...
     * 
     * use Namespace\ObjectName as Alias;
     *
     * @param string $alias
     * @return JennyRaider\ObjectBuilder\ObjectNameDefinition
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }
    
    /**
     * Retrieves a part of the object name, valid parameters are...
     * 
     * "name", "namespace", "varName", "namespaces"
     *
     * @param string $partKey
     * @return mixed
     */
    protected function getPart($partKey, $default = false)
    {
        $parts = $this->getNameParts();
        return isset($parts[$partKey]) ? $parts[$partKey] : $default;
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
     * Updates the object name.
     * 
     * @return JennyRaider\ObjectBuilder\ObjectNameDefinition
     */
    public function setName($name)
    {
        $namespace = $this->getNamespace();
        $this->setFullyQualifiedName($namespace . '\\' . $name);
        return $this;
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
     * Will return an array of namespaces if they exist or an empty
     * array if the object is not in a namespace.
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->getPart('namespaces', array());
    }

    /**
     * Sets a new namespace for the object using the given array of
     * namespace segments.
     *
     * @param array     $namespaces
     * @return JennyRaider\ObjectBuilder\ObjectNameDefinition
     */
    public function setNamespaces(array $namespaces)
    {
        $namespace = implode('\\', $namespaces);
        $this->setNamespace($namespace);
        return $this;
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
        return $this->getPart('namespace', false);
    }
    
    /**
     * Switches out the namespace that the object resides in.
     * 
     * @param string    $namespace  E.g. - 'MyApp\Component'
     * @return JennyRaider\ObjectBuilder\ObjectNameDefinition
     */
    public function setNamespace($namespace)
    {
        $this->setFullyQualifiedName(trim($namespace, '\\') . '\\' . $this->getName());
        return $this;
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
        if(!$nameParts = explode('\\', $this->fullyQualifiedName)) throw new Exception(get_called_class() . ': Could not parse parts from class name "'.$this->fullyQualifiedName.'"');
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
    
    /*
     * Creates a well formatted array containing all the data for this name definition.
     * 
     * @return array
     */
    public function toArray()
    {
        return array_merge($this->getNameParts(), array('fullName' => $this->getFullyQualifiedName()));
    }
    
    /**
     * Convenience method for echo'ing and dumping a name definition.
     * 
     * @return string
     */
    public function __toString()
    {
        $string = "";
        $string .= "Name: " . $this->getName() . "\r\n";
        $string .= "Var:  " . $this->getVarName() . "\r\n";
        $string .= "NS:   " . $this->getNamespace() . "\r\n";
        $string .= "Full: " . $this->getFullyQualifiedName() . "\r\n";
        return $string;
    }
    
}
