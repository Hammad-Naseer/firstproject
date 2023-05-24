<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attendance extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        if($_SESSION['user_login']!= 1)
        		redirect(base_url() . 'login');
	}
	
    public function index()
    {
         if ($_SESSION['user_login']!= 1)
           redirect(base_url() . 'login');
         if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }
    
    public function get_date_wise_attendance_summary(){
        
        $section_id   =  $this->input->post('section_id');
        $start_date   =  date('Y-m-d' , strtotime($this->input->post('start')));
        $end_date     =  date('Y-m-d' , strtotime($this->input->post('end')));
        $section_name =  $this->input->post('section_name');
        
        $where = " AND school_id = " . $_SESSION['school_id'];
        if($start_date != '1970-01-01'){
            $where .= " AND date >= '$start_date' ";
        }
        if($end_date != '1970-01-01'){
            $where .= " AND date <= '$end_date' ";
        }

        $result=$this->db->query("select s.student_id , s.name ,
                                  (select count(attendance_id) from ".get_school_db().".attendance WHERE student_id = s.student_id and status = 1 $where ) as total_present ,
                                  (select count(attendance_id) from ".get_school_db().".attendance WHERE student_id = s.student_id and status = 2 $where ) as total_absent ,
                                  (select count(attendance_id) from ".get_school_db().".attendance WHERE student_id = s.student_id and status = 3 $where ) as total_leaves
                                  from ".get_school_db().".student s
								  where s.school_id=".$_SESSION['school_id']." 
								  and s.section_id=$section_id AND  s.student_status IN (".student_query_status().") ")->result_array();
								  
		$page_data['section_name']  =   $section_name;	
		$page_data['apply_filter']  =   $this->input->post('apply_filter');						  
		$page_data['section_id']    =   $section_id;
		$page_data['start_date']    =   $start_date;
		$page_data['end_date']      =   $end_date;
        $page_data['result']		=	$result;
        $page_data['page_name']		=	'date_wise_attendance_summary';
		$page_data['page_title']	=	get_phrase('date_wise_attendance_summary');
		$this->load->view('backend/index', $page_data);
        
    }
    
    
    public function date_wise_attendance_summary(){
        $page_data['page_name']		=	'date_wise_attendance_summary';
		$page_data['page_title']	=	get_phrase('date_wise_attendance_summary');
		$this->load->view('backend/index', $page_data);
    }
    
    
    function weekly_attendance_pdf()
    {
        $section_id   =  $this->input->post('section_id');
        $start_date   =  date('Y-m-d' , strtotime($this->input->post('start')));
        $end_date     =  date('Y-m-d' , strtotime($this->input->post('end')));
        
        $where = " AND school_id = " . $_SESSION['school_id'];
        if($start_date != '1970-01-01'){
            $where .= " AND date >= '$start_date' ";
        }
        if($end_date != '1970-01-01'){
            $where .= " AND date <= '$end_date' ";
        }

        $result = $this->db->query("select s.student_id , s.name ,
                                  (select count(attendance_id) from ".get_school_db().".attendance WHERE student_id = s.student_id and status = 1 $where ) as total_present ,
                                  (select count(attendance_id) from ".get_school_db().".attendance WHERE student_id = s.student_id and status = 2 $where ) as total_absent ,
                                  (select count(attendance_id) from ".get_school_db().".attendance WHERE student_id = s.student_id and status = 3 $where ) as total_leaves
                                  from ".get_school_db().".student s
								  where s.school_id=".$_SESSION['school_id']." 
								  and s.section_id=$section_id")->result_array();

        
        $page_data['apply_filter']  =   $this->input->post('apply_filter');						  
		$page_data['section_id']    =   $section_id;
		$page_data['start_date']    =   $start_date;
		$page_data['end_date']      =   $end_date;
        $page_data['result']		=	$result;
        $page_data['page_title']    =   get_phrase('date_wise_attendance_summary_pdf_report');

        $this->load->library('pdf');
        $view = 'backend/admin/date_wise_attendance_summary_pdf_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        
    }
    
    function weekly_attendance_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Date Wise Attendance Summary');
        $this->load->database();
        
        $section_id   =  $this->input->post('section_id');
        $section_name =  $this->input->post('section_name');
        $start_date   =  date('Y-m-d' , strtotime($this->input->post('start')));
        $end_date     =  date('Y-m-d' , strtotime($this->input->post('end')));
        
        $where = " AND school_id = " . $_SESSION['school_id'];
        if($start_date != '1970-01-01'){
            $where .= " AND date >= '$start_date' ";
        }
        if($end_date != '1970-01-01'){
            $where .= " AND date <= '$end_date' ";
        }

        $unpaid_std_arr = $this->db->query("select s.student_id , s.name ,
                                  (select count(attendance_id) from ".get_school_db().".attendance WHERE student_id = s.student_id and status = 1 $where ) as total_present ,
                                  (select count(attendance_id) from ".get_school_db().".attendance WHERE student_id = s.student_id and status = 2 $where ) as total_absent ,
                                  (select count(attendance_id) from ".get_school_db().".attendance WHERE student_id = s.student_id and status = 3 $where ) as total_leaves
                                  from ".get_school_db().".student s
								  where s.school_id=".$_SESSION['school_id']." 
								  and s.section_id=$section_id")->result_array();
    
        $titles_arr  = array();
        $unpaid_arr  = array();
        $logo_path   = "";
        $school_name = "";
        
        if (empty($d_school_id))
        {
            $logo_path      =  system_path($_SESSION['school_logo']);
            $school_name    =  $_SESSION['school_name'];
            $unpaid_arr[]   =  system_path($_SESSION['school_logo']);
        }
        else
        {
            $school_details =  get_school_details($d_school_id);
            $branch_name    =  $school_details['name'];
            $branch_logo    =  $school_details['logo'];
            $branch_folder  =  $school_details['folder_name'];
            $logo_path      =  system_path($branch_logo,$branch_folder,1);
            $school_name    =  $branch_name;
            $unpaid_arr[]   =  system_path($branch_logo,$branch_folder,1);
        }
        
        $unpaid_arr[] = 'Date Wise Attendance Summary';
        $unpaid_arr[] = "";
        $unpaid_arr[] = array('Sr.','Student Name','Total Present', 'Total Absent', 'Total Leaves');
        
        foreach ($unpaid_std_arr as $key => $value)
        {
            $count++;
            $record_arr = array();

            $record_arr[] = $count;
            $record_arr[] = $value['name'];
            $record_arr[] = $value['total_present'];
            $record_arr[] = $value['total_absent'];
            $record_arr[] = $value['total_leaves'];
            $unpaid_arr[] = $record_arr;
        }
        //______________set logo_______________
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        $path = $logo_path;
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('test_img');
        $objDrawing->setDescription('test_img');
        $objDrawing->setPath($path);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(60);   

        $objDrawing->setOffsetX(5); 
        $objDrawing->setOffsetY(5);                

        $objDrawing->setWidth(100); 
        $objDrawing->setHeight(60); 
        $objDrawing->setWorksheet($this->excel->getActiveSheet());

        $school_name = $school_name;
        $this->excel->getActiveSheet()->mergeCells('E1:J1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->mergeCells('A2:J2');
        $this->excel->getActiveSheet()->setCellValue('A2', $unpaid_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('A3:J3');

        $chk = "Filter : ". $section_name;
        if($start_date != '1970-01-01'){
            $chk .= "  - Start Date " . $start_date;
        }
        if($end_date != '1970-01-01'){
            $chk .= "  - End Date " . $end_date;
        }
        
        
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);
        
        $empty_rows = 5;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':J'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }
        
        $this->excel->getActiveSheet()->fromArray($unpaid_arr);
        $filename='Date Wise Student Attendance Summary.xls';

        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
        $objWriter->save('php://output');

    }
    
    function manage_attendance($date='',$month='',$year='',$section_id='')
	{
	    
		if($_SESSION['user_login']!=1)  redirect('login' );
		$filter = 0;
		
		if($_POST)
		{
		  $filter     = 1;
		  $section_id = $this->input->post('section_id');
		  $date_new	  =	explode("/",$_REQUEST['date']);
          $date       =	$date_new['1']."/".$date_new['0']."/".$date_new['2'];
	 	  $date       = $date_new['0'];
		  $month      = $date_new['1'];
		  $year       = $date_new['2'];
		}
	
		
		$page_data['filter']        =   $filter;
	 	$page_data['date']		    =	$date;
		$page_data['month']		    =	$month;
	    $page_data['year']		    =	$year;
		$page_data['section_id']	=	$section_id;
		$page_data['page_name']		=	'manage_attendance';
		$page_data['page_title']	=	get_phrase('manage_daily_attendance');

		$this->load->view('backend/index', $page_data);
    }
    
    function manage_subjectwise_attendance($date='',$month='',$year='',$section_id='')
	{
	    
		if($_SESSION['user_login']!=1)  redirect('login' );
		$filter = 0;
		
		if($_POST)
		{
		  $filter     = 1;
		  $section_id = $this->input->post('section_id');
		  $subject_id = $this->input->post('subject_id');
		  $date_new	  =	explode("/",$_REQUEST['date']);
          $date       =	$date_new['1']."/".$date_new['0']."/".$date_new['2'];
	 	  $date       = $date_new['0'];
		  $month      = $date_new['1'];
		  $year       = $date_new['2'];
		}
		
		$page_data['filter']        =   $filter;
	 	$page_data['date']		    =	$date;
		$page_data['month']		    =	$month;
	    $page_data['year']		    =	$year;
		$page_data['section_id']	=	$section_id;
		$page_data['subject_id']    =   $subject_id;
		$page_data['page_name']		=	'manage_subjectwise_attendance';
		$page_data['page_title']	=	get_phrase('manage_daily_attendance');

		$this->load->view('backend/index', $page_data);
    }
    
    function get_subjects_from_section_id()
    {
        $display = '';
        $section_id = $this->input->post("section_id");
        $display .= '<option>Select Subject</option>';
        $subjects = get_section_subject($section_id);
        foreach($subjects as $row):
            $display .= '<option value="'.$row['subject_id'].'">'.$row['name'].'-'.$row['code'].'</option>';
        endforeach;
        echo $display;
    }
    
    function apply_subjectwise_attendence($date='',$month='',$year='',$section_id='')
	{
	    $this->db->trans_begin();
	    
	    $send_sms = $this->input->post('send_sms');
	    
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
		$section_id  = $this->input->post('section_id');
		$subject_id  = $this->input->post('subjectId');
		$student_id  = $_POST['student_id'];
        $date_today  = $year.'-'.$month.'-'.$date;
		
		for($j=0;$j<=count($student_id)-1;$j++)
		{
            $verify_data	=	array(
    			 'student_id'=> $student_id[$j],
    			 'subject_id'=> $subject_id,
    			 'date'      => $date_today,
    			 'school_id' => $_SESSION['school_id']
			 );
			 
			$attendance_status = 0;
			if (isset($_POST["status-$j"])){
				$attendance_status = $_POST["status-$j"];
			}else{
				$attendance_status = 2;
			}
			
			$attendance = $this->db->get_where(get_school_db().'.subjectwise_attendance' , $verify_data);
			if($attendance->num_rows()>0)
			{
              	if($attendance->row()->status==3)
              	{
					
				}else{
					$attendance_id=$attendance->row()->attendance_id;
					$this->db->where('school_id',$_SESSION['school_id']);
					$this->db->where('attendance_id' , $attendance_id);
					$this->db->update(get_school_db().'.subjectwise_attendance' , array('subject_id' => $subject_id,'status' => $attendance_status,'user_id'=>$_SESSION['login_detail_id']));
           		}
            }else{
			    $this->db->insert(get_school_db().'.subjectwise_attendance' , array('subject_id' => $subject_id,'status' => $attendance_status,'user_id'=>$_SESSION['login_detail_id'],'student_id'=>$student_id[$j],'date'=>$date_today,'school_id' =>$_SESSION['school_id']));
            }
            
			if($attendance_status==2 && (int) $send_sms == 1)
			{
			    $this->load->helper('message');
			    
				$student_detail=  get_sms_detail($student_id[$j]);
				$numb          =  $student_detail['mob_num'];
				$student_name  =  $student_detail['student_name'];
				$class_name    =  $student_detail['class_name'];
				$section_name  =  $student_detail['section_name'];
				$to_email      =  $student_detail['email'];
				$message       =  "$student_name of $class_name - $section_name is absent on (".date('d-M-Y', mktime(0, 0, 0, $month, $date, $year)).")";

				send_sms($numb,'Indici Edu', $message, $student_id[$j],5);
			} 
			
		}
		
		$this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated',get_phrase('attendance_cannot_be_marked'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated',get_phrase('attendance_marked_successfully'));
            $this->db->trans_commit();
        }
		
		//redirect(base_url() . 'attendance/manage_attendance/'.$date.'/'.$month.'/'.$year.'/'.$section_id.'/');
	
		$page_data['date']			=	$date;
		$page_data['month']	   	    =	$month;
		$page_data['year']			=	$year;
		$page_data['section_id']	=	$section_id;
		$page_data['subject_id']	=	$subject_id;
		$page_data['page_name']		=	'manage_subjectwise_attendance';
		$page_data['page_title']	=	get_phrase('manage_daily_attendance');
		$this->load->view('backend/index', $page_data);
		
	}

    function subjectwise_attendance($date='',$month='',$year='',$section_id='')
	{
		if($_SESSION['user_login']!=1) redirect('login' );
		$filter = 0;
		
		if($_POST)
		{
			$filter     =   1;
		    $section_id =   $this->input->post('section_id');
		    $date_new	=	explode("/",$_REQUEST['date']);
            $date       =	$date_new['1']."/".$date_new['0']."/".$date_new['2'];
	 	    $date       =   $date_new['0'];
		    $month      =   $date_new['1'];
		    $year       =   $date_new['2'];
		}
		
		$page_data['filter']        =   $filter;
	 	$page_data['date']		    =	$date;
		$page_data['month']		    = 	$month;
	    $page_data['year']		    =	$year;
		$page_data['section_id']	=	$section_id;
		$page_data['page_name']		=	'subjectwise_attendance';
		$page_data['page_title']	=	get_phrase('subjectwise_daily_attendance');

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
	
	function attendance_selector()
	{
		
		$section_id =   $this->input->post('section_id');
		$date_new	=	explode("/",$_REQUEST['date']);
		$date		=	$date_new['1']."/".$date_new['0']."/".$date_new['2'];
		
		if($section_id == 0)
		{
			$this->session->set_flashdata('flash_message', get_phrase('value_missing'));
			redirect(base_url() . 'attendance/manage_attendance/'.$date );
		}
		else
		{	
		   redirect(base_url() . 'attendance/manage_attendance/'.$date.'/'.$section_id.'/');
		}
		
		
	}
	
	function apply_attendence($date='',$month='',$year='',$section_id='')
	{
	    $this->db->trans_begin();
	    $send_sms = $this->input->post('send_sms');
	    
	    if($_SESSION['user_login']!=1)redirect('login' );
		$section_id  = $this->input->post('section_id');
		$student_id  = $_POST['student_id'];
        $date_today  = $year.'-'.$month.'-'.$date;
		for($j=0;$j<=count($student_id)-1;$j++)
		{
            $verify_data	=	array(
    			 'student_id'=> $student_id[$j],
    			 'date'      => $date_today,
    			 'school_id' => $_SESSION['school_id']
			 );
			$attendance_status = 0;
			if (isset($_POST["status-$j"])){
			    
				$attendance_status = $_POST["status-$j"];
			}else{
				$attendance_status = 2;
			}
			$attendance = $this->db->get_where(get_school_db().'.attendance' , $verify_data);
			if($attendance->num_rows()>0)
			{
              	if($attendance->row()->status==3)
              	{
					
				}
				else
				{
					$attendance_id=$attendance->row()->attendance_id;
					$this->db->where('school_id',$_SESSION['school_id']);
					$this->db->where('attendance_id' , $attendance_id);
					$this->db->update(get_school_db().'.attendance' , array('status' => $attendance_status,'user_id'=>$_SESSION['login_detail_id']));
					$status= $attendance->row()->status;
					if($status == 2 && $attendance_status == 1){
					 
					     $this->db->where('attendance_id',$attendance_id);
					     $this->db->update(get_school_db().'.attendance_timing' , array('check_in'=> date("h:i:s a")));
					}
					
					   // 	Update Attendance Timing Table 
					if($attendance_status ==2):
					    	
				        $this->db->where('attendance_id',$attendance_id)->update(get_school_db().'.attendance_timing' , array('check_in'=>''));
				    endif;
           		}
             }
             else
             {
                
                   	$attendance_id=$attendance->row()->attendance_id;
					$this->db->insert(get_school_db().'.attendance' , array('status' => $attendance_status,'user_id'=>$_SESSION['login_detail_id'],'student_id'=>$student_id[$j],'date'=>$date_today,'school_id' =>$_SESSION['school_id']));
					$check_in = date("h:i:s a");
				    $last_id = $this->db->insert_id();
				         if($attendance_status == 1){
                                $data['check_in'] = $check_in;
                            }else{
                                $data['check_in']  = "";
                            }
                            $data['attendance_id'] = $last_id;
				    $this->db->insert(get_school_db().'.attendance_timing' , $data);
                   
			
					
             }
            
			if($attendance_status==2 && (int) $send_sms == 1)
			{
			    $this->load->helper('message');
			    
				$student_detail=  get_sms_detail($student_id[$j]);
				$numb          =  $student_detail['mob_num'];
				$student_name  =  $student_detail['student_name'];
				$class_name    =  $student_detail['class_name'];
				$section_name  =  $student_detail['section_name'];
				$to_email      =  $student_detail['email'];
				$message       =  "$student_name of $class_name - $section_name is absent on (".date('d-M-Y', mktime(0, 0, 0, $month, $date, $year)).")";

				send_sms($numb,'Indici Edu', $message, $student_id[$j],5);
			} 
			
		}
		
		$this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated',get_phrase('attendance_cannot_be_marked'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated',get_phrase('attendance_marked_successfully'));
            $this->db->trans_commit();
        }
		
		//redirect(base_url() . 'attendance/manage_attendance/'.$date.'/'.$month.'/'.$year.'/'.$section_id.'/');
	
		$page_data['date']			=	$date;
		$page_data['month']	   	    =	$month;
		$page_data['year']			=	$year;
		$page_data['section_id']	=	$section_id;
		$page_data['page_name']		=	'manage_attendance';
		$page_data['page_title']	=	get_phrase('manage_daily_attendance');
		$this->load->view('backend/index', $page_data);
		
	}
	
	function view_stud_attendance()
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $section_id = $_POST['section_id'];
	    $apply_filter = $_POST['apply_filter'];
	    
	    
	    if(isset($apply_filter) && $apply_filter != ""){
            $query="SELECT * FROM ".get_school_db().".student WHERE section_id=$section_id AND school_id=".$_SESSION['school_id']." AND  student_status IN (".student_query_status().") ORDER BY roll desc";
        	$students=$this->db->query($query)->result_array();
    		$page_data['students']		    =	$students;
	    }else{
	        $page_data['students']		    =	array();
	    }
		
		$page_data['section_id']		    =	$section_id;
    	$page_data['apply_filter']		    =	$apply_filter;
		$page_data['page_name']		    =	'view_student_attendance';
		$page_data['page_title']		=	get_phrase('view_student_attendance');
		$this->load->view('backend/index', $page_data);
		
	}
	
// 	function student_list()
// 	{
// 		$this->load->view('backend/admin/ajax/get_stud_list.php');
// 	}
	
	function get_months()
    {	
    	$acad_year    = $this->input->post("acad_year");
    	$yearly_terms = $this->input->post("yearly_terms");
    	$student_id   = $this->input->post("student_id");
    	if(isset($yearly_terms) && $yearly_terms!="")
    	{
    		$this->db->where('school_id',$_SESSION['school_id']);
    		$this->db->where('yearly_terms_id',$yearly_terms);
    	    $query = $this->db->get(get_school_db().'.yearly_terms');
    	}
    	else
    	{
    	
    		$q="SELECT * FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
    	    $query=$this->db->query($q);
    	}
    	
        $row        = $query->result_array();
    	$start_date = $row[0]['start_date'];
    	$end_date   = $row[0]['end_date'];
        $months     = array();
        
        while (strtotime($start_date) <= strtotime($end_date)) {
            $months[]   = array('year' => date('Y', strtotime($start_date)), 'month' => date('m', strtotime($start_date)), );
            $start_date = date('d M Y', strtotime($start_date.'+ 1 month'));
        }


        $arrlength = count($months);
        $counter   = 1;
        $str       = array();
        foreach($months as $key=>$value)
        {
        		$month_year=date("F - Y", mktime(0, 0, 0,  $value['month'], 1, $value['year']));
        		if($key == 0)
        		{
        			$month_first=$value['month'].",".$value['year'];
        		}
        ?>
        		<button type="button" class="btn btn-primary" name="btn_month" id="btn_new<?php echo $counter;?>" value="<?php echo $value['month'];?>,<?php echo $value['year'];?>,<?php echo $student_id;?>" onclick="attendance('<?php echo $value['month'];?>','<?php echo $value['year'];?>','<?php echo $student_id;?>')"><?php echo $month_year;?></button>
        <?php	
        	$counter++;	
        }
	
	}
	
	function month_create()
    {
        
    	$acad_year    = $this->input->post("acad_year");
    	$yearly_terms = $this->input->post("yearly_terms");
    	
    	if(isset($yearly_terms) && $yearly_terms!="")
    	{
    		$this->db->where('school_id',$_SESSION['school_id']);
    		$this->db->where('yearly_terms_id',$yearly_terms);
    	    $query = $this->db->get(get_school_db().'.yearly_terms');
    	}
    	
    	else
    	{
        	$q     = "SELECT * FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
    	    $query = $this->db->query($q);
    	}
    	
    	$row        = $query->result_array();
    	$start_date = $row[0]['start_date'];
    	$end_date   = $row[0]['end_date'];
	
        $months     = array();
        while (strtotime($start_date) <= strtotime($end_date)) {
            $months[]   = array('year' => date('Y', strtotime($start_date)), 'month' => date('m', strtotime($start_date)), );
            $start_date = date('d M Y', strtotime($start_date.'+ 1 month'));
        }
        
        
        $arrlength = count($months);
        $counter   = 1;
        $str       = array();
        $m         = array();
        $y         = array();
        
        $current_year  = date('Y');
        $current_month = date('m');
        $month_current = "";
        
        foreach($months as $key=>$value)
        	{
        		if ($current_year==$value['year'] && $current_month==$value['month'])
        		{
        			$month_current=date("F - Y", mktime(0, 0, 0,  $value['month'], 1, $value['year']));
        		}
        		$m[]=date("F - Y", mktime(0, 0, 0,  $value['month'], 1, $value['year']));
        		
        	$counter++;	
        	}
        	if($month_current == "")
        	{
        		if(isset($m[0]) && $m[0]!="")
        		{
        			$month_current=$m[0];
        		}
        	}
        	
        	$list_array['month']         = $m;
        	$list_array['month_current'] = $month_current;
        	echo json_encode($list_array);
        	
	}
		
	function attendance_generator()
	{
		$this->load->view('backend/admin/ajax/get_stud_attendance.php');
	}
	
	function get_year_term2()
    {
 		echo $yearly_term = yearly_terms_option_list($this->input->post('acad_year'),'',1);
 	}
 	
 	function get_year_term_array()
 	{
		$arr              = array("1","3");
		echo $yearly_term = yearly_terms_option_list($this->input->post('acad_year'),'',$arr);
	}
	
    function mark_absent_student()
    {
	
    	 $date  = $this->input->post('date');
         $date  = date_slash($date);
     	 $q     = "select student_id from ".get_school_db().".student where school_id=".$_SESSION['school_id']." and student_status in (".student_query_status().")";
         $qur_v = $this->db->query($q)->result_array();	
    
    	 foreach($qur_v as $row)
    	 {
    		$sel=$this->db->query("select * from ".get_school_db().".attendance where student_id=".$row['student_id']." and date='$date' and school_id=".$_SESSION['school_id'])->result_array();		
    		if(count($sel)>0)
    		{
    		}
    		else
    		{
             $this->db->insert(get_school_db().'.attendance' , array('status' =>2,'user_id'=>$_SESSION['login_detail_id'],'student_id'=>$row['student_id'],'date'=>$date,'school_id' =>$_SESSION['school_id']));
            }
    	}
    	
        $this->session->set_flashdata('flash_message', get_phrase('attendance_marked_successfully'));
    }

    function mark_absent_teacher()
    {
        
        $date  = $this->input->post('date');
        $date  = date_slash($date);
        $q     = "select * from ".get_school_db().".staff where school_id=".$_SESSION['school_id']." ";
        $qur_v = $this->db->query($q)->result_array();	
        foreach($qur_v as $row)
        {
           $sel = $this->db->query("select staff_id from ".get_school_db().".attendance_staff where staff_id=".$row['staff_id']." and date='".$date."' and school_id=".$_SESSION['school_id'])->result_array();		
           if(count($sel)>0)
           {
           }
           else
           {
               $this->db->insert(get_school_db().'.attendance_staff' , array('status' =>2,'user_id'=>$_SESSION['login_detail_id'],'staff_id'=>$row['staff_id'],'date'=>$date,'school_id' =>$_SESSION['school_id']));
           }
        }
        $this->session->set_flashdata('flash_message', get_phrase('teacher_attendance_marked_successfully'));
    }   
    
    function view_subjectwise_attendance()
    {
        if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $section_id = $_POST['section_id'];
	    $apply_filter = $_POST['apply_filter'];
	    
	    
	    if(isset($apply_filter) && $apply_filter != ""){
            $query="SELECT * FROM ".get_school_db().".student WHERE section_id=$section_id AND school_id=".$_SESSION['school_id']." AND  student_status IN (".student_query_status().") ORDER BY roll desc";
        	$students=$this->db->query($query)->result_array();
    		$page_data['students']		    =	$students;
	    }else{
	        $page_data['students']		    =	array();
	    }
		
		$page_data['section_id']		    =	$section_id;
    	$page_data['apply_filter']		    =	$apply_filter;
		$page_data['page_name']		    =	'view_subjectwise_attendance';
		$page_data['page_title']		=	get_phrase('view_subjectwise_attendance');
		$this->load->view('backend/index', $page_data);
    }
    function student_subjectwise_attendance($student_id = 0)
    {
    
        if(isset($_POST['month'])){
            $date_month=$_POST['month'];
            $date_month = date("m", strtotime($date_month));
            $date_year=$_POST['year'];
        }else{
            $date_month = date('m');
            $date_year = date('Y');
        }
        
        $page_data['stud_id']		    =	str_decode($student_id);
        $page_data['date_month']		    =	$date_month;
        $page_data['date_year']		    =	$date_year;
		$page_data['page_name']		    =	'student_subjectwise_attendance';
		$page_data['page_title']		=	get_phrase('student_subjectwise_attendance');
		$this->load->view('backend/index', $page_data);
    }
    
    function previous_attendance()
    {
        $section = $_POST['section_id'];
        $student_id = $_POST['student_select'];
        $date = $_POST['att_date'];
        $apply_filter = $_POST['apply_filter'];
        
        if($student_id != "" && $date != ""):
            $query = $this->db->query("SELECT * FROM ".get_school_db().".attendance WHERE student_id = '$student_id' AND date = '$date' ");
            if($query->num_rows() > 0):
                $data = $query->result();
            endif;    
        endif;    
        
        $page_data['student_filter']	=	$student_id;
        $page_data['section_filter']    =	$section;    
        $page_data['date']		        =	$date;
        $page_data['apply_filter']		=	$apply_filter;
        $page_data['data']		        =	$data;
		$page_data['page_name']		    =	'previous_attendance';
		$page_data['page_title']		=	get_phrase('previous_attendance');
		$this->load->view('backend/index', $page_data);
    }
    
    function mark_previous_attendance($std_id,$date)
    {
        if($std_id != "" && $date != ""):
        $query = $this->db->query("UPDATE ".get_school_db().".attendance SET status = 1 WHERE student_id = '$std_id' AND date = '$date' ");
            if($query):
            $this->session->set_flashdata('flash_message', get_phrase('attendance_marked_successfully'));
            redirect(base_url() . 'attendance/previous_attendance');
            endif;    
        endif;  
    }
    
     function individual_mark_student_check_out(){
      
        $attendance_id = $_POST['attendance_id'];
        $current_date = date("Y-m-d");
        $data = $this->db->query("SELECT attendance.attendance_id,attendance.status,attendance_timing.check_out  FROM  ".get_school_db().".attendance
                                   LEFT JOIN ".get_school_db().".attendance_timing ON attendance_timing.attendance_id = attendance.attendance_id
                                   WHERE attendance.attendance_id = $attendance_id And attendance.date= '$current_date' ")->result_array();
        foreach($data as $item)
    	   {
    	    $attendance_id = $item['attendance_id'];
            $check_out = date("h:i:s a");
            $this->db->where('attendance_id',$attendance_id)->update(get_school_db().'.attendance_timing' , array('check_out' => $check_out)); 	
    	   }
    }
    
    function mark_student_check_out(){
      
        $section_id = $_POST['hidden_data_check_out_id'];
        $current_date = date("Y-m-d");
        $data = $this->db->query("SELECT attendance.attendance_id,attendance.status,attendance_timing.check_out  FROM  ".get_school_db().".student
                                   LEFT JOIN ".get_school_db().".attendance ON attendance.student_id = student.student_id
                                   LEFT JOIN ".get_school_db().".attendance_timing ON attendance_timing.attendance_id = attendance.attendance_id
                                   WHERE section_id = $section_id And attendance.date= '$current_date' ")->result_array();
                               
        foreach($data as $item)
    	   {
    	    $attendance_id = $item['attendance_id'];
    	    $check_out = $item['check_out'];
    	 
                if($item['status'] == 1 && empty($item['check_out'])){
                       $check_out = date("h:i:s a");
                       $this->db->where('attendance_id',$attendance_id)->update(get_school_db().'.attendance_timing' , array('check_out' => $check_out)); 	
                    }	     
    	   }
    	 
    }
    
     
    function mark_staff_check_out(){
      
        // $staff_id = $_POST['hidden_staff_check_out_id'];
   
        $current_date = date("Y-m-d");
        $data = $this->db->query("SELECT attendance_staff.attend_staff_id,attendance_staff.status,attendance_staff_timing.check_out,attendance_staff_timing.check_in  FROM  ".get_school_db().".attendance_staff
                                   LEFT JOIN ".get_school_db().".attendance_staff_timing ON attendance_staff_timing.attend_staff_id = attendance_staff.attend_staff_id
                                   WHERE attendance_staff.date = '$current_date' ")->result_array();
                                  
        foreach($data as $item)
    	   {
    	    $staff_id = $item['attend_staff_id'];
    	    $check_out = $item['check_out'];
    	    $check_in = $item['check_in'];
                if($item['status'] == 1 ){
                       $check_out = date("h:i:s a");
                       $this->db->where('attend_staff_id',$staff_id)->update(get_school_db().'.attendance_staff_timing' , array('check_out' => $check_out)); 	
                    }	     
    	   }
    	 
    }
    function specific_staff_check_out(){
     
        foreach($_POST['selct_check_box'] as $staff ){
              $check_out = date("h:i:s a");
              $this->db->where('attend_staff_id',$staff)->update(get_school_db().'.attendance_staff_timing' , array('check_out' => $check_out)); 
        }

    }
}
?>