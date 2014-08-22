<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 19:28
 */
define('ecommerceShop', true);

try {
    require_once( "ecommerce/controller/Controller.php" );
    require_once( "ecommerce/base/Exceptions.php" );


    ecommerce\Controller\controller::run();


//    if( ! isset( $_SESSION['cart_ecommerce'] ) ) {
//        $_SESSION['cart_ecommerce'] = array();
//        $_SESSION['total_items_ecommerce'] = 0;
//        $_SESSION['total_price_ecommerce'] = '0.00';
//    }

} catch ( \ecommerce\base\AppException $ex ) {
   echo $ex->getErrorObject();
} catch ( \ecommerce\base\DBException $ex ) {
    echo $ex->getMessage();
} catch ( \PDOException $ex ) {
    echo $ex->getMessage() . " AND " . $ex->getCode();
}
?>