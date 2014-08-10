<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 09/08/14
 * Time: 15:02
 */
class DB {
    private static $instance;
    private $value = array();

    private function __construct() { }

     static function  instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function get( $key ) {
        if( isset( $this->value[$key] ) ) {
            return $this->value[$key];
        }
        try {
            $this->value[$key] = new PDO('mysql:host=localhost;dbname=shop','root','zhalnin5334',
                array( PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8' ) );
        } catch( PDOException $ex ) {
            echo $ex->getMessage();
        }
        return $this->value[$key];
    }

    static function getDB() {
        return self::instance()->get( 'dsn' );
    }

    static function getStatement( $stmt ) {
        $prepare = self::getDB()->prepare( $stmt );
        if( ! $prepare ) {
            throw new PDOException( 'Error in block_category - mobile - prepare' );
        }
        return $prepare;
    }
}



?>