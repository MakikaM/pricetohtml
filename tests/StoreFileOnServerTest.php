<?php

namespace makikam\PriceToHTML;

use PHPUnit\Framework\TestCase;

class StoreFileOnServerTest extends TestCase
{
    public function testConstructorEmptyDestPath()
    {
        $this->expectException(\InvalidArgumentException::class);
        $tmp = new StoreFileOnServer('', 'qwe', 'qwe');
    }

    public function testConstructorWrongDestDirectory()
    {
        $this->expectException(\InvalidArgumentException::class);
        $tmp = new StoreFileOnServer('wrong_path', 'qwe', '..', 1);
    }

    public function testConstructorEmptyDestFile()
    {
        $this->expectException(\InvalidArgumentException::class);
        $tmp = new StoreFileOnServer('qwe', '', 'qwe');
    }

    public function testConstructorEmptyOldFilesPath()
    {
        $this->expectException(\InvalidArgumentException::class);
        $tmp = new StoreFileOnServer('qwe', 'qwe', '');
    }

    public function testConstructorWrongOldFilesPath()
    {
        $this->expectException(\InvalidArgumentException::class);
        $tmp = new StoreFileOnServer('..', 'qwe', 'wrong_path', 1);
    }

    public function testOldFilesRemovingAndPlacingNewPriceOnPlace()
    {
        $oft_dir = __DIR__ . '/' . 'old_files_test/';
        $keep_n_old_prices = 2;

        touch($oft_dir . 'test_file_1.xls', time() - 1);
        touch($oft_dir . 'test_file_2.xls', time() - 2);
        touch($oft_dir . 'test_file_3.xls', time() - 3);
        touch(__DIR__ . '/excel/' . 'test_file.xls', time());
        copy(__DIR__ . '/excel/test_correct_price.xls', sys_get_temp_dir() . '/' . 'tmp_price.xls');

        $_FILES = [
            'file' => [
                'name' => 'test_file.xls',
                'type' => 'asd',
                'tmp_name' => 'tmp_price.xls',
                'error' => UPLOAD_ERR_OK,
                'size' => 10
            ]
        ];

        try {
            $tmp = new StoreFileOnServer(__DIR__ . '/excel/', 'test_file.xls', $oft_dir, $keep_n_old_prices);
        } catch (\Exception $e){
            throw $e;
        } finally {
            unset($_FILES);
            unset($tmp);
        }

        $this->assertFileNotExists($oft_dir . 'test_file_2.xls');
        $this->assertFileNotExists($oft_dir . 'test_file_3.xls');
        $this->assertEquals($keep_n_old_prices, $this->_getFilesCount($oft_dir));
    }

    private function _getFilesCount(string $dpath): int
    {
        $fcount = 0;
        $fsi = new \FilesystemIterator($dpath);

        foreach ($fsi as $entry) {
            if ($entry->isFile()) {
                $fcount++;
            }
        }

        return $fcount;
    }
}
