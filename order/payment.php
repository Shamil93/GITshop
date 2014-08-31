<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 31/08/14
 * Time: 15:32
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    define('myeshop', true);
    require_once('../include/DB.php');
    require_once('../utility/handleData.php');

    $id_order = handleData($_POST['WMI_PAYMENT_NO']);
    $status_pay = strtolower(handleData($_POST['WMI_ORDER_STATE']));
    $order_type_pay = handleData($_POST['WMI_PAYMENT_TYPE']);
    $nomer_zakaza = handleData($_POST['WMI_ORDER_ID']);

    $sth_update = DB::getStatement("UPDATE orders SET order_pay=?, order_type_pay=?, nomer_zakaza=? WHERE order_id=? ");
    $sth_update->execute(array($status_pay, $order_type_pay, $nomer_zakaza, $id_order ));

    echo 'WMI_RESULT=OK&WMI_DESCRIPTION=Заказ успешно оплачен!';
}
?>