<?php
//include class
include_once 'Database.php';

//get a Database instance
$database_obj = Database::get_instance();

//create a prepared statement
$stmt = $database_obj->stmt_init();


if($stmt->prepare("SELECT name, surname FROM people WHERE surname = ?")){
	$surname = "Morokole";
	//bind parameters
	$stmt->bind_param("s", $surname);

	//execute the prepared statement
	$stmt->execute();

	//bind results to these variables
	$stmt->bind_result($name, $lastname);

	//loop through fetched value and print them
	while($stmt->fetch())
		echo $name.' '.$lastname.'<br />';
}
	


?>
