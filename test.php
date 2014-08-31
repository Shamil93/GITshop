<?php
require_once('include/DB.php');

function paymentAmountCorrect() {

    $amount = 0.00;

    for( $i=1; $i <= 2; $i++ ) {
        $sth = DB::getStatement( 'SELECT table_products.price
                                FROM table_products, buy_products
                                WHERE buy_products.buy_id_order=?
                                AND table_products.products_id = buy_products.buy_id_product' );

        $sth->execute( array( intval( 14 ) ) );
        if( $sth ) {
            $item_price = $sth->fetch();
            echo "<tt><pre>".print_r($item_price, true)."</pre></tt>";
            $amount += $item_price['price'] * 1;
        }
    }
            echo "<tt><pre>".print_r($amount, true)."</pre></tt>";
    if( ( $amount + 1 ) == 1 ) {
        return true;
    } else {
        return false;
    }
}


//paymentAmountCorrect();
echo 33980.00 == 33980;
?>