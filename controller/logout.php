<?php
/********************
 * logout.php
 *
 * CSCI S-75
 * Project 1
 * Chris Gerber
 *
 * Logout controller
 ********************/

require_once('../includes/helper.php');

unset($_SESSION['userid']);
session_destroy();

render('login');
?>