<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/08/14
 * Time: 15:29
 */

namespace ecommerce\mapper;
error_reporting( E_ALL & ~E_NOTICE );

class NewsIdentityObject  extends IdentityObject {

    /**
     * @param null $field - имя поля для создания условного оператора
     * В родительский класс передаем само поле и массив ($enforce)
     */
    function __construct($field=null) {
        parent::__construct($field, array('id',
                                            'title',
                                            'text',
                                            'date',
                                            'hide')
        );
    }
}
?>