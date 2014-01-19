<?php
/******************
 * pdo3.php
 *
 * CSCI S-75
 * Section 5
 * Chris Gerber
 *
 * Inner Join
 ******************/

// database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'jharvard');
define('DB_PASSWORD', 'crimson');
define('DB_DATABASE', 'jharvard_section5');

// connect to server and select database
$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_DATABASE;
$dbh = new PDO($dsn, DB_USER, DB_PASSWORD);

// execute a query
$results = $dbh->query('SELECT *
						FROM students
						JOIN grades
						ON students.id = grades.student');

// print results
print "ID\tFirst\tLast\tStudent\tProject\tGrade\n";
foreach ($results as $result)
{
	print $result['id']."\t";
	print $result['first']."\t";
	print $result['last']."\t";
	print $result['student']."\t";
	print $result['project']."\t";
	print $result['grade']."\n";
}

// close connection
$dbh = null;
?>