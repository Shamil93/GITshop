<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/08/14
 * Time: 15:32
 */

namespace ecommerce\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "ecommerce/mapper/UpdateFactory.php" );


class NewsUpdateFactory  extends UpdateFactory{

    function newUpdate( \ecommerce\domain\DomainObject $obj ) {
        $id = $obj->getId();
        $cond = null;
        $values['title'] = $obj->getTitle();
        $values['text']  = $obj->getText();
        $values['date']  = $obj->getDate();
        $values['hide']  = $obj->getHide();
        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( "news", $values, $cond );
    }
}

?>