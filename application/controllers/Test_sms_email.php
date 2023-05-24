<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//session_start(); 

class Test_sms_email extends CI_Controller
{
	
    function __construct()
    {
        parent::__construct();
		$this->load->database();
        /*cache control*/
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }
    
    public function opensms()
    {
        $this->load->view('send_sms');
    }
    
    public function smstesting()
    {
        
        
        $this->load->helper('message');
        
        //api code
        $username = "923088805106";
        $password = "Indici@f3tech@@"; //
        $mobile   = "03145345534";
        
        
        $message  = "Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC,
        making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, 
        looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum  from a Lorem Ipsum from a Lorem Ipsum  from a Lorem Ipsum  from a Lorem Ipsum ";
        
        $response = send_sms($mobile , 'Indici Edu' , $message , 0 , 00);
        
        echo $response;
    }
    
    public function emailtesting()
    {
        $responce = "";
        
        $to_email = $this->input->post('email');
        $subject = "Testing Email";
        $emailMessage = $this->input->post('emailMessage');
        $send_name = "INDICI";
        
        $this->load->config('mail');
        $this->load->library('email'); // load email library
        $f_email = $this->config->item('smtp_user');
        $this->email->from($f_email, $send_name);//sending email
        $this->email->to($to_email);      // sending email
        $this->email->subject($subject); // sending email
        $this->email->reply_to("no replay");// sending email
        $this->email->message($emailMessage);// sending email
        $this->email->set_mailtype("html");
        if ($this->email->send())
        {
            $responce = "Mail sent";
        }
    
        else
        {
            $responce = "Send Error";
        }
        
        echo $responce;
    }
    
    function get_ip_details()
	{
        $user_ip=$_SERVER['REMOTE_ADDR'];
        $ip_response = file_get_contents('http://ip-api.com/json/'.$user_ip);
        $ip_array=json_decode($ip_response);
        echo "<pre>";
        print_r($ip_array);
        //  echo $country_name=$ip_array->country; 
        //  echo $city=$ip_array->city;
	}
	
	function new_email()
	{
	    
	    $this->load->helper('message');
	    $message = "Hello Hammad How Are you ?";
	    $me = get_email_layout($message);
	   // hammad.ali@f3technologies.eu
	   //zeeshanarain4455@gmail.com
	    echo  $me;
	    //email_send("No Reply","Indici-Edu","hammadalirajpoot@yahoo.com","Testing",$me,0);
	}
}