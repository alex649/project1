<?php
/*******************
 * register.php
 *
 * CSCI S-75
 * Project 1
 * Alex Spivakovsky
 *
 * Register controller
 *******************/
error_reporting(E_ALL);
ini_set('display_errors', on);
require_once('../model/model.php');
require_once('../includes/helper.php');

if (isset($_POST['email']) &&
	isset($_POST['password']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    $data = register_user($email, $password, $error);

    if ($data['userid'] > 0)
    {
	render('home', array('data' => $data));
    }
    elseif ($data['userid'] == 0)
    {
    ?>	
    <script type='text/javascript'>
	alert("Username already taken.");

	render('register');
    </script>
    <?    
    }
    else
    {
	print $error;
	    
	render('register');
    }
}
else
{
    render('register');
}
?>
