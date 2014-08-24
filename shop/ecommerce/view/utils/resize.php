<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 24/08/14
 * Time: 21:37
 */

/**
 * Для изменения размера изоражения
 * @param $img - имя файла
 * @param $path - путь до файла
 * @param $width_s - желаемая ширина
 * @param $height_s - желаемая высота
 * @return array
 */
function resize( $img, $path, $width_s, $height_s ) {
        //                        echo "<tt><pre>".print_r($img, true). "</pre></tt>";
        $img_path   = $path.$img; // путь до фото
        $max_width  = $width_s; // ширина
        $max_height = $height_s; // высота
        list($width, $height) = getimagesize($img_path); // получаем текущие размеры
        $ratioh = $max_height / $height; // коэффициент высоты
        $ratiow = $max_width / $width; // коэффициент ширины
        $ratio  = min($ratioh, $ratiow); // берем самое маленькое
        $width  = intval($ratio * $width); // получаем ширину
        $height = intval($ratio * $height); // получаем высоту
    return array($width,$height);
}
?>