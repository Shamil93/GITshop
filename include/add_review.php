<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 18/08/14
 * Time: 18:10
 */

/**
 * Обработчик для добавления отзыва на товар
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    define('myeshop', true);
    require_once ('DB.php');
    require_once ('../utility/handleData.php');

    if (isset($_POST['id'])) {
        $id = handleData($_POST['id']);
    }
    if (isset($_POST['name'])) {
        $name = handleData($_POST['name']);
    }
    if (isset($_POST['good'])) {
        $good = handleData($_POST['good']);
    }
    if (isset($_POST['bad'])) {
        $bad = handleData($_POST['bad']);
    }
    if (isset($_POST['comment'])) {
        $comment = handleData($_POST['comment']);
    }

    $date = date('Y-m-d H:i:s', time());

    $sth = DB::getStatement('INSERT INTO table_reviews (products_id, name, good_reviews, bad_reviews, comment, date )
                                VALUES(?,?,?,?,?,?)');
    $sth->execute(array($id, $name, $good, $bad, $comment, $date));

    echo 'yes';
} else {
    echo 'no';
}

?>