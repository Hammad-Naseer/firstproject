<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class chart_of_account extends CI_Model {


 //$menu_ary=array();

function __construct()
{
    	parent::__construct();
    	
    	
    	$this->menu_ary=array();
}
    
function rec_parent($parent_id=0){
	

	$school_id=$_SESSION['school_id'];	
$coa_rec=$this->db->query("select * from ".get_school_db().".chart_of_accounts where parent_id=$parent_id and school_id=$school_id and status=1")->result_array();




foreach($coa_rec as $coa){


$this->menu_ary[$coa['coa_id']]=$coa['account_head'];

$coa_rec1=$this->db->query("select * from ".get_school_db().".chart_of_accounts where school_id=$school_id and parent_id=".$coa['coa_id']." and status=1")->result_array();

if(count($coa_rec1)>0){
$this->rec_parent($coa['coa_id']);


}
}

}

function rec_parent1($coa_id,$val_emp){
if($val_emp==0){
$this->menu_ary=array();	
}
$this->rec_parent($coa_id);
return $this->menu_ary;
}

}

