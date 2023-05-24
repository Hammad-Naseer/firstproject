<?php

class Authenticate{

    var $CI;
    public function __construct()
    {
            $this->CI =& get_instance(); 
    }

    public function isSessionExists(){

        if($this->CI->session->has_userdata('key')){
            return $this->CI->session->userdata('key');
        }else{
            return false;
        }
    }
}