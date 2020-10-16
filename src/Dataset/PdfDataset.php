<?php

declare(strict_types=1);

namespace App\Dataset;

use DateInterval;
use DateTime;
use Exception;

final class PdfDataset extends AbstractDataset
{
    
    private array $nameColumns;
    private array $keysSubTotal = [];

    /**
     * @param string[] $nameColumns
     */
    public function __construct(array $nameColumns = [])
    {
        $this->data = [];
        $this->nameColumns = $nameColumns;
    }

    public function getKeysSubTotal(): array
    {
        return $this->keysSubTotal;
    }

    /**
     * Добавляет значения в массив данных
     *
     * @param array $arv Массив значений
     * @return void
     */
    public function addRow(array $arv)
    {
        if (count($arv) != count($this->nameColumns)) {
            throw new Exception(
                'Кол-во аргументов не соотвествует количеству столбцов',
                1
            );
        }

        $this->data[] = $arv;
    }

    /**
     * Возращает кол-во столбцов
     *
     * @return integer
     */
    public function getCountColumn(): int
    {
        return count($this->nameColumns);
    }

    /**
     * Возращает названия столбцов
     *
     * @return string[]
     */
    public function getNameColumn(): array
    {
        return $this->nameColumns;
    }

    private function isValidNameColumn(array $nameColumns): bool
    {
        foreach ($nameColumns as $column) {
            if (!isset($column, $this->nameColumns)) {
                throw new Exception("$column отсутствует в массиве");
            }
        }
        return true;
    }


    /**
     * Проверяет на валидность шаблон кол-во аргументов в строке
     *
     * @param array $array Массив значений
     * @param string $templateRow
     * @return boolean
     */
    private function isValidTemplate(array $array, string $templateRow): bool
    {
        if (count($array) != preg_match_all('/\%\d/m', $templateRow)) {
            throw new Exception('Кол-во аргументов не соотвествует количеству в шаблоне', 1);
        }

        return true;
    }

    private function getKeysOnColumn(array $nameColumns)
    {
        $result = [];
        foreach ($nameColumns as $column) {
            if (isset($column, $this->nameColumns)) {
                $result[$column] = array_search($column, $this->nameColumns);
            }
        }
        return $result;
    }

    public function addTotal(array $nameColumns, string $templateRow)
    {
        $this->isValidNameColumn($nameColumns);
        $this->isValidTemplate($nameColumns, $templateRow);
        $keysColumn = $this->getKeysOnColumn($nameColumns);

        $total = [];
        foreach ($this->data as $key => $value) {
            
            foreach ($keysColumn as $column => $key) {
                if (is_string($value[$key])) {
                    continue;
                }
                if($value[$key] instanceof DateInterval)
                {
                    $total[$column] = $total[$column] ?? new DateTime("00:00");
                    $total[$column]->add($value[$key]);
                }else{
                    $total[$column] = $total[$column] ?? 0;
                    $total[$column] += $value[$key];
                }
            }
        }

        $totalStr = $this->getPrepareTemplate($nameColumns, $templateRow, $total);

        $this->pushTotalStr($totalStr);
    }

    public function addSubTotal(array $nameColumns, string $templateRow)
    {
        $this->isValidTemplate($nameColumns, $templateRow);
        //проверяет $nameColumns на наличие столбцов(заголовков) в отчёте
        $this->isValidNameColumn($nameColumns);

        $keysColumn = $this->getKeysOnColumn($nameColumns);
        $data_array_reserve = array_reverse($this->data);
        $total = [];
        //подсчёт снизу вверх, итогов до ключа в keysSubTotal
        foreach ($data_array_reserve as $key => $value) {
            if (in_array(count($this->data) - $key, $this->keysSubTotal)) {
                break;
            }
            $total = $this->getTotal($keysColumn, $value, $total);
        }

        $total_str = $this->getPrepareTemplate(
            $nameColumns,
            $templateRow,
            $total
        );
        // $this->sub_total[array_key_last($this->data)] = $total_str;
        $this->pushTotalStr($total_str);
    }

    private function getTotal(array $keysColumn, $value, array $total)
    {
        foreach ($keysColumn as $column => $key) {
            if (is_string($value[$key])) {
                continue;
            }
            if($value[$key] instanceof DateInterval)
            {
                $total[$column] = $total[$column] ?? new DateTime("00:00");
                $total[$column]->add($value[$key]);
                continue;
            }
            $total[$column] = $total[$column] ?? 0;
            $total[$column] += $value[$key];
        }
        return $total;
    }

    private function pushTotalStr(string $total_str)
    {
        $this->data[] = $total_str;
        $this->keysSubTotal[] = array_key_last($this->data);
    }


    /**
     * Возращает готовую строку, шаблон берётся
     * из $templateRow, вместо %0, %1 ... %N
     * Поставляет $total, важно чтобы кол-во элементов name_colums
     * совпадало с аргументами и $total
     *
     * @param array $nameColumns Имя столбцов из таблицы(по полю какому делать итог)
     * @param string $templateRow Шаблон строки
     * @param array $total Массив итогов, поставляется вместо %0, %1
     * @return string
     */
    private function getPrepareTemplate(
        array $nameColumns,
        string $templateRow,
        array $total
    ): string {
        $this->isValidTemplate($nameColumns, $templateRow);
        $pattern = [];
        $replacements = [];
        foreach ($nameColumns as $key => $value) {
            $pattern[] = "/%$key/";
            // dd(preg_match("/%$key/", $templateRow));
            if($total[$value] instanceof DateTime)
            {
                $replacements[] = $total[$value]->format(self::TIME_FORMAT);
                continue;
            }
            $replacements[] = $total[$value];
        }
        // dd($pattern, $replacements);
        $str = preg_replace($pattern, $replacements, $templateRow);
        return $str;
    }
}
