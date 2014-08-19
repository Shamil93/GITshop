<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 17/08/14
 * Time: 15:04
 */

/**
 * Обработчик для добавления/обновления продуктов в корзину(е)
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    define('myeshop', true);
    require_once ('DB.php');
    require_once ('../utility/handleData.php');
    require_once ('../utility/getIP.php');

    if (isset($_POST['id'])) {
        $id = handleData($_POST['id']);
    }
    $ip = getIP();

    // ищем в таблице cart по IP адресу посетителя и cart_id_product - id из таблицы table_products
    $sth = DB::getStatement('SELECT * FROM cart WHERE cart_ip = ? AND cart_id_product = ?');
    $sth->execute(array($ip, $id));
    $rows = $sth->fetch();
    if (!empty($rows)) { // если не пустой результат, т.е. этот товар уже есть в корзине
        $newCount = $rows['cart_count'] + 1; // добавляем к существующему еще один
        // обновляем таблицу cart, добавляя новое количество товара
        $sth1 = DB::getStatement('UPDATE cart SET cart_count = ? WHERE cart_ip = ? AND cart_id_product = ?');
        $sth1->execute(array($newCount, $ip, $id));

    } else { // если товар такой в корзине отсутствует - его просто не выбирали еще
        // ищем в таблице table_products этот товар по его ID
        $sth2 = DB::getStatement('SELECT * FROM table_products WHERE products_id = ?');
        $sth2->execute(array($id));
        $rows2 = $sth2->fetch(); // находим товар
        // добавляем в таблицу cart данный товар - его ID, цена, дата добавления в корзину и IP покупателя
        $sth3 = DB::getStatement('INSERT INTO cart(cart_id_product, cart_price, cart_datetime, cart_ip)
                                    VALUES(?,?,?,?)');
        $date = date('Y-m-d H:i:s', time());
        $sth3->execute(array($rows2['products_id'], $rows2['price'], $date, $ip));
    }
}
?>