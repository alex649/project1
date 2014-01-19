<?php
/**********************
 * pdo2.php
 *
 * CSCI S-75
 * Section 5
 * Chris Gerber
 *
 * Prepared statements
 **********************/

// hard-code the data for now
$grades = array(array(1,0,'B'),
				array(1,1,'B+'),
				array(2,0,'A'),
				array(2,1,'A'),
				array(4,0,'C'));

// connect to server and select database
$dbh = new PDO('mysql:host=localhost;dbname=jharvard_section5','jharvard','crimson');

// delete all grades in database
$dbh->exec('DELETE FROM grades');

// create a prepared statement
$add_grade = $dbh->prepare('INSERT INTO grades (student, project, grade)
						   VALUES (:student, :project, :grade)');

// insert all records
foreach ($grades as $grade)
{
	$add_grade->bindValue(':student',$grade[0],PDO::PARAM_INT);
	$add_grade->bindValue(':project',$grade[1],PDO::PARAM_INT);
	$add_grade->bindValue(':grade',$grade[2],PDO::PARAM_STR);
	$add_grade->execute();
}

// execute a query
$results = $dbh->query('SELECT * FROM grades');

// print results
print "Student\tProject\tGrade\n";
foreach ($results as $result)
{
	print $result['student']."\t";
	print $result['project']."\t";
	print $result['grade']."\n";
}

// close connection
$dbh = null;
?>