<?php 
    $query =$this->db->query("select * from ".get_school_db().".school s inner join ".get_system_db().".system_school sc on sc.sys_sch_id=s.sys_sch_id
    where sc.parent_sys_sch_id=".$_SESSION['parent_sys_sch_id'])->result_array();
    $school_ary=array();
    foreach($query as $row){
    	$school_id=$row['school_id'];
    	$school_ary[$school_id]=$row['name'];	
    }
    
    $quer="select * from ".get_system_db().".system_school ss inner join  ".get_school_db().".school sc on sc.sys_sch_id=ss.sys_sch_id 
    inner join ".get_school_db().".student s on s.school_id=sc.school_id inner join ".get_school_db().".transfer_student ts on s.student_id=ts.student_id where ss.parent_sys_sch_id=".$_SESSION['parent_sys_sch_id']." and ts.student_id=$param2 and ts.status=2";
    $edit_data=$this->db->query($quer)->result_array();
    
    foreach ($edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
                    <?php echo get_phrase('transfer_student'); ?>
                </div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url().'transfer_student/promotion_section/'.$row['transfer_id'] , array('class' => 'form-horizontal form-groups-bordered validate','id' => 'ajax_promotion_form' ,'enctype' => 'multipart/form-data'));?>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('transfer_from');?></label>             
                    <?php echo $school_ary[$row['from_branch']]; ?>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('transfer_to');?></label>
                    <?php echo $school_ary[$row['to_branch']]; ?>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('reason');?></label>
                    <?php echo $row['reason']; ?>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('status');?></label>
                    <?php $ary[$row['status']]="selected";  ?>
                    <select name="student_status" class="form-control" required="" >
                        <option  value=""><?php echo get_phrase('select_status');?></option>
                        <option <?php echo $ary[11]; ?> value="3"><?php echo get_phrase('cancel');?></option>
                        <option <?php echo $ary[14]; ?> value="4"><?php echo get_phrase('approve');?></option>
                    </select>
                </div>
                <div class="form-group">
					<div class="float-right">
    					<button type="submit" id="btn1" class="modal_save_btn">
    						<?php echo get_phrase('save');?>
    					</button>
    					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
    						<?php echo get_phrase('cancel');?>
    					</button>
    				</div>
				</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<?php
endforeach;
?>

<script>


$(document).ready(function(){


get_location('<?php echo $country_id; ?>','provience','<?php echo $provience_id; ?>');	
get_location('<?php echo $provience_id; ?>','city','<?php echo $city_id; ?>');	
get_location('<?php echo $city_id; ?>','location','<?php echo $location_id; ?>');	


$(".country").change(function(){
var loc_id=$(this).val();
var send_location='provience';


if(loc_id==""){
	
}

else{

get_location(loc_id,send_location);	

$(".provience").html("<option><?php echo get_phrase('select_province'); ?></option>");
$(".city").html("<option><?php echo get_phrase('select_city'); ?></option>");
$(".location").html("<option><?php echo get_phrase('select_location'); ?></option>");
}
	
	
});

$(".provience").change(function(){

	
var loc_id=$(this).val();
var send_location='city';

if(loc_id==""){
	
}

else{

get_location(loc_id,send_location);	
$(".city").html("<option><?php echo get_phrase('select_city'); ?></option>");
$(".location").html("<option><?php echo get_phrase('select_location'); ?></option>");
}
});

$(".city").change(function(){

	var loc_id=$(this).val();
var send_location='location';


if(loc_id==""){
	
}

else{

get_location(loc_id,send_location);	
$(".location").html("<option><?php echo get_phrase('select_location'); ?></option>");
}
});







});
	

function get_location(loc_id,send_location,selected){
	
	$("#loading").remove();
$('.'+send_location).after("<div id='loading' class='loader'></div>");
	
	
	
$.ajax({
			type: 'POST',
			data: {loc_id:loc_id,send_location:send_location,selected:selected},
	 url: "<?php echo base_url();?>c_student/get_location",
			dataType: "html",
			success: function(response) {
			$('.'+send_location).html(response);
			
		$("#loading").remove();
	
		
				 }
		});
	
	
	
}

</script>

<script>
	
$(document).ready(function(){




$('#acad_year').change(function(){
	

	
	
});


$("#btn1").click(function(e){	
e.preventDefault();

var student_id='<?php echo $edit_data[0]['transfer_id']; ?>';
 




$.ajax({
 		type: 'POST',
 		data: $("#ajax_promotion_form").serialize(),
 		 url: "<?php echo base_url();?>transfer_student/promotion_section/"+student_id,
 		dataType: "html",
 		success: function(response) {
 			
 		
 		$('#modal_ajax').modal('toggle');
 		
 		var newUrl = "<?php echo base_url();?>transfer_student/transfer_information";
 		location.replace(newUrl);
 		//get_all_rec();	
 			
 			
 			
 			 }
 	});






});
	

});
/*
function call_ajax(){
	$("#loading").remove();
$("#acad_year").after("<div id='loading' class='loader_small'></div>");

	var acad_year=$("#acad_year").val();
	var term_id="<?php echo $edit_data[0]['adm_term_id'];  ?>";

	
	if(acad_year==""){
			$("#loading").remove();
	}else{
			$.ajax({
			type: 'POST',
			data: {acad_year:acad_year,term_id:term_id},
			 url: "<?php echo base_url();?>c_student/get_year_term",
			dataType: "html",
			success: function(response) {
				
				$('#adm_term_id').html(response);
				
		
			//$("#loading").remove();
				
				 }
		});
	}
	
	
}

*/	
	
	
</script>

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