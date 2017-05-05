<?php

use MakikaM\PriceToHTML\PriceListParser;
use PHPUnit\Framework\TestCase;

class PriceListParserTest extends TestCase
{
    public function testNotEmptyColsToParse()
    {
        $this->expectException(InvalidArgumentException::class);

        try {
            $Reader = new PriceListParser(__DIR__ . '/' . 'test_file.xls', [], 0, []);
        } finally {
            unset($Reader);
        }
    }

    public function testIncorrectColsToParse()
    {
        $this->expectException(InvalidArgumentException::class);

        try {
            $Reader = new PriceListParser(__DIR__ . '/' . 'test_file.xls', [1, 2, 'ошибка'], 0, []);
        } finally {
            unset($Reader);
        }
    }


    public function testCorrectColsParsed()
    {
        //тестируем первую запись и последнюю
        $PPReader = new PriceListParser(__DIR__ . '/excel/' . 'test_file.xls', [5, 0, 7], 1, [7]);
        $arRows = $PPReader->GetParsedArray();
//        var_dump($arRows);
        $this->assertEquals(['ZIP WAX Шампунь 750 мл', 'П00035', '204.09'], $arRows[0]);

        $last_idx = count($arRows)-1;
        $this->assertEquals(['ЯЩИК, ПОДСТАКАННИК КОНСОЛИ', '96615503', '154.09'], $arRows[$last_idx]);

        //6654 строки в файле всего, первую - пропускаем, 2 строки без цены
        $this->assertCount(6651, $PPReader->GetParsedArray());
        $this->assertEquals(6651, $PPReader->GetParsedRowsCount());
        $this->assertEquals(2, $PPReader->GetPassedRowsCount());

        unset($PPReader);

    }
}
