<?php
/**
 * @desc entry point for the app
 * class loading and database init happens here
 */
require_once("cBootStraper.php");

$BootStraper = new \Alexwebgr\SocialGraph\cBootStraper();
$BootStraper->loadClasses();

$Database = new \Alexwebgr\SocialGraph\Core\cDatabase();

if($Database->getDBError())
{
    $Dispatcher = new \Alexwebgr\SocialGraph\Core\cDispatcher();
    $Dispatcher->setError($Database->getDBError());
    $Dispatcher->render();
}
else
{
    $Controller = new \Alexwebgr\SocialGraph\Api\Controller\cController();
    $Controller->delegate();
}
