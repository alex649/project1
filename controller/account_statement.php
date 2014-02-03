<?php
/*********************
 * account_statement.php
 *
 * CSCI S-75
 * Project 1
 * Alex Spivakovsky
 *
 * Account Statement controller
 *********************/

require_once('../model/model.php');
require_once('../includes/helper.php');

if (isset($_SESSION['userid']))
{
	// get the list of holdings for user
	$userid = (int)$_SESSION['userid'];
	$balance = get_user_balance($userid, &$error);

	if (isset($error))
	{
	    print $error;
	}
	
	render('account_statement', array('balance' => $balance));
}
else
{
	render('login');
}
?>
