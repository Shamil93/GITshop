<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 14/08/14
 * Time: 15:22
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include ('DB.php');
    include ('../utility/handleData.php');

    $login = handleData($_POST['login']);
    $pass  = strtolower(handleData($_POST['pass']));
    $pass  = strrev(md5($pass));
    $pass  = '9nm2rv8q'.$pass.'2yo6z';

    if ($_POST['rememberme'] == 'yes') {
        setcookie('rememberme',$login.'+'.$pass, time()+3600*24*31, "/");
    }

    $sth = DB::getStatement('SELECT * FROM reg_user WHERE (login = ? OR email = ?) AND pass = ?');
    $sth->execute(array($login, $login, $pass));
    $rows = $sth->fetch();
    if (! empty($rows)) {
        session_start();
        $_SESSION['auth']           = 'yes_auth';
        $_SESSION['auth_pass']      = $rows['pass'];
        $_SESSION['auth_login']     = $rows['login'];
        $_SESSION['auth_surname']   = $rows['surname'];
        $_SESSION['auth_name']      = $rows['name'];
        $_SESSION['auth_patronymic']= $rows['patronymic'];
        $_SESSION['auth_address']   = $rows['address'];
        $_SESSION['auth_phone']     = $rows['phone'];
        $_SESSION['auth_email']     = $rows['email'];
        echo 'yes_auth';
    } else {
        echo 'no_auth';
    }
}

?>