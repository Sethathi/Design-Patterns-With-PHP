<?php
//include class
include_once 'Database.php';

//get two instance confirm they are the same
$database1 = Database::get_instance();
$database2 = Database::get_instance();
if (spl_object_hash($database1) === spl_object_hash($database2)) {
    echo "Singleton works: \$database1 and \$database2 reference the same object.\n";
} else {
    echo "Singleton DOES NOT work: \$database1 and \$database2 DO NOT reference the same object!\n";
}

//make sure the constructor cannot be called
if ((is_callable(array($database1, '__construct'))) === FALSE) {
    echo "Constructor is not callable, this is correct.\n";
} else {
    echo "Constructor is callable, SHOULD NOT BE ALLOWED.\n";
}

$result = $database1->query("Select * from people");
if($result)
	$result = $result->fetch();
echo $result['name'];

?>
