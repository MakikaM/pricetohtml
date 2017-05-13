<?php

namespace makikam\PriceToHTML;


/**
 * Class CheckPriceListFile
 *
 * @throws
 * @package makikam\PriceToHTML
 */
class CheckPriceListFile
{
    CONST E_FILE_UPLOAD = 2;
    CONST E_CANT_OPEN = 3;

    private $maxUplFiles = 1;
    private $arExts;


    public function __construct(array $arExts = ['xls', 'xlsx'])
    {
        $this->_checkConstructorParam($arExts);
        $this->arExts = $arExts;

        $this->_checkFILESOnErrors();
    }


    /**
     * @throws \InvalidArgumentException
     * @param array $exts
     */
    private function _checkConstructorParam(array $exts)
    {
        if (count($exts) === 0) {
            throw new \InvalidArgumentException('need allowed file extensions');
        }

        foreach ($exts as $ext) {
            if ($ext === '') {
                throw new \InvalidArgumentException('empty file extension');
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function _checkFILESOnErrors()
    {
        if (count(array_keys($_FILES)) > $this->maxUplFiles) {
            throw new \Exception('Too much files uploaded', CheckPriceListFile::E_FILE_UPLOAD);
        }

        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('File upload error', CheckPriceListFile::E_FILE_UPLOAD);
        }

        if (!$this->_isAcceptableFileExtension($_FILES['file']['name'])) {
            throw new \Exception('Wrong file extension', CheckPriceListFile::E_CANT_OPEN);
        }

        if (!$this->_isParseable($_FILES['file']['tmp_name'])) {
            throw new \Exception("Can't parse file", CheckPriceListFile::E_CANT_OPEN);
        }
    }


    /**
     * @param string $fname
     * @return bool
     * @throws \Exception
     */
    private function _isParseable(string $fname): bool
    {
        $res = true;

        try {
            $Reader = new \SpreadsheetReader(sys_get_temp_dir() . "\\" . $fname);
        } catch (\Exception $e) {
            //throw new \Exception($e->getMessage(), CheckPriceListFile::E_CANT_OPEN);
            $res = false;
        } finally {
            unset($Reader);
        }

        return $res;
    }


    private function _isAcceptableFileExtension(string $fname): bool
    {
        $ext = pathinfo($fname, PATHINFO_EXTENSION);
        return in_array($ext, $this->arExts, true);
    }

}