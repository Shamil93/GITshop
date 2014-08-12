<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 12/08/14
 * Time: 20:15
 */

/**
 * Проверка наличия уже зарегистрированного пользователя
 * при обращении ajax
 */
// проверяем, как обратились к этому скрипту
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require_once ('../include/DB.php');
    require_once ('../utility/handleData.php');

    // обработка входных данных
    $login = handleData($_POST['reg_login']);

    $sth = DB::getStatement('SELECT login FROM reg_user WHERE login = ?');
    $sth->execute(array($login));
    $row = $sth->fetch();
    if (! empty($row)) { // если результат пустой
        echo 'false'; // возвращаем false
    } else {
        echo 'true'; // если не пустой, возвращаем true
    }
}
?>