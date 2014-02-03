<?php
/*********************
 * home.php
 *
 * CSCI S-75
 * Project 1
 * Alex Spivakovsky
 *
 * Default controller
 *********************/

require_once('../includes/helper.php');
require_once('../model/model.php');

if (isset($_POST['register']))
{
    if (isset($_POST['email']) &&
	isset($_POST['password']))
    {
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        $data = register_user($email, $password, &$error);

	if (isset($error))
	{
	    print $error;
	}

        if ($data['userid'] > 0)
        {
	    $_SESSION['userid'] = $data['userid'];
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
}
elseif (isset($_POST['login']))
{
    if (isset($_POST['email']) &&
	isset($_POST['password']))
    {
	$email = htmlspecialchars($_POST['email']);
	$password = htmlspecialchars($_POST['password']);
	
	$data = login_user($email, $password, &$error);

	if (isset($error))
	{
	    print $error;
	}

	if ($data['userid'] > 0)
	{
	    $_SESSION['userid'] = $data['userid'];
	    render('home', array('data' => $data));
	}
	else
	{
	?>	
	    <script type='text/javascript'>
	        alert("Unable to connect to database.  Please check username and password.");
	    </script>
	<?
	    render('login');    
	}
    }
    else
    {
	render('login');
    }
}
elseif (isset($_SESSION['userid']))
{
    render('home');
}
else
{
    render('login');
}
?>
