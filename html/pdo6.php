<?php
/*********************
 * pdo6.php
 *
 * CSCI S-75
 * Section 5
 * Chris Gerber
 *
 * Transactions
 *********************/

// database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'jharvard');
define('DB_PASSWORD', 'crimson');
define('DB_DATABASE', 'jharvard_section5');

// connect to server and select database
$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_DATABASE;
$dbh = new PDO($dsn, DB_USER, DB_PASSWORD);

function print_grades($dbh, $message)
{
	// execute a query
	$results = $dbh->query('SELECT * FROM grades');
	
	// print results
	print "\n$message\n";
	print "Student\tProject\tGrade\n";
	foreach ($results as $result)
	{
		print $result['student']."\t";
		print $result['project']."\t";
		print $result['grade']."\n";
	}
}

print_grades($dbh, 'Before updates');

$dbh->beginTransaction();
$dbh->exec("UPDATE grades SET grade='A-' WHERE student=1");
$dbh->commit();

$dbh->beginTransaction();
$dbh->exec("UPDATE grades SET grade='A-' WHERE student=2");

print_grades($dbh, 'Before roll back');

$dbh->rollBack();

print_grades($dbh, 'After roll back');

// close connection
$dbh = null;
?>