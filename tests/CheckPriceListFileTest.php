<?php

namespace makikam\PriceToHTML;

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

    public function testPriceCheckPassSuccess()
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
            $tmp = new CheckPriceListFile();
            $this->assertInstanceOf(__NAMESPACE__.'\\'.CheckPriceListFile, $tmp);
        } finally {
            if (file_exists($tmp_file)) {
                unlink($tmp_file);
            }
            unset($tmp);
        }

    }

    public function tearDown()
    {
        if (isset($_FILES)) {
            unset($_FILES);
        }
    }
}
