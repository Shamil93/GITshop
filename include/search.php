<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/08/14
 * Time: 00:25
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('DB.php');
    include('../utility/handleData.php');

    $search = strtolower(handleData($_POST['text']));
    $searchLike =  "LIKE '%$search%'";
    $sth = DB::getStatement('SELECT * FROM table_products WHERE title ? AND visible = ?');
    $sth->execute(array( $searchLike, 1));
    $rows = $sth->fetchAll();
    if (!empty($rows)){
        $sth2 = DB::getStatement('SELECT * FROM table_products WHERE title ? AND visible = ? LIMIT 10');
        $sth2->execute(array($searchLike, 1));
        $rows2 = $sth2->fetchAll();
        foreach ($rows2 as $row):
            echo '<li><a href="search.php?q='.$row['title'].'">'.$row['title'].'</a></li>';
        endforeach;
    }
}
?>