<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/08/14
 * Time: 11:45
 */
session_start();
if ($_SESSION['auth_admin'] == 'yes_auth') {

define('myeshop', true);
if (isset( $_GET['logout'])) {
    unset($_SESSION['auth_admin']);
    header ('Location: login.php');
}
$_SESSION['urlpage'] = "<a href='index.php'>Главная</a>";

require_once ('include/DB.php');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Панель управления</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <!--    <link href="TrackBar/jQuery/trackbar.css" rel="stylesheet" type="text/css" />-->
    <!--    <script type="text/javascript" src="js/jquery-2.1.1.js"></script>-->
    <!--    <script type="text/javascript" src="js/jcarouserllite_1.0.1.js"></script>-->
    <!--    <script type="text/javascript" src="js/jquery.cookie.js"></script>-->
    <!--    <script type="text/javascript" src="TrackBar/jQuery/jquery.trackbar.js"></script>-->
    <!--    <script type="text/javascript" src="js/jquery.TextChange.js"></script>-->
    <!--    <script type="text/javascript" src="js/shop-script.js"></script>-->
</head>
<body>
<div id="block-body">
    <?php require_once ('include/block-header.php') ?>
    <div id="block-content">
        <div id="block-parameters">
            <p id="title-page">Общая статистика</p>
        </div>
    </div>
</div>
</body>
</html>
<?php
} else {
    header ('Location: login.php');
}
?>