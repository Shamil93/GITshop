<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/08/14
 * Time: 17:56
 */

namespace ecommerce\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "ecommerce/domain/DomainObject.php" );
require_once( "ecommerce/mapper/ProductsIdentityObject.php" );


class Products extends DomainObject {
    private $title;
    private $price;
    private $brand;
    private $seo_words;
    private $seo_description;
    private $mini_description;
    private $image;
    private $description;
    private $mini_features;
    private $features;
    private $datetime;
    private $new;
    private $leader;
    private $sale;
    private $visible;
    private $count;
    private $type_tovara;
    private $brand_id;
    private $vote;
    private $yes_like;


    /**
     * Поля из БД - сохраняем из в переменные
     * @param null $products_id
     * @param string $title
     * @param string $price
     * @param string $brand
     * @param int $seo_words
     * @param string $seo_description
     * @param string $mini_description
     * @param string $image
     * @param string $description
     * @param string $mini_features
     * @param string $features
     * @param string $datetime
     * @param int $new
     * @param int $leader
     * @param int $sale
     * @param int $visible
     * @param int $count
     * @param string $type_tovara
     * @param string $brand_id
     * @param int $vote
     * @param int $yes_like
     */
    function __construct( $products_id=null,
                          $title='',
                          $price='',
                          $brand='',
                          $seo_words=1,
                          $seo_description='',
                          $mini_description='',
                          $image='',
                          $description='',
                          $mini_features='',
                          $features='',
                          $datetime='',
                          $new=0,
                          $leader=0,
                          $sale=0,
                          $visible=0,
                          $count=0,
                          $type_tovara='',
                          $brand_id='',
                          $vote=1,
                          $yes_like=1 ) {

        $this->title = $title;
        $this->price  = $price;
        $this->brand  = $brand;
        $this->seo_words  = $seo_words;
        $this->seo_description = $seo_description;
        $this->mini_description = $mini_description;
        $this->image = $image;
        $this->description = $description;
        $this->mini_features = $mini_features;
        $this->features = $features;
        $this->datetime = $datetime;
        $this->new = $new;
        $this->leader = $leader;
        $this->sale = $sale;
        $this->visible = $visible;
        $this->count = $count;
        $this->type_tovara = $type_tovara;
        $this->brand_id = $brand_id;
        $this->vote = $vote;
        $this->yes_like = $yes_like;


        parent::__construct($products_id); // вызываем конструктор родительского класса
    }

    /**
     * Здесь в родительском классе DomainObject вызываем метод getFinder,
     *
     * @return mixed
     */
    static function findAll() {
        $finder = self::getFinder( __CLASS__ ); // из родительского класса вызываем, получаем DomainObjectAssembler( PersistenceFactory )
        $idobj = self::getIdentityObject( __CLASS__ ); // ProductsIdentityObject
        $productsIdobj = new \ecommerce\mapper\ProductsIdentityObject( 'visible' ); // здесь без фабрики создаем экземпляр, чтобы передать имя поля для класса IdentityObect
        $productsIdobj->eq(1);
//        echo "<tt><pre>".print_r($productsIdobj, true)."</pre></tt>";
        return $finder->find( $productsIdobj ); // из DomainObjectAssembler возвращаем Коллекцию с итератором
    }

    static function find( $products_id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \ecommerce\mapper\ProductsIdentityObject( 'products_id' );
        return $finder->findOne( $idobj->eq( $products_id )->field( 'visible' )->eq(1) );
    }

    static function paginationMysql( $page=null ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \ecommerce\mapper\ProductsIdentityObject( 'visible' );
        $idobj->eq( '1' );

        return $finder->findPagination( "table_products",
                                        $idobj,
                                        "",
                                        10,
                                        3,
                                        "",
                                        $page.'&cmd=Main');
    }


    function setTitle($title_s) {
        $this->title = $title_s;
        $this->markDirty();
    }
    function setPrice($price_s) {
        $this->price = $price_s;
        $this->markDirty();
    }
    function setBrand($brand_s) {
        $this->brand = $brand_s;
        $this->markDirty();
    }
    function setSeoWords($seo_words_s) {
        $this->seo_words = $seo_words_s;
        $this->markDirty();
    }
    function setSeoDescription($seo_description_s) {
        $this->seo_description = $seo_description_s;
        $this->markDirty();
    }
    function setMiniDescription($mini_description_s) {
        $this->mini_description = $mini_description_s;
        $this->markDirty();
    }
    function setImage($image_s) {
        $this->image = $image_s;
        $this->markDirty();
    }
    function setDescription($description_s) {
        $this->description = $description_s;
        $this->markDirty();
    }
    function setMiniFeatures($mini_features_s) {
        $this->mini_features = $mini_features_s;
        $this->markDirty();
    }
    function setFeatures($features_s) {
        $this->features = $features_s;
        $this->markDirty();
    }
    function setDatetime($datetime_s) {
        $this->datetime = $datetime_s;
        $this->markDirty();
    }
    function setNew($new_s) {
        $this->new = $new_s;
        $this->markDirty();
    }
    function setLeader($leader_s) {
        $this->leader = $leader_s;
        $this->markDirty();
    }
    function setSale($sale_s) {
        $this->sale = $sale_s;
        $this->markDirty();
    }
    function setVisible($visible_s) {
        $this->visible = $visible_s;
        $this->markDirty();
    }
    function setCount($count_s) {
        $this->count = $count_s;
        $this->markDirty();
    }
    function setTypeTovara($type_tovara_s) {
        $this->type_tovara = $type_tovara_s;
        $this->markDirty();
    }
    function setBrandId($brand_id_s) {
        $this->brand_id = $brand_id_s;
        $this->markDirty();
    }
    function setVote($vote_s) {
        $this->vote = $vote_s;
        $this->markDirty();
    }
    function setYesLike($yes_like_s) {
        $this->yes_like = $yes_like_s;
        $this->markDirty();
    }



    function getTitle() {
        return $this->title;
    }
    function getPrice() {
        return $this->price;
    }
    function getBrand() {
        return $this->brand;
    }
    function getSeoWords() {
        return $this->seo_words;
    }
    function getMiniDescription() {
        return $this->mini_description;
    }
    function getImage() {
        return $this->image;
    }
    function getDescription() {
        return $this->description;
    }
    function getMiniFeatures() {
        return $this->mini_features;
    }
    function getFeatures() {
        return $this->features;
    }
    function getDatetime() {
        return $this->datetime;
    }
    function getNew() {
        return $this->new;
    }
    function getLeader() {
        return $this->leader;
    }
    function getSale() {
        return $this->sale;
    }
    function getVisible() {
        return $this->visible;
    }
    function getCount() {
        return $this->count;
    }
    function getTypeTovara() {
        return $this->type_tovara;
    }
    function getBrandId() {
        return $this->brand_id;
    }
    function getVote() {
        return $this->vote;
    }
    function getYesLike() {
        return $this->yes_like;
    }
}
?>