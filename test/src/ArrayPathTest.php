<?php
/**
 * ArrayPath
 *
 * @link      http://github.com/mathiasgrimm/arraypath for the canonical source repository
 * @copyright Copyright (c) 2014 Mathias Grimm. (http://github.com/mathiasgrimm)
 * @license   https://github.com/mathiasgrimm/arraypath/blob/master/LICENSE.txt Mathias Grimm License
 */
use MathiasGrimm\ArrayPath\ArrayPath;

class ArrayPathTest extends \PHPUnit_Framework_TestCase
{
    protected $aSource;
    
    public function setUp()
    {
        parent::setUp();
        ArrayPath::setSeparator('/');
        
        $this->aSource = array(
            'l1-1' => 10,
            'l1-2' => array(
                'l2-1' => 20,
                'l2-2' => array(
                    'l3-1' => 30,
                    'l3-2' => 31
                )
            )
        );
        
    }
    public function testGetSeparatorDefault()
    {
        $this->assertEquals('/', ArrayPath::getSeparator());
    }
    
    
    public function testSetSeparator()
    {
        $this->assertEquals('/', ArrayPath::setSeparator('/'));
        $this->assertEquals('/', ArrayPath::setSeparator('.'));
        $this->assertEquals('.', ArrayPath::setSeparator('.'));
        $this->assertEquals('.', ArrayPath::setSeparator('.'));
    }
    
    public function testGetSeparator()
    {
        ArrayPath::setSeparator('#');
        $this->assertEquals('#', ArrayPath::getSeparator());
    }
    
    public function testGetSingleNoDefault()
    {
        $aSource = array(1);
        
        $a = microtime();
        ArrayPath::get($aSource, 0);
        $b = microtime();
        
        $this->assertEquals(1, ArrayPath::get($aSource, 0));
        
        $aSource = array('mathias' => 'gladiator');
        $this->assertEquals('gladiator', ArrayPath::get($aSource, 'mathias'));
        $this->assertEquals(null, ArrayPath::get($aSource, 'non-existent-index'));
    }
    
    public function testGetSingleWithDefault()
    {
        $aSource = array(1);
        $this->assertEquals(1, ArrayPath::get($aSource, 0));
    
        $aSource = array('mathias' => 'gladiator');
        $this->assertEquals('gladiator', ArrayPath::get($aSource, 'mathias', 'anything'));
        $this->assertEquals('anything', ArrayPath::get($aSource, 'non-existent-index', 'anything'));
    }
    
    public function testGetMultiNoDefault()
    {
        $aSource = $this->aSource; 

        $this->assertEquals(10, ArrayPath::get($aSource, 'l1-1'));
        $this->assertEquals(null, ArrayPath::get($aSource, 'l1-3'));
        
        $this->assertEquals(null, ArrayPath::get($aSource, 'l1-1/non-existent'));
        $this->assertEquals(20, ArrayPath::get($aSource, 'l1-2/l2-1'));
        $this->assertEquals(30, ArrayPath::get($aSource, 'l1-2/l2-2/l3-1'));
    }
    
    public function testGetMultiDefault()
    {
        $aSource = $this->aSource;
    
        $this->assertEquals(10, ArrayPath::get($aSource, 'l1-1', 'def'));
        $this->assertEquals('def', ArrayPath::get($aSource, 'l1-3', 'def'));
    
        $this->assertEquals('def', ArrayPath::get($aSource, 'l1-1/non-existent', 'def'));
        $this->assertEquals(20, ArrayPath::get($aSource, 'l1-2/l2-1', 'def'));
        $this->assertEquals(30, ArrayPath::get($aSource, 'l1-2/l2-2/l3-1', 'def'));
    }
    
    public function testSet()
    {
        $aSource   = array(
            'l1-1' => 10,
            'l1-2' => array(
                'l2-1' => 20,
                'l2-2' => array(
                    'l3-1' => 30,
                    'l3-2' => 31,
                )
            )
        );
        
        $aExpected   = array(
            'l1-1' => 10,
            'l1-2' => array(
                'l2-1' => 20,
                'l2-2' => array(
                    'l3-1' => 30,
                    'l3-2' => 31,
                    'l3-3' => 17
                )
            )
        );

        $this->assertEquals(17, ArrayPath::set($aSource, 'l1-2/l2-2/l3-3', 17));
        $this->assertEquals($aExpected, $aSource);
    }
    
    public function testRemove()
    {
        $aSource   = array(
            'l1-1' => 10,
            'l1-2' => array(
                'l2-1' => 20,
                'l2-2' => array(
                    'l3-1' => 30,
                    'l3-2' => 31
                )
            )
        );

        $this->assertEquals(10, ArrayPath::remove($aSource, 'l1-1'          ));
        $this->assertEquals(20, ArrayPath::remove($aSource, 'l1-2/l2-1'     ));
        $this->assertEquals(31, ArrayPath::remove($aSource, 'l1-2/l2-2/l3-2'));
        
        $aExpected = array(
            'l1-2' => array(
                'l2-2' => array(
                    'l3-1' => 30,
                )
            )
        );
        
        $this->assertEquals($aExpected, $aSource);
        
        $this->assertEquals(null, ArrayPath::remove($aSource, 'l1-2/l2-2/l3-2/non-existent'));
    }
    
    public function testExistsSimple()
    {
        $a['a'] = null;
        $a['b'] = 2;
        
        $this->assertFalse(ArrayPath::exists($a, 'c'));
        $this->assertTrue(ArrayPath::exists($a, 'a'));
        $this->assertTrue(ArrayPath::exists($a, 'b'));
    }
    
    public function testExistsMulti()
    {
        $a = array(
            'a' => array(
                'b' => array(
                    'c' => null
                )
            )  
        );
        
        $this->assertFalse(ArrayPath::exists($a, 'a/b/c/d'));
        $this->assertTrue(ArrayPath::exists($a, 'a/b/c'));
    }

    public function testItRegisterClassAliasWithForDefault()
    {
        $this->assertFalse(class_exists('A'));
        ArrayPath::registerClassAlias();
        $this->assertTrue(class_exists('A'));

        $a = array(
            'a' => array(
                'b' => array(
                    'c' => null
                )
            )
        );

        $this->assertFalse(ArrayPath::exists($a, 'a/b/c/d'));
        $this->assertTrue(ArrayPath::exists($a, 'a/b/c'));
    }

    public function testItRegisterClassAliasWithAlias()
    {
        $this->assertFalse(class_exists('MyAlias'));
        ArrayPath::registerClassAlias('MyAlias');
        $this->assertTrue(class_exists('MyAlias'));

        $a = array(
            'a' => array(
                'b' => array(
                    'c' => null
                )
            )
        );

        $this->assertFalse(MyAlias::exists($a, 'a/b/c/d'));
        $this->assertTrue(MyAlias::exists($a, 'a/b/c'));
    }

}
