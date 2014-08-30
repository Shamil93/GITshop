<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/08/14
 * Time: 10:54
 */
session_start();
define('myeshop', true);
require_once ('include/DB.php');
require_once ('utility/handleData.php');

if ($_POST['submit_enter']){
    $login = handleData($_POST['input_login']);
    $pass = handleData($_POST['input_pass']);
}

if ($login && $pass) {

    $pass = md5($pass);
    $pass = strrev($pass);
    $pass = strtolower('mb03foo51'.$pass.'qj2jjdp9');
//$pass = '';

    $sth = DB::getStatement('SELECT * FROM reg_admin WHERE login = ? AND pass = ?');
    $sth->execute(array($login, $pass));
    $row = $sth->fetch();
//    echo "<tt><pre>".print_r($row)."</pre></tt>";
    if (! empty($row)) {
        $_SESSION['auth_admin']      = 'yes_auth';
        $_SESSION['auth_admin_login']      = $row['login'];

        // должность
        $_SESSION['admin_role']      = $row['role'];

        // привилегии на блок заказов
        $_SESSION['accept_orders']   = $row['accept_orders'];
        $_SESSION['delete_orders']   = $row['delete_orders'];
        $_SESSION['view_orders']     = $row['view_orders'];

        // привилегии на блок товаров
        $_SESSION['delete_tovar']    = $row['delete_tovar'];
        $_SESSION['add_tovar']       = $row['add_tovar'];
        $_SESSION['edit_tovar']      = $row['edit_tovar'];

        // привилегии на блок отзывов
        $_SESSION['accept_reviews']  = $row['accept_reviews'];
        $_SESSION['delete_reviews']  = $row['delete_reviews'];

        // привилегии на блок клиентов
        $_SESSION['view_clients']    = $row['view_clients'];
        $_SESSION['delete_clients']  = $row['delete_clients'];


        // привилегии на блок новостей
        $_SESSION['add_news']        = $row['add_news'];
        $_SESSION['delete_news']     = $row['delete_news'];

        // привилегии на блок категорий
        $_SESSION['add_category']    = $row['add_category'];
        $_SESSION['delete_category'] = $row['delete_category'];

        // привилегии на блок администраторов
        $_SESSION['view_admin']      = $row['view_admin'];

//        echo '<tt><pre>'.print_r($row, true).'</pre></tt>';


        header('Location: index.php');
    } else {
        $msgerror = "Неверный Логин и(или) Пароль!";
    }
} else {
    $msgerror = "Заполните все поля!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shop</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/style-login.css" rel="stylesheet" type="text/css" />
<!--    <link href="TrackBar/jQuery/trackbar.css" rel="stylesheet" type="text/css" />-->
<!--    <script type="text/javascript" src="js/jquery-2.1.1.js"></script>-->
<!--    <script type="text/javascript" src="js/jcarouserllite_1.0.1.js"></script>-->
<!--    <script type="text/javascript" src="js/jquery.cookie.js"></script>-->
<!--    <script type="text/javascript" src="TrackBar/jQuery/jquery.trackbar.js"></script>-->
<!--    <script type="text/javascript" src="js/jquery.TextChange.js"></script>-->
<!--    <script type="text/javascript" src="js/shop-script.js"></script>-->
</head>
<body>

<div id="block-pass-login">
    <?php
    if (! empty($msgerror)) {
        echo '<p id="msgerror">'.$msgerror.'</p>';
    }
    ?>
    <form method="POST">
        <ul id="pass-login">
            <li><label>Логин</label><input type="text" name="input_login" /></li>
            <li><label>Пароль</label><input type="password" name="input_pass" /></li>
        </ul>
        <p align="right"><input type="submit" name="submit_enter" id="submit_enter" value="Вход" /></p>
    </form>
</div>
</body>
</html>