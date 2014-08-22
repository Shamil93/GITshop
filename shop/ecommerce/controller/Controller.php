<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 19:25
 */
namespace ecommerce\controller;

require_once( "ecommerce/controller/ApplicationHelper.php" );
require_once( "ecommerce/controller/Request.php" );
require_once( "ecommerce/domain/ObjectWatcher.php" );

/**
 * Class Controller
 * @package ecommerce\controller
 * На переднем плане - запускается первым
 */
class Controller {
    private $applicationHelper;

    private function __construct() {}

    /**
     * Запускается из стартового скрипта
     */
    static function run() {
        $instance = new Controller(); // синглтон
        $instance->init();            // метод для парсинга xml в список команд/вьюшек и статусов
        $instance->handleRequest();   // метод для обработки команд при запуске стартового скрипта
    }

    /**
     * Получает список команд/вьюшек/статусов и дескриптора базы данных из файла xml
     * и записывает в соответствующие файлы - кэширует
     */
    function init() {
        $applicationHelper = ApplicationHelper::instance(); // синглтон
        $applicationHelper->init();                         // основной метод
    }

    /**
     * Получает список параметров запроса, получает кэшированные данные из файла
     * находит соответствующие команды - выполняет их и вызывает вьюшки
     */
    function handleRequest() {
        $request = new Request();                                           // получаем параметры запроса и дополнительные методы
        $app_c = \ecommerce\base\ApplicationRegistry::appController();   // получаем кэшированные данные ( controllerMap - карту приложения, getCommand(), getView(), getResource(), getForward(), resolveCommand() )
        while( $cmd = $app_c->getCommand( $request ) ) {                    // выполняем поиск команд
            $cmd->execute( $request );                                      // выполняем метод Execute( doExecute ) полученной команды ( подкласс Command )
//            echo "<tt><pre>".print_r($cmd, true)."</pre></tt>";
        }
        \ecommerce\domain\ObjectWatcher::instance()->performOperations(); // метод для UPDATE или INSERT
        $this->invokeView( $app_c->getView( $request ) );                   // выполняем поиск вьюшки и включаем ее
    }

    /**
     * Получает название вьюшки и включает ее
     * @param $target
     */
    function invokeView( $target ) {
        include( "ecommerce/view/$target.php" );
        exit;
    }
}
//Controller::run();
?>