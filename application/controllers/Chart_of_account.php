<?php
if(!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();

class Chart_of_account extends CI_Controller
{
    
  function __construct()
  {
    parent::__construct();
	if($_SESSION['accountant_login'] == 1 || $_SESSION['user_login']== 1)
	{
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->menu_ary=array();
	}
	else
    {
        redirect('login');
	}
  }
  
  function index()
  {}
  
  function chart_of_accounts($param1='')
  {
    $page_data['page_name']  = 'chart_of_accounts';
    $page_data['page_title'] = get_phrase('chart_of_accounts');
    $this->load->view('backend/index', $page_data);
  }
  
  function coa($param1 = '', $param2 = '', $param3 = '')
  {
        $school_id = $_SESSION['school_id'];
  		if($param1 == 'create')
  		{
            $data['account_head']     = $this->input->post('account_title');
            $data['account_number'] = $this->input->post('account_number');
            $data['account_type']    =  $this->input->post('account_type');
            $data['school_id']  = $_SESSION['school_id'];
            $data['status']  = $this->input->post('status');
            $data['parent_id']  = $this->input->post('parent_id');
            $data['is_active']  =  $this->input->post('is_active');
            
            $this->db->insert(get_school_db().'.chart_of_accounts', $data);
    
            $coa_id = $this->db->insert_id();
            $coa_id_temp = $coa_id;
            $data_school_coa['coa_id'] = $coa_id_temp;
            $data_school_coa['school_id'] =  $school_id;
            $this->db->insert(get_school_db().'.school_coa', $data_school_coa);
    
            $login_type = $_SESSION['login_type'];

            if($login_type == 2)
            {
                $parent_sys_sch_id = $_SESSION['parent_sys_sch_id'];
                $school_coa_str = "select school_id from ".get_school_db().".school where sys_sch_id =$parent_sys_sch_id";
                $school_coa_query = $this->db->query($school_coa_str)->row();
                $school_id_temp = $school_coa_query->school_id;
                $data_school_coa['school_id'] =  $school_id_temp;
                $this->db->insert(get_school_db().'.school_coa', $data_school_coa);
            }

            $coa_type  =  $this->input->post('coa_type');
            $data_coa_type['coa_id'] = $coa_id;
            $data_coa_type['school_id']  = $_SESSION['school_id'];
            foreach ($coa_type as $ct_key => $ct_value)
            {
              $data_coa_type['coa_type'] = $ct_value;
              $this->db->insert(get_school_db() . '.chart_of_account_types', $data_coa_type);
            }

   			$this->session->set_flashdata('club_updated',get_phrase('chart_of_account_title_saved_successfully'));	
  			redirect(base_url() . 'chart_of_account/coa_list');
  		}
  		if($param1 == 'edit')
  		{
  			$data['account_head'] = $this->input->post('account_title');
  			$data['account_number'] = $this->input->post('account_number');
  			$data['account_type'] = $this->input->post('account_type');
  			$data['school_id']  = $_SESSION['school_id'];
  			$data['status']  = $this->input->post('status');
  			$data['is_active']  =  $this->input->post('is_active');
  			//$data['status']  = $this->input->post('status');
  			$data['parent_id']  = $this->input->post('parent_id');
  			$this->db->where('coa_id',  $this->input->post('coa_id'));
            $this->db->where('school_id',$_SESSION['school_id']);
  			$this->db->update(get_school_db().'.chart_of_accounts', $data);

           $coa_type  =  $this->input->post('coa_type');

           $coa_id = $this->input->post('coa_id');
           $data_coa_type['coa_id'] = $coa_id;
           $data_coa_type['school_id']  = $_SESSION['school_id'];

           $this->db->where('coa_id', $coa_id);
           $this->db->where('school_id',$_SESSION['school_id']);
           $this->db->delete(get_school_db() .".chart_of_account_types");
           foreach ($coa_type as $ct_key => $ct_value)
           {
              $data_coa_type['coa_type'] = $ct_value;
              $this->db->insert(get_school_db() . '.chart_of_account_types', $data_coa_type);
           }

  			$this->session->set_flashdata('club_updated',get_phrase('chart_of_account_title_updated_successfully'));	
  			redirect(base_url() . 'chart_of_account/coa_list');
  		}
  		
  		if($param1 == 'delete')
        { 
          $qur_1=$this->db->query("select coa_id from ".get_school_db().".chart_of_accounts where parent_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();
          if(count($qur_1)>0)
          {
            $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_child_records_exist'));
          }
          else
          {
            $qur_s_c_f_d=$this->db->query("select s_c_d_id from ".get_school_db().".student_chalan_detail where coa_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();
            if(count($qur_s_c_f_d)>0)
            {
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_related_challan_forms_exist'));
            }
            else
            {
                $qur_journal=$this->db->query("select journal_entry_id from ".get_school_db().".journal_entry where coa_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();
                if(count($qur_journal)>0)
                {
                    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_related_journal_entries_exist'));
                }
                else
                {
                    if($login_type == 2)
                    {
                        $this->db->where('coa_id', $param2);
                        $this->db->where('school_id',$_SESSION['school_id']);
                        $this->db->delete(get_school_db().'.school_coa');
                        $this->session->set_flashdata('club_updated',get_phrase('chart_of_account_title_deleted_successfully'));
                    }
                    else
                    {
                        $this->session->set_flashdata('club_updated',get_phrase('chart_of_account_title_not_deleted'));
                    }
                }
            }
        }
        redirect(base_url() . 'chart_of_account/coa_list/');
        exit();
  		}
  }
  
  function coa_list()
  {
		$page_data['page_name']  = 'coa_list';
		$page_data['page_title']=get_phrase('chart_of_accounts_list');
		$this->chec_menu();
		$page_data['menu']=$this->menu_ary;
		$this->load->view('backend/index', $page_data);
  }
  
  function miscellaneous_settings()
  {
    $page_data['page_name']  = 'miscellaneous_settings';
    $page_data['page_title']= get_phrase('miscellaneous_settings');
    $this->chec_menu();
    $page_data['menu'] = $this->menu_ary;
    $this->load->view('backend/index', $page_data);
  }
  
  function financial_report_settings()
  {
    $page_data['page_name']  = 'financial_report_settings';
    $page_data['page_title']=get_phrase('income_statement_settings');
    $this->chec_menu();
    $page_data['menu']=$this->menu_ary;
    $this->load->view('backend/index', $page_data);
  }
  
  function chec_menu($parent_id=0)
  {
      $school_id=$_SESSION['school_id'];
      $login_type = $_SESSION['login_type'];

      $coa_rec_str = "select sc.name as school_name , coa.* from ".get_school_db().".chart_of_accounts as coa
      Inner join ".get_school_db().".school_coa as s_coa  on s_coa.coa_id = coa.coa_id
      INNER JOIN ".get_school_db().".school as sc on sc.school_id = coa.school_id
      where coa.parent_id=$parent_id
      And s_coa.school_id = $school_id";

      $coa_rec=$this->db->query($coa_rec_str)->result_array();
      $this->menu_ary[]= " <ul id= 'tree3' class='nav-list col-md-12 col-lg-12'>";

      foreach($coa_rec as $coa)
      {
        $add = "";
        $url = "";
        if (right_granted('chartofaccount_manage'))
        {
            $add = '<a  class="fla btn btn-primary btn-sm" style="padding:2px 4px 2px 4px !important;"  href="#" onclick="showAjaxModal(\'' . base_url() . 'modal/popup/add_chart_of_account/' . $coa['coa_id'] . '\')">Add Child</a>';
            if(($school_id == $coa['school_id']) || ($login_type == 1))
            {
              $url = '<a class="fle btn-warning btn-sm" style="padding:2px 4px 2px 4px !important;color:white !important" href="#" onclick="showAjaxModal(\'' . base_url() . 'modal/popup/modal_coa_edit/' . $coa['coa_id'] . '\')" >Edit</a>  ';

            }
        }
        $delete = "";
        if (right_granted('chartofaccount_delete'))
          {
            if(($school_id == $coa['school_id']) || ($login_type == 1))
              {
                $delete = '<a class="fld btn-danger btn-sm" style="padding:2px 4px 2px 4px !important;color:white !important" href="#" onclick="confirm_modal(\'' . base_url() . 'chart_of_account/coa/delete/' . $coa['coa_id'] . '\')">Delete</a>';
              }
        }
        if($coa['is_active'] == 0) {
            $is_active = ' <span class="orange space">('.get_phrase('inactive').')</span>';
        } 
        else {
            $is_active = ' <span class="green space">('.get_phrase('active').')</span>';
        }
        $account_type = "";

        if($coa['account_type'] == 2)
        {
            $account_type ='<span class="green space">('.get_phrase('debit').')</span>';
        } 
        elseif ($coa['account_type'] == 1)
        {
            $account_type = '<span class="orange space">('.get_phrase('credit').')</span>';
        }
        $school_info = "";
        if($school_id != $coa['school_id'] && $login_type == 1)
        {
            $school_info = " - ".$coa['school_name'];
        }
        $click='onclick="active(\''.$coa['coa_id'].'\')"';
        $this->menu_ary[]="<li $click class='act".$coa['coa_id']."'>".$coa['account_head']." <i class='fas fa-arrow-right' aria-hidden='true'></i>".$coa['account_number'].$account_type.$is_active."$url  $delete $add".$school_info;

        $coa_rec1_str = "select coa.coa_id from ".get_school_db().".chart_of_accounts as coa Inner join ".get_school_db().".school_coa as s_coa on s_coa.coa_id = coa.coa_id
                         INNER JOIN ".get_school_db().".school as sc on sc.school_id = coa.school_id where s_coa.school_id=$school_id and coa.parent_id=".$coa['coa_id']."";
        $coa_rec1=$this->db->query($coa_rec1_str)->result_array();
        if(count($coa_rec1)>0)
        {
            $this->chec_menu($coa['coa_id']);
        }
        $this->menu_ary[]= "</li>";
      }
      $this->menu_ary[]= "</ul>";

  }
  
  function class_level()
  {
    $this->chec_menu();
  }
  
  /*
  function chalan_account($action="", $id="")
  {
  	if($action=="add_edit"){	
    $data['title']=$this->input->post('title');
    $c_a_id=$this->input->post('c_a_id');
    $data['order_num']=$this->input->post('order_num');
    $data['status']=$this->input->post('status');
    $data['coa_id']=$this->input->post('coa_id');
    $school_id=$_SESSION['school_id'];
    if($c_a_id!="")
    {
  	
  	   $this->db->where('c_a_id',$c_a_id);
  	   $this->db->where('school_id',$school_id);
  	   $this->db->update(get_school_db().'.chalan_accounts',$data);
  	   $this->session->set_flashdata('club_updated','Record Editeted Successfully');	
    }
    else
    {
  	   $data['school_id']=$school_id;
  	   $this->db->insert(get_school_db().'.chalan_accounts',$data);
       $this->session->set_flashdata('club_updated','Record save Successfully');
    }
    redirect(base_url() . 'chart_of_account/chalan_account/');
    }
    if($action=='delete')
    {
        $this->db->where('c_a_id', $id);
        $this->db->delete(get_school_db().'.chalan_accounts');
        $this->session->set_flashdata('club_updated','Record deleted');
        redirect(base_url() . 'chart_of_account/chalan_account/');
    }
    $page_data['page_name']  = '../accountant/chalan_setting_listing';
    $page_data['page_title'] = get_phrase('chalan_account_setting');
    $this->load->view('backend/index', $page_data);
  }
  */
  function coa_status_list()
  {
  	$coa_status=array(
  	0=>get_phrase('waiting_for_approval'),
  	1=>get_phrase('approved'),
  	2=>get_phrase('rejected'),
  	3=>get_phrase('archived')
  	);
  	return $coa_status;
  }
  function coa_status_option($selected="")
  {
    $coa_status=$this->coa_status_list();
   	$str="<option>".get_phrase('select')."</option>";
   	foreach($coa_status as $key=>$value)
    {
		    ($key==$selected)?$sel="selected":$sel="";
        $str.="<option $sel value='$key'>$value</option>";
    }
    return $str;
  }
  
  function coa_status($id="")
  {
  	$coa_status=$this->coa_status_list();
  	return  $coa_status[$id];
  } 
  
  function account_number_val($coa_id=0)
  {
      
     $account_number=$this->input->post('account_number');
     if($coa_id == 0)
     {
         $val_val=$this->db->query("SELECT coa_id FROM ".get_school_db().".chart_of_accounts WHERE account_number='$account_number'")->result_array();
     }
     else
     {
         $val_val=$this->db->query("SELECT coa_id FROM ".get_school_db().".chart_of_accounts WHERE account_number='$account_number' and coa_id != $coa_id")->result_array();
     }
     if(count($val_val)>0)
     {
         echo "no";
     }
     else
     {
        echo "yes";
     }
     
  }
  
  function save_arrears()
  {
      $data['generate_dr_coa_id']=$this->input->post('generate_dr_coa_id');
      $data['generate_cr_coa_id']=$this->input->post('generate_cr_coa_id');

      /*$data['issue_dr_coa_id']=$this->input->post('issue_dr_coa_id');
		  $data['issue_cr_coa_id']=$this->input->post('issue_cr_coa_id');*/
      $data['receive_dr_coa_id']=$this->input->post('receive_dr_coa_id');
      $data['receive_cr_coa_id']=$this->input->post('receive_cr_coa_id');
      /*$data['cancel_dr_coa_id']=$this->input->post('cancel_dr_coa_id');
      $data['cancel_cr_coa_id']=$this->input->post('cancel_cr_coa_id');*/
      $data['school_id']=$_SESSION['school_id'];
      //$misc_id=$this->input->post('misc_id');

      $data['type']='arrears_coa';
      // $data['type'] = $this->input->post('account_type');
      $data['status']=1;
      $query = $this->db->query("SELECT misc_settings_id from  ".get_school_db().".misc_challan_coa_settings where type = '".$data['type']."' and school_id = ".$_SESSION['school_id']."");
      $row = $query->row();
      if(count($row)== 0)
      {
        $this->db->insert(get_school_db().'.misc_challan_coa_settings',$data);
      }
      else
      {
        $this->db->where('type',$data['type']);
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->update(get_school_db().'.misc_challan_coa_settings',$data);
      }
  }
  function save_arrears1()
  {
      /*$data_fee['issue_dr_coa_id']=$this->input->post('issue_dr_coa_id_1');
      $data_fee['issue_cr_coa_id']=$this->input->post('issue_cr_coa_id_1');*/
      $data_fee['receive_dr_coa_id']=$this->input->post('receive_dr_coa_id_1');
      $data_fee['receive_cr_coa_id']=$this->input->post('receive_cr_coa_id_1');

      /* $data_fee['cancel_dr_coa_id']=$this->input->post('cancel_dr_coa_id_1');
      $data_fee['cancel_cr_coa_id']=$this->input->post('cancel_cr_coa_id_1');*/
      $data_fee['school_id']=$_SESSION['school_id'];
      //$misc_id=$this->input->post('misc_id');
      $data_fee['type']='late_fee_fine_coa';
      // $data['type'] = $this->input->post('account_type');
      $data_fee['status']=1;
      $query_fee = $this->db->query( "SELECT * from  ".get_school_db().".misc_challan_coa_settings 
        where type = '".$data_fee['type']."' and school_id = ".$_SESSION['school_id']."");
      $row_fee = $query_fee->row();
      if(count($row_fee) == 0)
      {
        $this->db->insert(get_school_db().'.misc_challan_coa_settings',$data_fee);
        //echo "inserted";
      }else
      {
        $this->db->where('type',$data_fee['type']);	
        $this->db->where('school_id',$_SESSION['school_id']);	
        $this->db->update(get_school_db().'.misc_challan_coa_settings',$data_fee);	
        //echo "updated";
      }
  }
  function save_income_statement()
  {
    $coa_id = 0;
    /*$data_income['income_stmt_sales']=$this->input->post('income_stmt_sales');
    $data_income['income_stmt_income']=$this->input->post('income_stmt_income');
    $data_income['income_stmt_expense']=$this->input->post('income_stmt_expense');*/
    $income_stmt_sales = "income_stmt_sales";
    $income_stmt_expense = "income_stmt_expense";

    $income_id_sales=$this->input->post('income_id_sales');
    $income_id_expense=$this->input->post('income_id_expense');

    /*$income_stmt_sales =$this->input->post('income_stmt_sales');
    $income_stmt_income=$this->input->post('income_stmt_income');
    $income_stmt_expense=$this->input->post('income_stmt_expense');*/

    $school_id=$_SESSION['school_id'];

    $data_income_array = array
    (
      array("setting_type"=>$income_stmt_sales,'coa_id'=>$income_id_sales),
      array("setting_type"=>$income_stmt_expense,'coa_id'=>$income_id_expense)
    );

    foreach($data_income_array as $key => $value)
    {
      $income_stmt = $data_income_array[$key]['setting_type'];
      $coa_id = $data_income_array[$key]['coa_id'];
      // $data_income[$income_stmt] = $income_stmt;
      $query_income_str = "SELECT * from  " . get_school_db() . ".financial_reports_settings where settings_type = '" . $income_stmt . "' and school_id = " . $_SESSION['school_id'] . "";
      $query_income = $this->db->query($query_income_str)->num_rows();
      if ($query_income == 0)
      {
          $insert_str = "INSERT INTO " . get_school_db() . ".financial_reports_settings (settings_type , coa_id ,school_id) VALUES ('".$income_stmt."',$coa_id , $school_id)";
          $this->db->query($insert_str);
          echo "inserted";
      }
      else
      {
          $update_str = "UPDATE  " . get_school_db() . ".financial_reports_settings SET  settings_type = '".$income_stmt."',coa_id = $coa_id WHERE settings_type = '".$income_stmt."' AND school_id = $school_id";
          $this->db->query($update_str);
          echo "updated";
      }
    }
  }
  function save_balance_sheet()
  {
      $coa_id = 0;
      $balance_sheet_assets = "balance_sheet_assets";
      $balance_sheet_liabilities = "balance_sheet_liabilities";
      $balance_sheet_capital = "balance_sheet_capital";

      $balance_sheet_id_assets=$this->input->post('balance_sheet_id_assets');
      $balance_sheet_id_liabilities=$this->input->post('balance_sheet_id_liabilities');
      $balance_sheet_id_capital=$this->input->post('balance_sheet_id_capital');

      /*$balance_sheet_assets =$this->input->post('balance_sheet_assets');
      $balance_sheet_liabilities=$this->input->post('balance_sheet_liabilities');
      $balance_sheet_capital=$this->input->post('balance_sheet_capital');*/

      $school_id=$_SESSION['school_id'];

      $data_income_array = array
      (
          array("settings_type"=>$balance_sheet_assets,'coa_id'=>$balance_sheet_id_assets),
          array("settings_type"=>$balance_sheet_liabilities,'coa_id'=>$balance_sheet_id_liabilities),
          array("settings_type"=>$balance_sheet_capital,'coa_id'=>$balance_sheet_id_capital)
      );

      foreach($data_income_array as $key => $value)
      {
          $balance_sheet_stmt = $data_income_array[$key]['settings_type'];
          $coa_id = $data_income_array[$key]['coa_id'];
          // $data_income[$income_stmt] = $income_stmt;

          $query_income_str = "SELECT * from  " . get_school_db() . ".financial_reports_settings where settings_type = '" . $balance_sheet_stmt . "' 
      					and school_id = " . $_SESSION['school_id'] . "";
          $query_income = $this->db->query($query_income_str)->num_rows();

          if ($query_income == 0)
          {
            $insert_str = "INSERT INTO " . get_school_db() . ".financial_reports_settings (settings_type , coa_id ,school_id) VALUES ('".$balance_sheet_stmt."',$coa_id , $school_id)";
            $this->db->query($insert_str);
          }
          else
          {
            $update_str = "UPDATE  " . get_school_db() . ".financial_reports_settings SET  settings_type = '".$balance_sheet_stmt."',coa_id = $coa_id WHERE settings_type = '".$balance_sheet_stmt."' AND school_id = $school_id";
            $this->db->query($update_str);
            echo "updated";
          }
      }
  }
  function test()
  {
    echo "hello";
  }
}