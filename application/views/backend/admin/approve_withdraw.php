<?php 
$quer="select  s.*,s.student_status as student_status 
, cs.title as section_name, cc.name as class_name, d.title as department_name,sw.status as status,sw.s_c_f_id as s_c_f_id,chf.status as challan_status,sw.confirm_by,sw.confirm_date,sw.requested_by,sw.request_date,sw.std_withdraw_id
from ".get_school_db().".student s 
inner join ".get_school_db().".student_withdrawal sw
ON s.student_id=sw.student_id
inner join ".get_school_db().".student_chalan_form chf
ON sw.s_c_f_id=chf.s_c_f_id
inner join ".get_school_db().".class_section cs on cs.section_id=s.section_id
inner join ".get_school_db().".class cc on cc.class_id=cs.class_id 
inner join ".get_school_db().".departments d on d.departments_id=cc.departments_id 
where  s.school_id=".$_SESSION['school_id']." 
and chf.is_cancelled = 0
AND sw.std_withdraw_id=$param2";


/*$quer="select  s.* 

 
, cs.title as section_name, cc.name as class_name, d.title as department_name

from ".get_school_db().".student s 
inner join ".get_school_db().".class_section cs on cs.section_id=s.section_id
inner join ".get_school_db().".class cc on cc.class_id=cs.class_id 
inner join ".get_school_db().".departments d on d.departments_id=cc.departments_id 

inner join ".get_school_db().".acadmic_year ay on ay.academic_year_id=s.academic_year_id 



where  s.school_id=".$_SESSION['school_id']." and  s.student_status>20 AND s.student_id=$param2";*/
$students=$this->db->query($quer)->result_array();
/*echo "<pre>";
print_r($students);*/




 $school_id=$_SESSION['school_id'];



//$qur=$this->db->query("select * from ".get_school_db().".student where student_id=$param2 and  school_id=$school_id")->result_array();

?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('withdrawal');?>
            	</div>
            </div>
			<div class="panel-body">
<?php echo "<strong>".get_phrase('name').": </strong>".$students[0]['name'];
echo "<br>";
echo "<strong>".get_phrase('roll#').": </strong>".$students[0]['roll'];
echo "<br>";
echo "<strong>".get_phrase('department')."/".get_phrase('class')."/".get_phrase('section').": </strong>".$students[0]['department_name']." - ".$students[0]['class_name']." - ".$students[0]['section_name'];
echo "<br>";
$status_val=$students[0]['challan_status'];
echo "<strong>Challan status:</strong>".withdraw_challan_status($status_val);
if($students[0]['requested_by']>0)
    	{?>
    		<br>
			<strong><?php echo get_phrase('requested_by'); ?>:</strong>
		<?php 
		
		$user_req=get_user_info($students[0]['requested_by']);
 		echo  $user_req[0]['name'];
 		?>
 		<br>
 		<strong><?php echo get_phrase('requested_date'); ?>:</strong>
 		<?php
 		echo convert_date($students[0]['request_date']);
		}


		

//exit;
?>
                <?php echo form_open(base_url().'c_student/confirm_withdraw/'.$students[0]['student_id'].'/'.$students[0]['s_c_f_id'].'/'.$students[0]['std_withdraw_id'], array('class' => 'form-horizontal form-groups-bordered validate','id' => 'ajax_promotion_form' ,'enctype' => 'multipart/form-data'));?>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('withdrawal_status');?></label>
                        <select id="acad_year" name="student_status" class="form-control" required="">
                            <?php echo approve_withdraw_option($students[0]['student_status']); ?>
                        </select>   
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('withdrawal_reason');?></label>
                        <textarea class="form-control" name="withdrawal_reason"></textarea>
                    </div>
                    <div class="form-group">
                		<div class="float-right">
                			<button id="btn1" type="submit" class="modal_save_btn"><?php echo get_phrase('save');?></button>
                		</div>
                	</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>


<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-right: 16px solid green;
  border-bottom: 16px solid red;
  border-left: 16px solid pink;
  width: 100px;
  height: 100px;
      margin-left: auto;
    margin-right: auto;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 1s linear infinite;
}


.loader_small {
  border: 5px solid #f3f3f3;
  border-radius: 50%;
  border-top: 5px solid blue;
  border-right: 5px solid green;
  border-bottom: 5px solid red;
  border-left: 5px solid pink;
  width: 20px;
  height: 20px;
      margin-left: auto;
    margin-right: auto;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 1s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<script>
$(document).ready(function(){
		
	var get_val =$('#acad_year').val();

for(get_val=parseInt($('#acad_year').val()); get_val>=0; get_val--){

$("#acad_year option[value='"+get_val+"']").attr('disabled','true');

}

});
</script>