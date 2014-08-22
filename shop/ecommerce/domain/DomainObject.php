<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 21:39
 */

namespace ecommerce\domain;

//включаем IDENTITY_MAP и UNIT_OF_WORK шаблоны
require_once( "ecommerce/domain/ObjectWatcher.php" );
// включаем вспомогательный класс-фабрику
require_once( "ecommerce/domain/HelperFactory.php" );

abstract class DomainObject {
    private $id = -1;

    /**
     * Конструктор вызывается из дочернего класса
     * @param null $id
     */
    function __construct( $id = null ){
        if( is_null( $id ) ) {
            $this->markNew(); // отмечаем как новое обращение, т.е. не ( SELECT .... WHERE id = ... )
        } else {
            $this->id = $id; // если не новое обращение, то сохраняем id
        }
    }

    /**
     * Получаем id вставленной записи в БД
     * @return int|null
     */
    function getId() {
//        echo "<tt><pre>".print_r($this->id, true)."</pre></tt>";
        return $this->id;
    }

    /**
     * Возвращаем коллекцию
     * @param $type
     * @return
     * \ecommerce\mapper\ContactsCollection|
     * \ecommerce\mapper\GuestbookCollection|
     * \ecommerce\mapper\NewsCollection
     */
    static function getCollection( $type ) {
        return HelperFactory::getCollection( $type );
    }

    /**
     * Вспомогательный метод для вызова статического метода
     * @return \ecommerce\mapper\ContactsCollection|\ecommerce\mapper\GuestbookCollection|\ecommerce\mapper\NewsCollection
     */
    function collection() {
        return self::getCollection( get_class( $this ) );
    }

    /**
     * Вызываем из дочернего класса
     * В HelperFactory вызываем метод getFactory
     * и должны получить PersistenceFactory (ecommerce\domain\News - NewsPersistenceFactory и т.д.)
     * и вернется DomainObjectAssembler с PersistenceFactory в качестве параметра
     * @param $type - класс
     * @return \ecommerce\mapper\DomainObjectAssembler
     */
    static function getFinder( $type ) {
        return HelperFactory::getFinder( $type );
    }

    /**
     * Вспомогательный метод для вызова статического метода
     * @return \ecommerce\mapper\DomainObjectAssembler
     */
    function finder() {
        return self::getFinder( get_class( $this ) );
    }

    /**
     * Вызываем из дочернего класса
     * В HelperFactory вызываем метод getIdentityObject
     * и должны получить к примеру: new NewsIdentityObject()
     * - т.е. экземпляр класса, в зависимости от имени класса в $type
     * @param $type - имя класса
     * @return \ecommerce\mapper\ContactsIdentityObject|\ecommerce\mapper\GuestbookIdentityObject|\ecommerce\mapper\NewsIdentityObject
     */
    static function getIdentityObject( $type ) {
        return HelperFactory::getIdentityObject( $type );
    }
    function identityObject() {
        return self::getIdentityObject( get_class( $this ) );
    }

    /**
     * Устанавливаем id вставленной записи в БД
     * @param $id
     */
    function setId( $id ) {
//        echo "<tt><pre>".print_r($id, true)."</pre></tt>";
        $this->id = $id;
    }

    function __clone() {
        $this->id = -1;
    }

    /**
     * Добавляем в массив new текущий объект Domain
     * означает, что id = -1 - т.е. вставляем новую строку в таблицу
     */
    function markNew() {
        ObjectWatcher::addNew( $this );
    }

    /**
     * Добавляем в массив delete текущий объект Domain
     * с ключом Domain + id
     */
    function markDeleted() {
        ObjectWatcher::addDelete( $this );
    }

    /**
     * Добавляем в массив dirty текущий объект Domain
     * с ключом Domain + id, если его еще нет в массиве new
     * т.е. данные обновляются
     */
    function markDirty() {
        ObjectWatcher::addDirty( $this );
    }

    /**
     * Удаляем массивы delete, dirty и из new удаляем текущий объект Domain
     * с ключом Domain + id
     */
    function markClean() {
        ObjectWatcher::addClean( $this );
    }
}
?>