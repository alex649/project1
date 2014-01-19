<?php
require_once('../includes/helper.php');
render('header', array('title' => 'Password Hash Helper :)'));
?>

Password hash: <?= htmlspecialchars($pwdhash) ?><br />

<?php
render('footer');
?>
