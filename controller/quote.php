<?php
/*******************
 * quote.php
 *
 * CSCI S-75
 * Project 1
 * Chris Gerber
 * Alex Spivakovsky
 *
 * Quote controller
 *******************/

require_once('../model/model.php');
require_once('../includes/helper.php');

if (isset($_POST["symbol"]))
	$quote_data = get_quote_data(urlencode($_POST["symbol"]));

render('quote', array('quote_data' => $quote_data));
?>
