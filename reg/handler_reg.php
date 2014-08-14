<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 12/08/14
 * Time: 16:42
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {



    session_start();

    require_once('../include/DB.php');
    require_once('../utility/handleData.php');
    require_once('../utility/getIP.php');

    $error = array();

    $login =  strtolower(handleData($_POST['reg_login']));
    $pass =  strtolower(handleData($_POST['reg_pass']));
    $surname =  handleData($_POST['reg_surname']);

    $name =  handleData($_POST['reg_name']);
    $patronymic =  handleData($_POST['reg_patronymic']);
    $email =  handleData($_POST['reg_email']);

    $phone =  handleData($_POST['reg_phone']);
    $address =  handleData($_POST['reg_address']);

    if (strlen($login) < 5 or strlen($login) > 15) {
        $error[] = "Логин должен быть от 5 до 15 символов";
    } else {
        $sth = DB::getStatement('SELECT login FROM reg_user WHERE login = ?');
        $sth->execute(array($login));
        $row = $sth->fetch();
        if (! empty($row)) {
            $error[] = "Логин занят!";
        }
    }
    if (strlen($pass) < 7  or strlen($pass) > 15) $error[] = "Укажите пароль от 7 до 15!";
    if (strlen($surname) < 3 or strlen($surname) > 20) $error[] = "Укажите фамилию от 3 до 20!";
    if (strlen($name) < 3  or strlen($name) > 15) $error[] = "Укажите имя от 3 до 15!";
    if (strlen($patronymic) < 3  or strlen($patronymic) > 25) $error[] = "Укажите отчество от 3 до 25!";
    //if (! preg_match('|^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,6}|i', trim($email))); $error[] = "Укажите корректный E-mail!";
    if (! preg_match('|^[-a-z0-9_\.]+\@[-a-z0-9_\.]+\.[a-z]{2,6}$|i', $email))  $error[] = "Укажите корректный E-mail!";
    if (! $phone) $error[] = "Укажите номер телефона!";
    if (! $address) $error[] = "Необходимо указать адрес доставки!";

    if ($_SESSION['img_captcha'] != strtolower($_POST['reg_captcha'])) $error[] = "Не верный код с картинки!";
    unset($_SESSION['img_captcha']);

    if (count($error)) {
        header('Content-type:text/html; charset=utf8');
        echo implode('<br />', $error);
    } else {
        $pass = md5($pass);
        $pass = strrev($pass);
        $pass = '9nm2rv8q'.$pass.'2yo6z';

        $ip = getIP();

        $sth = DB::getStatement('INSERT INTO reg_user(login, pass, surname, name, patronymic, email, phone, address, datetime, ip)
                                VALUES(?,?,?,?,?,?,?,?,?,?)');
        $date = date('Y-m-d H:i:s', time());
        $sth->execute(array($login, $pass, $surname, $name, $patronymic, $email, $phone, $address, $date, $ip));
    echo 'true';
    }
}
?>