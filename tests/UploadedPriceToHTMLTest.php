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

    public function testClassWorks()
    {
        $test_filename = 'test_correct_price.xls';

        $_FILES = [
            'file' => [
                'name' => $test_filename,
                'type' => 'asd',
                'tmp_name' => $test_filename,
                'error' => UPLOAD_ERR_OK,
                'size' => 10
            ]
        ];

        $tmp_file = sys_get_temp_dir() . '/' . $test_filename;

        if (!copy(__DIR__ . '/excel/' . $test_filename, $tmp_file)) {
            throw new \Exception('cant create test xls file!');
        }

        try {
            $uph = new UploadedPriceToHTML();

            $res = $uph->setDestinationPath(__DIR__.'/excel/')
                       ->setDestFileName('test_file.xls')
                       ->setOldPricesPath(__DIR__.'/old_files_test/')
                       ->setOldPricesNum(3)
                       ->setColumnsToParse([5, 0, 7])
                       ->setHeadRowsPass(1)
                       ->setNotEmptyCols([7])
                       ->setHeadHTMLFile(__DIR__.'/html/price-test-header.html')
                       ->setFootHTMLFile(__DIR__.'/html/price-test-footer.html')
                       ->setDstHTMLFile(__DIR__.'/html/test-html-output.html')
                       ->Do();

            //$this->assertEquals("", $uph->getMessages());

            $this->assertTrue($res);

        } finally {
            if (file_exists($tmp_file)) {
                unlink($tmp_file);
            }
            unset($tmp);
        }
    }
}
