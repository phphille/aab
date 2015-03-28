<?php

namespace phpe\paginering;
/**
* Class to log what happens.
*
* @package LydiaCore
*/
class CPagineringTest extends  \PHPUnit_Framework_TestCase
{


    public function testHitsPerPage()
    {
        $el = new \phpe\paginering\CPaginering();

        $el->setTotalRows(2, 1, 4);
        $string = $el->GetNbrOfHitsPerPage();
        $this->assertContainsOnly('string', array($string));
    }



    public function testPageNav()
    {
        $el = new \phpe\paginering\CPaginering();

        $el->setTotalRows(2, 1, 4);
        $string = $el->GetPageNav();
        $this->assertContainsOnly('string', array($string));
    }
}
