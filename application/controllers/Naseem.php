<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Naseem extends CI_Controller {
    
    function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	} 
	function marksheeeet()
	{   
		$page_data['page_name'] = 'marksheet';
        $page_data['page_title'] = get_phrase('marksheet'); 
		$this->load->view('backend/index', $page_data);  
	} 
	 
}