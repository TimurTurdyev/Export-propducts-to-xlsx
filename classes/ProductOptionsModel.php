<?php

namespace Classes;

use Services\DB;

class ProductModel extends Model
{
    public function all($offset = 0, $limit = 5000)
    {
        $SQL = "SELECT 
                        p.product_id as 'Id Товара', p.sku AS 'Артикул', p.isbn AS 'Сущность', p.quantity AS 'Кол-во', p.image AS 'Картинка', p.price AS 'Цена', p.cost AS 'Закупка', p.base_price AS 'Базовая цена', p.base_currency_code AS 'Валюта', p.length AS 'Ширина', p.width AS 'Глубина', p.height AS 'Высота', p.status AS 'Статус', p.date_added AS 'Дата добавления', p.date_modified AS 'Дата изменения', p.setting AS 'Кастомные настройки', p.manufacturer_id AS 'Id Производитель', 
                        (SELECT `name`
                            FROM oc_manufacturer 
                            WHERE p.manufacturer_id = manufacturer_id LIMIT 0, 1) 
                            AS 'Производитель название',
                        p2c.category_id AS 'Id Категории',
                        (SELECT `name` FROM oc_category_description WHERE p2c.category_id AND language_id = 1 LIMIT 0, 1) AS 'Категория название',
                        CASE 
                            WHEN p.isbn = 'combination'
                            THEN (SELECT GROUP_CONCAT(
                                            item_id
                                            ORDER BY item_id ASC
                                            SEPARATOR ','
                                        ) 
                                    FROM oc_product_combination 
                                    WHERE p.product_id = product_id)
                            WHEN p.isbn = 'product'
                            THEN (SELECT GROUP_CONCAT(
                                            product_id
                                            ORDER BY product_id ASC
                                            SEPARATOR ','
                                        ) 
                                    FROM oc_product_combination 
                                    WHERE p.product_id = item_id)
                            ELSE 'Не указан тип' 
                        END AS 'КОЛЛЕКЦИИ/ТОВАРЫ',
                        pd.name AS 'Наименование товара', pd.meta_title AS 'Мета тайтл', pd.meta_h1 AS 'Заголовок', 
                        pd.meta_description AS 'Мета описание', pd.description AS 'Полное описание', pd.mini_desc AS 'Мини описание', pd.tag AS 'Теги'
                    FROM oc_product p
                    JOIN oc_product_description pd 
                        ON p.product_id = pd.product_id
                    LEFT JOIN oc_product_to_category p2c 
                        ON p.product_id = p2c.product_id    
                    WHERE p.product_id = pd.product_id 
                        AND pd.language_id = 1
                        AND p2c.main_category = 1
                    ORDER BY p.product_id LIMIT {$offset}, {$limit}";

        $products = DB::query($SQL);

        return $products->rows;
    }

    public function group($id)
    {
        $group = DB::query("SELECT g.group_id, g.name
                                        FROM oc_product_group pg
                                        LEFT JOIN oc_group g 
                                            ON pg.group_id = g.group_id
                                        WHERE pg.product_id = '{$id}'
                                        LIMIT 0, 1");
        if ($group->num_rows) {
            return $group->row;
        }
        return '';
    }

    public function categories($id, $main = 'child')
    {
        $SQL = "SELECT 
                GROUP_CONCAT(
                    CONCAT(
                        cd.category_id, 
                        '<=>', cd.name
                        )
                        ORDER BY cd.category_id ASC
                        SEPARATOR '\r\n'
                ) as 'categories'
                FROM oc_product_to_category p2c
                LEFT JOIN oc_category_description cd 
                    ON p2c.category_id = cd.category_id
                WHERE p2c.product_id = '{$id}'";
        if ($main === 'child') {
            $SQL .= " AND p2c.main_category = 0";
        } else if ($main === 'parent') {
            $SQL .= " AND p2c.main_category = 0";
        }

        $categories = DB::query($SQL);

        if ($categories->num_rows) {
            return $categories->row['categories'];
        }
        return '';
    }

    public function options($id)
    {
        $options = DB::query("SELECT 
                                        GROUP_CONCAT(
                                            CONCAT(
                                                'option_id=', od.option_id, 
                                                '|option_name=', od.name,
                                                '|option_value_id=', ovd.option_value_id, 
                                                '|option_value_name=', ovd.name,
                                                '|option_value_image=', ov.image,
                                                '|option_value_sku=', pov.sku, 
                                                '|option_value_price=', pov.price, 
                                                '|option_value_base_price=', pov.base_price
                                                )
                                                ORDER BY od.option_id ASC, ovd.option_value_id ASC
                                                SEPARATOR '\r\n'
                                        ) as 'options'
                                    FROM oc_product_option_value pov
                                    LEFT JOIN oc_option_description od 
                                        ON pov.option_id = od.option_id
                                    LEFT JOIN oc_option_value ov
                                        ON pov.option_value_id = ov.option_value_id
                                    LEFT JOIN oc_option_value_description ovd
                                        ON pov.option_value_id = ovd.option_value_id
                                    WHERE pov.product_id = '{$id}'
                                    ORDER BY pov.option_id ASC, pov.option_value_id ASC");
        if ($options->num_rows) {
            return $options->row['options'];
        }
        return '';
    }

    public function attributes($id)
    {
        $attributes = DB::query("SELECT 
                                        a.attribute_group_id AS 'ID Группа атрибута',
                                        (SELECT `name` 
                                            FROM oc_attribute_group_description 
                                            WHERE attribute_group_id = a.attribute_group_id 
                                                AND language_id  = pa.language_id) AS 'Группа атрибута',  
                                        a.attribute_id AS 'ID Атрибут',
                                        (SELECT `name` 
                                            FROM oc_attribute_description 
                                            WHERE attribute_id = a.attribute_id 
                                                AND language_id  = pa.language_id) AS 'Атрибут',  
                                        pa.text AS 'Атрибут значение в товаре'
                                    FROM oc_product_attribute pa
                                    LEFT JOIN oc_attribute a 
                                        ON pa.attribute_id = a.attribute_id
                                    WHERE pa.product_id = '{$id}'
                                    ORDER BY a.attribute_group_id ASC, pa.attribute_id ASC");
        return $attributes->rows;
    }
}
