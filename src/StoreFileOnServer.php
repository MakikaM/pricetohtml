<?php

namespace makikam\PriceToHTML;


class StoreFileOnServer
{
    protected $dstPath;//destination filepath
    protected $dstName;//destination filename
    protected $oldFilesPath;//где хранятся старые прайсы
    protected $oldFilesNum;//сколько старых прайсов хранить


    public function __construct(string $destFPath, $destFName, $oldFilesPath, int $oldFilesNum = 3)
    {
        //TODO oldFilesPath and oldFilesNum must be optionial
        $this->checkConstructionParams($destFPath, $destFName, $oldFilesPath);
        $this->dstPath = $destFPath;
        $this->dstName = $destFName;
        $this->oldFilesPath = $oldFilesPath;
        $this->oldFilesNum = $oldFilesNum;

        $this->_moveOldPricelist();
        $this->_moveTempFileToPricelist();
        $this->_deleteOldPricelists();
    }

    private function _deleteOldPricelists()
    {
        $arfiles = $this->_getFiles($this->oldFilesPath);

        $killcount = count(array_keys($arfiles)) - $this->oldFilesNum;

        if ($killcount < 1) {
            return;
        }

        ksort($arfiles);
        for ($i = 0; $i < $killcount; $i++) {
            $file = array_shift($arfiles);
            unlink($this->oldFilesPath . $file['name']);
        }

    }


    private function _getFiles(string $dpath): array
    {
        $files = [];
        $fsi = new \FilesystemIterator($dpath);

        foreach ($fsi as $entry) {
            if ($entry->isFile()) {
                $ftime = $entry->getMTime();

                while (array_key_exists($ftime, $files)) {
                    $ftime++;
                }

                $files[$ftime]['name'] = $entry->getFilename();
            }
        }

        return $files;
    }


    private function _moveOldPricelist()
    {
        if (file_exists($this->dstPath . '/' . $this->dstName)) {

            $fn = pathinfo($this->dstName);
            $archname = $fn['filename'] . '.' . date('m-d-Y_H-i-s-a', time()) . '.' . $fn['extension'];
            rename($this->dstPath . '/' . $this->dstName, $this->oldFilesPath . '/' . $archname);
        }
    }


    private function _moveTempFileToPricelist()
    {
        rename(sys_get_temp_dir() . '/' . $_FILES['file']['tmp_name'], $this->dstPath . '/' . $this->dstName);
    }


    private function checkConstructionParams(string $destFPath, $destFName, $oldFilesPath)
    {
        $err_msg = 'Empty path in constructor: ';
        $error = false;

        if ($destFPath === '') {
            $err_msg .= 'Destination path. ';
            $error = true;
        }

        if ($destFName === '') {
            $err_msg .= 'Destination filename. ';
            $error = true;
        }

        if ($oldFilesPath === '') {
            $err_msg = 'Old files path. ';
            $error = true;
        }

        if ($error) {
            throw new \InvalidArgumentException($err_msg);
        }


        $err_msg = 'Wrong path in constructor: ';

        if (!is_dir($destFPath)) {
            $err_msg .= 'Destination path is not directory. ';
            $error = true;
        }

        if (!is_dir($oldFilesPath)) {
            $err_msg .= 'Old files path is not directory. ';
            $error = true;
        }

        if ($error) {
            throw new \InvalidArgumentException($err_msg);
        }

        if (!is_writable($destFPath)) {
            $err_msg .= 'Destination path is not writeable. ';
            $error = true;
        }

        if(!is_writable($oldFilesPath)){
            $err_msg .= 'Old files path is not writeable. ';
            $error = true;
        }

    if ($error) {
        throw new \InvalidArgumentException($err_msg);
    }
}

}

