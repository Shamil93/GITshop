<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/08/14
 * Time: 21:14
 */
session_start();
if ($_SESSION['auth_admin'] == 'yes_auth') {

    define('myeshop', true);
    if (isset( $_GET['logout'])) {
        unset($_SESSION['auth_admin']);
        header ('Location: login.php');
    }
    $_SESSION['urlpage'] = "<a href='administrators.php'>Администраторы</a>";

    require_once ('include/DB.php');
    require_once ('utility/handleData.php');

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
            case 'delete':
                $sth_delete = DB::getStatement('DELETE FROM reg_admin WHERE id = ?');
                $sth_delete->execute(array($id));
                break;
        }
    }

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Панель управления - Администраторы</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="js/jquery_confirm/jquery.confirm/jquery.confirm.css" rel="stylesheet" type="text/css" />
        <link href="js/fancyBox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
        <!--    <link href="TrackBar/jQuery/trackbar.css" rel="stylesheet" type="text/css" />-->
        <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="js/jquery.migrate.js"></script>
        <script type="text/javascript" src="js/jquery_confirm/jquery.confirm/jquery.confirm.js"></script>
        <script type="text/javascript" src="js/fancyBox/source/jquery.fancybox.js" ></script>
        <!--    <script type="text/javascript" src="js/jcarouserllite_1.0.1.js"></script>-->
        <!--    <script type="text/javascript" src="js/jquery.cookie.js"></script>-->
        <!--    <script type="text/javascript" src="TrackBar/jQuery/jquery.trackbar.js"></script>-->
        <!--    <script type="text/javascript" src="js/jquery.TextChange.js"></script>-->
        <script type="text/javascript" src="js/admin-script.js"></script>
    </head>
    <body>
    <div id="block-body">
        <?php require_once ('include/block-header.php'); ?>

        <div id="block-content">
            <div id="block-parameters">
                <p id="title-page">Администраторы</p>
                <p align="right" id="add-style"><a href="add_administrators.php">Добавить админа</a></p>
            </div>

            <?php
            $sth_select_administrators = DB::getStatement('SELECT * FROM reg_admin ORDER BY id DESC');
            $sth_select_administrators->execute();
            $rows_administrators = $sth_select_administrators->fetchAll();
            if (!empty($rows_administrators)) {
                foreach ($rows_administrators as $row_administrators) {
                    echo '<ul id="list-admin">
                        <li>
                            <h3>'.$row_administrators["fio"].'</h3>
                            <p><strong>Должность</strong> - '.$row_administrators["role"].'</p>
                            <p><strong>E-mail</strong> - '.$row_administrators["email"].'</p>
                            <p><strong>Телефон</strong> - '.$row_administrators["phone"].'</p>
                            <p class="links-actions" align="right">
                                <a class="green" href="edit_administrators.php?id='.$row_administrators['id'].'">Изменить</a>
                                <a class="delete" rel="administrators.php?id='.$row_administrators['id'].'&action=delete">Удалить</a>
                            </p>
                        </li>
                    </ul>';
                }

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