<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Recover_Password extends CI_Controller
{
	private $system_db = '';
	private $enc_key = 'gweb';

    function __construct()
	{
		parent::__construct();

		$this->system_db = $this->config->item('system_db_name');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']== 1)
			redirect(base_url() . 'admin/dashboard');

		$this->load->database();
		$this->load->library('user_agent');
	}

    public function index()
    {
    	$this->load->view('backend/recover_password/v_recover_pass_email');
    }
    
    function submit_email()
    {
    	$refferer = base_url('recover_password');
    	if ($this->agent->referrer() == $refferer)
    	{
    		$email = $this->input->post('email');
    		if ($email != '')
    		{
    		    $res = $this->db->query("select email,user_login_id from ".$this->system_db.".user_login where email='".$email."' ")->result_array();
    			if (count($res) > 0)
    			{
    				$email = $res[0]['email'];
					$enc_email = $this->encrypt_data($email);

    				$code = rand(9999,999999);
    				$message = '
					
					<h2  align="center" style="margin-top:100px;">Gsims Password Recovery Confirmation!</h2>
					<p align="center"> Please click on the following link to recover your password on GSIMS.</p>';

    				$url = base_url('recover_password/submit_code').'/'.$enc_email.'/'.$code;
    				$message .= "<p align='center'><a target='_blank' href='".$url."' >Click here</a> </p>";

    				$this->load->library('email');

    				$this->email->from('no-reply@gminns.com');
					$this->email->to($res[0]['email']);
					$this->email->subject('Recover Your Password At GSIMS');
					$this->email->message($message);
					$this->email->send();

					$update['recovery_code'] = $code;
					$update['last_sent_at'] = date('Y-m-d h:i:s');
					$this->db->update($this->system_db.'.user_login', $update, array('user_login_id' => intval($res[0]['user_login_id'])) );

					$this->session->set_flashdata('code_sent_success', get_phrase('password_recovery_link_is_sent_to_your_email_._
     <br>_Please_check_your_email.'));
					echo $message;
    			}
    			else
    			{
    				$this->session->set_flashdata('code_sent_failed', get_phrase('email_you_provided_is_invalid.'));
					redirect(base_url() . 'recover_password');
    			}
    		}
    		else
    		{
    			$this->session->set_flashdata('code_sent_failed', get_phrase('email_you_provided_is_invalid.'));
				redirect(base_url() . 'recover_password');
    		}
    	}
    	else
    	{
    		redirect(base_url() . 'login');
    	}
    }
    
    function verify_code()
    {
    	$this->load->view('backend/recover_password/verify_code');
    }

    function submit_code($enc_email, $code)
    {
    	if ( $enc_email != '' && $code != '' )
    	{
    		$email = $this->decrypt_data($enc_email);

    		$res = $this->db->query("select recovery_code,last_sent_at from ".$this->system_db.".user_login where email='".$email."' and recovery_code = '".$code."' ")->result_array();
    		if ( (count($res) > 0) && 
    			intval($res[0]['recovery_code']) > 0
    		)	
    		{
    			$sent_at = $res[0]['last_sent_at'];
    			$cur_date_time = date('Y-m-d h:i:s');
    			
    			$diff = strtotime($cur_date_time) - strtotime($sent_at);
				$hours = $diff / ( 60 * 60 );

    			if ($hours <= 48) // 2 days
    			{
    				$data['email'] = $enc_email;
    				$data['user_email'] = $email;
		    		$data['code'] = $code;

					$this->load->view('backend/recover_password/verify_code', $data);
    			}
    			else
    			{
    				$this->session->set_flashdata('invalid_code', get_phrase('this_link_is_expired_please_try_again'));
    				redirect(base_url() . 'recover_password/verify_code');
    			}
    		}
    		else
    		{
    			$this->session->set_flashdata('invalid_code', get_phrase('sorry_this_link_is_not_valid_anymore'));
    			redirect(base_url() . 'recover_password/verify_code');
    		}
    	}
    	else
		{
			$this->session->set_flashdata('invalid_code', get_phrase('sorry_this_link_is_not_valid_anymore'));
			redirect(base_url() . 'recover_password/verify_code');
		}
    }

    function submit_new_password()
    {
    	$email = $this->decrypt_data($this->input->post('field1'));
    	if ($email != '')
    	{
    		$code = $this->input->post('field2');
    		$password = $this->input->post('password');

    		if ($email != '' && $code != '' && $password != '')
    		{
    			$res = $this->db->query("select user_login_id from ".$this->system_db.".user_login where email='".$email."' and recovery_code = '".$code."' ")->result_array();

    			if (count($res) > 0)
    			{
    				$update['password'] = $password;
    				$update['recovery_code'] = 0;
					$this->db->update($this->system_db.'.user_login', $update, array('user_login_id' => intval($res[0]['user_login_id'])) );

					$this->session->set_flashdata('password_updated', get_phrase('your_password_updated_successfully'));
					redirect(base_url() . 'recover_password/password_updated');
    			}
    			else
    			{
    				$this->session->set_flashdata('invalid_code', get_phrase('invalid_request_try_again'));
					redirect(base_url() . 'recover_password/verify_code');
    			}
    		}
    		else
    		{
    			$this->session->set_flashdata('invalid_code', 'invalid_request_try_again');
				redirect(base_url() . 'recover_password/verify_code');
    		}
    	}
    	else
    	{
    		redirect(base_url() . 'login');
    	}
    }

    function password_updated()
    {
    	$this->load->view('backend/recover_password/v_password_updated');
    }

    private function encrypt_data($data)
    {
    	$this->load->library('encrypt');
    	$encrypted_string = $this->encrypt->encode($data, $this->enc_key);

    	$response = str_replace(
    						array('+', '=', '/'), 
    						array('.', '-', ':'), 
    						$encrypted_string);
    	return $response;
    }

    private function decrypt_data($data)
    {
    	$encrypted_string = str_replace(
    						array('.', '-', ':'), 
    						array('+', '=', '/'), 
    						$data);


    	$this->load->library('encrypt');
    	$response = $this->encrypt->decode($encrypted_string, $this->enc_key);

    	return $response;
    }
}