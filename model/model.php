<?php
/*********************************
 * model.php
 *
 * CSCI S-75
 * Project 1
 * Chris Gerber
 *
 * Model for users and portfolios
 *********************************/

// database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'password');
define('DB_DATABASE', 'CS75Finance');

$initial_balance = 100000;
$pdo = NULL;

function connect_to_database()
{
    global $pdo;

    $dsn = "mysql:host=localhost;dbname=CS75Finance;charset=utf8";
    $opt = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );

    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $opt);

    return $pdo;
}

/*
 * register_user() - Create a new user account
 *
 * @param string $email
 * @param string $password
 * 
 * @return string $error
 */
function register_user($email, $password, &$error)
{
    global $initial_balance;
    global $pdo;

    $userid = 0;

    try
    {
        // connect to database
        $pdo = connect_to_database();
    }
    catch (Exception $e)
    {
        $error = "Could not connect to database.";
    }

    // prepare password hash
    $pwdhash = hash("SHA256",$password);
    $salt = mt_rand(37693, 100000);
    $pwdhash = crypt($pwdhash, $salt);
    $pwdhash = $salt . ":" . $pwdhash;

    try
    {
	// add new user into users database
        $query = sprintf("INSERT INTO `CS75Finance`.`users` (`username`,`password`) VALUES ('$email', '$pwdhash')");
	$pdo->query($query);

        // check that user has been added to the database
        // verify email and password pair
        $query = sprintf("SELECT * FROM users WHERE LOWER(username)='%s' AND password='%s'", strtolower($email), $pwdhash);
        $results = $pdo->query($query);
    }
    catch (Exception $e) 
    {
        $error = 'Your account could not be registered. Did you forget your password?';
    }
	
    // print results
    print "\nUsers\n";
    print "Id\tUsername\tPassword\n";
    foreach ($results as $result)
    {
	$userid = $result['id'];
    } 

    try
    {
        // add $100,000 to the account upon signup
        $query = sprintf("INSERT INTO `CS75Finance`.`portfolio` (`id`,`balance`) VALUES ('$userid', '$initial_balance')");
        $pdo->query($query);
    }
    catch (Exception $e) 
    {
        $error = 'Could not add free gift to your account.';
    }

    return $userid;
}

function login_user($email, $password, &$error)
{
    global $pdo;

    $userid = 0;

    try
    {
        // connect to database
        $pdo = connect_to_database();
    }
    catch (Exception $e)
    {
        $error = "Could not connect to database.";
    }

    // prepare password hash
    $pwdhash = hash("SHA256", $password);

    // get password hash from database
    try
    {
        $query = sprintf("SELECT * FROM users WHERE LOWER(username)='%s'", strtolower($email));
        $results = $pdo->query($query);
    }
    catch (Exception $e)
    {
        $error = "Unable to query database.  Please check username and password.";
    }

    foreach ($results as $result)
    {
	$password_hash = $result['password'];
    }

    list($salt, $hash) = explode(":", $password_hash);

    $pwdhash = crypt($pwdhash, $salt);

    // create a password hash to check against the one in the database
    $pwdhash = $salt . ":" . $pwdhash;

    // check submitted username and password against the ones stored in database
    try
    {
        $query = sprintf("SELECT * FROM users WHERE LOWER(username)='%s' AND password='%s'", strtolower($email), $pwdhash);
        $results = $pdo->query($query);
    }
    catch (Exception $e)
    {
        $error = "Unable to query database.  Please check username and password.";
    }

    // print results
    print "\nUsers\n";
    print "Id\tUsername\tPassword\n";
    foreach ($results as $result)
    {
	print $result['id']."\t";
	print $result['username']."\t";
	print $result['password']."\n";

	$userid = $result['id'];
    }

    return $userid;
}

/*
 * login_user() - Verify account credentials and create session
 *
 * @param string $email
 * @param string $password
 *
function login_user($email, $password)
{
	// prepare email address and password hash for safe query
	$email = mysql_escape_string($email);
	$pwdhash = hash("SHA1",$password);
	
	// connect to database with mysql_
	$connection = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
	mysql_select_db(DB_DATABASE);
	
	// verify email and password pair
	$userid = 0;
	$query = sprintf("SELECT id FROM users WHERE LOWER(email)='%s' AND passwordhash='%s'",strtolower($email),$pwdhash);
	$resource = mysql_query($query);
	if ($resource)
	{
	    $row = mysql_fetch_row($resource);
	    if (isset($row[0]))
		$userid = $row[0];
	}
	
	// close database and return 
	mysql_close($connection);
	return $userid;
}
*/

/*
 * get_user_shares() - Get portfolio for specified userid
 *
 * @param int $userid
 */
function get_user_shares($userid)
{
	// connect to database with PDO
	$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_DATABASE;
	$dbh = new PDO($dsn, DB_USER, DB_PASSWORD);
	
	// get user's portfolio
	$stmt = $dbh->prepare("SELECT symbol, shares FROM portfolios WHERE userid=:userid");
	$stmt->bindValue(':userid', $userid, PDO::PARAM_STR);
	if ($stmt->execute())
	{
	    $result = array();
	    while ($row = $stmt->fetch()) {
	            array_push($result, $row);
	    }
	
	    $dbh = null;
	    return $result;
	}
	
	// close database and return null 
	$dbh = null;
	return null;
}

/*
 * get_quote_data() - Get Yahoo quote data for a symbol
 *
 * @param string $symbol
 */
function get_quote_data($symbol)
{
	$result = array();
	$url = "http://download.finance.yahoo.com/d/quotes.csv?s={$symbol}&f=sl1n&e=.csv";
	$handle = fopen($url, "r");
	if ($row = fgetcsv($handle))
		if (isset($row[1]))
			$result = array("symbol" => $row[0],
						"last_trade" => $row[1],
							"name" => $row[2]);
	fclose($handle);
	return $result;
}

function get_user_balance($userid) { }

function buy_shares($userid, $symbol, $shares, &$error) { }

function sell_shares($userid, $symbol, &$error) { }
