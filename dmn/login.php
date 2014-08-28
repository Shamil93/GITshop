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

    $sth = DB::getStatement('SELECT * FROM reg_admin WHERE login = ? AND pass = ?');
    $sth->execute(array($login, $pass));
    $row = $sth->fetch();
//    echo "<tt><pre>".print_r($row)."</pre></tt>";
    if (! empty($row)) {
        $_SESSION['auth_admin'] = 'yes_auth';
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