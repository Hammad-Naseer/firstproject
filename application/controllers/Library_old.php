<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Library extends CI_Controller
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
    }
    
    /***default functin, redirects to login page if no teacher logged in yet***/
    public function index()
    {
        if ($_SESSION['library_login'] != 1)
            redirect(base_url() . 'login');
        if ($_SESSION['library_login'] == 1)
            redirect(base_url() . 'library/dashboard');
    }
    
    /***TEACHER DASHBOARD***/
    function dashboard()
    {
        if ($_SESSION['library_login'] != 1)
            redirect(base_url());
		$page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('library_dashboard');
        $this->load->view('backend/index', $page_data);
    }
   
    /****MANAGE STUDENTS CLASSWISE*****/
  function student_information($class_id = '')
	{
		if ($_SESSION['library_login'] != 1)
            redirect('login');
			
		$page_data['page_name']  	= 'student_information';
		$page_data['page_title'] 	= get_phrase('student_information'). " - ".get_phrase('class')." : ".
											$this->crud_model->get_class_name($class_id);
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
	}
	
    /****MANAGE PRINCIPALS*****/
    function principal_list($param1 = '', $param2 = '')
    {
        if ($_SESSION['library_login'] != 1)
            redirect(base_url());
        
        if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_principal_id'] = $param2;
        }
        $page_data['principals']   = $this->db->get('principal')->result_array();
        $page_data['page_name']  = 'principal';
        $page_data['page_title'] = get_phrase('principals_list');
        $this->load->view('backend/index', $page_data);
    }
	
	 /****MANAGE TEACHERS*****/
    function teacher_list($param1 = '', $param2 = '')
    {
        if ($_SESSION['library_login'] != 1)
            redirect(base_url());
        
        if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_library_id'] = $param2;
        }
        $page_data['teachers']   = $this->db->get('teacher')->result_array();
        $page_data['page_name']  = 'teacher';
        $page_data['page_title'] = get_phrase('teacher_list');
        $this->load->view('backend/index', $page_data);
    }
	
	 /****MANAGE Librarians*****/
    function library_list($param1 = '', $param2 = '')
    {
        if ($_SESSION['library_login'] != 1)
            redirect(base_url());
        
        if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_library_id'] = $param2;
        }
        $page_data['teachers']   = $this->db->get('library')->result_array();
        $page_data['page_name']  = 'library';
        $page_data['page_title'] = get_phrase('library_list');
        $this->load->view('backend/index', $page_data);
    }
    
    /******MANAGE OWN PROFILE AND CHANGE PASSWORD***/
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['library_login'] != 1)
            redirect(base_url() . 'login');
        if ($param1 == 'update_profile_info') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['religion']    = $this->input->post('religion');
            $data['blood_group'] = $this->input->post('blood_group');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('library_id', $_SESSION['library_id']);
            $this->db->update('teacher', $data);
            $this->session->set_flashdata('flash_message', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'library/manage_profile/');
        }
        if ($param1 == 'change_password') {
            $data['password']             = $this->input->post('password');
            $data['new_password']         = $this->input->post('new_password');
            $data['confirm_new_password'] = $this->input->post('confirm_new_password');
            
            $current_password = $this->db->get_where('library', array(
                'library_id' => $this->session->userdata('library_id')
            ))->row()->password;
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
                $this->db->where('library_id', $this->session->userdata('library_id'));
                $this->db->update('library', array(
                    'password' => $data['new_password']
                ));
                $this->session->set_flashdata('flash_message', get_phrase('password_updated'));
            } else {
                $this->session->set_flashdata('flash_message', get_phrase('password_mismatch'));
            }
            redirect(base_url() . 'library/manage_profile/');
        }
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $page_data['edit_data']  = $this->db->get_where('library', array(
            'library_id' => $_SESSION['library_id']
        ))->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
   /**********MANAGE LIBRARY / BOOKS********************/
	function book_category($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['library_login'] != 1)
            redirect('login');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $this->db->insert('book_category', $data);
            redirect(base_url() . 'library/book_category');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['book_category_id']      = $this->input->post('book_category_id');
			
            $this->db->where('book_category_id', $param2);
            $this->db->update('book_category', $data);
            redirect(base_url() . 'library/book_category');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('book_category', array('book_category_id' => $param2 ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('book_category_id', $param2);
            $this->db->delete('book_category');
            redirect(base_url() . 'library/book_category');
        }
        $page_data['books']      = $this->db->select("*")->from('book_category')->order_by('book_category_id', 'desc')->get()->result_array();
        $page_data['page_name']  = 'book_category';
        $page_data['page_title'] = get_phrase('manage_library_categories');
        $this->load->view('backend/index', $page_data);
        
    }
    /**********MANAGE LIBRARY / BOOKS********************/
    function book($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['library_login'] != 1)
            redirect('login');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['price']       = $this->input->post('price');
            $data['author']      = $this->input->post('author');
            $data['class_id']    = $this->input->post('class_id');
			$data['book_category_id']      = $this->input->post('book_category_id');
			$data['number_books']= $this->input->post('number_books');
            $data['status']      = $this->input->post('status');
            $this->db->insert('book', $data);
            redirect(base_url() . 'library/book');
        }
		if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['price']       = $this->input->post('price');
            $data['author']      = $this->input->post('author');
            $data['class_id']    = $this->input->post('class_id');
			$data['book_category_id']      = $this->input->post('book_category_id');
			$data['number_books']= $this->input->post('number_books');
            $data['status']      = $this->input->post('status');
            
            $this->db->where('book_id', $param2);
            $this->db->update('book', $data);
            redirect(base_url() . 'library/book');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('book', array(
                'book_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('book_id', $param2);
            $this->db->delete('book');
            redirect(base_url() . 'library/book');
        }
        $page_data['books']      = $this->db->select("*")->from('book')->order_by('book_id', 'desc')->get()->result_array();
        $page_data['page_name']  = 'book';
        $page_data['page_title'] = get_phrase('manage_library_books');
        $this->load->view('backend/index', $page_data);
        
    }
	/**********MANAGE LIBRARY / BOOKS********************/
    function book_issue($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['library_login'] != 1)
            redirect('login');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['price']       = $this->input->post('price');
            $data['author']      = $this->input->post('author');
            $data['class_id']    = $this->input->post('class_id');
			$data['book_category_id']      = $this->input->post('book_category_id');
			$data['number_books']= $this->input->post('number_books');
            $data['status']      = $this->input->post('status');
            $this->db->insert('book', $data);
            redirect(base_url() . 'library/book_issue');
        }
		if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['price']       = $this->input->post('price');
            $data['author']      = $this->input->post('author');
            $data['class_id']    = $this->input->post('class_id');
			$data['book_category_id']      = $this->input->post('book_category_id');
			$data['number_books']= $this->input->post('number_books');
            $data['status']      = $this->input->post('status');
            
            $this->db->where('book_id', $param2);
            $this->db->update('book', $data);
            redirect(base_url() . 'library/book');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('book', array(
                'book_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('book_id', $param2);
            $this->db->delete('book');
            redirect(base_url() . 'library/book_issue');
        }
        $page_data['book']      = $this->db->select("*")->from('book_issue')->order_by('issue_id', 'desc')->get()->result_array();
        $page_data['page_name']  = 'book_issue';
        $page_data['page_title'] = get_phrase('manage_issued_books');
        $this->load->view('backend/index', $page_data);
        
    }
	function book_request($param1 = '', $param2 = '')
    {
        if ($_SESSION['library_login'] != 1)
            redirect('login');
        if ($param1 == 'do_update') {
			
			//insert in book_issue table
			$data['request_id']  = $this->input->post('request_id');
            $data['issue_date']  = date("Y-m-d");
			$expiry = new DateTime($this->input->post('due_date'));
            $data['expiry_date'] = $expiry->format('Y-m-d').PHP_EOL;
            $data['status']      = 0;
            $this->db->insert('book_issue', $data);
			
			//update status of request
			$data1['status']      = 1;
            $this->db->where('request_id', $this->input->post('request_id'));
            $this->db->update('book_request',$data1);
			
			//update books stock
			$query_id	=	$this->db->get_where('book' , array('book_id' =>$this->input->post('book_id')));
			$res_id	=	$query_id->result_array();
				foreach($res_id as $row_id){
					$number_books	=	$row_id['number_books']-1;
				}
			$data2['number_books']	=	$number_books;
			$this->db->where('book_id', $this->input->post('book_id'));
            $this->db->update('book',$data2);
			
            redirect(base_url() . 'library/book_request');
        }
		
		if ($param1 == 'do_collect') {
			
			//update status of request
			$data1['status']      = 1;
			$expiry = new DateTime($this->input->post('collect_date'));
            $data1['collect_date'] = $expiry->format('Y-m-d').PHP_EOL;
            $data1['fine']=$this->input->post('fine');
			$this->db->where('issue_id', $this->input->post('issue_id'));
            $this->db->update('book_issue',$data1);
			
			//update books stock
			$query_id	=	$this->db->get_where('book' , array('book_id' =>$this->input->post('book_id')));
			$res_id	=	$query_id->result_array();
				foreach($res_id as $row_id){
					$number_books	=	$row_id['number_books']+1;
				}
			$data2['number_books']	=	$number_books;
			$this->db->where('book_id', $this->input->post('book_id'));
            $this->db->update('book',$data2);
			
            redirect(base_url() . 'library/book_issue');
        }
		if ($param1 == 'deny') {
			$data['status']      = 2;
            $this->db->where('request_id', $param2);
            $this->db->update('book_request',$data);
            redirect(base_url() . 'library/book_request');
        }
        $page_data['book']      = $this->db->select("*")->from('book_request')->order_by('request_id', 'desc')->get()->result_array();
        $page_data['page_name']  = 'book_request';
        $page_data['page_title'] = get_phrase('manage_book_requests');
        $this->load->view('backend/index', $page_data);
        
    }
    
    /**********MANAGE TRANSPORT / VEHICLES / ROUTES********************/
    function transport($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['library_login'] != 1)
            redirect('login');
        
        $page_data['transports'] = $this->db->get('transport')->result_array();
        $page_data['page_name']  = 'transport';
        $page_data['page_title'] = get_phrase('manage_transport');
        $this->load->view('backend/index', $page_data);
        
    }
    
    /***MANAGE EVENT / NOTICEBOARD, WILL BE SEEN BY ALL ACCOUNTS DASHBOARD**/
    function noticeboard($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['library_login'] != 1)
            redirect(base_url());
        
        if ($param1 == 'create') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
            $this->db->insert('noticeboard', $data);
            redirect(base_url() . 'library/noticeboard/');
        }
        if ($param1 == 'do_update') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
            $this->db->where('notice_id', $param2);
            $this->db->update('noticeboard', $data);
            $this->session->set_flashdata('flash_message', get_phrase('notice_updated'));
            redirect(base_url() . 'library/noticeboard/');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('noticeboard', array(
                'notice_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('notice_id', $param2);
            $this->db->delete('noticeboard');
            redirect(base_url() . 'library/noticeboard/');
        }
        $page_data['page_name']  = 'noticeboard';
        $page_data['page_title'] = get_phrase('manage_noticeboard');
        $page_data['notices']    = $this->db->get('noticeboard')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGE DOCUMENT / home work FOR A SPECIFIC CLASS or ALL*******************/
    function document($do = '', $document_id = '')
    {
        if ($_SESSION['library_login'] != 1)
            redirect('login');
        if ($do == 'upload') {
            move_uploaded_file($_FILES["userfile"]["tmp_name"], "uploads/document/" . $_FILES["userfile"]["name"]);
            $data['document_name'] = $this->input->post('document_name');
            $data['file_name']     = $_FILES["userfile"]["name"];
            $data['file_size']     = $_FILES["userfile"]["size"];
            $this->db->insert('document', $data);
            redirect(base_url() . 'library/manage_document');
        }
        if ($do == 'delete') {
            $this->db->where('document_id', $document_id);
            $this->db->delete('document');
            redirect(base_url() . 'library/manage_document');
        }
        $page_data['page_name']  = 'manage_document';
        $page_data['page_title'] = get_phrase('manage_documents');
        $page_data['documents']  = $this->db->get('document')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
}
