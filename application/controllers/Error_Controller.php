<?php 
    if (!defined('BASEPATH'))exit('No direct script access allowed');

    class Error_Controller extends CI_Controller
    {
        function __construct()
    	{
	    	parent::__construct();
    	}
        public function error_server()
        {
            $session = session_start();
    
            $data['heading'] = $_SESSION['500_error_heading'];
            $data['message'] = $_SESSION['500_error_message'];
    
            session_unset('500_error_heading');
            session_unset('500_error_message');
    
            $this->load->view('errors/html/error_general', $data);
        }
    }