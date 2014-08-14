<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 14/08/14
 * Time: 19:41
 */

/**
 * Выход из учетной записи
 */
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    unset($_SESSION['auth']);
    setcookie('rememberme','',0,'/');
    echo 'logout';
}
?>