<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/08/14
 * Time: 11:36
 */

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shop</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
    <script type="text/javascript" src="js/jcarouserllite_1.0.1.js"></script>
    <script type="text/javascript" src="js/shop-script.js"></script>
</head>
<body>

<div id="block-body">

    <!--    подключаем блок block-header-->
    <?php include( 'include/block-header.php' ); ?>

    <div id="block-right">
        <?php include( 'include/block-category.php' ); ?>
        <?php include( 'include/block-parameter.php' ); ?>
        <?php include( 'include/block-news.php' ); ?>
    </div>

    <div id="block-content">
        <div id="block-sorting">
            <p id="nav-breadcrumbs"><a href="index.php">Главная страница</a> \ <span>Все товары</span></p>
            <ul id="options-list">
                <li>Вид: </li>
                <li><img id="style-grid" src="images/icon-grid.png" /></li>
                <li><img id="style-list" src="images/icon-list.png" /></li>

                <li>Сортировать: </li>
                <li><a href="" id="select-sort">Без сортировки</a>
                    <ul id="sorting-list">
                        <li><a href="" >От дешевых к дорогим</a> </li>
                        <li><a href="" >От дорогих к дешевым</a> </li>
                        <li><a href="" >Популярное</a> </li>
                        <li><a href="" >Новинки</a> </li>
                        <li><a href="" >От А до Я</a> </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <?php include( 'include/block-footer.php' ); ?>

</div>

</body>
</html>