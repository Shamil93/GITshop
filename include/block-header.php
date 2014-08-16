<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/08/14
 * Time: 12:09
 */
//session_start();
// получаем переменную с поиском
if (isset( $_GET['q'])) {
    $search = handleData($_GET['q']);
    $searchLike = "LIKE '%".$search."%'";
    $searchQ = "&q=".$search;
} else {
    $search = '';
    $searchLike = '';
    $searchQ = "&q=";
}
?>
<!--Основной верхний блок-->
<div id="block-header">
<!--    Верхний блок с навигацией-->
    <div id="header-top-block">
<!--        Список с навигацией-->
        <ul id="header-top-menu">
            <li>Ваш город - <span>Санкт-Петербург</span></li>
            <li><a href="about.php">О нас</a></li>
            <li><a href="shops.php">Магазины</a></li>
            <li><a href="contacts.php">Контакты</a></li>
        </ul>
<!--        Вход и регистрация-->
        <?php

        if ((isset($_SESSION['auth'])) && $_SESSION['auth'] == 'yes_auth') {
            echo '<p id="auth-user-info" align="right"><img src="images/user.png" />Здравствуйте, '.$_SESSION["auth_name"].'! </p>';

        } else {
            echo '<p id="reg-auth-title" align="right"><a href="#" id="top-auth" class="top-auth">Вход</a><a class="top-auth-reg" href="registration.php">Регистрация</a></p>';
        }
        ?>
        <div id="block-top-auth">
            <div class="corner"></div>
            <form method="POST">
                <ul id="input-email-pass">
                    <h3>Вход</h3>
                    <p id="message-auth">Неверный Логин и(или) Пароль!</p>
                    <li><input type="text" id="auth-login" placeholder="Логин или Email" /></li>
                    <li><input type="password" id="auth-pass" placeholder="Пароль"/><span id="button-pass-show-hide" class="pass-show"></span></li>
                    <ul id="list-auth">
                        <li><input type="checkbox" name="rememberme" id="rememberme" /><label for="rememberme">Запомнить меня</label> </li>
                        <li><a id="remindpass" href="#">Забыли пароль?</a></li>
                    </ul>
                    <p align="right" id="button-auth"><a>Вход</a></p>
                    <p align="right" class="auth-loading"><img src="images/loading.gif" /></p>
                </ul>
            </form>
            <div id="block-remind">
                <h3>Восстановление<br /> пароля</h3>
                <p id="message-remind" class="message-remind-success"></p>
                <input type="text" placeholder="Ваш E-mail" id="remind-email" />
                <p align="right" id="button-remind"><a>Готово</a></p>
                <p align="right" class="auth-loading"><img src="images/loading.gif" /></p>
                <p id="prev-auth">Назад</p>
            </div>
        </div>
    </div>
<!--    Линия под главным блоком-->
    <div id="top-line"></div>

    <div id="block-user">
        <div class="corner2"></div>
        <ul>
            <li><img src="images/user_info.png" /><a href="profile.php">Профиль</a></li>
            <li><img src="images/logout.png" /><a id="logout">Выход</a></li>
        </ul>
    </div>

<!--    Логотип-->
    <img id="img-logo" src="images/logo.png" />
<!--    Информационный блок-->
    <div id="personal-info">
        <p align="right">Звонок бесплатный</p>
        <h3 align="right">8 (921) 325-20-99</h3>
        <img src="images/phone-icon.png" />
        <p align="right">Режим работы:</p>
        <p align="right">Будние дни: с 9:00 до 18:00</p>
        <p align="right">Суббота, Воскресенье - выходные </p>
        <img src="images/time-icon.png" />
    </div>

    <div id="block-search">
        <form action="search.php?q=" method="GET">
            <span></span>
            <input type="text" id="input-search" name="q" placeholder="Поиск среди более 100 000 товаров" value="<?php echo $search; ?>"/>
            <input type="submit" id="button-search" value="Поиск"/>
        </form>
        <ul id="result-search"></ul>
    </div>
</div>

<div id="top-menu">
    <ul>
        <li><img src="images/shop.png" /><a href="index.php">Главная</a></li>
        <li><img src="images/new-32.png"/><a href="view_icetopper.php?go=news">Новинки</a></li>
        <li><img src="images/bestprice-32.png"/><a href="view_icetopper.php?go=leaders">Лидеры продаж</a></li>
        <li><img src="images/sale-32.png"/><a href="view_icetopper.php?go=sale">Распродажа</a></li>
    </ul>

    <p align="right" id="block-basket"><img src="images/cart-icon.png" /><a href="">Корзина пуста</a></p>

    <div id="nav-line"></div>
</div>