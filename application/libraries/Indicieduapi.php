<?php

    defined('BASEPATH') or exit('No direct script access allowed');
    class Indicieduapi{
        
        public function __construct(){
            $this->CI =& get_instance();
        }
        
        public function api_response($code = "404",$status = false,$message = "",$query_result = array())
        {
            $data = array();
            $data['code'] = $code;
        	$data['status'] = $status;
        	$data['message'] = $message;
        	$data['data'] = $query_result;
        	return json_encode($data);
        	
        }
        
    }
    