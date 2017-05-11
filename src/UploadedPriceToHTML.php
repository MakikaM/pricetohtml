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
    private $_errorPage = null;
    /**
     * @var IMessagePage
     */
    private $_okPage = null;
    //----CheckPriceListFile----
    /**
     * @var string
     */
    private $_exts = null;
    //----StoreFileOnServer-----
    /**
     * @var string
     */
    private $_destPriceListPath = null;
    /**
     * @var string
     */
    private $_destPriceListFName = null;
    /**
     * @var string
     */
    private $_oldFilesPath = null;
    /**
     * @var int
     */
    private $_oldFilesNum = null;
    //----PriceListParser----
    /**
     * @var array
     */
    private $_arColsToParse = null;
    /**
     * @var int
     */
    private $_headRowsPass = null;
    /**
     * @var array
     */
    private $_arNotEmptyCols = null;
    //----PricesArrayToHTMLFile----
    /**
     * @var string
     */
    private $_headHTMLFile = null;
    /**
     * @var string
     */
    private $_footHTMLFile = null;
    /**
     * @var string
     */
    private $_dstHTMLFile = null;
    /**
     * @var array
     */
    private $_arPrice = null;
    /**
     * @var string
     */
    private $_templateString = null;

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
     * Let's go
     * @return bool
     */
    public function Do(): bool
    {

    }


}