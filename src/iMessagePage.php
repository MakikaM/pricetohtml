<?php
/**
 * Created by PhpStorm.
 * User: makc
 * Date: 11.05.17
 * Time: 18:14
 */

namespace MakikaM\PriceToHTML;


interface iMessagePage
{
    public function GetMessagePage(string $message): string;
}