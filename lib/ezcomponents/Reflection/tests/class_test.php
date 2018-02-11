<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version //autogen//
 * @filesource
 * @package Reflection
 * @subpackage Tests
 */

class ezcReflectionClassTest extends ezcTestCase
{
    /**
     * @var ezcReflectionClass
     */
    protected $rclass;

    public function setUp() {
        $this->rclass = new ezcReflectionClass('SomeClass');
    }

    public function tearDown() {
        unset($this->rclass);
    }

    public function testGetName() {
        self::assertEquals('SomeClass', $this->rclass->getName());
    }

    public function testGetMethod() {
        $method = $this->rclass->getMethod('helloWorld');
        self::assertType('ezcReflectionMethod', $method);
        self::assertEquals('helloWorld', $method->getName());
    }

    public function testGetConstructor() {
        $method = $this->rclass->getConstructor();
        self::assertType('ezcReflectionMethod', $method);
        self::assertEquals('__construct', $method->getName());
    }

	public function testGetInterfaces() {
        $ifaces = $this->rclass->getInterfaces();

        self::assertType('ezcReflectionClass', $ifaces[0]);
        self::assertEquals('IInterface', $ifaces[0]->getName());
        self::assertEquals(1, count($ifaces));
    }

    public function testGetMethods() {
        $class = new ezcReflectionClass('TestWebservice');
        $methods = $class->getMethods();
        self::assertEquals(0, count($methods));

        $methods = $this->rclass->getMethods();

        $expectedMethods = array('__construct', 'helloWorld', 'doSomeMetaProgramming');
        self::assertEquals(count($expectedMethods), count($methods));
        foreach ($methods as $method) {
            self::assertType('ezcReflectionMethod', $method);
            self::assertContains($method->getName(), $expectedMethods);

            ReflectionTestHelper::deleteFromArray($method->getName(), $expectedMethods);
        }
        self::assertEquals(0, count($expectedMethods));
    }

    public function testGetParentClass() {
        $parent = $this->rclass->getParentClass();

        self::assertType('ezcReflectionClass', $parent);
        self::assertEquals('BaseClass', $parent->getName());

        $parentParent = $parent->getParentClass();
        self::assertNull($parentParent);
    }

    public function testGetProperty() {
        $prop = $this->rclass->getProperty('fields');

        self::assertType('ezcReflectionProperty', $prop);
        self::assertEquals('fields', $prop->getName());

        try {
            $prop = $this->rclass->getProperty('none-existing-property');
        }
        catch (ReflectionException $expected) {
            return;
        }
        $this->fail('ReflectionException has not been raised on none existing property.');
    }

    public function testGetProperties() {
        $class = new ezcReflectionClass('TestWebservice');
        $properties = $class->getProperties();

        $expected = array('prop1', 'prop2', 'prop3');

        foreach ($properties as $prop) {
            self::assertType('ezcReflectionProperty', $prop);
            self::assertContains($prop->getName(), $expected);

            ReflectionTestHelper::deleteFromArray($prop->getName(), $expected);
        }
        self::assertEquals(0, count($expected));
    }

    public function testGetShortDescription() {
        $class = new ezcReflectionClass('TestWebservice');
        $desc = $class->getShortDescription();

        self::assertEquals('This is the short description', $desc);
    }

    public function testGetLongDescription() {
        $class = new ezcReflectionClass('TestWebservice');
        $desc = $class->getLongDescription();

        $expected = "This is the long description with may be additional infos and much more lines\nof text.\n\nEmpty lines are valide to.\n\nfoo bar";
        self::assertEquals($expected, $desc);
    }

    public function testIsTagged() {
        self::assertFalse($this->rclass->isTagged('foobar'));

        $class = new ezcReflectionClass('TestWebservice');
        self::assertTrue($class->isTagged('foobar'));
    }

    public function testGetTags() {
        $tags = $this->rclass->getTags();

        $expectedTags = array('licence', 'donotdocument', 'testclass', 'ignore');
        ReflectionTestHelper::expectedTags($expectedTags, $tags, $this);

        $expectedTags = array('webservice', 'foobar');
        $class = new ezcReflectionClass('TestWebservice');
        $tags = $class->getTags();
        ReflectionTestHelper::expectedTags($expectedTags, $tags, $this);
    }

    public function testGetExtension() {
        $class = new ezcReflectionClass('ReflectionClass');
        $ext = $class->getExtension();
        self::assertType('ezcReflectionExtension', $ext);
        self::assertEquals('Reflection', $ext->getName());

        $ext = $this->rclass->getExtension();
        self::assertNull($ext);
    }

    public function testGetExtensionName() {
        $class = new ezcReflectionClass( 'ReflectionClass' );
        self::assertEquals( 'Reflection', $class->getExtensionName() );
        self::assertEquals( '', $this->rclass->getExtensionName() );
    }

    public function testExport() {
        self::assertEquals( ReflectionClass::export('TestWebservice', true), ezcReflectionClass::export('TestWebservice', true) );
    }

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcReflectionClassTest" );
    }
}
?>
