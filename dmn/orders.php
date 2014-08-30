<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/08/14
 * Time: 23:50
 */
session_start();
if ($_SESSION['auth_admin'] == 'yes_auth') {

    define('myeshop', true);
    if (isset( $_GET['logout'])) {
        unset($_SESSION['auth_admin']);
        header ('Location: login.php');
    }
    $_SESSION['urlpage'] = "<a href='index.php'>Главная</a> \ <a href='orders.php'>Заказы</a> ";

    require_once ('include/DB.php');
    require_once ('utility/pager.php');
    require_once ('utility/handleData.php');

    if(isset($_GET['id'])) {
        $id = handleData($_GET['id']);
    }
    if(isset($_GET['sort'])) {
        $sort = handleData($_GET['sort']);
    } else {
        $sort = '';
    }
    switch($sort) {
        case 'all-orders':
            $sorting = "order_id DESC";
            $sort_name = "От А до Я";
            break;
        case 'confirmed':
            $sorting = "order_confirmed='yes' DESC";
            $sort_name = "Обработанные";
            break;
        case 'no-confirmed':
            $sorting = "order_confirmed='no' DESC";
            $sort_name = "Не обработанные";
            break;
        default:
            $sorting = "order_id DESC";
            $sort_name = "От А до Я";
            break;
    }


    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Панель управления - Заказы</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="js/jquery_confirm/jquery.confirm/jquery.confirm.css" rel="stylesheet" type="text/css" />
        <link href="js/fancyBox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="js/jquery.migrate.js"></script>
        <script type="text/javascript" src="js/admin-script.js"></script>
        <script type="text/javascript" src="js/jquery_confirm/jquery.confirm/jquery.confirm.js"></script>
        <script type="text/javascript" src="js/fancyBox/source/jquery.fancybox.js" ></script>

    </head>
    <body>
    <div id="block-body">
        <?php require_once ('include/block-header.php');
        $sth_all_count = DB::getStatement("SELECT COUNT(*) as count FROM orders");
        $sth_all_count->execute();
        $rows_all_count = $sth_all_count->fetch();
        $sth_buy_count = DB::getStatement("SELECT COUNT(*) as count FROM orders WHERE order_confirmed = 'yes'");
        $sth_buy_count->execute();
        $rows_buy_count = $sth_buy_count->fetch();
        $sth_no_buy_count = DB::getStatement("SELECT COUNT(*) as count FROM orders WHERE order_confirmed = 'no'");
        $sth_no_buy_count->execute();
        $rows_no_buy_count = $sth_no_buy_count->fetch();
        //                echo "<tt><pre> - djflskdjf - ".print_r($rows_all_count['count'], true). "</pre></tt>";
        ?>
        <div id="block-content">
            <div id="block-parameters">

                <ul id="options-list">
                    <li>Отзывы</li>
                    <li><a id="select-links" href="#"><?php echo $sort_name; ?></a>
                        <ul id="list-links-sort">
                            <li><a href="orders.php?sort=all-orders">От А до Я</a></li>
                            <li><a href="orders.php?sort=confirmed">Обработанные</a></li>
                            <li><a href="orders.php?sort=no-confirmed">Не обработанные</a></li>
                        </ul>
                    </li>
                </ul>

            </div>
            <div id="block-info">
                <ul id="review-info-count">
                    <li>Всего заказов - <strong><?php echo $rows_all_count['count']; ?></strong></li>
                    <li>Обработанных - <strong><?php echo $rows_buy_count['count']; ?></strong></li>
                    <li>Не обработанных - <strong><?php echo $rows_no_buy_count['count']; ?></strong></li>
                </ul>
            </div>

            <?php
            //$sth_orders = DB::getStatement('SELECT * FROM orders');
            $sth_orders = DB::getStatement("SELECT * FROM orders ORDER BY $sorting");
            $sth_orders->execute();
            $rows_orders = $sth_orders->fetchAll();
            if (!empty($rows_orders)) {
                foreach ($rows_orders as $row_orders) {
                    if($row_orders['order_confirmed'] == 'yes') {
                        $status = '<span class="green">Обработан</span>';
                    } else {
                        $status = '<span class="red">Не обработан</span>';
                    }

                    echo '
                    <div class="block-order">
                        <p class="order-datetime">'.$row_orders["order_datetime"].'</p>
                        <p class="order-number">Заказ № '.$row_orders["order_id"].' - '.$status.'</p>
                        <p class="order-link"><a class="green" href="view_order.php?id='.$row_orders["order_id"].'">Подробнее</a></p>
                    </div>';
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