<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    //session_start();
class Tutorials extends CI_Controller
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
        if (get_login_type_name($_SESSION['login_type']) != 'admin' )
            redirect(base_url() . 'login');
    }
    
    function video_tutorials()
    {
        $page_data['page_name']  = 'video_tutorials';
        $page_data['page_title'] = get_phrase('video_tutorials');
        
        $this->load->view('backend/index', $page_data);
        
    }
}