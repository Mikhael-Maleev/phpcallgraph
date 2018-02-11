<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version //autogen//
 * @filesource
 * @package Reflection
 * @subpackage Tests
 */

class ezcReflectionClassExternalTest extends ezcReflectionClassTest
{
    /**
     * @var ezcReflectionClass
     */
    protected $rclass;

    public function setUp()
    {
        $this->rclass = new ezcReflectionClass( new MyReflectionClass( 'SomeClass' ) );
    }

    public function tearDown()
    {
        unset($this->rclass);
    }

	public function testCall() {
		self::assertTrue($this->rclass->doSomeMetaProgramming());
	}

	public function testGetMethod() {
		parent::testGetMethod();

		$m = $this->rclass->getMethod('helloWorld');
		self::assertTrue($m->change());
	}

	public function testGetProperty() {
		parent::testGetProperty();

		$prop = $this->rclass->getProperty('fields');
		self::assertTrue($prop->change());
	}

	public function testGetProperties() {
		parent::testGetProperties();

		$props = $this->rclass->getProperties();
		self::assertTrue($props[0]->change());
	}

	public function testGetConstructor() {
		parent::testGetConstructor();

		$ctr = $this->rclass->getConstructor();
		self::assertTrue($ctr->change());
	}

	public function testGetMethods() {
		parent::testGetMethods();

		$ms = $this->rclass->getMethods();
		self::assertTrue($ms[0]->change());
	}

	public function testGetInterfaces() {
		parent::testGetInterfaces();

		$is = $this->rclass->getInterfaces();
		self::assertTrue($is[0]->change());
	}

	public function testGetParentClass() {
		parent::testGetParentClass();

		$parent = $this->rclass->getParentClass();
		self::assertTrue($parent->change());
	}

	public function testGetExtension() {
		parent::testGetExtension();

		self::assertNull($this->rclass->getExtension());
		$c = new ezcReflectionClass( new MyReflectionClass( 'ReflectionClass' ) );
		$ext = $c->getExtension();
		self::assertTrue($ext->change());
	}

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcReflectionClassExternalTest" );
    }
}
?>
