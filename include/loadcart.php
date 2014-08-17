<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 17/08/14
 * Time: 16:15
 */
/**
 * Обработчик для обновления стоимости и количества товаров
 * выводит результат рядом с иконкой корзины
 */
require_once ('DB.php');
require_once ('../utility/getIP.php');
require_once ('../utility/groupPrice.php');
require_once ('../utility/inclineItems.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ip = getIP();
    $itemArr = array('товар', 'товара', 'товаров');
    $sth = DB::getStatement('SELECT * FROM cart, table_products WHERE cart.cart_ip = ? and table_products.products_id = cart.cart_id_product');
    $sth->execute(array($ip));
    $rows = $sth->fetchAll();
    if (! empty($rows)) {
        $count = $int = '';
        foreach ($rows as $row) {
            $count = $count + $row['cart_count'];
            $int = $int + ($row['price'] * $row['cart_count']);
        }

//        echo "<tt><pre>".print_r(sklonenie($count, $forms), true)."</pre></tt>";
        $str = inclineItems($count, $itemArr );
        echo '<span>'.$count.' '.$str.'</span> на сумму <span>'.groupPrice($int).'</span> руб';

    } else {
        echo '0';
    }
}
?>