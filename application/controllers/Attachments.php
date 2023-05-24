<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();

class Attachments extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

    }
    
    function upload_file()
    {

        $size      =  $this->input->post('size');
        $ext       =  $this->input->post('ext');
        $file_type =  $this->input->post('file_type');

        $ext_f     =  array('img'=>array('png','jpeg','jpg'),'doc'=>array('png','jpeg','jpg','pdf','doc','docx'));
        $size_f    =  array('img'=>((1024*200)),'doc'=>((1024*1024)*2));
        
        if($size<$size_f[$file_type] && in_array($ext,$ext_f[$file_type]))
        {
            echo "yes";
        }
        elseif($size>$size_f[$file_type] && !in_array($ext,$ext_f[$file_type]))
        {
            echo "file_type_size";
        }
        elseif($size>$size_f[$file_type])
        {
            echo "size";
        }
        elseif(!in_array($ext,$ext_f[$file_type]))
        {
            echo "file_type";
        }
        else
        {
            echo "";
        }

    }
    
    
}