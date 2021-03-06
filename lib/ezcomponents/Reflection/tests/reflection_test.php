<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version //autogen//
 * @filesource
 * @package Reflection
 * @subpackage Tests
 */

class ezcReflectionTest extends ezcTestCase
{
    public function testGetDocParser() {
        self::assertType( 'ezcReflectionDocParser', ezcReflectionApi::getDocParserInstance() );
    }

    public function testSetDocParser() {
        $annotationParser = new ezcReflectionPhpDocParser();
        ezcReflectionApi::setDocParser( $annotationParser );
        self::assertEquals( $annotationParser, ezcReflectionApi::getDocParserInstance() );
    }

    public function testGetReflectionTypeFactory() {
        self::assertType( 'ezcReflectionTypeFactory', ezcReflectionApi::getReflectionTypeFactory() );
    }

    public function testSetReflectionTypeFactory() {
        $factory = new ezcReflectionTypeFactoryImpl();
        ezcReflectionApi::setReflectionTypeFactory( $factory );
        self::assertEquals( $factory, ezcReflectionApi::getReflectionTypeFactory() );
    }

    public function testGetTypeByName() {
        $string = ezcReflectionApi::getTypeByName('string');
        self::assertEquals('string', $string->toString());

        $int = ezcReflectionApi::getTypeByName('int');
        self::assertEquals('integer', $int->toString());

        $webservice = ezcReflectionApi::getTypeByName('TestWebservice');
        self::assertEquals('TestWebservice', $webservice->toString());

        $class = ezcReflectionApi::getTypeByName('ezcReflectionClass');
        self::assertEquals('ezcReflectionClass', $class->toString());

    }

    public function testGetClasses() {
        $classes = ezcReflectionApi::getClasses();
        self::assertContainsOnly( 'ezcReflectionClass', $classes );
        foreach ( $classes as $className => $class) {
            self::assertFalse( $class->isInterface() );
            self::assertEquals( $class->getName(), $className );
            $classNames[] = $class->getName();
        }
        self::assertEquals( get_declared_classes(), $classNames );
    }

    public function testGetInterfaces() {
        $interfaces = ezcReflectionApi::getInterfaces();
        self::assertContainsOnly( 'ezcReflectionClass', $interfaces );
        foreach ( $interfaces as $interfaceName => $interface) {
            self::assertTrue( $interface->isInterface() );
            self::assertEquals( $interface->getName(), $interfaceName );
            $interfaceNames[] = $interface->getName();
        }
        self::assertEquals( get_declared_interfaces(), $interfaceNames );
    }

    public function testGetUserDefinedFunctions() {
        $definedFunctionArrays = get_defined_functions();
        $userDefinedFunctions = $definedFunctionArrays['user'];
        $functions = ezcReflectionApi::getUserDefinedFunctions();
        self::assertContainsOnly( 'ezcReflectionFunction', $functions );
        foreach ( $functions as $functionName => $function ) {
            self::assertTrue( $function->isUserDefined() );
            self::assertEquals( strtolower( $function->getName() ), $functionName );
            self::assertContains( strtolower( $function->getName() ), $userDefinedFunctions );
            // strtolower used because of the following error:
            /*
                Failed asserting that
                Array
                (
                    [0] => __autoload
                    [1] => phpunit_util_errorhandler
                    [2] => _pear_call_destructors
                    [3] => m1
                    [4] => mmm
                    [5] => m2
                    [6] => m3
                    [7] => m4
                )
                 contains <string:PHPUnit_Util_ErrorHandler>.
            */
        }
    }

    public function testGetInteralFunctions() {
        $definedFunctionArrays = get_defined_functions();
        $internalFunctions = $definedFunctionArrays['internal'];
        $functions = ezcReflectionApi::getInternalFunctions();
        self::assertContainsOnly( 'ezcReflectionFunction', $functions );
        foreach ( $functions as $functionName => $function ) {
            self::assertTrue( $function->isInternal() );
            self::assertEquals( $function->getName(), $functionName );
            self::assertContains( $function->getName(), $internalFunctions );
        }
    }

    public function testGetFunctions() {
        $definedFunctionArrays = get_defined_functions();
        $definedFunctions = array_merge( $definedFunctionArrays['internal'], $definedFunctionArrays['user'] );
        $functions = ezcReflectionApi::getFunctions();
        self::assertContainsOnly( 'ezcReflectionFunction', $functions );
        foreach ( $functions as $functionName => $function ) {
            self::assertEquals( strtolower( $function->getName() ), $functionName );
            self::assertContains( strtolower( $function->getName() ), $definedFunctions );
            // strtolower used because of the following error:
            /*
                Failed asserting that
                Array
                (
                    [0] => __autoload
                    [1] => phpunit_util_errorhandler
                    [2] => _pear_call_destructors
                    [3] => m1
                    [4] => mmm
                    [5] => m2
                    [6] => m3
                    [7] => m4
                )
                 contains <string:PHPUnit_Util_ErrorHandler>.
            */
        }
    }

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcReflectionTest" );
    }
}
?>
