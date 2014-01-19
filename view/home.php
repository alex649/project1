<?php
require_once('../includes/helper.php');
render('header', array('title' => 'C$75 Finance'));
?>

<ul>
	<li><a href="quote/GOOG">Get quote for Google</a></li>
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
