<?php

namespace makikam\PriceToHTML;

/*Array
(
  [0] => Номер по каталогу
  [1] => Аналоги
  [2] => Марка
  [3] => Модель
  [4] => Производитель
  [5] => Наименование
  [6] => Мин.
  [7] => Цена Опт
)
Array
(
  [0] => П00035
  [1] =>
  [2] =>
  [3] =>
  [4] => Корея
  [5] => ZIP WAX Шампунь 750 мл
  [6] => 1
  [7] => 204.09
)*/

class PriceListParser
{
    private $_file;
    private $_colsParse = [];
    private $_headRowsPass = 0;
    private $_notEmptyCols = [];

    private $_parsedArray = [];
    private $_colsPassed = 0;
    private $_colsParsed = 0;


    public function __construct(string $file, array $colsParse, int $headRowsPass, array $notEpmtyCols)
    {
        $this->_checkParams($colsParse);

        $this->_file = $file;
        $this->_colsParse = $colsParse;
        $this->_headRowsPass = $headRowsPass;
        $this->_notEmptyCols = $notEpmtyCols;

        $this->_Parse();
    }

    private function _Parse()
    {
        try {
            $SRReader = new \SpreadsheetReader($this->_file);
            $this->_parseSpreadSheet($SRReader);
        } catch (\Exception $e) {
            throw $e;
        } finally {
            unset($SRReader);
        }
    }


    private function _parseSpreadSheet(\SpreadsheetReader $SRReader)
    {
        $idx = 0;


        foreach ($SRReader as $row) {
            if ($idx < $this->_headRowsPass) {
                $idx++;
                continue;
            }

            foreach ($this->_notEmptyCols as $neCol) {
                if (trim($row[$neCol]) === '') {
                    $this->_colsPassed++;
                    continue 2;
                }
            }

            $newRec = [];

            foreach ($this->_colsParse as $col) {
                $newRec[] = $row[$col];
            }

            $this->_parsedArray[] = $newRec;
            $this->_colsParsed++;
        }
    }


    private function _checkParams(array $cols_parse)
    {
        if (count($cols_parse) < 1) {
            throw new \InvalidArgumentException('$cols_parse должно быть непустым массивом номеров колонок');
        }

        foreach ($cols_parse as $col) {
            if (abs((int)$col) !== $col) {
                throw new \InvalidArgumentException('$col_parse содержит не целочисленное положительное значение колонки: ' . $col);
            }
        }
    }


    public function GetParsedArray(): array
    {
        return $this->_parsedArray;
    }

    public function GetParsedRowsCount(): int
    {
        return $this->_colsParsed;
    }

    public function GetPassedRowsCount(): int
    {
        return $this->_colsPassed;
    }


}