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

if (isset($_POST["submitdata"])) {

    if (isset($_POST['order_delivery']) && $_POST['order_delivery'] != '') {
        $_POST['order_delivery'] = handleData($_POST['order_delivery']);
    } else {
        $error[] = 'Выберите один из способов доставки!';
    }
    $_POST['order_fio']      = handleData($_POST['order_fio']);
    $_POST['order_email']    = handleData($_POST['order_email']);
    $_POST['order_address']  = handleData($_POST['order_address']);
    $_POST['order_phone']    = handleData($_POST['order_phone']);

    if (strlen($_POST['order_fio']) < 3 || strlen($_POST['order_fio']) > 50) {
        $error[] = 'Укажите ваши фамилию, имя и отчество от 3 до 50 символов!';
    }
    if (strlen($_POST['order_email']) == "") {
        $error[] = 'Укажите E-mail!';
    } else if(!preg_match('|^[-a-z0-9_\.]+\@[-a-z0-9_\.]+\.[a-z]{2,6}$|i',$_POST['order_email'])) {
        $error[] = 'Укажите корректный E-mail!';
    }
    if (strlen($_POST['order_address']) == "") {
        $error[] = 'Укажите адрес доставки!';
    }
    if (strlen($_POST['order_phone']) == "") {
        $error[] = 'Укажите номер телефона!';
    }
    if (count($error)) {
        $_SESSION['order_msg'] = "<p align='left' id='form-error'>".implode('<br />', $error)."</p>";
    } else {
        $_SESSION['order_delivery'] = $_POST['order_delivery'];
        $_SESSION['order_fio']      = $_POST['order_fio'];
        $_SESSION['order_email']    = $_POST['order_email'];
        $_SESSION['order_phone']    = $_POST['order_phone'];
        $_SESSION['order_address']  = $_POST['order_address'];
        $_SESSION['order_note']     = $_POST['order_note'];

        header("Location: cart.php?action=completion");
    }
}

$sth1 = DB::getStatement("SELECT * FROM cart, table_products WHERE cart.cart_ip = '{$ip}' AND table_products.products_id = cart.cart_id_product");
$sth1->execute();
$rows1 = $sth1->fetchAll();
if (! empty($rows1)) {
    $itogPriceCart = 0;
    $int1 = 0;
    foreach ($rows1 as $row1) {
        $int1 = $int1 + ($row1["price"] * $row1["cart_count"]);
//        $int = $row1['price'] * $row1['cart_count'];
//        echo "<tt><pre>".print_r($row1['price'], true)."</pre></tt>";
    }
    $itogPriceCart = $int1;
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
                            <li><a href="cart.php?action=oneclick">1. Корзина товаров</a></li>
                            <li><span>&rarr;</span></li>
                            <li><a class="active" href="cart.php?action=confirm">2. Контактная информация</a></li>
                            <li><span>&rarr;</span></li>
                            <li><a href="" >3. Завершение</a></li>
                            </ul>
                        </div>
                        <p>Шаг 2 из 3</p>
                    </div>';


                    if (isset($_SESSION['order_delivery']) && $_SESSION['order_delivery'] != '') {
                        $orderDelivery = $_SESSION['order_delivery'];
                    } else {
                        $orderDelivery = $_POST['order_delivery'];
                    }
                    $chck1 = $chck2 = $chck3 = '';
                    if ($orderDelivery == "По почте") $chck1 = "checked";
                    if ($orderDelivery == "Курьером") $chck2 = "checked";
                    if ($orderDelivery == "Самовывоз") $chck3 = "checked";

                    echo '<h3 class="title-h3">Способы доставки: </h3>';

                    if (isset($_SESSION["order_msg"]) && $_SESSION["order_msg"]) {
                        echo $_SESSION["order_msg"];
                        unset($_SESSION["order_msg"]);
                    }
            echo '<form method="POST">
                    <ul id="info-radio">
                        <li>
                            <input type="radio" name="order_delivery" class="order_delivery" id="order_delivery1" value="По почте" ' .$chck1.' />
                            <label class="label_delivery" for="order_delivery1">По почте</label>
                        </li>
                        <li>
                            <input type="radio" name="order_delivery" class="order_delivery" id="order_delivery2" value="Курьером"  '.$chck2.' />
                            <label class="label_delivery" for="order_delivery2">Курьером</label>
                        </li>
                        <li>
                            <input type="radio" name="order_delivery" class="order_delivery" id="order_delivery3" value="Самовывоз" ' .$chck3.' />
                            <label class="label_delivery" for="order_delivery3">Самовывоз</label>
                        </li>
                    </ul>
                    <h3 class="title-h3">Информация для доставки: </h3>
                    <ul id="info-order">';
                    if ($_SESSION['auth'] != 'yes_auth') {
                        if (isset($_SESSION["order_fio"]) && $_SESSION["order_fio"] != '') {
                            $orderFio = $_SESSION["order_fio"];
                        } else {
                            $orderFio = $_POST['order_fio'];
                        }
                        if (isset($_SESSION["order_email"]) && $_SESSION["order_email"] != '') {
                            $orderEmail = $_SESSION["order_email"];
                        } else {
                            $orderEmail =  $_POST['order_email'];
                        }
                        if (isset($_SESSION["order_phone"]) && $_SESSION["order_phone"] != '') {
                            $orderPhone = $_SESSION["order_phone"];
                        } else {
                            $orderPhone = $_POST['order_phone'];
                        }
                        if (isset($_SESSION["order_address"]) && $_SESSION["order_address"] != '') {
                            $orderAddress = $_SESSION["order_address"];
                        } else {
                            $orderAddress = $_POST['order_address'];
                        }

                        echo '<li><label for="order_fio"><span>*</span>ФИО</label><input type="text" name="order_fio" id="order_fio" value="'.$orderFio.'" /><span class="order_span_style">Пример: Иванов Иван Иванович</span></li>
                              <li><label for="order_email"><span>*</span>E-mail</label><input type="text" name="order_email" id="order_email" value="'.$orderEmail.'"  /><span class="order_span_style">Пример: ivanov@mail.ru</span></li>
                              <li><label for="order_phone"><span>*</span>Телефон</label><input type="text" name="order_phone" id="order_phone" value="'.$orderPhone.'"  /><span class="order_span_style">Пример: 8 950 333-22-11</span></li>
                              <li><label class="order_label_style" for="order_address"><span>*</span>Адрес<br /> доставки</label><input type="text" name="order_address" id="order_address" value="'.$orderAddress.'"  /><span class="order_span_style">Пример: г. Москва,<br /> ул. Строителей 35, корп.2, кв.35 </span></li>';
                    }
                    if (isset($_SESSION["order_note"]) && $_SESSION["order_note"] != '') {
                        $orderNote = $_SESSION["order_note"];
                    } else {
                        $orderNote =  $_POST['order_note'];
                    }

                    echo '<li><label class="order_label_style" for="order_note">Примечание</label>
                            <textarea name="order_note">'.$orderNote.'</textarea><span>Уточните информацию о заказе.<br /> Например, удобное время для звонка<br /> нашего менеджера</span></li></ul>
                        <p align="right"><input type="submit" name="submitdata" id="confirm-button-next" value="Далее" /></p>
                    </form>';
                    break;
                case 'completion':
                    echo '<div id="block-step">
                        <div id="name-step">
                            <ul>
                            <li><a href="cart.php?action=oneclick">1. Корзина товаров</a></li>
                            <li><span>&rarr;</span></li>
                            <li><a href="cart.php?action=confirm">2. Контактная информация</a></li>
                            <li><span>&rarr;</span></li>
                            <li><a class="active" href="" >3. Завершение</a></li>
                            </ul>
                        </div>
                        <p>Шаг 3 из 3</p>
                    </div>
                    <h3>Конечная информация: </h3>';
                    if ($_SESSION['auth'] == 'yes_auth') { // если пользователь авторизован
                        echo '<ul id="list-info">
                                <li><strong>Способ доставки: </strong>'.$_SESSION['order_delivery'].'</li>
                                <li><strong>E-mail: </strong>'.$_SESSION['auth_email'].'</li>
                                <li><strong>ФИО: </strong>'.$_SESSION['auth_surname'].' '.$_SESSION['auth_name'].' '.$_SESSION['auth_patronymic'].'</li>
                                <li><strong>Адрес доставки: </strong>'.$_SESSION['auth_address'].'</li>
                                <li><strong>Телефон: </strong>'.$_SESSION['auth_phone'].'</li>
                                <li><strong>Примечание: </strong>'.$_SESSION['order_note'].'</li>
                        </ul>';
                    } else { // если пользователь не авторизован
                        echo '<ul id="list-info">
                                <li><strong>Способ доставки: </strong>'.$_SESSION['order_delivery'].'</li>
                                <li><strong>E-mail: </strong>'.$_SESSION['order_email'].'</li>
                                <li><strong>ФИО: </strong>'.$_SESSION['order_fio'].'</li>
                                <li><strong>Адрес доставки: </strong>'.$_SESSION['order_address'].'</li>
                                <li><strong>Телефон: </strong>'.$_SESSION['order_phone'].'</li>
                                <li><strong>Примечание: </strong>'.$_SESSION['order_note'].'</li>
                        </ul>';
                    }
                    echo '<h2 class="itog-price" align="right">Итого: <strong>'.$itogPriceCart.'</strong> руб</h2>
                    <p align="right" class="button-next"><a href="">Оплатить</a></p>';
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