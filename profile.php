<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 14/08/14
 * Time: 19:46
 */
session_start();
define('myeshop', true);
if (isset($_SESSION['auth']) && $_SESSION['auth'] == 'yes_auth') {
    require_once('include/Exceptions.php');
    require_once('utility/pager.php');
    include "include/DB.php";
    include ('utility/handleData.php');


    //unset($_SESSION['auth']);
    //setcookie('rememberme','',0,'/');
    try {

        if (isset($_POST['save_submit']) && $_POST['save_submit']) {
            $_POST['info_surname'] = handleData($_POST['info_surname']);
            $_POST['info_name'] = handleData($_POST['info_name']);
            $_POST['info_patronymic'] = handleData($_POST['info_patronymic']);
            $_POST['info_address'] = handleData($_POST['info_address']);
            $_POST['info_phone'] = handleData($_POST['info_phone']);
            $_POST['info_email'] = handleData($_POST['info_email']);

            $error = array();

            $pass = strrev(md5(strtolower($_POST['info_pass'])));
            $pass = '9nm2rv8q'.$pass.'2yo6z';

            if ($_SESSION['auth_pass'] != $pass) {
                $error[] = 'Неверный текущий пароль!';
            } else {
                if ($_POST['info_new_pass'] != ""){
                    if (strlen($_POST['info_new_pass']) < 7 || strlen($_POST['info_new_pass']) > 15) {
                        $error[] = 'Укажите новый пароль от 7 до 15 символов!';
                    } else {
                        $newpass = strrev(md5((strtolower($_POST['info_new_pass']))));
                        $newpass = '9nm2rv8q'.$newpass.'2yo6z';
//                        $newpassquery = "pass='".$newpass."',";
                    }
                }

                if (strlen($_POST['info_surname']) < 3 || strlen($_POST['info_surname']) > 15) {
                    $error[] = 'Укажите фамилию от 3 до 15 символов!';
                }
                if (strlen($_POST['info_name']) < 3 || strlen($_POST['info_name']) > 15) {
                    $error[] = 'Укажите имя от 3 до 15 символов!';
                }
                if (strlen($_POST['info_patronymic']) < 3 || strlen($_POST['info_patronymic']) > 25) {
                    $error[] = 'Укажите отчество от 3 до 25 символов!';
                }
                if (strlen($_POST['info_address']) == "") {
                    $error[] = 'Укажите адрес доставки!';
                }
                if (strlen($_POST['info_phone']) == "") {
                    $error[] = 'Укажите номер телефона!';
                }
                if (strlen($_POST['info_email']) == "") {
                    $error[] = 'Укажите E-mail!';
                } else if (!preg_match('|^[-a-z0-9_\.]+\@[-a-z0-9_\.]+\.[a-z]{2,6}$|i',$_POST['info_email'])) {
                    $error[] = 'Укажите корректный E-mail!';
                }
            }
            if (count($error)) {
                $_SESSION['msg'] = "<p align='left' id='form-error'>".implode('<br />', $error)."</p>";
            } else {
                $_SESSION['msg'] = "<p align='left' id='form-success'>Данные успешно сохранены!</p>";
                if (! empty($newpass)) {
                    $sth = DB::getStatement('UPDATE reg_user
                                              SET pass=?,surname=?,name=?,patronymic=?,email=?,phone=?,address=?
                                              WHERE login=?');
                    $sth->execute(array($newpass,$_POST['info_surname'],$_POST['info_name'],$_POST['info_patronymic'],$_POST['info_email'],
                                        $_POST['info_phone'],$_POST['info_address'],$_SESSION['auth_login']));
//                    $rows = $sth->fetch();
                    $_SESSION['auth_pass'] = $newpass;
                } else {
                    $sth = DB::getStatement('UPDATE reg_user
                                              SET surname=?,name=?,patronymic=?,email=?,phone=?,address=?
                                              WHERE login=?');
                    $sth->execute(array($_POST['info_surname'],$_POST['info_name'],$_POST['info_patronymic'],$_POST['info_email'],
                        $_POST['info_phone'],$_POST['info_address'],$_SESSION['auth_login']));
//                    $rows = $sth->fetch();
                }
                $_SESSION['auth_surname'] = $_POST['info_surname'];
                $_SESSION['auth_name'] = $_POST['info_name'];
                $_SESSION['auth_patronymic'] = $_POST['info_patronymic'];
                $_SESSION['auth_address'] = $_POST['info_address'];
                $_SESSION['auth_phone'] = $_POST['info_phone'];
                $_SESSION['auth_email'] = $_POST['info_email'];
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

                <h3 class="h3-title" >Изменение профиля</h3>
                <?php
                    if (isset($_SESSION['msg']) && $_SESSION['msg']) {
                        echo $_SESSION['msg'];
                        unset($_SESSION['msg']);
                    }
                ?>
                <form method="POST" id="form_profile">
                        <ul id="info-profile">
                            <li>
                                <label for="info_pass">Текущий пароль</label>
                                <span class="star">*</span>
                                <input type="text" name="info_pass" id="info_pass" />
                            </li>
                            <li>
                                <label for="info_new_pass">Новый пароль</label>
                                <span class="star"></span>
                                <input type="text" name="info_new_pass" id="info_new_pass" />
                            </li>
                            <li>
                                <label for="info_surname">Фамилия</label>
                                <span class="star">*</span>
                                <input type="text" name="info_surname" id="info_surname" value="<?php echo $_SESSION['auth_surname']; ?>" />
                            </li>
                            <li>
                                <label for="info_name">Имя</label>
                                <span class="star">*</span>
                                <input type="text" name="info_name" id="info_name" value="<?php echo $_SESSION['auth_name']; ?>"  />
                            </li>
                            <li>
                                <label for="info_patronymic">Отчество</label>
                                <span class="star">*</span>
                                <input type="text" name="info_patronymic" id="info_patronymic" value="<?php echo $_SESSION['auth_patronymic']; ?>"  />
                            </li>
                            <li>
                                <label for="info_email">Email</label>
                                <span class="star">*</span>
                                <input type="text" name="info_email" id="info_email" value="<?php echo $_SESSION['auth_email']; ?>"  />
                            </li>
                            <li>
                                <label for="info_phone">Мобильный телефон</label>
                                <span class="star">*</span>
                                <input type="text" name="info_phone" id="info_phone" value="<?php echo $_SESSION['auth_phone']; ?>"  />
                            </li>
                            <li>
                                <label for="info_address">Адрес доставки</label>
                                <span class="star">*</span>
                                <textarea id="info_address" name="info_address"><?php echo $_SESSION['auth_address']; ?></textarea>
                            </li>
                        </ul>
                    <p align="right">
                        <input type="submit" id="form_submit" value="Сохранить" name="save_submit" />
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
} else {
    header('Location: index.php');
}
?>