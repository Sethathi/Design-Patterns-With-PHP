<?php

require_once '../Singleton/Database.php';
require_once 'Message_queue.php';
require_once '../Factory/Message_creator.php';


//echo "Get the database instance. \n"
$database_instance = Database::get_instance();

//echo "Create a new message queue. \n"
$msgQueue = new Message_queue();

//echo "Get user details from a database table. \n"
$people = $database_instance->query("select * from people");

while($person = $people->fetch()){
	//echo "Use the factory to create a msg object. \n"
	$msg = Message_creator::create($person);

	//echo "add the message object to the queue. \n"
	$msgQueue->addMessage($msg);
}

//echo "send all SMSs/Emails. \n"
$msgQueue->execute();
	
	


?>
