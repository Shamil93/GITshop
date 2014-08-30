<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/08/14
 * Time: 00:08
 */
defined('myeshop') or die('Доступ запрещен!');
require_once('DB.php');

$sth_count_orders = DB::getStatement("SELECT COUNT(*) as count FROM orders WHERE order_confirmed='no'");
$sth_count_orders->execute();
$row_count_orders = $sth_count_orders->fetch();
if ($row_count_orders['count'] > 0) {
    $count_str1 = '<p>+'.$row_count_orders['count'].'</p>';
} else {
    $count_str1 = '';
}

$sth_count_review = DB::getStatement("SELECT COUNT(*) as count FROM table_reviews WHERE moderat='0'");
$sth_count_review->execute();
$row_count_review = $sth_count_review->fetch();
if ($row_count_review['count'] > 0) {
    $count_str2 = '<p>+'.$row_count_review['count'].'</p>';
} else {
    $count_str2 = '';
}
//echo "<tt><pre>".print_r($_SESSION, true)."</pre></tt>";

?>
<div id="block-header">
    <div id="block-header1">
        <h3>E-SHOP. Панель Управления</h3>
        <p id="link-nav"><?php echo $_SESSION['urlpage']; ?></p>
    </div>
    <div id="block-header2">
        <p align="right"><a href="administrators.php" >Администраторы</a> | <a href="?logout">Выход</a></p>
        <p align="right">Вы - <span><?php echo $_SESSION['admin_role']; ?></span></p>
    </div>
</div>

<div id="left-nav">
    <ul>
        <li><a href="orders.php">Заказы</a><?php echo $count_str1; ?></li>
        <li><a href="tovar.php">Товары</a></li>
        <li><a href="reviews.php">Отзывы</a><?php echo $count_str2; ?></li>
        <li><a href="category.php">Категории</a></li>
        <li><a href="clients.php">Клиенты</a></li>
        <li><a href="news.php">Новости</a></li>
    </ul>
</div>
