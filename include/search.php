<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/08/14
 * Time: 00:25
 */

/**
 * Обработчик появления подсказок при вводе текста в строку для поиска по сайту
 */
//header('Content-Type: txt/html; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    define('myeshop', true);
    include('DB.php');
    include('../utility/handleData.php');

    $search = strtolower(handleData($_POST['text']));
//    $search = strtolower($_POST['text']);
    $sth_limit = DB::getStatement("SELECT * FROM table_products WHERE title LIKE '%".$search."%' AND visible = ? LIMIT 10");
    $sth_limit->execute(array(1));
    $rows = $sth_limit->fetchAll();
    if (!empty($rows)){
        foreach ($rows as $row):
            echo '<li><a href="search.php?q='.$row['title'].'">'.$row['title'].'</a></li>';
        endforeach;
    }
}
?>