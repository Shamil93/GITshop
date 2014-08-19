<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/08/14
 * Time: 19:38
 */
defined('myeshop') or header('Location: index.php');
?>
<div id="block-news">
    <img id="news-prev" src="images/img-prev.png">
    <div id="newsticker">
        <ul>
            <?php
            $sth = DB::getStatement("SELECT * FROM news ORDER BY id DESC");
            $sth->execute();
            $rows = $sth->fetchAll();
            foreach ($rows as $row): ?>
                <li>
                    <span><?php echo $row['date']; ?></span>
                    <a href=""><?php echo $row['title']; ?></a>
                    <p><?php echo $row['text']; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <img id="news-next" src="images/img-next.png">
</div>