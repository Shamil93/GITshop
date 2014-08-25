<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 22/08/14
 * Time: 11:27
 */
session_start();
if ($_SESSION['auth_admin'] == 'yes_auth') {

    define('myeshop', true);
    if (isset( $_GET['logout'])) {
        unset($_SESSION['auth_admin']);
        header ('Location: login.php');
    }
    $_SESSION['urlpage'] = "<a href='index.php'>Главная</a> \ <a href='tovar.php'>Товары</a> ";

    require_once ('include/DB.php');
    require_once ('utility/pager.php');
    require_once ('utility/handleData.php');

    if(isset($_GET['type'])) {
        $type = $_GET['type'];
    }
    if(isset($_GET['cat'])) {
        $cat = $_GET['cat'];
        switch($cat) {
            case 'all':
                $cat_name = 'Все товары';
                $url = "cat=all&";
                $cat = "";
                break;
            case 'mobile':
                $cat_name = 'Мобильные телефоны';
                $url = "&cat=mobile&";
                $cat = "WHERE type_tovara='mobile'";
                break;
            case 'notebook':
                $cat_name = 'Ноутбуки';
                $url = "&cat=notebook&";
                $cat = "WHERE type_tovara='notebook'";
                break;
            case 'notepad':
                $cat_name = 'Планшеты';
                $url = "&cat=notepad&";
                $cat = "WHERE type_tovara='notepad'";
                break;
            default:
                $cat_name = $cat;
                $url = "&type=".handleData($type)."&cat=".handleData($cat)."&";
                $cat = "WHERE type_tovara='".handleData($type)."' AND brand='".handleData($cat)."'";
                break;
        }
    } else {
        $cat_name = 'Все товары';
        $url = "";
        $cat = "";
    }


    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        switch ($action) {
            case 'delete':
                $sth_delete = DB::getStatement('DELETE FROM table_products WHERE products_id = ?');
                $sth_delete->execute(array($id));
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
        $sth_count = DB::getStatement("SELECT COUNT(*) as count FROM table_products $cat");
        $sth_count->execute();
        $rows = $sth_count->fetch();
//        echo "<tt><pre> - djflskdjf - ".print_r($rows['count'], true). "</pre></tt>";
        ?>
        <div id="block-content">
            <div id="block-parameters">

                <ul id="options-list">
                    <li>Товары</li>
                    <li><a id="select-links" href="#"><?php echo $cat_name; ?></a>
                    <div id="list-links">
                        <ul>
                            <li><a href="tovar.php?cat=all"><strong>Все товары</strong></a></li>
                            <li><a href="tovar.php?cat=mobile"><strong>Телефоны</strong></a></li>
                            <?php
                            $sth_mobile = DB::getStatement("SELECT * FROM category WHERE type='mobile'");
                            $sth_mobile->execute();
                            $row_mobile = $sth_mobile->fetchAll();
                            foreach ($row_mobile as $mobile):?>

                            <li><a href="tovar.php?type=<?php echo $mobile['type']; ?>&cat=<?php echo $mobile['brand']; ?>"><?php echo $mobile['brand']; ?></a></li>

                            <?php endforeach; ?>
                        </ul>
                        <ul>
                            <li><a href="tovar.php?cat=notebook"><strong>Ноутбуки</strong></a></li>
                            <?php
                            $sth_notebook = DB::getStatement("SELECT * FROM category WHERE type='notebook'");
                            $sth_notebook->execute();
                            $row_notebook = $sth_notebook->fetchAll();
                            foreach ($row_notebook as $notebook):?>

                                <li><a href="tovar.php?type=<?php echo $notebook['type']; ?>&cat=<?php echo $notebook['brand']; ?>"><?php echo $notebook['brand']; ?></a></li>

                            <?php endforeach; ?>
                        </ul>
                        <ul>
                            <li><a href="tovar.php?cat=notepad"><strong>Планшеты</strong></a></li>
                            <?php
                            $sth_notepad = DB::getStatement("SELECT * FROM category WHERE type='notepad'");
                            $sth_notepad->execute();
                            $row_notepad = $sth_notepad->fetchAll();
                            foreach ($row_notepad as $notepad):?>

                                <li><a href="tovar.php?type=<?php echo $notepad['type']; ?>&cat=<?php echo $notepad['brand']; ?>"><?php echo $notepad['brand']; ?></a></li>

                            <?php endforeach; ?>
                        </ul>

                    </div> </li>

                </ul>

            </div>
            <div id="block-info">
                <p id="count-style">Всего товаров - <strong><?php echo $rows['count']; ?></strong></p>
                <p align="right" id="add-style"><a href="add_product.php">Добавить товар</a></p>
            </div>
            <ul id="block-tovar">
        <?php
            /**
            * Постраничная навигация
            */
            $pnumber = 8; // количество выводимых предметов
            $pageLink = 5; // количество ссылок слева и справа
            if( isset($_GET['page'])) {
                $page = intval($_GET['page']); // номер страницы
            } else {
                $page = 0;
            }
            $page   = intval($page);
            $sth_count2    = DB::getStatement("SELECT COUNT(*) as count FROM table_products $cat");
            $sth_count2->execute();
            $rows   = $sth_count2->fetch();
            $total  = $rows['count']; // всего позиций
            $number = $total / $pnumber; // количество ссылок на странице
            if ($total / $pnumber != 0 ) $number++; // если не равно 0, то добавляем 1
            $number = intval($number); // и приводим к целому числу

            if (empty($page) || $page < 0 ) $page = 1; // номер страницы
            if ($page > $number) $page = $number; // если страница больше общего числа, то она и есть максимальная

            $start = $page * $pnumber - $pnumber; // с какого id выводить товар

            $queryStart = " LIMIT $start, $pnumber";
            if ($total > 0) {


                $sth_select = DB::getStatement("SELECT * FROM table_products $cat ORDER BY products_id DESC $queryStart");
                $sth_select->execute();
                $rows = $sth_select->fetchAll();
                foreach ($rows as $row):
                    if (isset($row['image']) && file_exists('../uploads_images/'.$row['image'])) {
                        $img_path   = '../uploads_images/'.$row['image'];
                        $max_width  = 160;
                        $max_height = 160;
                        list($width, $height) = getimagesize($img_path);
//                        echo "<tt><pre>".print_r($width, true). "</pre></tt>";
                        $ratioh = $max_height / $height;
                        $ratiow = $max_width / $width;
                        $ratio  = min($ratioh, $ratiow);
                        $width  = intval($ratio * $width);
                        $height = intval($ratio * $height);
                    } else {
                        $img_path = "images/no-image-90.png";
                        $width    = 90;
                        $height   = 164;
                    }

                    echo '<li><p>'.$row['title'].'</p>
                        <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'"/>
                        <p align="center" class="link-action">
                            <a class="green" href="edit_product.php?id='.$row["products_id"].'">Изменить</a> | <a rel="tovar.php?'.$url.'id='.$row['products_id'].'&action=delete" class="delete">Удалить</a>
                        </p></li>';

                endforeach;
            }
        ?>

            </ul>
            <div id="footerfix"></div>
            <?php
                $parameters = $url;
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