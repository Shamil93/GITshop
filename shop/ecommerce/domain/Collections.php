<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 21:14
 */

namespace ecommerce\domain;

interface NewsCollection extends \Iterator {
    function add( DomainObject $news );
}
interface ProductsCollection extends \Iterator {
    function add( DomainObject $news );
}


?>