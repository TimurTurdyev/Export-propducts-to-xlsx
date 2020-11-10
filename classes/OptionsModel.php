<?php

namespace Classes;

use Services\DB;

class OptionsModel extends Model
{
    public function all($offset = 0, $limit = 5000)
    {
        $SQL = "SELECT 
                        p.product_id as 'Id Товара', p.sku AS 'Артикул', p.base_price AS 'Базовая цена', p.base_currency_code AS 'Валюта',
                        pd.name AS 'Наименование товара'
                    FROM oc_product p
                    JOIN oc_product_description pd 
                        ON p.product_id = pd.product_id AND pd.language_id = 1
                    WHERE p.product_id = pd.product_id 
                        AND p.isbn = 'product'
                        AND p.status = 1
                    ORDER BY p.product_id LIMIT {$offset}, {$limit}";

        $products = DB::query($SQL);

        return $products->rows;
    }

    public function getOptions($id)
    {
        $options = DB::query("SELECT 
                                        od.option_id AS 'ID Группы',
                                        od.name AS 'Название Гуппы Опции',
                                        ovd.option_value_id AS 'ID Опции',
                                        ovd.name AS 'Название Опции',
                                        ov.image AS 'Картинка Опции',
                                        pov.sku AS 'Артикул Опции',
                                        pov.price AS 'Цена Опции',
                                        pov.base_price AS 'Базовая Цена Опции'
                                    FROM oc_product_option_value pov
                                    LEFT JOIN oc_option_description od 
                                        ON pov.option_id = od.option_id
                                    LEFT JOIN oc_option_value ov
                                        ON pov.option_value_id = ov.option_value_id
                                    LEFT JOIN oc_option_value_description ovd
                                        ON pov.option_value_id = ovd.option_value_id
                                    WHERE pov.product_id = '{$id}'
                                    ORDER BY pov.option_id ASC, pov.option_value_id ASC");
        return $options->rows;
    }
}
