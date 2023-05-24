<!--link rel="stylesheet" type="text/css" href="assets/includes/jquery-clockpicker.min.css"-->
<?php $urlArr=explode('/',$_SERVER['REQUEST_URI']);
$resArr=explode('-',end($urlArr));

// print_r($resArr);
// exit;
$exam_routine_id=$resArr[0];
$dept_id=$resArr[1];
$class_id=$resArr[2];
$section_id=$resArr[3];
$term=$resArr[4];
$academic_id=$resArr[5];

$q="select er.* from ".get_school_db().".exam_routine er where er.exam_routine_id=".$exam_routine_id." and er.school_id=".$_SESSION['school_id']."";

$resArr=$this->db->query($q)->result_array();

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title  black2" >
					<i class="entypo-plus-circled"></i>
                    <?php echo get_phrase('edit_datesheet');?>
				</div>
			</div>
			<div class="panel-body" style="padding: 0px;">
                <form name="exam_routine_form" id="exam_routine_form" class="form-horizontal validate">
                    <div class="form-group m-0"> 
                        <div class="col-sm-12">
                            <label id="yearly_term2_selection"><?php $yearly_term=academic_hierarchy($term); echo $yearly_term['a']?></label>	
                            <select id="yearly_term2" name="yearly_term1" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">
                                <?php echo yearly_term_selector($term);?>
                            </select>  
                        </div>
                    </div>
                    <div class="form-group m-0">
                        <div class="col-sm-12">
                            <label id="section_select_id1_selection"><?php $section_hierarchy=section_hierarchy($section_id);echo $section_hierarchy['d']?></label>
                            <select id="section_select_id1" class="selectpicker form-control" name="section_id" data-validate="required" data-message-required="Value Required"  class="form-control">
                                <?php echo section_selector($section_id);?>
                            </select>
                            <div id="section-err"></div>
                        </div>
                    </div>            
            	
            		<div class="form-group m-0">
            			<label class="col-sm-12 control-label"><?php echo get_phrase('subject');?></label>
            			<div class="col-sm-12">
            				<select id="subject_select_id" name="subject_id" class="form-control" style="width:100%;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
            					<?php echo subject_option_list($section_id,$resArr[0]['subject_id'])?>
            				</select>
            			</div>
            		</div>
            		<div class="form-group m-0">
            			<label class="col-sm-12 control-label"><?php echo get_phrase('total_marks');?></label>
            			<div class="col-sm-12">
            				<input type="text" class="form-control" id="total_marks" value="<?php echo $resArr[0]['total_marks']?>" name="total_marks" placeholder="Total Marks" style="width:100%;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
            			</div>
            		</div>
            		<div class="form-group m-0">
            			<label class="col-sm-12 control-label"><?php echo get_phrase('exam_type');?></label>
            			<div class="col-sm-12">
            				<select name="exam_id1"  id="exam_id1"  class="form-control" style="width:100%;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"><?php echo exam_type_option_list($term,$resArr[0]['exam_id'])?>	
                                               
            				</select>
            			</div>
            		</div>
            		<div class="form-group m-0">
            			<label class="col-sm-12 control-label"><?php echo get_phrase('exam_date');?></label>
            			<div class="col-sm-12">
            				<input type="date" name="exam_date1" value="<?php echo $resArr[0]['exam_date'];?>"  id="exam_date1" class="form-control" style="width:100%;"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
            				<div id="error_examdate1"></div>
            			</div>
            		</div>
            		<div class="form-group m-0">
            			<label class="col-sm-12 control-label"><?php echo get_phrase('starting_time');?></label>
            			<div class="col-sm-12">
            				<input type="time" class="form-control" value="<?php echo $resArr[0]['time_start']?>" id="time_start" name="time_start" placeholder="Start Time" style="width:100%;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
            			</div>
            		</div>
            		<div class="form-group m-0">
            			<label class="col-sm-12 control-label"><?php echo get_phrase('ending_time');?></label>
            			<div class="col-sm-12">
            				<input type="time" class="form-control" value="<?php echo $resArr[0]['time_end']?>" id="time_end" name="time_end" placeholder="End Time" style="width:100%;">
            			</div>
            		</div>
            		<div class="form-group  m-0">
            			<div class="col-sm-offset-3 col-sm-5">
            				<button id="submit-btn1" type="submit" class="btn btn-info"><?php echo get_phrase('edit_exam_routine');?></button>
            			</div>
            		</div>
            	</form>                
            </div>	
        </div>
    </div>
</div>    
                
<script type="text/javascript">
	$(document).ready(function(){
	    
	        $("#time_end").on("click change",function(){
                var start_date = $("#time_start").val();
                var end_date = $(this).val();
                if(start_date != "" && end_date != "" ){
                    if(end_date <= start_date)
                    {
                        $(this).val("");
                    }
                }    
            });
            
            $("#time_start").on("click change",function(){
                var end_date = $("#time_end").val();
                var start_date = $(this).val();
                if(start_date != "" && end_date != "" ){
                    if(start_date >= end_date)
                    {
                        $(this).val("");
                    }
                }    
            });
            
            
        	$('.selectpicker').on('change', function (){
        		var id=$(this).attr('id');
        		var selected = $('#'+ id +' :selected');
        		var group = selected.parent().attr('label');
        		$('#'+ id + '_selection').text(group);
            });
		    $('#session').css('display','none');
		    document.getElementById('exam_routine_form').onsubmit = function() {
			    return false;
		    };
		
		    $('#teacher-list').hide();
		
		    $('#section_select_id1').on('change',function(){
		
				var section_id=$(this).val();
				$.ajax({
						type: "POST",
						url: "<?php echo base_url(); ?>time_table/get_section_subject",

						data: ({section_id:section_id}),
						dataType : "html",
						success: function(html) {
							if(html !=''){
								$('#subject_select_id').html(html);
							}
						}
					});
			});

		    $('#academic_year1').on('change',function(){
				//alert("changed");
				var academic_year=$(this).val();
				if(academic_year=='')
				{
					$('#yearly_term').html('<select><option><?php echo get_phrase('select_term'); ?></option></select>');
				}
				$.ajax({
						type: "POST",
						url: "<?php echo base_url(); ?>time_table/get_class_routine_term",

						data: ({academic_year:academic_year,status:1}),
						dataType : "html",
						success: function(html) {
							if(html !=''){
								$('#yearly_term1').html(html);
							}
						}
					});
	
			});
		    $('#yearly_term2').on('change',function(){
		
				var yearly_term=$(this).val();
				$.ajax({
						type: "POST",
						url: "<?php echo base_url(); ?>exams/get_exam_type",

						data: ({yearly_term:yearly_term}),
						dataType : "html",
						success: function(html) {
							//alert(html);
							if(html !=''){
								$('#exam_id1').html(html);
								$('#exam_date1').val('');
								$('#exam_date1').val('');
							}
						}
					});
			});
			
			$('#exam_id1').on('change',function(){
				$('#exam_date1').val('');
			});
			
			$('#exam_date1').on('change',function(){
        		$('#submit-btn1').removeAttr('disabled','true');
        		$('#error_examdate1').text('');
        		var exam_date=$(this).val();
        		var exam_id=$('#exam_id1').val();
        		$.ajax({
        				type: "POST",
        				url: "<?php echo base_url(); ?>exams/exam_date_range",
        
        				data: ({exam_id:exam_id,exam_date:exam_date}),
        				dataType : "html",
        				success: function(html) {
        					
        					if(html==0)
        					{
        						$('#error_examdate1').text('<?php echo get_phrase('exam_date_must_be_within_defined_date_range');?>');
        						$('#submit-btn1').attr('disabled','true');
        					}
        					else{
        						$('#error_examdate1').text('');
        						$('#submit-btn1').removeAttr('disabled','true');
        					}
        				}
        
        
        			});
            });

		    $('#submit-btn1').click(function(e){
				//alert("here");
				$('#session').css('display','none');
				var section_id=$('#section_select_id1').val();
				var subject_id=$('#subject_select_id').val();
				var exam_date=$('#exam_date1').val();
				var exam_id=$('#exam_id1').val();
				var time_start=$('#time_start1').val();
				var time_end=$('#time_end1').val();	
				var yearly_term=$('#yearly_term2').val();
				var total_marks=$('#total_marks').val();
				//alert(section_id+subject_id+exam_date+exam_id+time_start+time_end+yearly_term);
				if(section_id!='' && subject_id!='' && yearly_term!='' && exam_id!='' && exam_date!=''){

					$.ajax({
							type: "POST",
							url: "<?php echo base_url(); ?>exams/exam_routine/do_update/<?php echo $exam_routine_id?>",

							data: ({section_id:section_id,subject_id:subject_id,exam_date:exam_date,exam_id:exam_id,time_start:time_start,time_end:time_end,yearly_term:yearly_term,total_marks:total_marks}),
				
							success: function(response) {
								console.log(response);
								if($.trim(response))
								{
									$('#modal_ajax').modal('hide');	
									$('#session').css('display','block');
									//$('#session').fadeOut('15000');
									$('#exam-routine').load("<?php echo base_url(); ?>exams/get_routine/",{section_id:section_id,subject_id:subject_id,yearly_term:yearly_term});
					
								}
							}
						});
				}
	
			});
		});
</script>