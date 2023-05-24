<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		
        /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        if($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');	
    }
    /***default functin, redirects to login page if no admin logged in yet***/
    
    function email_test()
    {
        $this->load->helper('message');
        $email =  email_send("No Reply",'INDICI EDU',"ali.hammad@indiciedu.com.pk","Test Email","Message CI3",1);
        $email =  email_send("No Reply",'INDICI EDU',"zeeshanarain4455@gmail.com","Test Email","Message CI3",1);
        $email =  email_send("No Reply",'INDICI EDU',"hammadalirajpoot@yahoo.com","Test Email","Message CI3",1);
    }
    
    public function index()
    {
        if ($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }
    
    function landing_page()
    {
        $data['title'] = "Indici Edu";
        $this->load->view("backend/custom_login/apsfwo_index",$data);
        $this->load->view("backend/custom_login/footer");
    }
    
    /***ADMIN DASHBOARD***/
    function dashboard()
    {

        $schl_id = $_SESSION['sys_sch_id'];
        sms_email_verification_school($schl_id);
    	if (!$_SESSION['user_login'])
            redirect(base_url());
        
        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('admin_dashboard');
        $this->load->view('backend/index', $page_data);
        
    }

    function add_parent()
    {
	    $student_id=$this->uri->segment(3);
	    $page_data['p_row']=$this->db->query("SELECT * from ".get_school_db().".student_relation sr 
	    	INNER JOIN ".get_school_db().".student_parent sp on sr.s_p_id=sp.s_p_id
	    	WHERE sr.school_id=".$_SESSION['school_id']." 
	    	AND sr.student_id=$student_id")->result_array();
		//echo $this->db->last_query();
		$page_data['page_name']  = 'add_parent';
	    $page_data['page_title']=get_phrase('parent_account');
	    $this->load->view('backend/index', $page_data);
	}
    function attach_parent($student_id="",$class_id="")
    {

        $data['p_name']= $this->input->post('p_name');
        $data['occupation']=$this->input->post('occupation');
        $data['contact']=$this->input->post('contact');
        $data['email']=$this->input->post('email');
        $s_p_id= $this->input->post('s_p_id');

        $password= $this->input->post('password');

        if($password=="")
        {

        }
        else
        {
        $data['password']=$password;
        }

        $relation=$this->input->post('relation');

        $image_file= $this->input->post('image_file');

        $user_file= $_FILES['userfile']['name'];

        if($user_file!="")
        {

            if($image_file!="")
            {
            $del_location="uploads/student_image/$image_file";
               file_delete($del_location);
            }

            $filename=$_FILES['userfile']['name'];
             $ext = pathinfo($filename, PATHINFO_EXTENSION);

             $data['id_file']=$relation.'_cnic_attach_'.time().'.'.$ext;

              move_uploaded_file($_FILES['userfile']['tmp_name'],'uploads/student_image/'.$data['attachment']);
        }
        $this->db->where('s_p_id', $s_p_id);
        $this->db->update(get_school_db().'.student_parent', $data);

        $data_s=array('parent_id'=>$s_p_id);

        $this->db->where('student_id',$student_id);
        $this->db->update(get_school_db().'.student', $data_s);

        $this->crud_model->clear_cache();



        redirect(base_url() . 'admin/parent/' . $class_id);
    }
	function update_parent()
	{
		$page_data['control'] =$this->uri->segment(3);
		$page_data['std_id']=$this->uri->segment(4);
		$page_data['section_id']=$this->uri->segment(5);
		$page_data['student_status']=$this->uri->segment(6);
		$page_data['page_name'] ='update_parent';
		$page_data['page_title'] = get_phrase('update_parent');

		$this->load->view('backend/index', $page_data);	
	}	
	function update_student()
	{
		//$page_data['controller']=$this->uri->segment(3);	
		$page_data['control'] =$this->uri->segment(3);
		$page_data['std_id']=$this->uri->segment(4);
		$page_data['section_id']=$this->uri->segment(5);
		$page_data['student_status']=$this->uri->segment(6);
		$page_data['page_name'] ='update_student';
		$page_data['page_title'] = get_phrase('update_student');
		

		$this->load->view('backend/index', $page_data);	
	}
	function save_update_parent($student_id="",$class_id="")
	{
		$data['p_name']= $this->input->post('f_p_name');
		$data['occupation']=$this->input->post('f_occupation');
		$data['contact']=$this->input->post('f_contact');
		$data['id_no']=$this->input->post('f_cnic');
		$data['school_id']=$_SESSION['school_id'];
		$s_p_id= $this->input->post('s_p_id');
		$image_file= $this->input->post('image_file');

		$user_file= $_FILES['f_userfile']['name'];
		if($user_file!="")
		{
			if($image_file!="")
			{
				$del_location=system_path($image_file,'student');
				file_delete($del_location);
			}
			$data['attachment']=file_upload_fun("f_userfile","student","f");
		}
		if($data['id_no']!="" && $data['p_name']!="") 
		{
			if($s_p_id!="")
			{
				$this->db->where('school_id',$_SESSION['school_id']);
				$this->db->where('s_p_id',$s_p_id);
				$this->db->update(get_school_db().'.student_parent', $data);
			}
			else
			{
				$this->db->insert(get_school_db().'.student_parent',$data);	
				$sp_id=$this->db->insert_id();

				$f_array=array('student_id'=>$student_id,
				's_p_id'=>$sp_id,
				'relation'=>'f',
				'school_id'=>$_SESSION['school_id']
				);
				$this->db->insert(get_school_db().'.student_relation',$f_array);
			}
		}

		// mother

		$m_data['p_name']= $this->input->post('m_p_name');
		$m_data['occupation']=$this->input->post('m_occupation');
		$m_data['contact']=$this->input->post('m_contact');
		$m_data['id_no']=$this->input->post('m_cnic');
		$m_data['school_id']=$_SESSION['school_id'];
		$m_s_p_id= $this->input->post('m_s_p_id');
		$m_image_file= $this->input->post('m_image_file');

		$user_file= $_FILES['m_userfile']['name'];
		if($user_file!="")
		{
			if($m_image_file!="")
			{
				$del_location=system_path($m_image_file,'student');
		 		file_delete($del_location);
		 	}
		 	$m_data['attachment']=file_upload_fun("m_userfile","student","m");
		}  
		if($m_data['id_no']!="" && $m_data['p_name']!="")
		{
			if($m_s_p_id!="")
			{
				$this->db->where('s_p_id',$m_s_p_id);
				$this->db->update(get_school_db().'.student_parent', $m_data);
			}
			else
			{	
				$this->db->insert(get_school_db().'.student_parent',$m_data);
				$sp_id=$this->db->insert_id();
				$f_array=array('student_id'=>$student_id,
				's_p_id'=>$sp_id,
				'relation'=>'m',
				'school_id'=>$_SESSION['school_id']
				);
				$this->db->insert(get_school_db().'.student_relation',$f_array);
			}
		}

		// guardian

		$g_data['p_name']= $this->input->post('g_p_name');
		$g_data['occupation']=$this->input->post('g_occupation');
		$g_data['contact']=$this->input->post('g_contact');
		$g_data['id_no']=$this->input->post('g_cnic');
		$g_data['school_id']=$_SESSION['school_id'];
		$g_s_p_id= $this->input->post('g_s_p_id');
		$g_image_file= $this->input->post('g_image_file');
		$user_file= $_FILES['g_userfile']['name'];


		if($user_file!="")
		{
			if($g_image_file!="")
			{
				$del_location=system_path($g_image_file,'student');
			 	file_delete($del_location);
			}
			$g_data['id_file']=file_upload_fun("g_userfile","student","g");
		}  
		         
		if($g_data['id_no']!="" && $g_data['p_name']!="")
		{
			if($g_s_p_id!="")
			{
				$this->db->where('school_id',$_SESSION['school_id']);
				$this->db->where('s_p_id',$g_s_p_id);
				$this->db->update(get_school_db().'.student_parent', $g_data);
			}
			else
			{
				$this->db->insert(get_school_db().'.student_parent',$g_data);
				$sp_id=$this->db->insert_id();


				$f_array=array('student_id'=>$student_id,
				's_p_id'=>$sp_id,
				'relation'=>'g',
				'school_id'=>$_SESSION['school_id']
				);

				$this->db->insert(get_school_db().'.student_relation',$f_array);
			}
		}

		$this->crud_model->clear_cache();
		//redirect(base_url() . 'admin/student_information/' . $class_id);
		redirect($_SERVER['HTTP_REFERER']);
	}
    /****MANAGE STUDENTS CLASSWISE*****/
	function student_add()
	{
		if ($_SESSION['user_login'] != 1)
            redirect(base_url());
			
		$page_data['page_name']  = 'student_add';
		$page_data['page_title'] = get_phrase('add_student');
		$this->load->view('backend/index', $page_data);
	}
	function parent_cnic()
	{
		$cnic=$this->input->post('cnic');
		$s_p_id=$this->input->post('pid');		
		$qur=$this->db->query("select * from ".get_school_db().".student_parent where school_id=".$_SESSION['school_id']." AND id_no='$cnic' AND s_p_id!=$s_p_id ")->result_array();

		//echo $this->db->last_query();
		if(count($qur)>0)
		{
			echo "no";
		}else
		{
			echo "yes";
		}
	}
	function student_information($class_id='')
	{
		if ($_SESSION['user_login'] != 1)
		redirect('login');

		$page_data['page_name']= 'student_information';
		$page_data['page_title']=get_phrase('student_information'). " - ".get_phrase('class')." : ".$this->crud_model->get_class_name($class_id);
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
	}
	function get_student_new()
	{
		$cnic=$this->input->post('cnic');

		$q="select s.name as student_name, cs.title as section_name,image,roll,sr.relation ,c.name as class_name ,d.title as department_name
		from ".get_school_db().".student_parent sp 
		inner join ".get_school_db().".student_relation sr on sr.s_p_id=sp.s_p_id 
		inner join ".get_school_db().".student s on s.student_id=sr.student_id 
		inner join ".get_school_db().".class_section cs on cs.section_id=s.section_id 
		inner join ".get_school_db().".class c on c.class_id=cs.class_id
		inner join ".get_school_db().".departments d on d.departments_id=c.departments_id
		where sp.school_id=".$_SESSION['school_id']." ANd sp.id_no='".$cnic."'";
		$query=$this->db->query($q);
		//echo $this->db->last_query();
		if($query->num_rows>0)
		{
			foreach($query->result() as $rows)
			{
				if($rows->image!="")
				{
					$img_comp=display_link($rows->image,"student");
				}else
				{
					$img_comp= base_url().'/uploads/default.png';
				}
				echo ' 
					<div class="col-lg-6 col-md-6 col-sm-6 pdl0 ">
					<div class="stdd">
					<div class="col-lg-4 col-md-4 col-sm-4 std_one">
					<span class="std_img"><img class="myim img-responsive" src="'.$img_comp.'" /></span>
					</div>
					<div class="col-lg-8 col-md-8 col-sm-8 std_two">
					<p class="std_name">'.get_phrase('name').': '.$rows->student_name.' </p>
					<p class="std_roll">'.get_phrase('roll').': '.$rows->roll.'</span>
					<p class="std_class">'.get_phrase('department').': '.$rows->department_name.'</p>
					<p class="std_class">'.get_phrase('class').': '.$rows->class_name.'</p>
					<p class="std_class">'.get_phrase('section').': '.$rows->section_name.'</p>
					<p class="std_rel">'.get_phrase('relation').': '.parent_h($rows->relation).'</p>
					</div>
					</div>

					</div>
					';
			}
		}
	}
	function get_class()
 	{
		$dept_id=$this->input->post('department_id');
		$class_id=$this->input->post('class_id');
		echo  class_option_list($dept_id,$class_id);
	}
	function get_class_section()
	{
		$class_id=$this->input->post('class_id');
		$section_id=$this->input->post('section_id');
		echo section_option_list($class_id,$section_id);
	}
	function get_section_student()
	{
		$section_id=$this->input->post('section_id');
		echo section_student($section_id);
	}
	function call_function()
	{
		$email= $this->input->post('email');
	 	get_email($email);
	}
	function get_parent_detail()
	{
		$s_p_id= $this->input->post('s_p_id');
		$qr="select s.*, c.name as class_name from ".get_school_db().".student s inner join ".get_school_db().".class c on c.class_id=s.class_id  where s.school_id=".$_SESSION['school_id']."
		AND s.parent_id='$s_p_id'";
		$query=$this->db->query($qr)->result_array();
		foreach($query as $qur)
		{
			echo '<span><img height="50" width="50" src="'.base_url().'uploads/student_image/'.$qur['image'].'" /> </span>';
			echo '<span> '.get_phrase('name').' : '.$qur['name'].'</span>';
			//echo "<br />";
			echo '<span>'.get_phrase('class_name').' : '.$qur['class_name'].'</span>';
		}
	}
	function get_cnic()
	{
		$cnic= $this->input->post('cnic');
		$qr="select * from ".get_school_db().".parent where school_id=".$_SESSION['school_id']." AND id_card='$cnic'";
		$query=$this->db->query($qr);
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
				$data[]=$rows;
			}
			echo json_encode($data);
		}
		else
		{
			$data=array("value"=>"no");
			echo json_encode($data);	
		}
	}

	function get_cnic_stu()
	{
		$cnic= $this->input->post('cnic');
		$table_name=$this->input->post('table_name');
	 	$type_n= $this->input->post('type_n');
	 	
	 	if($type_n=="s")
	 	{
			$s_id='id_no';	
			$select_field="id_no";
			$type_n_where="";
		}
		else
		{
			$s_id='id_no';
			$select_field="*";
			$type_n_where="";
		}

		$qr="select $select_field from  ".get_school_db().".$table_name  where school_id=".$_SESSION['school_id']." AND $s_id='$cnic' $type_n_where";
		$query=$this->db->query($qr);
		//echo $this->db->last_query();
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
				$data[]=$rows;
			}
			echo json_encode($data);
		}
		else
		{
			$data=array("value"=>"no");
			echo json_encode($data);
			//print_r($data);
		}
	} 
	function get_parent()
	{
		$s_p_id= $this->input->post('s_p_id');
		$qr="select * from ".get_school_db().".student_parent   where s_p_id='$s_p_id'";
		$query=$this->db->query($qr);
		// $this->db->last_query();
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
				$data[]=$rows;
			}
			//print_r($data);
			echo json_encode($data);
			//print_r($data);	
		}
		else
		{
			$data=array("value"=>"no");
			echo json_encode($data);
		}
	}
	function get_parent_info()
	{
		$section_id=$this->input->post('section_id');
		$page_data['students']=$this->db->get_where(get_school_db().'.student', array(
			'section_id' => $section_id,
			'school_id' =>$_SESSION['school_id']
			))->result_array();
		$this->load->view("backend/admin/ajax/get_parent_ajax.php",$page_data);
	}
	function get_cnic_admin()
	{
		$cnic= $this->input->post('cnic');
		$qr="select * from ".get_school_db().".teacher where school_id=".$_SESSION['school_id']." AND id_no='$cnic'";
		$query=$this->db->query($qr);

		if($query->num_rows()>0)
		{
			echo "yes";
		}
		else
		{
			echo "no";
		}
	}
	function send_msg()
	{
		$page_data['page_name'] ='../../sms';
		$page_data['page_title'] = get_phrase('update_parent');
		$this->load->view('backend/index', $page_data);	
		$this->load->view('sms');
	}	
	
	function sms_email_verification_process()
	{
	    $page_data['page_name']  = 'sms_email_verification_process';
		$page_data['page_title'] = get_phrase('sms_email_verification_process');
		$this->load->view('backend/index', $page_data);	
	}
	
	function email_sent_for_verification()
	{
	    $this->load->helper("message");
	    $school_id   = $_SESSION['sys_sch_id'];
	    $school_name = $this->input->post("sch_name");
	    $email       = $this->input->post("email");
	    $email_code  = 'indici-edu'.rand(4,100000);
	    $email_verification = $this->db->query("UPDATE ".get_system_db().".system_school SET email_verify_code = '$email_code' WHERE sys_sch_id = '$school_id'");

	    if($email_verification){
    	   // Send Email
    	    $subject = "Email Verification";
    	    $message = "<b>Dear ".$_SESSION['name']."</b><br><br>
            Your verification Code is  <span style='color:red;'>".$email_code."</span><br>
            Enter this code in our application to activate your account. <br>
            In case of any query feel free to contact us at info@indiciedu.com.pk.";
            $email_layout = get_email_layout($message,0);
            
            email_send("No Reply", "Indici Edu", $email, $subject, $email_layout, 0,00);
            
            echo "<div class='alert alert-success'>Verification Email Sent Successfully Check Your Email Verify Code</div>";
	    }
	}
	
	function email_code_check_for_verification()
	{
	    $this->load->helper("message");
	    
	    $school_id   = $_SESSION['sys_sch_id'];
	    $school_name = $this->input->post("sch_name");
	    $email_code  = $this->input->post("email_code");
	    $email       = $_SESSION['user_email'];
	    $return      = 0;
	    
	    $check_email_code = $this->db->query("SELECT email_verify_code FROM ".get_system_db().".system_school WHERE email_verify_code = '$email_code' AND sys_sch_id = '$school_id'");
	    if($check_email_code->num_rows() > 0)
	    {
	       
	        $email_status_change = $this->db->query("UPDATE ".get_system_db().".system_school SET is_email_verify = '1' WHERE sys_sch_id = '$school_id'");    
            $return = 1;
            
            // Send Email
    	    $subject = "Your Email is Verified";
    	    $message = "<b>Dear ".$_SESSION['name']."</b><br><br>
            Your email verification successfully done, now you can use your account.
            <br>
            In case of any query feel free to contact us at info@indiciedu.com.pk.";
            $email_layout = get_email_layout($message,0);
            
            email_send("No Reply", "Indici Edu", $email, $subject, $email_layout, 0,00);
            
	    }else
	    {
	        $return = 0;
	    }
	    
	    echo $return;
	    
	}
	
	function sms_sent_for_verification()
	{
	    $this->load->helper("message");
	    $school_id   =  $_SESSION['sys_sch_id'];
	    $school_name =  $this->input->post("sch_name");
	    $phone_no    =  $this->input->post("phone_no");
	    $otp         =  mt_rand(1000,9999); //rand(4,100000);
	    $sms_verification = $this->db->query("UPDATE ".get_system_db().".system_school SET sms_otp_code = '$otp' WHERE sys_sch_id = '$school_id'");

	    if($sms_verification){
    	   
    	   // Send SMS
    	    $message = "Dear ".$_SESSION['name']." \nYour verification Code is. ".$otp."\nindici-edu";
            send_sms($phone_no,'Indici Edu',$message,0,00);
            echo "<div class='alert alert-success'>Verification SMS Sent Successfully. Verify OTP Code</div>";
	    }
	}
	
	function sms_code_check_for_verification()
	{
	    $this->load->helper("message");
	    $school_id = $_SESSION['sys_sch_id'];
	    $school_name = $this->input->post("sch_name");
	    $sms_code = $this->input->post("sms_code");                         
	    $email = $_SESSION['user_email'];
	    $phone_no    =  $this->input->post("phone_no");
	    $check_email_code = $this->db->query("SELECT sms_otp_code FROM ".get_system_db().".system_school WHERE sms_otp_code = '$sms_code' AND sys_sch_id = '$school_id'");
        $return = 0;
	    if($check_email_code->num_rows() > 0)
	    {
	        $email_status_change = $this->db->query("UPDATE ".get_system_db().".system_school SET is_sms_verify = '1' WHERE sys_sch_id = '$school_id'");    
            $return = 1;

            // Send SMS
    	    $message = "Dear ".$_SESSION['name']." \n Your sms verification successfully done, now you can use your account. \nindici-edu";
            send_sms($phone_no,'Indici Edu',$message,0,00);
            
	    }else
	    {
	        $return = 0;
	    }
	    
	    echo $return;
	}
}