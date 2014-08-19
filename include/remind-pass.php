<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 14/08/14
 * Time: 17:31
 */

/**
 * Восстанавливаем забытый пароль
 */
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    define('myeshop', true);
    include('DB.php');
    include('../utility/handleData.php');
    include('../utility/funGenPass.php');
    include('../utility/sendMail.php');

    $email = handleData($_POST['email']); // получаем email

    if ($email != "") {
        if (preg_match('|^[-a-z0-9_\.]+\@[-a-z0-9_\.]+\.[a-z]{2,6}|i', $email) ) {

            $sth = DB::getStatement('SELECT email FROM reg_user WHERE email = ?');
            $sth->execute(array($email));
            $rows = $sth->fetch(); // если нашли поле по email
            if (!empty($rows)) {
                // создаем пароль
                $newpass = genPass();
                $pass = strrev(md5(strtolower($newpass)));
                $pass = "9nm2rv8q".$pass."2yo6z";

                // обновляем пароль в БД
                $sth2 = DB::getStatement('UPDATE reg_user SET pass=? WHERE email=?');
                $sth2->execute(array($pass, $email));

                // отправляем новый пароль
                sendMail('noreply@shop.ru',
                        $email,
                        'Новый пароль для сайта MyShop.ru',
                        'Ваш пароль: '.$newpass);
                echo 'yes';
            } else {
                echo "Указанный E-mail не найден!";
            }
        } else {
            echo "Укажите E-mail в верном формате!";
        }
    } else {
        echo "Укажите E-mail!";
    }
}

?>