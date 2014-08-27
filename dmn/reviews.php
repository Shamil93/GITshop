<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/08/14
 * Time: 15:46
 */
session_start();
if ($_SESSION['auth_admin'] == 'yes_auth') {

    define('myeshop', true);
    if (isset( $_GET['logout'])) {
        unset($_SESSION['auth_admin']);
        header ('Location: login.php');
    }
    $_SESSION['urlpage'] = "<a href='index.php'>Главная</a> \ <a href='reviews.php'>Отзывы</a> ";

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
        case 'accept':
            $sorting = "moderat='1' DESC";
            $sort_name = "Проверенные";
            break;
        case 'no-accept':
            $sorting = "moderat='0' DESC";
            $sort_name = "Не проверенные";
            break;
        default:
            $sorting = "reviews_id DESC";
            $sort_name = "Без сортировки";
            break;
    }



    if(isset($_GET['action'])) {
        $action = handleData($_GET['action']);
        switch($action) {
            case 'delete':
                $sth_delete = DB::getStatement('DELETE FROM table_reviews WHERE reviews_id=?');
                $sth_delete->execute(array($id));
                break;
            case 'accept':
                $sth_update = DB::getStatement('UPDATE table_reviews SET moderat=1  WHERE reviews_id=?');
                $sth_update->execute(array($id));
                break;
        }
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Панель управления</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="js/jquery_confirm/jquery.confirm/jquery.confirm.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="js/jquery.migrate.js"></script>
        <script type="text/javascript" src="js/admin-script.js"></script>
        <script type="text/javascript" src="js/jquery_confirm/jquery.confirm/jquery.confirm.js"></script>
    </head>
    <body>
    <div id="block-body">
        <?php require_once ('include/block-header.php');
        $sth_all_count = DB::getStatement("SELECT COUNT(*) as count FROM table_reviews");
        $sth_all_count->execute();
        $rows_all_count = $sth_all_count->fetch();
        $sth_no_accept_count = DB::getStatement("SELECT COUNT(*) as count FROM table_reviews WHERE moderat = '0'");
        $sth_no_accept_count->execute();
        $rows_no_accept_count = $sth_no_accept_count->fetch();
//                echo "<tt><pre> - djflskdjf - ".print_r($rows_all_count['count'], true). "</pre></tt>";
        ?>
        <div id="block-content">
            <div id="block-parameters">

                <ul id="options-list">
                    <li>Отзывы</li>
                    <li><a id="select-links" href="#"><?php echo $sort_name; ?></a>
                        <ul id="list-links-sort">
                            <li><a href="reviews.php?sort=accept">Проверенные</a></li>
                            <li><a href="reviews.php?sort=no-accept">Не проверенные</a></li>
                        </ul>
                    </li>
                </ul>

            </div>
            <div id="block-info">
               <ul id="review-info-count">
                   <li>Всего отзывов - <strong><?php echo $rows_all_count['count']; ?></strong></li>
                   <li>Не проверенные - <strong><?php echo $rows_no_accept_count['count']; ?></strong></li>
               </ul>
            </div>

            <?php

    /**
     * Постраничная навигация
     */
    $pnumber = 4; // количество выводимых предметов
    $pageLink = 5; // количество ссылок слева и справа
    if( isset($_GET['page'])) {
        $page = intval($_GET['page']); // номер страницы
    } else {
        $page = 0;
    }
    $page   = intval($page);
    $sth_count2    = DB::getStatement("SELECT COUNT(*) as count FROM table_reviews");
    $sth_count2->execute();
    $rows   = $sth_count2->fetch();
    $total  = $rows['count']; // всего позиций
    $number = $total / $pnumber; // количество ссылок на странице
    if ($total % $pnumber != 0 ) $number++; // если не равно 0, то добавляем 1
    $number = intval($number); // и приводим к целому числу

    if (empty($page) || $page < 0 ) $page = 1; // номер страницы
    if ($page > $number) $page = $number; // если страница больше общего числа, то она и есть максимальная

    $start = $page * $pnumber - $pnumber; // с какого id выводить товар

    $queryStart = " LIMIT $start, $pnumber";
    if ($total > 0) {
        $sth_select = DB::getStatement("SELECT * FROM table_reviews, table_products WHERE table_products.products_id=table_reviews.products_id ORDER BY $sorting $queryStart");
        $sth_select->execute();
        $rows_select = $sth_select->fetchAll();
//        echo "<tt><pre> - djflskdjf - ".print_r($rows_select, true). "</pre></tt>";
        foreach ($rows_select as $row_select):
            if (isset($row_select['image']) && file_exists('../uploads_images/'.$row_select['image'])) {
                $img_path   = '../uploads_images/'.$row_select['image'];
                $max_width  = 150;
                $max_height = 150;
                list($width, $height) = getimagesize($img_path);
//                        echo "<tt><pre>".print_r($width, true). "</pre></tt>";
                $ratioh = $max_height / $height;
                $ratiow = $max_width / $width;
                $ratio  = min($ratioh, $ratiow);
                $width  = intval($ratio * $width);
                $height = intval($ratio * $height);
            } else {
                $img_path = "images/no-image-90.png";
                $width    = 100;
                $height   = 182;
            }

            if ($row_select['moderat'] == '0') {
                $link_accept = '<a class="green" href="reviews.php?id='.$row_select['reviews_id'].'&action=accept">Принять</a>  | ';
            } else {
                $link_accept = '';
            }

            echo '<div class="block-reviews">
                    <div class="block-title-img">
                        <p>'.$row_select['title'].'</p>
                        <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" />
                    </div>
                    <p class="author-date"><strong>'.$row_select["name"].'</strong>, '.$row_select["datetime"].'</p>
                    <div class="plus-minus">
                        <img src="images/plus16.png" /><p>'.$row_select["good_reviews"].'</p>
                        <img src="images/minus16.png" /><p>'.$row_select["bad_reviews"].'</p>
                    </div>

                    <p class="reviews-comment" >'.$row_select["comment"].'</p>
                    <p class="links-actions" align="right" >'.$link_accept.'<a class="delete" rel="reviews.php?id='.$row_select['reviews_id'].'&action=delete">Удалить</a></p>
            </div>';



        endforeach;
    }
//                        echo "<tt><pre> - djflskdjf - ".print_r($total, true). "</pre></tt>";
            $parameters = "&sort=$sort";
            echo '<div class="pstrnav"><ul>';
            echo pager( $page, $pageLink, $number, $total, $parameters);
            echo '</ul></div>';
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