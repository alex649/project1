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

/*
 * connect_to_database() - Connect to database using PDO
 *
 * 
 * @return PDO $pdo object
 */
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

    $data = array();

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
        $query = sprintf("INSERT INTO `CS75Finance`.`users` (`username`,`password`, `balance`) VALUES ('$email', '$pwdhash',
            '$initial_balance')");
	$pdo->query($query);

        // check that user has been added to the database
        // verify email and password pair and balance
        $query = sprintf("SELECT id FROM users WHERE LOWER(username)='%s' AND password='%s' AND balance='%s'",
            strtolower($email), $pwdhash, $initial_balance);
        $results = $pdo->query($query);
    }
    catch (Exception $e) 
    {
        $error = 'Your account could not be registered. Did you forget your password?';
    }
	
    // get user id
    foreach ($results as $result)
    {
	$data['userid'] = $result['id'];
    }

    // close database 
    $pdo = null;

    return $data;
}

/*
 * login_user() - Login user
 *
 * @param string $email
 * @param string $password
 * 
 * @return string $error
 */
function login_user($email, $password, &$error)
{
    global $pdo;

    $data = array();

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

    // encrypt it
    $pwdhash = crypt($pwdhash, $salt);

    // create a password hash to check against the one in the database
    $pwdhash = $salt . ":" . $pwdhash;

    // check submitted username and password against the ones stored in database
    try
    {
        $query = sprintf("SELECT id FROM users WHERE LOWER(username)='%s' AND password='%s'", strtolower($email), $pwdhash);
	$results = $pdo->query($query);
    }
    catch (Exception $e)
    {
        $error = "Unable to query database.  Please check username and password.";
    }

    // get user id
    foreach ($results as $result)
    {
	$data['userid'] = $result['id'];
    }

    // close database 
    $pdo = null;

    return $data;
}

/*
 * get_user_shares() - Get portfolio for specified userid
 *
 * @param int $userid
 */
function get_user_shares($userid)
{
    global $pdo;

    try
    {
        // connect to database
        $pdo = connect_to_database();
    }
    catch (Exception $e)
    {
        $error = "Could not connect to database.";
    }

    // get user's portfolio
    $stmt = $pdo->prepare("SELECT symbol, shares FROM portfolios WHERE id=:userid");
    $stmt->bindValue(':userid', $userid, PDO::PARAM_STR);
    if ($stmt->execute())
    {
	$result = array();
	while ($row = $stmt->fetch())
	{
	    array_push($result, $row);
	}

	$pdo = null;
	return $result;
    }
	
    // close database and return null 
    $pdo = null;
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

/*
 * get_user_balance() - Get user balance
 *
 * @param int $userid
 */
function get_user_balance($userid) 
{
    global $pdo;
    $balance = 0;

    try
    {
        // connect to database
        $pdo = connect_to_database();
    }
    catch (Exception $e)
    {
        $error = "Could not connect to database.";
    }

    try
    {
        $query = sprintf("SELECT * FROM users WHERE id='%s'", $userid);
        $results = $pdo->query($query);
    }
    catch (Exception $e)
    {
        $error = "Unable to query database.  Please check username and password.";
    }

    foreach ($results as $result)
    {
	$balance = $result['balance'];
    }

    // close database 
    // $pdo = null;

    return $balance;
}

function buy_shares($userid, $symbol, $last_trade, $shares, &$error) 
{
    global $pdo;

    $cost_of_shares = $last_trade * $shares;

    try
    {
        // connect to database
        $pdo = connect_to_database();
    }
    catch (Exception $e)
    {
        $error = "Could not connect to database.";
    }

    try
    {
        // add shares to portofio
        $query = sprintf("INSERT INTO `CS75Finance`.`portfolios` (`id`, `symbol`, `shares`) VALUES ('$userid', '$symbol', '$shares')");
        $pdo->query($query);
    }
    catch (Exception $e)
    {
        $error = "Could not successfully add shares to the portfolios table.";
    }

    try
    {
	// deduct cost from balance in users table
	$balance = get_user_balance($userid);

	$remaining_balance = $balance - $cost_of_shares;

	$remaining_balance = round($remaining_balance, 2);

	$query = sprintf("UPDATE users SET balance='%s' WHERE id='%s'", $remaining_balance, $userid);
        $pdo->query($query);
    }
    catch (Exception $e)
    {
        $error = "Could not successfully update balance in the users database.";
    }
}

function sell_shares($userid, $symbol, &$error) { }
