<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 14:26
 */
namespace ecommerce\command;

require_once( "ecommerce/command/Command.php" );

class DefaultCommand extends Command {
    function doExecute( \ecommerce\controller\Request $request ) {
        $request->addFeedback( "Welcome to E-commerce" );
        return self::statuses('CMD_OK');
    }
}
?>