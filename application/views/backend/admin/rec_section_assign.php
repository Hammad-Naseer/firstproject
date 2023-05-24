<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('manage_transfer');?>
            	</div>
            </div>
			<div class="panel-body">
<?php echo form_open(base_url().'transfer_student/assign_section_receive/'.$param2 , array('class' => 'form-horizontal form-groups-bordered validate','id' => 'ajax_promotion_form' ,'enctype' => 'multipart/form-data'));?>
                
                
                
                    <div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('academic_year');?>
                        /
                         <?php echo get_phrase('term');?>
                        </label>
                        
    					<div class="col-md-6 col-lg-6 col-sm-6">
                            <label id="yearly_terms_filter_selection"></label>
                            <select id="yearly_terms_filter" name="term_id" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">
                                <?php
                                  $status_year=array(1);
                                  $status_term=array(1);                          
                                  echo yearly_term_selector('',$status_year,$status_term);
                                ?>
                            </select>   
                        </div>
				</div>

					 
			    <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('class');?>
                                /
                                <?php echo get_phrase('section');?>
                                <span class="red"> * </span></label>
                                <div class="col-sm-6">
                                <label id="section_id1_selection" style="font-size:11px; margin-top:-11px; display:block"></label>
                                <select id="section_id1" class="selectpicker form-control" name="section_id" data-validate="required" data-message-required="Value Required" >
                               	
                               
                               <?php echo section_selector();?>
                               
 								</select>
 								<div id="section-err"></div>
                                </div>

                                </div>



    
    
    
            



           <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('roll_no');?></label>
                    <div class="col-sm-6">
                     
 <input type="text" name="roll" class="form-control" required="" value="<?php echo $row['roll'] ?>"/> 


  </div>
</div>

	<div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('registration_no');?></label>
                    <div class="col-sm-6">
                     
 <input type="text" name="regist_num" class="form-control"  value=""/> 


  </div>
</div>
 
<div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('admission_date');?></label>
                    <div class="col-sm-6">
                     

<input type="date" class="form-control" name="admission_date" id="admission_date"/>

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
	
$('.selectpicker').on('change', function() {
            var id = $(this).attr('id');

            var selected = $('#' + id + ' :selected');

            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);





        });	
	









});





	



</script>



<script>
	
$(document).ready(function(){







/*$("#btn1").click(function(e){	
e.preventDefault();

var student_id='<?php echo $edit_data[0]['student_id']; ?>';
 




$.ajax({
 		type: 'POST',
 		data: $("#ajax_promotion_form").serialize(),
 		 url: "<?php echo base_url();?>transfer_student/promotion_section_receive/"+student_id,
 		dataType: "html",
 		success: function(response) {
 			
 		
 		$('#modal_ajax').modal('toggle');
 		get_all_rec();	
 			
 			
 			
 			 }
 	});






});*/
	

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