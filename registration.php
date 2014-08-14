<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 12/08/14
 * Time: 16:38
 */

require_once('include/Exceptions.php');
require_once('include/DB.php');
require_once('utility/handleData.php');
require_once('utility/pager.php');
session_start();
include "include/auth-cookie.php";
try {
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Регистрация</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="TrackBar/jQuery/trackbar.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="js/jquery.migrate.js"></script>
        <script type="text/javascript" src="js/jcarouserllite_1.0.1.js"></script>
        <script type="text/javascript" src="js/jquery.cookie.js"></script>
        <script type="text/javascript" src="TrackBar/jQuery/jquery.trackbar.js"></script>
        <script type="text/javascript" src="js/shop-script.js"></script>
        <script type="text/javascript" src="js/jquery.form.js"></script>
        <script type="text/javascript" src="js/jquery.validate.js"></script>
        <script type="text/javascript" src="js/check-form.js"></script>

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

        <h2 class="h2-title" >Регистрация</h2>
        <form method="POST" id="form_reg" action="reg/handler_reg.php">
            <p id="reg_message"></p>
<!--            <p id="reg_message" class="reg_message_good">Вы успешно зарегистрированы!</p>-->
            <div id="block-form-registration">
                <ul id="form-registration">
                    <li>
                        <label for="reg_login">Логин</label>
                        <span class="star">*</span>
                        <input type="text" name="reg_login" id="reg_login" />
                    </li>
                    <li>
                        <label for="reg_pass">Пароль</label>
                        <span class="star">*</span>
                        <input type="text" name="reg_pass" id="reg_pass" />
                        <span id="genpass">Сгенерировать</span>
                    </li>
                    <li>
                        <label for="reg_surname">Фамилия</label>
                        <span class="star">*</span>
                        <input type="text" name="reg_surname" id="reg_surname" />
                    </li>
                    <li>
                        <label for="reg_name">Имя</label>
                        <span class="star">*</span>
                        <input type="text" name="reg_name" id="reg_name" />
                    </li>
                    <li>
                        <label for="reg_patronymic">Отчество</label>
                        <span class="star">*</span>
                        <input type="text" name="reg_patronymic" id="reg_patronymic" />
                    </li>
                    <li>
                        <label for="reg_email">Email</label>
                        <span class="star">*</span>
                        <input type="text" name="reg_email" id="reg_email" />
                    </li>
                    <li>
                        <label for="reg_phone">Мобильный телефон</label>
                        <span class="star">*</span>
                        <input type="text" name="reg_phone" id="reg_phone" />
                    </li>
                    <li>
                        <label for="reg_address">Адрес доставки</label>
                        <span class="star">*</span>
                        <input type="text" name="reg_address" id="reg_address" />
                    </li>
                    <li>
                        <div id="block-captcha">
                            <img src="reg/reg_captcha.php"
                        </div>
                        <input type="text" name="reg_captcha" id="reg_captcha" />
                        <p id="reloadcaptcha">Обновить</p>
                    </li>
                </ul>
            </div>
            <p align="right">
                <input type="submit" id="form_submit" value="Регистрация" name="reg_submit" />
            </p>
        </form>

    </div><!-- end block-content -->


    <?php include('include/block-footer.php'); ?>

    </div>

    </body>
    </html>
<?php
} catch(PDOException $ex) {
    throw new Exceptions($ex);
}
?>