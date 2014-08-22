<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 22:30
 */

namespace ecommerce\mapper;
use ecommerce\domain\DomainObject;

error_reporting( E_ALL & ~E_NOTICE );


abstract class DomainObjectFactory {
    abstract function createObject( array $array );

    protected function getFromMap( $class, $id ) {
        return \ecommerce\domain\ObjectWatcher::exists( $class, $id );
    }

    protected function addToMap( \ecommerce\domain\DomainObject $obj ) {
        \ecommerce\domain\ObjectWatcher::add( $obj );
    }
}


/**
 * Class NewsObjectFactory
 * @package ecommerce\mapper
 * Как аргумент в классе PersistenceFactory
 */
class NewsObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \ecommerce\domain\News
     */
    function createObject( array $array ) {
        $class = "\\ecommerce\\domain\\News"; // название класса
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setTitle( $array['title'] );
        $obj->setText( $array['text'] );
        $obj->setDate( $array['date'] );
        $obj->setHide( $array['hide'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \ecommerce\domain\News
    }
}


/**
 * Class ProductsObjectFactory
 * @package ecommerce\mapper
 * Как аргумент в классе PersistenceFactory
 */
class ProductsObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \ecommerce\domain\Products
     */
    function createObject( array $array ) {
        $class = "\\ecommerce\\domain\\Products"; // название класса
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setTitle( $array['title'] );
        $obj->setPrice( $array['price'] );
        $obj->setBrand( $array['brand'] );
        $obj->setSeoWords( $array['seo_words'] );
        $obj->setSeoDescription($array['seo_description']);
        $obj->setMiniDescription($array['mini_description']);
        $obj->setImage($array['image']);
        $obj->setDescription($array['description']);
        $obj->setMiniFeatures($array['mini_features']);
        $obj->setFeatures($array['features']);
        $obj->setDatetime($array['datetime']);
        $obj->setNew($array['new']);
        $obj->setLeader($array['leader']);
        $obj->setSale($array['sale']);
        $obj->setVisible($array['visible']);
        $obj->setCount($array['count']);
        $obj->setTypeTovara($array['type_tovara']);
        $obj->setBrandId($array['brand_id']);
        $obj->setVote($array['vote']);
        $obj->setYesLike($array['yes_like']);

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \ecommerce\domain\Products
    }
}


?>