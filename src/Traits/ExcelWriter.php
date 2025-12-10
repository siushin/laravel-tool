<?php

namespace Siushin\LaravelTool\Traits;

use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * 工具类：Excel写入
 */
trait ExcelWriter
{
    use ExcelTool;

    /**
     * excel文件写入
     * @param array  $headers
     * @param array  $data
     * @param string $fileName
     * @param array  $extend_data 支持参数：save_path（保存路径，默认：/storage/app/excel/）、cell_color_list、column_center_list、column_auto_size_list、column_bg_color_list、freeze_pane_list
     * @return array                文件名、文件路径
     * @throws Exception
     * @author siushin<siushin@163.com>
     */
    public static function writerExcel(array $headers, array $data, string $fileName, array $extend_data = []): array
    {
        ini_set('memory_limit', '1024M');

        // 保存路径
        $save_path = $extend_data['save_path'] ?? '/storage/app/excel/';   //  保存路径，默认在/storage/app/excel
        // 设置样式
        $cell_color_list = $extend_data['cell_color_list'] ?? []; // 单元格颜色
        $column_center_list = $extend_data['column_center_list'] ?? []; // 列居中
        $column_auto_size_list = $extend_data['column_auto_size_list'] ?? []; // 列自适应宽度
        $column_bg_color_list = $extend_data['column_bg_color_list'] ?? []; // 列背景颜色
        $freezePane_list = $extend_data['freeze_pane_list'] ?? ['A2'];  // 冻结行/列，默认冻结首行（标题行）

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        // 设置整个工作表的样式

        // 设置第一行的所有单元格字体加粗
        $activeWorksheet->getStyle('1:1')->getFont()->setBold(true);

        // 冻结
        foreach ($freezePane_list as $freezePane) {
            $activeWorksheet->freezePane($freezePane);
        }

        // 设置列背景颜色
        foreach ($column_bg_color_list as $column => $bg_color) {
            // 设置列的背景颜色，并保留边框
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,  // 设置边框样式
                        'color' => ['argb' => 'FF000000'],     // 边框颜色为黑色
                    ],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => $bg_color,
                    ],
                ],
            ];
            $activeWorksheet->getStyle($column)->applyFromArray($styleArray);
        }

        // 设置字体颜色
        foreach ($cell_color_list as $cell => $cell_color) {
            // 检查颜色值是否为 ARGB 格式，如果传递的是 RGB（6位），需要加上 FF 前缀。
            if (strlen($cell_color) === 6) {
                $cell_color = 'FF' . strtoupper($cell_color); // 如果是 RGB，自动转换为 ARGB
            }
            // 获取单元格的现有样式
            $cellStyle = $activeWorksheet->getStyle($cell);
            // 设置字体颜色，但保留其他现有的样式
            $font = $cellStyle->getFont();
            $font->getColor()->setARGB($cell_color);
        }

        // 获取列名清单
        $count_column = count($headers);
        $column_list = self::generateExcelColumnNames($count_column);

        $rowLine = 1;
        $data = array_merge([$headers], $data);
        try {
            // 设置列样式
            foreach ($column_list as $column_name) {
                // 设置 列居中
                if (in_array($column_name, $column_center_list)) {
                    $activeWorksheet->getStyle("$column_name:$column_name")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
                // 设置 列自适应宽度
                if (in_array($column_name, $column_auto_size_list)) {
                    $activeWorksheet->getColumnDimension($column_name)->setAutoSize(true);
                }
            }

            foreach ($data as $rowData) {
                foreach ($column_list as $column_index => $column_name) {
                    $cellName = $column_name . $rowLine;
                    $activeWorksheet->setCellValue($cellName, $rowData[$column_index] ?? '');
                }
                $rowLine++;
            }

            $file_path = buildFilePath($save_path, $fileName, false);
            $full_file_path = buildFilePath($save_path, $fileName);

            // 获取目录名
            $full_dir_path = dirname($full_file_path);
            if (!is_dir($full_dir_path)) {
                mkdir($full_dir_path, 0777, true);
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($full_file_path);
        } catch (Exception $e) {
            throw_exception($e->getMessage());
        }

        return compact('fileName', 'file_path');
    }
}