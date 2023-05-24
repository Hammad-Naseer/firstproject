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
  
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('Class Conversion');?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url().'c_student/class_conversion/'.$row['student_id'] , array('class' => 'form-horizontal form-groups-bordered validate','id' => 'ajax_promotion_form' ,'enctype' => 'multipart/form-data'));?>
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
                <?php 
                 if (!isset($section_id) || $section_id == "") {
                        $section_id = $this->uri->segment(3);
                    }
                 ?>
                <div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('Select New Class');?><span class="star">*</span></label>    
	                <select  name="section_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" required>
                     <?php echo section_selector($section_id);?>
                    </select>
                    <div id="d1" class="d"></div> 
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



















