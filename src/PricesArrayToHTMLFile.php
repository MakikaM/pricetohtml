<?php

namespace MakikaM\PriceToHTML;


class PricesArrayToHTMLFile
{
    /**
     * @var string
     */
    private $_headFile;
    /**
     * @var string
     */
    private $_footFile;
    /**
     * @var string
     */
    private $_dstFile;
    /**
     * @var array
     */
    private $_arPrice;
    /**
     * @var string
     */
    private $_tpl;
    /**
     * @var int
     */
    private $_warningsCount = 0;
    /**
     * @var array
     */
    private $_arWarningsMessages = [];


    public function __construct(string $headFile, string $footFile, string $dstFile, array $arPrice, string $template_line = "<tr><td>%0 - %1</td><td>%2</td></tr>\n")
    {
        $this->_checkParams($headFile, $footFile, $dstFile, $arPrice, $template_line);
        $this->_storeParams($headFile, $footFile, $dstFile, $arPrice, $template_line);
        $this->_generateHTMLFile();
    }


    private function _generateHTMLFile()
    {
        copy($this->_headFile, $this->_dstFile);

        $this->_appendPriceLines();

        file_put_contents($this->_dstFile, file_get_contents($this->_footFile), FILE_APPEND | LOCK_EX);
    }


    private function _appendPriceLines()
    {
        //$arTplVars = [];
        $tplCount = $this->_getParamsInTemplate($this->_tpl, $arTplVars);

        if ($tplCount === 0) {
            throw new \InvalidArgumentException('в шаблоне прайса не указано ни одной подстановки');
        }

        $fhandle = fopen($this->_dstFile, 'ab');

        foreach ($this->_arPrice as $arPricePos) {
            $this->_checkPricePositionConsistency($arPricePos, $tplCount);
            fwrite($fhandle, str_replace($arTplVars, $arPricePos, $this->_tpl));
        }

        fclose($fhandle);
    }


    private function _checkPricePositionConsistency(array $arPricePos, int $tpl_count)
    {
        if (count($arPricePos) !== $tpl_count) {
            throw new \InvalidArgumentException('в массиве прайс-листа задана позиция с неверным количеством парметров: ' . print_r($arPricePos, true));
        }

        foreach ($arPricePos as $itm) {
            if ($itm === '') {
                $this->_addWarning($arPricePos);
            }
        }

    }


    private function _addWarning(array $arInconsistentPricePos)
    {
        $this->_warningsCount++;
        $this->_arWarningsMessages[] = 'По крайней мере одна колонка позиции пустая: ' . implode(', ', $arInconsistentPricePos);
    }


    private function _checkParams(string $headFile, string $footFile, string $dstFile, array $arPrice, string $template_line)
    {
        $this->_checkHeaderFooterFiles($headFile, $footFile);
        $this->_checkDstFileWritePermissions($dstFile);
        $this->_checkPricesArray($arPrice);
        $this->_checkNotEmptyLine($template_line);
    }


    /**
     * @param string $headFile
     * @param string $footFile
     */
    private function _checkHeaderFooterFiles(string $headFile, string $footFile)
    {
        if (!(is_readable($headFile) && is_readable($footFile))) {
            throw new \InvalidArgumentException('не могу прочитать header или footer html файл прайс-листа');
        }
    }


    /**
     * @param string $dstFile
     */
    private function _checkDstFileWritePermissions(string $dstFile)
    {
        if (file_exists($dstFile)) {
            $dst = $dstFile;
        } else {
            $dst = pathinfo($dstFile, PATHINFO_DIRNAME);
        }

        if (!is_writable($dst)) {
            throw new \InvalidArgumentException('не могу записать новый файл прайслиста');
        }
    }


    private function _checkPricesArray(array $arPrice)
    {
        if (count($arPrice) < 1) {
            throw new \InvalidArgumentException('пустой массив прайсов');
        }
    }


    private function _checkNotEmptyLine(string $tpl)
    {
        if ($tpl === '') {
            throw new \InvalidArgumentException('пустая строка шаблона HTML прайса');
        }
    }


    private function _getParamsInTemplate(string $tpl, &$arTplVars): int
    {

        $res = preg_match_all('/(%\d+)/', $tpl, $arTplVars);
        $arTplVars = $arTplVars[0];
        return $res;
    }

    /**
     * @param string $headFile
     * @param string $footFile
     * @param string $dstFile
     * @param array $arPrice
     * @param string $template_line
     */
    private function _storeParams(string $headFile, string $footFile, string $dstFile, array $arPrice, string $template_line)
    {
        $this->_headFile = $headFile;
        $this->_footFile = $footFile;
        $this->_dstFile = $dstFile;
        $this->_arPrice = $arPrice;
        $this->_tpl = $template_line;
    }
}