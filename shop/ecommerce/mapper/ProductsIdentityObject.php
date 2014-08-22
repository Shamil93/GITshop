<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/08/14
 * Time: 20:45
 */

namespace ecommerce\mapper;
error_reporting( E_ALL & ~E_NOTICE );


class ProductsIdentityObject  extends IdentityObject {

    /**
     * @param null $field - имя поля для создания условного оператора
     * В родительский класс передаем само поле и массив ($enforce)
     */
    function __construct($field=null) {
        parent::__construct($field, array(  'products_id',
                                            'title',
                                            'price',
                                            'brand',
                                            'seo_words',
                                            'seo_description',
                                            'mini_description',
                                            'image',
                                            'description',
                                            'mini_features',
                                            'features',
                                            'datetime',
                                            'new',
                                            'leader',
                                            'sale',
                                            'visible',
                                            'count',
                                            'type_tovara',
                                            'brand_id',
                                            'vote',
                                            'yes_like')
        );
    }
}
?>