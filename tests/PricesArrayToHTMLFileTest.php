<?php

namespace MakikaM\PriceToHTML;

use PHPUnit\Framework\TestCase;

class PricesArrayToHTMLFileTest extends TestCase
{
    protected $_HTMLDir;
    protected $_arPricesExample;


    public function setUp()
    {
        $this->_HTMLDir = __DIR__ . '/html/';
        $this->_arPricesExample = [
            0 => ['ZIP WAX Шампунь 750 мл', 'П00035', '204.09'],
            1 => ['ЯЩИК, ПОДСТАКАННИК КОНСОЛИ', '96615503', '154.09']
        ];
    }


    public function testWrongHeadFile()
    {
        $this->expectException(\InvalidArgumentException::class);

        $pth = new PricesArrayToHTMLFile(
            'wrong',
            $this->_HTMLDir . 'price-test-footer.html',
            $this->_HTMLDir . 'test-html-output.html',
            $this->_arPricesExample);
    }


    public function testWrongFootFile()
    {
        $this->expectException(\InvalidArgumentException::class);

        $pth = new PricesArrayToHTMLFile(
            $this->_HTMLDir . 'price-test-header.html',
            $this->_HTMLDir . 'wrong_footer',
            $this->_HTMLDir . 'test-html-output.html',
            $this->_arPricesExample);
    }


    //TODO php cli в docker'e запускается под root. Как задавать пользователя?
    /*public function testWrongDest()
    {
        $this->expectException(\InvalidArgumentException::class);

        $pth = new PricesArrayToHTMLFile(
            $this->_HTMLDir . 'price-test-header.html',
            $this->_HTMLDir . 'price-test-footer.html',
            '/' . 'test-html-output.html',
            $this->_arPricesExample);
    }*/


    public function testEmptyPriceListArray()
    {
        $this->expectException(\InvalidArgumentException::class);

        $pth = new PricesArrayToHTMLFile(
            $this->_HTMLDir . 'price-test-header.html',
            $this->_HTMLDir . 'price-test-footer.html',
            $this->_HTMLDir . 'test-html-output.html',
            []);
    }


    public function testEmptyTemplateLine()
    {
        $this->expectException(\InvalidArgumentException::class);

        $pth = new PricesArrayToHTMLFile(
            $this->_HTMLDir . 'price-test-header.html',
            $this->_HTMLDir . 'price-test-footer.html',
            $this->_HTMLDir . 'test-html-output.html',
            $this->_arPricesExample,
            '');
    }


    public function testWrongLessVarsNumInTemplateLineThenInArray()
    {
        $this->expectException(\InvalidArgumentException::class);

        $pth = new PricesArrayToHTMLFile(
            $this->_HTMLDir . 'price-test-header.html',
            $this->_HTMLDir . 'price-test-footer.html',
            $this->_HTMLDir . 'test-html-output.html',
            $this->_arPricesExample,
            "<tr><td>%1 - %2</td></tr><td><tr></tr></td>\n");//не хватает %3
    }


    public function testWrongMoreVarsNumInTemplateLineThenInArray()
    {
        $this->expectException(\InvalidArgumentException::class);

        $pth = new PricesArrayToHTMLFile(
            $this->_HTMLDir . 'price-test-header.html',
            $this->_HTMLDir . 'price-test-footer.html',
            $this->_HTMLDir . 'test-html-output.html',
            $this->_arPricesExample,
            "<tr><td>%1 - %2</td></tr><td><tr>%3</tr>%4</td>\n"); // %4 лишний
    }


    public function testOutputHTMLFileExists()
    {


        $pth = new PricesArrayToHTMLFile(
            $this->_HTMLDir . 'price-test-header.html',
            $this->_HTMLDir . 'price-test-footer.html',
            $this->_HTMLDir . 'test-html-output.html',
            $this->_arPricesExample,
            "<tr><td>%1 - %0</td><td>%2</td></tr>\n");

        $this->assertFileExists($this->_HTMLDir.'test-html-output.html');

        //TODO Сделать проверку на правильное наполнение файла
    }
}
