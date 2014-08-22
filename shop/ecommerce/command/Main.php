<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/08/14
 * Time: 14:00
 */

namespace ecommerce\command;
error_reporting(E_ALL &~ E_NOTICE);
//if(!defined('ecommerceShop')) die();
require_once ('ecommerce/command/Command.php');
require_once ('ecommerce/domain/News.php');
require_once ('ecommerce/domain/Products.php');
//require_once ('ecommerce/classes/class.PagerMysql.php');
require_once ('ecommerce/mapper/PaginationFactory.php');

class Main extends Command {
    function doExecute(\ecommerce\controller\Request $request) {

        $page   = $request->getProperty( 'page' ); // получаем поле PAGE из формы
        if( ! $page ) { // если страница не установлена
            $page = 1; // присваиваем 1
        }
        // создаем объект для поиска новостей и вызываем для этого метод
        $news = \ecommerce\domain\News::findAll();
        // сохраняем объект для передачи во вьюшку
        $request->setObject('news', $news);

//        $products = \ecommerce\domain\Products::findAll();
//        $p = new \ecommerce\classes\PagerMysql('table_products', ' WHERE visible=1','',10,3);

        $products = \ecommerce\domain\Products::paginationMysql($page); // запрост на постраничную навигацию
        $request->setObject('products',$products);
//        echo "<tt><pre> - djflskdjf - ".print_r($page, true). "</pre></tt>";
    }
}
?>