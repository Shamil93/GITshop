<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 10/08/14
 * Time: 18:00
 */

/**
 * Обрабатываем входящие данные
 * @param $str
 * @return string
 */
function handleData( $str ) {
    if (! empty($str)) {
        $str = trim( $str );
        $str = strip_tags( $str );
        $str = htmlspecialchars( stripslashes( $str ) );
        $str = htmlentities( $str );
        return $str;
    }
}
?>
