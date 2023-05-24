<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Time_table extends CI_Controller{

    function __construct()
    {
        parent::__construct();
        $this->load->database();

        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        if($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');

    }
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {

        if($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');
        if($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }

    function class_routine_settings($param1 = '', $param2 = '', $param3 = '')
    {

        if($_SESSION['user_login'] != 1)
            redirect(base_url());
            
        if($param1 == 'create')
        {
            $data['no_of_periods']      = $this->input->post('no_of_periods');
            $data['period_duration']    = $this->input->post('period_duration');
            $data['start_time']         = $this->input->post('start_time');
            $data['end_time']         = $this->input->post('end_time');
            $data['assembly_duration']  = $this->input->post('assembly_duration');
            
            if($this->input->post('break_duration') != ''){
                $data['break_duration']     = $this->input->post('break_duration');
            }
            if($this->input->post('break_after_period') != ''){
                $data['break_after_period'] = $this->input->post('break_after_period');
            }
            if($this->input->post('break_duration_after_every_period') != ''){
                $data['break_duration_after_every_period'] = $this->input->post('break_duration_after_every_period');
            }
            
            
            $data['school_id']          = $_SESSION['school_id'];
            $data['section_id']         = intval($this->input->post('section_id1'));
            $data['is_active']          = $this->input->post('is_active');
            $start_date                 = $this->input->post('start_date');
            $data['start_date']         = $start_date;

            $end_date                   = $this->input->post('end_date');
            $data['end_date']           = $end_date;

            if ($data['is_active'] == 1)
            {
                $data_update['is_active'] = 0;
                $this->db->where('section_id', $data['section_id']);
                $this->db->where('school_id', $_SESSION['school_id']);
                $this->db->update(get_school_db().'.class_routine_settings', $data_update);
            }

            $this->db->insert(get_school_db().'.class_routine_settings', $data);
            $this->session->set_flashdata('club_updated', get_phrase('timetable_saved_successfully'));
            exit;
        }
        
        if($param1 == 'edit')
        {
            $data['no_of_periods']     = $this->input->post('no_of_periods');
            $period_duration           = $this->input->post('period_duration');
            $assembly_duration         = $this->input->post('assembly_duration');

            $data['start_time']        = $this->input->post('start_time');
            $data['end_time']        = $this->input->post('end_time');
            $data['assembly_duration'] = $this->input->post('assembly_duration');
            
            /*
            if($this->input->post('break_duration') != ''){
                $data['break_duration']     = $this->input->post('break_duration');
            }
            if($this->input->post('break_after_period') != ''){
                $data['break_after_period'] = $this->input->post('break_after_period');
            }
            if($this->input->post('break_duration_after_every_period') != ''){
                $data['break_duration_after_every_period'] = $this->input->post('break_duration_after_every_period');
            }
            */
            
            
            $data['break_duration']                    =   ($this->input->post('break_duration') != '') ? $this->input->post('break_duration'): 0;
            $data['break_after_period']                =   ($this->input->post('break_after_period') != '') ? $this->input->post('break_after_period'): 0;
            $data['break_duration_after_every_period'] =   ($this->input->post('break_duration_after_every_period') != '') ? $this->input->post('break_duration_after_every_period'): 0;
            
            
           

            $data['school_id']    = $_SESSION['school_id'];
            $data['section_id']   = intval($this->input->post('section_id1'));
            $data['is_active']    = $this->input->post('is_active');
            $start_date           = $this->input->post('start_date');
            $data['start_date']   = $start_date;

            $end_date             = $this->input->post('end_date');
            $data['end_date']     = $end_date;
            $c_rout_sett_id       = $this->input->post('c_rout_sett_id');

            if($c_rout_sett_id!="")
            {
                if ($data['is_active'] == 1)
                {
                    $data_update['is_active'] = 0;
                    $this->db->where('section_id', $data['section_id']);
                    $this->db->where('school_id', $_SESSION['school_id']);
                    $this->db->update(get_school_db().'.class_routine_settings', $data_update);
                }

                $p="SELECT cr.duration,crs.period_duration,cr.class_routine_id FROM ".get_school_db().".class_routine cr INNER JOIN ".get_school_db().".class_routine_settings crs ON cr.c_rout_sett_id=crs.c_rout_sett_id
				 WHERE crs.c_rout_sett_id=$c_rout_sett_id AND crs.school_id=".$_SESSION['school_id']." And cr.duration=crs.period_duration";
                $query=$this->db->query($p)->result_array();
                foreach($query as $row)
                {
                    $data2['duration']=$period_duration;
                    $class_routine_id=$row['class_routine_id'];
                    $this->db->where('class_routine_id',$class_routine_id);
                    $this->db->where('school_id',$_SESSION['school_id']);
                    $this->db->update(get_school_db().'.class_routine', $data2);
                }
                $data['period_duration']=$period_duration;

                $this->db->where('c_rout_sett_id', $c_rout_sett_id);
                $this->db->where('school_id',$_SESSION['school_id']);
                $this->db->update(get_school_db().'.class_routine_settings', $data);

                $start_time=$this->input->post('start_time');
                $end_time=$this->input->post('end_time');

                $school_start_time=strtotime($start_time) + strtotime(minutes_to_hh_mm($assembly_duration)) - strtotime('00:00');
                $school_end_time=strtotime($end_time) + strtotime(minutes_to_hh_mm($assembly_duration)) - strtotime('00:00');
                $school_start_time = date('H:i', $school_start_time);
                $school_end_time = date('H:i', $school_end_time);

                $day=array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
                foreach($day as $day1)
                {
                    $this->class_routine_timings($c_rout_sett_id,$period_no=1,$day1,$school_start_time,$school_end_time);
                }
                exit;

            }

        }
        
        if($param1 == 'delete')
        {
            $depart_id=$this->uri->segment(5);
            $sect_id=$this->uri->segment(6);
            $getRoutine="select c_rout_sett_id from ".get_school_db().".class_routine where c_rout_sett_id=$param2";
            $rows=$this->db->query($getRoutine)->num_rows();
            if($rows==0)
            {
                $this->db->where('c_rout_sett_id', $param2);
                $this->db->delete(get_school_db().'.class_routine_settings');
                $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
            }
            else
            {
                $this->session->set_flashdata('club_updated', get_phrase('deletion_failed_settings_already_in_use'));
            }
            redirect(base_url() . 'time_table/class_routine/'.$depart_id.'/'.$sect_id);
            exit;
        }

        $page_data['page_name']  = 'modal_class_routine_edit';
        $page_data['page_title'] = get_phrase('time_table_settings');
        $this->load->view('backend/index', $page_data);
    }

    function time_table_list()
    {
        if($_SESSION['user_login'] != 1)
            redirect(base_url());

        $page_data['page_name']  = 'time_table_list';
        $page_data['page_title'] = get_phrase('time_table_list');
        $this->load->view('backend/index', $page_data);
    }

    function load_table()
    {
        $page_data['c_rout_sett_id'] = intval($this->input->post('c_rout_sett_id'));
        $page_data['section_id']     = intval($this->input->post('section_id'));
        $page_data['page_name']      = 'time_table_load';
        $page_data['page_title']     = get_phrase('time_table_list');
        echo $this->load->view("backend/admin/ajax/time_table_load",$page_data, TRUE);
    }

    function get_class()
    {
        echo  class_option_list($this->input->post('department_id'),$this->input->post('class_id'));
    }

    function get_class_section()
    {
        echo section_option_list($this->input->post('class_id'),$this->input->post('section_id'));
    }

    function get_class_section_routine_settings()
    {
        $class_id=$this->input->post('class_id');
        $section_id=$this->input->post('section_id');
        $term_id=$this->input->post('term_id');

        $q="select c.* from ".get_school_db().".class_section c  where class_id='$class_id' and school_id=".$_SESSION['school_id']." ";
        $query=$this->db->query($q);

        $str= '<option value="">Select Section</option>';
        if($query->num_rows>0)
        {
            foreach($query->result() as $rows){
                $opt_selected='';
                if($rows->section_id==$selected)
                {
                    $opt_selected="selected";
                }

                $str.='<option value="'.$rows->section_id.'" '.$opt_selected.'>'.$rows->title.'</option>';
            }
        }
        echo $str;
    }

    function check_section_routine_settings()
    {
        $section_id=$this->input->post('section_id');
        $term_id=$this->input->post('term_id');
        $q="select section_id from ".get_school_db().".class_routine_settings   where section_id='$section_id' and yearly_terms_id='$term_id' and school_id=".$_SESSION['school_id']." ";
        $query=$this->db->query($q)->num_rows();
        if($query>0)
        {
            echo "exists";
            exit;
        }
    }

    function get_section_subject()
    {
        echo subject_option_list($this->input->post('section_id'));
    }

    function get_term(){
        echo yearly_terms_option_list($this->input->post('academic_year'),'');
    }

    function get_table(){

        $dept_id=$this->input->post('department_id');
        $class_id=$this->input->post('class_id');
        $section_id=$this->input->post('section_id');
        $term_id=$this->input->post('term_id');
        $academic_id=$this->input->post('academic_id');
        $year='';
        if($academic_id!='' && $term_id!='select term')
        {
            $year=" and a.academic_year_id=".$academic_id." and y.yearly_terms_id=".$term_id."";
        }
        if($academic_id!='' && $term_id=='')
        {
            $year=" and a.academic_year_id=".$academic_id."";
        }

        $data = array();

        $q="SELECT d.title as department, cls.name as class,sec.title as section,c. * ,y.yearly_terms_id,y.academic_year_id, y.title as term , y.start_date as term_start_date , y.end_date as term_end_date, a.title as academic_year , a.start_date as academic_year_start_date , a.end_date as academic_year_end_date, cls.class_id, cls.departments_id, sec.section_id FROM ".get_school_db().".class_routine_settings c INNER JOIN ".get_school_db().".class_section sec ON c.section_id = sec.section_id INNER JOIN ".get_school_db().".class cls ON sec.class_id = cls.class_id INNER JOIN ".get_school_db().".departments d ON cls.departments_id = d.departments_id INNER JOIN ".get_school_db().".yearly_terms y ON c.yearly_terms_id = y.yearly_terms_id INNER JOIN ".get_school_db().".acadmic_year a ON y.academic_year_id = a.academic_year_id WHERE c.school_id =".$_SESSION['school_id']." ".$year."";

        if(!empty($dept_id) && empty($class_id)){

            $q="SELECT d.title as department, cls.name as class,sec.title as section,c. * ,y.yearly_terms_id,y.academic_year_id, y.title as term , y.start_date as term_start_date , y.end_date as term_end_date, a.title as academic_year , a.start_date as academic_year_start_date , a.end_date as academic_year_end_date, cls.class_id, cls.departments_id, sec.section_id FROM ".get_school_db().".class_routine_settings c INNER JOIN ".get_school_db().".class_section sec ON c.section_id = sec.section_id INNER JOIN ".get_school_db().".class cls ON sec.class_id = cls.class_id INNER JOIN ".get_school_db().".departments d ON cls.departments_id = d.departments_id INNER JOIN ".get_school_db().".yearly_terms y ON c.yearly_terms_id = y.yearly_terms_id INNER JOIN ".get_school_db().".acadmic_year a ON y.academic_year_id = a.academic_year_id WHERE c.school_id =".$_SESSION['school_id']." AND d.departments_id=$dept_id ".$year."";

        }
        if(!empty($dept_id) && (!empty($class_id)) && (empty($section_id))){

            $q="SELECT d.title as department, cls.name as class,sec.title as section,c. * ,y.yearly_terms_id,y.academic_year_id, y.title as term , y.start_date as term_start_date , y.end_date as term_end_date, a.title as academic_year , a.start_date as academic_year_start_date , a.end_date as academic_year_end_date, cls.class_id, cls.departments_id, sec.section_id FROM ".get_school_db().".class_routine_settings c INNER JOIN ".get_school_db().".class_section sec ON c.section_id = sec.section_id INNER JOIN ".get_school_db().".class cls ON sec.class_id = cls.class_id INNER JOIN ".get_school_db().".departments d ON cls.departments_id = d.departments_id INNER JOIN ".get_school_db().".yearly_terms y ON c.yearly_terms_id = y.yearly_terms_id INNER JOIN ".get_school_db().".acadmic_year a ON y.academic_year_id = a.academic_year_id WHERE c.school_id =".$_SESSION['school_id']." AND d.departments_id=$dept_id AND cls.class_id=$class_id ".$year."";
        }
        if(!empty($dept_id) && (!empty($class_id) && ($section_id!='Select Section' && !empty($section_id))))
        {
            $q="SELECT d.title as department, cls.name as class,sec.title as section,c. * ,y.yearly_terms_id,y.academic_year_id, y.title as term , y.start_date as term_start_date , y.end_date as term_end_date, a.title as academic_year , a.start_date as academic_year_start_date , a.end_date as academic_year_end_date, cls.class_id, cls.departments_id, sec.section_id FROM ".get_school_db().".class_routine_settings c INNER JOIN ".get_school_db().".class_section sec ON c.section_id = sec.section_id INNER JOIN ".get_school_db().".class cls ON sec.class_id = cls.class_id INNER JOIN ".get_school_db().".departments d ON cls.departments_id = d.departments_id INNER JOIN ".get_school_db().".yearly_terms y ON c.yearly_terms_id = y.yearly_terms_id INNER JOIN ".get_school_db().".acadmic_year a ON y.academic_year_id = a.academic_year_id WHERE c.school_id =".$_SESSION['school_id']." AND d.departments_id=$dept_id AND cls.class_id=$class_id AND c.section_id=$section_id ".$year."";
        }

        if(empty($dept_id) && ($class_id=='') && ($section_id=='Select Section'))
        {
            $q="SELECT d.title as department, cls.name as class,sec.title as section,c. * ,y.yearly_terms_id,y.academic_year_id, y.title as term , y.start_date as term_start_date , y.end_date as term_end_date, a.title as academic_year , a.start_date as academic_year_start_date , a.end_date as academic_year_end_date, cls.class_id, cls.departments_id, sec.section_id FROM ".get_school_db().".class_routine_settings c INNER JOIN ".get_school_db().".class_section sec ON c.section_id = sec.section_id INNER JOIN ".get_school_db().".class cls ON sec.class_id = cls.class_id INNER JOIN ".get_school_db().".departments d ON cls.departments_id = d.departments_id INNER JOIN ".get_school_db().".yearly_terms y ON c.yearly_terms_id = y.yearly_terms_id INNER JOIN ".get_school_db().".acadmic_year a ON y.academic_year_id = a.academic_year_id WHERE c.school_id =".$_SESSION['school_id']." ".$year."";

        }

        $data['timetable'] = $this->db->query($q)->result_array();
        $this->load->view("backend/admin/ajax/time_table",$data);

    }

    /**********MANAGING CLASS ROUTINE******************/
    function class_routine($param1 = '', $param2 = '', $param3 = '')
    {

        if ($_SESSION['user_login'] != 1)
            redirect(base_url());
        if ($param1 == 'create')
        {
            $period_start_time     = $this->input->post('period_start_time');
            $period_duration       = $this->input->post('period_duration');
            $day                   = $this->input->post('day');
            $period_no             = $this->input->post('period_no');
            $c_rout_sett_id        = $this->input->post('c_rout_sett_id');
            $data['c_rout_sett_id']= $c_rout_sett_id;
            $data['subject_id']    = $this->input->post('subject_id');
            $data['day']           = $day;
            $data['period_no']     = $period_no;
            $data['school_id']     = $_SESSION['school_id'];
            $component_id          = $this->input->post('component_id');
            $arr = array();
            if(sizeof($component_id)>0)
            {
                foreach($component_id as $k => $comp)
                {
                    $arr[] = $comp['value'];
                }
            };
            $data['subject_components']=implode(',',$arr);
            $data['duration']=$period_duration;

            if($this->db->insert(get_school_db().'.class_routine', $data))
            {

                $insert_id = $this->db->insert_id();
                $this->class_routine_timings($c_rout_sett_id,$period_no,$day,$period_start_time);
                $subject_teacher_id = $this->input->post('subject_teacher_id');
                if(sizeof($subject_teacher_id)>0)
                {
                    $data2['school_id']=$_SESSION['school_id'];
                    $data2['class_routine_id']=$insert_id;

                    foreach($subject_teacher_id as $row)
                    {
                        $data2['subject_teacher_id']=$row['value'];
                        $this->db->insert(get_school_db().'.time_table_subject_teacher', $data2);
                        $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
                        
                        $device_id  =   get_user_device_id(3 , $row['value'] , $_SESSION['school_id']);
                        $title      =   "New Time Table Subject Assigned";
                        $message    =   "A New Subject Has been Assigned In The Time Table To You By School Admin.";
                        $link       =    base_url()."teacher/my_class_routine";
                        sendNotificationByUserId($device_id, $title, $message, $link , $row['value'] , 3);
                        
                    }

                }

                echo "added";exit;
            }

        }

        if ($param1 == 'do_update') {
            $data['class_id']   = $this->input->post('class_id');
            $data['subject_id'] = $this->input->post('subject_id');

            $data['period_no']   = $this->input->post('period_no');
            $data['day']        = $this->input->post('day');

            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('class_routine_id', $param2);
            $this->db->update(get_school_db().'.class_routine', $data);

            $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'admin/class_routine/');
        }

        else if ($param1 == 'edit') {

            $page_data['edit_data'] = $this->db->get_where(get_school_db().'.class_routine', array(
                'class_routine_id' => $param2,
                'school_id' =>$_SESSION['school_id']
            ))->result_array();
        }
        
        if ($param1 == 'delete')
        {
            $p="SELECT day,period_no,period_start_time,c_rout_sett_id from ".get_school_db().".class_routine 
        	WHERE school_id=".$_SESSION['school_id']." AND class_routine_id=$param2";
            $result=$this->db->query($p)->result_array();
            $day=$result[0]['day'];
            $c_rout_sett_id=$result[0]['c_rout_sett_id'];
            $period_no=$result[0]['period_no'];
            $period_start_time=$result[0]['period_start_time'];

            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('class_routine_id', $param2);
            if($this->db->delete(get_school_db().'.class_routine'))
            {
                $this->db->where('school_id',$_SESSION['school_id']);
                $this->db->where('class_routine_id', $param2);
                $this->db->delete(get_school_db().'.time_table_subject_teacher');
                $this->class_routine_timings($c_rout_sett_id,$period_no,$day,$period_start_time);
                $this->session->set_flashdata('club_updated', get_phrase('record Deleted'));
                echo "deleted";exit;
            }
        }

        $depart_id=$this->uri->segment('3');
        $sect_id=$this->uri->segment('4');
        $c_rout_sett_id=$this->uri->segment('5');

        if(isset($depart_id) && ($depart_id>0) && isset($sect_id) && ($sect_id>0))
        {

            $page_data['department_id']=$depart_id;
            $page_data['sect_id']=$sect_id;
            $page_data['c_rout_set_id']=$c_rout_sett_id;

        }

        $page_data['page_name']  = 'class_routine';
        $page_data['page_title'] = get_phrase('manage_time_table');
        $this->load->view('backend/index', $page_data);
    }
    /**********Edit CLASS ROUTINE******************/
    function edit_class_routine($param1 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url());

        $data['class_routine_id']   = $param1;
        $q2="select * from ".get_school_db().".class_routine_settings where school_id=".$_SESSION['school_id']."";
        $periods=$this->db->query($q2)->result_array();

        $page_data['periods'] = $periods;
        $page_data['data']	=	$data;
        $page_data['page_name']  = 'edit_class_routine';
        $page_data['page_title'] = get_phrase('manage_class_routine');
        $this->load->view('backend/index', $page_data);
    }

    function get_student(){
        $class_id=$this->input->post('class_id');
        $q="select subject_id,name from ".get_school_db().".subject where school_id=".$_SESSION['school_id']."
		AND class_id='$class_id'";
        $query=$this->db->query($q);

        if($query->num_rows>0){
            echo '<option value="">'.get_phrase('select_subject').'</option>';
            foreach($query->result() as $rows){
                echo '<option value="'.$rows->subject_id.'">'.$rows->name.'</option>';
            }

        }
        else{

        }
    }

    function get_class_routine()
    {
        $section=$this->input->post('section_id');
        $department_id=$this->input->post('departments_id');
        $c_rout_id=$this->input->post('c_rout_id');

        if(isset($department_id) && isset($section))
        {
            $data['dept_id']=$department_id;
            $data['section']=$section;
            $data['c_rout_id']=$this->input->post('c_rout_set_id');
        }
        $section_id_filter=$this->input->post('section_id_filter');
        $data['section_id_filter']=$section_id_filter;

        $this->load->view("backend/admin/ajax/class_routine",$data);
    }
    function get_number_period()
    {
        exit('exit');
        $term_id=$this->input->post('yearly_term');
        $section_id=$this->input->post('section_id');
        $day=$this->input->post('day');
        $period_no=$this->input->post('period');

        $selected='';
        if(!empty($term_id) && !empty($section_id) && !empty($day) ){


            $q2="select c_rout_sett_id,no_of_periods from ".get_school_db().".class_routine_settings where school_id=".$_SESSION['school_id']."  AND yearly_terms_id=".$term_id." AND section_id=".$section_id."";

            $query2=$this->db->query($q2)->result_array();

            $q="select period_no from ".get_school_db().".class_routine where c_rout_sett_id=".$query2[0]['c_rout_sett_id']." AND school_id=".$_SESSION['school_id']." and day='".$day."'";
            $periodArr=$this->db->query($q)->result_array();

            $no_of_period_existing=array();
            if(sizeof($periodArr)>0)
            {
                foreach($periodArr as $row){
                    $no_of_period_existing[]=$row['period_no'];
                }

            }
            if(sizeof($no_of_period_existing)==$query2[0]['no_of_periods'])
            {
                echo "equal";
            }
            else{
                $str='<select id="period_no_select" name="period_no_select" class="form-control" data-validate="required" data-message-required="Value Required">';
                $str.='<option>'.get_phrase('select_number').'</option>';
                for($i=1;$i<=$query2[0]['no_of_periods'];$i++)
                { $selected='';
                    if(!in_array($i,$no_of_period_existing))
                    {
                        if($i==$period_no)
                        {
                            $selected="selected='selected'";
                        }

                        $str.='<option '.$selected.' value='.$i.'>'.$i.'</option>';
                    }
                }
                $str.='</select>';
                $str.="<input type='hidden' name='c_rout_sett_id' id='c_rout_sett_id' value='".$query2[0]['c_rout_sett_id']."'/>";
                echo $str;exit;
            }
        }


    }
    function get_class_routine_section(){

        $term_id=$this->input->post('term_id');
        $class_id=$this->input->post('class_id');
        $q2="select distinct sec.title,sec.section_id,cr.yearly_terms_id from ".get_school_db

            ().".class_routine_settings cr inner join ".get_school_db().".class_section sec on cr.section_id=sec.section_id 

inner join ".get_school_db().".class c on c.class_id=sec.class_id where  cr.school_id=".$_SESSION['school_id']." 

AND  cr.yearly_terms_id=$term_id and c.class_id=$class_id";
        $query2=$this->db->query($q2)->result_array();

        $str='<select name="term_id" class="form-control" data-validate="required" data-message-

required="Value Required">';
        $str.='<option>'.get_phrase('select_term').'</option>';
        foreach($query2 as $row)
        {
            $str.='<option value='.$row['section_id'].'>'.$row['title'].'</option>';
        }
        $str.='</select>';

        echo $str;
    }


    function get_teacher_checkbox()
    {
        $subject_id=$this->input->post('subject_id');

        $teachers=get_teachers_checkbox($subject_id);

        $i = 1;
        $str="<table><tr>";
        foreach($teachers as $row){

            $str.='<td><input data-validate="required" required data-message-required="*" class="teacher" 

type="checkbox" name="teachers" id="teacher"'.$row['teacher_id'].'" value="'.$row['subject_teacher_id'].'">'.$row

                ['teacher'].'</input> </td>';
            if ($i % 3== 0) $str.="</tr><tr>";
            $i++;
        }
        $str.="</tr></table>";

        echo $str;exit;

    }
    function get_available_teachers_checkbox($subject_id = 0){
        $subject_id=$this->input->post('subject_id');
        $section_id=$this->input->post('section_id');
        $day=$this->input->post('day');
        $period_num=$this->input->post('period_num');

        $q2= "SELECT s.name as teacher,s.staff_id as teacher_id,s.periods_per_week,s.periods_per_day,st.subject_teacher_id 
			FROM ".get_school_db().".staff s	INNER JOIN ".get_school_db().".subject_teacher st ON st.teacher_id=s.staff_id INNER JOIN ".get_school_db().".designation d  ON (s.designation_id = d.designation_id AND d.is_teacher=1)	WHERE st.subject_id=$subject_id and st.school_id=".$_SESSION['school_id']."";

        $teachers=$this->db->query($q2)->result_array();

        $q3="select st.teacher_id from  ".get_school_db().".class_routine cr
        inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
        inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id	
        inner join ".get_school_db().".class_routine_settings crs on crs.c_rout_sett_id=cr.c_rout_sett_id
        where cr.day='".$day."' and cr.period_no=$period_num and crs.is_active = 1  AND st.school_id=".$_SESSION['school_id']."";
        $assigned=$this->db->query($q3)->result_array();
        // inner join ".get_school_db().".class_routine_settings crs on crs.c_rout_sett_id=cr.c_rout_sett_id added by hammad
        // echo "<pre>";
        // print_r($assigned);
        // exit;
        $arrAssigned=array();
        if(sizeof($assigned)>0){
            foreach($assigned as $asg)
            {
                $arrAssigned[]=$asg['teacher_id'];
            }
        }
        $i = 1;
        $str='0';
        if(sizeof($teachers)>0){

            $str="<table  style='margin-top:10px;text-align:center;' class='table table-responsive table-striped table-bordered table-hover'><tr style='font-weight:bold;'><td></td><td></td><td colspan='2'>Periods Per Day</td><td colspan='2'>Periods Per Week</td></tr><tr style='font-weight:bold;'><td></td><td>Teacher</td><td>Assigned</td><td>Total</td><td>Assigned</td><td>Total</td></tr>";

            foreach($teachers as $row){
                $dis='';
                $assigned_var='Available';
                $mycolor="green";
                $period_week_count='';
                $period_day_count='';


                $week="SELECT count(st.teacher_id) as count FROM ".get_school_db().".time_table_subject_teacher ttst 
	inner join ".get_school_db().".class_routine cr on cr.class_routine_id=ttst.class_routine_id 
	inner join ".get_school_db().".class_routine_settings cs on cr.c_rout_sett_id=cs.c_rout_sett_id 
	inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
	where st.teacher_id=".$row['teacher_id']." and cs.is_active = 1 and st.school_id=".$_SESSION['school_id']." ";
 // and cs.is_active = 1 added by hammad
                $perids_per_week=$this->db->query($week)->result_array();
                //$period_week_count=$perids_per_week[0]['count'].' / '.$row['periods_per_week'];
                $day1="SELECT count(st.teacher_id) as count FROM ".get_school_db().".time_table_subject_teacher ttst 
	inner join ".get_school_db().".class_routine cr on cr.class_routine_id=ttst.class_routine_id 
	inner join ".get_school_db().".class_routine_settings cs on cr.c_rout_sett_id=cs.c_rout_sett_id 
	inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
	where st.teacher_id=".$row['teacher_id']." and cs.is_active = 1 and cr.day='".$day."' and st.school_id=".$_SESSION['school_id']."";
	
	// and cs.is_active = 1 added by hammad

                $perids_per_day=$this->db->query($day1)->result_array();
                //$period_day_count=$perids_per_day[0]['count'].' / '.$row['periods_per_day'];

                if(in_array($row["teacher_id"],$arrAssigned) )
                {

                    $dis="disabled";
                    $assigned_var=get_phrase('already_assigned');
                    $mycolor="red";
                }
                else
                {

                    if($perids_per_week[0]['count']>=$row['periods_per_week'])
                    {
                        $dis="disabled";
                        $assigned_var=get_phrase('weekly_limit_reached');
                        $mycolor="red";
                    }
                    else
                    {

                        if($perids_per_day[0]['count']>=$row['periods_per_day'])
                        {
                            $dis="disabled";
                            $assigned_var=get_phrase('daily_limit_reached');
                            $mycolor="red";
                        }
                    }
                }

                $str.='<tr><td><input '.$dis.' class="teacher" 

type="checkbox" onClick="mycl()" name="teachers" id="teacher"'.$row['teacher_id'].'" value="'.$row['subject_teacher_id'].'"></input></td><td>'.$row['teacher'].'<br><span class="'.$mycolor .'">'.$assigned_var.'</span></td><td> '.$perids_per_day[0]['count'].'</td><td> '.$row['periods_per_day'].'</td> <td>'.$perids_per_week[0]['count'].'</td><td>'.$row['periods_per_week'].'</td></tr>';
                if ($i % 3== 0) $str.="<tr>";
                $i++;
            }
            $str.="</tr></table>";}


        echo $str;exit;

    }
    function edit_assign_teacher()
    {
        $class_routine_id=$this->input->post('class_routine_id');
        $subject_id=$this->input->post('subject_id');
        $teacher_array=$this->input->post('teacher_id');
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->where('class_routine_id',$class_routine_id);

        if($this->db->delete(get_school_db().'.time_table_subject_teacher'))
        {
            if(sizeof($teacher_array)>0)
            {
                $data['school_id']=$_SESSION['school_id'];
                $data['class_routine_id']=$class_routine_id;
                foreach($teacher_array as $row)
                {
                    $data['subject_teacher_id']=$row['value'];
                    $this->db->insert(get_school_db().'.time_table_subject_teacher',$data);
                    $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
                }
                echo "edited";
            }
        }
    }



    function subject_period_count(){
        
        //section id is empty here
        $subject=$this->input->post('subject_id');
        $section_id=$this->input->post('section_id');
        $day=$this->input->post('day');
        $perion_num=$this->input->post('period_num');
        $c_rout_setting_id=$this->input->post('c_rout_setting_id');
        $query="select * from ".get_school_db().".class_routine cr inner join ".get_school_db().".class_routine_settings cs on cs.c_rout_sett_id=cr.c_rout_sett_id where cs.school_id=".$_SESSION['school_id']." 
        and cs.section_id = $section_id and cr.day='".$day."' and cr.subject_id=$subject and cs.c_rout_sett_id=$c_rout_setting_id";
        $res=$this->db->query($query)->num_rows();
        
        $query2="select periods_per_day,periods_per_week from ".get_school_db().".subject_section cr  where school_id=".$_SESSION['school_id']." and subject_id=$subject and section_id=$section_id";

        $res2=$this->db->query($query2)->result_array();
        //print_r($res2);exit;
        $ary=array();
        
        if($res<$res2[0]['periods_per_day'])
        {
            $query="select * from ".get_school_db().".class_routine cr inner join ".get_school_db().".class_routine_settings cs on cs.c_rout_sett_id = cr.c_rout_sett_id where cs.school_id=".$_SESSION['school_id']." and cs.section_id=$section_id and cr.subject_id=$subject and cs.c_rout_sett_id=$c_rout_setting_id";
             $res1=$this->db->query($query)->num_rows();
			//($res1>0) &&
            if(( ($res2[0]['periods_per_week']>0)) &&($res1<$res2[0]['periods_per_week']))
            {
            
                $ary['type']='Weekly';
                $ary['count']=$res1;
                $ary['msg']='';
                
                echo json_encode(($ary));
            }
            elseif((($res1>0) && ($res2[0]['periods_per_week']>0)) && ($res1>=$res2[0]['periods_per_week'])){
                $ary['type']='Weekly';
                $ary['count']=$res1;
                $ary['msg']=get_phrase('reached_weekly_limit_of').' ('.$res2[0]['periods_per_week'].')'.get_phrase('periods');
                echo json_encode(($ary));
            }
        }
        elseif( (($res>0) && ($res2[0]['periods_per_day']>0))&&($res>=$res2[0]['periods_per_day'])){
            $ary['type']='Daily';
            $ary['count']=$res;
            $ary['msg']=get_phrase('reached_daily_limit_of').' ('.$res2[0]['periods_per_day'].')'.get_phrase('periods');
            echo json_encode(($ary));
        }

    }
    function get_settings()
    {
        $setting_id=$_POST['setting_id'][0];
        // print_r($_POST['setting_id'][0]);exit;
        $res=$this->db->query("select * from ".get_school_db().".class_routine_settings where c_rout_sett_id=".$setting_id." and school_id=".$_SESSION['school_id']."")->result_array();

        if(sizeof($res)>0)
        {

            $class=$this->db->query("select class_id from ".get_school_db().".class_section where section_id=".$res[0]['section_id']." and school_id=".$_SESSION['school_id']."")->result_array();

            $arr['yearly_terms_id']=$res[0]['yearly_terms_id'];
            $arr['section_id']=$res[0]['section_id'];
            $arr['no_of_periods']=$res[0]['no_of_periods'];
            //if(sizeof($class)>0)
            {
                $arr['class_id']=$class[0]['class_id'];
            }
            $dept=$this->db->query("select departments_id from ".get_school_db().".class where class_id=".$class[0]['class_id']." and school_id=".$_SESSION['school_id']."")->result_array();
            //if(sizeof($dept)>0)
            {
                $arr['department_id']=$dept[0]['departments_id'];
            }
            $academic=$this->db->query("select academic_year_id from ".get_school_db().".yearly_terms where yearly_terms_id=".$res[0]['yearly_terms_id']." and school_id=".$_SESSION['school_id']."")->result_array();
            //if(sizeof($academic)>0)
            {
                $arr['academic_id']=$academic[0]['academic_year_id'];
            }
            echo json_encode($arr);


        }

    }

    function get_subject_list(){
        $urlArr=explode('/',$_SERVER['REQUEST_URI']);
        $str=subject_option_list(end($urlArr));
        echo $str;
    }

    function get_subject_components()
    {
        // echo $this->input->post('subject_id');
        $subject_id=  $this->input->post('subject_id');
        $term_id = intval($this->input->post('term_id'));
        $subjectQuery = $this->db->query("select title,subject_component_id,percentage from ".get_school_db().".subject_components 
	  		where 
	  		subject_id=".$subject_id." 
	  		and school_id=".$_SESSION['school_id']."
	  		")->result_array();
        //echo $this->db->last_query();
        //print_r($subjectQuery);
        $str='';
        if(sizeof($subjectQuery)>0)
        {
            $str="<table  style='margin-top:10px;text-align:center;' class='table table-responsive table-striped table-bordered table-hover'><tr style='font-weight:bold;'><td></td><td>".get_phrase('component')."</td><td>".get_phrase('weightage')."</td></tr>";

            foreach($subjectQuery as $row)
            {
                $str.='<tr><td><input class="components" type="checkbox"  name="components" id="component"'.$row['subject_component_id'].'" value="'.$row['subject_component_id'].'"></input></td><td>'.$row['title'].'</td><td> '.$row['percentage'].'</td> </tr>';
                if ($i % 3== 0) $str.="<tr>";
                $i++;
            }

            $str.="</tr></table>";
        }
        echo $str;exit;
    }


    function class_routine_timings($c_rout_sett_id=0,$period_no=1,$day=0,$period_start_time=0)
    {

        $query="select cr.period_no,cr.duration FROM ".get_school_db().".class_routine cr 
                            WHERE 
                            cr.school_id=".$_SESSION['school_id']." 
                            AND cr.day='$day'
                            AND cr.period_no >= $period_no
                            AND cr.c_rout_sett_id=$c_rout_sett_id
                            order by cr.period_no asc
                                ";

        $CRS_arr = $this->db->query($query)->result_array();
        $routine_arr = array();
        foreach($CRS_arr as $crs_row)
        {
            $routine_arr[$crs_row['period_no']]['duration']=$crs_row['duration'];
        }

        $settings=$this->db->query("select * from ".get_school_db().".class_routine_settings where c_rout_sett_id=$c_rout_sett_id and school_id=".$_SESSION['school_id']."")->result_array();
        $no_of_periods=0;
        $break_duration=0;
        $break_after_period=0;
        $default_period_duration=0;
        foreach($settings as $row)
        {
            $no_of_periods= $row['no_of_periods'];
            $break_duration=$row['break_duration'];
            $break_after_period=$row['break_after_period'];
            $default_period_duration=$row['period_duration'];
        }
        if(count($routine_arr)>0)
        {


            $period_new=$period_start_time;
            for($i=$period_no;$i<=$no_of_periods;$i++)
            {
                $end=0;
                $day = strtolower($day);
                if(isset($routine_arr[$i]['duration'])
                    && $routine_arr[$i]['duration']>0)
                {
                    $duration=$routine_arr[$i]['duration'];

                    $start=$period_new;
                    $period_new = strtotime($period_new) +
                        strtotime(minutes_to_hh_mm($duration)) -
                        strtotime('00:00');

                    $period_new = date('H:i', $period_new);
                    $end=$period_new;
                    $data_arr['period_start_time']=$start;
                    $data_arr['period_end_time']=$end;
                    $this->db->where('school_id',$_SESSION['school_id']);
                    $this->db->where('c_rout_sett_id',$c_rout_sett_id);
                    $this->db->where('period_no',$i);
                    $this->db->where('day',$day);
                    $this->db->update(get_school_db().'.class_routine',$data_arr);
                    //echo $this->db->last_query();
                }
                else
                {
                    $period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($default_period_duration)) - strtotime('00:00');
                    $period_new = date('H:i', $period_new);
                }
                if(($break_after_period > 0)
                    && ($break_after_period==$i) &&
                    ($break_duration > 0)
                )
                {
                    $period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($break_duration)) - strtotime('00:00');
                    $period_new = date('H:i', $period_new);
                }

            }
        }

    }




}