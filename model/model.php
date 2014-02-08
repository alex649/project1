<?php
/*********************************
 * model.php
 *
 * CSCI S-75
 * Project 1
 * Alex Spivakovsky
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
	return;
    }

    // prepare password hash
    $pwdhash = hash("SHA256",$password);
    $salt = mt_rand(37693, 100000);
    $pwdhash = crypt($pwdhash, $salt);
    $pwdhash = $salt . ":" . $pwdhash;

    try
    {
	// add new user into users database
        $query = sprintf("INSERT INTO users (`username`,`password`, `balance`) VALUES ('$email', '$pwdhash',
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
	return;
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
	return;
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
	return;
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
	return;
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
	return;
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
	return;
    }

    try
    {
        $query = sprintf("SELECT * FROM users WHERE id='%s'", $userid);
        $results = $pdo->query($query);
    }
    catch (Exception $e)
    {
        $error = "Unable to query database.  Please check username and password.";
	return;
    }

    foreach ($results as $result)
    {
	$balance = $result['balance'];
    }

    // close database 
    // $pdo = null;

    return $balance;
}

/*
 * buy_shares() - Buy shares by adding them to portfolios table
 *                and updating the balance in the users table  
 * @param int $userid
 * @param string $symbol
 * @param decimal $last_trade
 * @param int $shares
 * 
 * @return string $error
 */
function buy_shares($userid, $symbol, $last_trade, $shares, &$error) 
{
    global $pdo;

    $balance = get_user_balance($userid);
    $cost = $last_trade * $shares;
    $cost_this_trade = $cost;
    $cost_of_existing_shares;
    $num_of_existing_shares;
    $num_of_shares;

    $balance = get_user_balance($userid);

    $remaining_balance = $balance - $cost_this_trade;

    $remaining_balance = round($remaining_balance, 2);

    // check that the remaining balance on the account allows for the purchase to happen
    if ($balance < $cost)
    {
	$error = "You do not have enough money on your account to make this purchase.";
	return;
    }

    try
    {
        // connect to database
        $pdo = connect_to_database();
    }
    catch (Exception $e)
    {
        $error = "Could not connect to database.";
	return;
    }

    try
    {
	// buy the shares
	$query = sprintf("START TRANSACTION;");
	$pdo->query($query);
	
	$query = sprintf("INSERT INTO portfolios (`id`, `symbol`, `shares`, `cost`) VALUES ('$userid', '$symbol', '$shares', '$cost')
	    ON DUPLICATE KEY UPDATE shares=shares+$shares, cost=cost+$cost");
	$pdo->query($query);

	$query = sprintf("UPDATE users SET balance=balance-$cost WHERE id=$userid");
	$pdo->query($query);

	// check if the remaining balance is positive
	$query = sprintf("SELECT * FROM users WHERE id='%s'", $userid);
        $results = $pdo->query($query);

	foreach ($results as $result)
        {
	    $balance = $result['balance'];
        }

	// if balance is negative, rollback transaction
	if ($balance < 0)
	{
	    $query = sprintf("ROLLBACK;");
	    $pdo->query($query);
	}

	$query = sprintf("COMMIT;");
        $pdo->query($query);
    }
    catch (Exception $e)
    {
        $error = "Could not buy the shares.";
	return;
    }
}

/*
 * sell_shares() - Sell shares, deleting them from portfolios table
 *                 and updating the balance in the users table
 *
 * @param int $userid
 * @param string $symbol
 * 
 * @return string $error
 */
function sell_shares($userid, $symbol, &$error)
{
    global $pdo;

    $cost;

    try
    {
        // connect to database
        $pdo = connect_to_database();
    }
    catch (Exception $e)
    {
        $error = "Could not connect to database.";
	return;
    }

    try
    {
	// get cost of stock when it was bought
	// in this particular assignment, the stock is sold for the same price as the one at which it was bought
	$query = sprintf("START TRANSACTION;");
	$pdo->query($query);

        $query = sprintf("SELECT * FROM portfolios WHERE id='%s' AND symbol='%s'", $userid, $symbol);
        $results = $pdo->query($query);

	foreach ($results as $result)
        {
	    $cost = $result['cost'];
        }

	// if the stock already exists, delete the old entry in the table
        if (isset($cost))
        {
	    $query = sprintf("DELETE FROM portfolios WHERE id='%s' AND symbol='%s' LIMIT 1", $userid, $symbol);
            $pdo->query($query);

	    // add cost of shares sold to the balance in users table
	    $query = sprintf("UPDATE users SET balance=balance+$cost WHERE id=$userid");
	    $pdo->query($query);
        }

	$query = sprintf("COMMIT;");
        $pdo->query($query);
	
    }
    catch (Exception $e)
    {
        $error = "Unable to sell the shares.  Please check that the shares have not already been sold";
	return;
    }  
}
