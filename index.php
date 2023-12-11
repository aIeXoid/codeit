<?php 
require_once 'DB.php';
require_once 'addBidAction.php';

$db = DB::getInstance();

$addBidAction = new AddBidAction($db);

if(!empty($_POST)){
    $addBidAction->addBid($_POST);
}
?>