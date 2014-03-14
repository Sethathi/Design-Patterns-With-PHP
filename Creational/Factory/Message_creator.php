<?php 

require_once '../Command/SMS_notification.php';
require_once '../Command/Email_notification.php';


/**
* This class creates new objects for us based on a user's preferences -- SMS or EMAIL
*
* @author Sethathi Morokole <sethathi@gmail.com>
*
*
*/

class Message_creator{
	

	/**
	* This method creates objects for use based on a user's preferences
	*
	* @access public
	* @return an instance based on a user's preferences
	* @param Array message
	*
	*/
	public static function create($message){
		//echo "Here we get the message type -- EMAIL/SMS. \n"
		$message_type = $message['notification_type'];

		//echo "Here we get the actual EMAIL address/ phone number to send an SMS. \n"
		$to = $message['contact'];

		//echo "Here we get the actual notification message. \n"
		$text = $message['notification_text'];

		//echo "Here we construct the class name -- SMS_notification or Email_notification. \n"
		$class_name = $message_type.'_notification';

		try{
		if(class_exists($class_name)){
			//echo "We create and return the object here. \n"
			return new $class_name($to, $text);
		}else
			throw new Exception("Invalid type of message given");
		}catch(Exception $e){
				echo $e->getMessage();
			}

	}


}
?>