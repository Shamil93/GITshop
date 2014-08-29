<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 30/08/14
 * Time: 00:23
 */
session_start();
if ($_SESSION['auth_admin'] == 'yes_auth') {

    define('myeshop', true);
    if (isset( $_GET['logout'])) {
        unset($_SESSION['auth_admin']);
        header ('Location: login.php');
    }
    $_SESSION['urlpage'] = "<a href='index.php'>Главная</a> \ <a href='view_order.php'>Просмотр заказов</a> ";

    require_once ('include/DB.php');
    require_once ('utility/pager.php');
    require_once ('utility/handleData.php');

    if(isset($_GET['id'])) {
        $id = handleData($_GET['id']);
    }

    if (isset($_GET['action'])) {
        $action = handleData($_GET['action']);
    }
    switch ($action) {
        case 'accept':
            $sth = DB::getStatement("UPDATE orders SET order_confirmed = 'yes' WHERE order_id = ?");
            $sth->execute(array($id));
            break;
        case 'delete':
            $sth = DB::getStatement("DELETE FROM orders WHERE order_id = ?");
            $sth->execute(array($id));
            header("Location: orders.php");
            break;
    }



    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Панель управления - Просмотр заказов</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="js/jquery_confirm/jquery.confirm/jquery.confirm.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="js/jquery.migrate.js"></script>
        <script type="text/javascript" src="js/admin-script.js"></script>
        <script type="text/javascript" src="js/jquery_confirm/jquery.confirm/jquery.confirm.js"></script>
    </head>
    <body>
    <div id="block-body">
        <?php require_once ('include/block-header.php');

        //                echo "<tt><pre> - djflskdjf - ".print_r($rows_all_count['count'], true). "</pre></tt>";
        ?>
        <div id="block-content">
            <div id="block-parameters">
                <p id="title-page">Просмотр заказа</p>
            </div>

            <?php
            $sth_orders_select = DB::getStatement('SELECT * FROM orders WHERE order_id=?');
            $sth_orders_select->execute(array($id));
            $rows_orders = $sth_orders_select->fetchAll();
            if(!empty($rows_orders)) {
                foreach ($rows_orders as $row_orders) {
                    if($row_orders['order_confirmed'] == 'yes') {
                        $status = '<span class="green">Обработан</span>';
                    } else {
                        $status = '<span class="red">Не обработан</span>';
                    }
                    echo '
                    <p class="view-order-link">
                        <a class="green" href="view_order.php?id='.$row_orders['order_id'].'&action=accept">Подтвердить заказ</a> |
                        <a class="delete" rel="view_order.php?id='.$row_orders['order_id'].'&action=delete">Удалить заказ</a>
                    </p>
                    <p class="order-datetime">'.$row_orders['order_datetime'].'</p>
                    <p class="order-number">Заказ № '.$row_orders['order_id'].' - '.$status.'</p>
                    <table align="center" cellpadding="10" width="100%">
                        <tr>
                            <th>№</th>
                            <th>Наименование товара</th>
                            <th>Цена</th>
                            <th>Количество</th>
                        </tr>';
                    $sth_products = DB::getStatement('SELECT * FROM buy_products, table_products WHERE buy_products.buy_id_order=? AND table_products.products_id=buy_products.buy_id_product');
                    $sth_products->execute(array($id));
                    $rows_products = $sth_products->fetchAll();
                    if (!empty($rows_products)) {
                        foreach( $rows_products as $row_product) {
                            $price = $price + $row_product['price'] * $row_product['buy_count_product'];
                            $index_count = $index_count + 1;
                            echo '
                            <tr>
                                <td align="center">'.$index_count.'</td>
                                <td align="center">'.$row_product["title"].'</td>
                                <td align="center">'.$row_product["price"].'</td>
                                <td align="center">'.$row_product["buy_count_product"].'</td>
                            </tr>
                            ';
                        }
                    }

                    if($row_orders['order_pay'] == 'accepted') {
                        $statpay = '<span class="green">Оплачено</span>';
                    } else {
                        $statpay = '<span class="red">Не оплачено</span>';
                    }

                    echo '
                    </table>
                    <ul id="info-order">
                        <li>Общая цена - <span>'.$price.'</span></li>
                        <li>Способ доставки - <span>'.$row_orders["order_dostavka"].'</span></li>
                        <li>Статус оплаты - '.$statpay.'</li>
                        <li>Тип оплаты - <span>'.$row_orders["order_type_pay"].'</span></li>
                        <li>Дата оплаты - <span>'.$row_orders["order_datetime"].'</span></li>
                    </ul>

                    <table align="center" cellpadding="10" width="100%">
                        <tr>
                            <th>ФИО</th>
                            <th>Адрес</th>
                            <th>Контакты</th>
                            <th>Примечание</th>
                        </tr>
                        <tr>
                            <td align="center">'.$row_orders["order_fio"].'</td>
                            <td align="center">'.$row_orders["order_address"].'</td>
                            <td align="center">'.$row_orders["order_phone"].'<br />'.$row_orders["order_email"].'</td>
                            <td align="center">'.$row_orders["order_note"].'</td>
                        </tr>
                    </table
                    ';

                }

            }

            ?>
        </div>
    </div>
    </body>
    </html>
<?php
} else {
    header ('Location: login.php');
}
?>