<?php

/**
 * The ObjectBuilder interface is the contract implemented by other packages in
 * order to build an ObjectDefinition. Crafting an ObjectDefinition by hand is
 * achievable but tedious, this interface and the underlying implementation
 * makes it much easier to define objects, it has a fluent interface and
 * much more user-friendly syntax.
 *
 * @category    JennyRaider
 * @package     ObjectBuilder
 * @author      Gary Saunders <gary@codenamegary.com>
 * @copyright   2014 JennyRaider
 * @license     http://opensource.org/licenses/MIT   MIT License
 * @link        https://bitbucket.org/jennyraider/objectbuilder
 */

namespace JennyRaider\ObjectBuilder;

interface ObjectBuilderInterface {

    /**
     * The constructor takes 2 arguments in order to build the base object definition it requires.
     *
     * @param string    $fullyQualifiedName     Example: MyApp\Component\ClassName
     * @param string    $type                   Example: "class" or "interface" or "final class" or "trait" etc.
     */
    public function __construct($fullyQualifiedName, $type);

    /**
     * Adds a definition to import, e.g. - "use" in the top of the file.
     *
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function imports(ObjectNameDefinition $objectDefintion);

    /**
     * Rather than define and object and pass it to import, you can simply
     * use this function for convenience and give it a fully qualified
     * class name. The builder will create the object defintion for
     * you and call the imports() function.
     *
     * @param  string   $fullyQualifiedName
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function importsName($fullyQualifiedName);

    /**
     * Specifies an object that should be imported and then injected into a
     * constructor. This will also create the related class variable as
     * necessary.
     *
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function injects(ObjectDefinition $objectDefinition);

    /**
     * Same as injects but accetps a fully qualified class name instead of a
     * pre-built object definition for convenience. You may also pass an
     * alias if you want the import to be alias'd.
     *
     * @param  string   $fullyQualifiedName
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function injectsName($fullyQualifiedName, $alias = null);

    /**
     * Specifies a trait to be used by the object. This is a "use" statement
     * inside the class definition.
     *
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function uses(ObjectDefinition $objectDefinition);

    /**
     * Same as uses() but accepts a fully qualified class name for convenience.
     *
     * @param  string   $fullyQualifiedName
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function usesName($fullyQualifiedName);

    /**
     * An object should be extended from. The generated definition will contain
     * methods to easily generate the "object extends other_object" statement.
     *
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function extend(ObjectDefinition $objectDefinition);

    /**
     * Same as extend() but accepts a fully qualified class name for convenience.
     *
     * @param  string   $fullyQualifiedName
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function extendName($fullyQualifiedName);

    /**
     * An interface that this object will implement. You can call this method
     * multiple times to implement multiple interfaces.
     *
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function implement(ObjectDefinition $objectDefinition);

    /**
     * Same as implement() but accepts a fully qualified class name for convenience.
     *
     * @param  string   $fullyQualifiedName
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function implementName($fullyQualifiedName);

    /**
     * Adds a variable into the class to represent the specified object. For scope,
     * specify "private", "protected" or "public". You may also prefix scope with
     * "final" if desired.
     *
     * This function will take the name of the specified object, strip out any generally
     * undesired words (Interface, Class, etc.) and create a pretty variable name
     * that will be defined in the class.
     *
     * E.g.: An object named "UserRepositoryInterface" would be defined in the class
     *       as "$userRepository"
     *
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function objectVariable($scope, ObjectDefinition $objectDefinition);

    /**
     * Same as objectVariable() but accepts a fully qualified name string in
     * place of an object definition for convenience.
     *
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function objectVariableName($scope, $fullyQualifiedName);

    /**
     * Very simply defines the specified variable inside the object.
     *
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function defineVariable(VariableDefinition $variableDefinition);

    /**
     * Same as defineVariable but takes a series of parameters and constructs
     * the VariableDefinition object rather than requiring the object to
     * be pre-defined.
     *
     * @param  JennyRaider\ObjectBuilder\ObjectDefinition $objectDefinition
     * @return JennyRaider\ObjectBuilder\ObjectDefinitionBuilder
     */
    public function defineVariableName($scope, $name, $type, $description = null);

    /**
     * Returns the object definition containing all of the parameters captured
     * by the builder.
     *
     * @return JennyRaider\ObjectBuilder\ObjectDefinition
     */
    public function make();

    /**
     * Returns the presenter that represents the object made
     * by the builder.
     *
     * @return JennyRaider\ObjectBuilder\ObjectPresenter
     */
    public function present();

}
