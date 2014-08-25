<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/08/14
 * Time: 10:58
 */

defined('myeshop') or die();

if (! empty($_FILES['galleryimg']['name'][0])) {
    for($i=0; $i<count($_FILES['galleryimg']['name']); $i++) {
        $error_gallery = '';

        if ($_FILES['galleryimg']['name'][$i]) {
            $galleryimgType = $_FILES['galleryimg']['type'][$i];
            // типы допустимых изображений
//            $types = array("image/gif","image/png","image/jpeg","image/jpg","image/pjpeg","image/x-png");
            $types = array("gif","png","jpeg","jpg");
            // расширение
            $imgext = strtolower(preg_replace('#.+\.([a-z]+)$#i',"$1", $_FILES['galleryimg']['name'][$i]));
//            $imgext = pathinfo($_FILES['galleryimg']['name'][$i])['extension'];
            // директория для загрузки
            $uploaddir = '../uploads_images/';
            // имя нового файла
            $newfilename = $_POST['form_type'].'-'.rand(100,500).'.'.$imgext;
            // путь к файлу
            $uploadfile = $uploaddir.$newfilename;

//            echo "<tt><pre> - djf2 - ".print_r($types, true). "</pre></tt>";
//            echo "<tt><pre> - djf - ".print_r($imgext, true). "</pre></tt>";

//            if(!in_array($galleryimgType, $types)) {
            if(!in_array($imgext, $types)) {
                $error_gallery = '<p id="form-error">Допустимые расширения - .gif, .jpg, .png</p>';
                $_SESSION['answer'] = $error_gallery;
                continue;
            }

            if (empty($error_gallery)) {
                if (move_uploaded_file($_FILES['galleryimg']['tmp_name'][$i], $uploadfile)) {
//                    echo "<tt><pre> - d - ".print_r("moved", true). "</pre></tt>";
                    $sth_insert = DB::getStatement('INSERT INTO uploads_images(products_id, image)
                                                    VALUES(?, ?)');
                    $sth_insert->execute(array($id, $newfilename));
                } else {
                    $_SESSION['answer'] = "Ошибка загрузки файла!";
                }
            }
        }
    }
}
?>