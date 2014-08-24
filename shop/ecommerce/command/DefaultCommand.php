<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 14:26
 */
namespace ecommerce\command;
error_reporting(E_ALL &~ E_NOTICE);
//if(!defined('ecommerceShop')) die();
require_once ('ecommerce/command/Command.php');
require_once ('ecommerce/domain/News.php');
require_once ('ecommerce/domain/Products.php');
//require_once ('ecommerce/classes/class.PagerMysql.php');
require_once ('ecommerce/mapper/PaginationFactory.php');

require_once( "ecommerce/command/Command.php" );

class DefaultCommand extends Command {
    function doExecute( \ecommerce\controller\Request $request ) {
//        echo "<tt><pre> - djflskdjf - ".print_r($request, true). "</pre></tt>";
        $page   = $request->getProperty( 'page' ); // получаем поле PAGE из формы
        $go     = $request->getProperty('go');
        $sort   = $request->getProperty('sort');


        if( ! $page ) { // если страница не установлена
            $page = 1; // присваиваем 1
        }

        switch($sort) {
            case 'price-asc':
                $sorting = 'price ASC';
                $sort_name = 'От дешевых к дорогим';
                break;
            case 'price-desc':
                $sorting = 'price DESC';
                $sort_name = 'От дорогих к дешевым';
                break;
            case 'popular':
                $sorting = 'count DESC';
                $sort_name = 'Популярные';
                break;
            case 'news':
                $sorting = 'datetime DESC';
                $sort_name = 'Новинки';
                break;
            case 'brand':
                $sorting = 'brand ASC';
                $sort_name = 'По алфавиту';
                break;
            default:
                $sorting = 'products_id ASC';
                $sort_name = 'Нет сортировки';
                break;
        }

        if (isset($go)) {
            $ice_topper = $go;
        }


        $idobj = new \ecommerce\mapper\ProductsIdentityObject( 'visible' );
        if (!empty($ice_topper)) {
            $idobj->eq( '1' )->field($ice_topper)->eq('1');
        }
        $idobj->eq( '1' );
        $findGoCount = \ecommerce\domain\Products::findGoCount($idobj);
        if ($findGoCount->getTotal() > 0 ) {
            if (!empty($sorting)) {
                $products = \ecommerce\domain\Products::paginationMysql($page, $idobj, 'ORDER BY '.$sorting); // запрост на постраничную навигацию
            } else {
                $products = \ecommerce\domain\Products::paginationMysql($page, $idobj); // запрост на постраничную навигацию
            }
        } else {
            $request->addFeedback('Данный товар отсутствует!');
        }



        // создаем объект для поиска новостей и вызываем для этого метод
        $news = \ecommerce\domain\News::findAll();
        // сохраняем объект для передачи во вьюшку
        $request->setObject('news', $news);


        $request->setObject('products',$products);
//        echo "<tt><pre> - djflskdjf - ".print_r($page, true). "</pre></tt>";
    }
}
?>