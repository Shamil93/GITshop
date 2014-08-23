<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/08/14
 * Time: 10:37
 */

defined('myeshop') or die();
$error_img = array();
if ($_FILES['upload_image']['error'] > 0) {
    switch ($_FILES['upload_image']['error']){
        case 1:
            $error_img[] = 'Размер файла превышает допустимое значение UPLOAD_MAX_FILE_SIZE';
            break;
        case 2:
            $error_img[] = 'Размер файла превышает допустимое значение MAX_FILE_SIZE';
            break;
        case 3:
            $error_img[] = 'Не удалось загрузить часть файла';
            break;
        case 4:
            $error_img[] = 'Файл не был загружен';
            break;
        case 6:
            $error_img[] = 'Отсутствует временная папка';
            break;
        case 7:
            $error_img[] = 'Не удалось записать файл на диск';
            break;
        case 8:
            $error_img[] = 'PHP-расширение остановило загрузку файла';
            break;
    }
} else {
    if ($_FILES['upload_image']['type'] == 'image/jpeg' || $_FILES['upload_image']['type'] == 'image/jpg' || $_FILES['upload_image']['type'] == 'image/png' ) {
        // расширение
        $imgtext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $_FILES['upload_image']['name']));
//        $path_parts = pathinfo( $name );  // получаем массив с метаданными изображения
//        $ext = ".".$path_parts['extension'];  // получаем точку с расширением, к примеру: ".png"

        // директория для загрузки
        $uploaddir = '../uploads_images/';
        // делаем имя файла
        $newfilename = $_POST['form_type'].'-'.$id.rand(10,100).'.'.$imgtext;
        // путь к файлу
        $uploadfile = $uploaddir.$newfilename;
//        echo "<tt><pre> - djflskdjf - ".print_r($uploadfile, true). "</pre></tt>";
        if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $uploadfile)) {
            $sth_update = DB::getStatement('UPDATE table_products SET image=? WHERE products_id=?');
//            echo "<tt><pre> - djflskdjf - ".print_r($sth, true). "</pre></tt>";
            $sth_update->execute(array($newfilename, $id));
        } else {
            $error_img[] = "Ошибка загрузки файла";
        }
    } else {
        $error_img[] = 'Допустимые расширения: jpeg, jpg, png';
    }
}
?>