<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version //autogen//
 * @filesource
 * @package Reflection
 * @subpackage Tests
 */

class ezcReflectionClassTypeTest extends ezcTestCase
{
    /**
     * @var ezcReflectionClassType
     */
    protected $rclass;

    public function setUp()
    {
        $this->rclass = new ezcReflectionClassType( 'SomeClass' );
    }

    public function testGetArrayType()
    {
        $this->assertNull( $this->rclass->getArrayType() );
    }

    public function testGetMapIndexType()
    {
        $this->assertNull( $this->rclass->getMapIndexType() );
    }

    public function testGetMapValueType()
    {
        $this->assertNull( $this->rclass->getMapValueType() );
    }

    public function testIsArray()
    {
        $this->assertFalse( $this->rclass->isArray() );
    }

    public function testIsClass()
    {
        $this->assertTrue( $this->rclass->isClass() );
    }

    public function testIsPrimitive()
    {
        $this->assertFalse( $this->rclass->isPrimitive() );
    }

    public function testIsMap()
    {
        $this->assertFalse( $this->rclass->isMap() );
    }

    public function testToString()
    {
        $this->assertEquals( 'SomeClass', $this->rclass->toString() );
    }

    public function testIsStandardType()
    {
        $this->assertFalse( $this->rclass->isStandardType() );
    }

    public function testGetXmlNameWithPrefix()
    {
        $this->assertEquals( 'tns:SomeClass', $this->rclass->getXmlName( true ) );
    }

    public function testGetXmlNameWithoutPrefix()
    {
        $this->assertEquals( 'SomeClass', $this->rclass->getXmlName( false ) );
    }

    public function testGetXmlSchema()
    {
        $expected = new DOMDocument;
        $expected->preserveWhiteSpace = false;
        $expected->load( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'schemas' . DIRECTORY_SEPARATOR . 'SomeClass.xsd' );

        $actual = new DOMDocument;
        $actual->appendChild( $this->rclass->getXmlSchema( $actual ) );

        $this->assertEquals( $expected, $actual );
    }

    public function testGetXmlSchema2()
    {
        $this->rclass = new ezcReflectionClassType( 'stdClass' );

        $expected = new DOMDocument;
        $expected->preserveWhiteSpace = false;
        $expected->load( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'schemas' . DIRECTORY_SEPARATOR . 'stdClass.xsd' );

        $actual = new DOMDocument;
        $actual->appendChild( $this->rclass->getXmlSchema( $actual ) );

        $this->assertEquals( $expected, $actual );
    }

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( 'ezcReflectionClassTypeTest' );
    }
}
?>
