<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/08/14
 * Time: 19:42
 */
session_start();
if ($_SESSION['auth_admin'] == 'yes_auth') {

    define('myeshop', true);
    if (isset( $_GET['logout'])) {
        unset($_SESSION['auth_admin']);
        header ('Location: login.php');
    }
    $_SESSION['urlpage'] = "<a href='clients.php'>Клиенты</a>";

    require_once ('include/DB.php');
    require_once ('utility/handleData.php');

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
            case 'delete':
                $sth_delete = DB::getStatement('DELETE FROM reg_user WHERE id = ?');
                $sth_delete->execute(array($id));
                break;
        }
    }

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Панель управления - Клиенты</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="js/jquery_confirm/jquery.confirm/jquery.confirm.css" rel="stylesheet" type="text/css" />
        <!--    <link href="TrackBar/jQuery/trackbar.css" rel="stylesheet" type="text/css" />-->
        <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="js/jquery.migrate.js"></script>
        <script type="text/javascript" src="js/jquery_confirm/jquery.confirm/jquery.confirm.js"></script>
        <!--    <script type="text/javascript" src="js/jcarouserllite_1.0.1.js"></script>-->
        <!--    <script type="text/javascript" src="js/jquery.cookie.js"></script>-->
        <!--    <script type="text/javascript" src="TrackBar/jQuery/jquery.trackbar.js"></script>-->
        <!--    <script type="text/javascript" src="js/jquery.TextChange.js"></script>-->
            <script type="text/javascript" src="js/admin-script.js"></script>
    </head>
    <body>
    <div id="block-body">
        <?php require_once ('include/block-header.php');
        $sth_select = DB::getStatement('SELECT COUNT(*) as count FROM reg_user');
        $sth_select->execute();
        $row_count = $sth_select->fetch();
        ?>

        <div id="block-content">
            <div id="block-parameters">
                <p id="count-client">Клиенты - <strong><?php echo $row_count['count']; ?></strong></p>
            </div>

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
//            $sth_count2    = DB::getStatement("SELECT COUNT(*) as count FROM table_products $cat");
//            $sth_count2->execute();
//            $rows   = $sth_count2->fetch();
            $total  = $row_count['count']; // всего позиций
            $number = $total / $pnumber; // количество ссылок на странице
            if ($total % $pnumber != 0 ) $number++; // если не равно 0, то добавляем 1
            $number = intval($number); // и приводим к целому числу

            if (empty($page) || $page < 0 ) $page = 1; // номер страницы
            if ($page > $number) $page = $number; // если страница больше общего числа, то она и есть максимальная

            $start = $page * $pnumber - $pnumber; // с какого id выводить товар

            $queryStart = " LIMIT $start, $pnumber";
            if ($total > 0) {


                $sth_select = DB::getStatement("SELECT * FROM reg_user  ORDER BY id DESC $queryStart");
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

                    echo '<div class="block-clients">
                            <p class="client-datetime">'.$row['datetime'].'</p>
                            <p class="client-email"><strong>'.$row['email'].'</strong></p>
                            <p class="client-links"><a class="delete" rel="clients.php?id='.$row["id"].'&action=delete" >Удалить</a></p>

                            <ul>
                                <li><strong>E-Mail</strong> - '.$row['email'].'</li>
                                <li><strong>ФИО</strong> - '.$row['surname'].' '.$row['name'].' '.$row['patronymic'].'</li>
                                <li><strong>Адрес</strong> - '.$row['address'].'</li>
                                <li><strong>Телефон</strong> - '.$row['phone'].'</li>
                                <li><strong>IP</strong> - '.$row['ip'].'</li>
                                <li><strong>Дата регистрации</strong> - '.$row['datetime'].'</li>
                            </ul>
                         </div>';

                endforeach;
            }
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