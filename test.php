<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 10/08/14
 * Time: 17:48
 */
define('myeshop', true);
require_once('include/Exceptions.php');
include "include/DB.php";
require_once('utility/handleData.php');
require_once('utility/pager.php');
include ('utility/groupPrice.php');
session_start();
include "include/auth-cookie.php";

try {


    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Shop</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="TrackBar/jQuery/trackbar.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="js/jcarouserllite_1.0.1.js"></script>
        <script type="text/javascript" src="js/jquery.cookie.js"></script>
        <script type="text/javascript" src="TrackBar/jQuery/jquery.trackbar.js"></script>
        <script type="text/javascript" src="js/jquery.TextChange.js"></script>
        <script type="text/javascript" src="js/shop-script.js"></script>
    </head>
    <body>

    <div id="block-body">

    <!--    подключаем блок block-header-->
    <?php include('include/block-header.php'); ?>

    <div id="block-right">


    </div>

    <div id="block-content">

        <div id="order-success" style="height: 200px; widht: 300px; margin: 5px auto; text-align: center;">
            <p>Ваш заказ принят!</p>
            <p>Через несколько минут наши специалисты свяжутся с вами по указанному номеру телефона</p>
            <?php echo "<tt><pre>".print_r($_SESSION, true)."</pre></tt>"; ?>
        </div>
    </div><!-- end block-content -->

    <?php include('include/block-footer.php');


    include ('utility/getIP.php');


    $paypal_email = "zhalninpal-facilitator@me.com";
    $paypal_currency = 'RUB';
    //$shipping = 10.00;
    $shipping = 0;
    $ip = getIP();

    echo $ip;

    ?>

    </div>

    </body>
    </html>
<?php
} catch(PDOException $ex) {
    throw new Exceptions($ex);
}
?>