<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();


//exit;
class Academic_planner extends CI_Controller
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
        if($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');
        //$_SESSION['school_id']=101;
        
    }

    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {

        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }


    public function planner_view()
    {
        $page_data['page_name']='planner_view';
        $page_data['page_title']=get_phrase('planner_view');
        $this->load->view('backend/index', $page_data);
    }


    function get_year_term()
    {
        if($this->input->post('acad_year')!="")
        {
            echo $yearly_term=yearly_terms_option_list($this->input->post('acad_year'));
        }
    }

    function get_planner()
    {
        $acad_year=$this->input->post("acad_year");
        $yearly_terms=$this->input->post("yearly_terms");

        if(isset($yearly_terms) && $yearly_terms!="")
        {
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('yearly_terms_id',$yearly_terms);
            $query = $this->db->get(get_school_db().'.yearly_terms');
        }

        else{
            $q="SELECT * FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
            $query=$this->db->query($q);

        }

        $row=$query->result_array();
        $start_date=$row[0]['start_date'];
        $end_date=$row[0]['end_date'];
        $months = array();

        while (strtotime($start_date) <= strtotime($end_date))
        {
            $months[] = array('year' => date('Y', strtotime($start_date)), 'month' => date('m', strtotime($start_date)), );
            $start_date = date('d M Y', strtotime($start_date.
                '+ 1 month'));
        }


        $arrlength = count($months);
        $counter=1;
        $str = array();
        ?>

        <?php
        foreach($months as $key=>$value)
        {

            $month_year=date("F - Y", mktime(0, 0, 0,  $value['month'], 1, $value['year']));
            if($key == 0)
            {
                $month_first=$value['month'].",".$value['year'];
            }
            ?>
            <button type="button" class="btn btn-primary" id="btn_new<?php echo $counter;?>" value="<?php echo $month_first;?>" onclick="planner('<?php echo $value['month'];?>','<?php echo $value['year'];?>')"><?php echo $month_year;?></button>
            <?php
            $counter++;
        }?>

        <?php
    }


    function month_create()
    {

        $this->db->where('school_id',$_SESSION['school_id']);
        $query = $this->db->get(get_school_db().'.academic_planner');

        /*$yearly_terms=$this->input->post("yearly_terms");
        if(isset($yearly_terms) && $yearly_terms!="")
        {
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('yearly_terms_id',$yearly_terms);
        $query = $this->db->get(get_school_db().'.yearly_terms');
        }*/

        $start_date=date_slash($this->input->post('start_date'));
        $end_date=date_slash($this->input->post('end_date'));
        /*
        if($start_date!='')
        {
        $start_date_arr=explode("/",$start_date);
        $start_date=$start_date_arr[2].'-'.$start_date_arr[0].'-'.$start_date_arr[1];
        }
        if($end_date!='')
        {
            $end_date_arr=explode("/",$end_date);
            $end_date=$end_date_arr[2].'-'.$end_date_arr[0].'-'.$end_date_arr[1];
        }
        */
        /*$row=$query->result_array();
        $start_date=$row[0]['start_date'];
        $end_date=$row[0]['end_date'];*/


        $months = array();
        while (strtotime($start_date) <= strtotime($end_date))
        {
            $months[] = array('year' => date('Y', strtotime($start_date)), 'month' => date('m', strtotime($start_date)), );
            $start_date = date('d M Y', strtotime($start_date.
                '+ 1 month'));
        }

        $arrlength = count($months);
        $counter=1;
        $str = array();
        $m=array();
        $y=array();
        $current_year=date('Y');
        $current_month=date('m');

        $month_current="";
        foreach($months as $key=>$value)
        {
            if ($current_year==$value['year'] && $current_month==$value['month'])
            {
                $month_current=date("F-Y", mktime(0, 0, 0,  $value['month'], 1, $value['year']));
            }

            $m[]=date("F-Y", mktime(0, 0, 0,  $value['month'], 1, $value['year']));
            $counter++;
        }
        if($month_current == "")
        {
            if(isset($m[0]) && $m[0]!="")
            {
                $month_current=$m[0];
            }
        }
        $list_array['month']=$m;
        $list_array['month_current']=$month_current;
        echo json_encode($list_array);
    }

    function planner_generator()
    {
        $this->load->view('backend/admin/ajax/planner.php');
    }

    function acad_planner()
    {
        $page_data['page_name']='academic_plan';
        $page_data['page_title']=get_phrase('academic_planner');
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

    function get_section_subject()
    {
        echo subject_option_list($this->input->post('section_id'));
    }

    function save_planner(){

        $data['title']=$this->input->post('title');
        $data['detail']=$this->input->post('detail');
        $data['objective']=$this->input->post('objective');
        $data['assesment']=$this->input->post('assesment');
        $data['requirements']=$this->input->post('requirements');
        $data['required_time']=$this->input->post('required_time');
        $data['subject_id']=$this->input->post('subject_id_add');

        $start_date =$this->input->post('date');
        $start_arry=explode('/',$start_date);
        $data['start'] = $start_date;
        $data['school_id'] = $_SESSION['school_id'];
        
        $file_name='userfile';
        $folder_name='academic_planner';

        if($_FILES['userfile']['name']!="")
        {
            $file=file_upload_fun($file_name,$folder_name,'a_plan');
            $data['attachment']=$file;
        }
		
		$data['is_active']=$this->input->post('is_active');
		

        $_POST['student_id']=	$data['teacher'];
        $_POST['subject_id']=	$data['teacher'];

        $this->db->insert(get_school_db().'.academic_planner',$data);
        redirect(base_url().'/academic_planner/acad_planner');

    }

    function edit_planner()
    {
      
        $id=$this->input->post('planner_id');
        $subject_id=$this->input->post('subject_id_add');
         print_r($subject_id);
        $title=$this->input->post('title');
        $detail=$this->input->post('detail');
        $objective=$this->input->post('objective');
        $assesment=$this->input->post('assesment');
        $start_date = $this->input->post('start');
       
        
        $data['planner_id']=$this->input->post('planner_id');
        $data['title']=$this->input->post('title');
        $data['subject_id']=$this->input->post('subject_id_add');
        $data['detail']=$this->input->post('detail');
        $data['objective']=$this->input->post('objective');
        $data['assesment']=$this->input->post('assesment');
        $data['requirements']=$this->input->post('requirements');
        $data['required_time']=$this->input->post('required_time');
        $id=$this->input->post('planner_id');
        $image_file=$this->input->post('attachment');
        $start_arry=explode('/',$start_date);
        $data['start']=$start_date;
		$data['is_active']=$this->input->post('is_active');

        $file_name='userfile';
        $folder_name='academic_planner';
        if($file_name!=""){

            if($image_file!=""){
                $system_path=system_path($image_file,$folder_name);
                $del_location=$system_path;
                file_delete($del_location);
            }

            $file=file_upload_fun($file_name,$folder_name);
            $data['attachment']=$file;

        }
        print_r($data);
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->where('planner_id',$id);
        $this->db->update(get_school_db().'.academic_planner',$data);

        redirect(base_url() . 'academic_planner/acad_planner/');
    }



    function academic_plan_generator()
    {
        $this->load->view('backend/admin/ajax/get_acad_planner.php');
    }

    function delete_planner($param1 = '', $param2 = '', $param3 = '')
    {
        $qur_1=$this->db->query("select a_p_d_id from ".get_school_db().".academic_planner_diary 
									where planner_id=$param1 and school_id=".$_SESSION['school_id'])->result_array();
        if(count($qur_1)>0)
        {
            $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
            redirect(base_url() . 'academic_planner/acad_planner/');
            exit();
        }
        $folder_name='academic_planner';
        if($param2!="")
        {
            $system_path=system_path($param2,$folder_name);
            $del_location=$system_path;
            file_delete($del_location);
        }

        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->where('planner_id',$param1);
        $this->db->delete(get_school_db().'.academic_planner');
        redirect(base_url() . 'academic_planner/acad_planner/');

    }

    function get_year_term_add()
    {
        if($this->input->post('acad_year')!="")
        {
            echo $yearly_term=yearly_terms_option_list($this->input->post('acad_year'),'',1);
        }
    }

    function get_year_term2()
    {
        if($this->input->post('acad_year')!="")
        {
            echo $yearly_term=yearly_terms_option_list($this->input->post('acad_year'),'',1);
        }
    }

    function term_date_range()
    {
        if(!empty($this->input->post('date1')))
        {
            echo term_date_range($this->input->post('term_id'),$this->input->post('date1'),'');
        }
    }

    function accordion_generator()
    {
        $this->load->view('backend/admin/ajax/planner_accordion.php');
    }
    
    
    function save_teacher_planner_activity()
    {
        
        $list_array=array();
        
        $teacher_id = $_SESSION['user_id'];
        $planner_id = $this->input->post('planner_id');
        $school_id  = $_SESSION['school_id'];
        
        $query = "select id from ".get_school_db().".teacher_planner_activity where teacher_id = $teacher_id 
                  and planner_id = $planner_id and school_id = $school_id limit 1";
        $result = $this->db->query($query)->result_array();
        
        if(count($result) > 0)
        {
            $data['status'] =  $this->input->post('status');
            $this->db->where('planner_id',$planner_id);
	        $this->db->where('teacher_id',$teacher_id);
	        $this->db->where('school_id',$school_id);
            $this->db->update(get_school_db().".teacher_planner_activity" , $data); 
            $list_array['message'] = 'Planner Activity Updated Successfully';
        }
        else
        {
            $data['planner_id']     =  $planner_id;
    	    $data['teacher_id']     =  $teacher_id;
    	    $data['status']         =  $this->input->post('status');
    	    $data['inserted_at']    =  date('Y-m-d H:i:s');
    	    $data['school_id']      =  $school_id;
    	    $this->db->insert(get_school_db().".teacher_planner_activity" , $data);
            $list_array['message']  = 'Planner Activity Saved Successfully';
        }
        
        echo json_encode($list_array);
    }
    
    function request_planner_change()
    {
        
        $list_array=array();
        
        $teacher_id             =  $_SESSION['user_id'];
        $planner_id             =  $this->input->post('acad_planner_id');
        $school_id              =  $_SESSION['school_id'];
        
        $data['planner_id']     =  $planner_id;
    	$data['teacher_id']     =  $teacher_id;
    	$data['status']         =  $this->input->post('status');
    	$data['inserted_at']    =  date('Y-m-d H:i:s');
    	$data['school_id']      =  $school_id;
    	$data['reason']         =  $this->input->post('reason');
    	$data['insertion_type'] =  2; // means change was later after the date
    	$this->db->insert(get_school_db().".teacher_planner_activity" , $data);
        $list_array['message']  = 'Planner Activity Saved Successfully';

        $this->session->set_flashdata('club_updated',get_phrase('planner_activity_was_marked_successfully'));
        redirect(base_url() . 'teacher/academic_planner/');
        
	    
    }
    
    function check_term_prev_date()
    {
        $output = array();
        $school_id = $_SESSION['school_id'];
        $yeraId =  $this->input->post('AcadYearID');
        $startDate =  $this->input->post('startDate');
        
        $this->db->where('academic_year_id',$yeraId);
        $this->db->where('school_id',$school_id);
        $this->db->order_by('yearly_terms_id','DESC');
        $return_data = $this->db->get(get_school_db().'.yearly_terms')->row();
        $end_date =  $return_data->end_date; // 2021-10-04 - 2021-10-01
        $output['ed'] = $end_date;
        $output['sd'] = $startDate;
        if($end_date >= $startDate)
        {
            $output['err'] = get_phrase('start_date_should_be_greater_then_last_term_end-Date');
        }else{
            $output['err'] = '';
        }
        
        echo json_encode($output);
    }
}
