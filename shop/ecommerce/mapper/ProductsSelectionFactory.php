<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/08/14
 * Time: 20:57
 */

namespace ecommerce\mapper;
error_reporting( E_ALL & ~E_NOTICE );
require_once( "ecommerce/mapper/SelectionFactory.php" );

class ProductsSelectionFactory extends SelectionFactory {

    /**
     * Принимает объект с условными операторами
     * @param IdentityObject $obj
     * @return array
     */
    function newSelection(IdentityObject $obj) {
        $fields = implode(',', $obj->getObjectFields()); // разбиваем искомые поля в Select id,name, и т.д. в классе IdentityObject
        $core = "SELECT $fields FROM table_products";  // составляем запрос
        $orderby = "";
        list( $where, $values ) = $this->buildWhere( $obj ); // из родительского класса
//        echo "<tt><pre>".print_r($core." ".$where." ".$orderby, true). "</pre></tt>";
//        echo "<tt><pre>".print_r($values, true). "</pre></tt>";
        return array( $core." ".$where. " " . $orderby, $values ); // возвращаем запрос с условными операторами WHERE ... < ? AND id = ? и массив с значениями
    }
}
?>