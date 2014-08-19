<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 18/08/14
 * Time: 19:23
 */
/**
 * Обработчик обновления счетчика "Нравится"
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    define('myeshop', true);
    session_start();

    if ($_SESSION['likeid'] != (int)$_POST['id']) {
        require_once ('DB.php');

        $id = (int)$_POST['id'];

        $sth = DB::getStatement('SELECT * FROM table_products WHERE products_id = ?');
        $sth->execute(array($id));
        $row = $sth->fetch();
        if (!empty($row)) {
            $newCount = $row['yes_like'] + 1;
            $sthUpdate = DB::getStatement('UPDATE table_products SET yes_like = ? WHERE products_id = ?');
            $sthUpdate->execute(array($newCount, $id));
            echo $newCount;
        }
        $_SESSION['likeid'] = (int)$_POST['id'];
    } else {
        echo 'no';
    }
}
?>