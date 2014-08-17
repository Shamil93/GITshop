<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 17/08/14
 * Time: 20:35
 */
/**
 * Обработчик для обновления итоговой стоимости
 * товаров в корзине после любых изменений
 * количества
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once ('DB.php');
    require_once ('../utility/getIP.php');
    require_once ('../utility/groupPrice.php');

    $ip = getIP();
    $sth = DB::getStatement('SELECT * FROM cart WHERE cart_ip = ?');
    $sth->execute(array($ip));
    $rows = $sth->fetchAll();
//    echo "<tt><pre>".print_r($_POST['count'], true)."</pre></tt>";
    if (!empty($rows)) {
        foreach ($rows as $row) {
            $int = $int + ($row['cart_price'] * $row['cart_count']);
        }
        echo groupPrice($int);
    }
}
?>