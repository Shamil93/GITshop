<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/08/14
 * Time: 18:44
 */
require_once('include/Exceptions.php');
require_once('utility/pager.php');
include "include/DB.php";
include ('utility/handleData.php');
session_start();
include "include/auth-cookie.php";

//unset($_SESSION['auth']);
//setcookie('rememberme','',0,'/');
try {
?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Корзина заказов</title>
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
            <?php include('include/block-category.php'); ?>
            <?php include('include/block-parameter.php'); ?>
            <?php include('include/block-news.php'); ?>
        </div>

        <div id="block-content">

            <?php

            $action = handleData($_GET['action']);
            switch ($action) {
                case 'oneclick':
                    echo '<div id="block-step">
                        <div id="name-step">
                            <ul>
                            <li><a class="active" href="" >1. Корзина товаров</a></li>
                            <li><span>&rarr;</span></li>
                            <li><a href="" >2. Контактная информация</a></li>
                            <li><span>&rarr;</span></li>
                            <li><a href="" >3. Завершение</a></li>
                            </ul>
                        </div>
                        <p>Шаг 1 из 3</p>
                        <a href="cart.php?action=clear">Очистить</a>
                    </div>';
                    break;
                case 'confirm':
                    echo '<div id="block-step">
                        <div id="name-step">
                            <ul>
                            <li><a href="" >1. Корзина товаров</a></li>
                            <li><span>&rarr;</span></li>
                            <li><a class="active" href="" >2. Контактная информация</a></li>
                            <li><span>&rarr;</span></li>
                            <li><a href="" >3. Завершение</a></li>
                            </ul>
                        </div>
                        <p>Шаг 2 из 3</p>
                        <a href="cart.php?action=clear">Очистить</a>
                    </div>';
                    break;
                case 'completion':
                    echo '<div id="block-step">
                        <div id="name-step">
                            <ul>
                            <li><a href="" >1. Корзина товаров</a></li>
                            <li><span>&rarr;</span></li>
                            <li><a href="" >2. Контактная информация</a></li>
                            <li><span>&rarr;</span></li>
                            <li><a class="active" href="" >3. Завершение</a></li>
                            </ul>
                        </div>
                        <p>Шаг 3 из 3</p>
                        <a href="cart.php?action=clear">Очистить</a>
                    </div>';
                    break;
                default:

                    break;
            }
            ?>

        </div><!-- end block-content -->


        <?php include('include/block-footer.php'); ?>

    </div>

    </body>
    </html>
<?php
} catch(PDOException $ex) {
    throw new Exceptions($ex);
}
?>