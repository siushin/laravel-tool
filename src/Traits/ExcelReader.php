<?php
declare(strict_types=1);

namespace Siushin\LaravelTool\Traits;

use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Excel读取
 */
trait ExcelReader
{
    use ExcelTool;

    /**
     * 获取Excel行数据
     *  <p>
     *  如果$callbackIterator不为空，则按行返回；否则，读取完数据行数据返回
     *  </p>
     * @param string     $inputFileName    完整文件名，包括路径
     * @param array      $columnMap        列名与字段名映射，比如传入：['name', 'age']，则返回：['A' => 'name', 'B' => 'age']
     * @param mixed|null $callbackIterator 回调函数，返回一个数组，用于处理每一行数据。如果为null，则返回一个二维数组，包含所有行数据
     * @param int        $startRow         开始行数，默认为2，表示从第二行开始读取
     * @param int        $endRow           结束行数，默认为-1，表示读取所有行
     * @return array
     * @throws Exception
     */
    static function getExcelRowData(string $inputFileName, array $columnMap, mixed $callbackIterator = null, int $startRow = 2, int $endRow = -1): array
    {
        ini_set('memory_limit', '1024M');

        $data = [];

        try {
            $inputFileType = IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);

            $spreadsheet = $reader->load($inputFileName);
            // 获取第一个工作表
            $worksheet = $spreadsheet->getActiveSheet();

            // 获取总列数
            $totalColumns = self::getExcelColumnIndex($worksheet->getHighestDataColumn());
            $columnNames = self::generateExcelColumnNames($totalColumns);
            // 列名与字段名映射
            $cellMap = [];
            for ($i = 0; $i < $totalColumns; $i++) {
                if (!isset($columnMap[$i])) break;
                $cellMap[$columnNames[$i]] = $columnMap[$i];
            }
            // 获取总行数
            $totalRows = $worksheet->getHighestDataRow();
            // 指定返回行数
            $endRow = $endRow <= 0 ? $totalRows : min($endRow, $totalRows);

            // 迭代读取每一行
            foreach ($worksheet->getRowIterator() as $row) {
                $rowLine = $row->getRowIndex();
                if ($rowLine < $startRow) continue; // 跳过表头的行数
                if ($rowLine > $endRow) break;  // 读取指定行数，大于行数，结束循环

                // 每次循环只处理一行
                $rowData = [];
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // 设置迭代器以遍历所有单元格

                foreach ($cellIterator as $index => $cell) {
                    $cellValue = mb_convert_encoding((string)$cell->getValue(), 'UTF-8', 'auto');
                    if (isset($cellMap[$index])) {
                        $rowData[$cellMap[$index]] = $cellValue;
                    } else {
                        $rowData[] = $cellValue;
                    }
                }

                // 数据处理方式
                if ($callbackIterator) {
                    $callbackIterator($rowData);
                } else {
                    $data[] = $rowData;
                }

                // 清除行数据和单元格迭代器的引用、清除行对象的引用
                unset($rowData, $cellIterator, $row);
            }
        } catch (Exception $e) {
            throw_exception($e->getMessage());
        }

        unset($worksheet, $spreadsheet);

        // 触发垃圾回收
        gc_collect_cycles();

        return $data;
    }
}