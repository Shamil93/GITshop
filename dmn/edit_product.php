<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/08/14
 * Time: 08:57
 */
session_start();
if ($_SESSION['auth_admin'] == 'yes_auth') {

    define('myeshop', true);
    if (isset( $_GET['logout'])) {
        unset($_SESSION['auth_admin']);
        header ('Location: login.php');
    }
    $_SESSION['urlpage'] = "<a href='index.php'>Главная</a> \ <a href='tovar.php'>Товары</a> \ <a>Изменение товара</a> ";

    require_once ('include/DB.php');
    require_once ('utility/pager.php');
    require_once ('utility/handleData.php');

    if (isset($_GET['id'])) {
        $id = handleData($_GET['id']);
    }

    if ($_POST['submit_save']) {
        $error = array();

        if (!isset($_POST['form_title'])) {
            $error[] = "Укажите название товара!";
        }
        if (!isset($_POST['form_price'])) {
            $error[] = "Укажите цену!";
        }
        if (!isset($_POST['form_category'])) {
            $error[] = "Укажите категорию!";
        } else {
            $sth_category2 = DB::getStatement('SELECT * FROM category WHERE id=?');
            $sth_category2->execute(array($_POST['form_category']));
            $rows_category2 = $sth_category2->fetch();
            $selectbrand = $rows_category2['brand'];
//            echo "<tt><pre> - djflskdjf - ".print_r($rows_category2['brand'], true). "</pre></tt>";
        }
        if (isset($_POST['chk_visible'])) { $chk_visible = "1"; }
        else { $chk_visible = "0"; }

        if (isset($_POST['chk_new'])) { $chk_new = "1"; }
        else { $chk_new = "0"; }

        if (isset($_POST['chk_leader'])) { $chk_leader = "1"; }
        else { $chk_leader = "0"; }

        if (isset($_POST['chk_sale'])) { $chk_sale = "1"; }
        else { $chk_sale = "0"; }

        if (!empty($error)) {
            $_SESSION['message'] = '<p id="form-error">'.implode('<br />', $error).'</p>';
        } else {

            $sth_insert = DB::getStatement('INSERT INTO table_products( title,
                                                                        price,
                                                                        brand,
                                                                        seo_words,
                                                                        seo_description,
                                                                        mini_description,
                                                                        description,
                                                                        mini_features,
                                                                        features,
                                                                        datetime,
                                                                        new,
                                                                        leader,
                                                                        sale,
                                                                        visible,
                                                                        type_tovara,
                                                                        brand_id )
                                              VALUES( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $date = date('Y-m-d H:i:s', time());
            $sth_insert->execute(array( $_POST['form_title'],
                $_POST['form_price'],
                $selectbrand,
                $_POST['form_seo_words'],
                $_POST['form_seo_description'],
                $_POST['txt1'],
                $_POST['txt2'],
                $_POST['txt3'],
                $_POST['txt4'],
                $date,
                $chk_new,
                $chk_leader,
                $chk_sale,
                $chk_visible,
                $_POST['form_type'],
                $_POST['form_category']));
            $_SESSION['message'] = '<p id="form-success">Товар успешно добавлен!</p>';
            $id = DB::getId();
//            echo "<tt><pre> - djflskdjf - ".print_r($_POST, true). "</pre></tt>";
            if (empty($_POST['upload_image'])) {
                include('actions/upload-image.php');
                unset($_POST['upload_image']);
            } else {
                print "no";
            }
            if (empty($_POST['galleryimg'])) {
                include('actions/upload-gallery.php');
                unset($_POST['galleryimg']);
            }
        }
    }

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Панель управления</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="js/jquery.migrate.js"></script>
        <script type="text/javascript" src="js/admin-script.js"></script>
        <script type="text/javascript" src="js/ckeditor/AjexFileManager/ajex.js"></script>
        <script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
    </head>
    <body>
    <div id="block-body">
        <?php require_once ('include/block-header.php');
        //        $sth_count = DB::getStatement("SELECT COUNT(*) as count FROM table_products $cat");
        //        $sth_count->execute();
        //        $rows = $sth_count->fetch();
        //        echo "<tt><pre> - djflskdjf - ".print_r($rows['count'], true). "</pre></tt>";
        ?>
        <div id="block-content">
            <div id="block-parameters">
                <p id="title-page">Добавление товара</p>
            </div>
            <?php
            // вывод сообщений об ошибках
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }
            if (isset($_SESSION['answer'])) {
                echo $_SESSION['answer'];
                unset($_SESSION['answer']);
            }


            $sth_edit_sel = DB::getStatement('SELECT * FROM table_products WHERE products_id=?');
            $sth_edit_sel->execute(array($id));
            $rows_edit = $sth_edit_sel->fetchAll();

            $type_mobile = $type_notebook = $type_notepad = "";
            foreach($rows_edit as $row_edit):
                switch( $row_edit['type_tovara'] ) {
                    case 'mobile':
                        $type_mobile = 'selected';
                        break;
                    case 'notebook':
                        $type_notebook = 'selected';
                        break;
                    case 'notepad':
                        $type_notepad = 'selected';
                        break;
                }
                echo "<tt><pre>".print_r($row_edit, true). "</pre></tt>";
                ?>

                <form  enctype="multipart/form-data" method="POST">
                    <ul id="edit-tovar">
                        <li><label>Название товара</label><input type="text" name="form_title" value="<?php echo $row_edit['title']; ?>"/></li>
                        <li><label>Цена</label><input type="text" name="form_price"  value="<?php echo $row_edit['price']; ?>" /></li>
                        <li><label>Ключевые слова</label><input type="text" name="form_seo_words"  value="<?php echo $row_edit['seo_words']; ?>"  /></li>
                        <li><label>Краткое описание</label><textarea name="form_seo_description"><?php echo $row_edit['seo_description']; ?></textarea></li>
                        <li><label>Тип товара</label><select name="form_type" id="type" size="1" >
                                <option value="mobile" <?php echo $type_mobile; ?> >Мобильные телефоны</option>
                                <option value="notebook" <?php echo $type_notebook; ?> >Ноутбуки</option>
                                <option value="notepad" <?php echo $type_notepad; ?> >Планшеты</option>
                            </select></li>
                        <li><label>Категория</label><select name="form_category" size="10" >
                                <?php
                                $sth_category = DB::getStatement('SELECT * FROM category');
                                $sth_category->execute();
                                $rows_category = $sth_category->fetchAll();
                                if(!empty($rows_category)) {
                                    foreach($rows_category as $row_category){
                                        if($row_edit['brand_id'] == $row_category['id']){
                                            echo "aa";
                                            $brand = "selected";
                                        } else {
                                            $brand = "";
                                            echo "no";
                                        }
                                        echo "<option value='".$row_category['id']."' $brand >$row_edit[type_tovara]-$row_category[brand]</option>";
                                    }
                                }

                                ?>
                            </select></li>
                    </ul>
                    <?php
                    if (strlen($row_edit['image']) > 0 && file_exists('../uploads_images/'.$row_edit['image'])) {
                        $img_path   = '../uploads_images/'.$row_edit['image'];
                        $max_width  = 110;
                        $max_height = 110;
                        list($width, $height) = getimagesize($img_path);
                        $ratioh = $max_height / $height;
                        $ratiow = $max_width / $width;
                        $ratio  = min($ratioh, $ratiow);
                        $width  = intval($ratio * $width);
                        $height = intval($ratio * $height);

                        echo '<label class="stylelabel">Основная картинка</label>
                                <div id="baseimg">
                                    <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" />
                                    <a href="edit_product.php?id='.$row_edit['products_id'].'&img='.$row_edit['image'].'&action=delete" ></a>
                                </div>';
                    } else {
                        echo '<label class="stylelabel">Основная картинка</label>
                                <div id="baseimg-upload">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
                                    <input type="file" name="upload_image" />
                                </div>';
                    }
                    ?>

<!--                    <label class="stylelabel">Основная картинка</label>-->
<!--                    <div id="baseimg-upload">-->
<!--                        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />-->
<!--                        <input type="file" name="upload_image" />-->
<!--                    </div>-->

                    <h3 class="h3click">Краткое описание товара</h3>
                    <div class="div-editor1">
                        <textarea id="editor1" name="txt1" cols="100" rows="20"><?php echo $row_edit['mini_description']; ?></textarea>
                        <script type="text/javascript">
                            var ckeditor1 = CKEDITOR.replace("editor1");
                            AjexFileManager.init({
                                returnTo: 'ckeditor',
                                editor: ckeditor1
                            });
                        </script>
                    </div>

                    <h3 class="h3click">Описание товара</h3>
                    <div class="div-editor2">
                        <textarea id="editor2" name="txt2" cols="100" rows="20"><?php echo $row_edit['description']; ?></textarea>
                        <script type="text/javascript">
                            var ckeditor2 = CKEDITOR.replace("editor2");
                            AjexFileManager.init({
                                returnTo: 'ckeditor',
                                editor: ckeditor2
                            });
                        </script>
                    </div>

                    <h3 class="h3click">Краткие характеристики</h3>
                    <div class="div-editor3">
                        <textarea id="editor3" name="txt3" cols="100" rows="20"><?php echo $row_edit['mini_features']; ?></textarea>
                        <script type="text/javascript">
                            var ckeditor3 = CKEDITOR.replace("editor3");
                            AjexFileManager.init({
                                returnTo: 'ckeditor',
                                editor: ckeditor3
                            });

                        </script>
                    </div>


                    <h3 class="h3click">Характеристики</h3>
                    <div class="div-editor4">
                        <textarea id="editor4" name="txt4" cols="100" rows="20"><?php echo $row_edit['features']; ?></textarea>
                        <script type="text/javascript">
                            var ckeditor4 = CKEDITOR.replace("editor4");
                            AjexFileManager.init({
                                returnTo: 'ckeditor',
                                editor: ckeditor4
                            });
                        </script>
                    </div>

                    <label class="stylelabel">Галерея картинок</label>

                    <div id="objects">
                        <div id="addimage1" class="addimage">
                            <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                            <input type="file" name="galleryimg[]" />
                        </div>
                    </div>

                    <p id="add-input">Добавить</p>
                    <ul id="gallery-img">
                    <?php
                    $sth_edit_gallery = DB::getStatement('SELECT * FROM uploads_images WHERE products_id=?');
                    $sth_edit_gallery->execute(array($id));
                    $rows_edit_gallery = $sth_edit_gallery->fetchAll();

                    foreach ($rows_edit_gallery as $row_edit_gallery) {
                        if (strlen($row_edit_gallery['image']) > 0 && file_exists('../uploads_images/'.$row_edit_gallery['image'])) {
                            $img_path   = '../uploads_images/'.$row_edit_gallery['image'];
                            $max_width  = 100;
                            $max_height = 100;
                            list($width, $height) = getimagesize($img_path);
                            $ratioh = $max_height / $height;
                            $ratiow = $max_width / $width;
                            $ratio  = min($ratioh, $ratiow);
                            $width  = intval($ratio * $width);
                            $height = intval($ratio * $height);

                        } else {
                            $img_path = "images/noimages.png";
                            $width = 80;
                            $height = 70;
                        }

                        echo '<li id="del'.$row_edit_gallery['id'].'">
                                    <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" title="'.$row_edit_gallery['image'].'" />
                                    <a class="del-img" img_id="'.$row_edit_gallery['id'].'" ></a>
                                </li>';
                    }

                    ?>
                    </ul>

                    <h3 class="h3title">Настройки товара</h3>
                    <ul id="checkbox">
                        <?php
                        if ($row_edit['visible'] == 1) $chk1 = "checked";
                        if ($row_edit['new'] == 1) $chk2 = "checked";
                        if ($row_edit['leader'] == 1) $chk3 = "checked";
                        if ($row_edit['sale'] == 1) $chk4 = "checked";
                        ?>
                        <li><input type="checkbox" name="chk_visible" id="chk_visible" <?php echo $chk1; ?> /><label for="chk_visible">Показывать товар</label></li>
                        <li><input type="checkbox" name="chk_new" id="chk_new" <?php echo $chk2; ?>  /><label for="chk_new">Новый товар</label></li>
                        <li><input type="checkbox" name="chk_leader" id="chk_leader" <?php echo $chk3; ?>  /><label for="chk_leader">Популярные товары</label></li>
                        <li><input type="checkbox" name="chk_sale" id="chk_sale" <?php echo $chk4; ?>  /><label for="chk_sale">Товар со скидкой</label></li>
                    </ul>

                    <p align="right"><input id="submit_form" type="submit" name="submit_sav" value="Сохранить"/></p>


                </form>

            <?php endforeach; ?>
        </div> <!-- end of block-content -->
    </div>
    </body>
    </html>
<?php
} else {
    header ('Location: login.php');
}
?>