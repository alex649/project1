<?php
/*********************
 * index.php
 *
 * CSCI S-75
 * Project 1
 * Chris Gerber
 *
 * Dispatcher for MVC
 *********************/
session_start();

if (isset($_GET["page"]))
{
    $page = $_GET["page"];
}
else
{
    $page = "home";
}

$path = __DIR__ . '/../controller/' . $page . '.php';

if (file_exists($path))
{
    require($path);
}

?>
