<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 19/08/14
 * Time: 18:10
 */
define('myeshop', true);
require_once('include/Exceptions.php');
include "include/DB.php";
include ('utility/sendMail.php');
include ('utility/handleData.php');
session_start();
include "include/auth-cookie.php";

//unset($_SESSION['auth']);
//setcookie('rememberme','',0,'/');
try {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $_POST['feed_name'] = handleData($_POST['feed_name']);
        $_POST['feed_email'] = handleData($_POST['feed_email']);
        $_POST['feed_subject'] = handleData($_POST['feed_subject']);
        $_POST['feed_text'] = handleData($_POST['feed_text']);

        if (isset($_POST['send_message'])) {
            $error = array();
            if (!$_POST['feed_name']) $error[] = 'Укажите свое имя!';
            if (strlen($_POST['feed_email']) == "") {
                $error[] = 'Укажите E-mail!';
            } else if (!preg_match('|^[-a-z0-9_\.]+\@[-a-z0-9_\.]+\.[a-z]{2,6}$|i',trim($_POST['feed_email']))) {
                $error[] = 'Укажите корректный E-mail!';
            }
            if (!$_POST['feed_subject']) $error[] = 'Укажите тему письма!';
            if (!$_POST['feed_text']) $error[] = 'Укажите текст письма!';
            if (strtolower($_POST['reg_captcha']) != $_SESSION['img_captcha']) {
                $error[] = 'Неверный код с картинки!';
            }

            if (count($error)) {
                $_SESSION['message'] = '<p id="form-error">'.implode( '<br />' ,$error).'</p>';
            } else {
                sendMail($_POST['feed_email'],'zhalninpal@me.com',$_POST['feed_subject'],
                        'От: '.$_POST['feed_name'].'<br />'.$_POST['feed_text']);
                $_SESSION['message'] = '<p id="form-success">Ваше сообщение успешно отправлено!</p>';
                header('Refresh: 2; URL=index.php');
            }
        }
    }

    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Shop</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="TrackBar/jQuery/trackbar.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="js/jcarouserllite_1.0.1.js"></script>
        <script type="text/javascript" src="js/jquery.cookie.js"></script>
        <script type="text/javascript" src="TrackBar/jQuery/jquery.trackbar.js"></script>
        <script type="text/javascript" src="js/jquery.TextChange.js"></script>
        <script type="text/javascript" src="js/shop-script.js"></script>
    </head>
    <body>

    <div id="block-body">

        <!--    подключаем блок block-header-->
        <?php include('include/block-header.php'); ?>

        <div id="block-right">
            <?php include('include/block-category.php'); ?>
            <?php include('include/block-parameter.php'); ?>
            <?php include('include/block-news.php'); ?>
        </div>

        <div id="block-content">

    <?php
    if (isset($_SESSION['message']) && $_SESSION['message']) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    ?>
            <form method="POST">
                <div id="block-feedback">
                    <ul id="feedback">
                        <li><label>Ваше имя</label><input type="text" name="feed_name" value="<?php echo $_POST['feed_name']; ?>" /></li>
                        <li><label>Ваш E-mail</label><input type="text" name="feed_email" value="<?php echo $_POST['feed_email']; ?>"  /></li>
                        <li><label>Тема</label><input type="text" name="feed_subject" value="<?php echo $_POST['feed_subject']; ?>"  /></li>
                        <li><label>Текст сообщения</label><textarea name="feed_text" ><?php echo $_POST['feed_text']; ?></textarea></li>
                        <li><label for="reg_captcha">Защитный код</label>
                            <div id="block-captcha"><img src="reg/reg_captcha.php" />
                                <input type="text" name="reg_captcha" id="reg_captcha" />
                                <p id="reloadcaptcha">Обновить</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <p align="right"><input type="submit" name="send_message" id="form_submit" value="Отправить" /></p>
            </form>
<?php
} catch(PDOException $ex) {
    throw new Exceptions($ex);
}
?>