<?php  
$edit_data=$this->db->get_where(get_school_db().'.student' , array('student_id' => $param2,'school_id' =>$_SESSION['school_id']) )->result_array();
foreach ( $edit_data as $row):
$department_data=get_section_edit($row['student_id']);
$departments_id=$department_data[0]['departments_id'];



   $student_id=$department_data[0]['student_id'];

  $class_id=$department_data[0]['class_id'];

  $section_id=$department_data[0]['section_id'];




?>

<style>
	.marg_zero{
		
		margin: 0px;
		padding: 0px;
		
	}
	
	
</style>



<div class="row">
	<div class="col-md-12" >
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_student');?>
            	</div>
            </div>
			<div class="panel-body">
				<?php echo form_open(base_url().'c_student/student/'.$row['section_id'].'/do_update/'.$row['student_id'] , array('id'=>'student_edit','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
				
				
				
	<div class="col-lg-12 marg_zero">			
				
                <div class="form-group">
<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('photo');?></label>
<div class="col-sm-8">
							<div class="fileinput fileinput-new" data-provides="fileinput">
<input type="hidden" name="image_file" value="<?php echo  $row['image']; ?>"/>


<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput" id="student_image">




<img src="<?php

if($row['image']==""){
	
	 echo  base_url().'/uploads/default.png';
	
}
else{
 echo  display_link($row['image'],'student');	
}


?>" alt="...">

</div>



<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
								<div>
									<span class="btn btn-white btn-file">
										<span class="fileinput-new">
                                        
                                        <?php echo get_phrase('select_image'); ?>
                                        </span>
										<span class="fileinput-exists"><?php echo get_phrase('change'); ?></span>
										<input type="file" name="userfile" id="userfile" onchange="file_validate('userfile','img','img_f_msg')" accept="image/*">
									</span>					
<a class="btn btn-primary" onclick="delete_files('<?php echo $row['image']; ?>','student','student_id','<?php echo $row['student_id']; ?>','image','student','student_image')"><?php echo get_phrase('delete_image'); ?></a>

									
									
								</div>

							</div>
						</div>
					
					</div>
<span style="color: green;"><?php echo get_phrase('allowed_file_size'); ?>: 200kb, <?php echo get_phrase('allowed_file_types'); ?>: png, jpg, jpeg </span>
                   <br />
<span style="color: red;" id="img_f_msg"></span>		

	
	
	
	
	
	

<div class="form-group">
						<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('name');  ?><span class="red"> * </span></label>
                        
						<div class="col-sm-8">
							<input required="" maxlength="100" type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['name'];?>">
						</div>
					</div>


					
<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('Date_of Birth');?></label>
                        
						<div class="col-sm-8">
							<input type="text" class="form-control datepicker" name="birthday" value="<?php echo date_dash($row['birthday']);?>" data-start-view="2" data-format="dd/mm/yyyy">
						</div> 
					</div>
		
			
<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('gender');?> <span class="red"> * </span></label>
                        
						<div class="col-sm-8">
						
						<?php 
						
						
						
						   ?>
	<select required name="sex" class="form-control">
<option value=""><?php echo get_phrase('select'); ?></option>

<option value="male" <?php if($row['gender'] == 'male')echo 'selected';?>><?php echo get_phrase('male');?></option>
<option value="female"<?php if($row['gender'] == 'female')echo 'selected';?>><?php echo get_phrase('female');?></option>
 </select>
</div> 
</div>
<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo  $rows['religion'];  echo get_phrase('religion'); ?> <span class="red">*</span></label>
<div class="col-sm-8">
<?php echo religion_list("religion","form-control",$row['religion']);    ?>
</div> 
</div>
<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('mobile_#');?><span class="red"> * </span></label>
<div class="col-sm-8">
<input required="" maxlength="20" type="text" class="form-control" name="mobile_num" value="<?php echo $row['mob_num'] ?>" >
</div> 
</div>
<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('emergency_#');?><span class="red"> * </span></label>
                        
						<div class="col-sm-8">
							<input required="" maxlength="20" type="text" class="form-control" name="emg_num" value="<?php echo $row['emg_num']; ?>" >
						</div> 
					</div>
		
		
			
<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('postal_address');?></label>
                        
						<div class="col-sm-8">
						
							
<textarea id="address_cont_p" class="form-control" oninput="count_value('address_cont_p','address_count_p','200')" name="address" maxlength="200" rows="4"><?php echo $row['address'];  ?></textarea>

<div id="address_count_p"></div>
						</div> 
					</div>


<script> 
$(document).ready(function(){
    $("#toclick").click(function(){
        $("#toslide").slideToggle("slow");
    });
});
</script>


         
     <div class="border mgt10">

		<div class="panel panel-default panel-shadow panel-collapse" data-collapsed="0" id="toclick">
			<div class="panel-heading">
				<div class="panel-title myttl">
                
                <?php echo get_phrase('edit_more_information'); ?></div>
				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
				</div>
		 </div>
		<div class="panel-body" id="toslide" style="display:none;">
		<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('student_category');     ?></label>
                        
						<div class="col-sm-8">
							<select name="student_category_edit" class="form-control">
						<?php echo student_category($row['student_category_id']);?>
						</select>
						</div> 
					</div>
		
			
<!-- roll  -->	 
				
<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('roll');     ?></label>
                        
						<div class="col-sm-8">
							<input maxlength="50" type="text" class="form-control" name="roll" value="<?php echo $row['roll'];?>" >
						</div> 
					</div>
					
<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('permanent_address'); ?></label>
                        
						<div class="col-sm-8">
						
							
<textarea rows="5" id="per_cont_p" class="form-control" name="p_address" oninput="count_value('per_cont_p','per_count_p','200')" maxlength="200" ><?php echo $row['p_address'];  ?></textarea>
							
	<div id="per_count_p"></div>						
						</div> 
					</div>


					
<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('id_type');?></label>
                        
						<div class="col-sm-8">
						
<?php echo id_type_list('id_type','form-control',$row['id_type']);?>
						</div> 
					</div>

<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('id_no');?></label>
                        
						<div class="col-sm-8">
						
<input maxlength="30" type="text" class="form-control" id="cnic_n" name="form_b" onchange="get_cnic('cnic_n','s','student')" value="<?php echo $row['id_no']; ?>" onkeyup="nospaces(this)" >
						</div> 
					</div>					
					
					
  <div class="form-group">
                <label class="col-sm-4 control-label">
                    <?php echo get_phrase('id_file');?>
                </label>
                <div class="col-sm-8">
                    <input value="" type="file" class="form-control" name="form_b_file" id="form_b_file" onchange="file_validate('form_b_file','doc','img_f_msg')">
<span style="color: green;">
<?php echo get_phrase('allowed_file_size');?>
:  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx</span>
                   <br />
<span style="color: red;" id="img_f_msg"></span>
                    <span id="id_file">				
		 <?php
		
 $val_im=display_link($row['id_file'],'student',0,0); 
 if($val_im!=""){
 ?>	
<a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><?php echo get_phrase('form_attachment');?></a>

<a onclick="delete_files('<?php echo $row['id_file']; ?>','student','student_id','<?php echo $row['student_id']; ?>','id_file','student','id_file',2)" class="img-responsive img-thumbnail"><?php echo get_phrase('delete_attachment');?></a>





<?php
}
 ?> 				
<input type="hidden" name="id_file_old" value="<?php echo $row['id_file']; ?>" />								
			</span>
                </div>
            </div>
            
            
            
<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('nationality');?>
	
</label>
                        
						<div class="col-sm-8">
						
						
	<select name="nationality" class="form-control">					
<?php echo country_option_list($row['nationality']); ?></select>

						</div> 
					</div>				

<?php 

$country_id=0;
$provience_id=0;
$city_id=0;
$location_id=0;
if (intval($row['location_id']) > 0)
{
	$location_data=get_country_edit($row['location_id']);
	if (count($location_data) > 0)
	{
		$country_id=$location_data[0]['country_id'];
		$provience_id=$location_data[0]['province_id'];
		$city_id=$location_data[0]['city_id'];
		$location_id=$location_data[0]['location_id'];
	}
}
//print_r($location_data);

  ?>

<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('country');?>
	
</label>
                        
						<div class="col-sm-8">
						
						
	<select name="country" class="form-control country">					
<?php echo country_option_list($country_id); ?></select>

						</div> 
					</div>

<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('provience');?></label>
                        
						<div class="col-sm-5 provience_html">

<select name="provience" class="form-control provience">
<option><?php echo get_phrase('select'); ?></option>
<?php 

echo province_option_list($country_id, $provience_id); ?>

</select>


						</div> 
					</div>
<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('city');?></label>
<div class="col-sm-5 city_html">
<select name="city" class="form-control city">

<option><?php echo get_phrase('select'); ?></option>
<?php echo city_option_list($provience_id, $city_id); ?>
</select>
</div> 
</div>

<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('location');?></label>
                        
<div class="col-sm-5 city_html">

<select name="location" class="form-control location">
<option><?php echo get_phrase('select'); ?></option>
<?php echo location_option_list($city_id, $location_id); ?>

</select>
			</div> 
			</div>

<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('residence_#');?></label>
<div class="col-sm-8">
<input maxlength="20" type="text" class="form-control" name="phone" value="<?php echo $row['phone'];?>" >
</div> 
					</div>       

<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('blood_group');?></label>
                        
						<div class="col-sm-8">
							<input type="text" class="form-control" name="bd_group" value="<?php echo $row['bd_group']; ?>" >
						</div> 
					</div>       
                    
<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('disability(if any)');?></label>
<div class="col-sm-8">
<input type="text" class="form-control" name="disability" value="<?php echo $row['disability'] ?>" >
</div> 
</div>                  
                    
<div class="form-group">
<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('email');?></label>
						<div class="col-sm-8">
							<input id="email" type="text" class="form-control" name="email" value="<?php echo $row['email'];?>">
						</div>
					</div>
    
			</div>

</div>













            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
             
 <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button id="btn1" type="submit" class="btn btn-info"><?php echo get_phrase('Save');?></button>
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

get_class1('<?php echo $departments_id;  ?>','<?php echo $class_id;  ?>',0);





//alert('<?php echo $section_id;  ?>');

if('<?php echo $section_id;  ?>'!=""){
	


//get_section1('<?php echo $class_id;  ?>','<?php echo $section_id;  ?>');

}





$(this).delay(2000).queue(function() {



  });
		



$("#departments_id1").change(function(){
	var dep_id=$(this).val();
	
get_class1(dep_id,"","");
	
});

$("#class_id1").change(function(){
	var clas_id=$(this).val();
	

		
	
//	get_section1(clas_id,"");

	
});	


});

function get_class1(dep_id,sel,run){




	$("#icon").remove();
	$("#departments_id1").after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
	$.ajax({
		async: true,
			type: 'POST',
			data: {dep_id:dep_id,sel:sel},
	url: "<?php echo base_url();?>c_student/get_class",
			dataType: "html",
			success: function(response) {
				
			
				
				$("#icon").remove();
			
			$("#class_id1").html(response);
		//	$("#section_id1").html("<option>Select Section</option>");
			
			if(run==0){
		//	get_section1('<?php echo $class_id;  ?>','<?php echo $section_id;  ?>');
			
		}
			
				
				 }
		});
	
}


function get_section1(class_id,sel){
	
	
	
	$("#icon").remove();
	$("#class_id1").after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');	
	
	

	
$.ajax({
	async: true,
			type: 'POST',
			data: {class_id:class_id,sel:sel},
	url: "<?php echo base_url();?>c_student/get_section",
			dataType: "html",
			success: function(response) {
				
			
				
				$("#icon").remove();
			
		//	$("#section_id1").html(response);
		
			
				
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
$(".location").html("<option>Select Location</option>");
}
});







});
	
$('#email').change(function(){
	
	get_email();
	
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

function get_email(){
	
	$('#email').after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
	var email=$('#email').val();
		$.ajax({
      type: 'POST',
       data: {email:email},
      url: "<?php echo base_url();?>admin/call_function",
      dataType: "html",
      success: function(response) { 
if($.trim(response)=='yes'){

$("#btn1").attr('disabled','true');
$("#email").css('border','1px solid red');
$("#icon").remove();

if($('#message').html()==undefined){
$("#email").before('<p id="message" style="color:red;"><?php echo get_phrase('email_address_is_already_exist'); ?></p>');	
}
}else{
	
$("#btn1").removeAttr('disabled');
$("#email").css('border','1px solid green');
$("#icon").remove();	
$("#message").remove();	
}      
}
});
}



function get_cnic(cnic_n, type_n, table_name, detail_field) {
        var cnic = $('#' + cnic_n).val();
		//
        //return false;
        $("#message_scnic").remove();
		//alert(table_name);

        $('#' + cnic_n + type_n).remove();

        $('#' + cnic_n).after('<div id="' + cnic_n + type_n + '" class="loader_small"></div>');
        $.ajax({
            type: 'POST',
            
            data: {
                cnic: cnic,
                type_n: type_n,
                table_name: table_name
            },
           url: "<?php echo base_url();?>admin/get_cnic_stu",
            dataType: "json",
            //dataType: "html",
            success: function(response) {
   //             console.log("response is : " +response);
   //          	alert(response);
			// alert(response.value);

                $('#' + cnic_n + type_n).remove();



                if (type_n == 's') {
                    if ($.trim(response.value) == 'no') {
                    	
                        $("#btn1").removeAttr('disabled');
                        $("#" + cnic_n).css('border', '1px solid green');
                        $("#message_scnic").remove();
                    } else {
                        $("#btn1").attr('disabled', 'true');
                        $("#" + cnic_n).css('border', '1px solid red');

                        if ($('#message_scnic').html() == undefined) {
                            $("#" + cnic_n).before('<p id="message_scnic" style="color:red;"><?php echo get_phrase('id_already_exist'); ?></p>');
                        }
                    }





                    $("#" + cnic_n + type_n).remove();
                } else if (type_n == 'f') {

                    if ($.trim(response.value) == 'no') {
                    	
                        $('#f_name').removeAttr("readonly").val('');
                        
                        //$('#f_cnic').removeAttr("readonly").val('');
                        $('#f_num').removeAttr("readonly").val('');
                        $('#f_ocu').removeAttr("readonly").val('');

                    } else {
                        $('#f_name').val(response[0].p_name).attr("readonly", "true");
                        //$('#f_cnic').val(response[0].cnic);
                        $('#f_num').val(response[0].contact).attr("readonly", "true");
                        $('#f_ocu').val(response[0].occupation).attr("readonly", "true");
                    }

                    $("#" + cnic_n + type_n).remove();

                } else if (type_n == 'm') {

                    if ($.trim(response.value) == 'no') {
                        $('#m_name').removeAttr("readonly").val('');
                        //$('#m_cnic').removeAttr("readonly").val('');
                        $('#m_num').removeAttr("readonly").val('');
                        $('#m_ocu').removeAttr("readonly").val('');

                    } else {
                        $('#m_name').val(response[0].p_name).attr("readonly", "true");
                        //$('#f_cnic').val(response[0].cnic);
                        $('#m_num').val(response[0].contact).attr("readonly", "true");
                        $('#m_ocu').val(response[0].occupation).attr("readonly", "true");
                    }

                    $("#" + cnic_n + type_n).remove();

                } else if (type_n == 'g') {

                    if ($.trim(response.value) == 'no') {
                        $('#g_name').removeAttr("readonly").val('');
                        //$('#m_cnic').removeAttr("readonly").val('');
                        $('#g_num').removeAttr("readonly").val('');
                        $('#g_ocu').removeAttr("readonly").val('');

                    } else {
                        $('#g_name').val(response[0].p_name).attr("readonly", "true");
                        //$('#f_cnic').val(response[0].cnic);
                        $('#g_num').val(response[0].contact).attr("readonly", "true");
                        $('#g_ocu').val(response[0].occupation).attr("readonly", "true");
                    }

                    $("#" + cnic_n + type_n).remove();
                }



                //get_student(cnic,detail_field);


            }

        });



    }



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
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>