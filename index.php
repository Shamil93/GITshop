<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/08/14
 * Time: 11:36
 */
require_once( 'include/Exceptions.php' );
include "include/DB.php";
try {
// получаем значение для сортировки товара
$sorting = $_GET['sort'];
switch ( $sorting ) {
    case 'price-asc':
        $sorting = 'price ASC';
        $sort_name = 'От дешевых к дорогим';
        break;
    case 'price-desc':
        $sorting = 'price DESC';
        $sort_name = 'От дорогих к дешевым';
        break;
    case 'popular':
        $sorting = 'count DESC';
        $sort_name = 'Популярные';
        break;
    case 'news':
        $sorting = 'datetime DESC';
        $sort_name = 'Новинки';
        break;
    case 'brand':
        $sorting = 'brand ASC';
        $sort_name = 'По алфавиту';
        break;
    default:
        $sorting = 'products_id ASC';
        $sort_name = 'Нет сортировки';
        break;

}
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
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
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
                <li><a href="#" id="select-sort"><?php echo $sort_name; ?> </a>
                    <ul id="sorting-list">
                        <li><a href="index.php?sort=price-asc" >От дешевых к дорогим</a> </li>
                        <li><a href="index.php?sort=price-desc" >От дорогих к дешевым</a> </li>
                        <li><a href="index.php?sort=popular" >Популярное</a> </li>
                        <li><a href="index.php?sort=news" >Новинки</a> </li>
                        <li><a href="index.php?sort=brand" >От А до Я</a> </li>
                    </ul>
                </li>
            </ul>
        </div>

        <ul id="block-tovar-grid">

        <?php

            $sth = DB::getStatement( "SELECT * FROM table_products WHERE visible='1' ORDER BY {$sorting}" );
            $sth->execute();
            $rows = $sth->fetchAll();


        foreach ( $rows as $row ):

                if( isset( $row['image'] ) && file_exists( 'uploads_images/'.$row['image'] ) ) {
                        $img_path   = 'uploads_images/'.$row['image'];
                        $max_width  = 200;
                        $max_height = 200;
                        list( $width, $height ) = getimagesize( $img_path );
                        $ratioh = $max_height / $height;
                        $ratiow = $max_width / $width;
                        $ratio  = min( $ratioh, $ratiow );
                        $width  = intval( $ratio * $width );
                        $height = intval( $ratio * $height );
                } else {
                    $img_path = "images/no-image.png";
                    $width    = 110;
                    $height   = 200;
                }
//echo "<tt><pre>".print_r( $row['image'], true ). "</pre></tt>";
                ?>
                <li>
                    <div class="block-images-grid"><img src="<?php echo $img_path;  ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" /></div>
                    <p class="style-title-grid"><a href="" ><?php echo $row['title'];  ?></a></p>
                    <ul class="reviews-and-counts-grid">
                        <li><img src="images/eye-icon.png" /><p>0</p></li>
                        <li><img src="images/comment-icon.png" /><p>0</p></li>
                    </ul>
                    <a href="" class="add-cart-style-grid"></a>
                    <p class="style-price-grid"><strong><?php echo $row['price']; ?></strong> руб.</p>
                    <div class="mini-features"><?php echo $row['mini_features'];  ?></div>
                </li>

            <?php endforeach; ?>

        </ul>

        <ul id="block-tovar-list">

            <?php

            $sth = DB::getStatement( "SELECT * FROM table_products WHERE visible='1' ORDER BY {$sorting}" );
            $sth->execute();
            $rows = $sth->fetchAll();


            foreach ( $rows as $row ):

                if( isset( $row['image'] ) && file_exists( 'uploads_images/'.$row['image'] ) ) {
                    $img_path   = 'uploads_images/'.$row['image'];
                    $max_width  = 150;
                    $max_height = 150;
                    list( $width, $height ) = getimagesize( $img_path );
                    $ratioh = $max_height / $height;
                    $ratiow = $max_width / $width;
                    $ratio  = min( $ratioh, $ratiow );
                    $width  = intval( $ratio * $width );
                    $height = intval( $ratio * $height );
                } else {
                    $img_path = "images/noimages80x70.png";
                    $width    = 80;
                    $height   = 70;
                }
    //echo "<tt><pre>".print_r( $row['image'], true ). "</pre></tt>";
                ?>
                <li>
                    <div class="block-images-list"><img src="<?php echo $img_path;  ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" /></div>

                    <ul class="reviews-and-counts-list">
                        <li><img src="images/eye-icon.png" /><p>0</p></li>
                        <li><img src="images/comment-icon.png" /><p>0</p></li>
                    </ul>
                    <p class="style-title-list"><a href="" ><?php echo $row['title'];  ?></a></p>
                    <a href="" class="add-cart-style-list"></a>
                    <p class="style-price-list"><strong><?php echo $row['price']; ?></strong> руб.</p>
                    <div class="style-text-list"><?php echo $row['mini_description'];  ?></div>
                </li>

            <?php endforeach; ?>

        </ul>


    </div><!-- end block-content -->


    <?php include( 'include/block-footer.php' ); ?>

</div>

</body>
</html>
<?php
} catch( PDOException $ex ) {
    throw new Exceptions( $ex );
}
?>