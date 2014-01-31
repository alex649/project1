<?php
/*********************
 * home.php
 *
 * CSCI S-75
 * Project 1
 * Alex Spivakovsky
 *
 * home view
 *********************/

require_once('../includes/helper.php');
render('header', array('title' => 'C$75 Finance'));
?>

<ul>
	<li><a href="account_statement">Get Balance</a></li>
	<li><a href="portfolio">View Portfolio</a></li>
	<li><a href="logout">Logout</a></li>
</ul>
<form action=quote method=POST>
    Quote Symbol:<input type="text" name="symbol">
    <input type="submit" name="submit" value="Search">
</form>
<?php
render('footer');
?>
