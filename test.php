<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    session_start();
    define('myeshop', true);
    include("../include/db_connect.php");
    include("../functions/functions.php");

    $error = array();

    $login = iconv("UTF-8", "cp1251",strtolower(clear_string($_POST['reg_login'])));
    $pass = iconv("UTF-8", "cp1251",strtolower(clear_string($_POST['reg_pass'])));
    $surname = iconv("UTF-8", "cp1251",clear_string($_POST['reg_surname']));

    $name = iconv("UTF-8", "cp1251",clear_string($_POST['reg_name']));
    $patronymic = iconv("UTF-8", "cp1251",clear_string($_POST['reg_patronymic']));
    $email = iconv("UTF-8", "cp1251",clear_string($_POST['reg_email']));

    $phone = iconv("UTF-8", "cp1251",clear_string($_POST['reg_phone']));
    $address = iconv("UTF-8", "cp1251",clear_string($_POST['reg_address']));


    if (strlen($login) < 5 or strlen($login) > 15)
    {
        $error[] = "Ëîãèí äîëæåí áûòü îò 5 äî 15 ñèìâîëîâ!";
    }
    else
    {
        $result = mysql_query("SELECT login FROM reg_user WHERE login = '$login'",$link);
        If (mysql_num_rows($result) > 0)
        {
            $error[] = "Ëîãèí çàíÿò!";
        }

    }

    if (strlen($pass) < 7 or strlen($pass) > 15) $error[] = "Óêàæèòå ïàðîëü îò 7 äî 15 ñèìâîëîâ!";
    if (strlen($surname) < 3 or strlen($surname) > 20) $error[] = "Óêàæèòå Ôàìèëèþ îò 3 äî 20 ñèìâîëîâ!";
    if (strlen($name) < 3 or strlen($name) > 15) $error[] = "Óêàæèòå Èìÿ îò 3 äî 15 ñèìâîëîâ!";
    if (strlen($patronymic) < 3 or strlen($patronymic) > 25) $error[] = "Óêàæèòå Îò÷åñòâî îò 3 äî 25 ñèìâîëîâ!";
    if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i",trim($email))) $error[] = "Óêàæèòå êîððåêòíûé email!";
    if (!$phone) $error[] = "Óêàæèòå íîìåð òåëåôîíà!";
    if (!$address) $error[] = "Íåîáõîäèìî óêàçàòü àäðåñ äîñòàâêè!";

    if($_SESSION['img_captcha'] != strtolower($_POST['reg_captcha'])) $error[] = "Íåâåðíûé êîä ñ êàðòèíêè!";
    unset($_SESSION['img_captcha']);

    if (count($error))
    {

        echo implode('<br />',$error);

    }else
    {
        $pass   = md5($pass);
        $pass   = strrev($pass);
        $pass   = "9nm2rv8q".$pass."2yo6z";

        $ip = $_SERVER['REMOTE_ADDR'];

        mysql_query("	INSERT INTO reg_user(login,pass,surname,name,patronymic,email,phone,address,datetime,ip)
						VALUES(
						
							'".$login."',
							'".$pass."',
							'".$surname."',
							'".$name."',
							'".$patronymic."',
                            '".$email."',
                            '".$phone."',
                            '".$address."',
                            NOW(),
                            '".$ip."'							
						)",$link);

        echo 'true';
    }


}
?>