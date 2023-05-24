<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();

class Event_annoucments extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('report');
        /*cache control*/
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        if($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');
    }

    /***default functin, redirects to login page if no admin logged in yet***/
    public function events_program($param1 = '', $param2 = '')
    {
        if($_SESSION['user_login']!= 1)
		redirect(base_url());
        
        //Insert Data
		if($param1 == 'create'){
			$data['event_title']        = $this->input->post('title');
			$data['event_details'] =    $this->input->post('details');
			$data['event_start_date']   = $this->input->post('start_date');
			$data['event_end_date']   = $this->input->post('end_date');
			$data['active_inactive']     = $this->input->post('status');
			$data['school_id'] = $_SESSION['school_id'];
			$this->db->insert(get_school_db().'.events_annoucments', $data);
			
			$this->session->set_flashdata('club_updated', get_phrase('event_added_successfully'));
			redirect(base_url() . 'event_annoucments/events_program');
		}
		
		if($param1 == 'do_update'){
			$data['event_title']        = $this->input->post('title');
			$data['event_details'] =    $this->input->post('details');
			$data['event_start_date']   = $this->input->post('start_date');
			$data['event_end_date']   = $this->input->post('end_date');
			$data['active_inactive']     = $this->input->post('status');
			$data['event_status']     = $this->input->post('event_status');
            
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('event_id', $param2);
			$this->db->update(get_school_db().'.events_annoucments', $data);
			$this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
			redirect(base_url() . 'event_annoucments/events_program');
		} 
        
		else if($param1 == 'edit'){
			$page_data['edit_data'] = $this->db->get_where('events_annoucments', array('event_id' => $param2,'school_id' =>$_SESSION['school_id']))->result_array();
		}
		
		if($param1 == 'delete'){
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('event_id', $param2);
			$event_del = $this->db->delete(get_school_db().'.events_annoucments');
			if($event_del)
			{
			    $this->db->where('event_id', $param2);
			    $event_del = $this->db->delete(get_school_db().'.events_annoucments_details');
			}
			$this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
			redirect(base_url() . 'event_annoucments/events_program');
		}
		
		if($param1 == 'assign')
		{
		    $dept_id = implode("','",$this->input->post('depts'));
		    $class_id = implode("','",$this->input->post('classess'));
		    $section_id = implode("','",$this->input->post('sectionss'));
		    $school_id = $_SESSION['school_id'];
	        if($dept_id !== "" && $class_id == "" && $section_id == "")
	        {
	            //  Dept Wise Get Students
	            $std_list = $this->db->query("SELECT a.student_id FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id WHERE d.departments_id IN('$dept_id') AND d.school_id = '$school_id'")->result();
	        }else if($dept_id !== "" && $class_id !== "" && $section_id == ""){
                //  Class Wise Get Students
	            $std_list = $this->db->query("SELECT a.student_id FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id WHERE a.adm_date BETWEEN '$startdate' AND '$enddate' AND c.class_id IN('$class_id') AND a.school_id = '$school_id' ")->result();		            		          
	        }else if($dept_id !== "" && $class_id !== "" && $section_id !== ""){
                //  Section Wise Get Students
		        $std_list = $this->db->query("SELECT a.student_id FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id WHERE a.section_id IN('$section_id') AND a.school_id = '$school_id'")->result(); 
	        }
		    
		    foreach($std_list as $data)
            {
                $student_id =   $data->student_id;
	            $event_data = array(
                    'event_id'      =>  $param2,
                    'student_id'    =>  $data->student_id
                );
                $tbl_name = get_school_db().".events_annoucments_details";
                $event_done = $this->db->insert($tbl_name,$event_data);

                $device_id  =   get_user_device_id(6 , $student_id , $_SESSION['school_id']);
                $title      =   "Event Announcment";
                $message    =   "A New Event has been announced.";
                $link       =   "<?php echo base_url();?>student_p/dashboard";
                sendNotificationByUserId($device_id, $title, $message, $link , $student_id , 6); 
            }
            if($event_done){
                $datas = array('event_status' => '1');
                $tbl_name = get_school_db().".events_annoucments";
                $this->db->where("event_id",$param2)->update($tbl_name,$datas);
                $this->session->set_flashdata('club_updated', get_phrase('event_assigned_successfully'));
    			redirect(base_url() . 'event_annoucments/events_program');
            }
		}
		
		$page_data['event_annoucement']=$this->db->query("SELECT * FROM ".get_school_db().".events_annoucments")->result_array();
        $page_data['page_name']='event_annoucment';
        $page_data['page_title']=get_phrase('event_annoucment');
        $this->load->view('backend/index', $page_data); 
    }
    
    function event_announcement_detail()
    {
        $id = str_decode($this->uri->segment(3));
        $page_data['announce_detail'] = $this->db->query("SELECT ea.*,ed.*,s.name,d.title as dep_name,c.name as class_name,cs.title as section_name FROM ".get_school_db().".events_annoucments ea LEFT JOIN ".get_school_db().".events_annoucments_details ed ON ed.event_id = ea.event_id LEFT JOIN ".get_school_db().".student s ON s.student_id = ed.student_id INNER JOIN ".get_school_db().".class_section cs ON cs.section_id = s.section_id INNER JOIN 
        ".get_school_db().".class c on c.class_id = cs.class_id inner join ".get_school_db().".departments d on d.departments_id = c.departments_id
        WHERE ea.event_id = '$id' AND ea.school_id = '".$_SESSION['school_id']."'")->result_array();
        $page_data['page_name']='event_announcement_detail';
        $page_data['page_title']=get_phrase('event_announcement_detail');
        $this->load->view('backend/index', $page_data); 
    }
    function event_response(){
       
      $student_id  = $_SESSION['student_id'];
      $event_detail_id = $this->input->post('event_did'); 
      $response_text = $this->input->post('response_text'); 
      $response_status = $this->input->post('eve_status');
  
      $current_date = date('Y-m-d H:i:s');
      $data = array(
          'Session_id' => 	$student_id,
          'event_detail_id' => $event_detail_id,
          'response_text' => $response_text,
          'response_status' => $response_status,
          'response_date' => $current_date,
        );
        
          $page = $this->db->query("UPDATE ".get_school_db().".events_annoucments_details
          SET event_detail_id= '".$event_detail_id."',response_text='".$response_text."',response_status= '".$response_status."',response_date= '".$current_date."'
          WHERE event_detail_id= '".$event_detail_id."'");
           echo $response_status;
        
    }
    
    
}