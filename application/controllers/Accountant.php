<?php
if(!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();


class Accountant extends CI_Controller{
  
  var $menu_ary;
  
  function __construct()
    {
		parent::__construct();
		$this->load->database();
		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->menu_ary=array();
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
	}
    
    
    
	/***default functin, redirects to login page if no accountant logged in yet***/
	public function index()
	{
 		if($_SESSION['accountant_login']!= 1)
		redirect(base_url() . 'login');
		if($_SESSION['accountant_login'] == 1)
		redirect(base_url() . 'accountant/dashboard');
	}
	/***accountant DASHBOARD***/
	function dashboard()
    {
		if(!$_SESSION['accountant_login'])
		redirect(base_url());
		$page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('accountant_dashboard');
        $this->load->view('backend/index', $page_data);
	}
	/****MANAGE STUDENTS CLASSWISE*****/
	function student_add(){
		if($_SESSION['accountant_login'] != 1)
		redirect(base_url());
			
		$page_data['page_name']  = 'student_add';
		$page_data['page_title'] = get_phrase('add_student');
		$this->load->view('backend/index', $page_data);
	}
	
	function student_information($class_id = ''){
		if($_SESSION['accountant_login'] != 1)
		redirect('login');
			
		$page_data['page_name']  	= 'student_information';
		$page_data['page_title'] 	= get_phrase('student_information'). " - ".get_phrase('class')." : ".
		$this->crud_model->get_class_name($class_id);
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
		
	}

	function student($param1 = '', $param2 = '', $param3 = ''){
		if($_SESSION['accountant_login'] != 1)
		redirect('login');
		if($param1 == 'create'){
			$data['name']     = $this->input->post('name');
			$data['birthday'] = $this->input->post('birthday');
			$data['sex']      = $this->input->post('sex');
			$data['address']  = $this->input->post('address');
			$data['phone']    = $this->input->post('phone');
			$data['email']    = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['class_id'] = $this->input->post('class_id');
			$data['roll']     = $this->input->post('roll');
			$this->db->insert(get_school_db().'.student', $data);
			$student_id = mysql_insert_id();
			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $student_id . '.jpg');
            
			//getting last inserted ids
			$last_insert_id=$this->db->insert_id();
            
			$this->email_model->account_opening_email('student', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
			redirect(base_url() . 'accountant/chalan/'. $last_insert_id);
		}
       
		if($param2 == 'do_update'){
			$data['name']        = $this->input->post('name');
			$data['birthday']    = $this->input->post('birthday');
			$data['sex']         = $this->input->post('sex');
			$data['address']     = $this->input->post('address');
			$data['phone']       = $this->input->post('phone');
			$data['email']       = $this->input->post('email');
			$data['class_id']    = $this->input->post('class_id');
			$data['roll']        = $this->input->post('roll');
            
			$this->db->where('student_id', $param3);
			$this->db->update(get_school_db().'.student', $data);
			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $param3 . '.jpg');
			$this->crud_model->clear_cache();
            
			redirect(base_url() . 'accountant/student_information/' . $param1);
		} 
		
		if($param2 == 'delete'){
			$this->db->where('student_id', $param3);
			$this->db->delete(get_school_db().'.student');
			redirect(base_url() . 'accountant/student_information/' . $param1);
		}

		if($param1 == 'concession'){
			$data['student_id']        = $this->input->post('student_id');
			$data['reasion']    = $this->input->post('reasion');
			$data['status'] = 0;
            
			$this->db->insert(get_school_db().'.concession', $data);
           
			redirect(base_url() . 'accountant/chalan/'. $last_insert_id);
		}

		if($param1 == 'chalan'){
			$data['student_id']= $this->input->post('student_id');
			$student_id=$this->input->post('student_id');
			$data['chalan1']= $this->input->post('chalan1');
			$data['amount1']= $this->input->post('amount1');
			$data['chalan2']= $this->input->post('chalan2');
			$data['amount2']= $this->input->post('amount2');
			$data['chalan3']= $this->input->post('chalan3');
			$data['amount3']= $this->input->post('amount3');
			$data['chalan4']= $this->input->post('chalan4');
			$data['amount4']= $this->input->post('amount4');
			$data['chalan5']= $this->input->post('chalan5');
			$data['amount5']= $this->input->post('amount5');

			$data['chalan_num']= $this->input->post('chalan_number');
			$data['chalan_month']= $this->input->post('month');
			$data['chalan_year']= $this->input->post('year');
			$data['discount']= $this->input->post('concession');
			$data['due_date']= $this->input->post('end_date');
			$data['comment']= $this->input->post('reasion');
			$data['status']= 0;


			$data['total_amount']= $this->input->post('total_amount');
			$this->db->insert(get_school_db().'.chalan', $data);
    
			$this->db->insert_id();
    
			$q="SELECT student.roll,student.name, class.name as class_name FROM ".get_school_db().".student INNER join ".get_school_db().".class ON student.class_id=class.class_id where student.student_id=$student_id"  ;
    
			$query=$this->db->query($q);  
    
			if($query->num_rows()>0){
		
				foreach($query->result() as $rows){
			
					$data[]=$rows;
			
				}
		
				$page_data['back_data']=$data;
		
			}
    
			$page_data['page_name']  = 'chalan_print';
			$page_data['page_title'] = get_phrase('chalan_print');
			$page_data['page_data'] = $data;
	
			$this->load->view('backend/index', $page_data);
    
           
			//redirect(base_url() . 'accountant/get_pending_chalan');
		}
	}
  
  
	function chalan($last_insert_id=""){
		if($_SESSION['accountant_login'] != 1)
		redirect(base_url());
			
		$page_data['page_name']  = 'chalan';
		$page_data['page_title'] = get_phrase('chalan');
		$page_data['last_insert_id']=$last_insert_id;
		$this->load->view('backend/index', $page_data);
	}
  

  	
	function save_edit_chalan(){
  	
		if($_SESSION['accountant_login'] == 1)
		redirect(base_url() . 'accountant/dashboard');
            
		$chalan_id=$this->input->post('chalan_id');
		$class_id=$this->input->post('class_id');

		$data['chalan1']= $this->input->post('chalan1');
		$data['amount1']= $this->input->post('amount1');
		$data['chalan2']= $this->input->post('chalan2');
		$data['amount2']= $this->input->post('amount2');
		$data['chalan3']= $this->input->post('chalan3');
		$data['amount3']= $this->input->post('amount3');
		$data['chalan4']= $this->input->post('chalan4');
		$data['amount4']= $this->input->post('amount4');
		$data['chalan5']= $this->input->post('chalan5');
		$data['amount5']= $this->input->post('amount5');

		$data['chalan_month']= $this->input->post('month');
		$data['chalan_year']= $this->input->post('year');
		$data['discount']= $this->input->post('concession');
		$data['due_date']= $this->input->post('end_date');
		$data['comment']= $this->input->post('reasion');
		$data['status']= 0;
		$data['total_amount']= $this->input->post('total_amount');
		$this->db->where('chalan_id', $chalan_id);
		$this->db->update(get_school_db().'.chalan', $data);
    
		$this->session->set_flashdata('club_updated',get_phrase('Record Editeted Successfully'));
    
		redirect(base_url() . 'accountant/list_print/'.$class_id.'/'.$data['chalan_month'].'/'.$data['chalan_year']);

	}
  	
 
  
	function chalan_print(){
		if($_SESSION['accountant_login'] != 1)
		redirect(base_url());
		$page_data['page_name']  = 'chalan_print';
		$page_data['page_title'] = get_phrase('chalan_print');
		$this->load->view('backend/index', $page_data);
	
		$page_data['chalan1']= $this->uri->segment(3);
		$page_data['amount1']= $this->uri->segment(4);
		$page_data['chalan2']= $this->uri->segment(5);
		$page_data['amount2']= $this->uri->segment(6);
		$page_data['chalan3']= $this->uri->segment(7);
		$page_data['amount3']= $this->uri->segment(8);
		$page_data['chalan4']= $this->uri->segment(9);
		$page_data['amount4']= $this->uri->segment(10);
		$page_data['chalan5']= $this->uri->segment(11);
		$page_data['amount5']= $this->uri->segment(12);
		$page_data['total_amount']= $this->uri->segment(13);
	
		$this->load->view('backend/index', $page_data);
 
	}
 
  
	/****MANAGE Concession*****//////////////////////////////////////////////////
	function concession_get_all($param1 = '', $param2 = '', $param3 = ''){
		if($_SESSION['accountant_login'] != 1)
		redirect(base_url());
            
		if($param1 == 'do_update'){
			$data['name']        = $this->input->post('name');
			$data['birthday']    = $this->input->post('birthday');
			$data['sex']         = $this->input->post('sex');
			$data['address']     = $this->input->post('address');
			$data['phone']       = $this->input->post('phone');
			$data['email']       = $this->input->post('email');
			$data['password']    = $this->input->post('password');
            
			$this->db->where('teacher_id', $param2);
			$this->db->update(get_school_db().'.teacher', $data);
			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $param2 . '.jpg');
			redirect(base_url() . 'admin/teacher/');
		} else if($param1 == 'personal_profile'){
			$page_data['personal_profile']   = true;
			$page_data['current_teacher_id'] = $param2;
		} else if($param1 == 'edit'){
			$page_data['edit_data'] = $this->db->get_where(get_school_db().'.teacher', array(
					'teacher_id' => $param2
				))->result_array();
		}
		if($param1 == 'delete'){
			$this->db->where('concession_id', $param2);
			$this->db->delete(get_school_db().'.concession');
			redirect(base_url() . 'accountant/concession_get_all/');
		}
       
		$page_data['page_name']  = 'concession_all';
		$page_data['page_title'] = get_phrase('manage_Concession');
		$this->load->view('backend/index', $page_data);
	}
	///////////////////////////////////////////
  
	/****MANAGE challan*****//////////////////////////////////////////////////
	function get_pending_chalan($param1 = '', $param2 = '', $param3 = ''){
		if($_SESSION['accountant_login'] != 1)
		redirect(base_url());
            
		if($param1 == 'do_update'){
			$data['name']        = $this->input->post('name');
			$data['birthday']    = $this->input->post('birthday');
			$data['sex']         = $this->input->post('sex');
			$data['address']     = $this->input->post('address');
			$data['phone']       = $this->input->post('phone');
			$data['email']       = $this->input->post('email');
			$data['password']    = $this->input->post('password');
            
			$this->db->where('teacher_id', $param2);
			$this->db->update(get_school_db().'.teacher', $data);
			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $param2 . '.jpg');
			redirect(base_url() . 'admin/teacher/');
		}
		else if($param1 == 'personal_profile'){
			$page_data['personal_profile']   = true;
			$page_data['current_teacher_id'] = $param2;
		} 
		else if($param1 == 'edit'){
			$page_data['edit_data'] = $this->db->get_where('teacher', array(
					'teacher_id' => $param2
				))->result_array();
		}
		if($param1 == 'delete'){
			$this->db->where('chalan_id', $param2);
			$this->db->delete('chalan');
			redirect(base_url() . 'accountant/get_pending_chalan/');
		}
		$page_data['concession']   = $this->db->get('concession')->result_array();
		$page_data['page_name']  = 'get_pending_chalan';
		$page_data['page_title'] = get_phrase('pending_chalan');
		$this->load->view('backend/index', $page_data);
	}
	///////////////////////////////////////////
	/****payed challan*****//////////////////////////////////////////////////
	function get_payed_chalan($param1 = '', $param2 = '', $param3 = ''){
		if($_SESSION['accountant_login'] != 1)
		redirect(base_url());
            
		if($param1 == 'do_update'){
			$data['name']        = $this->input->post('name');
			$data['birthday']    = $this->input->post('birthday');
			$data['sex']         = $this->input->post('sex');
			$data['address']     = $this->input->post('address');
			$data['phone']       = $this->input->post('phone');
			$data['email']       = $this->input->post('email');
			$data['password']    = $this->input->post('password');
            
			$this->db->where('teacher_id', $param2);
			$this->db->update('teacher', $data);
			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $param2 . '.jpg');
			redirect(base_url() . 'admin/teacher/');
		}
        
		else if($param1 == 'personal_profile'){
			$page_data['personal_profile']   = true;
			$page_data['current_teacher_id'] = $param2;
		} 
        
		else if($param1 == 'edit'){
			$page_data['edit_data'] = $this->db->get_where('teacher', array('teacher_id' => $param2))->result_array();
		}

		if($param1 == 'delete'){
			$this->db->where('chalan_id', $param2);
			$this->db->delete('chalan');
			redirect(base_url() . 'accountant/get_pending_chalan/');
		}

		$page_data['concession']   = $this->db->get('concession')->result_array();
		$page_data['page_name']  = 'all_payed_chalan';
		$page_data['page_title'] = get_phrase('all_payed_chalan');
		$this->load->view('backend/index', $page_data);
	}
	/****MANAGE Pending student*****//////////////////////////////////////////////////
	function All_pending_student($param1 = '', $param2 = '', $param3 = ''){
		if($_SESSION['accountant_login'] != 1)
		redirect(base_url());
            
		if($param1 == 'do_update'){
			$data['name']        = $this->input->post('name');
			$data['birthday']    = $this->input->post('birthday');
			$data['sex']         = $this->input->post('sex');
			$data['address']     = $this->input->post('address');
			$data['phone']       = $this->input->post('phone');
			$data['email']       = $this->input->post('email');
			$data['password']    = $this->input->post('password');
            
			$this->db->where('teacher_id', $param2);
			$this->db->update('teacher', $data);
			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $param2 . '.jpg');
			redirect(base_url() . 'admin/teacher/');
		}
        
		else if($param1 == 'personal_profile'){
			$page_data['personal_profile']   = true;
			$page_data['current_teacher_id'] = $param2;
		}
        
		else if($param1 == 'edit'){
			$page_data['edit_data'] = $this->db->get_where('teacher', array('teacher_id' => $param2))->result_array();
		}
       
		if($param1 == 'delete'){
			$this->db->where('chalan_id', $param2);
			$this->db->delete('chalan');
			redirect(base_url() . 'accountant/get_pending_chalan/');
		}

		$page_data['page_name']  = 'all_pending_student';
		$page_data['page_title'] = get_phrase('all_pending_student');
		$this->load->view('backend/index', $page_data);
	}
	///////////////////////////////////////////
	function student_chalan(){
		if(!$_SESSION['accountant_login'])
		redirect(base_url());
		$chalan_id= $this->uri->segment(3);
  
		$q="select c.chalan_id,c.student_id,c.chalan1,c.amount1,c.chalan2,c.due_date,c.amount2,c.chalan3,c.amount3,c.chalan4,c.amount4,c.chalan5,c.amount5,c.total_amount,c.date,c.status,c.chalan_num,c.chalan_month,c.chalan_year,c.discount,s.name,s.roll,cc.name as class_name

		from student s inner join chalan c
		on

		s.student_id=c.student_id
		INNER join class cc ON
		s.class_id=cc.class_id
		where c.chalan_id=$chalan_id";
  
		$query=$this->db->query($q);
  
		if($query->num_rows()>0){
  	
			foreach($query->result() as $rows){
				$data[]=$rows;
			}
  	
			$page_data['rows']=$data;
  	
		}

		$page_data['page_name']  = 'student_chalan_print';
		$page_data['page_title'] = get_phrase('student_chalan_print');
		$this->load->view('backend/index2', $page_data);
	}


	function batch_form(){
		if($_SESSION['accountant_login'] != 1)
		redirect(base_url());
			
		$page_data['page_name']  = 'batch_print';
		$page_data['page_title'] = get_phrase('chalan');
		$page_data['$class_id']= $class_id= $this->uri->segment(3);
		$this->load->view('backend/index', $page_data);
	
	}

	function create_chalan($param1 = ''){
		if(!$_SESSION['accountant_login'])
		redirect(base_url());
		if($param1 == 'chalan'){

			//print_r($_POST);

			$class_id=$this->input->post('class_id');
			$data['chalan1']= $this->input->post('chalan1');
			$data['amount1']= $this->input->post('amount1');
			$data['chalan2']= $this->input->post('chalan2');
			$data['amount2']= $this->input->post('amount2');
			$data['chalan3']= $this->input->post('chalan3');
			$data['amount3']= $this->input->post('amount3');
			$data['chalan4']= $this->input->post('chalan4');
			$data['amount4']= $this->input->post('amount4');
			$data['chalan5']= $this->input->post('chalan5');
			$data['amount5']= $this->input->post('amount5');

			//$data['chalan_num']= $this->input->post('chalan_number');

			$data['chalan_month']= $this->input->post('month');
			$data['chalan_year']= $this->input->post('year');
			$data['discount']= $this->input->post('concession');
			$data['due_date']= $this->input->post('end_date');
			$data['comment']= $this->input->post('reasion');
			$data['status']= 0;
			$data['total_amount']= $this->input->post('total_amount');


			$query=$this->db->query("select * from student where class_id=$class_id");

 
			if($query->num_rows()>0){
 	
				foreach($query->result() as $rows){
	
					$student_id= $rows->student_id;
					$data['student_id']=$student_id;
					$data['chalan_num']=$data['chalan_month'].'-'.$data['chalan_year'].'-'.$student_id;
	 
					$this->db->insert('chalan', $data);
				}

				$print_month=$data['chalan_month'];
				$print_year=$data['chalan_year'];


				redirect(base_url() . "accountant/list_print/$class_id/$print_month/$print_year");

			}
		}
	}
	function list_print(){
		if(!$_SESSION['accountant_login'])
		redirect(base_url());
		$page_data['page_name']  = 'all_chalan_print';
		$page_data['page_title'] = get_phrase('chalan_list');
		$this->load->view('backend/index', $page_data);
	
	}

	function edit_chalan(){
		if(!$_SESSION['accountant_login'])
		redirect(base_url());
		$class_id= $this->uri->segment(3);
		$month= $this->uri->segment(4);
		$year= $this->uri->segment(5);
		$chalan_id= $this->uri->segment(6);

		$query=$this->db->query("select * from chalan where chalan_id=$chalan_id");

		if($query->num_rows()>0){
	
			foreach($query->result() as $rows){
		
				$data[]=$rows;
			}	
			$page_data['res']=$data;
		}

		$page_data['page_name']  = 'chalan_edit';
		$page_data['page_title'] = get_phrase('edit_chalan');
		$this->load->view('backend/index', $page_data);
		
	}

	function batch_print(){
		if(!$_SESSION['accountant_login'])
		redirect(base_url());
		$year =$this->input->post('year');
		$month=$this->input->post('month');
		$class_id=$this->input->post('class_id');

		$q="select c.chalan_id,c.student_id,c.chalan1,c.amount1,c.chalan2,c.due_date,c.amount2,c.chalan3,c.amount3,c.chalan4,c.amount4,c.chalan5,c.amount5,c.total_amount,c.date,c.status,c.chalan_num,c.chalan_month,c.chalan_year,c.discount,s.name,s.roll,cc.name as class_name
		from student s inner join chalan c
		on
		s.student_id=c.student_id
		INNER join class cc ON
		s.class_id=cc.class_id
		where c.chalan_year='$year' and c.chalan_month='$month'  and s.class_id=$class_id ORDER BY s.roll";
  
		$query=$this->db->query($q);
  
		if($query->num_rows()>0){
  	
			foreach($query->result() as $rows){
		
				$data[]=$rows;
		
			}
  	
			$page_data['rows']=$data;
  	
		}

		$page_data['page_name']  = 'batch_chalan_print';
		$page_data['page_title'] = get_phrase('student_chalan_print');
		
		
		$this->load->view('backend/index2', $page_data);
	
	}

	function batch_delete(){
	
		if(!$_SESSION['accountant_login'])
		redirect(base_url());
		$year =$this->input->post('year');
		$month=$this->input->post('month');
		$class_id=$this->input->post('class_id');

		$q="delete from chalan
		where c.chalan_year='$year' and c.chalan_month='$month'  and s.class_id=$class_id";

		$this->session->set_flashdata('club_updated',get_phrase('Record Deleted Successfully'));

		redirect(base_url() . 'accountant/student_information/'.$class_id);

	}

	function challan_paid(){
		$chalan_id=$this->uri->segment(3);
		$data=array('status'=>1);
		$this->db->where('chalan_id',$chalan_id);
		$this->db->update('chalan',$data);
		redirect(base_url().'accountant/get_pending_chalan/');
	
	}

}