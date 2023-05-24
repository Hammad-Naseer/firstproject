<?php if(!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();

class Departments extends CI_Controller {


function __construct(){
parent::__construct();

if($_SESSION['user_login'] != 1)
  redirect('login');

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->menu_ary=array();
	}


function departments_listing($action="", $id=""){
if($action=="add_edit"){
					
$data['title']=$this->input->post('title');
$departments_id=$this->input->post('departments_id');
$data['short_name']=$this->input->post('short_name');
$data['discription']=$this->input->post('discription');
$data['department_head']=$this->input->post('department_head');
//$data['order_num']=$this->input->post('order_num');
$school_id=$_SESSION['school_id'];

if($departments_id!="")
{
	$this->db->where('departments_id',$departments_id);
	$this->db->where('school_id',$school_id);
	$this->db->update(get_school_db().'.departments',$data);
	$this->session->set_flashdata('club_updated',get_phrase('department_updated_successfully'));	
}
else
{
	$data['school_id']=$school_id;
	$this->db->insert(get_school_db().'.departments',$data);
	$this->session->set_flashdata('club_updated',get_phrase('department_saved_successfully'));
}

redirect(base_url() . 'departments/departments_listing/');
			
}
		
if($action=='delete'){
	
$school_id=$_SESSION['school_id'];

$qur_delete=$this->db->query("select departments_id from ".get_school_db().".class where departments_id=$id and school_id=$school_id")->result_array();

if(count($qur_delete)>0){
	
	$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
	
}else{
$delete_ary=array('school_id'=>$school_id,'departments_id'=>$id);
$this->db->where($delete_ary);
$this->db->delete(get_school_db().'.departments');
$this->session->set_flashdata('club_updated',get_phrase('department_deleted_successfully'));	

	
}


redirect(base_url().'departments/departments_listing');	
	
}

$page_data['page_name']  = 'departments_listing';
$page_data['page_title'] = get_phrase('departments');
$this->load->view('backend/index', $page_data);	

}

function departments_new($action="", $id=""){
		
if($action=="add_edit"){
					
$data['title']=$this->input->post('title');
$departments_id=$this->input->post('departments_id');
$data['short_name']=$this->input->post('short_name');
$data['discription']=$this->input->post('discription');
$data['department_head']=$this->input->post('department_head');
//$data['order_num']=$this->input->post('order_num');
$school_id=$_SESSION['school_id'];
if($departments_id!=""){
	$this->db->where('departments_id',$departments_id);
	$this->db->where('school_id',$school_id);
	$this->db->update(get_school_db().'.departments',$data);
	$this->session->set_flashdata('club_updated',get_phrase('department_updated_successfully'));	
}
else{
	
	$data['school_id']=$school_id;
	$this->db->insert(get_school_db().'.departments',$data);
    $this->session->set_flashdata('club_updated',get_phrase('department_saved_successfully'));
	
}
redirect(base_url() . 'departments/departments_new/');
			
}
		
if($action=='delete'){
	
$school_id=$_SESSION['school_id'];

$qur_delete=$this->db->query("select departments_id from ".get_school_db().".class where departments_id=$id and school_id=$school_id")->result_array();

if(count($qur_delete)>0){
	
	$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
	
}else{
$delete_ary=array('school_id'=>$school_id,'departments_id'=>$id);
$this->db->where($delete_ary);
$this->db->delete(get_school_db().'.departments');
$this->session->set_flashdata('club_updated',get_phrase('department_deleted_successfully'));	

	
}


redirect(base_url().'departments/departments_new');	
	
}

$page_data['page_name']  = 'departments_listing';
$page_data['page_title'] = get_phrase('departments');
$this->load->view('backend/index', $page_data);	

}

function classes($param1 = '', $param2 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url());      
      
        if ($param1 == 'create') {
            $data['name']         = $this->input->post('name');
			$data['name_numeric'] = $this->input->post('name_numeric');
			$data['departments_id'] = $this->input->post('departments_id');
			$data['description'] = $this->input->post('description');
		    //	$data['order_by'] = $this->input->post('order_by');
            $data['strength']   = $this->input->post('strength');
            $data['teacher_id']   = $this->input->post('teacher_id');
            $data['school_id'] = $_SESSION['school_id'];
            $this->db->insert(get_school_db().'.class', $data);
            $this->session->set_flashdata('club_updated',get_phrase('class_saved_successfully'));
            redirect(base_url() . 'departments/classes/');
        }
        if ($param1 == 'do_update') {
            $data['name']         = $this->input->post('name');
        //        $data['order_by'] = $this->input->post('order_by');
            $data['name_numeric'] = $this->input->post('name_numeric');
			$data['departments_id'] = $this->input->post('departments_id');
			$data['strength']   = $this->input->post('strength');
			$data['description'] = $this->input->post('description');
            $data['teacher_id']   = $this->input->post('teacher_id');
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('class_id', $param2);
            $this->db->update(get_school_db().'.class', $data);
 			$this->session->set_flashdata('club_updated',get_phrase('class_updated_successfully'));	
            redirect(base_url() . 'departments/classes/');
        }else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where(get_school_db().'.class', array(
                'class_id' => $param2,
                'school_id' =>$_SESSION['school_id']
            ))->result_array();
        }
        if ($param1 == 'delete') 
		{
        	$school_id=$_SESSION['school_id'];
			$qur_delete=$this->db->query("select class_id from ".get_school_db().".class_section where class_id=$param2 and school_id=$school_id")->result_array();
            if(count($qur_delete)>0){
            	$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
            }else{
                $this->db->where('school_id',$_SESSION['school_id']);
                $this->db->where('class_id', $param2);
                $this->db->delete(get_school_db().'.class');
                $this->session->set_flashdata('club_updated',get_phrase('class_deleted_successfully'));
            }       
            redirect(base_url() . 'departments/classes/');
        }
        //$search_ary=array('school_id'=>$_SESSION['school_id']);
        $department_id=$this->input->post('department_id');
        
        if(isset($department_id) && $department_id!=""){
            $search_ary=" and departments_id=".$department_id."";
        }
        $q="SELECT c.*,c.order_by as order_by,d.title as designation,d.is_teacher as is_teacher,s.name as staff_name FROM ".get_school_db().".class c 
        LEFT JOIN ".get_school_db().".staff s ON c.teacher_id=s.staff_id
        LEFT JOIN ".get_school_db().".designation d ON (s.designation_id=d.designation_id and d.is_teacher=1) 
        where c.school_id=".$_SESSION['school_id']." $search_ary";
        $class=$this->db->query($q)->result_array();
        $page_data['class']    = $class;
        $page_data['page_name']  = 'class';
        $page_data['page_title'] = get_phrase('manage_class');

        $this->load->view('backend/index', $page_data);
    }
    


function class_new($param1 = '', $param2 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url());      
      
        if ($param1 == 'create') {
            $data['name']         = $this->input->post('name');
			$data['name_numeric'] = $this->input->post('name_numeric');
			$data['departments_id'] = $this->input->post('departments_id');
			$data['description'] = $this->input->post('description');
            $data['teacher_id']   = $this->input->post('teacher_id');
            $data['school_id'] = $_SESSION['school_id'];
            $this->db->insert(get_school_db().'.class', $data);
            $this->session->set_flashdata('club_updated',get_phrase('class_saved_successfully'));
            redirect(base_url() . 'departments/departments_listing/');
        }
        if ($param1 == 'do_update') {
            $data['name']         = $this->input->post('name');
   
            $data['name_numeric'] = $this->input->post('name_numeric');
            
$data['departments_id'] = $this->input->post('departments_id');
$data['description'] = $this->input->post('description');
            $data['teacher_id']   = $this->input->post('teacher_id');
            
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('class_id', $param2);
            $this->db->update(get_school_db().'.class', $data);
            
            
 $this->session->set_flashdata('club_updated',get_phrase('class_updated_successfully'));	
            redirect(base_url() . 'departments/departments_listing/');
        } 
        
        
        else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where(get_school_db().'.class', array(
                'class_id' => $param2,
                'school_id' =>$_SESSION['school_id']
            ))->result_array();
        }
        if ($param1 == 'delete') {
        	
        	$school_id=$_SESSION['school_id'];

$qur_delete=$this->db->query("select * from ".get_school_db().".class_section where class_id=$param2 and school_id=$school_id")->result_array();

if(count($qur_delete)>0){
	
	$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
	
}else{
        	
$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('class_id', $param2);
            $this->db->delete(get_school_db().'.class');
           $this->session->set_flashdata('club_updated',get_phrase('section_deleted_successfully'));
           
    }       

redirect(base_url() . 'departments/departments_listing/');
        }

 redirect(base_url() . 'departments/departments_listing/');  
    }
    
function section_listing($action="", $id=""){
		
if($action=="add_edit"){			
    $data['title']=$this->input->post('title');
    $section_id=$this->input->post('section_id');
    $data['class_id']=$this->input->post('class_id');
    $data['short_name']=$this->input->post('short_name');
    $data['discription']=$this->input->post('discription');
    $data['teacher_id']=$this->input->post('teacher_id');
    //$data['order_num']=$this->input->post('order_num');
    $data['status']=$this->input->post('status');
    $school_id=$_SESSION['school_id'];

    if($section_id!=""){
    	$this->db->where('section_id',$section_id);
    	$this->db->where('school_id',$school_id);
    	$this->db->update(get_school_db().'.class_section',$data);
        $this->session->set_flashdata('club_updated',get_phrase('section_updated_successfully'));	
    }else{
    	$data['school_id']=$school_id;
    	$this->db->insert(get_school_db().'.class_section',$data);
        $this->session->set_flashdata('club_updated',get_phrase('section_saved_successfully'));
    }
    redirect(base_url() . 'departments/section_listing/');
}
		
if($action=='delete'){

    $qur_2=$this->db->query("select section_id from ".get_school_db().".circular where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();
    if(count($qur_2)>0){
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use_in_circulars'));	
    redirect(base_url().'departments/section_listing');	
    exit();
}

$qur_3=$this->db->query("select section_id from ".get_school_db().".class_chalan_form where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();
if(count($qur_3)>0){
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use_in_challan_forms'));	
    redirect(base_url().'departments/section_listing');	
    exit();
}
$qur_4=$this->db->query("select section_id from ".get_school_db().".class_routine_settings where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();
if(count($qur_4)>0){
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use_in_time_table'));	
    redirect(base_url().'departments/section_listing');	
    exit();
}
	
$qur_5=$this->db->query("select section_id from ".get_school_db().".diary where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();
if(count($qur_5)>0)
{
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use_in_diary'));	
    redirect(base_url().'departments/section_listing');	
    exit();
}
	
$qur_7=$this->db->query("select section_id from ".get_school_db().".exam_routine where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();

if(count($qur_7)>0){
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use_in_datesheet'));	
    redirect(base_url().'departments/section_listing');	
    exit();
}
	
$qur_8=$this->db->query("select section_id from ".get_school_db().".student where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();

if(count($qur_8)>0){
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use_in_students'));
    redirect(base_url().'departments/section_listing');	
    exit();
}

$qur_10=$this->db->query("select section_id from ".get_school_db().".subject_section where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();
if(count($qur_10)>0){
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use_in_subjects'));	
    redirect(base_url().'departments/section_listing');	
    exit();
}

	
    $school_id=$_SESSION['school_id'];
    $delete_ary=array('school_id'=>$school_id,'section_id'=>$id);
    $this->db->where($delete_ary);
    $this->db->delete(get_school_db().'.class_section');
    $this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));	
    
    redirect(base_url().'departments/section_listing');	
	
}
    
    $school_id=$_SESSION['school_id'];

    $que_listing =   "select cs.*,c.name as class_name, d.title as dpp_title , t.name as teacher_name,cs.discription,ds.title as designation,ds.is_teacher as is_teacher from
    ".get_school_db().".class_section cs inner join ".get_school_db().".class c on cs.class_id=c.class_id left join ".get_school_db().".staff t on t.staff_id=cs.teacher_id inner join ".get_school_db().".departments d on c.departments_id=d.departments_id LEFT JOIN ".get_school_db().".designation ds ON t.designation_id=ds.designation_id where cs.school_id=$school_id order by cs.section_id ASC";
    $department_id=$this->input->post('department_id'); 
    $class_id=$this->input->post('class_id');
    
    if(isset($department_id) && $department_id!=""){
        $que_listing =   "select cs.*,c.name as class_name, d.title as dpp_title , t.name as teacher_name,cs.discription from
        ".get_school_db().".class_section cs  
        inner join ".get_school_db().".class c on cs.class_id=c.class_id 
        left join ".get_school_db().".staff t on t.staff_id=cs.teacher_id 
        inner join ".get_school_db().".departments d on c.departments_id=d.departments_id
        where cs.school_id=$school_id and d.departments_id=$department_id order by d.departments_id ASC";
    }
    
    if(isset($class_id) && $class_id!=""){
        $que_listing =   "select cs.*,c.name as class_name, d.title as dpp_title , t.name as teacher_name,cs.discription from
        ".get_school_db().".class_section cs  
        inner join ".get_school_db().".class c on cs.class_id=c.class_id 
        left join ".get_school_db().".staff t on t.staff_id=cs.teacher_id 
        inner join ".get_school_db().".departments d on c.departments_id=d.departments_id
        where cs.school_id=$school_id and c.class_id=$class_id order by d.departments_id ASC,c.class_id ASC";
    }
      
    $page_data['students'] = $this->db->query($que_listing)->result_array();

    $page_data['page_name']  = 'section_listing';
    $page_data['page_title'] = get_phrase('departments');
    $this->load->view('backend/index', $page_data);	

}

function section_new($action="", $id=""){
		
if($action=="add_edit"){
$data['title']=$this->input->post('title');
$section_id=$this->input->post('section_id');
$data['class_id']=$this->input->post('class_id');
$data['short_name']=$this->input->post('short_name');
$data['discription']=$this->input->post('discription');
$data['teacher_id']=$this->input->post('teacher_id');
$data['status']=$this->input->post('status');
$school_id=$_SESSION['school_id'];


if($section_id!=""){
	$this->db->where('section_id',$section_id);
	$this->db->where('school_id',$school_id);
	$this->db->update(get_school_db().'.class_section',$data);
    $this->session->set_flashdata('club_updated',get_phrase('section_updated_successfully'));	
}
else{
	$data['school_id']=$school_id;
	$this->db->insert(get_school_db().'.class_section',$data);
    $this->session->set_flashdata('club_updated',get_phrase('section_saved_successfully'));
}
redirect(base_url() . 'departments/departments_listing/');
			
}
		
if($action=='delete'){
/*
$qur_1=$this->db->query("select * from ".get_school_db().".academic_planner where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();
if(count($qur_1)>0){
$this->session->set_flashdata('club_updated','Deletion Failed. Record Already In Use');	
redirect(base_url().'departments/departments_listing');	
//exit();
}
*/

$qur_2=$this->db->query("select section_id from ".get_school_db().".circular where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();

if(count($qur_2)>0){
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
    redirect(base_url().'departments/departments_listing');	
    exit();
}

$qur_3=$this->db->query("select section_id from ".get_school_db().".class_chalan_form where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();

if(count($qur_3)>0){
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
    redirect(base_url().'departments/departments_listing');	
    exit();
}

$qur_4=$this->db->query("select section_id from ".get_school_db().".class_routine_settings where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();

if(count($qur_4)>0){
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
    redirect(base_url().'departments/departments_listing');	
    exit();
}
	
$qur_5=$this->db->query("select section_id from ".get_school_db().".diary where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();

if(count($qur_5)>0){
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
    redirect(base_url().'departments/departments_listing');	
    exit();
}
	
/*
$qur_6=$this->db->query("select * from ".get_school_db().".discount_list where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();

if(count($qur_6)>0){
$this->session->set_flashdata('club_updated','Deletion Failed. Record Already In Use');	
redirect(base_url().'departments/section_listing');	
exit();
	
}
	*/	
	
	
$qur_7=$this->db->query("select section_id from ".get_school_db().".exam_routine where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();

if(count($qur_7)>0){
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
    redirect(base_url().'departments/departments_listing');	
    exit();
}
	
$qur_8=$this->db->query("select section_id from ".get_school_db().".student where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();

if(count($qur_8)>0){
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
    redirect(base_url().'departments/departments_listing');	
    exit();
}

$qur_10=$this->db->query("select section_id from ".get_school_db().".subject_section where section_id=$id and school_id=".$_SESSION['school_id'])->result_array();

if(count($qur_10)>0){
    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
    redirect(base_url().'departments/departments_listing');	
    exit();
}

$school_id=$_SESSION['school_id'];
$delete_ary=array('school_id'=>$school_id,'section_id'=>$id);
$this->db->where($delete_ary);
$this->db->delete(get_school_db().'.class_section');
$this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));	

redirect(base_url().'departments/departments_listing');	
	
}

redirect(base_url().'departments/departments_listing');	

}

function get_class()
{
    $departments_id = $this->input->post('departments_id');
    $clscomp_id=$this->input->post('clscomp_id');
    $qur_name=$this->db->query("select class_id,name from ".get_school_db().".class where departments_id='$departments_id'")->result_array();
    
    echo "<option value=''>".get_phrase('select_class')."</option>";
    foreach($qur_name as $rows){
        if($clscomp_id==$rows['class_id']){
            $slc="selected";	
        }else{
    	    $slc="";
        }
        echo "<option  $slc value='".$rows['class_id']."'>".$rows['name']."</option>";
    }	
}

function get_class_for_event()
{
    $departments_id = implode("','",$this->input->post('departments_id'));
    // echo "select * from ".get_school_db().".class where departments_id  IN('$departments_id')";
    $qur_name=$this->db->query("select * from ".get_school_db().".class where departments_id  IN('$departments_id')")->result_array();
    echo "<div class='form-group'> <table class='table'>";
    foreach($qur_name as $rows){
    ?>
        <tr>
            <td><?php echo $rows['name'];  ?></td>
            <td><input type='checkbox' name='classess[]' class='sectionss' value=' <?php echo $rows['class_id'] ?>'></td>
        </tr>
    <?php
    }
    echo '</table><br><button type="button" class="modal_save_btn fetch_sections">Fetch Sections</button>
        <script>
            $(".fetch_sections").click(function(){
                var test = new Array();
                $(".sectionss:checked").each(function() {
                    test.push($(this).val());
                });
                //$("#secs_id1").after("<div id="loader1" class="loader_small"></div>");
                $.ajax({
                    type: "POST",
                    data: {class_id:test},
                    url: "'.base_url().'departments/get_section_for_event",
                    success: function(response)
                    {
                    console.log(response);
                        $(".sections_data").html(response);
                    }
                });
            });
        </script>
    </div><div class="form-group"><h3 id="secs_id1">Select Sections</h3><br><div class="sections_data"></div></div>';
}

function get_section_for_event()
{
    $class_id = implode("','",$this->input->post('class_id'));

    $qur_name = $this->db->query("select section_id,title from ".get_school_db().".class_section where class_id  IN('$class_id')")->result_array();
 
    $data = "<div class='form-group'> <table class='table'>";
    foreach($qur_name as $rows){
        $data .= "<tr><td>".$rows['title']."</td><td><input type='checkbox' name='sectionss[]' class='sectionss' value=".$rows['section_id']."></td></tr>";
    }
     $data .= "</table></div>";
     
     echo $data;
}

function get_sections()
{
    $class_id = $this->input->post('class_id');
    $qur_name=$this->db->query("select section_id,title from ".get_school_db().".class_section where class_id='$class_id'")->result_array();
    echo "<option value=''>".get_phrase('select_section')."</option>";
    foreach($qur_name as $rows){
        echo "<option value='".$rows['section_id']."'>".$rows['title']."</option>";
    }	
}


function get_class_r(){
$departments_id=$this->input->post('department_id');	
$class_id=$this->input->post('class_id');	

$qur_name=$this->db->query("select * from ".get_school_db().".class where departments_id='$departments_id' and school_id=".$_SESSION['school_id'])->result_array();

echo $this->db->last_query();
echo "<option value=''>".get_phrase('select_class')."</option>";

foreach($qur_name as $rows){
	
if($class_id==$rows['class_id']){
   $selected="selected";	
}
else{
	$selected=" ";	
}	
	
	

echo "<option 

$selected

 $slc value='".$rows['class_id']."'>".$rows['name']."</option>";
	
}

}
function sec_subj_generator($param1 = '', $param2 = '',$param3 = '')
{
	$page_data['section_id']=$param1;
	$this->load->view('backend/admin/ajax/get_section_subject.php',$page_data);
}

function department_generator()
{
	$page_data['section_id']=$this->input->post('section_id');
	$this->load->view('backend/admin/ajax/accordion_department.php',$page_data);
}

}