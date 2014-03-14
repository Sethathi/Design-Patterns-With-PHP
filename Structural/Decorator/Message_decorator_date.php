<?php

class Message_decorator_date{

	private $_message;

	public $date;
	
	public function __construct(){
		$this->date = date('Y-m-d');
	}

	public function add_date_to_message(IMessage $message){
		$this->_message = $message;
		$this->_message->text .= $this->date;
	}


}




?>