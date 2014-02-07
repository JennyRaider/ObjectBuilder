# JennyRaider\ObjectBuilder

ObjectBuilder is a utility for fully describing and building the schema of an object. JennyRaider uses the ObjectBuilder to describe an object (class, interface, etc.) and passes the resulting defintion to a view in order to more easily render a real file.

*WARNING*: ObjectBuilder is, to some degree, opinionated about style. As the examples show, the built definitions do things like convert a StudlyCaps "ObjectName" to camel cased "objectName" when a variable of type ObjectName is to be used as a class variable. While the builder does permit and condone the usage of suffixes and prefixes like "Interface" and "Abstract" for naming, JennyRaider will strip those terms from the corresponding variable name of an object; if you define an object as having a class variable of type "FooRepositoryInterface", ObjectBuilder will include a corresponding class variable called "$fooRepository".

## API

API Documentation can be found at:

    [http://docs.jennyraider.com/objectbuilder](http://docs.jennyraider.com/objectbuilder)

## Example Usage

### Example 1: Define a simple class

    <?php
    // Pass the fully qualified name of the object you want to describe as the first
    // parameter and the type of object you want to describe as the 2nd
    $builder = new ObjectBuilder('MyApp\Component\Object', 'class');
    $objectDefinition = $builder->make();
    var_dump($objectDefinition->toArray());

Will produce...  

    array(12) {
      'name' => string(6) "Object"
      'varName' => string(6) "object"
      'namespaces' => array(2) {
        [0] => string(5) "MyApp"
        [1] => string(9) "Component"
      }
      'namespace' => string(15) "MyApp\Component"
      'fullName' => string(22) "MyApp\Component\Object"
      'type' => string(5) "class"
      'import' => array(0) {
      }
      'use' => array(0) {
      }
      'variable' => array(0) {
      }
      'inject' => array(0) {
      }
      'extend' => array(0) {
      }
      'implement' => array(0) {
      }
    }

### Example 2: Extending and Implementing

This example defines a class that extends Foo, implements FooInterface and requires a BazInterface to be injected into the constructor.

    <?php
    $builder = new ObjectBuilder('MyApp\Component\Object', 'class');
    $builder->extendName('Foo');
    $builder->implementName('FooInterface');
    $builder->injectsName('BazInterface');
    $objectDefinition = $builder->make();
    var_dump($objectDefinition->toArray());


Will produce (shortened for brevity)...

    array(12) {
      'name' => string(6) "Object"
      'varName' => string(6) "object"
      'namespaces' => array(2) {
        [0] => string(5) "MyApp"
        [1] => string(9) "Component"
      }
      'namespace' => string(15) "MyApp\Component"
      'fullName' => string(22) "MyApp\Component\Object"
      'type' => string(5) "class"
      'import' => array(3) {
        [0] => { ObjectDefinition for Foo ... }
        [1] => { ObjectDefinition for FooInterface ... }
        [2] => { ObjectDefinition for BazInterface ... }
      }
      'use' =>
      'variable' => array(1) {
        [0] => array(5) {
          'scope' => string(9) "protected"
          'name' => string(3) "baz"
          'type' => string(6) "object"
          'object' => array(12) { ObjectDefinition for BazInterface ... }
          'description' => NULL
        }
      }
      'inject' => array(1) {
        [0] => { ObjectDefinition for BazInterface ... }
      }
      'extend' => array(1) {
        [0] => array(12) {
          'name' => string(3) "Foo"
          'varName' => string(3) "foo"
          'namespaces' => array(0) { }
          'namespace' => bool(false)
          'fullName' => string(3) "Foo"
          'type' => string(5) "class"
          ...
        }
      }
      'implement' => array(1) {
        [0] => array(12) {
          'name' => string(12) "FooInterface"
          'varName' => string(3) "foo"
          ...
        }
      }
    }

# FAQ

*Why the inconsistency between singular and plural forms of things, e.g. - $builder->implement vs. $builder->implements?*

Certain words are reserved by PHP. I don't like it anymore than you do.

# LICENSE

The MIT License (MIT)

Copyright (c) 2014 JennyRaider

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.