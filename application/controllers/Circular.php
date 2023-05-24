<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Circular extends CI_Controller
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
        $this->school_db=$_SESSION['school_db'];
        if($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');
    }

    public function index()
    {

        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }
    
    function get_section_student()
    {
        $section_id = $this->input->post("section_id");
        if($section_id != "")
        {
            echo section_student($section_id);
        }
    }
    
    function circulars($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url());

        if ($param1 == 'create') {
            
            $data['circular_title']    = $this->input->post('circular_title');
            $data['circular']          = $this->input->post('circular');
            $data['student_id']        = $this->input->post('student_id');
            $data['section_id']        = $this->input->post('section_id');
            $data['school_id']         = $_SESSION['school_id'];
            $data['create_timestamp']  = date('Y-m-d', strtotime($this->input->post('create_timestamp')));
            $data['is_active']	       = intval($this->input->post('is_active'));
            $filename                  = $_FILES['image1']['name'];
            $folder_name               = $_SESSION['folder_name'];
            $ext                       = pathinfo($filename, PATHINFO_EXTENSION);
           
            if($filename!=""){
                $data['attachment'] = file_upload_fun('image1','circular','');
            }

            $this->db->insert(get_school_db().'.circular',$data);
            $school_teachers = get_school_teachers();
            foreach($school_teachers as $teacher){
                $device_id  =   get_user_device_id(3 , $teacher['user_login_detail_id'] , $_SESSION['school_id']);
                $title      =   "New Circular";
                $message    =   "A Circular Has been Created By Admin.";
                $link       =    base_url()."teacher/circulars";
                sendNotificationByUserId($device_id, $title, $message, $link , $teacher['user_login_detail_id'] , 3);
            }

            $last_is=$this->db->insert_id();

            ///sms start here
            $this->load->helper('message');
            $message="New Circular: ".$data['circular_title']."  ".$data['circular']. " ";
            if(isset($_POST['send_message']) && $_POST['send_message']!="") {
                
                if($data['student_id']!="" || $data['student_id']>0){
                    $sms_ary=get_sms_detail($data['student_id']);
                    send_sms($sms_ary['mob_num'],'Indici Edu',$message,$data['student_id']);
                }
                else{
                    sms_to_section($data['section_id'],$message);
                    $data_status['sms_status']=1;
                    $this->db->where('notice_id',$param2);
                    $this->db->update(get_school_db().'.noticeboard',$data_status);
                }
                $data_status['sms_status']=1;
                $this->db->where('circular_id',$last_is);
                $this->db->update(get_school_db().'.circular',$data_status);
                
            }
            
            if(isset($_POST['send_email']) && $_POST['send_email']!="")
            {
                
                if($data['student_id']!="" || $data['student_id']>0){
                    $sms_ary=get_sms_detail($data['student_id']);
                    $email_layout = get_email_layout($message);
                    email_send("No Reply","Indici-Edu",$sms_ary['email'],"Circular",$email_layout,$data['student_id']);
                }
                else{
                    $email_layout = get_email_layout($message);
                    email_to_section($data['section_id'],$email_layout,"Circular");
                    $data_status['sms_status']=1;
                    $this->db->where('notice_id',$param2);
                    $this->db->update(get_school_db().'.noticeboard',$data_status);
                }
                $data_status['sms_status']=1;
                $this->db->where('circular_id',$last_is);
                $this->db->update(get_school_db().'.circular',$data_status);
                
            }

            $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
            
            
            
            //added by interns start
            if( $data['student_id'] )
            {
                    $parent_idd = get_parent_idd($data['student_id']);   
                    $stdname    =    get_student_info($data['student_id']);
                    $std_name   = $stdname[0]['student_name'];
                    $class_idd = get_class_id($data['section_id']);
                    $class_name = get_class_name($class_idd);
                    
                   $d=array(
                    'title'=>'New Circular For '.$std_name.' '.$class_name,
                    'body'=>'A Circular Has been Created By Admin.',
                    );
                     $d2 = array(
                    'screen'=>'circulars',
                    'student_id'=>$data['student_id'],
                    'section_id'=>$data['section_id'],
                    
                    );
                
                    
                    if($parent_idd)
                    {
                        $get_isUserLogin = "SELECT islogin from indicied_indiciedu_production.mobile_device_id where user_login_id = $parent_idd";
                        $isUserLogin = $this->db->query($get_isUserLogin)->row();
                        if($isUserLogin->islogin == 1)
                        {
                             notify($d,$d2,$parent_idd);
                        }
                       
                    }
            
            }
            else
            {
          
                $studentsArray=get_students_by_sectionid($data['section_id'] );
                foreach($studentsArray as $key=> $value)
                {
                    $parent_idd = get_parent_idd($value['student_id']);   
                    $stdname    =    get_student_info($value['student_id']);
                    $std_name   = $stdname[0]['student_name'];
                    $class_idd = get_class_id($data['section_id']);
                    $class_name = get_class_name($class_idd);
                    
                   $d=array(
                    'title'=>'New Circular For '.$std_name.' '.$class_name,
                    'body'=>'A Circular Has been Created By Admin.',
                    );
                     $d2 = array(
                    'screen'=>'circulars',
                    'student_id'=>$value['student_id'],
                    'section_id'=>$data['section_id'],
                    
                    );
                   
                    
                    if($parent_idd)
                    {
                        $get_isUserLogin = "SELECT islogin from indicied_indiciedu_production.mobile_device_id where user_login_id = $parent_idd";
                        $isUserLogin = $this->db->query($get_isUserLogin)->row();
                        if($isUserLogin->islogin == 1)
                        {
                             notify($d,$d2,$parent_idd);
                        }
                       
                    }
            
                }
            }
            //added by interns end
            redirect(base_url() . 'circular/circulars/');
        }
        
        if ($param1 == 'do_update') {

            $data['circular_title']=$this->input->post('circular_title');
            $data['circular']= $this->input->post('circular');
            $data['section_id']= $this->input->post('section_id');
            $data['student_id']= $this->input->post('student_id');
            $data['school_id']= $_SESSION['school_id'];
            $data['is_active']=intval($this->input->post('is_active'));
            $data['create_timestamp']  = date('Y-m-d', strtotime($this->input->post('create_timestamp')));
            $filename=$_FILES['image2']['name'];
            $folder_name = $_SESSION['folder_name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if($filename!=""){
                $data['attachment']=file_upload_fun('image2','circular','');
                $image_old = $this->input->post('image_old');
                if($image_old!=""){
                    $del_location=system_path($image_old,'circular');
                    file_delete($del_location);
                }
            }
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('circular_id', str_decode($param2));
            $this->db->update(get_school_db().'.circular', $data);
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));

            $this->load->helper('message');
            $message="Updated New Circular: ".$data['circular_title']."  ".$data['circular']. " ";
            if(isset($_POST['send_message']) && $_POST['send_message']!="") {

                if($data['student_id']!="" || $data['student_id']>0){
                    $sms_ary=get_sms_detail($data['student_id']);
                    send_sms($sms_ary['mob_num'],'Indici Edu',$message,$data['student_id']);
                }
                else{
                    sms_to_section($data['section_id'],$message);
                    $data_status['sms_status']=1;
                    $this->db->where('notice_id',str_decode($param2));
                    $this->db->update(get_school_db().'.noticeboard',$data_status);
                }

                $data_status['sms_status']=1;
                $this->db->where('circular_id',str_decode($param2));
                $this->db->update(get_school_db().'.circular',$data_status);
            }

            if(isset($_POST['send_email']) && $_POST['send_email']!="")  {
                if($data['student_id']!="" || $data['student_id']>0){
                    $sms_ary=get_sms_detail($data['student_id']);
                    $email_layout = get_email_layout($message);
                    email_send("No Reply","Indici-Edu",$sms_ary['email'],"Circular",$email_layout,$data['student_id']);
                }
                else{
                    $email_layout = get_email_layout($message);
                    email_to_section($data['section_id'],$email_layout,"Circular");
                    $data_status['sms_status']=1;
                    $this->db->where('circular_id',str_decode($param2));
                    $this->db->update(get_school_db().'.circular',$data_status);
                }
                $data_status['sms_status']=1;
                $this->db->where('circular_id',$last_is);
                $this->db->update(get_school_db().'.circular',$data_status);
            }
            redirect(base_url() . 'circular/circulars/');

        }
        
        else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where(get_school_db().'.circular', array(
                'school_id' =>$_SESSION['school_id'],
                'circular_id' => str_decode($param2)
            ))->result_array();

        }
        
        if ($param1 == 'delete') {
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('circular_id', str_decode($param2));
            $this->db->delete(get_school_db().'.circular');

            $image_old = $param3;
            $del_location=system_path($image_old,'circular');
            file_delete($del_location);
            $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
            redirect(base_url() . 'circular/circulars/');
        }
        
		
$date_query="";
$section_id = $this->input->post('section_id');
$student_id = $this->input->post('student_id');
$start_date='';
$end_date='';
$start_date=$this->input->post('starting');
$end_date=$this->input->post('ending');

$is_active=$this->input->post('is_active');
if($start_date!='')
{
	$start_date_arr=explode("/",$start_date);
	$start_date=$start_date_arr[2].'-'.$start_date_arr[1].'-'.$start_date_arr[0];
}
if($end_date!='')
{
	$end_date_arr=explode("/",$end_date);
	$end_date=$end_date_arr[2].'-'.$end_date_arr[1].'-'.$end_date_arr[0];
}
$per_page = 10;
$apply_filter = $this->input->post('apply_filter', TRUE);
$std_search = $this->input->post('std_search', TRUE);
$std_search = trim(str_replace(array("'", "\""), "", $std_search));

if (!isset($start_date) || $start_date == "") {
            $start_date = $this->uri->segment(3);
}

if (!isset($start_date) || $start_date == "") {
            $start_date = 0;
} 

if (!isset($end_date) || $end_date == "") {
            $end_date = $this->uri->segment(4);
}

if (!isset($end_date) || $end_date == "") {
            $end_date = 0;
}
        
if (!isset($section_id) || $section_id == "") {
            $section_id = $this->uri->segment(5);
}

if (!isset($section_id) || $section_id == "") {
            $section_id = 0;
} 

if (!isset($student_id) || $student_id == "") {
            $student_id = $this->uri->segment(6);
}

if (!isset($student_id) || $student_id == "") {
            $student_id = 0;
}

if (!isset($is_active) || $is_active == "") {
            $is_active = $this->uri->segment(7);
}

if (!isset($is_active) || $is_active == "") {
            $is_active ='none';
} 

$page_num = $this->uri->segment(8);

if (!isset($page_num) || $page_num == "") {
            $page_num = 0;
            $start_limit = 0;
} 
else {
            $start_limit = ($page_num - 1) * $per_page;
}
        
if(($start_date!='') && ($start_date > 0))
{
	$date_query=" AND create_timestamp >= '".$start_date."'";
	$page_data['filter'] = true;
}
if(($end_date!='') && ($end_date>0))
{
	$date_query=" AND create_timestamp <= '".$end_date."'";
	$page_data['filter'] = true;
}
if(($start_date!='') && ($start_date > 0) && ($end_date!='') && ($end_date > 0))
{
	$date_query=" AND create_timestamp >= '".$start_date."' AND create_timestamp <= '".$end_date."' ";
	$page_data['filter'] = true;
}

$section_query="";
$student_query="";

	
if(isset($section_id) && ($section_id>0))
{
	$section_query = " AND cl.section_id=$section_id";
	$page_data['filter'] = true;
}	


if(isset($student_id) && ($student_id>0))
{
	$student_query=" AND st.student_id=$student_id";
	$page_data['filter'] = true;
}

if(isset($is_active) && ($is_active >= 0) && ($is_active!="") && ($is_active!=='none'))
{
	$is_active_arr=" AND cl.is_active=$is_active";
	$page_data['filter'] = true;
}

if(isset($is_active) && ($is_active == "all") && ($is_active!="") && ($is_active!=='none'))
{
	$is_active_arr=" AND (cl.is_active=0 OR cl.is_active=1)";
	$page_data['filter'] = true;
}

$std_query = "";
if (isset($std_search) && !empty($std_search))
{
	$std_query = " AND (
                            cl.circular_title LIKE '%" . $std_search . "%' OR 
                            cl.circular LIKE '%" . $std_search . "%' 
                        )";
$page_data['filter'] = true;
}

        $q="SELECT cl.circular_id as circular_id,cl.circular_title as circular_title,cl.circular as circular,cl.section_id as section_id,cl.student_id as student_id,
            cl.create_timestamp as create_timestamp,cl.attachment as attachment,cl.is_active,st.name as student_name,d.title as dept_name,sec.title as section_name,
            c.name as class_name FROM ".get_school_db().".circular cl LEFT JOIN ".get_school_db().".student st ON cl.student_id=st.student_id
            INNER JOIN ".get_school_db().".class_section sec ON cl.section_id=sec.section_id INNER join ".get_school_db().".class c ON sec.class_id=c.class_id
            INNER join ".get_school_db().".departments d ON c.departments_id = d.departments_id WHERE cl.school_id=".$_SESSION['school_id']. $section_query . $date_query.$student_query. $is_active_arr.$std_query." order by cl.create_timestamp desc ";

        $query = $this->db->query($q)->result_array();
		$page_data['query']=$query;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['std_search']=$std_search;
        $page_data['page_name']  = 'circulars';
        $page_data['start_date']=$start_date;
        $page_data['end_date']=$end_date;
        $page_data['section_id']=$section_id;
        $page_data['student_id']=$student_id;
        $page_data['is_active']=$is_active;
        $page_data['page_title'] = get_phrase('manage_circulars');
        //$page_data['package_rights'] = package_rights();
        $this->db->where('school_id',$_SESSION['school_id']);

        $this->load->view('backend/index', $page_data);
    }
    
    function get_class()
    {
        echo  class_option_list($this->input->post('department_id'),$this->input->post('class_id'));

    }

    function get_class_section()
    {
        echo section_option_list($this->input->post('class_id'));
    }
    
    function get_year_term()
    {
        if($this->input->post('acad_year')!="")
        {
            echo $yearly_term=yearly_terms_option_list($this->input->post('acad_year'));
        }
    }
    
    function get_year_term2()
    {
       if($this->input->post('acad_year')!="")
        {
            echo $yearly_term=yearly_terms_option_list($this->input->post('acad_year'));
        }
    }
    
    function term_date_range()
    {
        if(!empty($this->input->post('date1')))
        {
            echo term_date_range($this->input->post('term_id'),$this->input->post('date1'),'');
        }
    }


}
    