<?php

namespace Classes;

use Services\DB;

class ImagesModel extends Model
{
    public function all($offset = 0, $limit = 5000)
    {
        $SQL = "SELECT 
                        p.product_id as 'Id Товара', p.sku AS 'Артикул',
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
    public function getImages($id) {
        $images = DB::query("SELECT 
                                        pi.image AS 'Картирка товара', 
                                        pi.sort_order AS 'Картинка сортировка', 
                                        ovd.option_value_id AS 'Связь ID Опции',
                                        ovd.name AS 'Связь Название Опции'
                                    FROM oc_product_image pi 
                                    LEFT JOIN oc_option_value_description ovd
                                        ON pi.option_value_id = ovd.option_value_id 
                                    WHERE pi.product_id = {$id} ORDER BY pi.sort_order ASC");
        return $images->rows;
    }
}
