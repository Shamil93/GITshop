<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/08/14
 * Time: 18:44
 */
define('myeshop', true);
require_once('include/Exceptions.php');
require_once('utility/pager.php');
include "include/DB.php";
include ('utility/handleData.php');
include ('utility/getIP.php');
include ('utility/groupPrice.php');
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

if (isset($_POST["submitdata"]) || isset($_POST['pay now'])) {

    $sth_orders_insert = DB::getStatement('INSERT INTO orders (order_datetime,order_dostavka,
                                                                 order_fio,order_address,order_phone,
                                                                 order_note,order_email)
                                                   VALUES (?,?,?,?,?,?,?)');
    $date = date('Y-m-d H:i:s', time());
    if ($_SESSION['auth'] == 'yes_auth'){
        $sth_orders_insert->execute(array($date,
                                    $_POST['order_delivery'],
                                    "{$_SESSION['auth_surname']} {$_SESSION['auth_name']} {$_SESSION['auth_patronymic']}",
                                    $_SESSION['auth_address'],
                                    $_SESSION['auth_phone'],
                                    $_POST['order_note'],
                                    $_SESSION['auth_email']));
        $_SESSION['order_delivery'] = $_POST['order_delivery'];
        $_SESSION['order_payment'] = $_POST['order_payment'];
        $_SESSION['order_note'] = $_POST['order_note'];
        $_SESSION['order_id'] = DB::getId();
    } else {
        $_SESSION['order_delivery'] = $_POST['order_delivery'];
        $_SESSION['order_payment'] = $_POST['order_payment'];
        $_SESSION['order_fio'] = $_POST['order_fio'];
        $_SESSION['order_email'] = handleData($_POST['order_email']);
        $_SESSION['order_phone'] = handleData($_POST['order_phone']);
        $_SESSION['order_address'] = $_POST['order_address'];
        $_SESSION['order_note'] = $_POST['order_note'];
        $sth_orders_insert->execute(array($date,
                                $_POST['order_delivery'],
                                $_POST['order_fio'],
                                $_POST['order_address'],
                                $_POST['order_phone'],
                                $_POST['order_note'],
                                $_POST['order_email']));
        $_SESSION['order_id'] = DB::getId();
    }

    $sth_cart_select = DB::getStatement('SELECT * FROM cart WHERE cart_ip=?');
    $sth_cart_select->execute(array($ip));
    $rows_cart_select = $sth_cart_select->fetchAll();
    if (!empty($rows_cart_select)) {
        foreach ($rows_cart_select as $row_cart_select) {
            $sth_products_insert = DB::getStatement('INSERT INTO buy_products (buy_id_order,
                                                                            buy_id_product,
                                                                            buy_count_product)
                                                        VALUES (?,?,?)');
            $sth_products_insert->execute(array($_SESSION['order_id'],
                                                $row_cart_select['cart_id_product'],
                                                $row_cart_select['cart_count']));
        }
    }
    header("Location: cart.php?action=completion");
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
    $itogPriceCart = groupPrice($int1);
    $_SESSION['itog_price'] = $int1;
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
                                    <p><a href="view_content.php?id='.$row["products_id"].'" >'.$row["title"].'</a></p>
                                    <p class="cart-mini-features">'.$row["mini_features"].'</p>
                                </div>
                                <div class="count-cart">
                                    <ul class="input-count-style">
                                        <li><p align="center" class="count-minus" iid="'.$row['cart_id'].'">-</p></li>
                                        <li><p align="center"><input id="input-id'.$row['cart_id'].'" iid="'.$row['cart_id'].'" class="count-input" maxlength="3" type="text" value="'.$row["cart_count"].'" /></p></li>
                                        <li><p align="center" class="count-plus" iid="'.$row['cart_id'].'">+</p></li>
                                    </ul>
                                </div>
                                <div id="tovar'.$row['cart_id'].'" class="price-product"><h5><span class="span-count">'.$row["cart_count"].'</span> x <span>'.groupPrice($row["cart_price"]).' руб</span></h5><p price="'.$row['cart_price'].'">'.groupPrice($int).' руб</p></div>
                                <div class="delete-cart"><a href="cart.php?id='.$row["cart_id"].'&action=delete" ><img src="images/bsk_item_del.png" /></a></div>
                                <div id="bottom-cart-line"></div>
                            </div>';




                        }
                        echo '<h2 class="itog-price" align="right">Итого: <strong>'.groupPrice($allPrice).'</strong> руб</h2>
                        <p align="right" class="button-next"><a href="cart.php?action=confirm">Далее</a></p>';
                    } else {
                        echo '<h3 id="clear-cart" align="center">Корзина пуста</h3>';
                    }

                    break;
                // переход к оплате
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

                    if (isset($_SESSION['order_payment']) && $_SESSION['order_payment'] != '') {
                        $orderPayment = $_SESSION['order_payment'];
                    } else {
                        $orderPayment = $_POST['order_payment'];
                    }
                    $chk4 = $chk5 = '';
                    if ($orderPayment == 'Другие виды оплаты') $chk4 = "checked";
                    if ($orderPayment == 'PayPal') $chk4 = "checked";

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
                    <h3 class="title-h3">Способ оплаты: </h3>
                    <ul id="info-radio">
                        <li>
                            <input type="radio" name="order_payment" class="order_payment" id="order_payment1" value="Другие виды оплаты" ' .$chck1.' />
                            <label class="label_payment" for="order_payment1">Другие виды оплаты</label>
                        </li>
                        <li>
                            <input type="radio" name="order_payment" class="order_payment" id="order_payment2" value="PayPal"  '.$chck2.' />
                            <label class="label_payment" for="order_payment2">PayPal</label>
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
                    if ($_SESSION['order_payment'] == 'PayPal') {
                        ?>
                        <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
                                <input type="hidden" name="cmd" value="_cart" >
                                <input type="hidden" name="upload" value="1" >
                                <input type="hidden" name="business" value="zhalninpal-facilitator@me.com" >

                            <?php
                            $sth = DB::getStatement("SELECT * FROM cart, table_products WHERE cart.cart_ip = '{$ip}' AND table_products.products_id = cart.cart_id_product");
                            $sth->execute();
                            $rows = $sth->fetchAll();
                            if (! empty($rows)) {
                                $allPrice = 0;
                                $i = 1;
                                $sum_subtotal = 0;
                                $sum_shipping = 0;
//                                echo "<tt><pre>".print_r($rows , true)."</pre></tt>";
                                foreach ($rows as $row) {
                                    $int = $row['cart_price'] * $row['cart_count'];
                                    $allPrice = $allPrice + $int;

                            ?>
                                <input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo $row['title']; ?>" >
                                <input type="hidden" name="item_number_<?php echo $i; ?>" value="<?php echo $_SESSION['order_id']; ?>" >
                                <input type="hidden" name="amount_<?php echo $i; ?>" value="<?php echo $row['price']; ?>" >
                                <input type="hidden" name="quantity_<?php echo $i; ?>" value="<?php echo $row['cart_count']; ?>" >

                            <?php
                                $i++;

                                }
                                if( $allPrice <= 10 ) {
                                    $sum_shipping = $allPrice;
                                } else {
                                    $sum_shipping = round($allPrice / 100  * 4.02 + 10);
                                }
                            }
                            ?>

                                <input type="hidden" name="currency_code" value="RUB" >
                                <input type="hidden" name="lc" value="RUS" >
                                <input type="hidden" name="rm" value="2" >
                                <input type="hidden" name="shipping_1" value="<?php echo $sum_shipping; ?>" >
                                <input type="hidden" name="return" value="http://zhalnin.tmweb.ru/" >
                                <input type="hidden" name="cancel_return" value="http://zhalnin.tmweb.ru/" >
                                <input type="hidden" name="notify_url" value="http://zhalnin.tmweb.ru/order/paypal.php" >
                            <input type="image" src="images/paypal/paypal_mini.png" name="pay now" value="pay" class="pay-button" width="150" height="37" />
                            </form>
                        <?php
                    } else {
                        echo '<h2 class="itog-price" align="right">Итого: <strong>'.$itogPriceCart.'</strong> руб</h2>
                        <form method="post" action="https://www.walletone.com/checkout/default.aspx" accept-charset="UTF-8">
                          <input type="hidden" name="WMI_MERCHANT_ID"    value="169513168489"/>
                          <input type="hidden"  name="WMI_PAYMENT_AMOUNT" value="0"/>
                          <input type="hidden"  name="WMI_CURRENCY_ID"    value="643"/>
                          <input type="hidden"  name="WMI_PAYMENT_NO"    value="'.$_SESSION["order_id"].'"/>
                          <input type="hidden"  name="WMI_DESCRIPTION"    value="Оплата демонстрационного заказа"/>
                          <input type="hidden"  name="WMI_SUCCESS_URL"    value="http://zhalnin.tmweb.ru"/>
                          <input type="hidden"  name="WMI_FAIL_URL"       value="http://zhalnin.tmweb.ru"/>
                          <p align="right" ><input type="submit" name="submitdata" id="confirm-button-next" value="Оплатить" /></p>
                        </form>';
                    }
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
                                        <li><p align="center" class="count-minus" iid="'.$row['cart_id'].'">-</p></li>
                                        <li><p align="center"><input id="input-id'.$row['cart_id'].'" iid="'.$row['cart_id'].'" class="count-input" maxlength="3" type="text" value="'.$row["cart_count"].'" /></p></li>
                                        <li><p align="center" class="count-plus" iid="'.$row['cart_id'].'">+</p></li>
                                    </ul>
                                </div>
                                <div id="tovar'.$row['cart_id'].'" class="price-product"><h5><span class="span-count">'.$row["cart_count"].'</span> x <span>'.groupPrice($row["cart_price"]).' руб</span></h5><p price="'.$row['cart_price'].'">'.groupPrice($int).' руб</p></div>
                                <div class="delete-cart"><a href="cart.php?id='.$row["cart_id"].'&action=delete" ><img src="images/bsk_item_del.png" /></a></div>
                                <div id="bottom-cart-line"></div>
                            </div>';




                        }
                        echo '<h2 class="itog-price" align="right">Итого: <strong>'.groupPrice($allPrice).'</strong> руб</h2>
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