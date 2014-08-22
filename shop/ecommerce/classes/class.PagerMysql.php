<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 19.12.12
 * Time: 18:19
 * To change this template use File | Settings | File Templates.
 */
namespace ecommerce\classes;
error_reporting( E_ALL & ~E_NOTICE );


//require_once("../../class/class.Database.php");
// Include parent class
require_once( "ecommerce/classes/class.Pager.php" );
// Include exception for error handling
require_once( "ecommerce/base/Exceptions.php" );


/**
 * Describe pager navigation for MySQL.
 */
class PagerMysql extends Pager {
  // Name of table
  protected $tablename;
  // WHERE-condition
  protected $where;
  // Sorting ORDER
  protected $order;
  // Quantity of position on the page
  private $pnumber;
  // Quantity or reference from left and right
  // from current page
  private $page_link;
  // Parameters
  protected $parameters;

  // Constructor
  public function __construct($tablename,
                              $where = "",
                              $order = "",
                              $pnumber = 10,
                              $page_link = 3,
                              $parameters = "")  {
    $this->tablename   = $tablename;
    $this->where       = $where;
    $this->order       = $order;
    $this->pnumber     = $pnumber;
    $this->page_link   = $page_link;
    $this->parameters  = $parameters;

    parent::__construct();
  }

    /**
     * Total quantity of position in list.
     * @throws \ecommerce\base\AppException
     */
    public function getTotal()  {
    // Form query for take
    // total quantity of records in table
    $query = "SELECT COUNT(*) FROM {$this->tablename}
              {$this->where}
              {$this->order}";

    $sth = $this->getStatement( $query );
    $result = $sth->execute();

    if( ! $result ) {
        throw new \ecommerce\base\AppException(  "Ошибка обращения к таблице
                              позиций - getTotal()");
    }
    return $sth->fetchColumn();
  }

  /**
   * Quantity of position on the page.
   * @return int
   */
  public function getPnumber() {
    // Quantity of position on the page
    return $this->pnumber;
  }

  /**
   * Quantity of position from left and right
   * form current page.
   * @return int
   */
  public function getPageLink() {
    // Quantity of references from left and right
    // from current page
    return $this->page_link;
  }

  /**
   * String to take along by reference
   * to the other page.
   * @return string
   */
  public function getParameters()  {
    // Additional parameters that
    // is necessarily to take by reference
    return $this->parameters;
  }


    /**
     * Return array of Files's strings
     * by number page $index
     * @return mixed
     * @throws \ecommerce\base\AppException
     */
    public function getPage() {
    // Current page
    $page = intval($_GET['page']);
    if(empty($page)) $page = 1;
    // Quantity records in file
    $total = $this->getTotal();
    // Calculate number of pages in system
    $number = (int)($total/$this->getPnumber());
    if((float)($total/$this->getPnumber()) - $number != 0) $number++;
    // Eject position of current page
    $arr = array();
    // Number, from whom begin choose
    // strings from file
    $first = ($page - 1) * $this->getPnumber();
    // Take positions for current page
    $query = "SELECT * FROM {$this->tablename}
              {$this->where}
              {$this->order}
              LIMIT $first, {$this->getPnumber()}";
    $sth = $this->getStatement( $query );
    $result = $sth->execute();

    if( ! $result ) {
      throw new \ecommerce\base\AppException(  "Ошибка обращения к таблице
                              позиций - getPage()");
    }
//      echo "<tt><pre>".print_r($arr, true)."</pre></tt>";


    // If have at least one element,
    // fill the array $arr
//    while( $arr[] = $sth->fetchAll() );
//    // Remove last 0 element of array $arr
//    unset($arr[count($arr) - 1]);
//    return $arr;

      return $sth->fetchAll();
  }
}
?>