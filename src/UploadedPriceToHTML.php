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
    protected $_exts = null;
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
    protected $_oldFilesNum = null;

    //----PriceListParser----
    /**
     * @var array
     */
    protected $_arColsToParse = null;
    /**
     * @var int
     */
    protected $_headRowsPass = null;
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

    /**
     * Allowed spreadsheet file extensions.
     *
     * @param array $exts
     * @return UploadedPriceToHTML
     */
    public function setAllowedExts(array $exts): UploadedPriceToHTML
    {
        $this->_exts = $exts;
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
    public function setOldPricesNum(int $oldFilesNum): UploadedPriceToHTML
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
    public function setHeadRowsPass(int $headRowsPass): UploadedPriceToHTML
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
    public function setArNotEmptyCols(array $arNotEmptyCols): UploadedPriceToHTML
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
    public function SetErrorPage(iMessagePage $messagePage): UploadedPriceToHTML
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
    public function SetOkPage(iMessagePage $messagePage): UploadedPriceToHTML
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
        if (!$this->_checkPriceListFile()) {
            return false;
        }



        return true;
    }



    protected function _storeFileOnServer(): bool
    {
        $res = true;

        try {
            if ($this->_exts !== null) {
                $checkPriceListFile = new CheckPriceListFile($this->_exts);
            } else {
                $checkPriceListFile = new CheckPriceListFile();
            }
        } catch (\Exception $e) {
            $res = false;
            $this->_callErrorPage($e->getMessage());
        } finally {
            $checkPriceListFile = null;
        }

        return $res;
    }


    protected function _checkPriceListFile(): bool
    {
        $res = true;

        try {
            if ($this->_exts !== null) {
                $checkPriceListFile = new CheckPriceListFile($this->_exts);
            } else {
                $checkPriceListFile = new CheckPriceListFile();
            }
        } catch (\Exception $e) {
            $res = false;
            $this->_callErrorPage($e->getMessage());
        } finally {
            $checkPriceListFile = null;
        }

        return $res;
    }


    protected function _callokPage($str)
    {
        $this->_callPage($this->_okPage, $str);
    }


    protected function _callErrorPage($str)
    {
        $this->_callPage($this->_errorPage, $str);
    }


    protected function _callPage($page, string $msg)
    {
        if ($page instanceof iMessagePage) {
            echo $page->GetMessagePage($msg);
        }
    }

}