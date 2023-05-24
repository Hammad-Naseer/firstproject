<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

//session_start();
class Location extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('location');

		if($_SESSION['user_login'] != 1)
		redirect('login');

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}
	
	public function index()
    {
        if ($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');
            
        $page_data['page_name']  = 'location';
		$page_data['page_title'] = get_phrase('manage_locations');    
        $this->load->view('backend/index', $page_data);
    }
    
	
	function apis()
	{
	    $data_action = $this->input->post('data_action');
	    if($data_action == "fetch_all" || $data_action == "filter")
        {
            // Get Api Working
            // $api_url = "https://dev.indiciedu.com.pk/api/location_list?sch_id=214&sch_db=indicied_indiciedu_production";
            // $client = curl_init($api_url);
            // curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
            // $response = curl_exec($client);
            // curl_close($client);
            
            $loc_country   =  $this->input->post('loc_country');
            $loc_province  =  $this->input->post('loc_province');
            $loc_city      =  $this->input->post('loc_city');
            
            // Post Api Working
            $token = $_SESSION['token'];
            $form_data = array(
                'school_id'                 =>  $_SESSION["school_id"],
                'school_db'                 =>  $_SESSION["school_db"],
                'user_login_detail_id'      =>  $_SESSION["login_detail_id"],
                'token'                     =>  $token,
                'loc_country'               =>  $loc_country,
                'loc_province'              =>  $loc_province,
                'loc_city'                  =>  $loc_city
            );
            
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => base_url().'api/api_location_list',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => $form_data,
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response);
            // print_r($response);exit;
            
            $output = '';
            if($result->code == '200')
            {
                $count = 1;
                foreach($result->data as $row)
                {
                    if ($row->status == 1){
        				$status = "<span style='color:green'>Active</span>";
        			}else{
        				$status = "<span style='color:red'>Inactive</span>";     
        			}
                    $output .= '
                        <tr>
                        <td class="td_middle">
            				'.$count++.'
            			</td>
            			<td>
            				<div class="myttl"> '.$row->title.'</div>
                				<div> <strong>'.get_phrase('city').': </strong>'.$row->city.'</div>
                			<div>
                				<strong>'.get_phrase('province').': </strong>'.$row->province.'
                			</div>
                			<div>
                				<strong>'.get_phrase('country').': </strong>'.$row->country.'
                			</div>
                			<div><strong>'.get_phrase('status').': </strong>
                			'.$status.'
                			</div>
            			</td>
            			<td class="td_middle">';
            			    if (right_granted(array('locations_manage', 'locations_delete'))){
    				        $output .= '<div class="btn-group align-middle" data-step="6" data-position="left" data-intro="if you want location record edit or delete then press this button you have the option edit/delete">
            					<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
            					'.get_phrase('action').'
            						<span class="caret"></span>
            					</button>
        					<ul class="dropdown-menu dropdown-default pull-right" role="menu">';
                            if (right_granted('locations_manage')){
                            $edit_modal_url = "'".base_url()."modal/popup/modal_edit_location/".$row->location_id. "'";    
    						$output .= '<li>
    							<a href="#" onclick="showAjaxModal('.$edit_modal_url.');">
    								<i class="entypo-pencil">
    								</i>
    								'.get_phrase('edit').'
    							</a>
    						</li>';
    						}if (right_granted('locations_delete')){
    						$delete_modal_url = "'".base_url()."location/delete/".$row->location_id. "'";
    						$output .= '<li class="divider"></li>
        						<!-- DELETION LINK -->
        						<li>
        							<a href="#" onclick="confirm_modal('.$delete_modal_url.');">
        								<i class="entypo-trash"></i>
        								'.get_phrase('delete').'
        							</a>
        						</li>';
    						}
					$output .= '</ul>
				</div>';
			    }
			$output .= '</td>
                      </tr>
                    ';
                 }
            }
            else
            {
                $output .= '
                 <tr>
                    <td colspan="4" align="center">No Data Found</td>
                 </tr>
                ';
            }
        
            echo $output;
        }
	}
	
	function location_list_generator()
	{
		$this->load->view('backend/admin/ajax/get_location_list.php');
	}
	
	function get_province_list()
	{
		echo province_option_list($this->input->post('id'));
	}
	
	function get_city_list()
	{
		echo city_option_list($this->input->post('id'));
	}
	
	function get_location_list()
	{
		echo location_option_list($this->input->post('id'));
	}
	
	function add_location()
	{
		$data['city_id']                   = $this->input->post('loc_add_city');
		$data['title']                     = $this->input->post('title');
		$data['status']                    = $this->input->post('loc_status');
		$data['school_id']                 = $_SESSION['school_id'];
        $data['school_db']                 = $_SESSION["school_db"];
        $data['user_login_detail_id']      = $_SESSION["login_detail_id"];
        $data['token']                     = $_SESSION["token"];
		
		$curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => base_url().'api/api_location_insert',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $data,
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response);
		$this->session->set_flashdata('club_updated',$result->message);
		redirect(base_url().'location/index');
	}
	
	function edit_location()
	{
		$data['city_id'] = $this->input->post('loc_add_city');
		$data['title'] = $this->input->post('title');
		$data['status'] = $this->input->post('loc_status');
		$data['location_id'] =  $this->input->post('location_id');
		$data['school_id']                 = $_SESSION['school_id'];
        $data['school_db']                 = $_SESSION["school_db"];
        $data['user_login_detail_id']      = $_SESSION["login_detail_id"];
        $data['token']                     = $_SESSION["token"];
		
		$curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => base_url().'api/api_location_update',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $data,
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response);
        
		$this->session->set_flashdata('club_updated',$result->message);
		redirect(base_url().'location/');
	}
	
	function delete($location_id=0) 
	{
	    $data = array(
	        'location_id'               =>   $location_id,
    		'school_id'                 =>   $_SESSION['school_id'],
            'school_db'                 =>   $_SESSION["school_db"],
            'user_login_detail_id'      =>   $_SESSION["login_detail_id"],
            'token'                     =>   $_SESSION["token"],
	    );
        
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => base_url().'api/api_location_delete',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $data,
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response);
        
        $this->session->set_flashdata('club_updated',$result->message);
        redirect(base_url() . 'location/');
   
    }

    
}