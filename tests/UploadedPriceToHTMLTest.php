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

}
