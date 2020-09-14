<?php

namespace Classes;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductController extends BaseController
{
    private $file_path = '';

    public function __construct($file_path)
    {
        $this->file_path = $file_path;
    }

    public function init()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $product = new ProductModel();
        $products = $product->all(0, 50000);

        $table_header = [];

        $row = 1;
        // Loop Data Products
        foreach ($products as $index => $info) {
            $row += 1;
            $column = 0;
            // Loop Row Product Value
            foreach ($info as $key => $value) {
                // Generate Header Column to first row
                $table_header[$key] = '';
                $column_name = Coordinate::stringFromColumnIndex($column += 1);
                $sheet->setCellValue($column_name . ($row), $value . '');
            }

            // Generate Header Column to first row
            $table_header['Группа товара'] = '';
            // Loop Row Groups
            if ($group = $product->group($info['Id Товара'])) {
                $column_name = Coordinate::stringFromColumnIndex($column += 1);
                $sheet->setCellValue($column_name . ($row), $group . '');
            } else {
                $column += 1;
            }

            // Generate Header Column to first row
            $table_header['В категориях'] = '';
            // Loop Row Categories
            if ($categories = $product->categories($info['Id Товара'])) {
                $column_name = Coordinate::stringFromColumnIndex($column += 1);
                $sheet->setCellValue($column_name . ($row), $categories . '');
            } else {
                $column += 1;
            }

            // Generate Header Column to first row
            $table_header['Опции товара'] = '';
            // Loop Row Options
            if ($options = $product->options($info['Id Товара'])) {
                $column_name = Coordinate::stringFromColumnIndex($column += 1);
                $sheet->setCellValue($column_name . ($row), $options . '');
            } else {
                $column += 1;
            }

            // Loop Row Attributes
            if ($attributes = $product->attributes($info['Id Товара'])) {
                foreach ($attributes as $index => $attribute) {
                    foreach ($attribute as $key => $value) {
                        // Generate Header Column to first row
                        $table_header[$key . '(' . $index . ')'] = '';
                        $column_name = Coordinate::stringFromColumnIndex($column += 1);
                        $sheet->setCellValue($column_name . ($row), $value . '');
                    }
                }
            }
        }

        $column = 0;
        // Loop Headers Column to first loop
        foreach ($table_header as $key => $value) {
            $column_name = Coordinate::stringFromColumnIndex($column += 1);
            $sheet->setCellValue($column_name . 1, $key . '');
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($this->file_path);
    }
}
