<?php

namespace MakikaM\PriceToHTML;

use PHPUnit\Framework\TestCase;


class CheckPriceListFileTest extends TestCase
{
    public function testEmptyExtsInConstructor()
    {
        $this->expectException(\InvalidArgumentException::class);
        $tmp = new CheckPriceListFile([]);
    }


    public function testFileErrorUpload()
    {
        $_FILES = [
            'file' => [
                'name' => 'qwe',
                'type' => 'asd',
                'tmp_name' => 'asdasd',
                'error' => UPLOAD_ERR_FORM_SIZE, // код ошибки
                'size' => 10
            ]
        ];

        $this->expectExceptionCode(CheckPriceListFile::E_FILE_UPLOAD);
        $tmp = new CheckPriceListFile();
    }

    public function testFileWrongExtension()
    {
        $_FILES = [
            'file' => [
                'name' => 'qwe.zsd',
                'type' => 'asd',
                'tmp_name' => 'asdasd',
                'error' => UPLOAD_ERR_OK,
                'size' => 10
            ]
        ];

        $this->expectExceptionCode(CheckPriceListFile::E_CANT_OPEN);
        $tmp = new CheckPriceListFile();
    }

    public function testCorruptedFile()
    {
        $test_file = 'test_corrupted_excel_file.xls';
        $_FILES = [
            'file' => [
                'name' => $test_file,
                'type' => 'asd',
                'tmp_name' => $test_file,
                'error' => UPLOAD_ERR_OK,
                'size' => 10
            ]
        ];

        if (!copy(__DIR__ . '/excel/' . $test_file, sys_get_temp_dir() . $test_file)) {
            throw new \Exception('cant create test xls file!');
        }

        $this->expectExceptionCode(CheckPriceListFile::E_CANT_OPEN);
        $tmp = new CheckPriceListFile();
    }

    public function tearDown()
    {
        if (isset($_FILES)) {
            unset($_FILES);
        }
    }
}
