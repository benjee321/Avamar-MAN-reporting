<?php
namespace AERR;
class sendEmail {
	
	private $_variables = array();
	
	
	
	public function setContent($content = array()){
		$this->_variables = $content;	
	}
	
	public function Send($task,$subject){
		
		//get from the base.php the list of the recipient
		
		$to =implode(", ", $GLOBALS['users']);
		
	 	$message = $this->compile($task);
		
		$headers = "From: ".$GLOBALS['$from_email']." \r\n";
		//$headers .= "Reply-To: ".$to. "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		
		mail($to, $subject, $message, $headers);
		//echo $message."\n";
		 
		
	}
	
	
	private function compile($template){
		
		
		ob_start();
		extract($this->_variables);
		include './mailtemplates/'.$template.'.phtml';
		
		
		$content = ob_get_clean();
		
		
		return $content;
	}

}