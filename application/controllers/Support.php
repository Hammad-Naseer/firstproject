<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Support extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();
		//$this->load->database();
		$this->load->helper('message');
		$this->load->helper('url');
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');

    }
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        if ($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }
    
    function supports($param1="",$param2="",$param3="")
    {
		if ($_SESSION['user_login'] != 1)
            redirect(base_url());
        if ($param1 == 'create') 
        {
			$data['user_detail_id']=$_SESSION['login_detail_id'];
			$data['title']= $this->input->post('problem_title');
			$data['problem_priority']=$this->input->post('problem_priority');
			$data['url']=$this->input->post('url');
			$data['description']=$this->input->post('description');
			$data['problem_date']=date('Y-m-d h:i:s');
			$data['entered_by']='1';
			$data['system_school_id'] = $_SESSION['sys_sch_id'];
			$data['status']=1;
			$this->db->insert(get_system_db().'.system_problem', $data);

			//____________________email to client ________________________________
			$message = "<!DOCTYPE html>
						<html>
						<head>
							<title></title>
						</head>
						<body>
							<div>
							<strong>Dear Valued Customer,</strong>
							<div style='margin-left: 30px;'>
							<p>Thanks for your email to notify us of the difficulties you have been experiencing.</br> Our support team will work on it's resolution.</p>
							<p>Thanks For your patience.</p>
							</br>
								<strong>Best Regards :</strong>
								<p>Indic-edu Support Team</p>
							</div>
						</div>
						</body>
						</html>";
			$subject = 'Indic-edu Support';
            $to_email = $_SESSION['user_email'];
			$from = "No Reply";
			$id = 0;
			$email_layout = get_email_layout($message);
			email_send($from,"Indic-edu",$to_email,$subject,$email_layout,$id);

			//____________________email to admin________________________________

			$system_prob_id = $this->input->post('problem_priority');
			$q="SELECT problem_priority FROM ".get_system_db().".system_problem WHERE system_prob_id=".$system_prob_id." ";
			$query=$this->db->query($q)->result_array();

			$query[0]['problem_priority'];
        	$priority_arr= priority_listing($query[0]['problem_priority']);
    		$priority_heading_id=$priority_arr['index_val'];
    		$priority_color=priority_color($priority_heading_id);
			// email to admin
			$message = "<!DOCTYPE html>
						<html>
						<head>
							<title></title>
						</head>
						<body>
							<strong>Dear Admin,</strong>
							<div style='margin-left: 30px;'>
							<p>Problem statement given below</p>
							<table>
							<tr>
								<td>From :</td>
								<td>".$_SESSION['school_name']."</td>
							</tr>
							<tr>
								<td>Problem Title:</td>
								<td>". $this->input->post('problem_title') ."</td>
							</tr>
							<tr>
								<td>Problem Date : </td>
								<td>".date('Y-m-d h:i:s')."</td>
							</tr>
							<tr>
								<td>Problem Priority : </td>
								<td>
								
								<span><strong>".$priority_arr['heading']."</strong></span></br>
                        	 	<span style='color:".$priority_color."'>".$priority_arr['sub_heading']."</span>
								</td>
							</tr>
							<tr>
								<td>Url : </td>
								<td>".$this->input->post('url')."</td>
							</tr>
							<tr>
								<td>Description : </td>
								<td>".$this->input->post('description')."</td>
							</tr>
							</table>
							</br>
							<strong>Best Regards :</strong>
							<p>Indic-edu Support Team</p>
							</div>
						</body>
						</html>";
			$f_email = $_SESSION['user_email'];
			$send_name = $_SESSION['school_name'];
			$to_email ='saad.afzal@my.web.pk';
// 			$to_email ='hammadraja2003@gmail.com';
			$subject = 'Indic-edu Support';
			
			$email_layout = get_email_layout($message);
			email_send($f_email,$send_name,$to_email,$subject,$email_layout,0);
			//__________________________________________________________________

            //$to="923315284304";
			$to="923088805106";
			$from="Indici Edu";
			$message="Problem Reported: ".$priority_arr['heading'];
			$student_id = 0;
			
			//working
			send_sms($to,$from,$message,$student_id);

			$this->session->set_flashdata('club_updated', get_phrase('problem_detail_successfully_submitted'));
		 $page_data['page_name']  = 'system_support';
        $page_data['page_title'] = get_phrase('system_support');
        $this->load->view('backend/index', $page_data);
 		}
 
        $page_data['page_name']  = 'system_support';
        $page_data['page_title'] = get_phrase('system_support');
        $this->load->view('backend/index', $page_data);
	}
	
	function view_support_problems()
	{
	    $q = "SELECT sp.title , sp.problem_priority , sp.url , sp.description , sp.problem_date , sp.system_school_id , sp.status as problem_status , sp.closed_by , sp.closing_date , 
	    spc.comments , spc.comment_date FROM ".get_system_db().".`system_problem` sp
	    LEFT join ".get_system_db().".system_problem_comments spc on sp.system_prob_id = spc.system_prob_id
	    where sp.system_school_id = ".$_SESSION['school_id']." order by sp.system_prob_id Desc";
	    
	    $problems_arr = $this->db->query($q)->result_array();
	    
	    $page_data['problems_arr']  = $problems_arr;
	    $page_data['page_name']     = 'view_support_problems';
        $page_data['page_title']    = get_phrase('view_support_problems');
        $this->load->view('backend/index', $page_data);
	}
    
  
}
    