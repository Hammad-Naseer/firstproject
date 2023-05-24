<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Cron_job extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
		$this->load->database();
        /*cache control
            $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
            $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            $this->output->set_header('Pragma: no-cache');
            $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            $this->school_db=$_SESSION['school_db'];
            if($_SESSION['user_login']!= 1)
    		redirect(base_url() . 'login');
            //$_SESSION['school_id']=101;
        */
       $this->load->helper('message');
    }
    
    function send_sms_test(){
        // https://indiciedu.com.pk/cron_job/send_sms_test
        $this->load->helper('message');
        $mob_num    = "923145345534";
        //$message    = "This is a test message to test the functionality of the long messages sent through using short code API. This message should ideally be delivered in full and no word should be broken down. The same exact message needs to be typed in Urdu to test what would happen if an Urdu text has been sent. Further to above this supposed to be the last sentence of the message and should be delivered in an unified manner";
        $message    = "This";
        $student_id = "0";
        $response = send_sms($mob_num, 'Indici Edu', $message, $student_id);
        print_r($response);
    }
    
    
/***default functin, redirects to login page if no admin logged in yet***/
 function cron_sms(){
$db_nm=$this->config->item('system_db_name');
cron_sms($db_nm);

 }
  function cron_email(){
 $db_nm=$this->config->item('system_db_name');
 cron_email($db_nm);	
 }
function sms_updates(){	

$db_nm=$this->config->item('system_db_name');
$qur_bulk=$this->db->query("select * from $db_nm.bulk_sms where is_active=1")->result_array();

foreach($qur_bulk as $row_bulk){	
if($row_bulk['section_id']==0){
$qq="select * from ".bulk_school_db($row_bulk['sys_sch_id']).".student where school_id=".$row_bulk['school_id']." and student_status in (".student_query_status().")";
}else{
$qq="select * from ".bulk_school_db($row_bulk['sys_sch_id']).".student where school_id=".$row_bulk['school_id']." and section_id=".$row_bulk['section_id']."   and student_status in (".student_query_status().")";
}

$detail_qur=$this->db->query($qq)->result_array();

foreach($detail_qur as $row){	
$to=$row['mob_num'];
$from="Indici Edu";
//$responce=send_sms($to="",$from="Indici Edu",$message="",$sms_type="");
//end api
$data['sys_sch_id']=$row_bulk['sys_sch_id'];
$data['recepient']=$to;
$data['sender']=$from;
$data['student_id']=$row['student_id'];
$data['message']= $row_bulk['message'];
$data['status']=0;//$response;
$data['sms_type']=2;
$data['sms_service']="SendPK";

$this->db->insert("$db_nm.sms_log",$data);
}	

$this->db->where('sms_log_id',$row_bulk['sms_log_id']);	
$this->db->update("$db_nm.bulk_sms",array('is_active'=>0));


}
}

function email_updates(){	

$db_nm=$this->config->item('system_db_name');
$qur_bulk=$this->db->query("select * from $db_nm.bulk_email where is_active=1")->result_array();

foreach($qur_bulk as $row_bulk){
if($row_bulk['section_id']==0){
$qq="select * from ".bulk_school_db($row_bulk['sys_sch_id']).".student where  student_status in (".student_query_status().")and school_id=".$row_bulk['school_id'];
}else{
$qq="select * from ".bulk_school_db($row_bulk['sys_sch_id']).".student where  section_id=".$row_bulk['section_id']."   and student_status in (".student_query_status().") and school_id=".$row_bulk['school_id'];
}
$detail_qur=$this->db->query($qq)->result_array();
foreach($detail_qur as $row){	
$to=$row['email'];
$from="Indici Edu";
//$responce=send_sms($to="",$from="GSIMS",$message="",$sms_type="");
//end api
$data['sys_sch_id']=$row_bulk['sys_sch_id'];
$data['recepient']=$to;
$data['sender']=$from;
$data['student_id']=$row['student_id'];
$data['subject']=$row_bulk['subject'];
$data['is_send']=0;
$data['message']=$row_bulk['message'];
$data['status']=0;//$response;
$data['email_type']=2;
$this->db->insert("$db_nm.email_log",$data);
}
$this->db->where('email_log_id',$row_bulk['email_log_id']);	
$this->db->update("$db_nm.bulk_email",array('is_active'=>0));

}
}


}
    