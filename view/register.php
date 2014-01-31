<?php
/*********************
 * register.php
 *
 * CSCI S-75
 * Project 1
 * Alex Spivakovsky
 *
 * register view
 *********************/

require_once('../includes/helper.php');
render('header', array('title' => 'C$75 Finance'));
?>

<form method="POST" action="home" onsubmit="return validateForm();">
    </br>
    E-mail address: <input type="text" name="email" maxlength="30" />
    </br>
    </br>
    Password: <input type="password" name="password" size="20" maxlength="20" />
    </br>
    </br>
    <input type="submit" name="register" value="Register" />
</form>

<script type='text/javascript'>
// <! [CDATA[

// validates if valid email address and password have been entered
function validateForm()
{
    var isValid = true;

    // check if the correct email was entered
    emailField = $("input[name=email]");
    
    // email address must be of	the form username@domain.tld or	username@subdomain.domain.tld,
    // where tld contains only alphabetical characters,	subdomain and domain contain 
    // only alphanumeric characters, and username contains only alphanumeric characters,
    // dots, underscores, hyphens, and/or pluses, between 6 and 30 characters
    var reg = /^\w+([-+_.]\w+)*@(\w+([.\w+]))|\w+\.[a-zA-z].{6,30}$/;
    if (!reg.test(emailField.val())) {
	alert("Please enter valid email address.");
        isValid = false;
    }

    // check if the correct password was entered
    passwordField = $("input[name=password]");

    // password must contain at least one number, one lowercase and one uppercase letter,
    // and be between 6 and 20 characters
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}/;

    if (!re.test(passwordField.val())) {
	alert("The password must contain at least one number, "
            + "one lowercase, one uppercase letter, "
                + "and must be between 6 and 20 characters long.");
	isValid = false;
    }

    return isValid;		
}

// set the focus to the email field (located by id attribute)
$("input[name=email]").focus();

// ]] >
</script>

<?php
render('footer');
?>
