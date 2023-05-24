<?php
//session_start();

function coa_status_list()
{

	$coa_status=array(1=>get_phrase("approval_in_process"),
    2=>get_phrase('approved'),
	3=>get_phrase('rejected'),
	4=>get_phrase('archived'));
	return $coa_status;
}



function coa_status_option($selected="")
{

   	$coa_status=coa_status_list();
    $str="<option value=''>".get_phrase('select_status')."</option>";
   	foreach($coa_status as $key=>$value)
   	{
		($key==$selected)?$sel="selected":$sel="";
		$str.="<option $sel value='$key'>$value</option>";
	}

   	return $str;


   }

function debit_credit_array()
{

    $coa_status=array(
        1=>get_phrase('Credit'),
        2=>get_phrase('Debit'),
       );

    return $coa_status;
}

function debit_credit_optoin($selected="")
{

    $array_list=debit_credit_array();

    $str="<option value=''>".get_phrase('select_account_type')."</option>";

    foreach($array_list as $key=>$value){


        ($key==$selected)?$sel="selected":$sel="";

        $str.="<option $sel value='$key'>$value</option>";

    }

    return $str;


}


function debit_credit_active_array()
{

    $coa_status1=array(
        "0"=>get_phrase('no'),
        "1"=>get_phrase('yes'),
    );
    return $coa_status1;
}

function debit_credit_active_optoin($selected1="")
{
    //echo "val==".$selected1;
    // $sel= $selected;
    $array_debit_active_list=debit_credit_active_array();
    //$str1="<option value=''>".get_phrase('select_account_type')."</option>";
    foreach($array_debit_active_list as $key1=>$value1)
    {
       // echo $key1."==".$selected1;
      //  echo "<br>";
        $sel1="";
        if ($key1 == $selected1)
        {
            $sel1="selected";
        }
        $str1.="<option $sel1 value='$key1'>$value1</option>";
    }
    return $str1;
}






  function coa_status($id=""){

  		$coa_status=coa_status_list();
  	return  $coa_status[$id];

  }


    function get_coa_balance($coa_id,$start_date,$end_date){
        $CI=& get_instance();
        $CI->load->database();
        $date_check="";
        $date_check2="";
        //echo $start_date." ( ) ".$end_date."(end)";


        if($start_date!=""  && $end_date!=""){


            $start_date1=date_slash($start_date);
            $end_date1=date_slash($end_date);
            $date_check=" (scf.fee_month_year between '$start_date1' and '$end_date1') and ";
            $date_check2="(at.date between '$start_date1' and '$end_date1') and ";
        }
        if($start_date!="" && $end_date==""){
            $start_date1=date_slash($start_date);
            $end_date1=date_slash($end_date);
            $date_check=" scf.fee_month_year >= '$start_date1' and ";
            $date_check2=" at.date >= '$start_date1' and ";
        }
        if($end_date!="" && $start_date==""){
            $start_date1=date_slash($start_date);
            $end_date1=date_slash($end_date);

            $date_check=" scf.fee_month_year <= '$end_date1' and ";
            $date_check2=" at.date <= '$end_date1' and ";
        }


        $qur="select 1 as rec_type, scf.status as status, coa.account_number, coa.account_type, coa.account_head, scf.fee_month_year as transection_date, sum(scd.amount) as total_amount, concat(scd.fee_type_title,' from ', scf.student_name) as trasection from 
            
        ".get_school_db().".chart_of_accounts coa 
        inner join ".get_school_db().".student_chalan_detail scd on scd.coa_id=coa.coa_id 
        inner join ".get_school_db().".student_chalan_form scf on scf.s_c_f_id=scd.s_c_f_id 
        where $date_check coa.coa_id=$coa_id and  scf.school_id=".$_SESSION['school_id']." 
        and scf.status in(4,5) 
        
        group by scf.status
        
        
        union 
        
        
        select 2 as rec_type, at.type as status, coa.account_number, coa.account_type, coa.account_head, at.date as transection_date, sum(at.amount) as total_amount, at.title as trasection from ".get_school_db().".account_transection at inner join ".get_school_db().".chart_of_accounts coa on coa.coa_id=at.coa_id where $date_check2 coa.coa_id=$coa_id and  at.school_id=".$_SESSION['school_id'];


        return $chart_of=$CI->db->query($qur)->result_array();




    }

function get_dab_crd($coa_id,$start_date,$end_date){
	$CI=& get_instance();
	$CI->load->database();
	$date_check="";
	$date_check2="";

    $data_ary['debit']=0;
    $data_ary['credit']=0;

	if($start_date!=""  && $end_date!=""){
    	$start_date1=date_slash($start_date);
    	$end_date1=date_slash($end_date);
        $date_check=" (scf.fee_month_year between '$start_date1' and '$end_date1') and ";
        $date_check2="(at.date between '$start_date1' and '$end_date1') and ";
    }
	if($start_date!="" && $end_date==""){
		$start_date1=date_slash($start_date);
	$end_date1=date_slash($end_date);
$date_check=" scf.fee_month_year >= '$start_date1' and ";
$date_check2=" at.date >= '$start_date1' and ";
	}
	if($end_date!="" && $start_date==""){
			$start_date1=date_slash($start_date);
	$end_date1=date_slash($end_date);

$date_check=" scf.fee_month_year <= '$end_date1' and ";
$date_check2=" at.date <= '$end_date1' and ";
	}


$qur="select 1 as rec_type, scf.status as status, coa.account_number, coa.account_type, coa.account_head, scf.fee_month_year as transection_date, sum(scd.amount) as total_amount, concat(scd.fee_type_title,' from ', scf.student_name) as trasection from 
".get_school_db().".chart_of_accounts coa inner join ".get_school_db().".student_chalan_detail scd on scd.coa_id=coa.coa_id inner join ".get_school_db().".student_chalan_form scf on scf.s_c_f_id=scd.s_c_f_id where $date_check coa.coa_id=$coa_id and  scf.school_id=".$_SESSION['school_id']." and scf.status in(4,5) 
group by scf.status
union 
select 2 as rec_type, at.type as status, coa.account_number, coa.account_type, coa.account_head, at.date as transection_date, sum(at.amount) as total_amount, at.title as trasection from ".get_school_db().".account_transection at inner join ".get_school_db().".chart_of_accounts coa on coa.coa_id=at.coa_id where $date_check2 coa.coa_id=$coa_id and  at.school_id=".$_SESSION['school_id'];


$qur_v=$CI->db->query($qur)->result_array();
if($coa_id==76){
	echo $this->db->last_query();
}

foreach($qur_v as $row){
	if($row['rec_type']==1)
	{
		if($row['status']==5){
			$debit+=$row['total_amount'];
		}

		elseif($row['status']==4){
		$credit+=$row['total_amount'];
		}
	}
elseif($row['rec_type']==2){

if($row['account_type']==1){

	$credit+=$row['total_amount'];

	}

	elseif($row['account_type']==2){
	$debit+=$row['total_amount'];
	}
}

	}

 $data_ary['debit']=$debit;
$data_ary['credit']=$credit;




	return $data_ary;

}

function get_parent_head_coa_id($setting_type='')
{
	$CI=& get_instance();
	$CI->load->database();
	
	$get_rev_exp_coa = $CI->db->query("select settings_type,coa_id from ".get_school_db().".financial_reports_settings WHERE settings_type = '$setting_type'")->row();
	return $get_rev_exp_coa->coa_id;
}

function getStudentIds($section_id=0)
{
    $CI=& get_instance();
	$CI->load->database();
    // Get Students Ids
	$get_std_ids = $CI->db->query("SELECT GROUP_CONCAT(student_id) AS sIds FROM ".get_school_db().".student WHERE section_id = '$section_id'")->row();
	return $students_Ids = ($get_std_ids->sIds) ? $get_std_ids->sIds : 0;
}

function sectionFeeSumUp($section_id,$start_date,$end_date)
{
    // 76 Should Be Dynamic in Query
    $CI=& get_instance();
	$CI->load->database();
    $s_date = date_slash($start_date);
    $e_date = date_slash($end_date);
    
    $students_Ids = getStudentIds($section_id);
	
	// Ledger Sum Amount
	$ledger_query = $CI->db->query("SELECT SUM(debit) AS total_fee FROM ".get_school_db().".journal_entry WHERE student_id IN($students_Ids) AND entry_type = 1 AND coa_id = 76 And DATE_FORMAT(entry_date, '%Y-%m-%d') BETWEEN '$s_date' And '$e_date' ")->row();
	$ledger_query_result =  $ledger_query->total_fee;
	return $ledger_query_result;
}

function sectionFeeRecovery($section_id,$start_date,$end_date)
{
    // 86 Should Be Dynamic in Query
    $CI=& get_instance();
	$CI->load->database();
    $s_date = date_slash($start_date);
    $e_date = date_slash($end_date);
    
    $students_Ids = getStudentIds($section_id);
 
	// Ledger Sum Amount
	$ledger_query = $CI->db->query("SELECT SUM(debit) AS total_recovery FROM ".get_school_db().".journal_entry WHERE student_id IN($students_Ids) AND entry_type = 3 AND coa_id = 86 And DATE_FORMAT(entry_date, '%Y-%m-%d') BETWEEN '$s_date' And '$e_date' ")->row();
	$ledger_query_result =  $ledger_query->total_recovery;
	return $ledger_query_result;
}

?>