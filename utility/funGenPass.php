<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 14/08/14
 * Time: 18:31
 */
function genPass() {
    // длина пароля
    $number = 7;
// символы, из которых должен состоять предложенный пароль
    $arr = array( 'a','b','c','d','e','f',
        'g','h','i','j','k','l',
        'm','n','o','p','q','r',
        's','t','u','v','x','w',
        'z','1','2','3','4','5',
        '6','7','8','9','0');
    $pass = "";
    for ($i = 0; $i < $number; $i++) { // проходим в цикле от 0 - 7
        $index = rand(0, count($arr) -1); // берем случайный символ из массива
        $pass .= $arr[$index]; // добавляем символ в строку $pass
    }
    return $pass;
}
?>