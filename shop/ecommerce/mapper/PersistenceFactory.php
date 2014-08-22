<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 22:03
 */

namespace ecommerce\mapper;
error_reporting( E_ALL & ~E_NOTICE );
//require_once( "ecommerce/mapper/Collections.php" );
//require_once( "ecommerce/mapper/DomainObjectFactory.php" );
//require_once( "ecommerce/mapper/NewsIdentityObject.php" );
//require_once( "ecommerce/mapper/NewsSelectionFactory.php" );

require_once( "ecommerce/mapper.php" );

abstract class PersistenceFactory {

    abstract function getMapper();
    abstract function getDomainObjectFactory();
    abstract function getCollection( array $array );
    abstract function getSelectionFactory();
    abstract function getUpdateFactory();
//    abstract function getDeleteFactory();

    /**
     * Фабрика для получения нужного объекта
     * @param $target_class - имя класса из DomainObject -- HelperFactory
     * @return ContactsPersistenceFactory|GuestbookPersistenceFactory|NewsPersistenceFactory
     */
    static function getFactory( $target_class ) {
        switch( $target_class ) {
            case "ecommerce\\domain\\News":
                return new NewsPersistenceFactory();
                break;
            case "ecommerce\\domain\\Products":
                return new ProductsPersistenceFactory();
                break;

        }
    }
}



/**
 * Class NewsPersistenceFactory
 * @package ecommerce\mapper
 * Класс для управления новостями
 */
class NewsPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new NewsMapper();
    }

    function getDomainObjectFactory() {
        return new NewsObjectFactory();
    }

    /**
     * Получаем из DomainObjectAssembler результирующий массив запроса
     * @param array $array - результирующий набор БД из DomainObjectAssembler->find() - т.е. $raw
     * будет иметь методы:
     * - add()
     * - targetClass()
     * - notifyAccess()
     * - getRow()
     * - rewind()
     * - current()
     * - key()
     * - next()
     * - valid()
     * @return NewsCollection - $this->raw; $this->total; $this->dofact
     */
    function getCollection( array $array ) {
        return new NewsCollection( $array, $this->getDomainObjectFactory() ); // возвращаем экземпляр Collection, будет содержать метод createObject
    }

    /**
     * Получаем из DomainObjectAssembler
     * @return NewsSelectionFactory
     */
    function getSelectionFactory() {
        return new NewsSelectionFactory();
    }

    function getUpdateFactory() {
        return new NewsUpdateFactory();
    }

    /**
     * Получаем из \ecommerce\domain\News
     * @return NewsIdentityObject
     */
    function getIdentityObject() {
        return new NewsIdentityObject();
    }
}


/**
 * Class ProductsPersistenceFactory
 * @package ecommerce\mapper
 * Класс для управления новостями
 */
class ProductsPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new ProductsMapper();
    }

    function getDomainObjectFactory() {
        return new ProductsObjectFactory();
    }

    /**
     * Получаем из DomainObjectAssembler результирующий массив запроса
     * @param array $array - результирующий набор БД из DomainObjectAssembler->find() - т.е. $raw
     * будет иметь методы:
     * - add()
     * - targetClass()
     * - notifyAccess()
     * - getRow()
     * - rewind()
     * - current()
     * - key()
     * - next()
     * - valid()
     * @return ProductsCollection - $this->raw; $this->total; $this->dofact
     */
    function getCollection( array $array ) {
        return new ProductsCollection( $array, $this->getDomainObjectFactory() ); // возвращаем экземпляр Collection, будет содержать метод createObject
    }

    /**
     * Получаем из DomainObjectAssembler
     * @return ProductsSelectionFactory
     */
    function getSelectionFactory() {
        return new ProductsSelectionFactory();
    }

    function getUpdateFactory() {
        return new ProductsUpdateFactory();
    }

    /**
     * Получаем из \ecommerce\domain\Products
     * @return ProductsIdentityObject
     */
    function getIdentityObject() {
        return new ProductsIdentityObject();
    }

    function getPaginationFactory( $tableName,
                                   $where,
                                   $order,
                                   $pageNumber,
                                   $pageLink,
                                   $parameters,
                                   $page ) {
        return new ProductsPaginationFactory(  $tableName,
                                                $where,
                                                $order,
                                                $pageNumber,
                                                $pageLink,
                                                $parameters,
                                                $page);
    }
}

?>