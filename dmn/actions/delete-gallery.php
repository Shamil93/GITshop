<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/08/14
 * Time: 23:13
 */
/**
 * Обработчик удаления изображения upload-gallery из edit_product.php
 */
if( $_SERVER['REQUEST_METHOD'] == 'POST') {
    define('myeshop', true);
    require_once ('../include/DB.php');

    $path = $_SERVER['DOCUMENT_ROOT']."/uploads_images".$_POST['title'];
    if(file_exists($path)) {
        unlink($path);
        $sth_delete = DB::getStatement('DELETE FROM uploads_images WHERE id=?');
        $sth_delete->execute(array($_POST['id']));
        echo 'delete';
    } else {
        echo 'delete';
        $sth_delete = DB::getStatement('DELETE FROM uploads_images WHERE id=?');
        $sth_delete->execute(array($_POST['id']));
    }
}
?>