<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title  black2" >
					<i class="entypo-plus-circled"></i>
                    <?php echo get_phrase('manage_datesheet');?>
				</div>
			</div>

            <?php 
            
                $resArr='';
                $section_id='';
                $exam_id='';
                
                $urlArr=explode('/',$_SERVER['REQUEST_URI']);
                
                $resArr=explode('-',end($urlArr));
                if(sizeof($resArr)>1)
                {
                	$section_id=$resArr[0];
                	$exam_id=$resArr[1];
                	$date=$resArr[3].'-'.$resArr[4].'-'.$resArr[5];
                }
                $dept_title=section_hierarchy($section_id); 
                       
            ?>
            <br />
            <ul class="breadcrumb" style="display: inline;  padding: 2px;    margin-left: 5px;    color: #428abd;">
                <li>
                    <?php echo $dept_title['d']; ?>
                </li>
                <li>
                    <?php echo $dept_title['c']; ?>
                </li>
                <li>
                    <?php echo $dept_title['s'] ?>
                </li>
            </ul>
            <br />
            <p style="margin-left: 5px;">
            <?php
                $q="select * from ".get_school_db().".exam where school_id=".$_SESSION['school_id']." AND exam_id=".$exam_id;
                $exam_arr=$this->db->query($q)->result_array();
                echo "Exam type : ".$exam_arr[0]['name'].' ('.convert_date($exam_arr[0]['start_date']).' to '.convert_date($exam_arr[0]['end_date']).')';
                echo "<br>";
                echo "Date : ".$date;
            ?>
            </p>
            <div class="panel-body">
                <form name="exam_routine_form" id="exam_routine_form" class="form-horizontal validate">
            		<div class="form-group">
                        <label class="control-label"><?php echo get_phrase('subject');?><span class="red"> * </span></label>
                        <select id="subject_select_id" name="subject_id" class="form-control" style="width:100%;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <?php echo subject_option_list($section_id)?>
                        </select>
                    </div>
                    <div class="form-group" id="total_marks_div" style="display:none">
                        <label class="control-label"><?php echo get_phrase('Total marks');?><span class="red"> * </span></label>
                        <input type="text" class="form-control" id="total_marks" name="total_marks" placeholder="Total Marks" style="width:100%;" required="" data-message-required="<?php echo get_phrase('value_required');?>">
                    </div>
                    <div class="form-group" id="component-group" style="display:none">
                        <label class="col-sm-4 control-label"><?php echo get_phrase('components');?></label>
                        <div class="components col-sm-8"></div>
                        <div class="component-success"></div>
                    </div>            
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('start_time');?><span class="red"> * </span></label>
                        <input type="time" class="form-control barc" id="time_start" name="time_start" placeholder="08:00" style="width:100%;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">      
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('end_time');?><span class="red"> * </span></label>
                        <input type="time" class="form-control barc" id="time_end" name="time_end" placeholder="12:00" style="width:100%;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                    </div>              
                    <div class="form-group">
                        <div class="float-right">
                          <button id="submit-btn" type="submit" class="modal_save_btn"><?php echo get_phrase('save');?></button>
                        </div>
                	</div>
                </form>                
            </div>
        </div>
    </div>
</div>    

<script>
    
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

</script>
<script type="text/javascript">

$(document).ready(function(){
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
	
	$('#section_select_id').on('change',function(){
		var section_id=$(this).val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>exams/get_section_subject",

			data: ({section_id:section_id}),
			dataType : "html",
			success: function(html) {
				if(html !=''){
				    $('#subject_select_id').html(html);
				}	
			}
		});
	});
    $('#subject_select_id').on('change',function(){
		var subject_id=$(this).val();
		$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>exams/check_total_marks",

				data: ({subject_id:subject_id}),
				dataType : "html",
				success: function(html) {
					
					if(html!='')
					{
						$('#total_marks_div').css('display','none');
						$('#component-group').show();
						$('.components').html(html);
					}

					else{
						$('#component-group').hide();
						$('#total_marks_div').css('display','block');
						$('#total_marks').attr('data-validate','required');
					}
					}
			});
	});
	
	$('#yearly_term1').on('change',function(){
		var yearly_term=$(this).val();
		$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>exams/get_exam_type",

				data: ({yearly_term:yearly_term}),
				dataType : "html",
				success: function(html) {
					//alert(html);
					if(html !=''){
					$('#exam_id').html(html);
					$('#exam_date').val('');
					$('#error_examdate').text('');
					
					}
					
				}


			});
	});
	$('#exam_date').on('change',function(){
		$('#submit-btn').removeAttr('disabled','true');
		$('#error_examdate').text('');
		var exam_date=$(this).val();
		var exam_id=$('#exam_id').val();
		$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>exams/exam_date_range",

				data: ({exam_id:exam_id,exam_date:exam_date}),
				dataType : "html",
				success: function(html) {
					
					if(html==0)
					{
						$('#error_examdate').text('<?php echo get_phrase('exam_date_must_be_within_defined_date_range'); ?>');
						$('#submit-btn').attr('disabled','true');
					}
					else{
						$('#error_examdate').text('');
						$('#submit-btn').removeAttr('disabled','true');
					}
				}


			});
	});

    $('#submit-btn').on('click',function(e){
	
	$('#session').css('display','none');
	var section_id='<?php echo $section_id;?>';
	var subject_id=$('#subject_select_id').val();
	var exam_date='<?php echo $date;?>';
	var exam_id='<?php echo $exam_id;?>';
	var time_start=$('#time_start').val();
	var time_end=$('#time_end').val();	
	var total_marks=$('#total_marks').val();
	
	
		if(section_id!='' && subject_id!='' && exam_id!='' && exam_date!='' && time_start!='' && time_end!=''){
		
	
	$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>exams/exam_routine/create",

					data: ({section_id:section_id,subject_id:subject_id,exam_date:exam_date,exam_id:exam_id,time_start:time_start,time_end:time_end,total_marks:total_marks}),
					
					success: function(response) {
					
						if($.trim(response))
						{
							
						
						$('#modal_ajax').modal('hide');	
						$('#session').css('display','block');
						
						$('#exam-routine').load("<?php echo base_url(); ?>exams/get_routine/",{section_id:section_id,subject_id:subject_id,exam_id:exam_id});
						
						}
						
						
					}


				});
	}
		
	});
	//yearly_term:yearly_term
	$(document).on('change','.comp',function(){
		$('.component-success').text('');
		var subject_id=$('#subject_select_id').val();
		var subject_component_id=$(this).attr('id');
		var marks=$(this).val();
		if(marks!='' && marks <=100){
		
		$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>exams/update_components/",

					data: ({subject_id:subject_id,subject_component_id:subject_component_id,marks:marks}),
					
					success: function(response) {
						
						if($.trim(response)=='updated')
						{
						$('.component-success').text("<?php echo get_phrase('component_updated'); ?>");
						}
						}
		});}
	});
});
	
</script>  