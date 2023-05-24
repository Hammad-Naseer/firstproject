<?php
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');
        //session_start();
    
    class Messenger extends CI_Controller
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

        function messege_view()
        {
            if (!$_SESSION['user_login'])
                redirect(base_url());

            $page_data['page_name']  = 'messenger/messege_view';
            $page_data['page_title'] = get_phrase('messenger');
            $this->load->view('backend/index', $page_data);
        }
        
}