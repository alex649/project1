<?php
/*******************
 * login.php
 *
 * CSCI S-75
 * Project 1
 * Chris Gerber
 *
 * Login controller
 *******************/

require_once('../model/model.php');
require_once('../includes/helper.php');

if (isset($_POST['email']) &&
	isset($_POST['password']))
{
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	$data = login_user($email, $password, $error);

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
	}
}
else
{
	render('login');
}
?>
