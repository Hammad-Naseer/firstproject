<?php

///This Helper Contain All The SMS And Email Details

//session_start();

function send_sms($to="",$from="Indici Edu",$message="",$student_id,$sms_section = 0)
{
    $to=validate_phone_num($to);
    $CI=& get_instance();
    $CI->load->database();
    
    $access;
    if($sms_section > 0)
    {
        $permission = $CI->db->where("sms_section",$sms_section)->where("school_id",$_SESSION['school_id'])->where("sms_status",1)->get(get_school_db().".sms_settings");
        if($permission->num_rows() > 0){
            $access = "grant";
        }else{
            $access = "denied";
        }
    }
    
    if($access == "grant" || $sms_section == 00){
    
        // start api
        $username = "923088805106";     ///API Username
        $password = "Indici@f3tech@@";    ///
        $mobile   = $to;                  ///Recepient Mobile Number
        $sender   = $from;
        
        if($mobile != ""){
            //sending sms
            // $post = "sender=".urlencode($sender)."&mobile=".urlencode($mobile)."&message=".urlencode($message)."";
            $post = "sender=".urlencode($sender)."&mobile=".urlencode($mobile)."&message=".urlencode($message)."&type=unicode";
            $url  = "https://sendpk.com/api/sms.php?username=$username&password=$password";
            $ch   = curl_init();
            $timeout = 30; // set to zero for no timeout
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $result = curl_exec($ch); 
            
            $message_length  = strlen($message);
            $message_count   = $message_length/160;
            $total_sms_count = ceil($message_count);
            
            
            $is_send        =  0;
            $r              =  explode(":" , $result);
            $response_text  =  trim($r[0]);
            $status_text    =  "";
            
            
            if($response_text == "OK ID")
            {
                $status_text =  "Message Was Successfully Accepted For Delivery"; 
                $is_send     =  1;
            }
            else if($response_text == 1){  $status_text = "Username Or Password Is Either Invalid Or Disabled";  }
            else if($response_text == 2){  $status_text = "Username Is Empty";   }
            else if($response_text == 3){  $status_text = "Password Is Empty";   }
            else if($response_text == 4){  $status_text = "Sender ID Is Empty";  }
            else if($response_text == 5){  $status_text = "Recepient Is Empty";  }
            else if($response_text == 6){  $status_text = "Message Is Empty";    }
            else if($response_text == 7){  $status_text = "Invalid Recepient";   }
            else if($response_text == 8){  $status_text = "Insufficient Credit"; }
            else if($response_text == 9){  $status_text = "SMS Rejected";        }
        }else{
            $status_text = "Mobile number is empty";
            $is_send = 0;
            $total_sms_count = 0;
        }
        
        $data['sys_sch_id']                 =   $_SESSION['sys_sch_id'];
        $data['recepient']                  =   $to;
        $data['sender']                     =   $from;
        $data['student_id']                 =   $student_id;
        $data['message']                    =   $message;
        $data['status']                     =   $status_text;
        $data['is_send']                    =   $is_send; 
        $data['sms_type']                   =   1;
        $data['date_time']                  =   date('Y-m-d H:i:s'); 
        $data['sms_service']                =   "sendsms.pk";
        $data['total_sms_count']            =   $total_sms_count;
        
        $CI->db->insert(get_system_db().".sms_log",$data);
    
        return $result;
    
    }   
}

function send_sms_code($to="",$from="Indici Edu",$message="",$student_id)
{

    $to=validate_phone_num($to);
    $CI=& get_instance();
    $CI->load->database();
    
    // start api
    $username = "923088805106";     ///Your Username
    $password = "Indici@f3tech@@";    ///Your Password
    
    // $username = "923088805106";     ///API Username
    // $password = "Indici@f3tech@@";    ///
    $mobile = $to;                  ///Recepient Mobile Number
    $sender = $from;
    ////sending sms
    
    $post = "sender=".urlencode($sender)."&mobile=".urlencode($mobile)."&message=".urlencode($message)."";
    $url = "https://sendpk.com/api/sms.php?username=$username&password=$password";
    $ch = curl_init();
    $timeout = 30; // set to zero for no timeout
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $result = curl_exec($ch); 
    /*Print Responce*/ 
    //end api
    
    return $result;
    
}

function get_sms_detail($student_id = 0)
{

    $CI=& get_instance();
    $CI->load->database();
    $ur=$CI->db->query("select s.student_id, s.email,s.mob_num,s.name, c.name as class_name, cs.title as section_name from ".get_school_db().".student s 
                            inner join ".get_school_db().".class_section cs on cs.section_id=s.section_id
                            inner join ".get_school_db().".class c on c.class_id=cs.class_id
                            where s.student_id=$student_id ")->result_array();

    $ary['mob_num']=$ur[0]['mob_num'];
    $ary['student_name']=$ur[0]['name'];
    $ary['class_name']=$ur[0]['class_name'];
    $ary['section_name']=$ur[0]['section_name'];
    $ary['email']=$ur[0]['email'];
    $ary['student_id']=$ur[0]['student_id'];
    return $ary;
}

function get_sms_detail_staff($staff_id="")
{

    $CI=& get_instance();
    $CI->load->database();
    $ur=$CI->db->query("select * from ".get_school_db().".staff s
                            
                            where s.staff_id=$staff_id ")->result_array();

    $ary['mobile_no']=$ur[0]['mobile_no'];
    $ary['name']=$ur[0]['name'];
    $ary['email']=$ur[0]['email'];
    return $ary;
}

function sms_to_all_staff($message){
    $CI=& get_instance();
    $CI->load->database();
    $ur=$CI->db->query("select * from ".get_school_db().".staff s")->result_array();
    foreach($ur as $s){
        $staff_id=$s['staff_id'];
        $mobile_no=$s['mobile_no'];
        send_sms($mobile_no,'GMINNS Info',$message,$staff_id);
    }
}

/*function get_sms_detail_staff($student_id="")
{

    $CI=& get_instance();
    $CI->load->database();
    $ur=$CI->db->query("select s.email,s.mob_num,s.name, c.name as class_name, cs.title as section_name from ".get_school_db().".student s 
                            inner join ".get_school_db().".class_section cs on cs.section_id=s.section_id
                            inner join ".get_school_db().".class c on c.class_id=cs.class_id
                            where s.student_id=$student_id ")->result_array();

    $ary['mob_num']=$ur[0]['mob_num'];
    $ary['student_name']=$ur[0]['name'];
    $ary['class_name']=$ur[0]['class_name'];
    $ary['section_name']=$ur[0]['section_name'];
    $ary['email']=$ur[0]['email'];
    return $ary;
}*/

function sms_to_all($message){

    $CI=& get_instance();
    $CI->load->database();
    $data['sys_sch_id']=$_SESSION['sys_sch_id'];
    $data['school_id']=$_SESSION['school_id'];
    $data['section_id']=0;
    $data['message']=$message;//$response;
    $data['is_active']=1;//$response;
    $CI->db->insert(get_system_db().".bulk_sms",$data);

}

function sms_to_section($section_id,$message)
{
    $CI=& get_instance();
    $CI->load->database();
    // $data['sys_sch_id']=$_SESSION['sys_sch_id'];
    // $data['section_id']=$section_id;
    // $data['school_id']=$_SESSION['school_id'];
    // $data['message']=$message;//$response;
    // $data['is_active']=1;//$response;
    // $CI->db->insert(get_system_db().".bulk_sms",$data);
    $ur=$CI->db->query("select s.student_id, s.email,s.mob_num,s.name, c.name as class_name, cs.title as section_name from ".get_school_db().".student s 
                            inner join ".get_school_db().".class_section cs on cs.section_id=s.section_id
                            inner join ".get_school_db().".class c on c.class_id=cs.class_id
                            where s.section_id=$section_id ")->result_array();

    foreach($ur as $s){
        $id = $s['student_id'];
        $mob_num=$s['mob_num'];
        send_sms($mob_num, 'GMINNS Info', $message, $id);
    }

}

function email_verfication_send($send_name,$to_email,$subject,$message,$sys_sch_id)
{
    print_r($send_name);
    print_r($to_email);
    print_r($subject);
    print_r($message);
    print_r($sys_sch_id);
  
    
    $CI=& get_instance();
    $CI->load->database();
    $CI->load->config('mail');
    $CI->load->library('email');
    $f_email = $CI->config->item('smtp_user');
    $CI->email->from($f_email, $send_name);
    $CI->email->to($to_email);      
    $CI->email->subject($subject); 
    $CI->email->reply_to("no replay");
    $CI->email->message($message);
    $CI->email->set_mailtype("html");
    if ($CI->email->send())
    {
        $responce = true;
        $is_send  = 1;
    }
    else
    {
        $responce = false;
        $is_send  = 0;
    }
    
    $data['sys_sch_id']  =  $sys_sch_id;
    $data['recepient']   =  $to_email;
    $data['sender']      =  $f_email;
    $data['student_id']  =  0;
    $data['message']     =  $message;
    $data['subject']     =  $subject;
    $data['status']      =  $responce;
    $data['email_type']  =  1;
    $data['is_sent']     =  $is_send;
    $CI->db->insert("indicied_indiciedu_gsimscom_gsims_system.email_log",$data);
    
    return $responce;
    
}

function email_send($f_email,$send_name,$to_email,$subject,$email,$student_id,$email_section = 0)
{
    $CI=& get_instance();
    $CI->load->database();
    $access;

    if($email_section > 0)
    {
        $permission = $CI->db->where("sms_section",$email_section)->where("school_id",$_SESSION['school_id'])->where("email_status",1)->get(get_school_db().".sms_settings");
        if($permission->num_rows() > 0){
            $access = "grant";
        }else{
            $access = "denied";
        }
    }
    
    if($access == "grant" || $email_section == 00){
        
        if($to_email != ""){
            $CI->load->config('mail');
            $CI->load->library('email'); // load email library
            $f_email = $CI->config->item('smtp_user');
            $CI->email->from($f_email, $send_name);//sending email
            $CI->email->to($to_email);      // sending email
            $CI->email->subject($subject); // sending email
            $CI->email->reply_to("no replay");// sending email
            $CI->email->message($email);// sending email
            $CI->email->set_mailtype("html");
            
            if ($CI->email->send()){
                $responce = "Mail sent";
                $is_send  = 1;
            }else{
                $responce = "Send Error";
                $is_send  = 0;
            }
        }else{
                $responce = "Recipient is empty";
                $is_send  = 0;
        }
    
        $data['sys_sch_id']  = $_SESSION['sys_sch_id'];
        $data['recepient']   = $to_email;
        $data['sender']      = $f_email;
        $data['student_id']  = $student_id;
        $data['message']     = $email;
        $data['subject']     = $subject;
        $data['status']      = $responce;
        $data['date_time']   = date('Y-m-d H:i:s');;
        
        $data['email_type']  = 1;
        $data['is_sent']     = $is_send;
        $CI->db->insert(get_system_db().".email_log",$data);
    }
}
    

function email_to_all($message,$subject)
{
    $CI=& get_instance();
    $CI->load->database();
    $data['sys_sch_id'] = $_SESSION['sys_sch_id'];
    $data['subject']    = $subject;
    $data['school_id']  = $_SESSION['school_id'];
    $data['section_id'] = 0;
    $data['message']    = $message;
    $data['is_active']  = 1;
    $CI->db->insert(get_system_db().".bulk_email",$data);
}

function email_to_all_staff($message, $subject){
    $CI=& get_instance();
    $CI->load->database();
    $ur=$CI->db->query("select * from ".get_school_db().".staff s")->result_array();
    foreach($ur as $s){
        $staff_id=$s['staff_id'];
        $email=$s['email'];
        email_send("No Reply","Indici-Edu",$email,$subject,$message, $staff_id);
    }
}

function email_to_section($section_id,$message,$subject)
{

    $CI=& get_instance();
    $CI->load->database();
    // $data['sys_sch_id']=$_SESSION['sys_sch_id'];
    // $data['subject']=$subject;
    // $data['school_id']=$_SESSION['school_id'];
    // $data['section_id']=$section_id;
    // $data['message']=$message;//$response;
    // $data['is_active']=1;//$response;
    // $CI->db->insert(get_system_db().".bulk_email",$data);
    $ur=$CI->db->query("select s.student_id, s.email,s.mob_num,s.name, c.name as class_name, cs.title as section_name from ".get_school_db().".student s 
                            inner join ".get_school_db().".class_section cs on cs.section_id=s.section_id
                            inner join ".get_school_db().".class c on c.class_id=cs.class_id
                            where s.section_id=$section_id ")->result_array();

    foreach($ur as $s){
        $id = $s['student_id'];
        $email=$s['email'];
        email_send("No Reply","Indici-Edu",$email,$subject,$message, $id);
    }

}

function cron_sms($db_nm){
    $CI=& get_instance();
    $CI->load->database();
	
    // $username = "923074446694";
    // $password = "8563";
	
    $query=$CI->db->query("SELECT * FROM $db_nm.sms_log 
                          WHERE is_send=0 and sms_type in (2,3)")->result_array();
    foreach($query as $row)
    {
        $mobile = $row['recepient'];
        $sender = $row['sender'];
        $message=$row['message'];
        $url = "https://brandyourtext.com/sms/api/send?username=pispl&password=123456&mask=InfoSMS&mobile=".$mobile."&message=".urlencode($message)."";
        //$url="http://sendpk.com/api/sms.php?username=".$username."&password=".$password."&mobile=".$mobile."&sender=".urlencode($sender)."&message=".urlencode($message)."";
        $ch=curl_init();
        $timeout = 30;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $responce = curl_exec($ch);
        curl_close($ch);
        $data['is_send']=1;//$response;
        $data['status']=$responce;//$response;
        $CI->db->where('sms_log_id',$row['sms_log_id']);
        $CI->db->update("$db_nm.sms_log",$data);
        sleep(5);
    }
}

function cron_email($db_nm)
{
    $CI=& get_instance();
    $CI->load->database();

    $qq="SELECT * FROM $db_nm.email_log 
              WHERE is_send=0 and email_type in (2,3)";
    $query=$CI->db->query($qq)->result_array();

    foreach($query as $row){
        $f_email=$row['recepient'];
        $send_name="no Reply";
        $to_email=$row['sender'];
        $subject=$row['subject'];
        $email=$row['message'];
        $CI=& get_instance();
        $CI->load->database();
        $CI->load->library('email'); // load email library
        $CI->email->from($f_email, $send_name);//sending email
        $CI->email->to($to_email);      // sending email
        $CI->email->subject($subject); // sending email
        $CI->email->reply_to("no replay");// sending email
        $CI->email->message($email);// sending email
        $CI->email->set_mailtype("html");
        if ($CI->email->send()){
            $responce = "Mail send";
        }
        else{
            $responce = "Send Error";
        }
        $data['is_send']=1;//$response;
        $data['status']=$responce;//$response;
        $CI->db->where('email_log_id',$row['email_log_id']);
        $CI->db->update("$db_nm.email_log",$data);

        sleep(5);
    }

}

function validate_phone_num($string){

    //$string= preg_replace("/[^0-9]/", "", $string);
    $string=preg_replace('~\D~', '', $string);
    $to_count=strlen($string);
    $to_r="";
    for($i=11; $i>0;$i--)
    {
        $to_r=$string[$to_count].$to_r;
        $to_count--;
    }
    return "92".$to_r;
}

function send_single_sms($to="",$from="GSIMS",$message="",$student_id)
{
    $to=validate_phone_num($to);
    $CI=& get_instance();
    $CI->load->database();
    $data['sys_sch_id']=$_SESSION['sys_sch_id'];
    $data['recepient']=$to;
    $data['sender']=$from;
    $data['student_id']=$student_id;
    $data['message']=$message;
    $data['status']=0;//$response;
    $data['is_send']=1;//$response;
    $data['sms_type']=2;
    $data['sms_service']="SendPK";
    $CI->db->insert(get_system_db().".sms_log",$data);
//}
    return $responce;
}

function email_single_send($f_email,$send_name,$to_email,$subject,$email,$student_id){

    $CI=& get_instance();
    $CI->load->database();
    $data['sys_sch_id']=$_SESSION['sys_sch_id'];
    $data['recepient']=$to_email;
    $data['sender']=$f_email;
    $data['student_id']=$student_id;
    $data['message']=$email;
    $data['subject']=$subject;
    $data['status']=0;//$response;
    $data['is_send']=0;//$response;
    $data['email_type']=2;
//$data['sms_service']="SendPK";
    $CI->db->insert(get_system_db().".email_log",$data);
}

function bulk_school_db($sys_sch_id){

    $CI=& get_instance();
    $CI->load->database();
    $db_nm=$CI->config->item('system_db_name');
    $ur_val=$CI->db->query("select * from $db_nm.system_school where sys_sch_id=$sys_sch_id")->result_array();
    return $ur_val[0]['school_db'];
}

function testing_telenor($to="",$from="GMINNS Info",$message="",$student_id){
    $to=validate_phone_num($to);
    $CI=& get_instance();
    $CI->load->database();
//api code
    $username = "923074446694";
    $password = "8563";
	
    $mobile = $to;
    $sender = $from;
//$message = "Test SMS From SendPK.com";

    $url = "http://sendpk.com/api/sms.php?username=".$username."&password=".$password."&mobile=".$mobile."&sender=".urlencode($sender)."&message=".urlencode($message)."";
    $ch = curl_init();
    $timeout = 30;
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
    $responce = curl_exec($ch);
    curl_close($ch);
//end api
    $data['sys_sch_id']=$_SESSION['sys_sch_id'];
    $data['recepient']=$to;
    $data['sender']=$from;
    $data['student_id']=$student_id;
    $data['message']=$message;
    $data['status']=$responce;//$response;
    $data['is_send']=1;//$response;
    $data['sms_type']=1;
    $data['sms_service']="SendPK";
    $CI->db->insert(get_system_db().".sms_log",$data);
//}

    return $responce;
}
 

function get_email_layout($message ="", $btn_url = ""  , $btn_text ="Login")
{
    
   
    /*
    $CI =& get_instance();
    $CI->load->database();
        
    $qur = "select * from ".get_school_db().".email_layout_settings where school_id = ".$_SESSION['school_id']." ";
        $email_layout_arr = $CI->db->query($qur)->result_array();
        
        $school_name =  $_SESSION['school_name'];
        $address     =  "dummy address";
        $logo        =  $_SESSION['school_logo'];
        $folder_name =  $_SESSION['folder_name'];
        $logo_path   =  base_url()."assets/images/indici-edu-logo-SVG.svg";  //base_url()."uploads/".$folder_name."/".$_SESSION['school_logo'];
        $email_img   =  base_url().'assets/images/email_img.jpg';
         
         
        if($log_btn == 1)
        {
            $button = '<p style="padding-left:15px;">You can login now, press this button</p><a class="btn btn-primary" style="margin-left:15px;text-decoration: none; color: #fff;
    	    background-color: #337ab7;border-color: #2e6da4; display: inline-block;
    	    margin-bottom: 0;font-weight: 400;text-align: center;white-space: nowrap;
    	    vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;
    	    cursor: pointer;background-image: none;border: 1px solid transparent;
    	    padding: 6px 12px;font-size: 14px;line-height: 1.42857143;border-radius: 4px;
    	    -webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;
    	    user-select: none;" target="_blank" href="https://indiciedu.com.pk/login">Login</a>';
        }else{
            $button = '';
        }
    
        $template_1 = '<div style="Margin:0px auto;max-width:700px;">
    	<table  width="100%" style="border-collapse: collapse;border-spacing: 0;">
    		<tbody>
    			<tr style="height: 100px; width: 100%; background-color: #40b3f4">
    				<td colspan="2">
                        <div style="align-content: center;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size: 14px;line-height:1.5;text-align: center;color: white;margin-top: 10px;">
                        <h2>indici-edu</h2>
                        </div>
    				</td>
    				<td><img src="'.$logo_path.'" style="height: auto;width: 120px;margin-top: 10px; border-radius:10px;"></td>
    			</tr>
    			<tr style="height: 10px; width: 100%; background-color: #3f4d61;">
    				<td colspan="3">&nbsp;</td>
    			</tr>
    			<tr style="height: 150px; width: 100%;  background-color: white;">
    				<td colspan="3">
    					<html>
    						<head>
    							<title></title>
    						</head>
    						<body>
    							<div>
    								<div style="margin-left: 30px;">
    								<p>'.$message.'</p>
    								<br>
    								    '.$button.'
    								    <br>
    								    <br>
    									<strong>Best Regards <br> Team indici-edu</strong>
    								</div>
    							</div>
    							<br>
    						</body>
    					</html>
    				</td>
    			</tr>
    			<tr style="height: 10px; width: 100%; background-color: #3f4d61;">
    				<td colspan="3">&nbsp;</td>
    			</tr>
    			<tr style="height: 50px; width: 100%; background-color: #40b3f4">
    				<td colspan="3">
    					<p style="color:white;">*** This is an automatically generated email, please do not reply ...</p>
    				</td>
    			</tr>
    			<tr style="height: 20px; width: 100%; text-align:center; background-color: #40b3f4">
    				<td colspan="3">
    					<a style="text-decoration: none;" href="https://indiciedu.com.pk/" target="blank">Indici-edu</a>
    				</td>
    			</tr>
    		</tbody>
    	</table>
    	</div>';
    	
    	return $template_1;
    	
    	*/

        
    	/*
    	$template_2 = '<!doctype html>
    <html lang="en">
      <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> 
      </head> <style>
    @media only screen and (max-width: 768px) {
      .small-d{
          text-align:center;
      }
    }
    .login-btn{
        display: inline-block;text-align: center;color: #fff;background-color: #0d6efd;border-color: #0d6efd;padding: 5px;text-decoration: none;
    }.emltop-innerr
    {
        background: #f3f3f3;border-radius: 44px;box-shadow: 0px 9px 9px -10px #3f4244;
    }section.email-footer a span { color: #000; } section.email-top{background:linear-gradient(0deg,#fff 35%,#74c1ed 35%)}li.list-group-item{border:none}li.list-group-item i{font-size:29px}section.email-footer{background:#f3f3f3}section.email-footer a{text-decoration:none;color:#00bcd4}section.email-footer a:hover{color:#03a9f4} </style> <body>
    <section class="email-top">
        <div class="container-fluid"> 
        <div class="container" style="padding-top: 50px;"> 

            <div class="row d-flex align-items-center emltop-innerr px-3">
                <div class="col-sm-12 col-md-6 py-3 small-d">
                <img src="'.$school_logo.'" class="img-fluid" style="width:140px;" alt="...">  
                </div>
                <div class="col-sm-12 col-md-6 py-3 small-d d-flex justify-content-sm-center justify-content-md-end">
                    <h3 class="m-0">'.$school_name.'</h3>
                </div>
            </div>
        </div>
        </div> 
    </section>
    <section class="email-body">
        <div class="container-fluid"> 
        <div class="container">
            <div class="row d-flex align-items-center"> 
                <div class="col-sm-12 col-md-12 py-5">
                    <h4>Dear ,</h4>
                    <p class="mb-4">'.$message.'</p>
                    <h4>Best Regards</h4>
                    <h6 class="mb-4">'.$school_name.' Team</h6>
                    <a href="'.$btn_url.'" class="login-btn" target="_blank" > '.$btn_text.' </a>
                </div>
            </div>
        </div>
        </div> 
    </section>
    <section class="email-footer  pt-5">
        <div class="container-fluid"> 
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-sm-12 col-md-6 pt-0">
                    <img src="'.$edu_logo.'" class="img-fluid school-logo" alt="school logo" style="height: 80px;">
                </div>
                <div class="col-sm-12 col-md-6 pt-0 d-flex justify-content-sm-start justify-content-md-end"> 
                    <ul class="list-group list-group-horizontal">
                        <li class="list-group-item px-1 bg-transparent"><a href="https://www.facebook.com/Indici.edu/"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
                        <li class="list-group-item px-1 bg-transparent"><a href="https://twitter.com/indiciedu"><i class="fa fa-twitter-square" aria-hidden="true"></i></a></li>
                        <li class="list-group-item px-1 bg-transparent"><a href="https://www.linkedin.com/company/indiciedu/"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
                    </ul>
                </div> 
                <div class="col-sm-12 col-md-12 col-lg-6 pb-3">
                    <p class="my-4">© 2017-2021 All Rights Reserved By <a href="https://indiciedu.com.pk/">indici edu</a></p>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 pb-3 d-flex justify-content-md-center justify-content-lg-end  justify-content-md-end"> 
                    <ul class="d-sm-inline-flex p-0 m-0">
                        <li class="list-group-item px-1 bg-transparent"> <a href="mailto:info@indiciedu.com.pk"><span>Email:</span> info@indiciedu.com.pk</a></li>
                        <li class="list-group-item px-1 bg-transparent"> <a href="tel:00923347675306"><span>Phone:</span> +92-334-7675306</a></li>
                        <li class="list-group-item px-1 bg-transparent"> <a href="https://indiciedu.com.pk/"><span>Web:</span> www.indiciedu.com.pk</a></li>
                    </ul>
                </div> 
            </div>
        </div>
        </div> 
    </section>
 
 
    <!-- Option 1: Bootstrap Bundle with Popper  For Email Template-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  
    </body>
    </html>'; 

    return $template_2;
    */
    
    /*
    $template_3 = '<!DOCTYPE html>
<html lang="en">
<title>Indici edu - Email Tempalte </title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
 .container {
    max-width: 1320px; 
        margin: auto;
}
body {
    margin: 0;
    font-family: sans-serif;
}
.school-edu-row {
    display: flex;
    justify-content: space-between;background: #f3f3f3;
    border-radius: 44px;
    box-shadow: 0px 9px 9px -10px #3f4244;align-items: center;margin: 14px;
}section.email-top {
    background: linear-gradient( 
0deg,#fff 35%,#74c1ed 35%);
}.school-edu-container-top{
    padding-top: 76px;
}.p-3 {
    padding: 20px;
}
footer.email-footer {
    background: #f3f3f3;
}
.edu-flex  {
    display: flex;
}.school-edu-footer {
    justify-content: space-between;align-items: center;margin: 0px 15px;
    }
    ul.indiciedu-social-icons {
        margin: 0px;
    list-style: none;
    display: flex;    padding: 0;
    }.indiciedu-social-icons li {
    font-size: 36px;
    margin: 0px 7px;
}.school-edu-bottom {
    justify-content: space-between;align-items: center;    margin: 0px 15px;
}ul.indiciedu-social-links {
    list-style: none;
    display: flex;    padding: 0;
}ul.indiciedu-social-links li { 
    margin: 5px 5px;
}ul.indiciedu-social-links span {
    color: #000;
}ul.indiciedu-social-links li a {
    text-decoration: none !important;
}.school-edu-container-footer {
    padding: 30px 0px;
}
@media screen and (max-width: 768px) {
    ul.indiciedu-social-links { 
    display: block;
}
}
@media screen and (max-width: 457px) {
   .school-edu-row {
    display: block;
    text-align: center;
} 
}


@media screen and (max-width: 600px) {
    .edu-flex {display: block;} 
}

</style>
<body>
 
    <section class="email-top">
        <div class="container-fluid"> 
        <div class="container school-edu-container-top">  
            <div class="school-edu-row p-3">
                <div class="school-logo-col p-3">
                    <img src="https://dev.indiciedu.com.pk/uploads/sch214-214/email_layout/email_1637054447.jpg" class="img-fluid" style="width:140px;" alt="...">  
                </div>
                <div class="school-name-col p-3">
                    <h2>Campusnetic School 1</h2>
                </div>
            </div>
        </div> 
        </div> 
    </section>


    <section class="email-body">
        <div class="container-fluid">
        <div class="container school-edu-container-body">  
            <div class="school-edu-body p-3"> 
                <div class="school-name-col p-3">
                    <h1>Dear ,</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                         et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                          ex ea commodo consequat. Lorem ipsum dolor sit amet consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                         et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                          ex ea commodo consequat. Lorem ipsum dolor sit amet consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                         et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                          ex ea commodo consequat. Lorem ipsum dolor sit amet consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                         et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                          ex ea commodo consequat. Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                         et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                          ex ea commodo consequat. Lorem ipsum dolor sit amet consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                         et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                          ex ea commodo consequat. Lorem ipsum dolor sit amet consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                         et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                          ex ea commodo consequat. Lorem ipsum dolor sit amet consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                         et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                          ex ea commodo consequat. Lorem ipsum dolor sit amet</p>
                    <h1>Best Regards</h1>
                    <h4>Campusnetic School 1 Team</h4>
                </div>
            </div>
        </div>
        </div> 
    </section>


    <footer class="email-footer">
        <div class="container-fluid">
        <div class="container school-edu-container-footer">  
            <div class="school-edu-footer  edu-flex  edu-block">
                <div class="school-logo-col">
                    <img src="https://indiciedu.com.pk/frontend/wp-content/uploads/2021/06/SVGindici-edu.svg" class="img-fluid" style="width: 300px; max-width:340px;" alt="...">  
                </div>
                <div class="school-name-col">
                    <ul class="indiciedu-social-icons">
                        <li><i class="fa fa-facebook-square" aria-hidden="true"></i></li>
                        <li><i class="fa fa-twitter-square" aria-hidden="true"></i></li>
                        <li><i class="fa fa-linkedin-square" aria-hidden="true"></i></li>
                    </ul>
                </div>
            </div>
            <div class="school-edu-bottom edu-flex edu-block">
                <div class="indici-copyrights-col ">
                    <p class="my-4">Copyright © 2020 <a href="index.html">indici edu</a></p> 
                </div>
                <div class="indici-contact-col ">
                    <ul class="indiciedu-social-links">
                        <li> <a href="mailto:info@indiciedu.com"><span>Email: </span>info@indiciedu.com</a></li>
                        <li> <a href="tel:03471234567"><span>Phone: </span>03471234567</a></li>
                        <li> <a href="https://indiciedu.com.pk/"><span>Web: </span>https://indiciedu.com.pk/</a></li>
                    </ul>
                </div>
            </div>
        </div>
        </div> 
    </footer>
 
 

</body>
</html>';

return $template_3;

*/

	$CI =& get_instance();
    $CI->load->database();
	$qur = "select * from ".get_school_db().".email_layout_settings where school_id = ".$_SESSION['school_id']." ";
    $email_layout_arr = $CI->db->query($qur)->row();
    
    $folder_name =  $_SESSION['folder_name'];
    
    if($email_layout_arr->school_name == ""){
        $school_name =  $_SESSION['school_name'];
    }else{
        $school_name =  $email_layout_arr->school_name;
    }
    
    if($email_layout_arr->logo == ""){
        $school_logo = base_url()."uploads/".$folder_name."/".$_SESSION['school_logo'];
    }else{
        $school_logo =  base_url()."uploads/".$folder_name."/email_layout/".$email_layout_arr->logo;
    }
    
    $edu_logo   =  base_url()."assets/images/indici-edu-logo-SVG.svg";
    
    
    if($btn_url == ""){
        $btn_url = base_url()."institute/".$_SESSION['landing_page'];
    }
    // $school_logo = xss_clean($school_logo);
    // $edu_logo = xss_clean($edu_logo);
        
$template_4 ='<!DOCTYPE html>
<html lang="en">
<title>Indici edu - Email Tempalte </title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
 .container {
    max-width: 1320px; 
        margin: auto;
}
body {
    margin: 0;
    font-family: sans-serif;
}
.school-edu-row { 
    justify-content: space-between;background: #f3f3f3;
    border-radius: 44px;
    box-shadow: 0px 9px 9px -10px #3f4244;align-items: center;margin: 14px;
}section.email-top {
     
}.school-edu-container-top{
    padding-top: 76px;
}.p-3 {
    padding: 20px;
}
.edu-flex  {
    display: flex;
}.school-edu-footer {
    justify-content: space-between;align-items: center;margin: 0px 15px;
    }
    ul.indiciedu-social-icons {
        margin: 0px;
    list-style: none;
    display: flex;    padding: 0;
    }.indiciedu-social-icons li {
    font-size: 36px;
    margin: 0px 7px;
}.school-edu-bottom {
    justify-content: space-between;align-items: center;    margin: 0px 15px;
}ul.indiciedu-social-links {
    list-style: none !important ;
    display: flex !important ;    padding: 0 !important ;
}ul.indiciedu-social-links li { 
    margin: 5px 5px !important ;
}ul.indiciedu-social-links span {
    color: #000 !important ;
}ul.indiciedu-social-links li a {
    text-decoration: none !important;
}.school-edu-container-footer {
    padding: 30px 0px !important ;
}
@media screen and (max-width: 768px) {
        ul.indiciedu-social-links { 
        display: block !important ;
    }
}
@media screen and (max-width: 457px) {
       .school-edu-row {
        display: block !important ;
        text-align: center !important ;
    } 
}
@media screen and (max-width: 600px) {
    .edu-flex {display: block !important ;} 
}
</style>
<body>
 
    <section class="email-top" style="background: linear-gradient( 0deg,#fff 35%,#74c1ed 35%);">
        <div class="container-fluid"> 
        <div class="container school-edu-container-top" style="padding-top: 76px;">  
            <div class="school-edu-row p-3" style="text-align: center; background: #f3f3f3; border-radius: 44px; box-shadow: 0px 9px 9px -10px #3f4244; align-items: center; margin: 14px; background: linear-gradient( 0deg,#fff 35%,#74c1ed 35%) !important; border: solid 2px #fff;">
            
            <div class="edu-mojan">
                <div class="school-logo-col p-3">
                    <img src="'.xss_clean($school_logo).'" class="img-fluid" style="width:140px;border-radius: 5px !important;" alt="...">  
                </div>
                <div class="school-name-col">
                    <h2>'.$school_name.'</h2>
                </div>
            </div>
            
            </div>
        </div> 
        </div> 
    </section>
    <section class="email-body">
        <div class="container-fluid">
        <div class="container school-edu-container-body">  
            <div class="school-edu-body p-3" style="padding: 20px !important;"> 
                <div class="school-name-col p-3">
                    <h1>Dear ,</h1>
                    <p style="text-align: justify;">'.$message.'</p>
                    <h1>Best Regards</p>
                    <h4>'.$school_name.' Team</h4>
                </div>
            </div>
        </div>
        </div> 
    </section> 
    <footer class="email-footer" style="background: #f3f3f3;">
        <div class="container-fluid">
        <div class="container school-edu-container-footer" style="padding: 30px 0px;">  
            <div class="school-edu-footer  edu-flex  edu-block" style="margin: 0px 15px;">
                <div class="school-logo-col">
                    <img src="'.xss_clean($edu_logo).'" class="img-fluid" style="width: 300px; max-width:340px;" alt="...">  
                </div>
                <div class="school-name-col">
                     
                </div>
            </div>
            <div class="school-edu-bottom edu-flex edu-block" style="justify-content: space-between; align-items: center; margin: 0px 15px;">
                <div class="indici-copyrights-col ">
                    <p class="my-4">Copyright © 2020 <a href="https://indiciedu.com.pk/">indici edu</a></p> 
                </div>
                <div class="indici-contact-col ">
                    <ul class="indiciedu-social-links">
                        <li style="margin: 5px 5px !important;"> <a href="mailto:info@indiciedu.com" style="text-decoration: none !important;"><span style="color: #000 !important;">Email: </span>info@indiciedu.com</a></li>
                        <li style="margin: 5px 5px !important;"> <a href="tel:03471234567" style="text-decoration: none !important;"><span style="color: #000 !important;">Phone: </span>+92-334-7675306</a></li>
                        <li style="margin: 5px 5px !important;"> <a href="https://indiciedu.com.pk/" style="text-decoration: none !important;"><span style="color: #000 !important;">Web: </span>www.indiciedu.com.pk</a></li>
                    </ul>
                </div>
            </div>
        </div>
        </div> 
    </footer>
</body>
</html>
';

return $template_4;
      
}


