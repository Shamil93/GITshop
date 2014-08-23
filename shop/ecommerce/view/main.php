<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 15:20
 */
namespace ecommerce\view;
error_reporting( E_ALL & ~E_NOTICE );

// подключаем помощник для вьюшки
require_once( "ecommerce/view/ViewHelper.php" );
require_once( "ecommerce/view/utils/groupPrice.php" );


try {
    // получаем объект request
    $request = \ecommerce\view\VH::getRequest();
//    echo "<tt><pre>".print_r($request, true). "</pre></tt>";
    $products = $request->getObject('products');
// переадресация на страницу с новостями - главная
//header( "Location:?cmd=News" );
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>E-commerce</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <script type="text/javascript" src="ecommerce/view/js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="ecommerce/view/js/jquery.migrate.js"></script>
        <script type="text/javascript" src="ecommerce/view/js/jquery.cookie.js"></script>
        <script type="text/javascript" src="ecommerce/view/js/jcarouserllite_1.0.1.js"></script>
        <script type="text/javascript" src="ecommerce/view/js/shop-script.js"></script>
        <link href="ecommerce/view/css/reset.css" rel="stylesheet" type="text/css" />
        <link href="ecommerce/view/css/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>

    <div id="block-body">

        <!--    подключаем блок block-header-->
        <?php require_once('ecommerce/view/include/block-header.php'); ?>

        <div id="block-right">



            <?php require_once('ecommerce/view/include/block-news.php'); ?>

        </div>

        <div id="block-content">
            <div id="block-sorting">
                <p id="nav-breadcrumbs"><a href="index.php">Главная страница</a> \ <span>Все товары</span></p>
                <ul id="options-list">
                    <li>Вид: </li>
                    <li><img id="style-grid" src="ecommerce/view/icons/icon-grid.png" /></li>
                    <li><img id="style-list" src="ecommerce/view/icons/icon-list.png" /></li>

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

                <?php foreach ($products['select'] as $product):
                    $image_product = $product->getImage();
                    if (isset($image_product) && file_exists('ecommerce/view/uploads_images/'.$image_product)) {
//                        echo "<tt><pre>".print_r($image_product, true). "</pre></tt>";
                        $img_path   = 'ecommerce/view/uploads_images/'.$image_product;
                        $max_width  = 200;
                        $max_height = 200;
                        list($width, $height) = getimagesize($img_path);
                        $ratioh = $max_height / $height;
                        $ratiow = $max_width / $width;
                        $ratio  = min($ratioh, $ratiow);
                        $width  = intval($ratio * $width);
                        $height = intval($ratio * $height);
                    } else {
                        $img_path = "ecommerce/view/images/no-image.png";
                        $width    = 110;
                        $height   = 200;
                    }

                    ?>

                <li>
                    <div class="block-images-grid"><img src="<?php echo $img_path;  ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" /></div>
                    <p class="style-title-grid"><a href="view_content.php?id=<?php echo $product->getId(); ?>" ><?php echo $product->getTitle();  ?></a></p>
                    <ul class="reviews-and-counts-grid">
                        <li><img src="ecommerce/view/icons/eye-icon.png" /><p><?php echo $product->getCount(); ?></p></li>
                        <li><img src="ecommerce/view/icons/comment-icon.png" /><p><?php echo $count_review; ?></p></li>
                    </ul>
                    <a href="" class="add-cart-style-grid" tid="<?php echo $product->getId(); ?>"></a>
                    <p class="style-price-grid"><strong><?php echo \ecommerce\view\utils\groupPrice($product->getPrice()); ?></strong> руб.</p>
                    <div class="mini-features"><?php echo $product->getMiniFeatures();  ?></div>
                </li>

                <?php endforeach; echo $products['navigation']; ?>

            </ul>

            <ul id="block-tovar-list">

                <?php foreach ($products['select'] as $product):
                    $image_product = $product->getImage();
                    if (isset($image_product) && file_exists('ecommerce/view/uploads_images/'.$image_product)) {
//                        echo "<tt><pre>".print_r($image_product, true). "</pre></tt>";
                        $img_path   = 'ecommerce/view/uploads_images/'.$image_product;
                        $max_width  = 150;
                        $max_height = 150;
                        list($width, $height) = getimagesize($img_path);
                        $ratioh = $max_height / $height;
                        $ratiow = $max_width / $width;
                        $ratio  = min($ratioh, $ratiow);
                        $width  = intval($ratio * $width);
                        $height = intval($ratio * $height);
                    } else {
                        $img_path = "ecommerce/view/images/no-image.png";
                        $width    = 80;
                        $height   = 70;
                    }
                    ?>

                <li>
                    <div class="block-images-list"><img src="<?php echo $img_path;  ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" /></div>

                    <ul class="reviews-and-counts-list">
                        <li><img src="ecommerce/view/icons/eye-icon.png" /><p><?php echo $product->getCount(); ?></p></li>
                        <li><img src="ecommerce/view/icons/comment-icon.png" /><p><?php echo $count_review; ?></p></li>
                    </ul>
                    <p class="style-title-list"><a href="view_content.php?id=<?php echo $product->getId(); ?>"" ><?php echo $product->getTitle();  ?></a></p>
                    <a href="" class="add-cart-style-list" tid="<?php echo $product->getId(); ?>"></a>
                    <p class="style-price-list"><strong><?php echo \ecommerce\view\utils\groupPrice($product->getPrice()); ?></strong> руб.</p>
                    <div class="style-text-list"><?php echo $product->getMiniDescription();  ?></div>
                </li>

            <?php endforeach; echo $products['navigation']; ?>

            </ul>




        </div><!-- end block-content -->

        <!--    подключаем блок footer-->
        <?php require_once('ecommerce/view/include/block-footer.php'); ?>
    </div>


    </body>
    </html>
<?php
// ловим сообщения об ошибках
} catch( \imei_service\base\AppException $exc ) {
    print $exc->getErrorObject();
} catch( \imei_service\base\DBException $exc ) {
    print $exc->getErrorObject();
}
?>