<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/08/14
 * Time: 14:57
 */

namespace ecommerce\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "ecommerce/domain/DomainObject.php" );
require_once( "ecommerce/mapper/NewsIdentityObject.php" );


class News extends DomainObject {
    private $title;
    private $text;
    private $date;
    private $hide;

    /**
     * Поля из БД - сохраняем из в переменные
     * @param null $id
     * @param string $title
     * @param string $text
     * @param string $date
     * @param int $hide
     */
    function __construct( $id=null,
                          $title='',
                          $text='',
                          $date='00-00-00 00:00:00',
                          $hide=1) {

        $this->title = $title;
        $this->text  = $text;
        $this->date  = $date;
        $this->hide  = $hide;


        parent::__construct( $id ); // вызываем конструктор родительского класса
    }

    /**
     * Здесь в родительском классе DomainObject вызываем метод getFinder,
     *
     * @return mixed
     */
    static function findAll() {
        $finder = self::getFinder( __CLASS__ ); // из родительского класса вызываем, получаем DomainObjectAssembler( PersistenceFactory )
        $idobj = self::getIdentityObject( __CLASS__ ); // NewsIdentityObject
        $newsIdobj = new \ecommerce\mapper\NewsIdentityObject( 'hide' ); // здесь без фабрики создаем экземпляр, чтобы передать имя поля для класса IdentityObect
        $newsIdobj->eq(1);
        return $finder->find( $newsIdobj ); // из DomainObjectAssembler возвращаем Коллекцию с итератором
    }

    static function find( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \ecommerce\mapper\NewsIdentityObject( 'id' );
        return $finder->findOne( $idobj->eq( $id )->field( 'hide' )->eq(1) );
    }


    function setTitle($title_s) {
        $this->title = $title_s;
        $this->markDirty();
    }
    function setText($text_s) {
        $this->text = $text_s;
        $this->markDirty();
    }
    function setDate($date_s) {
        $this->date = $date_s;
        $this->markDirty();
    }
    function setHide($hide_s) {
        $this->hide = $hide_s;
        $this->markDirty();
    }

    function getTitle() {
        return $this->title;
    }
    function getText() {
        return $this->text;
    }
    function getDate() {
        return $this->date;
    }
    function getHide() {
        return $this->hide;
    }
}
?>