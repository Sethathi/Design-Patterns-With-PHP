<?php

/**
* This class has methods which will help us add objects to a queue then afterwards send them
*
*
*/

class Message_queue{
	
	var $_queue = array();

	/**
	* This method adds messages to a queue
	*
	* @param IMessage message
	* @return void
	* @access public
	*
	*/
	public function addMessage(IMessage $message){
		$this->_queue[] = $message;

	}

	/**
	* Here we send all messages in a queue, then display the total sent
	* @return void
	*/
	public function execute(){

		$total_messages = 0;

		$total_sent = 0;

		foreach ($this->_queue as $message) {
			++$total_messages;
			if($message->send())
				++$total_sent;
		}

		echo "$total_sent out of $total_messages messages sent";
	}




}



?>