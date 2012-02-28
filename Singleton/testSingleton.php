<?php
//include class
include_once 'Database.php';

//get two instance confirm they are the same
$database1 = Database::get_instance();
$database2 = Database::get_instance();
if (spl_object_hash($database1) === spl_object_hash($database2))
{
    echo "Singleton works: \$database1 and \$database2 reference the same object.\n";
}
else
{
    echo "Singleton DOES NOT work: \$database1 and \$database2 DO NOT reference the same object!\n";
}

//make sure the constructor cannot be called
if ((is_callable(array($database1, '__construct'))) === FALSE)
{
    echo "Constructor is not callable, this is correct.\n";
}
else
{
    echo "Constructor is callable, SHOULD NOT BE ALLOWED.\n";
}

//test insert (prepare, binding, execution)
$query = 'INSERT INTO people VALUES(:id, :name, :surname, :contact, :notification_type, :notification_text)';
$data = array(
    'id' => 10,
    'name' => 'Pete',
    'surname' => 'Cosmos',
    'contact' => 'pete@example.com',
    'notification_type' => 'Email',
    'notification_text' => 'Hi, Pete!',
);
$result = $database1->query($query, $data);
var_dump($result);

echo "Checking database for data..\n";
$result = $database1->query("SELECT id, name, surname FROM people");
while ($row = $result->fetchObject())
{
    echo "Person: $row->id $row->name $row->surname\n";
}

?>
