<?php
    if (!defined('BASEPATH'))exit('No direct script access allowed');
    class Todo extends CI_Controller
    {
        function __construct()
    	{
    		parent::__construct();
    		
            /*cache control*/
    		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            $this->output->set_header('Pragma: no-cache');
            if($_SESSION['user_login'] != 1)
                redirect(base_url() . 'login');    
        }
        
        public function list()
        {
            $todo_query = $this->db->query("SELECT * FROM ".get_school_db().".todo_list WHERE user_id = '".$_SESSION['user_id']."' AND user_type = '".$_SESSION['login_type']."' AND school_id = '".$_SESSION['school_id']."' ")->result_array();

            $page_data['todo_data'] = $todo_query;
            $page_data['page_name'] = 'todo/todo_list';
    		$page_data['page_title'] = 'todo list';
            $this->load->view("backend/index",$page_data);
        }
        
        public function todo_insert()
        {
            $resp = array();
            $data = array(
                'user_id'       =>  $_SESSION['user_id'],
                'school_id'     =>  $_SESSION['school_id'],
                'todo_title'    =>  $this->input->post("title"),
                'todo_content'  =>  $this->input->post("content"),
                'user_type'     =>  $_SESSION['login_type'],
            );
            
            $todo_id = $this->input->post("todo_id");
            if($todo_id > 0)
            {
               $this->db->where("todo_list_id",$todo_id);
               $this->db->update(get_school_db().".todo_list",$data);
               $resp['msg'] = "Update Inserted Succcessfully";
            }else{
            
                if($this->db->insert(get_school_db().".todo_list",$data))
                {
                    $resp['id'] = $this->db->insert_id();
                    $resp['msg'] = "Todo Inserted Succcessfully";
                }
            }
            
            echo  json_encode($resp);
        }
        
        public function todo_delete()
        {
            $todo_id = $this->uri->segment(3);
            $this->db->where("todo_list_id",$todo_id)
                     ->delete(get_school_db().".todo_list");
            $this->session->set_flashdata('error', 'Todo Deleted Successfully');
            redirect(base_url() . 'todo/list');         
        }
    }
    