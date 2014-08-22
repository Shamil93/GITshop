<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 22/08/14
 * Time: 10:31
 */

namespace ecommerce\mapper;
require_once('ecommerce/mapper/PaginationFactory.php');


/**
 * Class ProductsPaginationFactory
 * Постраничная навигация для Поиска
 * @package ecommerce\mapper
 */
class ProductsPaginationFactory extends PaginationFactory  {
    protected static $PDO;
    // Имя таблицы в БД
    protected $tableName;
    // Условие WHERE
    protected $where;
    // Условие ORDER BY
    protected $order;
    // Количество позиций на странице
    protected $pageNumber;
    // Количество ссылок на другие страницы
    // слева и справа от текущей позиции
    protected $pageLink;
    // Дополнительные параметры
    protected $parameters;


    public function __construct( $tableName ,
                                 $where = "",
                                 $order = "",
                                 $pageNumber = 10,
                                 $pageLink = 3,
                                 $parameters = "") {

        $this->tableName = $tableName;
        $this->where = $where;
        $this->order = $order;
        $this->pageNumber = $pageNumber;
        $this->pageLink = $pageLink;
        $this->parameters = $parameters;


        if( ! isset( self::$PDO ) ) {
            $dsn = \ecommerce\base\DBRegistry::getDB();
            if( is_null( $dsn ) ) {
                throw new \ecommerce\base\AppException( "No DSN" );
            }
            self::$PDO = $dsn;
        }
    }


    /**
     * Метод для подготовки запроса к БД(PDO)
     * @param $str
     * @return mixed
     */
    function getStatement($str) {
        if( ! isset( $this->statements[$str] ) ) {
            $this->statements[$str] = self::$PDO->prepare( $str );
        }
        return $this->statements[$str];
    }


    public function getTotal() {
        list( $where, $values ) = $this->buildWhere( $this->where );
//        echo "<tt><pre>".print_r($values, true)."</pre></tt>";
        $sth = $this->getStatement( "SELECT COUNT(*) FROM {$this->tableName}
                                        {$where} {$this->order}" );

        $this->bindWhereValue( $sth, $this->where );

        $result = $sth->execute();
        if( ! $result ) {
            throw new \PDOException("Ошибка при подсчете позиций в getTotal()" );
        }
        $count = $sth->fetchColumn();
        $sth->closeCursor();
        return $count;
    }

    public function getPageNumber() { return $this->pageNumber; }

    public function getPageLink() { return $this->pageLink; }

    public function getParameters() { return $this->parameters; }

    public function getPage() {
//        echo "<tt><pre> - djflskdjf - ".print_r($this->getParameters(), true). "</pre></tt>";
        $page = intval( $_GET['page'] );
        if( empty( $page ) ) { $page = 1; }
        $total = $this->getTotal();
        $number = (int)( $total / $this->getPageNumber() );
//        if( (float)( $total / $this->getPageNumber() - $number ) != 0 ) { $number++; }
        if ($total / $this->getPageNumber() != 0 ) $number++; // если не равно 0, то добавляем 1
        $number = intval($number); // и приводим к целому числу

        if (empty($page) || $page < 0 ) $page = 1; // номер страницы
        if ($page > $number) $page = $number; // если страница больше общего числа, то она и есть максимальная
        $arr = array();
        $first = ( $page - 1 ) * $this->getPageNumber();

        list( $where, $values ) = $this->buildWhere( $this->where );
        $fields = implode( ",", $this->where->getObjectFields() );
//        echo "<tt><pre>".print_r($values, true)."</pre></tt>";
        $sth = $this->getStatement( "SELECT $fields FROM $this->tableName "."$where "."{$this->order} "." LIMIT :start, :end ");

        $this->bindWhereValue( $sth, $this->where );
        $sth->bindValue(':start', intval($first), \PDO::PARAM_INT );
        $sth->bindValue(':end', intval($this->getPageNumber()), \PDO::PARAM_INT );

        $result = $sth->execute( );
        if( ! $result ) {
            throw new \PDOException( "Ошибка при выборке в getPage()" );
        }
//        while( $arr[] = $sth->fetchAll() );
//        unset( $arr[count($arr) - 1]);
//        $sth->closeCursor();

        $arr = $sth->fetchAll();
        return $arr;
    }


    /**
     * Строим конструкцию Where
     * WHERE name = :name AND id = :id
     * @param IdentityObject $obj
     * @return array
     */
    function buildWhere( IdentityObject $obj ) {
        if( $obj->isVoid() ) {
            return array( "", array() );
        }
        $compstrings = array();
        $values = array();
        foreach ($obj->getComps() as $comp) {
            $compstrings[] = "{$comp['name']} {$comp['operator']} :{$comp['name']} ";
            $values[] = ":{$comp['name']} , {$comp['value']}";
        }
        $where = "WHERE " . implode( " AND ", $compstrings );
        return array( $where, $values );
    }

    /**
     * Связывает имя переменной с ее значением
     * sth->bindValue(:name,'Alex')
     * @param $sth
     * @param $obj
     */
    function bindWhereValue( $sth, $obj) {
//        echo "<tt><pre>".print_r($sth, true)."</pre></tt>";
        foreach( $obj->getComps() as $val ) {
            $sth->bindValue(":".$val['name'],$val['value']);
        }
    }

    /**
     * Метод для вывода постраничной навигации
     * @return string
     */
    function printPageProducts() {
        $return_page = "<div class='pstrnav'><ul>";
        // Print reference "Back", if this is not first page
        // Reference to first page
//    if($page != 1) {
        // Для передачи позиции текущей страницы
//        $page = intval( $_GET['page'] );
        $page = intval( $_GET['page'] );
        if (empty($page) || $page < 0 ) $page = 1; // номер страницы
//        echo "<tt><pre> - djflskdjf - ".print_r($page, true). "</pre></tt>";

        if($this->getTotal() > $this->getPageLink() && $page != 1) {
            $return_page .= "<li><a href='$_SERVER[PHP_SELF]".
                "?page=1{$this->getParameters()}'>".
                "&lt;&lt;</a></li><li><p class=\"nav-point\">...</p></li>";

            $return_page .= "<li><a href='$_SERVER[PHP_SELF]".
                "?page=".($page - 1)."{$this->getParameters()}'>".
                "&lt;</a></li><li><p class=\"nav-point\">...</p></li>";
        }
        // Print previous elements
        if($page > $this->getPageLink() + 1) {
            for($i = $page - $this->getPageLink(); $i < $page; $i++)  {
                $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$i{$this->getParameters()}'>$i</a></li>";
            }
        } else {
            for($i = 1; $i < $page; $i++) {
                $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$i{$this->getParameters()}'>$i</a></li>";
            }
        }

        if ($this->getPageNumber() > 1) { // если количество ссылок на странице больше одной, то выводим
            // Print current element
            $return_page .= "<li><a class='pstr-active' href='$_SERVER[PHP_SELF]?page=$i{$this->getParameters()}'>$i</a></li>";
        }
        // Print next element
        if($page + $this->getPageLink() < $this->getPageNumber()) {
            for($i = $page + 1; $i <= $page + $this->getPageLink(); $i++) {
                $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$i{$this->getParameters()}'>$i</a></li>";
            }
        } else {
            for($i = $page + 1; $i <= $this->getPageNumber(); $i++) {
                $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$i{$this->getParameters()}'>$i</a></li>";
            }
        }

        // Print reference "Forward", if it is not last page
//    if($page != $this->getPageNumber()) {
        if($this->getTotal() > $this->getPageLink() && $page != $this->getPageNumber()) {
            $return_page .= "<li><p class=\"nav-point\">...</p><li><a href='".
                "$_SERVER[PHP_SELF]?page=".
                ($page + 1)."{$this->getParameters()}'>".
                "&gt;</a></li>";
            // Reference for last page
            $return_page .= "<li><p class=\"nav-point\">...</p></li><li><a href='$_SERVER[PHP_SELF]".
                "?page={$this->getPageNumber()}{$this->getParameters()}'>".
                "&gt;&gt;</a></li>";
        }
        $return_page .= '</ul></div>';
        return $return_page;
    }
}
?>