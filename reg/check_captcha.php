<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 12/08/14
 * Time: 20:42
 */

/**
 * Проверка правильности введенной капчи из формы регистрации
 * посредством ajax
 */
if($_SERVER["REQUEST_METHOD"] == "POST") {
     session_start();
     if($_SESSION['img_captcha'] == strtolower($_POST['reg_captcha'])) {
         echo 'true';
     } else {
         echo 'false';
     }
 }

?>