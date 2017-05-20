<?php

namespace makikam\PriceToHTML;


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
        $tplVarsCount = $this->_getParamsInTemplate($this->_tpl, $arTplVars);

        if ($tplVarsCount === 0) {
            throw new \InvalidArgumentException('no variables found in template');
        }

        $fhandle = fopen($this->_dstFile, 'ab');
        $arTplVarsOrder = $this->_getTemplateVarsOrder($arTplVars);

        foreach ($this->_arPrice as $arPricePos) {
            $this->_checkPricePositionConsistency($arPricePos, $tplVarsCount);
            $arReorderedTplVarsPos = $this->_getReorderedTplVarsPositions($arTplVarsOrder, $arPricePos);

            fwrite($fhandle, str_replace($arTplVars, $arReorderedTplVarsPos, $this->_tpl));
        }

        fclose($fhandle);
    }


    private function _getTemplateVarsOrder(array $arTplVars)
    {
        $res = [];
        foreach ($arTplVars as $tplVar) {
            $res[] = (int)substr($tplVar, 1);
        }

        return $res;
    }


    private function _getReorderedTplVarsPositions(array $arTplVarsOrder, array $arPricePos): array
    {
        $res = [];

        foreach ($arTplVarsOrder as $idx) {
            $res[] = $arPricePos[$idx];
        }

        return $res;
    }


    private function _checkPricePositionConsistency(array $arPricePos, int $tpl_count)
    {
        if (count($arPricePos) !== $tpl_count) {
            throw new \InvalidArgumentException('wrong number of elements in price positions array. It\'s not equal to number of variables in template string: ');
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
        $this->_arWarningsMessages[] = 'At least one column in position is empty: ' . implode(', ', $arInconsistentPricePos);
    }


    private function _checkParams(string $headFile, string $footFile, string $dstFile, array $arPrice, string $template_line)
    {
        $this->_checkHeaderFooterFiles($headFile, $footFile);
        $this->_checkDstFileWritePermissions($dstFile);
        $this->_checkPricesArray($arPrice);
        $this->_checkNotEmptyLine($template_line);
    }


    private function _checkHeaderFooterFiles(string $headFile, string $footFile)
    {
        if (!(is_readable($headFile) && is_readable($footFile))) {
            throw new \InvalidArgumentException('can\'t read header or footer html file');
        }
    }


    private function _checkDstFileWritePermissions(string $dstFile)
    {
        if (file_exists($dstFile)) {
            $dst = $dstFile;
        } else {
            $dst = pathinfo($dstFile, PATHINFO_DIRNAME);
        }


        if (!is_writable($dst)) {
            throw new \InvalidArgumentException('can\'t write new file: '.$dstFile);
        }
    }


    private function _checkPricesArray(array $arPrice)
    {
        if (count($arPrice) < 1) {
            throw new \InvalidArgumentException('empty pricelist array');
        }
    }


    private function _checkNotEmptyLine(string $tpl)
    {
        if ($tpl === '') {
            throw new \InvalidArgumentException('empty template string');
        }
    }


    private function _getParamsInTemplate(string $tpl, &$arTplVars): int
    {
        $res = preg_match_all('/(%\d+)/', $tpl, $arTplVars);
        $arTplVars = $arTplVars[0];
        return $res;
    }


    private function _storeParams(string $headFile, string $footFile, string $dstFile, array $arPrice, string $template_line)
    {
        $this->_headFile = $headFile;
        $this->_footFile = $footFile;
        $this->_dstFile = $dstFile;
        $this->_arPrice = $arPrice;
        $this->_tpl = $template_line;
    }
}