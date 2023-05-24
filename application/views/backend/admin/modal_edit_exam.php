<?php 
    $query="SELECT e.* , y.yearly_terms_id, y.academic_year_id,a.title as acad_title,a.start_date as acad_start_date,a.end_date as acad_end_date FROM ".get_school_db().".exam e INNER JOIN ".get_school_db().".yearly_terms y ON y.yearly_terms_id = e.yearly_terms_id INNER JOIN ".get_school_db().".acadmic_year a ON a.academic_year_id = y.academic_year_id where e.school_id=".$_SESSION['school_id']."  and exam_id=".$param2."";
    $edit_data = $this->db->query($query)->result_array();
    foreach ( $edit_data as $row):
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_exam');?>
            	</div>
            </div>
			<div class="panel-body">	
                <?php echo form_open(base_url().'exams/exam/edit/do_update/'.$row['exam_id'] , array('id'=>'exam_edit','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                    
                    <div class="form-group">
                        <!--<label class="control-label" ><span class="red"> * </span></label>-->
                        <label id="term_id1_selection" style="font-size:11px; margin-top:-19px;"> <?php echo get_phrase('academic_year');?> <?php echo $row['acad_title']."(".date('d-M-Y',strtotime($row['acad_start_date']))." to ".date('d-M-Y',strtotime($row['acad_end_date'])).")";?></label>	
                        <select id="term_id1" name="term_id" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">               
                            <?php
                                $status_year=array(1,3);
                       			$status_term=array(1); 
                                echo yearly_term_selector($row['yearly_terms_id'],$status_year,$status_term);
                            ?>
         				</select>
         			</div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('name');?><span class="red"> * </span></label>
                        <input type="text" class="form-control" name="name" value="<?php echo $row['name'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('start_date');?><span class="red"> * </span></label>
                        <input type="date" class="form-control" id="start_date" name="start_date" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['start_date']; ?>"   data-format="dd/mm/yyyy">
                        <div id="error_start"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('end_date');?><span class="red"> * </span></label>
                            <input type="date" class="form-control" id="end_date" name="end_date" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['end_date']; ?>"   data-format="dd/mm/yyyy">
                            <div id="error_end"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('comment');?></label>
                        <input type="text" class="form-control" name="comment" value="<?php echo $row['comment'];?>"/>
                    </div>
                    <div class="form-group">
                        <div class="float-right">
        					<button type="submit" id="submit" class="modal_save_btn">
        						<?php echo get_phrase('save');?>
        					</button>
        					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
        						<?php echo get_phrase('cancel');?>
        					</button>
        				</div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php endforeach; ?>
<script>
$(document).ready(function(){
	//$('.selectpicker').selectpicker();
    $('.selectpicker').on('change', function (){
		var id=$(this).attr('id');
		var selected = $('#'+ id +' :selected');
		var group = selected.parent().attr('label');
		$('#'+ id + '_selection').text(group);
    });
	$('#start_date').on('change',function(){
			$('#submit').removeAttr('disabled','true');
			$('#error_start').text('');
			var start_date=$(this).val();
			var term_id=$('#term_id1').val();
			if(start_date!='')
			{
			$.ajax({
					type: "POST",
url: "<?php echo base_url();?>exams/term_date_range",
data: ({start_date:start_date,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						
						if(html==0){
						 $('#error_start').text('<?php echo get_phrase('start_date_should_be_between_term_range');?>');
						 $('#submit').attr('disabled','true');			
						}					
					}
				});
				}
		
	});
	
$('#end_date').on('change',function(){
		$('#submit').removeAttr('disabled','true');
			 $('#error_end').text('');
			var end_date=$(this).val();
			var term_id=$('#term_id1').val();
			if(end_date!='')
			{
			$.ajax({
					type: "POST",
url:"<?php echo base_url(); ?>exams/term_date_range",
data: ({end_date:end_date,term_id:term_id}),
dataType : "html",
success: function(html) {
	
	
	
if(html==0){
$('#error_end').text('<?php echo get_phrase('end_date_should_be_between_term_range');?>');	 $('#submit').attr('disabled','true');	
	}					
					}
				});
				}
				
	});
	
	$('#term_id1').change(function(){
	
		$('#submit').removeAttr('disabled');
		var term_id=$(this).val();
		var start_date=$('#start_date').val();
		var end_date=$('#end_date').val();
		if(end_date!='')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>exams/term_date_range",

					data: ({end_date:end_date,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_end').text('<?php echo get_phrase('end_date_should_be_between_term_range');?>');		
						 $('#submit').attr('disabled','true');	
						}	else{
							$('#submit').removeAttr('disabled');
						}					
					}
				});
				}
				if(start_date!='')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>exams/term_date_range",

					data: ({start_date:start_date,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_start').text('<?php echo get_phrase('start_date_should_be_between_term_range');?>');
						 $('#submit').attr('disabled','true');			
						}	
						else{
							$('#submit').removeAttr('disabled');
						}					
					}
				});
				}
		
	});




		
});
</script>

<script>
 	 $("#end_date").change(function () {
    	$('#submit').removeAttr('disabled','true');
    var startDate = document.getElementById("start_date").value;
    var endDate = document.getElementById("end_date").value;
 
    if ((Date.parse(startDate) > Date.parse(endDate))) {
        $('#error_end').text("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>");
        $('#submit').attr('disabled','true');	

}
});
			
$("#end_date").change(function () {
    	$('#submit').removeAttr('disabled','true');
    var startDate = document.getElementById("start_date").value;
    var endDate = document.getElementById("end_date").value;
 
    if ((Date.parse(endDate) < Date.parse(startDate))) {
        $('#error_end').text("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>");
        $('#submit').attr('disabled','true');	
       // document.getElementById("end_date").value = "";
        
        
    }
});		
				
$("#start_date").change(function () {
	$('#submit').removeAttr('disabled','true');
    var startDate = document.getElementById("start_date").value;
    var endDate = document.getElementById("end_date").value;
 
    if ((Date.parse(endDate) < Date.parse(startDate))){
         $('#error_start').text("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>");
       $('#submit').attr('disabled','true');	
        //document.getElementById("start_date").value = "";

    }
});
			
$("#start_date").change(function () {
	$('#submit').removeAttr('disabled','true');
    var startDate = document.getElementById("start_date").value;
    var endDate = document.getElementById("end_date").value;
  if ((Date.parse(startDate) > Date.parse(endDate))) {
$('#error_start').text("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>");
       $('#submit').attr('disabled','true');	
        //document.getElementById("start_date").value = "";

    }
});


</script>

