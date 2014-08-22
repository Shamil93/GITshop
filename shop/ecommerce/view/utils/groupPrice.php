<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 17/08/14
 * Time: 12:44
 */
namespace ecommerce\view\utils;
/**
 * Принимаем цену как есть и возвращаем отформатированную строку
 * Пример: 3990
 * Получаем: 3 990
 * @param $value
 * @return mixed
 */
function groupPrice($value) {
    // получаем отформатированную строку - 3,990
    // заменяем запятую пробелом - 3 990
    $price = preg_replace('|\,|',' ', number_format($value));
    return $price; // возвращаем отформатированную строку
}

