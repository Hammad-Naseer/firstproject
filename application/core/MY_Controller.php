<?php
    class MY_Controller extends CI_Controller {

        public function __construct() {
            parent::__construct();
            // class is just an alias for the controller name
            if (!$this->session->userdata('user_login')) {
                // redirect('login');
                echo $is_login;exit;
            }else
            {
                $CI =& get_instance();
                if($CI ->router->class == "Validate_Session"){
                    echo "Hook";
                }
                exit;
            }
        }
    
    }