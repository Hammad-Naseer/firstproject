<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Data_import extends CI_Controller
{
function __construct()
    {
        parent::__construct();
		$this->load->database();
        /*cache control*/
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
    }
    
  
   public function index()
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'data_import/data_add');
    }
    
 
    /****MANAGE STUDENTS CLASSWISE*****/
			function data_add()
			{
				if ($_SESSION['user_login'] != 1)
				redirect(base_url());

				$page_data['page_name']  = 'data_import/data_add';
				$page_data['page_title'] = get_phrase('data import');
				$this->load->view('backend/index', $page_data);
			}

			function upload(){
			$this->load->helper('import_data');
			if ($_SESSION['user_login'] != 1)
			redirect('login');

				if(move_uploaded_file($_FILES['upload_csv']['tmp_name'], 'uploads/csv/' . $_FILES['upload_csv']['name'])){

					$page_data['file_name'] =$_FILES['upload_csv']['name'];
					$page_data['page_name']  = 'data_import/read_csv';
					$page_data['page_title'] = get_phrase('CSV');
					$this->load->view('backend/index', $page_data);


				}    
			else{ $this->session->set_flashdata('mark_message', get_phrase('file_could_not_be_uploaded'));} 


			}
	
	function read_csv($file){
	if($_SESSION['user_login'] != 1)
	redirect('login');
	if(isset($_POST['submit'])){
		$colArry=$_POST['col'];
		foreach($_POST['id'] as $key=>$value){
			$selectedKey=0;
			
			if($key=='selected'){
				$count=0;
				for($i=0;$i<sizeof($value);$i++){
					$col=array();
					
					$arryNum=$value;
					$resArr=$_POST['id'][$arryNum[$i]];	
					foreach($colArry as $k=>$v){
						$newArry[$v][]=$resArr[$k];
					}
					
					foreach($newArry as $key=>$val){
						$col[]=$key;
						$rec[]=implode(' ',$val);
					}
					$newArry=array();
					
					$query="insert into ".get_school_db().".teacher (".implode(",",$col).") values ('".implode("','",$rec)."')";
					$routine=$this->db->query($query);
					$count++;
				$rec=array();}
				
				if($routine){
				//	echo $count. " record(s) Inserted";
			 $this->session->set_flashdata('flash_message',$count."_record_inserted");	
			if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'data_import/data_add');
				}
				else{
				if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'data_import/data_add');	
				}
			
			}

		}
	}
		
		
	}
	

        
}