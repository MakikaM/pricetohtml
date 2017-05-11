<?php
/**
 * Created by PhpStorm.
 * User: makc
 * Date: 11.05.17
 * Time: 17:45
 */

namespace MakikaM\PriceToHTML;


class UploadedPriceToHTML
{
    private $_errorPage = null;


    public function SetErrorPage(iMessagePage $messagePage): UploadedPriceToHTML
    {
        if ($messagePage !== null) {
            $this->_errorPage = $messagePage;
        }

        return $this;
    }


    public function Do()
    {

    }


}