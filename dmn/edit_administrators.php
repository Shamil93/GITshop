<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/08/14
 * Time: 22:12
 */
session_start();
if ($_SESSION['auth_admin'] == 'yes_auth') {

    define('myeshop', true);
    if (isset( $_GET['logout'])) {
        unset($_SESSION['auth_admin']);
        header ('Location: login.php');
    }
    $_SESSION['urlpage'] = "<a href='index.php'>Главная</a> \ <a href='edit_administrators.php'>Изменение администратора</a>";

    require_once ('include/DB.php');
    require_once ('utility/handleData.php');

    if (isset($_GET['id'])) {
        $id = handleData($_GET['id']);
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['submit_edit'])) {

            $error = array();

            if (isset($_POST['admin_login'])) {
                $login = handleData($_POST['admin_login']);
            } else {
                $error[] = "Укажите логин!";
            }

            if (isset($_POST['admin_pass'])) {
                $pass = handleData($_POST['admin_pass']);
                $pass = md5(handleData($pass));
                $pass = strrev($pass);
                $pass = strtolower("mb03foo51".$pass."qj2jjdp9");
                $pass = "pass='".$pass."'";
            }

            if (!isset($_POST['admin_fio'])) $error[] = "Укажите ФИО!";
            else $fio = handleData($_POST['admin_fio']);

            if (!isset($_POST['admin_role'])) $error[] = "Укажите должность!";
            else $role = handleData($_POST['admin_role']);

            if (!isset($_POST['admin_email'])) $error[] = "Укажите E-mail!";
            else $email = handleData($_POST['admin_email']);

            if (isset($_POST['admin_phone'])) $phone = handleData($_POST['admin_phone']);

            if (count($error)) {
                $_SESSION['message'] = "<p id='form-error'>".implode('<br />', $error)."</p>";
            } else {


                $sth_insert = DB::getStatement("UPDATE reg_admin SET login=?,{$pass},fio=?,role=?,email=?,
                                                                             phone=?,view_orders=?,accept_orders=?,
                                                                             delete_orders=?,add_tovar=?,edit_tovar=?,
                                                                             delete_tovar=?,accept_reviews=?,delete_reviews=?,
                                                                             view_clients=?,delete_clients=?,add_news=?,
                                                                             delete_news=?,add_category=?,delete_category=?,
                                                                             view_admin=?
                                                                WHERE id=?");
                $sth_insert->execute(array($login,
                                            $fio,
                                            $role,
                                            $email,
                                            $phone,
                                            $_POST['view_orders'],
                                            $_POST['accept_orders'],
                                            $_POST['delete_orders'],
                                            $_POST['add_tovar'],
                                            $_POST['edit_tovar'],
                                            $_POST['delete_tovar'],
                                            $_POST['accept_reviews'],
                                            $_POST['delete_reviews'],
                                            $_POST['view_clients'],
                                            $_POST['delete_clients'],
                                            $_POST['add_news'],
                                            $_POST['delete_news'],
                                            $_POST['add_category'],
                                            $_POST['delete_category'],
                                            $_POST['view_admin'],
                                            $id));
                $_SESSION['message'] = "<p id='form-success'>Пользователь успешно изменен!</p>";
            }
        }
    }


    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Панель управления - Изменение администратора</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="js/jquery_confirm/jquery.confirm/jquery.confirm.css" rel="stylesheet" type="text/css" />
        <link href="js/fancyBox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
        <!--    <link href="TrackBar/jQuery/trackbar.css" rel="stylesheet" type="text/css" />-->
        <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="js/jquery.migrate.js"></script>
        <script type="text/javascript" src="js/jquery_confirm/jquery.confirm/jquery.confirm.js"></script>
        <script type="text/javascript" src="js/fancyBox/source/jquery.fancybox.js" ></script>

        <!--    <script type="text/javascript" src="js/jcarouserllite_1.0.1.js"></script>-->
        <!--    <script type="text/javascript" src="js/jquery.cookie.js"></script>-->
        <!--    <script type="text/javascript" src="TrackBar/jQuery/jquery.trackbar.js"></script>-->
        <!--    <script type="text/javascript" src="js/jquery.TextChange.js"></script>-->
        <script type="text/javascript" src="js/admin-script.js"></script>
    </head>
    <body>
    <div id="block-body">
        <?php require_once ('include/block-header.php'); ?>

        <div id="block-content">
            <div id="block-parameters">
                <p id="title-page">Изменение администратора</p>
            </div>
            <?php
            if(isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }

            $sth_select_admin = DB::getStatement('SELECT * FROM reg_admin WHERE id=?');
            $sth_select_admin->execute(array($id));
            $rows_admin = $sth_select_admin->fetchAll();

            if (!empty($rows_admin)) {
                foreach ($rows_admin as $row_admin):
                    if ($row_admin["view_orders"] == 1) $view_orders = "checked";
                    if ($row_admin["accept_orders"] == 1) $accept_orders = "checked";
                    if ($row_admin["delete_orders"] == 1) $delete_orders = "checked";
                    if ($row_admin["add_tovar"] == 1) $add_tovar = "checked";
                    if ($row_admin["edit_tovar"] == 1) $edit_tovar = "checked";
                    if ($row_admin["delete_tovar"] == 1) $delete_tovar = "checked";
                    if ($row_admin["accept_reviews"] == 1) $accept_reviews = "checked";
                    if ($row_admin["delete_reviews"] == 1) $delete_reviews = "checked";
                    if ($row_admin["view_clients"] == 1) $view_clients = "checked";
                    if ($row_admin["delete_clients"] == 1) $delete_clients = "checked";
                    if ($row_admin["add_news"] == 1) $add_news = "checked";
                    if ($row_admin["delete_news"] == 1) $delete_news = "checked";
                    if ($row_admin["view_admin"] == 1) $view_admin = "checked";
                    if ($row_admin["add_category"] == 1) $add_category = "checked";
                    if ($row_admin["delete_category"] == 1) $delete_category = "checked";
                    ?>
                    <form id="form-info"  method="POST">
                        <ul id="info-admin">
                            <li><label>Логин</label><input type="text" name="admin_login" value="<?php echo $row_admin["login"] ?>" /></li>
                            <li><label>Пароль</label><input type="password" name="admin_pass" /></li>
                            <li><label>ФИО</label><input type="text" name="admin_fio" value="<?php echo $row_admin["fio"] ?>" /></li>
                            <li><label>Должность</label><input type="text" name="admin_role" value="<?php echo $row_admin["role"] ?>" /></li>
                            <li><label>E-mail</label><input type="text" name="admin_email" value="<?php echo $row_admin["email"] ?>" /></li>
                            <li><label>Телефон</label><input type="text" name="admin_phone" value="<?php echo $row_admin["phone"] ?>" /></li>
                        </ul>

                        <h3 id="title-privilege">Привилегии</h3>
                        <p id="link-privilege"><a id="select-all" >Выбрать все</a> | <a id="remove-all">Снять все</a></p>

                        <div class="block-privilege">
                            <ul class="privilege">
                                <li><h3>Заказы</h3></li>
                                <li>
                                    <input type="checkbox" name="view_orders" id="view_orders" value="1"  <?php echo $view_orders; ?> />
                                    <label for="view_orders" >Просмотр заказов.</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="accept_orders" id="accept_orders" value="1"   <?php echo $accept_orders; ?> />
                                    <label for="accept_orders" >Обработка заказов.</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="delete_orders" id="delete_orders" value="1"  <?php echo $delete_orders; ?>  />
                                    <label for="delete_orders" >Удаление заказов.</label>
                                </li>
                            </ul>

                            <ul class="privilege">
                                <li><h3>Товары</h3></li>
                                <li>
                                    <input type="checkbox" name="add_tovar" id="add_tovar" value="1"   <?php echo $add_tovar; ?> />
                                    <label for="add_tovar">Добавление товаров.</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="edit_tovar" id="edit_tovar" value="1"  <?php echo $edit_tovar; ?>  />
                                    <label for="edit_tovar">Изменение товаров.</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="delete_tovar" id="delete_tovar" value="1"   <?php echo $delete_tovar; ?> />
                                    <label for="delete_tovar">Удаление товаров.</label>
                                </li>
                            </ul>

                            <ul class="privilege">
                                <li><h3>Отзывы</h3></li>
                                <li>
                                    <input type="checkbox" name="accept_reviews" id="accept_reviews" value="1"  <?php echo $accept_reviews; ?>  />
                                    <label for="accept_reviews">Модерация отзывов.</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="delete_reviews" id="delete_reviews" value="1"  <?php echo $delete_reviews; ?>  />
                                    <label for="delete_reviews">Удаление отзывов.</label>
                                </li>
                            </ul>

                        </div>

                        <div class="block-privilege">
                            <ul class="privilege">
                                <li><h3>Клиенты</h3></li>
                                <li>
                                    <input type="checkbox" name="view_clients" id="view_clients" value="1"   <?php echo $view_clients; ?> />
                                    <label for="view_clients">Просмотр клиентов.</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="delete_clients" id="delete_clients" value="1"  <?php echo $delete_category; ?>  />
                                    <label for="delete_clients">Удаление клиентов.</label>
                                </li>
                            </ul>

                            <ul class="privilege">
                                <li><h3>Новости</h3></li>
                                <li>
                                    <input type="checkbox" name="add_news" id="add_news" value="1"  <?php echo $add_news; ?>  />
                                    <label for="add_news">Добавление новостей.</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="delete_news" id="delete_news" value="1"  <?php echo $delete_news; ?>  />
                                    <label for="delete_news">Удаление новостей.</label>
                                </li>
                            </ul>

                            <ul class="privilege">
                                <li><h3>Категории</h3></li>
                                <li>
                                    <input type="checkbox" name="add_category" id="add_category" value="1"  <?php echo $add_category; ?>  />
                                    <label for="add_category">Добавление категорий.</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="delete_category" id="delete_category" value="1"  <?php echo $delete_category; ?>  />
                                    <label for="delete_category">Удаление категорий.</label>
                                </li>
                            </ul>

                        </div>

                        <div id="block-privilege" >
                            <ul class="privilege">
                                <li><h3>Администраторы</h3></li>
                                <li>
                                    <input type="checkbox" name="view_admin" id="view_admin" value="1"  <?php echo $view_admin; ?>  />
                                    <label for="view_admin">Просмотр администраторов.</label>
                                </li>
                            </ul>
                        </div>

                        <p align="right"><input type="submit" id="submit_form" name="submit_edit" value="Изменить" /></p>
                    </form>
                <?php endforeach;

            }
            ?>



        </div>
    </div>
    </body>
    </html>
<?php
} else {
    header ('Location: login.php');
}
?>