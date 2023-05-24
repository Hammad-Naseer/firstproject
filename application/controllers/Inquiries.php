<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inquiries extends CI_Controller
{
    function __construct()
	{
		parent::__construct();
		$this->load->helper('message');
		$this->load->helper('student');
		$this->load->helper('exams');
// 		if($_SESSION['user_login'] != 1)
// 		redirect('login');
// 		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
// 		$this->output->set_header('Pragma: no-cache');
	}
	
	
	
	public function general_inquiry()
	{
	    $this->load->helper("message");
	    $sch_db = $_SESSION['school_db'];
	    $data = array(
	        'name'                  =>  $this->input->post("name"),
	        'email'                 =>  $this->input->post("email"),    
            'mobile_no'             =>  $this->input->post("mobile_no"),    
            'inquiry_type'          =>  $this->input->post("inquiry_type"),    
            'inquiry_description'   =>  $this->input->post("decsription"),
            'school_id'             =>  $_SESSION['parent_sys_sch_id']
	   );
	   
	   $this->db->insert($sch_db.'.sch_general_inquiries',$data);
	   
	   //Get School Num & Email
	   $this->db->where("school_id",$_SESSION['school_id']);
	   $get_sch_data = $this->db->select()->get($sch_db.'.school')->row();
       $phone_no = $get_sch_data->phone;
       $email = $get_sch_data->email;
	    
	    // Send SMS
	    $message = "Dear ".$get_sch_data->name." \n it is notified you that ".$this->input->post("inquiry_type")." general inquiry has been generated. \nindici-edu";
        send_sms($phone_no,'Indici Edu',$message,0,00);

	    // Send Email
	    $subject = "General Inquiry";
	    $email_message = "<b>Dear ".$get_sch_data->name."</b><br><br>
        it is notified you that ".$this->input->post("inquiry_type")." general inquiry has been generated.
        <br>
        In case of any query feel free to contact us at info@indiciedu.com.pk.";
        $email_layout = get_email_layout($email_message);
        
        email_send("No Reply", "Indici Edu", $email, $subject, $email_layout, 0,00);
	   
	    echo "General Inquiry Sent Successfully";
	    
	}
	
	public function admission_inquiry()
	{
	    $this->load->helper("message");
	    $sch_db = $_SESSION['school_db'];

	    $data = array(
	        'name'          =>  $this->input->post("student_name"),
	        'father_name'   =>  $this->input->post("f_name"),    
            'class_id'      =>  $this->input->post("class"),    
            'mobile_no'     =>  $this->input->post("mob_num"),    
            'email'         =>  $this->input->post("email"),
            'address'       =>  $this->input->post("address"),    
            'description'   =>  $this->input->post("decsription"),
            'school_id'     =>  $_SESSION['parent_sys_sch_id']
	    );
	   
	   $this->db->insert($sch_db.'.sch_admission_inquiries',$data);

	   $this->db->where("school_id",$_SESSION['parent_sys_sch_id']);
	   $get_sch_data = $this->db->select()->get($sch_db.'.school')->row();
       $phone_no = $get_sch_data->phone;
       $email = $get_sch_data->email;
	    
	    // Send SMS
	    $message = "Dear ".$get_sch_data->name." \n it is notified you that ".$this->input->post("student_name")." admission inquiry has been generated. \nindici-edu";
        send_sms($phone_no,'Indici Edu',$message,0,00);

	    // Send Email
	    $subject = "Admission Inquiry";
	    $email_message = "<b>Dear ".$get_sch_data->name."</b><br><br>
        it is notified you that ".$this->input->post("student_name")." admission inquiry has been generated.
        <br>
        In case of any query feel free to contact us at info@indiciedu.com.pk.
        <br>
        ";
        $email_layout = get_email_layout($email_message);
        
        if(trim($email) != '')
        {
            email_send("No Reply", "Indici Edu", $email, $subject, $email_layout, 0,00);
        }
        
        
        
        // Send Email For Parents
        $parent_email = $this->input->post("email");
	    $p_subject = "Required Additional Information For Admission Process";
	    $p_email_message = "<b>Dear ".$this->input->post("f_name")."</b>,<br><br>
        This is to confirm that we have received your request for new admission. To further carry forward the process, we need following information from your side.
        <br>
        <ul>
            <li>Student B.Form No/CNIC</li>
            <li>Student Full Name</li>
            <li>Father CNIC No</li>
            <li>Date of Birth</li>
            <li>Religion</li>
            <li>Gender</li>
        </ul>
        <br>
        Please send this information to ".$email." at your earliest for further processing of your request.
        <br>
        Regards,
        ".$get_sch_data->contact_person."
        <br>
        Admin, ".$get_sch_data->name;
        $p_email_layout = get_email_layout($p_email_message);
        email_send("No Reply", "Indici Edu", $parent_email, $p_subject, $p_email_layout, 0,00);
	   
	    echo "Admission Inquiry Sent Successfully";
	    
	}
	
	public function general_inquiry_view()
	{
	    
	    $this->db->where('school_id',$_SESSION['school_id']);
	    $page_data['general_inquiries']     = $this->db->select("*")->from(get_school_db().'.sch_general_inquiries')->order_by('s_g_i_id', 'desc')->get()->result_array();
        $page_data['page_name']  = 'general_inquiry_view';
        $page_data['page_title'] = get_phrase('general_inquiry_view');
        
        $this->load->view('backend/index', $page_data);
	    
	}
	
	public function admission_inquiry_view()
	{
	    $this->db->where('school_id',$_SESSION['school_id']);
	    $page_data['admission_inquiries']     = $this->db->select("*")->from(get_school_db().'.sch_admission_inquiries')->order_by('s_a_i_id', 'desc')->get()->result_array();
        $page_data['page_name']  = 'admission_inquiry_view';
        $page_data['page_title'] = get_phrase('admission_inquiry_view');
        $this->load->view('backend/index', $page_data);
	}
	
	public function admission_inq_action()
	{
	    $inq_ids = implode(",",$this->input->post('inquiry_ids'));   
	    $inquiry_action = $this->input->post('inquiry_action');
	    
	    // Get Admission Inquiry Record
	    $get_inquiry_data = $this->db->query("SELECT email,s_a_i_id,s_a_i_status FROM ".get_school_db().".sch_admission_inquiries WHERE s_a_i_id IN($inq_ids) ")->result();
	    
        $email_message = $this->input->post('email_message'). "<br> <b> Please Send required information at " . $_SESSION['user_email'] . "</b>";
        $subject = "Student Admission Data Collection";
		$email_layout = get_email_layout($email_message);
        foreach($get_inquiry_data as $inquir_1):
            email_send("No Reply",'Indici Edu',$inquir_1->email,$subject,$email_layout,0);
            $this->db->where("s_a_i_id",$inquir_1->s_a_i_id)->update(get_school_db().".sch_admission_inquiries",array('s_a_i_status' => 1));
        endforeach;
        
        $this->session->set_flashdata('flash_message', get_phrase('data_collection_email_send_successfully'));
        // redirect(base_url() . 'inquiries/admission_inquiry_view','refresh');
        echo "<script>window.location.href='".base_url()."inquiries/admission_inquiry_view';</script>";
	}
	
	public function move_to_candidate()
	{
	    $this->db->trans_begin();
	    
	    // Get Admission Inquiry Record
	    $adm_inq_id = $this->input->post('adm_id');
	    $section_id = $this->input->post('section_id');
	    $get_inquiry_data = $this->db->query("SELECT * FROM ".get_school_db().".sch_admission_inquiries WHERE s_a_i_id = $adm_inq_id ")->row();
	   
	   // Student Insertion
	    $data['form_num'] = $this->input->post('form_number');
	    $data['id_type'] = $this->input->post('student_id_type');
	    $data['name'] = $get_inquiry_data->name;
	    $data['id_no'] = $this->input->post('form_b');
	    $data['gender'] = $this->input->post('sex');
	    $data['email'] = $get_inquiry_data->email;
	    $data['address'] = $get_inquiry_data->address;
	    $data['phone'] = $get_inquiry_data->mobile_no;
	    $data['mob_num'] = $get_inquiry_data->mobile_no;
	    $data['section_id'] = $section_id;
	    $data['academic_year_id'] = 0;
	    $data['roll'] = 0;
	    $data['student_status'] = 1;
	    $data['school_id'] = $_SESSION['school_id'];
        $data['date_added'] = date("Y-m-d h:i:sa");
        $data['added_by'] = $_SESSION['login_detail_id'];
	    
	    $this->db->insert(get_school_db() . '.student', $data);
        $student_id = $this->db->insert_id();
        
	    // Barcode Generate
	    $scl_id = $_SESSION['sys_sch_id'];
        $bar_code_type = 112;
        $school_id = sprintf("%'06d", $scl_id);
        $std_id = sprintf("%'07d", $student_id);
        $system_id = $bar_code_type . '' . $school_id . '' . $std_id;
        $path = 'uploads/' . $_SESSION['folder_name'] . '/student';
        
        $bar_cod['barcode_image'] = $this->set_barcode($system_id, $path);
        $bar_cod['system_id'] = $system_id;
            
        $this->db->where('student_id', $student_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->update(get_school_db() . '.student', $bar_cod);
	    
	    // Parents Insertion
	    $fcnic = $this->input->post('f_cnic');
	    $query_f = $this->db->query("select * from " . get_school_db() . ".student_parent where school_id=" . $_SESSION['school_id'] . " AND id_no='" . $fcnic . "'")->result_array();
        if (count($query_f) > 0) {
            $data_f['s_p_id'] = $query_f[0]['s_p_id'];
        } else {
            $dataf['id_no'] = $fcnic;
            $dataf['p_name'] = $get_inquiry_data->father_name;
            $dataf['id_type'] = $this->input->post('id_type_f');
            $dataf['school_id'] = $_SESSION['school_id'];
            
            $this->db->insert(get_school_db() . '.student_parent', $dataf);
            $data_f['s_p_id'] = $this->db->insert_id();
        }
        
        // Student Relation Insertion
        $data_f['student_id'] = $student_id;
        $data_f['relation'] = 'f';
        $data_f['school_id'] = $_SESSION['school_id'];
        $this->db->insert(get_school_db() . '.student_relation', $data_f);
	    
	    $updated_by = $_SESSION['login_detail_id'];
        student_archive($updated_by, $student_id);

	   // Assign Group
	    $assign_group['user_group_id']  =  $this->input->post('user_group_id');
        $assign_group['school_id']      =  $_SESSION['school_id'];
        $assign_group['student_id']     =   $student_id;
        $this->db->insert(get_school_db() . '.user_rights', $assign_group);
	    
	   // Update Status Admission Inquiry
	    $this->db->where("s_a_i_id",$get_inquiry_data->s_a_i_id)->update(get_school_db().".sch_admission_inquiries",array('s_a_i_status' => 2));
	    
	    $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        
        // $email_message = $this->input->post('email_message'). "<br> <b> Please Send required information at " . $_SESSION['user_email'] . "</b>";
        // $subject = "Student Admission Data Collection";
		// $email_layout = get_email_layout($email_message);
        // email_send("No Reply",'Indici Edu',$inquir_1->email,$subject,$email_layout,0);
        
        $this->session->set_flashdata('flash_message', get_phrase('this_admission_inquiry_convert_to_candidate_successfully'));
        echo "<script>window.location.href='".base_url()."inquiries/admission_inquiry_view';</script>";
	}
	
	public function general_inq_action()
	{
	    $g_inq_id = $this->input->post('s_g_i_id');  
	    $response = $this->input->post('response');

	    // Get General Inquiry Record
	    $get_inquiry_data = $this->db->query("SELECT s_g_i_id,email,mobile_no,s_g_i_status FROM ".get_school_db().".sch_general_inquiries WHERE s_g_i_id = $g_inq_id ")->row();
	    
        // Email Sent
        if($this->input->post('email'))
        {
            $email_message = $response;
            $subject = "General Inquiry Resposne";
    		$email_layout = get_email_layout($email_message);
            email_send("No Reply",'Indici Edu',$get_inquiry_data->email,$subject,$email_layout,0,6);
        }
        
        // SMS Sent    
        if($this->input->post('sms'))
        {
            $message = $response;
            send_sms($get_inquiry_data->mobile_no, 'Indici Edu', $message, 0,6);
        }    
        
        $this->db->where("s_g_i_id",$get_inquiry_data->s_g_i_id)->update(get_school_db().".sch_general_inquiries",array('s_g_i_status' => 1));
        
        $this->session->set_flashdata('flash_message', get_phrase('general_inquiry_response_send_successfully'));
        echo "<script>window.location.href='".base_url()."inquiries/general_inquiry_view';</script>";
	}
	
	public function complete_general_inquiry()
	{
	    $id = str_decode($this->uri->segment(3));    
	    $this->db->where("s_g_i_id",$id)->update(get_school_db().".sch_general_inquiries",array('s_g_i_status' => 2));
	    $this->session->set_flashdata('flash_message', get_phrase('general_inquiry_response_completed_successfully'));
        echo "<script>window.location.href='".base_url()."inquiries/general_inquiry_view';</script>";
	}
	
	function set_barcode($code, $path)
    {
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcode = new Zend_Barcode();
        $file = $barcode->draw('Code128',
            'image',
            array('text' => $code, 'barHeight' => 20, 'drawText' => TRUE, 'withQuietZones' => FALSE, 'orientation' => 0),
            array());
        $file_name = $code . '.png';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $store_image = imagepng($file, $path . '/' . $file_name);
        return $file_name;
    }
	
	
}