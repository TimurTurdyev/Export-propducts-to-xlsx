<?php
require './../config.php';
$file_path = HTTPS_SERVER . 'export_1c/files/prime-wood.products.xlsx';
/*
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');


$writer = new Xlsx($spreadsheet);
$writer->save($file_path);

header("Content-Type: text/html");
*/?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export 1C</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Действие</th>
                <th scope="col">Значение</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">1</th>
                <td>Скопировать</td>
                <td><input type="text" value="<?php echo $file_path; ?>" class="form-control input-lg"></td>
            </tr>
            <tr>
                <th scope="row">2</th>
                <td>Скачать</td>
                <td><a href="<?php echo $file_path; ?>">Файл</a></td>
            </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
