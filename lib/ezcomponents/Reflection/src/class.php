<?php
/**
 * File containing the ezcReflectionClass class.
 *
 * @package Reflection
 * @version //autogen//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Extends the ReflectionClass using PHPDoc comments to provide
 * type information
 *
 * @package Reflection
 * @version //autogen//
 * @author Stefan Marr <mail@stefan-marr.de>
 * @author Falko Menge <mail@falko-menge.de>
 */
class ezcReflectionClass extends ReflectionClass
{
    /**
     * @var ezcReflectionDocParser Parser for source code annotations
     */
  static public $docParser;

    /**
     * @var string|object|ReflectionClass
     *      Name, instance or ReflectionClass object of the class to be
     *      reflected
     */
    static public $rclass;

    /**
     * Constructs a new ezcReflectionClass object
     *
     * @param string|object|ReflectionClass $argument
     *        Name, instance or ReflectionClass object of the class to be
     *        reflected
     */
    public function __construct( $argument )
    {
        if ( !$argument instanceof ReflectionClass )
        {
            parent::__construct( $argument );
        }
        $this->rclass = $argument;
        $this->docParser = ezcReflectionApi::getDocParserInstance();
        $this->docParser->parse( $this->getDocComment() );
    }
  /**
   * All property accessible from outside but readonly
   * if property does not exist return null
   *
   * @param string $name
   *
   * @return mixed|null
   */
  public function __get ($name) {
    return ($this->{$name}) ? $this->{$name} : null;
  }

  /**
   * __set trap, property not writeable
   *
   * @param string $name
   * @param mixed $value
   *
   * @return mixed
   */
  function __set ($name, $value) {
    $this->{$name} = $value;
    return  $value;
  }


    /**
     * Use overloading to call additional methods
     * of the ReflectionClass instance given to the constructor
     *
     * @param string $method Method to be called
     * @param array<integer, mixed> $arguments Arguments that were passed
     * @return mixed
     */
    public function __call( $method, $arguments )
    {
        if ( $this->rclass instanceof ReflectionClass )
        {
            // query external reflection object
            return call_user_func_array( array($this->rclass, $method), $arguments );
        } else {
            throw new Exception( 'Call to undefined method ' . __CLASS__ . '::' . $method );
        }
    }

    /**
     * Returns an ezcReflectionMethod object of the method specified by $name.
     *
     * @param string $name Name of the method
     * @return ezcReflectionMethod
     */
    public function getMethod($name) {
    	if ( $this->rclass instanceof ReflectionClass ) {
    		return new ezcReflectionMethod($this->rclass->getMethod($name));
    	} else {
    		return new ezcReflectionMethod(parent::getMethod($name));
    	}
    }

    /**
     * Returns an ezcReflectionMethod object of the constructor method.
     *
     * @return ezcReflectionMethod
     */
    public function getConstructor() {
        if ($this->rclass instanceof ReflectionClass) {
            // query external reflection object
            $constructor = $this->rclass->getConstructor();
        } else {
            $constructor = parent::getConstructor();
        }

        if ($constructor != null) {
            return new ezcReflectionMethod($constructor);
        } else {
            return null;
        }
    }

    /**
     * Returns the methods as an array of ezcReflectionMethod objects.
     *
     * @param integer $filter
     *        A combination of
     *        ReflectionMethod::IS_STATIC,
     *        ReflectionMethod::IS_PUBLIC,
     *        ReflectionMethod::IS_PROTECTED,
     *        ReflectionMethod::IS_PRIVATE,
     *        ReflectionMethod::IS_ABSTRACT and
     *        ReflectionMethod::IS_FINAL
     * @return ezcReflectionMethod[]
     */
    public function getMethods($filter = -1) {
        $extMethods = array();
        if ( $this->rclass instanceof ReflectionClass ) {
            $methods = $this->rclass->getMethods($filter);
        } else {
            $methods = parent::getMethods($filter);
        }
        foreach ($methods as $method) {
            $extMethods[] = new ezcReflectionMethod($method);
        }
        return $extMethods;
    }

    /**
     * Returns an array of all interfaces implemented by the class.
     *
     * @return ezcReflectionClass[]
     */
    public function getInterfaces() {
    	if ( $this->rclass instanceof ReflectionClass ) {
    		$ifaces = $this->rclass->getInterfaces();
    	} else {
    		$ifaces = parent::getInterfaces();
    	}

    	$result = array();
    	foreach ($ifaces as $i) {
    		$result[] = new ezcReflectionClassType($i); //TODO: Shouldn't this be eczReflectionClass
    	}
    	return $result;
    }

    /**
     * Returns the class' parent class, or, if none exists, FALSE
     *
     * @return ezcReflectionClassType|boolean
     */
    public function getParentClass()
    {
        if ( $this->rclass instanceof ReflectionClass )
        {
            // query external reflection object
            $parentClass = $this->rclass->getParentClass();
        } else {
            $parentClass = parent::getParentClass();
        }

        if (is_object($parentClass)) {
            return new ezcReflectionClassType($parentClass);
        }
        else {
            return null;
        }
    }

    /**
     * Returns the class' property specified by its name
     *
     * @param string $name
     * @return ezcReflectionProperty
     * @throws RelectionException if property doesn't exists
     */
    public function getProperty($name) {
		if ( $this->rclass instanceof ReflectionClass )
        {
            // query external reflection object
            $prop = $this->rclass->getProperty($name);
        } else {
            $prop = parent::getProperty($name);
        }

		if (is_object($prop) && !($prop instanceof ezcReflectionProperty)) {
			return new ezcReflectionProperty($prop, $name);
        } else {
			// TODO: may be we should throw an exception here
            return $prop;
        }
    }

    /**
     * Returns an array of this class' properties
     *
     * @param integer $filter
     *        A combination of
     *        ReflectionProperty::IS_STATIC,
     *        ReflectionProperty::IS_PUBLIC,
     *        ReflectionProperty::IS_PROTECTED and
     *        ReflectionProperty::IS_PRIVATE
     * @return ezcReflectionProperty[] Properties of the class
     */
    public function getProperties($filter = -1) {
        if ( $this->rclass instanceof ReflectionClass ) {
        	$props = $this->rclass->getProperties($filter);
        } else {
            //TODO: return ezcReflectionProperty[]
        	$props = parent::getProperties($filter);
        }

        $extProps = array();
        foreach ($props as $prop) {
            $extProps[] = new ezcReflectionProperty( $prop );
        }
        return $extProps;
    }

    /**
     * Returns the short description of the class from the source code
     * documentation
     *
     * @return string short description of the class
     */
    public function getShortDescription() {
        return $this->docParser->getShortDescription();
    }

    /**
     * Returns the long description of the class from the source code
     * documentation
     *
     * @return string Long description of the class
     */
    public function getLongDescription() {
        return $this->docParser->getLongDescription();
    }

    /**
     * Checks whether the class is annotated with the annotation $annotation
     *
     * @param string $annotation Name of the annotation
     * @return boolean
     */
    public function isTagged($annotation) {
        return $this->docParser->isTagged($annotation);
    }

    /**
     * Returns an array of annotations (optinally only annotations of a given name)
     *
     * @param string $name Name of the annotations
     * @return ezcReflectionDocTag[] Annotations
     */
    public function getTags($name = '') {
        if ($name == '') {
            return $this->docParser->getTags();
        }
        else {
            return $this->docParser->getTagsByName($name);
        }
    }

    /**
     * Returns NULL or the extension the class belongs to
     *
     * @return ezcReflectionExtension
     */
    public function getExtension() {
    	if ( $this->rclass instanceof ReflectionClass ) {
    		$ext = $this->rclass->getExtension();
    	} else {
    		$ext = parent::getExtension();
    	}

        if ($ext) {
            return new ezcReflectionExtension($ext);
        } else {
            return null;
        }
    }

    /**
     * Returns FALSE or the name of the extension the class belongs to
     *
     * This is purely a wrapper method which either calls the corresponding
     * method of the parent class or forwards the call to the ReflectionClass
     * instance passed to the constructor.
     * @return string|boolean Extension name or FALSE
     */
    public function getExtensionName() {
    	if ( $this->rclass instanceof ReflectionClass ) {
            // query external reflection object
    		$extensionName = $this->rclass->getExtensionName();
    	} else {
    		$extensionName = parent::getExtensionName();
    	}
        return $extensionName;
    }

    /**
     * Returns the name of the class.
     *
     * This is purely a wrapper method which either calls the corresponding
     * method of the parent class or forwards the call to the ReflectionClass
     * instance passed to the constructor.
     * @return string Class name
     */
    public function getName() {
        if ( $this->rclass instanceof ReflectionClass )
        {
            // query external reflection object
            $name = $this->rclass->getName();
        } else {
            $name = parent::getName();
        }
        return $name;
    }

    /**
     * Returns the doc comment for the class.
     *
     * This is purely a wrapper method which either calls the corresponding
     * method of the parent class or forwards the call to the ReflectionClass
     * instance passed to the constructor.
     * @return string Doc comment
     */
    public function getDocComment() {
        if ( $this->rclass instanceof ReflectionClass )
        {
            // query external reflection object
            $comment = $this->rclass->getDocComment();
        } else {
            $comment = parent::getDocComment();
        }
        return $comment;
    }

    /**
     * Exports a reflection object.
     *
     * Returns the output if TRUE is specified for return, printing it otherwise.
     * This is purely a wrapper method which calls the corresponding method of
     * the parent class.
     * @param ReflectionClass|string $class
     *        ReflectionClass object or name of the class
     * @param boolean $return
     *        Wether to return (TRUE) or print (FALSE) the output
     * @return mixed
     */
    public static function export($class, $return = false) {
        return parent::export($class, $return);
    }
}
?>
