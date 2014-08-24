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
// форматирование цены в формате: 13 000 022
require_once( "ecommerce/view/utils/groupPrice.php" );
// изменение размера изображения
require_once( "ecommerce/view/utils/resize.php" );


try {
    // получаем объект request
    $request = \ecommerce\view\VH::getRequest();
    $products = $request->getObject('products'); // получаем объект с выборкой продуктов с постраничной навигацией
    $sort     = $request->getProperty('sort'); // получаем название сортировки
    $feedback = $request->getFeedbackString(); // сообщение об ошибке
        echo "<tt><pre>".print_r($request, true). "</pre></tt>";


    // Формируем надпись на сортировке
    switch($sort) {
        case 'price-asc':
            $sort_name = 'От дешевых к дорогим';
            break;
        case 'price-desc':
            $sort_name = 'От дорогих к дешевым';
            break;
        case 'popular':
            $sort_name = 'Популярные';
            break;
        case 'news':
            $sort_name = 'Новинки';
            break;
        case 'brand':
            $sort_name = 'По алфавиту';
            break;
        default:
            $sort_name = 'Нет сортировки';
            break;
    }
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

            <!--    подключаем блок block-news-->
            <?php require_once('ecommerce/view/include/block-news.php'); ?>

        </div>

        <div id="block-content">
            <div id="block-sorting">
                <p id="nav-breadcrumbs"><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Главная страница</a> \ <span>Все товары</span></p>
                <ul id="options-list">
                    <li>Вид: </li>
                    <li><img id="style-grid" src="ecommerce/view/icons/icon-grid.png" /></li>
                    <li><img id="style-list" src="ecommerce/view/icons/icon-list.png" /></li>

                    <li>Сортировать: </li>
                    <li><a href="#" id="select-sort"><?php echo $sort_name; ?> </a>
                        <ul id="sorting-list">
                            <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?sort=price-asc" >От дешевых к дорогим</a> </li>
                            <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?sort=price-desc" >От дорогих к дешевым</a> </li>
                            <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?sort=popular" >Популярное</a> </li>
                            <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?sort=news" >Новинки</a> </li>
                            <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?sort=brand" >От А до Я</a> </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <ul id="block-tovar-grid">

                <?php
                if (!is_null($products)) { // если существует
                    foreach ($products['select'] as $product): // проходим в цикле
                        $image_product = $product->getImage(); // получаем изображение
                        // проверяем на существование
                        if (isset($image_product) && file_exists('ecommerce/view/uploads_images/'.$image_product)) {
                            $path = 'ecommerce/view/uploads_images/';
                            $img_path   = $path.$image_product; // путь до фото
                            list($width, $height) = resize( $image_product, $path, 200, 200); // получаем ширину и высоту
                        } else { // если нет фото или не существует путь
                            $img_path = "ecommerce/view/images/no-image.png"; // путь до файла по-умолчанию
                            $width    = 110; // ширина
                            $height   = 200; // высота
                        }
//                            echo "<tt><pre>".print_r($product, true). "</pre></tt>";

                        ?>

                        <li>
                            <div class="block-images-grid"><img src="<?php echo $img_path;  ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" /></div>
                            <p class="style-title-grid"><a href="<?php $_SERVER['PHP_SELF']; ?>?id=<?php echo $product->getId(); ?>" ><?php echo $product->getTitle();  ?></a></p>
                            <ul class="reviews-and-counts-grid">
                                <li><img src="ecommerce/view/icons/eye-icon.png" /><p><?php echo $product->getCount(); ?></p></li>
                                <li><img src="ecommerce/view/icons/comment-icon.png" /><p><?php echo $count_review; ?></p></li>
                            </ul>
                            <a href="" class="add-cart-style-grid" tid="<?php echo $product->getId(); ?>"></a>
                            <p class="style-price-grid"><strong><?php echo \ecommerce\view\utils\groupPrice($product->getPrice()); ?></strong> руб.</p>
                            <div class="mini-features"><?php echo $product->getMiniFeatures();  ?></div>
                        </li>

                    <?php endforeach; echo $products['navigation'];
                } else {
                    echo "<p>$feedback</p>";
                }

                ?>

            </ul>

            <ul id="block-tovar-list">

                <?php
                if (!is_null($products)) { // если существует
                    foreach ($products['select'] as $product): // проходим в цикле
                        $image_product = $product->getImage(); // получаем изображение
                        // проверяем на существование
                        if (isset($image_product) && file_exists('ecommerce/view/uploads_images/'.$image_product)) {
                            $path = 'ecommerce/view/uploads_images/';
                            $img_path   = $path.$image_product; // путь до фото
                            list($width, $height) = resize( $image_product, $path, 150, 150); // получаем ширину и высоту
                        } else { // если нет фото или не существует путь
                            $img_path = "ecommerce/view/images/no-image.png"; // путь до файла по-умолчанию
                            $width    = 80; // ширина
                            $height   = 70; // высота
                        }

                        ?>

                        <li>
                            <div class="block-images-list"><img src="<?php echo $img_path;  ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" /></div>

                            <ul class="reviews-and-counts-list">
                                <li><img src="ecommerce/view/icons/eye-icon.png" /><p><?php echo $product->getCount(); ?></p></li>
                                <li><img src="ecommerce/view/icons/comment-icon.png" /><p><?php echo $count_review; ?></p></li>
                            </ul>
                            <p class="style-title-list"><a href="<?php $_SERVER['PHP_SELF']; ?>?id=<?php echo $product->getId(); ?>"" ><?php echo $product->getTitle();  ?></a></p>
                            <a href="" class="add-cart-style-list" tid="<?php echo $product->getId(); ?>"></a>
                            <p class="style-price-list"><strong><?php echo \ecommerce\view\utils\groupPrice($product->getPrice()); ?></strong> руб.</p>
                            <div class="style-text-list"><?php echo $product->getMiniDescription();  ?></div>
                        </li>

                    <?php endforeach; echo $products['navigation'];
                } else {
                    echo "<p>$feedback</p>";
                }

                ?>

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