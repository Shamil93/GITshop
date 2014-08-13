<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/08/14
 * Time: 12:09
 */
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
        <p id="reg-auth-title" align="right"><a href="#" id="top-auth" class="top-auth">Вход</a><a class="top-auth-reg" href="registration.php">Регистрация</a></p>
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
        </div>
    </div>
<!--    Линия под главным блоком-->
    <div id="top-line"></div>
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
            <input type="text" id="input-search" name="q" placeholder="Поиск среди более 100 000 товаров"/>
            <input type="submit" id="button-search" value="Поиск"/>
        </form>
    </div>
</div>

<div id="top-menu">
    <ul>
        <li><img src="images/shop.png" /><a href="index.php">Главная</a></li>
        <li><img src="images/new-32.png"/><a href="">Новинки</a></li>
        <li><img src="images/bestprice-32.png"/><a href="">Лидеры продаж</a></li>
        <li><img src="images/sale-32.png"/><a href="">Распродажа</a></li>
    </ul>

    <p align="right" id="block-basket"><img src="images/cart-icon.png" /><a href="">Корзина пуста</a></p>

    <div id="nav-line"></div>
</div>