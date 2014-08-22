<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/05/14
 * Time: 21:39
 */

namespace ecommerce\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "ecommerce/base/Exceptions.php" );

/**
 * Class PaginationFactory
 * Выполняет функцию постраничной навигации
 * Необходим для таких плагинов, как гостевая книга, комментарии блогов
 * @package ecommerce\mapper
 */
abstract class PaginationFactory {

    protected function __construct() {}

    /**
     * Необходимые методы для работы постраничной навигации
     */
    abstract protected function getTotal();
    abstract protected function getPageNumber();
    abstract protected function getPageLink();
    abstract protected function getParameters();

    /**
     * Методы, которые лучше бы объявить здесь,
     * чтобы в наследовании о них не забыть
     */
    abstract function getStatement($str);
    abstract function buildWhere( IdentityObject $obj );
    abstract function bindWhereValue( $sth, $obj);

    /**
     * * Возвращает постраничную навигацию типа: " << ... < ... 1 2 3 ... > ... >> "
     * @return string
     */
    public function printPage() {
        // (string) - возвращаем результат
        $returnPage = "";
        // Для передачи позиции текущей страницы
        $page = intval( $_GET['page'] );
        if( empty( $page ) ) $page = 1;
        $number = (int)( $this->getTotal() / $this->getPageNumber() );
        if( (float)( $this->getTotal() / $this->getPageNumber() ) - $number != 0 ) { $number++; }

//        // Двойная стрелка для перелистывания в начало
//        $returnPage .= "<a href='".
//                        "?page=1{$this->getParameters()}'>".
//                        "&lt;&lt;</a> ... ";

        // Если это не первая страница - то выводим стрелку для одиночного
        // пролистывания
        if( $page != 1 ) {
            // Двойная стрелка для перелистывания в начало
            $returnPage .= "<a href='".
                "?cmd=Guestbook&page=1{$this->getParameters()}'>".
                "&lt;&lt;</a> ... ";

            $returnPage .= " <a href='"
                ."?cmd=Guestbook&page=".($page-1)."{$this->getParameters()}'>"
                ."&lt;</a> ... ";
        }

        // Если текущая страница больше, чем желаемое количество + 1 ( 4 ), то
        // указываем ссылки на предыдущие страницы, пример:
        // страница 5 > желаемого количества отображаемых ссылок плюс 1 4
        // в цикле проходим 5-3(2) < 5 --> выводим ссылки на страницы 2, 3, 4
        if( $page > $this->getPageLink() + 1 ) {
            for( $i = $page - $this->getPageLink(); $i < $page; $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>$i</a> ";
            }
            // Если меньше ( 4 ), то от 1 до 3-х - указываем ссылки на страницы 1, 2, 3
            // если page меньше 4-х, то и выводим меньше
        } else {
            for( $i = 1; $i < $page; $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>$i</a> ";
            }
        }

        // Указываем текущую страницу
        $returnPage .= "$i ";

        // Если страница 1-я, то указываем ссылки на страницы справа - 2, 3, 4
        if( $page + $this->getPageLink() < $number ) {
            for( $i = $page + 1; $i <= $page + $this->getPageLink(); $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>$i</a> ";
            }
            // Если уже 2-я страница и более, то указываем сслылки на страницы 3, 4, 5
        } else {
            for( $i = $page + 1; $i <= $number; $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>$i</a> ";
            }
        }

        // Если это не последняя страница, то выводим стрелку для
        // единичного перелистывания
        if( $page != $number ) {
            $returnPage .= " ... <a href='?cmd=Guestbook&page="
                .($page+1)
                ."{$this->getParameters()}'>"
                ."&gt;</a>";
            // Двойная стрелка для перелистывания в конец
            $returnPage .= " ... <a href='"
                ."?cmd=Guestbook&page=$number{$this->getParameters()}'>"
                ."&gt;&gt;</a>";
        }

//        // Двойная стрелка для перелистывания в конец
//        $returnPage .= " ... <a href='"
//            ."?page=$number{$this->getParameters()}'>"
//            ."&gt;&gt;</a>";


        return $returnPage;
    }


    /**
     * Возвращает постраничную навигацию типа: " Предыдущая Следующая 1 2 3 "
     * @return string
     */
    public function printPageNav() {
        // (string) - возвращаем результат
        $returnPage = "";
        // Для передачи позиции текущей страницы
        $page = intval( $_GET['page'] );
        if( empty( $page ) ) $page = 1;
        $number = (int)( $this->getTotal() / $this->getPageNumber() );
        if( (float)( $this->getTotal() / $this->getPageNumber() ) - $number != 0 ) { $number++; }

        $returnPage .= "<span class='pagination'>
                            <span class='pagination-prevnext'>";
        // Если это первая страница - то выводим <span>
        if( $page == 1 ) {
            $returnPage .= "<span class='pagination-prev-inactive'>&nbsp;Предыдущая&nbsp;</span>";
            // Если это не первая страница - то выводим стрелку для одиночного
            // пролистывания
        } else {
            $returnPage .= "<a class='pagination-prev' href='"
                ."?cmd=Guestbook&page=".($page-1)."{$this->getParameters()}'>&nbsp;"
                ."Предыдущая&nbsp;</a>";
        }
        // Если это последняя страница, то выводим <span>
        if( $page == $number ) {
            $returnPage .= "<span class='pagination-next-inactive'>&nbsp;Следующая&nbsp;</span>";
            // Если это не последняя страница, то выводим стрелку для
            // единичного перелистывания
        } else {
            $returnPage .= "<a class='pagination-next' href='?cmd=Guestbook&page="
                .($page+1)
                ."{$this->getParameters()}'>&nbsp;"
                ."Следующая&nbsp;</a>";
        }

        $returnPage .= "</span>&nbsp;<span class='pagination-numbers'>";

        // Если текущая страница больше, чем желаемое количество + 1 ( 4 ), то
        // указываем ссылки на предыдущие страницы, пример:
        // страница 5 > желаемого количества отображаемых ссылок плюс 1 4
        // в цикле проходим 5-3(2) < 5 --> выводим ссылки на страницы 2, 3, 4
        if( $page > $this->getPageLink() + 1 ) {
            for( $i = $page - $this->getPageLink(); $i < $page; $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>&nbsp;$i&nbsp;</a>";
            }
            // Если меньше ( 4 ), то от 1 до 3-х - указываем ссылки на страницы 1, 2, 3
            // если page меньше 4-х, то и выводим меньше
        } else {
            for( $i = 1; $i < $page; $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>&nbsp;$i&nbsp;</a>";
            }
        }

        // Указываем текущую страницу
        $returnPage .= "&nbsp;<span class='pagination-current'>$i</span>&nbsp;";

        // Если страница 1-я, то указываем ссылки на страницы справа - 2, 3, 4
        if( $page + $this->getPageLink() < $number ) {
            for( $i = $page + 1; $i <= $page + $this->getPageLink(); $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>&nbsp;$i&nbsp;</a>";
            }
            // Если уже 2-я страница и более, то указываем сслылки на страницы 3, 4, 5
        } else {
            for( $i = $page + 1; $i <= $number; $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>&nbsp;$i&nbsp;</a>";
            }
        }

        $returnPage .= "</span></span>";
        return $returnPage;
    }

    /**
     * * Возвращает постраничную навигацию типа: " [1-10][11-20] ... [31-40][41-50] "
     * @return string
     */
    public function __toString() {
        $returnPage = "";

        $page = intval( $_GET['page'] );
        if( empty( $page ) ) { $page = 1; }

        $number = (int)($this->getTotal() / $this->getPageNumber() );
        // Если число нечетное, то прибавляем еще единицу
        if( (float)( $this->getTotal() / $this->getPageNumber() ) - $number != 0 ) { $number++; }

        // Если страница 5 и более, то выводим ссылку на блок из 10-ти страниц
        // [1-10] - всего желаемое отображение 10-ти блоков на странице
        if( $page - $this->getPageLink() > 1 ) {
            $returnPage .= "<a href='"
                ."?cmd=Guestbook&page=1{$this->getParameters()}'>"
                ."[1-{$this->getPageNumber()}]"
                ."</a>&nbsp;&nbsp; ... &nbsp;&nbsp;";
            // Выводим три ссылки на следующие страницы
            for( $i = $page - $this->getPageLink(); $i < $page; $i++ ) {
                $returnPage .= "&nbsp;<a href='"
                    ."?cmd=Guestbook&page=$i{$this->getParameters()}'>"
                    ."[".( ( $i - 1 ) * $this->getPageNumber() + 1 )
                    ."-".$i * $this->getPageNumber()
                    ."]</a>&nbsp;";
            }
            // Если страница 4 и до 2-х
        } else {
            for( $i = 1; $i < $page; $i++ ) {
                $returnPage .= "&nbsp;<a href='"
                    ."?cmd=Guestbook&page=$i{$this->getParameters()}'>"
                    ."[".( ( $i - 1 ) * $this->getPageNumber() + 1 )
                    ."-".$i * $this->getPageNumber()
                    ."]</a>&nbsp;";
            }
        }

        // Если все меньше 11-ти (их всего 11)
        if( $page + $this->getPageLink() < $number ) {
            // Пока страницы меньше или равно сумме страницы + 10, т.е. 1 <= 11
            for( $i = $page; $i <= $page + $this->getPageLink(); $i++ ) {
                // Если страница попдает в текущий блок
                if( $page == $i ) {
                    $returnPage .= "&nbsp;["
                        .( ( $i - 1) * $this->getPageNumber() + 1 )
                        ."-".$i * $this->getPageNumber()
                        ."]&nbsp;";
                } else {
                    // Если не текущая, то выводим три блока по 10
                    $returnPage .= "&nbsp;<a href='"
                        ."?cmd=Guestbook&page=$i{$this->getParameters()}'>"
                        ."[".( ( $i - 1 ) * $this->getPageNumber() + 1 )
                        ."-". $i * $this->getPageNumber()
                        ."]</a>&nbsp;";
                }
            }
            // Выводим ссылку на последнюю страницу
            $returnPage .= "&nbsp; ... &nbsp;&nbsp;"
                ."<a href='"
                ."?cmd=Guestbook&page=$number{$this->getParameters()}'>"
                ."[".( ( $number - 1 ) * $this->getPageNumber() + 1 )
                ."-{$this->getTotal()}]</a>&nbsp;";
        } else {
            for( $i = $page; $i <= $number; $i++ ) {
                // Если это текущая страница
                if( $number == $i ) {
                    // Если это последний блок страниц
                    if( $page == $i ) {
                        // Указываем текущий последний блок страниц
                        $returnPage .= "&nbsp;["
                            .( ( $i - 1 ) * $this->getPageNumber() + 1 )
                            ."-{$this->getTotal()}]&nbsp;";
                    } else {
                        // Если не последний блок не текущий, то отображаем его,
                        // как ссылки
                        $returnPage .= "&nbsp;<a href='"
                            ."?cmd=Guestbook&page=$i{$this->getParameters()}'>"
                            ."[".( ( $i - 1 ) * $this->getPageNumber() + 1 )
                            ."-{$this->getTotal()}]</a>&nbsp;";
                    }
                } else {
                    // Если это не последняя текущая страница,
                    // но текущая, то отображаем ее
                    if( $page == $i ) {
                        $returnPage .= "&nbsp;["
                            .( ( $i - 1 ) * $this->getPageNumber() + 1 )
                            ."-".$i * $this->getPageNumber()
                            ."]&nbsp;";
                    } else {
                        // Отображаем блоки страниц справа от текущей
                        $returnPage .= "&nbsp;<a href='"
                            ."?cmd=Guestbook&page=$i{$this->getParameters()}'>"
                            ."[".( ( $i - 1 ) * $this->getPageNumber() + 1 )
                            ."-".$i * $this->getPageNumber()
                            ."]</a>&nbsp;";
                    }
                }
            }
        }
        return $returnPage;
    }
}
?>