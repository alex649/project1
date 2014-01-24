<?php
/*********************
 * portfolio.php
 *
 * CSCI S-75
 * Project 1
 * Chris Gerber
 *
 * Portfolio controller
 *********************/

require_once('../model/model.php');
require_once('../includes/helper.php');

if (isset($_SESSION['userid']))
{
    $userid = (int)$_SESSION['userid'];
    $symbol = $_POST['symbol'];
    // $name = $_POST['name'];
    $last_trade = $_POST['last_trade'];
    $shares = $_POST['shares'];

    // buy shares
    buy_shares($userid, $symbol, $last_trade, $shares, &$error);

    // get the list of holdings for user
    $holdings = get_user_shares($userid);
	
    render('portfolio', array('holdings' => $holdings));
}
else
{
    render('login');
}
?>
