<?php $quey=$this->db->query("select name, section_id, roll, section_id, adm_term_id,reg_num , student_status,academic_year_id ,adm_date from ".get_school_db().".student where student_id=$param2 and school_id=".$_SESSION['school_id'])->result_array(); ?>

<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
					<h4><?php echo get_phrase('approve_student_admission'); ?></h4>
                    <p style="color:white !important"><?php echo get_phrase('name'); ?> :<?php echo  $quey[0]['name']; ?></p>
                    <p style="color:white !important">(<?php echo get_phrase('department'); ?> - <?php echo get_phrase('class'); ?> - <?php echo get_phrase('section'); ?> ): <?php $section_h=  section_hierarchy($quey[0]['section_id']); echo $section_h['d']." - ".$section_h['c'].' - '.$section_h['s'];?></p>
            	</div>
            </div>
            <div class="panel-body">
                <?php echo form_open(base_url().'class_chalan_form/approve_admission/', array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'disable_submit_btn'));?>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('Acadamic year');?></label>
                        <select id="acad_year" name="academic_year_id" class="form-control" required="">
          	                <?php echo academic_year_option_list($quey[0]['academic_year_id'],$status=1); ?>
                        </select>   
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('admission_term');?></label>
                        <select id="adm_term_id" name="adm_term_id" class="form-control" required="">
              	            <option value=""><?php echo get_phrase('select'); ?></option>
                        </select>   
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('roll_number');?> </label> <span class="text-danger">Last Assigned Roll#: <?php echo get_last_roll_number();?></span>
                        <input maxlength="50" type="text" class="form-control" name="roll" id="roll" value="<?php if($quey[0]['roll']!=0){ echo $quey[0]['roll']; }?>" required="" />
                        <input type="hidden" class="form-control" name="student_id" value="<?php echo  $param2;  ?>" required="" />
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('registration#');?></label>
                        <input maxlength="50" type="text" class="form-control" name="reg_num" id="reg_num" value="<?php echo $quey[0]['reg_num']; ?>" required="" />
                        <input type="hidden" class="form-control" name="student_id" value="<?php echo  $param2;  ?>" required="" />
                    </div>
                    <div class="form-group">
            			<label for="field-2" class="control-label"><?php echo get_phrase('admission_date');?></label>
                        <input type="date" class="form-control" name="adm_date" value="" data-format="dd/mm/yyyy">
            		</div> 
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('student_status');?></label>
                        <select onchange="hide_show()" name="student_status" id="student_status_s" class="form-control" required="">
              	            <option value=""><?php echo get_phrase('select'); ?></option>
                          	<?php 
                                $sel1="";
                               	$sel2="";
                           
                                if($quey[0]['student_status']==9){
                                   	$sel1="selected";
                                }elseif($quey[0]['student_status']==10){
                                   	$sel2="selected";
                           	
                                }
                            ?>
                            <option value="9" <?php echo $sel1; ?>><?php echo get_phrase('send_for_approval');?></option>
              	            <option <?php echo $sel2; ?>  value="10"><?php echo get_phrase('confirm_admission');?></option>
                        </select>   
                    </div>
                    <div class="row" id="sms_email" style="display: none;">  
                        <div class="col-md-12 col-sm-12" style="padding-left:30px">
                            <div class="form-group" <?= check_sms_preference(4,"style","sms") ?>>
                                <label class="control-label"><?php echo get_phrase('send_sms');?></label>
                                <input type="checkbox" id="send_message" name="send_message" class="" value="0"  />
                            </div>    
                        </div>       
                        <div class="col-md-12 col-sm-12" style="padding-left:30px">
                            <div class="form-group" <?= check_sms_preference(4,"style","email") ?>>
                                <label class="control-label"><?php echo get_phrase('Send Email');?></label>
                                <input type="checkbox"  id="send_email" name="send_email" class="" value="0"  />
                            </div>
                        </div>  
                    </div>
                    <div class="form-group">
                        <div class="float-right">
                            <button id="btn_dsb" type="submit" class="modal_save_btn"><?php echo get_phrase('save');?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function(){
    call_ajax();
    $('#acad_year').change(function(){
    	call_ajax();
    });
	
    call_ajax();
    	
    $('#roll').change(function(){
    	var roll=$(this).val();
    	var student_id="<?php echo $param2; ?>";
    	$.ajax({
    		type: 'POST',
    		data: {roll:roll,student_id:student_id},
            url: "<?php echo base_url();?>c_student/roll_check",
    		dataType: "html",
    		success: function(response) {
    		    if($.trim(response)=='no'){
    			$('#msg').remove();
    			$('#btn_dsb').attr('disabled','true'); 
                $('#roll').after('<div id="msg" style="color:red;"><?php echo get_phrase('record_already_exist'); ?></div>')
    			}else{
    				$('#msg').remove();
    				$('#btn_dsb').removeAttr('disabled');	
    			}	
    		}
    	});
    });

});

function call_ajax(){
	$("#loading").remove();
    $("#acad_year").after("<div id='loading' class='loader_small'></div>");
	var acad_year=$("#acad_year").val();
	var adm_term_id="<?php echo $quey[0]['adm_term_id']; ?>";
	if(acad_year==""){
			$("#loading").remove();
	}else{
			$.ajax({
			type: 'POST',
			data: {acad_year:acad_year,term_id:adm_term_id},
			url: "<?php echo base_url();?>c_student/get_year_term",
			dataType: "html",
			success: function(response) {
				$('#adm_term_id').html(response);
		    	$("#loading").remove();
			}
		});
	}
}

function hide_show(){

    var val2=document.getElementById('student_status_s').value;
	if(val2==10){
		$("#sms_email").show();
	}else{
	    $("#sms_email").hide();
	}
	
}


</script>
