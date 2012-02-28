<?php
//include class
include_once 'Database.php';

//get a Database instance
$database_obj = Database::get_instance();

echo "Selecting name, surname with WHERE clause:\n";
//create a prepared statement
$query = "SELECT name, surname FROM people WHERE surname = ?";
$stmt = $database_obj->prepare($query);

if ($stmt)
{
	$surname = "Morokole";
	//bind parameters
	$stmt->bindParam(1, $surname);

	//execute the prepared statement
	if ( ! $stmt->execute())
	{
		die("Failed to execute query");
	}

	//loop through fetched value and print them
	while($row = $stmt->fetchObject())
	{
		echo $row->name.' '.$row->surname."\n";
	}
}


//test insert (prepare, binding, execution)
echo "Inserting row into `people` table:\n";
$query = 'INSERT INTO people VALUES(:id, :name, :surname, :contact, :notification_type, :notification_text)';
$data = array(
	'id' => 10,
	'name' => 'Pete',
	'surname' => 'Cosmos',
	'contact' => 'pete@example.com',
	'notification_type' => 'Email',
	'notification_text' => 'Hi, Pete!',
);
$result = $database_obj->query($query, $data);
echo "Insert was " . ($result ? "successful.\n" : "unsuccessful.\n");

echo "Checking database for data:\n";
$result = $database_obj->query("SELECT id, name, surname FROM people");
while ($row = $result->fetchObject())
{
	echo "Person: $row->id $row->name $row->surname\n";
}
	


?>
