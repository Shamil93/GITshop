<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/08/14
 * Time: 22:02
 */

/**
 * Для постраничной навигации
 * @param $page - номер страницы
 * @param $pageLink - количество ссылок слева/справа от текущей
 * @param $number - число ссылок на страницы
 * @param null $parameters
 * @param $total - общее количество элементов в выборке
 * @return string - возвращаем строку
 */
function pager( $page, $pageLink, $number, $total, $parameters=null ) {

    $return_page = "";
    // Print reference "Back", if this is not first page
    // Reference to first page
//    if($page != 1) {
    if($total > $pageLink && $page != 1) {
        $return_page .= "<li><a href='$_SERVER[PHP_SELF]".
            "?page=1{$parameters}'>".
            "&lt;&lt;</a></li><li><p class=\"nav-point\">...</p></li>";

        $return_page .= "<li><a href='$_SERVER[PHP_SELF]".
            "?page=".($page - 1)."{$parameters}'>".
            "&lt;</a></li><li><p class=\"nav-point\">...</p></li>";
    }
    // Print previous elements
    if($page > $pageLink + 1) {
        for($i = $page - $pageLink; $i < $page; $i++)  {
            $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$i{$parameters}'>$i</a></li>";
        }
    } else {
        for($i = 1; $i < $page; $i++) {
            $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$i{$parameters}'>$i</a></li>";
        }
    }

    if ($number > 1) { // если количество ссылок на странице больше одной, то выводим
        // Print current element
        $return_page .= "<li><a class='pstr-active' href='$_SERVER[PHP_SELF]?page=$i{$parameters}'>$i</a></li>";
    }
    // Print next element
    if($page + $pageLink < $number) {
        for($i = $page + 1; $i <= $page + $pageLink; $i++) {
            $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$i{$parameters}'>$i</a></li>";
        }
    } else {
        for($i = $page + 1; $i <= $number; $i++) {
            $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$i{$parameters}'>$i</a></li>";
        }
    }

    // Print reference "Forward", if it is not last page
//    if($page != $number) {
    if($total > $pageLink && $page != $number) {
        $return_page .= "<li><p class=\"nav-point\">...</p><li><a href='".
            "$_SERVER[PHP_SELF]?page=".
            ($page + 1)."{$parameters}'>".
            "&gt;</a></li>";
        // Reference for last page
        $return_page .= "<li><p class=\"nav-point\">...</p></li><li><a href='$_SERVER[PHP_SELF]".
            "?page=$number{$parameters}'>".
            "&gt;&gt;</a></li>";
    }
    return $return_page;
}