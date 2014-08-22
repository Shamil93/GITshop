<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 21:12
 */

namespace ecommerce\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "ecommerce/mapper/Collection.php" );

/**
 * Class NewsCollection - для новостного блока
 * @package ecommerce\mapper
 */
class NewsCollection
    extends Collection
    implements \ecommerce\domain\NewsCollection {

    function targetClass() {
        return "\\ecommerce\\domain\\News";
    }
}

/**
 * Class ProductsCollection - для блока с продукцией
 * @package ecommerce\mapper
 */
class ProductsCollection
    extends Collection
    implements \ecommerce\domain\ProductsCollection {

    function targetClass() {
        return "\\ecommerce\\domain\\Products";
    }
}

class DefferredNewsCollection extends NewsCollection {
    private $stmt;
    private $valueArray;
    private $run=false;

    function __construct( DomainObjectFactory $dofact, \PDOStatement $stmt_handle, array $valueArray ) {
        parent::__construct( null, $dofact );
        $this->stmt = $stmt_handle;
        $this->valueArray = $valueArray;
    }

    function notifyAccess() {
        if( ! $this->run ) {
            $this->stmt->execute( $this->valueArray );
            $this->raw = $this->stmt->fetchAll();
            $this->total = count( $this->raw );
        }
        $this->run = true;
    }
}


?>