<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/08/14
 * Time: 21:48
 */
session_start();
if ($_SESSION['auth_admin'] == 'yes_auth') {

    define('myeshop', true);
    if (isset( $_GET['logout'])) {
        unset($_SESSION['auth_admin']);
        header ('Location: login.php');
    }
    $_SESSION['urlpage'] = "<a href='news.php'>Новости</a>";

    require_once ('include/DB.php');
    require_once ('utility/handleData.php');

    if (isset($_POST['submit_news'])) {
        if ($_SESSION['add_news'] == 1) {
            if ($_POST['news_title'] == "" || $_POST['news_text'] == "") {
                $message = "<p id='form-error'>Заполните все поля!</p>";
            } else {
                $data = date('Y-m-d H:i:s', time());
                $sth_insert = DB::getStatement('INSERT INTO news (title,text,date)
                                                    VALUES(?,?,?)');
                $sth_insert->execute(array($_POST['news_title'],
                                            $_POST['news_text'],
                                            $data));
                $message = "<p id='form-success'>Новость добавлена!</p>";

            }
        } else {
            $msgerror = 'У вас нет прав на добавление новостей!';
        }
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
            case 'delete':
                if ($_SESSION['delete_news'] == 1) {
                    $sth_delete = DB::getStatement('DELETE FROM news WHERE id = ?');
                    $sth_delete->execute(array($id));
                } else {
                    $msgerror = 'У вас нет прав на удаление новостей!';
                }
                break;
        }
    }

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Панель управления - Новости</title>
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
        <?php require_once ('include/block-header.php');
        $sth_select = DB::getStatement('SELECT COUNT(*) as count FROM news');
        $sth_select->execute();
        $row_count = $sth_select->fetch();
        ?>

        <div id="block-content">
            <div id="block-parameters">
                <p id="count-client">Новости - <strong><?php echo $row_count['count']; ?></strong></p>
                <p align="right" id="add-style"><a class="news" href="#news">Добавить новость</a></p>
            </div>

            <?php
            if (isset($msgerror)) echo '<p id="form-error" align="center">'.$msgerror.'</p>';
            if ($message != "") echo $message;

            $sth_news = DB::getStatement('SELECT * FROM news ORDER BY id DESC');
            $sth_news->execute();
            $rows = $sth_news->fetchAll();
            if(!empty($rows)) {
                foreach ($rows as $row) {

                    echo '<div class="block-news">
                    <h3>'.$row["title"].'</h3>
                    <span>'.$row["date"].'</span>
                    <p>'.$row["text"].'</p>
                    <p class="links-actions" align="right"><a class="delete" rel="news.php?id='.$row["id"].'&action=delete">Удалить</a></p>
                    </div>';
                }
            }

?>
            <div id="news">
                <form method="POST">
                    <div id="block-input">
                        <label>Заголовок <input type="text" name="news_title" /></label>
                        <label>Описание <textarea name="news_text" ></textarea></label>
                    </div>
                    <p align="right">
                        <input type="submit" name="submit_news" id="submit_news" value="Добавить" />
                    </p>
                </form>
            </div>
        </div><!-- end of block-content -->
    </div>
    </body>
    </html>
<?php
} else {
    header ('Location: login.php');
}
?>