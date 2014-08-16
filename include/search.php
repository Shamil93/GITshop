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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('DB.php');
    include('../utility/handleData.php');

    $search = strtolower(handleData($_POST['text']));
    $sth = DB::getStatement("SELECT * FROM table_products WHERE title LIKE '%".$search."%' AND visible = ? LIMIT 10");
    $sth->execute(array(1));
    $rows = $sth->fetchAll();
    if (!empty($rows)){
        foreach ($rows as $row):
            echo '<li><a href="search.php?q='.$row['title'].'">'.$row['title'].'</a></li>';
        endforeach;
    }
}
?>