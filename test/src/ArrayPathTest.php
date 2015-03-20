<?php
/**
 * ArrayPath
 *
 * @link      http://github.com/mathiasgrimm/arraypath for the canonical source repository
 * @copyright Copyright (c) 2014 Mathias Grimm. (http://github.com/mathiasgrimm)
 * @license   https://github.com/mathiasgrimm/arraypath/blob/master/LICENSE.txt Mathias Grimm License
 */
use Mathiasgrimm\Arraypath\ArrayPath;

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
        ArrayPath::get(0, $aSource);
        $b = microtime();
        
        $this->assertEquals(1, ArrayPath::get(0, $aSource));
        
        $aSource = array('mathias' => 'gladiator');
        $this->assertEquals('gladiator', ArrayPath::get('mathias', $aSource));
        $this->assertEquals(null, ArrayPath::get('non-existent-index', $aSource));
    }
    
    public function testGetSingleWithDefault()
    {
        $aSource = array(1);
        $this->assertEquals(1, ArrayPath::get(0, $aSource));
    
        $aSource = array('mathias' => 'gladiator');
        $this->assertEquals('gladiator', ArrayPath::get('mathias', $aSource, 'anything'));
        $this->assertEquals('anything', ArrayPath::get('non-existent-index', $aSource, 'anything'));
    }
    
    public function testGetMultiNoDefault()
    {
        $aSource = $this->aSource; 

        $this->assertEquals(10, ArrayPath::get('l1-1', $aSource));
        $this->assertEquals(null, ArrayPath::get('l1-3', $aSource));
        
        $this->assertEquals(null, ArrayPath::get('l1-1/non-existent', $aSource));
        $this->assertEquals(20, ArrayPath::get('l1-2/l2-1', $aSource));
        $this->assertEquals(30, ArrayPath::get('l1-2/l2-2/l3-1', $aSource));
    }
    
    public function testGetMultiDefault()
    {
        $aSource = $this->aSource;
    
        $this->assertEquals(10, ArrayPath::get('l1-1', $aSource, 'def'));
        $this->assertEquals('def', ArrayPath::get('l1-3', $aSource, 'def'));
    
        $this->assertEquals('def', ArrayPath::get('l1-1/non-existent', $aSource, 'def'));
        $this->assertEquals(20, ArrayPath::get('l1-2/l2-1', $aSource, 'def'));
        $this->assertEquals(30, ArrayPath::get('l1-2/l2-2/l3-1', $aSource, 'def'));
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

        $this->assertEquals(17, ArrayPath::set(17, 'l1-2/l2-2/l3-3', $aSource));
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

        $this->assertEquals(10, ArrayPath::remove('l1-1'           , $aSource));
        $this->assertEquals(20, ArrayPath::remove('l1-2/l2-1'      , $aSource));
        $this->assertEquals(31, ArrayPath::remove('l1-2/l2-2/l3-2' , $aSource));
        
        $aExpected = array(
            'l1-2' => array(
                'l2-2' => array(
                    'l3-1' => 30,
                )
            )
        );
        
        $this->assertEquals($aExpected, $aSource);
        
        $this->assertEquals(null, ArrayPath::remove('l1-2/l2-2/l3-2/non-existent', $aSource));
    }
    
    public function testExistsSimple()
    {
        $a['a'] = null;
        $a['b'] = 2;
        
        $this->assertFalse(ArrayPath::exists('c', $a));
        $this->assertTrue(ArrayPath::exists('a', $a));
        $this->assertTrue(ArrayPath::exists('b', $a));
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
        
        $this->assertFalse(ArrayPath::exists('a/b/c/d', $a));
        $this->assertTrue(ArrayPath::exists('a/b/c', $a));
    }
}
