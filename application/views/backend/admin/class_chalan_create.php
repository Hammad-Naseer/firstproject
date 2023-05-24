<?php 

 
$edit_data=$this->db->query("select * from ".get_school_db().".class_section cs inner join ".get_school_db().".class c on c.class_id=cs.section_id
inner join ".get_school_db().".departments d on d.departments_id=c.departments_id where cs.section_id=$param2 and cs.school_id=101")->result_array();

 $departments_id=$edit_data[0]['departments_id'];

 // $student_id=$department_data[0]['student_id'];
  $class_id=$edit_data[0]['class_id'];
  
  $section_id=$edit_data[0]['section_id'];


?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_student');?>
            	</div>
            </div>
			<div class="panel-body">
<?php echo form_open(base_url().'c_student/promotion_section/', array('class' => 'form-horizontal form-groups-bordered validate','id' => 'ajax_promotion_form' ,'enctype' => 'multipart/form-data'));?>            


<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('department');?></label>
                        
						<div class="col-sm-5">
	<select id="departments_id1" name="departments_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">

<?php echo department_option_list($departments_id); ?>


                          </select>
						</div> 
					</div>

					
<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                        
						<div class="col-sm-5">
							<select id="class_id1" name="class_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>">
                         
                          </select>
						</div> 
					</div>

<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('section');?></label>
                        
						<div class="col-sm-5">
							<select id="section_id1" name="section_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                         
                          </select>
						</div> 
					</div>

    
    
    
              <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('acadamic_year');?></label>
                    <div class="col-sm-5">
                     
  <select id="acad_year" name="academic_year_id" class="form-control" required="">
  	<option value=""><?php echo get_phrase('select_year'); ?></option>
  
  	<?php 
  	echo academic_year_option_list(0,$status=1);

    ?>

  	
  </select>   

  </div>
</div>

        
 <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button id="btn1" type="submit" class="btn btn-info"><?php echo get_phrase('save');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<script>

$(document).ready(function(){

get_class1('<?php echo $departments_id;  ?>','<?php echo $class_id;  ?>',0);
if('<?php echo $section_id;  ?>'!=""){

get_section1('<?php echo $class_id;  ?>','<?php echo $section_id;  ?>');

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
			get_section1('<?php echo $class_id;  ?>','<?php echo $section_id;  ?>');
			
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

else
{

get_location(loc_id,send_location);	
$(".city").html("<option><?php echo get_phrase('select_city'); ?>y</option>");
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

var section_id='<?php echo $section_id; ?>';
 




$.ajax({
 		type: 'POST',
 		data: $("#ajax_promotion_form").serialize(),
 		 url: "<?php echo base_url();?>c_student/promotion_class/"+section_id,
 		dataType: "html",
 		success: function(response) {
 			
 		
 		$('#modal_ajax').modal('toggle');
 		get_all_rec();	
 			
 			
 			
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