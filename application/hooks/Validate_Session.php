<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Validate_Session
{
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->load->helper('url');
    }

    public function validate() 
    {
        if ( strtolower( $this->CI->router->class ) == 'login' || strtolower( $this->CI->router->class ) == 'api' || strtolower( $this->CI->router->class ) == 'payment'  || strtolower( $this->CI->router->class ) == 'mobile_webservices' || strtolower( $this->CI->router->class ) == 'mobile_webservices_teacher' )
        {
        }else{
            if(!$this->CI->session->userdata('user_login')) 
            {
                redirect(site_url() . 'login');
            }
        }
    }
}