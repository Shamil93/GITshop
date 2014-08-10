<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 10/08/14
 * Time: 15:32
 */

class Exceptions extends PDOException {

    public function __construct( PDOException $ex ) {
        if( strstr( $ex->getMessage(), 'SQLSTATE[' ) ) {
            $this->code = $ex->getCode();
            $this->message = $ex->getMessage();
            $this->line = $ex->getLine();
        }
        print $this->code .", ".$this->message." in line: ".$this->line."<br /><br />";
    }
}
?>