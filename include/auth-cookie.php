<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 14/08/14
 * Time: 16:06
 */

/**
 * Авторизация
 * файл включен в каждую страницу
 * Если не установлена сессия или она не имеет индекса "авторизации" и куки установлен в режим запоминания
 * парсируем куки, выбирая логин и пароль
 * Из базы данных получаем пользователя
 * и сохраняем в сессию данные о нем
 */
if ((!isset($_SESSION['auth']) || $_SESSION['auth'] != 'yes_auth') && isset($_COOKIE['rememberme']) && $_COOKIE['rememberme']) {
    $str = $_COOKIE['rememberme'];
    $allLen = strlen($str);
    $loginLen = strpos($str, "+");
    $login = handleData(substr($str, 0, $loginLen));
    $pass = handleData(substr($str, $loginLen+1, $allLen));

    $sth = DB::getStatement('SELECT * FROM reg_user WHERE (login = ? OR email = ?) AND pass = ?');
    $sth->execute(array($login, $login, $pass));
    $rows = $sth->fetch();
    if (! empty($rows)) {
        session_start();
        $_SESSION['auth'] = 'yes_auth';
        $_SESSION['auth_pass'] = $rows['pass'];
        $_SESSION['auth_login'] = $rows['login'];
        $_SESSION['auth_surname'] = $rows['surname'];
        $_SESSION['auth_name'] = $rows['name'];
        $_SESSION['auth_patronymic'] = $rows['patronymic'];
        $_SESSION['auth_address'] = $rows['address'];
        $_SESSION['auth_phone'] = $rows['phone'];
        $_SESSION['auth_email'] = $rows['email'];
    }
}
?>