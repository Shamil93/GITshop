<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/08/14
 * Time: 18:37
 */
session_start();
if ($_SESSION['auth_admin'] == 'yes_auth') {

    define('myeshop', true);
    if (isset( $_GET['logout'])) {
        unset($_SESSION['auth_admin']);
        header ('Location: login.php');
    }
    $_SESSION['urlpage'] = "<a href='index.php'>Главная</a> \ <a href='category.php'>Категории</a>";

    require_once ('include/DB.php');
    require_once ('utility/handleData.php');

    if ($_POST['submit_cat']) {
        if ($_SESSION['add_category']) {
            $error = array();

            if (!$_POST['cat_type']) $error[] = "Укажите тип товара!";
            if (!$_POST['cat_brand']) $error[] = "Укажите название категории!";

            if (count($error)) {
                $_SESSION['message'] = "<p id='form-error'>".implode('<br />', $error)."</p>";
            } else {
                $cat_type = handleData($_POST['cat_type']);
                $cat_brand = handleData($_POST['cat_brand']);

                $sth_insert = DB::getStatement('INSERT INTO category(type,brand)
                                                VALUES(?,?)');
                $sth_insert->execute(array($cat_type, $cat_brand));
                $_SESSION['message'] = "<p id='form-success'>Категория успешно добавлена!</p>";
            }
        } else {
            $msgerror = 'У вас нет прав на добавление категории!';
        }
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Панель управления - Категории</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="js/fancyBox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
        <!--    <link href="TrackBar/jQuery/trackbar.css" rel="stylesheet" type="text/css" />-->
            <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
        <!--    <script type="text/javascript" src="js/jcarouserllite_1.0.1.js"></script>-->
        <!--    <script type="text/javascript" src="js/jquery.cookie.js"></script>-->
        <!--    <script type="text/javascript" src="TrackBar/jQuery/jquery.trackbar.js"></script>-->
        <!--    <script type="text/javascript" src="js/jquery.TextChange.js"></script>-->
            <script type="text/javascript" src="js/jquery.migrate.js"></script>
            <script type="text/javascript" src="js/admin-script.js"></script>
        <script type="text/javascript" src="js/fancyBox/source/jquery.fancybox.js" ></script>
    </head>
    <body>
    <div id="block-body">
        <?php require_once ('include/block-header.php') ?>
        <div id="block-content">
            <div id="block-parameters">
                <p id="title-page">Категории</p>
            </div>
            <?php
            if (isset($msgerror)) echo '<p id="form-error" align="center">'.$msgerror.'</p>';
            if(isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }
            ?>
            <form method="POST">
                <ul id="cat_products">
                    <li>
                        <label>Категории</label>
                        <div>
                            <?php if ($_SESSION['delete_category'] == 1) {
                                        echo '<a class="delete-cat">Удалить</a>';
                            }
                            ?>
                        </div>
                        <select name="cat_type" id="cat_type" size="10" >
                            <?php
                            $sth_select = DB::getStatement('SELECT * FROM category ORDER BY type DESC');
                            $sth_select->execute();
                            $rows = $sth_select->fetchAll();
                            if(!empty($rows)) {
                                foreach ($rows as $row) {
                                    echo '<option value="'.$row["id"].'" >'.$row["type"].' - '.$row["brand"].'</option>';
                                }

                            }
                            ?>
                        </select>
                    </li>
                    <li>
                        <label>Тип товара</label>
                        <input type="text" name="cat_type" />
                    </li>
                    <li>
                        <label>Бренд</label>
                        <input type="text" name="cat_brand" />
                    </li>
                </ul>
                <p align="right"><input type="submit" name="submit_cat" id="submit_form" value="Добавить категорию" /></p>
            </form>
        </div>
    </div>
    </body>
    </html>
<?php
} else {
    header ('Location: login.php');
}
?>