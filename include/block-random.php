<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 18/08/14
 * Time: 19:53
 */
defined('myeshop') or header('Location: index.php');
/**
 * Блок случайного вывода изображения в футере
 */
//echo "$_SERVER[PHP_SELF]";
require_once ('DB.php');
require_once ('utility/groupPrice.php');
?>
<div id="block-random-tovar">
    <ul>
        <?php
        // по количеству просмотров
//        $sth = DB::getStatement('SELECT DISTINCT * FROM table_products WHERE visible = 1 ORDER BY count DESC LIMIT 4');
        // случайно
        $sth = DB::getStatement('SELECT DISTINCT * FROM table_products WHERE visible = 1 ORDER BY RAND() LIMIT 4');
        $sth->execute();
        $rows = $sth->fetchAll();
        if (!empty($rows)) {
            foreach ($rows as $row) {

                $sth1 = DB::getStatement('SELECT COUNT(*) as count FROM table_reviews WHERE products_id = ? AND moderat = ?');
                $sth1->execute(array($row['products_id'], 1));
                $rows1 = $sth1->fetch();
                if (! empty($rows1)) {
                    $count_review = $rows1['count'];
//                    echo "<tt><pre>".print_r($row1['image'], true). "</pre></tt>";
                    if (strlen($row['image']) > 0 && file_exists('uploads_images/'.$row['image'])) {
                        $img_path   = 'uploads_images/'.$row['image'];
                        $max_width  = 120;
                        $max_height = 120;
                        list($width, $height) = getimagesize($img_path);
                        $ratioh = $max_height / $height;
                        $ratiow = $max_width / $width;
                        $ratio  = min($ratioh, $ratiow);
                        $width  = intval($ratio * $width);
                        $height = intval($ratio * $height);
                    } else {
                        $img_path = "images/noimages80x70.png";
                        $width    = 65;
                        $height   = 119;
                    }
                }

                echo '<li>
                    <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" />
                    <a class="random-title" href="view_content.php?id='.$row['products_id'].'" >'.$row['title'].'</a>
                    <p class="random-reviews">Отзывы '.$count_review.'</p>
                    <p class="random-price">'.groupPrice($row['price']).'</p>
                    <a class="random-add-cart" tid="'.$row['products_id'].'"></a>
                </li>';

            }
        }
        ?>

    </ul>
</div>