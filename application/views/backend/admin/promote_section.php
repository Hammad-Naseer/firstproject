<style>
    .d{color:red;}
</style>
<?php
    
    $edit_data=$this->db->get_where(get_school_db().'.student' , array('student_id' => $param2,'school_id' =>$_SESSION['school_id']) )->result_array();
    foreach ( $edit_data as $row):
    $department_data=get_section_edit($row['student_id']);
    $departments_id=$department_data[0]['departments_id'];
    $student_id=$department_data[0]['student_id'];
    $class_id=$department_data[0]['class_id'];
    $section_id=$department_data[0]['section_id'];
    $pro_sec=section_hierarchy($department_data[0]['pro_section_id']);
    $pro_class_id=$pro_sec['c_id'];
    $pro_departments_id=$pro_sec['d_id'];
    $pro_section_id=$pro_sec['s_id'];
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('promote_student ');?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url().'c_student/promotion_section/'.$row['student_id'] , array('class' => 'form-horizontal form-groups-bordered validate','id' => 'ajax_promotion_form' ,'enctype' => 'multipart/form-data'));?>
                <div class="form-group text-dark">
                    <strong> <?php echo get_phrase('name');?>: </strong>
                    <?php echo $row['name'];?>
                    <br>
                    <strong> <?php echo get_phrase('roll_no');?></strong>
                    <?php echo $row['roll'];?>
                    <br>
                    <strong> <?php echo get_phrase('department');?>/ <?php echo get_phrase('class');?>/ <?php echo get_phrase('section');?>: </strong>
                	<?php  $name_har= section_hierarchy($section_id);?>
                	<ul class="breadcrumb breadcrumb2" style
                		="padding:2px;">
                	    <li><?php echo   $name_har['d']   ;?>	</li>
                		<li><?php echo   $name_har['c']   ;?>	</li>		
                		<li><?php echo   $name_har['s']   ;?>	</li>		
                	</ul>
                </div>
                <div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('department');?><span class="star">*</span></label>    
	                <select id="departments_id1" name="departments_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" required>
                        <?php echo department_option_list($pro_departments_id); ?>
                    </select>
                    <div id="d1" class="d"></div> 
				</div>	     
                <div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('class');?><span class="star">*</span></label>
					<select id="class_id1" name="class_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>" required>
                    </select>      
                    <div id="d2" class="d"></div>
		        </div>
                <div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('section');?><span class="star">*</span></label>
					<select id="section_id1" name="section_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" required>
                    </select>      
                    <div id="d3" class="d"></div> 
				</div>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('Acadamic year');?><span class="star">*</span></label>
                    <select id="acad_year" name="academic_year_id" class="form-control" required>
  	                    <?php echo academic_year_option_list($row['pro_academic_year_id'],$status=1); ?>
                    </select>   
                    <div id="d4" class="d"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('roll_no');?><span class="star">*</span></label>     
                     <input name="roll" class="form-control" value="<?php 
                     if($pro_class_id!=0){
                     echo $row['roll'];
                     } ?>" required id="roll"> 
                    <div id="d6" class="d"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('status'); ?><span class="star">*</span></label>
                    <?php if($pro_class_id!=0){ $ary[$row['student_status']]="selected"; } ?> 
                    <select name="student_status" class="form-control" required id="status" >
                        <option  value=""><?php echo get_phrase('select_status'); ?></option>
                        <?php if($row['student_status']==12 || $row['student_status']==13){ ?>
                        <option <?php echo $ary[12].$ary[13]; ?> value="12" disabled=""> <?php echo get_phrase('promotion_chalan_paid');?></option>
                        <option <?php echo $ary[14]; ?> value="14"> <
                        <?php echo get_phrase('promotion_confirmed');?></option>
                        <?php 
                            }elseif($row['student_status']==10 || $row['student_status']==14){
                        ?>
                        <option <?php echo $ary[11]; ?> value="11"><?php echo get_phrase('promotion_requested'); ?></option>
                        <?php } ?>
                        <?php if($row['student_status']==16 || $row['student_status']==17){ ?>
                        <option <?php echo $ary[16].$ary[17]; ?> value="16" disabled=""> <?php echo get_phrase('demotion_chalan_paid');?></option>
                        <option <?php echo $ary[18]; ?> value="18"> <?php echo get_phrase('demotion_confirmed');?></option>
                        <?php
                            }elseif($row['student_status']==10 || $row['student_status']==14){
                        ?>
                        <option <?php echo $ary[15]; ?> value="15"> <?php echo get_phrase('demotion_request');?> </option>
                        <?php } ?>
                    </select>
                    <div id="d5" class="d"></div>
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

get_class1('<?php echo $pro_departments_id;  ?>','<?php echo $pro_class_id;  ?>',0);
if('<?php echo $pro_section_id;  ?>'!=""){

get_section1('<?php echo $pro_class_id;  ?>','<?php echo $pro_section_id;  ?>');

}
$(this).delay(2000).queue(function() {
  });

$("#departments_id1").change(function(){
	var dep_id=$(this).val();
get_class1(dep_id,"","");
});

$("#class_id1").change(function(){
	var clas_id=$(this).val();
	get_section1(clas_id,"");

});	


});

function get_class1(dep_id,sel,run){




	$("#loading").remove();
	$("#departments_id1").after('<div id="loading" class="loader_small"></div>');
	
	$.ajax({
		async: true,
			type: 'POST',
			data: {dep_id:dep_id,sel:sel},
	url: "<?php echo base_url();?>c_student/get_class",
			dataType: "html",
			success: function(response) {
				
			
				
				$("#loading").remove();
			
			$("#class_id1").html(response);
			$("#section_id1").html("<option><?php echo get_phrase('select_section'); ?></option>");
			
			if(run==0){
			get_section1('<?php echo $pro_class_id;  ?>','<?php echo $pro_section_id;  ?>');
			
		}
			
				
				 }
		});
	
	


	
}
function get_section1(class_id,sel){
	
	
	
	$("#loading").remove();
	$("#class_id1").after('<div id="loading" class="loader_small"></div>');	
	
	

	
$.ajax({
	async: true,
			type: 'POST',
			data: {class_id:class_id,sel:sel},
	url: "<?php echo base_url();?>c_student/get_section",
			dataType: "html",
			success: function(response) {
				
			
				
				$("#loading").remove();
			
			$("#section_id1").html(response);
		
			
				
				 }
		});	
	
}

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


    //location.reload();
    e.preventDefault();
    var student_id='<?php echo $edit_data[0]['student_id']; ?>';
    $.ajax({
 		type: 'POST',
 		data: $("#ajax_promotion_form").serialize(),
 		 url: "<?php echo base_url();?>c_student/promotion_section/"+student_id,
 		dataType: "html",
 		success: function(response)
        {
 		$('#modal_ajax').modal('toggle');

 		$("#btn1").attr('disabled','disabled');
            location.reload();
           // window.location.href = 'http://dev1/gsims/c_student/student_information/';
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










<script>
	
if($("#departments_id1").val()==""){
	$("#d1").html("<?php echo get_phrase('value_required'); ?>");
}
if($("#class_id1").val()==""){
	$("#d2").html("<?php echo get_phrase('value_required'); ?>");
}
	if($("#section_id1").val()==""){
	$("#d3").html("<?php echo get_phrase('value_required'); ?>");
}
	if($("#acad_year").val()==""){
	$("#d4").html("<?php echo get_phrase('value_required'); ?>");
}
	if($("#status").val()==""){
	$("#d5").html("<?php echo get_phrase('value_required'); ?>");
}
		if($("#roll").val()==""){
	$("#d6").html("<?php echo get_phrase('value_required'); ?>");
}
</script>









