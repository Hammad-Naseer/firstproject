<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Attendance_staff extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		
       /*cache control*/
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
                if ($_SESSION['user_login'] == 1)
        redirect(base_url() . 'admin/dashboard');
    }
    function manage_staff_attendance($date='',$month='',$year='')
    {
        if($_SESSION['user_login']!=1)
            redirect('login' );
    		$filter=0;
    		if($_POST)
    		{
    		    $date_new=explode("/",$_REQUEST['date']);	
    		    $date=$date_new['2']."/".$date_new['1']."/".$date_new['0'];
    			$month=$date_new['1'];
    			$date=$date_new['0'];
    			$year=$date_new['2'];
    			$filter=1;
    		}
    		$page_data['filter'] = $filter;
    		$page_data['date']		=	$date;
    		$page_data['month']		=	$month;
    		$page_data['year']		=	$year;
    		$page_data['page_name']='manage_staff_attendance';
    		$page_data['page_title']		=	get_phrase('manage_daily_attendance');
    		$this->load->view('backend/index', $page_data);
    }
    
  
      function check_out_staff_attendance($date='',$month='',$year='')
      
    {
        if($_SESSION['user_login']!=1)
            redirect('login' );
    		$filter=0;
    		if($_POST)
    		{
    		    $date_new=explode("/",$_REQUEST['date']);	
    		    $date=$date_new['2']."/".$date_new['1']."/".$date_new['0'];
    			$month=$date_new['1'];
    			$date=$date_new['0'];
    			$year=$date_new['2'];
    			$filter=1;
    		}
    		$page_data['filter'] = $filter;
    		$page_data['date']		=	$date;
    		$page_data['month']		=	$month;
    		$page_data['year']		=	$year;
    		$page_data['page_name']='check_out_staff_attendance';
    		$page_data['page_title']		=	get_phrase('check_out_daily_attendance');
    		$this->load->view('backend/index', $page_data);
    }
    
    
	//apply attendence
	function apply_attendence($date='',$month='',$year='')
	{
	    $this->db->trans_begin();
	     
	    $send_sms = $this->input->post('send_sms');
		if($_SESSION['user_login']!=1)redirect('login' );
		$staff_id=$_POST['staff_id'];
                
        $date_today = $year.'-'.$month.'-'.$date;
        
		for($j=0;$j<=count($staff_id)-1;$j++)
		{
			$verify_data	=	array(
			'staff_id'=> $staff_id[$j],
			 'date'=> $date_today,
			 'school_id' =>$_SESSION['school_id']
			 );
			$attendance = $this->db->get_where(get_school_db().'.attendance_staff' , $verify_data);
			
			$attendance_status = 0;
			if (isset($_POST["status-$j"])){
				$attendance_status = $_POST["status-$j"];
			}else{
				$attendance_status = 2;
			}
			
            if($attendance->num_rows()!=0)
            {
            	if($attendance->row()->status==3){}
				else{	
					$attendance_id		= $attendance->row()->attend_staff_id;
					$this->db->where('school_id',$_SESSION['school_id']);
            		$this->db->where('attend_staff_id' , $attendance_id);
            		$this->db->update(get_school_db().'.attendance_staff' , array('status' => (isset($_POST["status-$j"]))?$_POST["status-$j"]:2,'user_id'=>$_SESSION['login_detail_id']));
            		
            			$status= $attendance->row()->status;
			
					if($status == 2 && $attendance_status == 1){
					 
					     $this->db->where('attend_staff_id',$attendance_id);
					     $this->db->update(get_school_db().'.attendance_staff_timing' , array('check_in'=> date("h:i:s a")));
					}
					
						   // 	Update Attendance Staff Timing Table 
					if($attendance_status ==2):
					    	
				        $this->db->where('attend_staff_id',$attendance_id)->update(get_school_db().'.attendance_staff_timing' , array('check_in'=>''));
				    endif;
            		
            		
            		
            		if($attendance_status == 2 && $send_sms == "1")
        			{
        			    $this->load->helper('message');
        				$staff_detail=  get_staff_detail($staff_id[$j]);
        				$numb          =  $staff_detail[0]['mobile_no'];
        				$staff_name  =  $staff_detail[0]['name'];
        				$to_email      =  $staff_detail[0]['email'];
        				$message       =  "$staff_name is absent on (".date('d-M-Y', mktime(0, 0, 0, $month, $date, $year)).")";
        				if($numb != "" || !empty($numb)){
            				send_sms($numb,'Indici Edu', $message, $staff_id[$j] , 14);
        				}
        			}
           		}
            }else{
               $this->db->insert(get_school_db().'.attendance_staff' , array('status' => (isset($_POST["status-$j"]))?$_POST["status-$j"]:2,'user_id'=>$_SESSION['login_detail_id'],'staff_id'=>$staff_id[$j],'date'=>$date_today,'school_id' =>$_SESSION['school_id']));
               
               	$check_in = date("h:i:s a");
               	$last_id = $this->db->insert_id();
               	
               	if($attendance_status == 1){
                   $data['check_in'] = $check_in;
                            }
                 else{
                   $data['check_in']  = "";
                          }
                     $data['attend_staff_id'] = $last_id; 
                     
                      $this->db->insert(get_school_db().'.attendance_staff_timing' , $data);
               	
                if($attendance_status == 2 && $send_sms == "1")
    			{
    			    $this->load->helper('message');
    				$staff_detail=  get_staff_detail($staff_id[$j]);
    				$numb          =  $staff_detail[0]['mobile_no'];
    				$staff_name  =  $staff_detail[0]['name'];
    				$to_email      =  $staff_detail[0]['email'];
    				$message       =  "$staff_name is absent on (".date('d-M-Y', mktime(0, 0, 0, $month, $date, $year)).")";
    				if($numb != "" || !empty($numb)){
        				send_sms($numb,'Indici Edu', $message, $staff_id[$j] , 14);
    				}	
    			}
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
        
		redirect(base_url() . 'attendance_staff/manage_staff_attendance/'.$date.'/'.$month.'/'.$year);
		
        // 		$page_data['date']			=	$date;
        // 		$page_data['month']		=	$month;
        // 		$page_data['year']			=	$year;
        		
        // 		$page_data['page_name']		=	'manage_staff_attendance';
        // 		$page_data['page_title']		=	get_phrase('manage_daily_attendance');
        // 		$this->load->view('backend/index', $page_data);
	}
	
	
	function view_staff_attendance()
	{
		if($_SESSION['user_login']!=1)redirect('login' );
		$page_data['page_name']		=	'view_staff_attendance';
		$page_data['page_title']		=	get_phrase('view_staff_attendance');
		$this->load->view('backend/index', $page_data);
		
	}
	
	function get_year_term2()
    {
		if($this->input->post('acad_year')!="")
		{
			echo $yearly_term=yearly_terms_option_list($this->input->post('acad_year'),'',1);
		}
 		
 	}
 	
 	function get_months()
    {	
    	$acad_year=$this->input->post("acad_year");
    	$yearly_terms=$this->input->post("yearly_terms");
    	$staff_id=$this->input->post("staff_id");
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
        $row=$query->result_array();
    	$start_date=$row[0]['start_date'];
    	$end_date=$row[0]['end_date'];
        $months = array();
        while (strtotime($start_date) <= strtotime($end_date)) {
            $months[] = array('year' => date('Y', strtotime($start_date)), 'month' => date('m', strtotime($start_date)), );
            $start_date = date('d M Y', strtotime($start_date.
                '+ 1 month'));
        }
    
    
        $arrlength = count($months);
        $counter=1;
        $str = array();
        foreach($months as $key=>$value)
    	{
    		
    		$month_year=date("F - Y", mktime(0, 0, 0,  $value['month'], 1, $value['year']));
    		if($key == 0)
    		{
    			$month_first=$value['month'].",".$value['year'];
    		}?>
    		
    		<button type="button" class="btn btn-primary" name="btn_month" id="btn_new<?php echo $counter;?>" value="<?php echo $value['month'];?>,<?php echo $value['year'];?>,<?php echo $staff_id;?>" onclick="attendance('<?php echo $value['month'];?>','<?php echo $value['year'];?>','<?php echo $staff_id;?>')"><?php echo $month_year;?></button>
    		
    	<?php	
        	$counter++;	
    	}

    }
	
		
    function month_create()
    {	
    	$start_date=date_slash($this->input->post("start_date"));
    	$end_date=date_slash($this->input->post("end_date"));
    	
        $months = array();
    
        while(strtotime($start_date) <= strtotime($end_date)) {
        	
             $months[]=array('year'=>date('Y', strtotime($start_date)), 'month'=>date('m', strtotime($start_date)),);
             $start_date=date('d M Y', strtotime($start_date.'+ 1 month'));
        
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
    	
    	$list_array['month']=$m;
    	$list_array['month_current']=$month_current;
    	echo json_encode($list_array);
    	
    	
    	}
		
	
	function attendance_generator()
	{
		$this->load->view('backend/admin/ajax/get_staff_attendance.php');
	}
	
 	
 }	
   
?>