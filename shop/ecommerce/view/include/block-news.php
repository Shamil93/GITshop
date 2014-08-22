<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/08/14
 * Time: 16:03
 */
namespace ecommerce\view;
//defined('ecommerceShop') or die();
error_reporting( E_ALL & ~E_NOTICE );

$news = $request->getObject('news');

?>
<div id="block-news">
    <img id="news-prev" src="ecommerce/view/icons/img-prev.png">
    <div id="newsticker">
        <ul>
            <?php
            foreach ($news as $new): ?>
                <li>
                    <span><?php echo $new->getDate(); ?></span>
                    <a href=""><?php echo $new->getTitle(); ?></a>
                    <p><?php echo $new->getText(); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <img id="news-next" src="ecommerce/view/icons/img-next.png">
</div>