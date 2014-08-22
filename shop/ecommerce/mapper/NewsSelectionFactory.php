<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/08/14
 * Time: 15:43
 */

namespace ecommerce\mapper;
error_reporting( E_ALL & ~E_NOTICE );
require_once( "ecommerce/mapper/SelectionFactory.php" );

class NewsSelectionFactory extends SelectionFactory {

    /**
     * Принимает объект с условными операторами
     * @param IdentityObject $obj
     * @return array
     */
    function newSelection( IdentityObject $obj ) {
        $fields = implode( ',', $obj->getObjectFields() ); // разбиваем искомые поля в Select id,name, и т.д. в классе IdentityObject
        $core = "SELECT $fields FROM news";  // составляем запрос
        $orderby = " ORDER BY id DESC";
        list( $where, $values ) = $this->buildWhere( $obj ); // из родительского класса
        return array( $core." ".$where. " " . $orderby, $values ); // возвращаем запрос с условными операторами WHERE ... < ? AND id = ? и массив с значениями
    }
}
?>