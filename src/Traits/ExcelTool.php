<?php
declare(strict_types=1);

namespace Siushin\LaravelTool\Traits;

/**
 * Excel工具
 */
trait ExcelTool
{
    /**
     * 列号 转换为 Excel 列名
     * @param int $columnNumber 列号，将数字转换为Excel列名，比如传入：1，则返回：A
     * @return string
     */
    static function numberToExcelColumn(int $columnNumber): string
    {
        $columnName = '';
        while ($columnNumber > 0) {
            $columnNumber--; // 调整为0-indexed
            $remainder = $columnNumber % 26;
            $columnName = chr(65 + $remainder) . $columnName; // 65是ASCII码'A'
            $columnNumber = (int)($columnNumber / 26);
        }
        return $columnName;
    }

    /**
     * 生成指定数量的列名
     * @param int $totalColumns 列数，根据列数生成列名，比如传入：3，则返回数组 ['A', 'B', 'C']
     * @return array
     */
    static function generateExcelColumnNames(int $totalColumns): array
    {
        $columnNames = [];
        for ($i = 1; $i <= $totalColumns; $i++) {
            $columnNames[] = self::numberToExcelColumn($i);
        }
        return $columnNames;
    }

    /**
     * Excel列名 转换为 列号
     * @param string $columnName
     * @return int
     */
    static function getExcelColumnIndex(string $columnName): int
    {
        $index = 0;
        $length = strlen($columnName);
        for ($i = 0; $i < $length; $i++) {
            $index = $index * 26 + ord($columnName[$i]) - 64;
        }
        return $index;
    }
}