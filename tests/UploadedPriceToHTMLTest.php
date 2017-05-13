<?php
/**
 * Created by PhpStorm.
 * User: makc
 * Date: 11.05.17
 * Time: 18:28
 */

namespace makikam\PriceToHTML;

use PHPUnit\Framework\TestCase;

class UploadedPriceToHTMLTest extends TestCase
{
    /**
     * @var UploadedPriceToHTML
     */
    protected $upth;


    public function setUp()
    {
        $this->upth = new UploadedPriceToHTML();
    }

    
    public function tearDown()
    {
        $this->upth = null;
    }


    public function testReturnsFalseOnIncorrectPriceListCheck()
    {
        $this->assertFalse($this->upth->Do());
    }

    public function test()
    {
        $this->qwe(null);
        $this->assertFalse(false);
    }

    public function qwe(int $a = 3)
    {
        var_dump($a);
    }
}
