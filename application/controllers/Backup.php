<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Backup extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
		$this->load->database();
		$this->load->library('zip');
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        $this->school_db=$_SESSION['school_db'];
        
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }
    
    public function manage_backup()
    {
		if ($_SESSION['user_login'] != 1)
            redirect(base_url());
            
       	$page_data['page_name']  = 'manage_backup';
        $page_data['page_title'] = get_phrase('manage_backup');
         $this->load->view('backend/index', $page_data);      
	}
    
}   
?>