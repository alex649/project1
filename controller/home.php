<?php
/*********************
 * home.php
 *
 * CSCI S-75
 * Project 1
 * Chris Gerber
 *
 * Default controller
 *********************/

require_once('../includes/helper.php');

if (isset($_SESSION['userid']))
{
    render('home');
}
else
{
    render('login');
}
?>
