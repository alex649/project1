<?php
require_once('../includes/helper.php');
render('header', array('title' => 'Account Statement'));
?>

<h1>Account Balance</h1>

<?php

print "Balance:             ";
print htmlspecialchars($balance);

?>

<br>
<br>
<p>Back to <a href="home">Home Page</a></p>

<?php
render('footer');
?>
