<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 31/08/14
 * Time: 20:38
 */

define('myeshop', true);

require_once('../include/DB.php');
require_once('../utility/handleData.php');
include ('../utility/getIP.php');


$paypal_email = "zhalninpal-facilitator@me.com";
$paypal_currency = 'RUB';
//$shipping = 10.00;
$shipping = 0;
$ip = getIP();

function noPaypalTransId( $trans_id ) {
    $sth = DB::getStatement( 'SELECT order_id FROM orders WHERE nomer_zakaza = ?' );

    $sth->execute( array( $trans_id ) );
    $num_result = $sth->fetch();
    if( $num_result == 0 ) {
//        file_put_contents('payment6.txt',"noPaypalTransId - true!"."\n",FILE_APPEND );
        return true;
    }
//    file_put_contents('payment6.txt',"noPaypalTransId - false!"."\n",FILE_APPEND );
    return false;
}


function paymentAmountCorrect( $shipping, $params ) {

    $amount = 0.00;

    for( $i=1; $i <= $params['num_cart_items']; $i++ ) {
        $sth = DB::getStatement('SELECT table_products.price
                                FROM table_products, buy_products
                                WHERE buy_products.buy_id_order=?
                                AND table_products.products_id = buy_products.buy_id_product');

        $sth->execute( array( intval( $params["item_number{$i}"] ) ) );

        if( $sth ) {
            $item_price = $sth->fetch();
//            file_put_contents('payment9.txt',$item_price."\n",FILE_APPEND );
            $amount += $item_price['price'] * $params["quantity{$i}"];
        }
    }
    file_put_contents('payment8.txt',$amount."\n",FILE_APPEND );
    file_put_contents('payment7.txt',$params['mc_gross']."\n",FILE_APPEND );
    if( (round($amount / 100 * 4.02 + 10) + $amount) == $params['mc_gross'] ) {
//        file_put_contents('payment5.txt',"paymentAmountCorrect - true!"."\n",FILE_APPEND );
        return true;
    } else {
//        file_put_contents('payment5.txt',"paymentAmountCorrect - false!"."\n",FILE_APPEND );
        return false;
    }
}



//foreach ( $_POST as $key => $val ) {
//
//    file_put_contents('payment1.txt',"$key => $val"."\n",FILE_APPEND );
//}

$postdata="";
foreach ($_POST as $key=>$value) $postdata.=$key."=".urlencode($value)."&";
$postdata .= "cmd=_notify-validate";
$curl = curl_init("https://www.sandbox.paypal.com/cgi-bin/webscr");
curl_setopt ($curl, CURLOPT_HEADER, 0);
curl_setopt ($curl, CURLOPT_POST, 1);
curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata);
curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 1);
$response = curl_exec ($curl);

//file_put_contents('payment2.txt',"$response"."\n",FILE_APPEND );

curl_close ($curl);
if ($response != "VERIFIED") die("You should not do that ...");
//foreach ( $_POST as $key => $val ) {
//
//    file_put_contents('payment3.txt',"$key => $val"."\n",FILE_APPEND );
//}

$sth = DB::getStatement('DELETE FROM cart WHERE cart.cart_ip = ?');
$sth->execute(array($ip));

if( $_POST['payment_status'] == 'Completed'
    && noPaypalTransId( $_POST['txn_id'] )
    && $paypal_email == $_POST['receiver_email']
    && $paypal_currency == $_POST['mc_currency']
    && paymentAmountCorrect( $shipping, $_POST )
) {
//    createOrder( $_POST );
//    if ($_POST['payment_status'] == 'Completed') { $status_pay = 'accepted'; }
//    else { $status_pay = $_POST['payment_status']; }
//    $id_order = $_POST['item_number'];
//    $order_type_pay = 'PayPal';
//    $nomer_zakaza = $_POST['txn_id'];

    for( $i=1; $i <= $_POST['num_cart_items']; $i++ ) {
        if ($_POST['payment_status'] == 'Completed') { $status_pay = 'accepted'; }
        else { $status_pay = $_POST['payment_status']; }
        $order_type_pay = 'PayPal';
        $nomer_zakaza = $_POST['txn_id'];
        $sth_update = DB::getStatement("UPDATE orders SET order_pay=?, order_type_pay=?, nomer_zakaza=? WHERE order_id=? ");
        $sth_update->execute(array($status_pay, $order_type_pay, $nomer_zakaza, $_POST["item_number{$i}"] ));

    }
}

//file_put_contents('payment4.txt',"$Заказ успешно оплачен!"."\n",FILE_APPEND );
//echo 'WMI_RESULT=OK&WMI_DESCRIPTION=Заказ успешно оплачен!';

?>