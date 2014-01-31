<?php
/*********************
 * sell.php
 *
 * CSCI S-75
 * Project 1
 * Chris Gerber
 *
 * Sell shares controller
 *********************/

require_once('../model/model.php');
require_once('../includes/helper.php');

$symbol;

if (isset($_SESSION['userid']))
{
    $userid = (int)$_SESSION['userid'];

    foreach($_POST as $key=>$value) {
        $symbol = $key;
    }

    // buy shares
    sell_shares($userid, $symbol, &$error);

    // get the list of holdings for user
    $holdings = get_user_shares($userid);
	
    render('portfolio', array('holdings' => $holdings));
}
else
{
    render('login');
}
?>
