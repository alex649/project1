<?php
/*********************
 * portfolio.php
 *
 * CSCI S-75
 * Project 1
 * Alex Spivakovsky
 *
 * Portfolio controller
 *********************/

require_once('../model/model.php');
require_once('../includes/helper.php');

if (isset($_SESSION['userid']))
{
    // get the list of holdings for user
    $userid = (int)$_SESSION['userid'];

    // variables for buying shares
    $symbol = $_POST['symbol'];

    // only whole integers are allowed for the number of shares
    $shares = intval($_POST['shares']);

    $result = get_quote_data($symbol);
    $last_trade = $result['last_trade'];

    if (isset($last_trade))
    {
        buy_shares($userid, $symbol, $last_trade, $shares, &$error);
	
	if (isset($error))
	{
	    print $error;

	    return;
	}
    }

    $i = 0;
    foreach($_POST as $key=>$value) 
    {
        $symbol = $key;
	$i++;
    }

    if (isset($symbol) && ($i < 2))
    {	
        // sell shares
        sell_shares($userid, $symbol, &$error);

	if (isset($error))
	{
	    print $error;

	    return;
	}
    }

    $holdings = get_user_shares($userid, &$error);

    if (isset($error))
    {
	print $error;

	return;
    }
	
    render('portfolio', array('holdings' => $holdings));
}
else
{
    render('login');
}
?>
