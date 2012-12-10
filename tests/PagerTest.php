<?php

/**
 * Test class for Pager 
 */

namespace Rebolon\Component\Pager\Tests;

use Rebolon\Component\Pager\Pager;
use \PHPUnit_Framework_TestCase;

class PagerTest 
    extends \PHPUnit_Framework_TestCase
{
    // suffixes des noms de variables utilisées pour transporter les infos de page en page
    const __SUFFIX_LIST__ = 'myPagerForTest_'; // identifiant de la liste
    const __SUFFIX_GOTOPAGE__ = 'gtp_'; // indique la page à afficher
    const __SUFFIX_ITEMPERPAGE__ = 'ipp_'; // indique le nombre d'info à afficher par page
    const __SUFFIX_MAXPAGERITEM__ = 'mpi_'; // indique le nombre maximum d'index du pagex

    // nom du pager dans la page
    static protected $suffixName = 'myPagerForTest';
    
    // nombre d'info affiché sur la page 
    static protected $itemPerPage = 20;
    
    // nombre d'item dans le pager
    static protected $maxPagerItem = 10;
    
    /**
     *
     * @var Rebolon\Component\Pager\Pager
     */
    protected $pager;
    
    public function setUp()
    {
        $this->pager = new Pager;

        $this->pager->setSuffixName(self::$suffixName);
        $this->pager->setMaxPagerItem(self::$maxPagerItem);
        $this->pager->setItemPerPage(self::$itemPerPage);
    }
    
/* *** PagerConfigInterface *** */
    /**
     * No need to test because all tests are based on setup that use method from PagerCofnigInterface
     */
    
/* *** PagerInterface *** */
    
    /**
     * test base config with default params
     */
    public function test_PagerInterface_1()
    {       
        $this->pager->init(150);

        $this->assertEquals(20, $this->pager->getItemPerPageParam(), 
            'La valeur de getCurPageParam ne correspond pas à celle attendue');
        $this->assertEquals(0, $this->pager->getOffset(), 
            'La valeur de getOffset ne correspond pas à celle attendue');
    }

    /**
     * test with default parameters overloaded by request params
     */
    public function test_PagerInterface_2()
    {
        $_REQUEST['REQUESTED_URI'] = '/rebolon/pager/test';
        $_REQUEST[self::__SUFFIX_ITEMPERPAGE__ . self::$suffixName] = 1;

        $this->pager->init(150);

        $this->assertEquals(1, $this->pager->getItemPerPageParam(), 
            'La valeur de getItemPerPageParam ne correspond pas à celle attendue');
        $this->assertEquals(0, $this->pager->getOffset(), 
            'La valeur de getOffset ne correspond pas à celle attendue');
    }

    /**
     * test with default parameters overloaded by request params
     */
    public function test_PagerInterface_3()
    {
        $_REQUEST['REQUESTED_URI'] = '/rebolon/pager/test';
        $_REQUEST[self::__SUFFIX_ITEMPERPAGE__ . self::$suffixName] = 10;
        $_REQUEST[self::__SUFFIX_GOTOPAGE__ . self::$suffixName] = 2;
        
        $this->pager->init(150);

        $this->assertEquals(10, $this->pager->getItemPerPageParam(), 
            'La valeur de getItemPerPageParam ne correspond pas à celle attendue');
        $this->assertEquals(20, $this->pager->getOffset(), 
            'La valeur de getOffset ne correspond pas à celle attendue');
    }
    
/* *** PagerTplInterface *** */
    public function test_buildPager_1()
    {
        $this->pager->init(150);
        $pagerList = $this->pager->buildPager();
        $this->assertEquals(12, count($pagerList));
        $this->assertEquals('<<', $pagerList[0]['label']);
        $this->assertEquals('<', $pagerList[1]['label']);
        for ($i=2;$i<9;$i++) {
            $this->assertEquals(($i-1), $pagerList[$i]['label']);
        }
        $this->assertEquals('>', $pagerList[10]['label']);
        $this->assertEquals('>>', $pagerList[11]['label']);
    }
    
    /**
     * standard behavior for suffixName
     */
    public function test_getSuffixName_1()
    {
        $this->assertEquals('myPager_' . self::$suffixName, 
            $this->pager->getSuffixName(), 
            'La valeur de getSuffixName ne correspond pas à celle attendue');
    }
    
    /**
     * standard behavior
     */
    public function test_isCurrentPage_1()
    {
        $this->pager->init(150);

        $this->assertTrue($this->pager->isCurrentPage(1), 
                'La valeur de isCurrentPage(1'
                . ') ne correspond pas à celle attendue');
        
        for ($i=2 ; $i<=10 ; $i++) {
            $this->assertFalse($this->pager->isCurrentPage($i), 
                'La valeur de isCurrentPage(' . $i 
                . ') ne correspond pas à celle attendue');
        }
    }
    
    /**
     * overloaded behavior using request params
     */
    public function test_isCurrentPage_2()
    {
        $_REQUEST['REQUESTED_URI'] = '/rebolon/pager/test';
        $_REQUEST[self::__SUFFIX_ITEMPERPAGE__ . self::$suffixName] = 50;
        $_REQUEST[self::__SUFFIX_GOTOPAGE__ . self::$suffixName] = 2;
        
        $this->pager->init(150);

        // not existing page because pager starts at 1
        $this->assertFalse($this->pager->isCurrentPage(0), 
                'La valeur de isCurrentPage(0'
                . ') ne correspond pas à celle attendue');
        // existing pages
        $this->assertFalse($this->pager->isCurrentPage(1), 
                'La valeur de isCurrentPage(1'
                . ') ne correspond pas à celle attendue');
        $this->assertFalse($this->pager->isCurrentPage(2), 
                'La valeur de isCurrentPage(2'
                . ') ne correspond pas à celle attendue');
        $this->assertTrue($this->pager->isCurrentPage(3),
                'La valeur de isCurrentPage(3'
                . ') ne correspond pas à celle attendue');
        // not existing page because in request params there can be 
        // only 3 page starting at 1
        $this->assertFalse($this->pager->isCurrentPage(4),
                'La valeur de isCurrentPage(4'
                . ') ne correspond pas à celle attendue');
    }
    
    /**
     * standard behavior using properties of Pager
     */
    public function test_getTotalPage_1()
    {
        $this->pager->init(150);

        $this->assertEquals(ceil(150/20), $this->pager->getTotalPage(), 
            'La valeur de getTotalPage ne correspond pas à celle attendue');
    }
    
    /**
     * overloaded behavior using request properties
     */
    public function test_getTotalPage_2()
    {
        $_REQUEST['REQUESTED_URI'] = '/rebolon/pager/test';
        $_REQUEST[self::__SUFFIX_ITEMPERPAGE__ . self::$suffixName] = 10;
        
        $this->pager->init(150);

        $this->assertEquals(ceil(150/10), $this->pager->getTotalPage(), 
            'La valeur de getTotalPage ne correspond pas à celle attendue');
    }
    
    /**
     * standard behavior
     */
    public function test_getCurrentPage_1()
    {
        $this->pager->init(150);

        $this->assertEquals(0, $this->pager->getCurrentPage(), 
            'La valeur de getCurrentPage ne correspond pas à celle attendue');
    }
    
    /**
     * overloaded behavior using request params
     */
    public function test_getCurrentPage_2()
    {
        $_REQUEST['REQUESTED_URI'] = '/rebolon/pager/test';
        $_REQUEST[self::__SUFFIX_GOTOPAGE__ . self::$suffixName] = 5;
        
        $this->pager->init(150);

        $this->assertEquals(5, $this->pager->getCurrentPage(), 
            'La valeur de getCurrentPage ne correspond pas à celle attendue');
    }
    
    /**
     * overloaded behavior using request params
     * gotopage is greater than the max exepected : 100
     * so getCurrentPage should return 7
     */
    public function test_getCurrentPage_3()
    {
        $_REQUEST['REQUESTED_URI'] = '/rebolon/pager/test';
        $_REQUEST[self::__SUFFIX_GOTOPAGE__ . self::$suffixName] = 100;

        $this->pager->init(150);

        $this->assertEquals(7, $this->pager->getCurrentPage(), 
            'La valeur de getCurrentPage ne correspond pas à celle attendue');
    }
    
    /**
     * overloaded behavior using request params
     * same as previous but itemperpage has been modified by request so
     * gotopage is greater than the max exepected : 100
     * so getCurrentPage should return 14
     */
    public function test_getCurrentPage_4()
    {
        $_REQUEST['REQUESTED_URI'] = '/rebolon/pager/test';
        $_REQUEST[self::__SUFFIX_ITEMPERPAGE__ . self::$suffixName] = 10;
        $_REQUEST[self::__SUFFIX_GOTOPAGE__ . self::$suffixName] = 100;
        
        $this->pager->init(150);

        $this->assertEquals(14, $this->pager->getCurrentPage(), 
            'La valeur de getCurrentPage ne correspond pas à celle attendue');
    }
    
    /**
     * standard behavior
     */
    public function test_isToDisplay_1()
    {
        $this->pager->init(150);

        for ($i=1 ; $i<=20 ; $i++) {
            $this->assertTrue($this->pager->isToDisplay($i), 
                'La valeur de isToDisplay(' . $i 
                . ') ne correspond pas à celle attendue');
        }
        for ($i=21 ; $i<=151 ; $i++) {
            $this->assertFalse($this->pager->isToDisplay($i), 
                'La valeur de isToDisplay(' . $i 
                . ') ne correspond pas à celle attendue');
        }
    }
    
    /**
     * overloaded behavior using request params
     */
    public function test_isToDisplay_2()
    {
        $_REQUEST['REQUESTED_URI'] = '/rebolon/pager/test';
        $_REQUEST[self::__SUFFIX_ITEMPERPAGE__ . self::$suffixName] = 50;
        $_REQUEST[self::__SUFFIX_GOTOPAGE__ . self::$suffixName] = 2;
        
        $this->pager->init(150);

        for ($i=1 ; $i<=100 ; $i++) {
            $this->assertFalse($this->pager->isToDisplay($i), 
                'La valeur de isToDisplay(' . $i 
                . ') ne correspond pas à celle attendue');
        }
        for ($i=101 ; $i<=150 ; $i++) {
            $this->assertTrue($this->pager->isToDisplay($i), 
                'La valeur de isToDisplay(' . $i 
                . ') ne correspond pas à celle attendue');
        }
        $this->assertFalse($this->pager->isToDisplay(151), 
            'La valeur de isToDisplay(151'
            . ') ne correspond pas à celle attendue');
    }
}