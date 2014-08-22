<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 06/07/14
 * Time: 15:34
 */

namespace ecommerce\classes;
error_reporting( E_ALL & ~E_NOTICE );
session_start();
require_once( "ecommerce/base/Registry.php" );

class Cart {
    private static $instance;
    private $pdo;

    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        if( ! isset( $this->pdo) ) {
            $this->pdo =  \ecommerce\base\DBRegistry::getDB();
        }
    }

    /**
     * Добавляем в корзину предмет
     * @param $id_catalog - каталог в system_position
     * @param $position - позиция в system_position
     * @return bool
     */
    protected function addToCart( $id_catalog, $position ) {
        if( isset( $_SESSION['cart_ecommerce'][$id_catalog][$position] ) ) {
            $_SESSION['cart_ecommerce'][$id_catalog][$position]++;
            return true;
        } else {
            $_SESSION['cart_ecommerce'][$id_catalog][$position] = 1;
            return true;
        }
        return false;
    }

    /**
     * Подсчитываем количество предметов в корзине
     * по id каталога и id позиции
     * @param $cart - сессия
     * @return int - количество предметов
     */
    protected function totalItems( $cart ) {
        $total = 0;
        if( is_array( $cart ) ) {
            foreach ( $cart as $id_catalog => $position ) {
                foreach ( $position as $qty ) {
                    $total += $qty;
                }
            }
        }
        return $total;
    }

    /**
     * Подсчитываем общую стоимость товаров в корзине
     * @param $cart - сессия
     * @return string - сумма
     */
    protected function totalPrice( $cart ) {
        $price = '0.00';
        if( is_array( $cart ) ) {
            foreach ( $cart as $id_catalog => $catalog ) {
                foreach ( $catalog as $position => $qty ) {
//                    $pdo = \ecommerce\base\DBRegistry::getDB();
                    $sth = $this->pdo->prepare( 'SELECT cost FROM system_position
                                                        WHERE system_position.pos = ?
                                                        AND system_position.id_catalog = ?' );
                    $result = $sth->execute( array( $position, $id_catalog ) );
                    $item = $sth->fetch();
                    if( $result ) {
                        $price += $item['cost'] * $qty;
                    }
                }
            }
        }
        return $price;
    }


    /**
     * Получаем заказанные товары из каталога по  id_catalog и position
     * @param $position
     * @param $id_catalog
     * @return mixed
     * @throws \PDOException
     */
    protected function findProduct( $position, $id_catalog ) {
//        $pdo = \ecommerce\base\DBRegistry::getDB();
        $sth = $this->pdo->prepare( 'SELECT * FROM system_position
                                                        WHERE system_position.pos = ?
                                                        AND system_position.id_catalog = ?' );
        $result = $sth->execute( array( $position, $id_catalog ) );
        if( ! $result ) {
            throw new \PDOException( "Error in class.Cart.php" );
        }
        return $sth->fetch();
    }

    /**
     * Обновляем колличество предметов в корзине:
     * из вьюшки получаем POST запрос с параметрами
     * парсируем их и обновляем количество
     */
    protected function updateCart() {
//        echo "<tt><pre>".print_r( $_POST, true )."</pre></tt>";
        // из запроса получаем два индекса(8_2), где 8-id_catalog и 2-position в БД, и значение-количество предметов
        foreach ($_POST as $index => $value ) {
            if( preg_match('|[0-9]+|', $value ) ) { // если количество предметов это цифра/число
                // разбиваем наш индекс по "_" и инициализируем переменные
                list($id_catalog, $position ) = explode('_', $index);
                if( $value == 0 ) { // если количество предметов равно 0
                        unset( $_SESSION['cart_ecommerce'][$id_catalog][$position] ); // уничтожаем эту позицию в корзине
                    if( empty( $_SESSION['cart_ecommerce'][$id_catalog] ) ) { // если по этому каталогу больше нет предметов
                        unset( $_SESSION['cart_ecommerce'][$id_catalog] ); // уничтожаем каталог в корзине
                    }
                } else { // если количество в корзине не равно 0
                    $_SESSION['cart_ecommerce'][$id_catalog][$position] = $value; // то данную позицию по каталогу обновляем
                }
            }
        }
    }


    /**
     * Проверяем уникальность id транзакции,
     * чтобы в таблице его не было
     * @param $trans_id
     * @return bool
     */
    protected function noPaypalTransId( $trans_id ) {
//        $pdo = \ecommerce\base\DBRegistry::getDB();
        $sth = $this->pdo->prepare( 'SELECT id FROM system_cart_orders WHERE paypal_trans_id = ?' );
        $sth->execute( array( $trans_id ) );
        $num_result = $sth->fetch();
        if( $num_result == 0 ) {
            return true;
        }
        return false;
    }


    /**
     * Сверяем стоимость товара с доставкой из БД с
     * данными, отправляемыми формой на PayPal
     * @param $shipping
     * @param $params
     * @return bool
     */
    protected function paymentAmountCorrect( $shipping, $params ) {

        $amount = 0.00;
//        $pdo = \ecommerce\base\DBRegistry::getDB();

        for( $i=1; $i <= $params['num_cart_items']; $i++ ) {
            // инициализируем две переменные из строки, типа: 36_2
            list($id_catalog, $position ) = explode( '_', $params["item_number{$i}"] );
            $sth = $this->pdo->prepare( 'SELECT cost FROM system_position
                                                        WHERE system_position.pos = ?
                                                        AND system_position.id_catalog = ?' );
            $result = $sth->execute( array( $position, $id_catalog ) );
            if( $result ) {
                $item_price = $sth->fetch();
                $amount += $item_price['cost'] * $params["quantity{$i}"];
            }
        }
        if( ( $amount / 100 * 3.9 + 10 ) == $params['mc_gross'] ) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Метод для парсинга POST из формы, чтобы получить
     * фактическую стоимость каждой единицы и
     * общей стоимости для сравнения при оформлении заказа вручную (не PayPal)
     * @param $shipping
     * @param $params
     * @return bool
     */
    protected function paymentAmountCorrectManual( $shipping, $params ) {

        $amount = 0.00;
        // проверка на соответствие суммы заказу
        foreach( $params as $key => $val ) {
            if( preg_match('|amount_(.*)|', $key, $match ) ) {
                $count = $match[1];
            }
        }
        // проходим в цикле, чтобы инициализировать нужные нам переменные для вставки в system_cart_items
        for( $i=1; $i <= $count; $i++ ) {
            // защита от воров первая
            if( $params["item_number_{$i}"] <= 0 ) {
                file_put_contents('error_cart.txt', "Error at: ".date('Y-m-d H:i:s', time() ) ." : item_number_{$i} = {$params['item_number_'.$i]}"."\n\r", FILE_APPEND );
                return false;
            } elseif( $params["amount_{$i}"] <= 0 ) {
                file_put_contents('error_cart.txt', "Error at: ".date('Y-m-d H:i:s', time() ) ." : amount_{$i} = {$params['amount_'.$i]}"."\n\r", FILE_APPEND );
                return false;
            } elseif( $params["total_cost_{$i}"] <= 0 ) {
                file_put_contents('error_cart.txt', "Error at: ".date('Y-m-d H:i:s', time() ) ." : total_cost_{$i} = {$params['total_cost_'.$i]}"."\n\r", FILE_APPEND );
                return false;
            }
            // инициализируем две переменные из строки, типа: 36_2
            list($id_catalog, $position ) = explode( '_', $params["item_number_{$i}"] );
            $sth = $this->pdo->prepare( 'SELECT cost FROM system_position
                                                        WHERE system_position.pos = ?
                                                        AND system_position.id_catalog = ?' );
            $result = $sth->execute( array( $position, $id_catalog ) );
            if( $result ) {
                $item_price = $sth->fetch();
                if( empty( $item_price ) ) {
                    file_put_contents('error_cart.txt', "Error at DB: ".date('Y-m-d H:i:s', time() ) ." : items_price is empty"."\n\r", FILE_APPEND );
                }
                $amount += $item_price['cost'] * $params["quantity_{$i}"];
            } else {
                file_put_contents('error_cart.txt', "Error in DB: item_number_{$i} = {$params['item_number_'.$i]},
                                                    amount_{$i} = {$params['amount_'.$i]},
                                                    total_cost_{$i} = {$params['total_cost_'.$i]},
                                                    subtotal = {$params['subtotal']}"."\n\r", FILE_APPEND );
            }
        }
        // защита от воров вторая
        if( ( $amount ) == $params['subtotal'] ) {
            file_put_contents('error_cart.txt', "Error: subtotal = {$params['subtotal']}"."\n\r", FILE_APPEND );
            return true;
        } else {
            return false;
        }
    }



    static function getTotalPrice( $cart ) {
        return self::instance()->totalPrice( $cart );
    }

    static function getTotalItems( $cart ) {
        return self::instance()->totalItems( $cart );
    }

    static function setAddToCart( $id_catalog, $id_position ) {
        return self::instance()->addToCart( $id_catalog, $id_position );
    }

    static function setUpdateCart() {
        self::instance()->updateCart();
    }

    static function getProduct(  $position, $id_catalog ) {
        return self::instance()->findProduct(  $position, $id_catalog );
    }

    static function getNoPaypalTransId(  $trans_id ) {
        return self::instance()->noPaypalTransId(  $trans_id);
    }

    static function getPaymentAmountCorrect( $shipping, $params ) {
        return self::instance()->paymentAmountCorrect( $shipping, $params );
    }

    static function getPaymentAmountCorrectManual( $shipping, $params ) {
        return self::instance()->paymentAmountCorrectManual( $shipping, $params );
    }

}
?>