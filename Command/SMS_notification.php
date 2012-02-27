<?php
require_once 'IMessage.php';

/*
* Class implements the IMessage interface and provides code to send an SMS
* 
* @author Sethathi Morokole <sethathi@gmail.com>
*/

class SMS_notification implements IMessage{


    /* Clickatell Configuration */

	var $username = NULL;

    var $password = NULL;

    var $api_id = NULL;

    /* phone number to send an SMS to*/
    public $to = NULL;

    /* actual notification message will be in here */
    public $text = NULL;

  
    public function __construct($to, $text){
        $this->to = $to;
        $this->text = $text;
    }
	
    /**
    * Here we send an SMS notification using CLickatell
    * 
    * @return boolean, TRUE if SMS successfully sent, FALSE otherwise
    * @access public
    *
    */
	public function send(){
        

		$sent = FALSE;
        
        $baseurl = "http://api.clickatell.com";

        $this->text = urlencode($this->text);

        //ap
        $this->to = '266'.$this->to;

        // auth call
        $url = "$baseurl/http/auth?user=$this->username&password=$this->password&api_id=$this->api_id";

        // do auth call
        $ret = file($url);

        // explode our response. return string is on first line of the data returned
        $sess = explode(":", $ret[0]);
        if ($sess[0] === 'OK') {

            $sess_id = trim($sess[1]); // remove any whitespace
            $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$this->to&text=$this->text";

            // do sendmsg call
            $ret = file($url);
            $send = explode(':', $ret[0]);

            if ($send[0] === "ID")
                $sent = TRUE;
            
        } else {
            echo 'Authentication failure: ' . $ret[0];
        }
        return $sent;
    }
		
	}
?>