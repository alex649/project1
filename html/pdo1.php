<?php
/******************
 * pdo1.php
 *
 * CSCI S-75
 * Section 5
 * Chris Gerber
 *
 * Sample PDO code
 ******************/

// connect to server and select database
$dbh = new PDO('mysql:host=localhost;dbname=jharvard_section5','jharvard','crimson');

// execute a query
$students = $dbh->query('SELECT * FROM students');

// print results
print "ID\tFirst\tLast\n";
foreach ($students as $student)
{
	print $student['id']."\t";
	print $student['first']."\t";
	print $student['last']."\n";
}

// close connection
$dbh = null;
?>