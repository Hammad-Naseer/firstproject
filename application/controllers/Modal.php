<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class Modal extends CI_Controller {

	function __construct()
    {
		parent::__construct();
		$this->load->database();
		/*cache control*/
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
        if($_SESSION['user_login'] != 1)
			redirect(base_url() . 'login');
    }
	
	/***default functin, redirects to login page if no admin logged in yet***/
	public function index()
	{
	}
	function popup($page_name = '' , $param2 = '' , $param3 = '',$param4 = '',$root = 0)
	{
		$account_type =	get_login_type_folder($_SESSION['login_type']);
		$page_data['param2']		=	$param2;
		$page_data['param3']		=	$param3;
        $page_data['param4']		=	$param4;
		$exp_ary                    =   explode(':',$page_name);
        
        if($root == 1)
        {
            $this->load->view( 'backend/'.$page_name.'.php' ,$page_data);	
        }else{
            if(isset($exp_ary[1]) && $exp_ary[1]!=""){
                $this->load->view( 'backend/'.$exp_ary[1].'/'.$exp_ary[0].'.php' ,$page_data);	
            }
            else{
                $this->load->view( 'backend/'.$account_type.'/'.$page_name.'.php' ,$page_data);	
            }
        }
		echo '<script src="'.base_url().'assets/js/neon-custom-ajax.js"></script>';
		echo '<script src="'.base_url().'assets/js/common.js"></script>';
	}
}


