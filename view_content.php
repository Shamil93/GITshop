<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 17/08/14
 * Time: 20:48
 */

require_once('include/Exceptions.php');
require_once('utility/pager.php');
include "include/DB.php";
include ('utility/handleData.php');
include ('utility/groupPrice.php');
session_start();
include "include/auth-cookie.php";

//unset($_SESSION['auth']);
//setcookie('rememberme','',0,'/');
try {


    if (isset($_GET['id'])) {
        $id = handleData($_GET['id']);
    }
    if ($id != $_SESSION['countid']) {
        $sth1 = DB::getStatement('SELECT count FROM table_products WHERE products_id = ?');
        $sth1->execute(array($id));
        $row1 = $sth1->fetch();
        $newcount = $row1['count'] + 1;

        $sth2 = DB::getStatement('UPDATE table_products SET count = ? WHERE products_id = ?');
        $sth2->execute(array($newcount, $id));

        $_SESSION['countid'] = $id;

    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Shop</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="TrackBar/jQuery/trackbar.css" rel="stylesheet" type="text/css" />
        <link href="fancyBox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="js/jcarouserllite_1.0.1.js"></script>
        <script type="text/javascript" src="js/jquery.cookie.js"></script>
        <script type="text/javascript" src="TrackBar/jQuery/jquery.trackbar.js"></script>
        <script type="text/javascript" src="js/jquery.TextChange.js"></script>
        <script type="text/javascript" src="js/shop-script.js"></script>
        <script type="text/javascript" src="fancyBox/source/jquery.fancybox.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
               $('.image-modal').fancybox();
            });
        </script>
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

            $sth    = DB::getStatement("SELECT * FROM table_products WHERE products_id = ? AND visible=?");
            $sth->execute(array($id, 1));
//            $rows  = $sth->fetch();
            $row  = $sth->fetch();

            //        echo "<tt><pre> - asjfksajdfklsdjf - ".print_r(gettype($pnumber), true). "</pre></tt>";
//            foreach ($rows as $row){

                if (isset($row['image']) && file_exists('uploads_images/'.$row['image'])) {
                    $img_path   = 'uploads_images/'.$row['image'];
                    $max_width  = 300;
                    $max_height = 300;
                    list($width, $height) = getimagesize($img_path);
                    $ratioh = $max_height / $height;
                    $ratiow = $max_width / $width;
                    $ratio  = min($ratioh, $ratiow);
                    $width  = intval($ratio * $width);
                    $height = intval($ratio * $height);
                } else {
                    $img_path = "images/no-image.png";
                    $width    = 110;
                    $height   = 200;
                }

                echo '<div id="block-breadcrumbs-and-rating">
                    <p id="nav-breadcrumbs"><a href="view_mobile.php">Мобильные телефоны</a> \ <span>'.$row['brand'].'</span></p>
                </div>
                <div id="block-content-info">
                    <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" />
                    <div id="block-mini-description">
                        <p id="content-title">'.$row['title'].'</p>
                        <ul class="reviews-and-counts-content">
                            <li><img src="images/eye-icon.png" /><p>'.$row['count'].'</p></li>
                            <li><img src="images/comment-icon.png" /><p>
                        </ul>
                        <p id="style-price" >'.groupPrice($row['price']).' руб</p>
                        <a href="" class="add-cart" id="add-cart-view" tid="'.$row['products_id'].'" ></a>
                        <p id="content-text">'.$row['mini_features'].'</p>
                    </div>
                </div>
                ';

            // вывод маленьких изображений
            $sth1    = DB::getStatement("SELECT * FROM uploads_images WHERE products_id = ?");
            $sth1->execute(array($id));
            $rows1  = $sth1->fetchAll();
//            $row1  = $sth1->fetch();

//                    echo "<tt><pre> - asjfksajdfklsdjf - ".print_r($rows1, true). "</pre></tt>";
            echo '<div id="block-img-slide">
                    <ul>';
            foreach ($rows1 as $row1){

                if (isset($row1['image']) && file_exists('uploads_images/'.$row1['image'])) {
                    $img_path   = 'uploads_images/'.$row1['image'];
                    $max_width  = 70;
                    $max_height = 70;
                    list($width, $height) = getimagesize($img_path);
                    $ratioh = $max_height / $height;
                    $ratiow = $max_width / $width;
                    $ratio  = min($ratioh, $ratiow);
                    $width  = intval($ratio * $width);
                    $height = intval($ratio * $height);

                    echo '<li>
                            <a class="image-modal" href="#image'.$row1["id"].'"><img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" /></a>
                        </li>
                        <a style="display: none;" class="image-modal" rel="group" id="image'.$row1["id"].'"><img src="uploads_images/'.$row1["image"].'" /></a>';
                }
            }
            echo '</ul></div>';


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