<?php
if(!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();
class Vacation extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->database();

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
		
	}
	
	function Vacation_settings($action="" , $delete_id='')
	{
		if($_SESSION['user_login']!=1)redirect('login' );
		
		if($action=="delete")
		{
			$this->db->query("delete from ".get_school_db().".holiday where holiday_id=$delete_id AND school_id=".$_SESSION['school_id']." ");
			redirect(base_url().'vacation/Vacation_settings/');
		}
		else
		{
		     $start_date     = $this->input->post('start_date');
    	     $end_date       = $this->input->post('end_date');
    	     $apply_filter   =  $this->input->post('apply_filter');
    	     
             $date_query="";
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
             
             if($start_date!='')
             {
            	$date_query=" AND start_date >= '".$start_date."'";
             }
             
             if($end_date!='')
             {
            	$date_query=" AND start_date <= '".$end_date."'";
             }
             
             if($start_date!='' && $end_date!='')
             {
            	$date_query=" AND start_date >= '".$start_date."' AND start_date <= '".$end_date."' ";
             }
            
             $qur_val="select * from ".get_school_db().".holiday WHERE school_id=".$_SESSION['school_id']." $date_query Order By start_date DESC";
             $qr_arry=$this->db->query($qur_val)->result_array();
            
             $page_data['start_date']    =  $start_date;
             $page_data['end_date']      =  $end_date;
             $page_data['apply_filter']  =  $apply_filter;
             $page_data['qr_arry']       =  $qr_arry;                       
		     $page_data['page_name']     = 'vacation_settings';
		     $page_data['page_title']    = get_phrase('vacation_settings');
		     $this->load->view('backend/index', $page_data);
		
		    
		}


	}
	
	function add_vacation()
	{
		$startdate=  explode('/',$_POST['start-date']);
		$vacationStart = $_POST['start-date'];
		$enddate=  explode('/',$_POST['end-date']);
		$vacationEnd = $_POST['end-date'];
		
		$end=new DateTime($vacationEnd);
		$vacEnd=$end->modify('+1 day');
		$name=  $_POST['vacation-name'];
		//$term_id=  $_POST['term_id'];
		$period = new DatePeriod(
			new DateTime($vacationStart),
			new DateInterval('P1D'),
			$vacEnd
		);
		
		$data=array('start_date'=>$vacationStart,'end_date'=>$vacationEnd,'title'=>$name,'school_id'=>$_SESSION['school_id']);
		$holday_id=$this->input->post('holiday_id');
		
		
		if($holday_id == "")
		{
			$this->session->set_flashdata('club_updated',get_phrase('record_saved_sucessfully'));
			$this->db->insert(get_school_db().'.holiday',$data);
		}
		else
		{
        	$this->session->set_flashdata('club_updated', get_phrase('record_updated_sucessfully'));
            $this->db->where('holiday_id',$holday_id);	
            $this->db->update(get_school_db().'.holiday',$data);
            //echo $this->db->last_query();	
        }
            $this->load->helper('message');
            $message="".get_phrase('holiday_announcement').": $name ".get_phrase('from')." ".date('d-m-y',strtotime($vacationStart))." ".get_phrase('to')." ". date('d-m-y',strtotime($vacationEnd))."";	
            ///sms start
            if(isset($_POST['send_message']) && $_POST['send_message']!="") {
            sms_to_all($message);
            $data_status['sms_status']=1;
            $this->db->where('holiday_id',$holiday_id);	
            $this->db->update(get_school_db().'.holiday',$data_status);
            }
            //Email Setting her
            if(isset($_POST['send_email']) && $_POST['send_email']!="") {
            $subject=get_phrase('vacation_announcement');
            email_to_all($message,$subject);
            }
            //email Ends here          
            redirect(base_url().'vacation/vacation_settings');
	    
	}
	
	function get_terms()
	{
        if($this->input->post('status')!='')
        {
          echo yearly_terms_option_list($this->input->post('academic_year'),'',$this->input->post('status'));
        }
        else
           echo yearly_terms_option_list($this->input->post('academic_year'));		
	}
	
    function get_vacation_list()
    {
    	$data['start_date']=$this->input->post('start_date');
    	$data['end_date']=$this->input->post('end_date');
    	
    	$this->load->view("backend/admin/ajax/vacation_list",$data);
    }
	
	function term_date_range()
	{
		$date1  =date_slash($this->input->post('date1'));
		$end_date=date_slash($this->input->post('date2'));
		
		if(!empty($date1))
		{
			echo term_date_range($this->input->post('term_id'),$date1,'');
		}
		
		if(!empty($end_date))
		{
			echo term_date_range($this->input->post('term_id'),'',$end_date);
		}
	}
	
	
}