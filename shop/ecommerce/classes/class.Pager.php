<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 18.12.12
 * Time: 22:49
 * To change this template use File | Settings | File Templates.
 */
namespace ecommerce\classes;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "ecommerce/base/Registry.php" );

/**
 * Root class for inheritance
 */
class Pager {
    protected static $pdo;

  /**
   * Construct of class.
   */
  protected function __construct() {
       if( isset( self::$pdo ) ) {
           return self::$pdo;
       } else {
           self::$pdo = \ecommerce\base\DBRegistry::getDB();
           return self::$pdo;
       }
  }

  protected function getStatement( $stmt ) {
      return self::$pdo->prepare( $stmt );
  }

  protected function getTotal() {
    // Total quantity of records
  }

  protected function getPnumber() {
    // Quantity of position on page
  }

  protected function getPageLink() {
    // Quantity of reference from left and right
    // from current page
  }

  protected function getParameters() {
    // Additional parameters, which
    // is necessarely to take along by reference
  }

  // References for another pages
  public function __toString() {
    // String for returning of result
    $return_page = "";

    // By GET-parameters page
    // number of current page sent
    $page = intval($_GET['page']);
    if(empty($page)) $page = 1;

    // Calculate number of page in system
    $number = (int)($this->getTotal()/$this->getPnumber());
    if((float)($this->getTotal()/$this->getPnumber()) - $number != 0) {
      $number++;
    }
    // Check if exists reference on the left
    if($page - $this->getPageLink() > 1)  {
      // Start pages form left
      $return_page .= "<a href=$_SERVER[PHP_SELF]".
                      "?page=1{$this->getParameters()}>
                      [1-{$this->getPnumber()}]
                      </a>&nbsp;&nbsp;...&nbsp;&nbsp;";
      // Exist
      for($i = $page - $this->getPageLink(); $i < $page; $i++) {
        // Three pages after ...
        $return_page .= "&nbsp;<a href=$_SERVER[PHP_SELF]".
                        "?page=$i{$this->getParameters()}>
                        [".(($i - 1) * $this->getPnumber() + 1).
                        "-".$i * $this->getPnumber()."]
                        </a>&nbsp;";
      }
    } else {
      // Not exist
      for($i = 1; $i < $page; $i++) {
        // Pages until first page
        $return_page .= "&nbsp;<a href=$_SERVER[PHP_SELF]".
                        "?page=$i{$this->getParameters()}>
                        [".(($i - 1) * $this->getPnumber() + 1).
                        "-".$i * $this->getPnumber()."]
                        </a>&nbsp;";
      }
    }

    // Check if exists reference on the right
    if($page + $this->getPageLink() < $number)  {
      // Exist
      for($i = $page; $i <= $page + $this->getPageLink(); $i++)  {
        if($page == $i)
          // Current page
          $return_page .= "&nbsp;[".
              (($i - 1) * $this->getPnumber() + 1).
              "-".$i*$this->getPnumber()."]&nbsp;";
        else
          // Three pages before ...
          $return_page .= "&nbsp;<a href=$_SERVER[PHP_SELF]".
                          "?page=$i{$this->getParameters()}>
                          [".(($i - 1) * $this->getPnumber() + 1).
                          "-".$i * $this->getPnumber()."]
                          </a>&nbsp;";
      }
      // End pages form right
      $return_page .= "&nbsp;...&nbsp;&nbsp;".
          "<a href=$_SERVER[PHP_SELF]".
          "?page=$number{$this->getParameters()}>
          [".(($number - 1)*$this->getPnumber() + 1).
          "-{$this->getTotal()}]
          </a>&nbsp;";
    } else {
      // Not exist
      for($i = $page; $i <= $number; $i++) {
        if($number == $i)  {
          if($page == $i)
            // Current last page
            $return_page .= "&nbsp;[".
                (($i - 1) * $this->getPnumber() + 1).
                "-{$this->getTotal()}]&nbsp;";
          else
            // Last page
            $return_page .= "&nbsp;<a href=$_SERVER[PHP_SELF]".
                            "?page=$i{$this->getParameters()}>
                            [".(($i - 1) * $this->getPnumber() + 1).
                            "-{$this->getTotal()}]
                            </a>&nbsp;";

        } else {
          if($page == $i)
            // Current page from right
            $return_page .= "&nbsp;[".
                (($i - 1) * $this->getPnumber() + 1).
                "-".$i * $this->getPnumber()."]&nbsp;";
          else
            // Another page until last page
            $return_page .= "&nbsp;<a href=$_SERVER[PHP_SELF]".
                            "?page=$i{$this->getParameters()}>
                            [".(($i - 1) * $this->getPnumber() + 1).
                            "-".$i * $this->getPnumber()."]
                            </a>&nbsp;";
        }
      }
    }
    return $return_page;
  }

  /**
   * Alternative kind of pager navigation
   * @return array
   */
  public function printPage() {
    // String for return result
    $return_page = "";

    // Page transfer by GET-parameter
    // of current page
    $page = intval($_GET['page']);
    if(empty($page)) $page = 1;
    // Calculate number of pages in system
    $number = (int)($this->getTotal()/$this->getPnumber());
//    echo "<tt><pre>".print_r($number, TRUE)."</pre></tt>";
//    echo "<tt><pre>".print_r($this->getTotal(), TRUE)."</pre></tt>";
//    echo "<tt><pre>".print_r($this->getPnumber(), TRUE)."</pre></tt>";
    if((float)($this->getTotal()/$this->getPnumber()) - $number != 0) $number++;
//    echo "<tt><pre>".print_r($number, TRUE)."</pre></tt>";
    // Reference to first page
    $return_page .= "<a href='$_SERVER[PHP_SELF]".
                    "?page=1{$this->getParameters()}'>".
                    "&lt;&lt;</a> ... ";
//    echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    // Print reference "Back", if this is not first page
    if($page != 1) $return_page .= " <a href='$_SERVER[PHP_SELF]".
                                    "?page=".($page - 1)."{$this->getParameters()}'>".
                                    "&lt;</a> ... ";
//    echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    // Print previous elements
    if($page > $this->getPageLink() + 1) {
      for($i = $page - $this->getPageLink(); $i < $page; $i++)  {
        $return_page .= "<a href='$_SERVER[PHP_SELF]?page=$i{$this->getParameters()}'>$i</a> ";
      }
//      echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    } else {
      for($i = 1; $i < $page; $i++) {
        $return_page .= "<a href='$_SERVER[PHP_SELF]?page=$i{$this->getParameters()}'>$i</a> ";
      }
//      echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    }

    // Print current element
    $return_page .= "$i ";
    // Print next element
    if($page + $this->getPageLink() < $number) {
      for($i = $page + 1; $i <= $page + $this->getPageLink(); $i++) {
        $return_page .= "<a href='$_SERVER[PHP_SELF]?page=$i{$this->getParameters()}'>$i</a> ";
      }

//        echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    } else {
      for($i = $page + 1; $i <= $number; $i++) {
        $return_page .= "<a href='$_SERVER[PHP_SELF]?page=$i{$this->getParameters()}'>$i</a> ";
      }

//        echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    }

    // Print reference "Forward", if it is not last page
    if($page != $number) $return_page .= " ... <a href='".
                        "$_SERVER[PHP_SELF]?page=".
                        ($page + 1)."{$this->getParameters()}'>".
                        "&gt;</a>";

//        echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    // Reference for last page
    $return_page .= " ... <a href='$_SERVER[PHP_SELF]".
                    "?page=$number{$this->getParameters()}'>".
                    "&gt;&gt;</a>";

//        echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    return $return_page;
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
        $number = (int)( $this->getTotal() / $this->getPnumber() );
        if( (float)( $this->getTotal() / $this->getPnumber() ) - $number != 0 ) { $number++; }

        $returnPage .= "<span class='pagination'>
                            <span class='pagination-prevnext'>";
        // Если это первая страница - то выводим <span>
        if( $page == 1 ) {
            $returnPage .= "<span class='pagination-prev-inactive'>&nbsp;Предыдущая&nbsp;</span>";
            // Если это не первая страница - то выводим стрелку для одиночного
            // пролистывания
        } else {
            $returnPage .= "<a class='pagination-prev' href='$_SERVER[PHP_SELF]"
                ."?page=".($page-1)."{$this->getParameters()}'>&nbsp;"
                ."Предыдущая&nbsp;</a>";
        }
        // Если это последняя страница, то выводим <span>
        if( $page == $number ) {
            $returnPage .= "<span class='pagination-next-inactive'>&nbsp;Следующая&nbsp;</span>";
            // Если это не последняя страница, то выводим стрелку для
            // единичного перелистывания
        } else {
            $returnPage .= "<a class='pagination-next' href='$_SERVER[PHP_SELF]?page="
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
                $returnPage .= "<a href='$_SERVER[PHP_SELF]?page=$i{$this->getParameters()}'>&nbsp;$i&nbsp;</a>";
            }
            // Если меньше ( 4 ), то от 1 до 3-х - указываем ссылки на страницы 1, 2, 3
            // если page меньше 4-х, то и выводим меньше
        } else {
            for( $i = 1; $i < $page; $i++ ) {
                $returnPage .= "<a href='$_SERVER[PHP_SELF]?page=$i{$this->getParameters()}'>&nbsp;$i&nbsp;</a>";
            }
        }

        // Указываем текущую страницу
        $returnPage .= "&nbsp;<span class='pagination-current'>$i</span>&nbsp;";

        // Если страница 1-я, то указываем ссылки на страницы справа - 2, 3, 4
        if( $page + $this->getPageLink() < $number ) {
            for( $i = $page + 1; $i <= $page + $this->getPageLink(); $i++ ) {
                $returnPage .= "<a href='$_SERVER[PHP_SELF]?page=$i{$this->getParameters()}'>&nbsp;$i&nbsp;</a>";
            }
            // Если уже 2-я страница и более, то указываем сслылки на страницы 3, 4, 5
        } else {
            for( $i = $page + 1; $i <= $number; $i++ ) {
                $returnPage .= "<a href='$_SERVER[PHP_SELF]?page=$i{$this->getParameters()}'>&nbsp;$i&nbsp;</a>";
            }
        }

        $returnPage .= "</span></span>";
        return $returnPage;
    }
}
?>