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
include ('utility/getIP.php');
session_start();
include "include/auth-cookie.php";

if (isset($_GET['id'])) {
    $id = handleData($_GET['id']);
}
if (isset($_GET['action'])) {
    $action = handleData($_GET['action']);
}
$ip = getIP();

switch ($action) {
    case 'clear':
        $sth = DB::getStatement("DELETE FROM cart WHERE cart_ip = ?");
        $sth->execute(array($ip));
        break;
    case 'delete':
        $sth = DB::getStatement("DELETE FROM cart WHERE cart_id = ? AND cart_ip = ?");
        $sth->execute(array($id, $ip));
        break;
}

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
                    // блок навигации
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
                    // заголовок таблицы блока
                    echo '<div id="header-list-cart">
                        <div id="head1">Изображение</div>
                        <div id="head2">Наименование товара</div>
                        <div id="head3">Кол-во</div>
                        <div id="head4">Цена</div>
                    </div>';


                    $sth = DB::getStatement("SELECT * FROM cart, table_products WHERE cart.cart_ip = '{$ip}' AND table_products.products_id = cart.cart_id_product");
                    $sth->execute();
                    $rows = $sth->fetchAll();
                    if (! empty($rows)) {
                        $allPrice = 0;
                        foreach ($rows as $row) {
                            $int = $row['cart_price'] * $row['cart_count'];
                            $allPrice = $allPrice + $int;
                            if (strlen($row['image']) > 0 && file_exists('uploads_images/'.$row['image'])) {
                                $imgPath = 'uploads_images/'.$row['image'];
                                $maxWidth = 100;
                                $maxHeight = 100;
                                list($width, $height) = getimagesize($imgPath);
                                $ratioh = $maxHeight / $height;
                                $ratiow = $maxWidth / $width;
                                $ratio = min($ratioh, $ratiow);

                                $width = intval($ratio * $width);
                                $height = intval($ratio * $height);
                            } else {
                                $imgPath = 'images/noimages.jpeg';
                                $width = 120;
                                $height = 105;
                            }

                            // блок с отображение товара в таблице
                            echo '<div class="block-list-cart">
                                <div class="img-cart">
                                    <p align="center"><img src="'.$imgPath.'" width="'.$width.'" height="'.$height.'" /></p>
                                </div>
                                <div class="title-cart">
                                    <p><a href="" >'.$row["title"].'</a></p>
                                    <p class="cart-mini-features">'.$row["mini_features"].'</p>
                                </div>
                                <div class="count-cart">
                                    <ul class="input-count-style">
                                        <li><p align="center" class="count-minus">-</p></li>
                                        <li><p align="center"><input class="count-input" maxlength="3" type="text" value="'.$row["cart_count"].'" /></p></li>
                                        <li><p align="center" class="count-plus">+</p></li>
                                    </ul>
                                </div>
                                <div class="price-product"><h5><span class="span-count">1</span> x <span>'.$row["cart_price"].'</span></h5><p>'.$int.'</p></div>
                                <div class="delete-cart"><a href="cart.php?id='.$row["cart_id"].'&action=delete" ><img src="images/bsk_item_del.png" /></a></div>
                                <div id="bottom-cart-line"></div>
                            </div>';




                        }
                        echo '<h2 class="itog-price" align="right">Итого: <strong>'.$allPrice.'</strong> руб</h2>
                        <p align="right" class="button-next"><a href="cart.php?action=confirm">Далее</a></p>';
                    } else {
                        echo '<h3 id="clear-cart" align="center">Корзина пуста</h3>';
                    }

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
                    // блок навигации
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
                    // заголовок таблицы блока
                    echo '<div id="header-list-cart">
                        <div id="head1">Изображение</div>
                        <div id="head2">Наименование товара</div>
                        <div id="head3">Кол-во</div>
                        <div id="head4">Цена</div>
                    </div>';


                    $sth = DB::getStatement("SELECT * FROM cart, table_products WHERE cart.cart_ip = '{$ip}' AND table_products.products_id = cart.cart_id_product");
                    $sth->execute();
                    $rows = $sth->fetchAll();
                    if (! empty($rows)) {
                        $allPrice = 0;
                        foreach ($rows as $row) {
                            $int = $row['cart_price'] * $row['cart_count'];
                            $allPrice = $allPrice + $int;
                            if (strlen($row['image']) > 0 && file_exists('uploads_images/'.$row['image'])) {
                                $imgPath = 'uploads_images/'.$row['image'];
                                $maxWidth = 100;
                                $maxHeight = 100;
                                list($width, $height) = getimagesize($imgPath);
                                $ratioh = $maxHeight / $height;
                                $ratiow = $maxWidth / $width;
                                $ratio = min($ratioh, $ratiow);

                                $width = intval($ratio * $width);
                                $height = intval($ratio * $height);
                            } else {
                                $imgPath = 'images/noimages.jpeg';
                                $width = 120;
                                $height = 105;
                            }

                            // блок с отображение товара в таблице
                            echo '<div class="block-list-cart">
                                <div class="img-cart">
                                    <p align="center"><img src="'.$imgPath.'" width="'.$width.'" height="'.$height.'" /></p>
                                </div>
                                <div class="title-cart">
                                    <p><a href="" >'.$row["title"].'</a></p>
                                    <p class="cart-mini-features">'.$row["mini_features"].'</p>
                                </div>
                                <div class="count-cart">
                                    <ul class="input-count-style">
                                        <li><p align="center" class="count-minus">-</p></li>
                                        <li><p align="center"><input class="count-input" maxlength="3" type="text" value="'.$row["cart_count"].'" /></p></li>
                                        <li><p align="center" class="count-plus">+</p></li>
                                    </ul>
                                </div>
                                <div class="price-product"><h5><span class="span-count">1</span> x <span>'.$row["cart_price"].'</span></h5><p>'.$int.'</p></div>
                                <div class="delete-cart"><a href="cart.php?id='.$row["cart_id"].'&action=delete" ><img src="images/bsk_item_del.png" /></a></div>
                                <div id="bottom-cart-line"></div>
                            </div>';




                        }
                        echo '<h2 class="itog-price" align="right">Итого: <strong>'.$allPrice.'</strong> руб</h2>
                        <p align="right" class="button-next"><a href="cart.php?action=confirm">Далее</a></p>';
                    } else {
                        echo '<h3 id="clear-cart" align="center">Корзина пуста</h3>';
                    }

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