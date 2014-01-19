<?php
require_once('../includes/helper.php');
render('header', array('title' => 'C$75 Finance'));
?>

<p>Click here to <a href="register">Register</a></p>

<form method="POST" action="login" onsubmit="return validateForm();">
    </br>
    E-mail address: <input type="text" name="email" />
    </br>
    </br>
    Password: <input type="password" name="password" />
    </br>
    </br>
    <input type="submit" value="Login" />
</form>

<script type='text/javascript'>
// <! [CDATA[

function validateForm()
{
	isValid = true;
	
	// check if the email address was entered (min=6: x@x.to)
	emailField = $("input[name=email]");
	
	if (emailField.val().length < 6) {
	    isValid = false;
	    document.write("Test");
	}

	/*
	return 0;
	if (isValid == false) {
	    return isValid;
	} else {
	    login_user(emailField.val(), passwordField.val());
	}
	*/

	return isValid;		
}

// set the focus to the email field (located by id attribute)
$("input[name=email]").focus();

// ]] >
</script>

<?php
render('footer');
?>
