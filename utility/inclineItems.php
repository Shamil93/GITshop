<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 17/08/14
 * Time: 19:13
 */
/**
 * Метод для склонения товаров
 * в зависимости от их количества
 * @param $n
 * @param $forms
 * @return mixed
 */
function inclineItems($n, $forms) {
    return $n % 10 == 1 && $n % 100 != 11 ? $forms[0] :
        ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $forms[1] :
            $forms[2]);
}
?>