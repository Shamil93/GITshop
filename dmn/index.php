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
    <?php require_once ('include/block-header.php');


    $sth_sel_orders = DB::getStatement('SELECT COUNT(*) as count FROM orders');
    $sth_sel_orders->execute();
    $row_sel_orders_count = $sth_sel_orders->fetch();
    $sth_sel_products = DB::getStatement('SELECT COUNT(*) as count  FROM table_products');
    $sth_sel_products->execute();
    $row_sel_products_count = $sth_sel_products->fetch();
    $sth_sel_review = DB::getStatement('SELECT COUNT(*) as count  FROM table_reviews');
    $sth_sel_review->execute();
    $row_sel_review_count = $sth_sel_review->fetch();
    $sth_sel_user = DB::getStatement('SELECT COUNT(*) as count FROM reg_user');
    $sth_sel_user->execute();
    $row_sel_user_count = $sth_sel_user->fetch();


    ?>
    <div id="block-content">
        <div id="block-parameters">
            <p id="title-page">Общая статистика</p>
        </div>
        <ul id="general-statistics">
            <li><p>Всего заказов - <span><?php echo $row_sel_orders_count['count']; ?></span></p></li>
            <li><p>Товаров - <span><?php echo $row_sel_products_count['count']; ?></span></p></li>
            <li><p>Отзывы - <span><?php echo $row_sel_review_count['count']; ?></span></p></li>
            <li><p>Клиенты - <span><?php echo $row_sel_user_count['count']; ?></span></p></li>
        </ul>
        <h3 id="title-statistics">Статистика продаж</h3>
        <table align="center" cellpadding="10" width="100%">
            <tr>
                <th>Дата</th>
                <th>Товар</th>
                <th>Цена</th>
                <th>Статус</th>
            </tr>

            <?php
            $sth_orders_products = DB::getStatement("SELECT * FROM orders, buy_products
                                                                WHERE orders.order_pay='accepted'
                                                                AND orders.order_id=buy_products.buy_id_order");
            $sth_orders_products->execute();
            $rows_orders_products = $sth_orders_products->fetchAll();
            if (!empty($rows_orders_products)) {
//                    echo "<tt><pre>".print_r($rows_orders_products, true)."</pre></tt>";
                foreach ($rows_orders_products as $row_orders_products) {
                    $sth_sel_products = DB::getStatement('SELECT * FROM table_products
                                                                    WHERE products_id=?');
                    $sth_sel_products->execute(array($row_orders_products['buy_id_product']));
                    $row_products = $sth_sel_products->fetch();
//            echo "<tt><pre>".print_r($rows_products, true)."</pre></tt>";
                    $statuspay = "";
                    if ($row_orders_products['order_pay'] == "accepted") $statuspay = "Оплачено";

                    echo '
                    <tr>
                        <td align="center">'.$row_orders_products["order_datetime"].'</td>
                        <td align="center">'.$row_products["title"].'</td>
                        <td align="center">'.$row_products["price"].'</td>
                        <td align="center">'.$statuspay.'</td>
                    </tr>';
                }

            }
            ?>
        </table>
    </div>
</div>
</body>
</html>
<?php
} else {
    header ('Location: login.php');
}
?>