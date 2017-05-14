<?php
/**
 * Created by PhpStorm.
 * User: makc
 * Date: 11.05.17
 * Time: 17:45
 */

namespace makikam\PriceToHTML;


class UploadedPriceToHTML
{
    /**
     * @var IMessagePage
     */
    protected $_errorPage = null;
    /**
     * @var IMessagePage
     */
    protected $_okPage = null;

    //----CheckPriceListFile----
    /**
     * @var string
     */
    protected $_exts = ['xls', 'xlsx'];
    //----StoreFileOnServer-----
    /**
     * @var string
     */
    protected $_destPriceListPath = null;
    /**
     * @var string
     */
    protected $_destPriceListFName = null;
    /**
     * @var string
     */
    protected $_oldFilesPath = null;
    /**
     * @var int
     */
    protected $_oldFilesNum = 3;

    //----PriceListParser----
    /**
     * @var array
     */
    protected $_arColsToParse = null;
    /**
     * @var int
     */
    protected $_headRowsPass = 0;
    /**
     * @var array
     */
    protected $_arNotEmptyCols = null;

    //----PricesArrayToHTMLFile----
    /**
     * @var string
     */
    protected $_headHTMLFile = null;
    /**
     * @var string
     */
    protected $_footHTMLFile = null;
    /**
     * @var string
     */
    protected $_dstHTMLFile = null;
    /**
     * @var array
     */
    protected $_arPrice = null;
    /**
     * @var string
     */
    protected $_templateString = null;

    //----UploadedPriceToHTML----
    /**
     * @var string
     */
    protected $_exitMessage = '';


    /**
     * Allowed spreadsheet file extensions.
     *
     * @param array $arExts
     * @return UploadedPriceToHTML
     */
    public function setAllowedExts(array $arExts = ['xls', 'xlsx']): UploadedPriceToHTML
    {
        $this->_exts = $arExts;
        return $this;
    }


    /**
     * Sets path where new excel pricelist file will be stored.
     *
     * @param string $destPriceListPath
     * @return UploadedPriceToHTML
     */
    public function setDestinationPath(string $destPriceListPath): UploadedPriceToHTML
    {
        $this->_destPriceListPath = $destPriceListPath;
        return $this;
    }


    /**
     * Sets excel pricelist file name on server.
     *
     * @param string $destPriceListFName
     * @return UploadedPriceToHTML
     */
    public function setDestFileName(string $destPriceListFName): UploadedPriceToHTML
    {
        $this->_destPriceListFName = $destPriceListFName;
        return $this;
    }


    /**
     * Sets path where to move current pricelist file if exists.
     *
     * @param string $oldFilesPath
     * @return UploadedPriceToHTML
     */
    public function setOldPricesPath(string $oldFilesPath): UploadedPriceToHTML
    {
        $this->_oldFilesPath = $oldFilesPath;
        return $this;
    }


    /**
     * Sets number of files to keep on server, other oldest files in folder will be deleted
     *
     * @param int $oldFilesNum
     * @return UploadedPriceToHTML
     */
    public function setOldPricesNum(int $oldFilesNum = 3): UploadedPriceToHTML
    {
        $this->_oldFilesNum = $oldFilesNum;
        return $this;
    }


    /**
     * Sets columns indexes not names in excel file.
     *
     * @param array $arColsToParse starts from 0
     * @return UploadedPriceToHTML
     */
    public function setColumnsToParse(array $arColsToParse): UploadedPriceToHTML
    {
        $this->_arColsToParse = $arColsToParse;
        return $this;
    }


    /**
     * Sets number of first rows which will be passed(for passing logo, contact information etc.).
     *
     * @param int $headRowsPass
     * @return UploadedPriceToHTML
     */
    public function setHeadRowsPass(int $headRowsPass = 0): UploadedPriceToHTML
    {
        $this->_headRowsPass = $headRowsPass;
        return $this;
    }


    /**
     * Sets columns that must not be empty, if so row will be passed.
     *
     * @param array $arNotEmptyCols
     * @return UploadedPriceToHTML
     */
    public function setNotEmptyCols(array $arNotEmptyCols): UploadedPriceToHTML
    {
        $this->_arNotEmptyCols = $arNotEmptyCols;
        return $this;
    }

    /**
     * Sets path and filename of head part of generating html file.
     *
     * @param string $headHTMLFile
     * @return UploadedPriceToHTML
     */
    public function setHeadHTMLFile(string $headHTMLFile): UploadedPriceToHTML
    {
        $this->_headHTMLFile = $headHTMLFile;
        return $this;
    }

    /**
     * Sets path and filename of foot part of generating html file.
     *
     * @param string $footHTMLFile
     * @return UploadedPriceToHTML
     */
    public function setFootHTMLFile(string $footHTMLFile): UploadedPriceToHTML
    {
        $this->_footHTMLFile = $footHTMLFile;
        return $this;
    }

    /**
     * Sets path and filename of resulting HTML file with price list.
     *
     * @param string $dstHTMLFile
     * @return UploadedPriceToHTML
     */
    public function setDstHTMLFile(string $dstHTMLFile): UploadedPriceToHTML
    {
        $this->_dstHTMLFile = $dstHTMLFile;
        return $this;
    }


    /**
     * Sets template for generation HTML price list positions.
     *
     * @param string $templateString
     * @return UploadedPriceToHTML
     */
    public function setTemplateString(string $templateString = "<tr><td>%0 - %1</td><td>%2</td></tr>\n"): UploadedPriceToHTML
    {
        $this->_templateString = $templateString;
        return $this;
    }


    /**
     * Sets function which will be called on parse error.
     *
     * @param iMessagePage
     * @return UploadedPriceToHTML
     */
    public function setErrorPage(iMessagePage $messagePage): UploadedPriceToHTML
    {
        $this->_errorPage = $messagePage;
        return $this;
    }


    /**
     * Sets function which will be called on parse ok
     *
     * @param iMessagePage
     * @return UploadedPriceToHTML
     */
    public function setOkPage(iMessagePage $messagePage): UploadedPriceToHTML
    {
        $this->_okPage = $messagePage;
        return $this;
    }


    /**
     * Starts parsing.
     *
     * @return bool
     */
    public function Do(): bool
    {
        $this->_exitMessage = '';

        if ($this->_checkPriceListFile() &&
            $this->_storeFileOnServer() &&
            $this->_priceListParser() &&
            $this->_pricesArrayToHTMLFile()
        ) {
            if ($this->_exitMessage !== '') {
                $this->_showOkPage($this->_exitMessage);
            }
            return true;
        }

        return false;
    }


    protected function _pricesArrayToHTMLFile(): bool
    {
        return $this->_createVoidClass(
            'PricesArrayToHTML',
            $this->_headHTMLFile,
            $this->_footHTMLFile,
            $this->_dstHTMLFile,
            $this->_arPrice,
            $this->_templateString = "<tr><td>%0 - %1</td><td>%2</td></tr>\n"
        );
    }


    protected function _priceListParser(): bool
    {
        $res = true;

        try {
            $pricelistFile = $this->_destPriceListPath . $this->_destPriceListFName;
            $priceListParser = new PriceListParser($pricelistFile, $this->_arColsToParse, $this->_headRowsPass, $this->_arNotEmptyCols);
            $this->_arPrice = $priceListParser->GetParsedArray();
            $this->_exitMessage .= "\n Rows parsed :" . $priceListParser->GetParsedRowsCount();
            $this->_exitMessage .= "\n Rows passed :" . $priceListParser->GetPassedRowsCount();
            $this->_exitMessage .= "\n Total :" . ($priceListParser->GetParsedRowsCount() + $priceListParser->GetPassedRowsCount()) . "\n";
        } catch (\Exception $e) {
            $res = false;
            $this->_processErrorText($e->getMessage());
        }

        return $res;
    }


    protected function _storeFileOnServer(): bool
    {
        return $this->_createVoidClass('StoreFileOnServer', $this->_destPriceListPath, $this->_destPriceListFName, $this->_oldFilesPath, $this->_oldFilesNum);
    }


    protected function _checkPriceListFile(): bool
    {
        return $this->_createVoidClass('CheckPriceListFile', $this->_exts);
    }


    protected function _createVoidClass(string $className, ...$params)
    {
        $res = true;

        try {
            $reflectionClass = new \ReflectionClass(__NAMESPACE__ . '\\' . $className);
            $voidClass = $reflectionClass->newInstance(...$params);
        } catch (\Exception $e) {
            $res = false;
            $this->_processErrorText($className . ": " . $e->getMessage());
        }

        return $res;
    }


    public function getMessages(): string
    {
        return $this->_exitMessage;
    }


    protected function _showOkPage($str)
    {
        $this->_showPage($this->_okPage, $str);
    }


    protected function _processErrorText($str)
    {
        $this->_exitMessage = $str;
        $this->_showPage($this->_errorPage, $str);
    }


    protected function _showPage($page, string $msg)
    {
        if ($page instanceof iMessagePage) {
            echo $page->GetMessagePage($msg);
        }
    }

}