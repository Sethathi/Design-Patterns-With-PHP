<?php



class Email_notification implements IMessage{


	public $to = NULL;
	public $text = NULL;

	public function __construct($to, $text){
		$this->to = $to;
		$this->text = $text;
	}


	/**
	* @todo method implementation
	*
	*/	
	public function send(){
		
	}

}


?>