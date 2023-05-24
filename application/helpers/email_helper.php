<?php

/*
function email_send($f_email,$send_name,$to_email,$subject,$email){
	
	
	
	
	
	
$CI=& get_instance();
$CI->load->database();
$CI->load->library('email');

$this->email->initialize(array(
  'protocol' => 'smtp',
  'smtp_host' => 'smtp.sendgrid.net',
  'smtp_user' => 'sendgridusername',
  'smtp_pass' => 'sendgridpassword',
  'smtp_port' => 587,
  'crlf' => "\r\n",
  'newline' => "\r\n"
));


$CI->email->from($f_email, $send_name);
$CI->email->to($to_email);
//$CI->email->cc('another@another-example.com');
//$CI->email->bcc('them@their-example.com');
$CI->email->subject($subject);
$CI->email->message($email);
$CI->email->send();
		
}

*/
?>