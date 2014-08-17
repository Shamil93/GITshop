<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 17/08/14
 * Time: 19:45
 */
/**
 * Обработчик для уменьшения количества товара в корзине
 * с перерасчетом ее стоимости
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once ('DB.php');
    require_once ('../utility/handleData.php');
    require_once ('../utility/getIP.php');

    if (isset($_POST['id'])) {
        $id = handleData($_POST['id']);
    }
    $ip = getIP();
    $sth = DB::getStatement('SELECT * FROM cart WHERE cart_id = ? AND cart_ip = ?');
    $sth->execute(array($id, $ip));
    $rows = $sth->fetchAll();
//    echo "<tt><pre>".print_r($id, true)."</pre></tt>";
    if (!empty($rows)) {
        foreach ($rows as $row) {
            $newCount = $row['cart_count'] - 1;

            if ($newCount > 0) {
                $sth2 = DB::getStatement('UPDATE cart SET cart_count = ? WHERE cart_id = ? AND cart_ip = ?');
                $sth2->execute(array($newCount, $id, $ip));
                echo $newCount;
            } else {
                echo $row['cart_count'];
            }
        }
    }
}
?>