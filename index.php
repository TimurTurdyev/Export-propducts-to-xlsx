<?php
require './../config.php';
$product = array(
    array('name' => 'Товары', 'link' => HTTPS_SERVER . 'export_1c/files/prime-wood.products.xlsx?time=' . date('Y-m-d-H')),
    array('name' => 'Опции товаров', 'link' => HTTPS_SERVER . 'export_1c/files/prime-wood.options.xlsx?time=' . date('Y-m-d-H')),
    array('name' => 'Картинки товаров', 'link' => HTTPS_SERVER . 'export_1c/files/prime-wood.images.xlsx?time=' . date('Y-m-d-H'))
);

 ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export 1C</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
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
        <?php foreach ($product as $index => $value) { ?>
            <tr>
                <th scope="row"><?php echo $index + 1; ?></th>
                <td><?php echo $value['name']; ?></td>
                <td><a href="<?php echo $value['link']; ?>">Файл</a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
