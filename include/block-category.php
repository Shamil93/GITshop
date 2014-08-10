<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/08/14
 * Time: 17:34
 */
?>

<div id="block-category">
    <p class="header-title" >Категории товаров</p>
    <ul>
        <li><a id="index1"><img src="images/mobile-icon.gif" id="mobile-images" />Мобильные телефоны</a>
            <ul class="category-section">
                <li><a href="view_cat.php?type=mobile"><strong>Все модели</strong></a></li>
                <?php
                $sth = DB::getStatement( "SELECT * FROM table_products WHERE type_tovara = 'mobile'" );
                $sth->execute();
                $rows = $sth->fetchAll();
                foreach( $rows as $row ) {
//                    echo "<tt><pre>".print_r( $row, true ). "</pre></tt>";
                    echo '<li><a href="view_cat.php?cat='.strtolower( $row["brand"] ) .'&type='.$row["type_tovara"].'">'. $row["brand"].'</a></li>';
                }
                ?>
            </ul>
        </li>
        <li><a id="index2"><img src="images/book-icon.gif" id="book-images" />Ноутбуки</a>
            <ul class="category-section">
                <li><a href="view_cat.php?type=notebook"><strong>Все модели</strong></a></li>

                <?php
                $sth = DB::getStatement( "SELECT * FROM table_products WHERE type_tovara = 'notebook'" );
                $sth->execute();
                $rows = $sth->fetchAll();
                foreach( $rows as $row ) {
//                    echo "<tt><pre>".print_r( $row, true ). "</pre></tt>";
                    echo '<li><a href="view_cat.php?cat='.strtolower( $row["brand"] ) .'&type='.$row["type_tovara"].'">'. $row["brand"].'</a></li>';
                }
                ?>
            </ul>
        </li>
        <li><a id="index3"><img src="images/table-icon.gif" id="table-images" />Планшеты</a>
            <ul class="category-section">
                <li><a href="view_cat.php?type=notepad"><strong>Все модели</strong></a></li>

                <?php
                $sth = DB::getStatement( "SELECT * FROM table_products WHERE type_tovara = 'notepad'" );
                $sth->execute();
                $rows = $sth->fetchAll();
                foreach( $rows as $row ) {
//                    echo "<tt><pre>".print_r( $row, true ). "</pre></tt>";
                    echo '<li><a href="view_cat.php?cat='.strtolower( $row["brand"] ) .'&type='.$row["type_tovara"].'">'. $row["brand"].'</a></li>';
                }
                ?>
            </ul>
        </li>

    </ul>
</div>