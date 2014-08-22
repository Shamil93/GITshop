<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 15:27
 */

namespace ecommerce\view;

class VH {
    static function getRequest() {
        return \ecommerce\base\RequestRegistry::getRequest();
    }
}
?>