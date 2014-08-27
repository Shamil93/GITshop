<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/08/14
 * Time: 19:13
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    define('myeshop', true);
    require_once('../include/DB.php');
    $sth_delete = DB::getStatement('DELETE FROM category WHERE id=?');
    $sth_delete->execute(array($_POST['id']));
    echo 'delete';
}
?>