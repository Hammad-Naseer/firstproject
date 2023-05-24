<?php
    $teacher_id;
    if($_POST['teacher_id'])
    {
    	$teacher_id=$_POST['teacher_id'];
    }
    $subj_categ_id=$_POST['subj_categ_id'];
    if(isset($subj_categ_id) && $subj_categ_id > 0)
    {
    	$subj_categ_query=" INNER JOIN ".get_school_db().".subject_category sc
    ON s.subj_categ_id=sc.subj_categ_id ";
    $subj_categ=" AND sc.subj_categ_id=".$subj_categ_id;
    }
    $q="SELECT * from ".get_school_db().".subject s ".$subj_categ_query." where s.school_id=".$_SESSION['school_id']."".$subj_categ;
    $subject_list=$this->db->query($q)->result_array();
?>
<table id="subject_teacher_add" cellpadding="0" cellspacing="0" border="0" class="table">
	<form id="subject_teacher" name="subject_teacher" method="post" class="form-horizontal form-groups-bordered validate">
		<tr><input type="hidden" name="teacher_id" id="teacher_id" value="<?php echo $teacher_id; ?>">
			<td  style="width:34px !important;"><?php echo get_phrase('add');?></td><td><?php echo get_phrase('subject');?></td>
		</tr>
		<?php 
		$ids=$this->db->query("select subject_id from ".get_school_db().".subject_teacher where teacher_id=".$teacher_id." and school_id=".$_SESSION['school_id']."")->result_array();
		$result_subject=$this->db->query($q="SELECT distinct st.subject_id FROM ".get_school_db().".`subject_teacher` st inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.subject_teacher_id=st.subject_teacher_id where st.teacher_id=".$teacher_id." and st.school_id=".$_SESSION['school_id']."")->result_array();
		
		foreach($result_subject as $t)
		{
			$sub_arry[]=$t['subject_id'];
		}
		foreach($ids as $id)
		{
			$arr[]=$id['subject_id'];
		}
		foreach($subject_list as $list){?>
		<tr>
		<td>
			<input <?php if(in_array($list['subject_id'],$sub_arry)){echo "disabled='disabled'";}?> type="checkbox" name="subject_id" class="subject" id="subject_id" value="<?php echo $list['subject_id']?>" <?php if(in_array($list['subject_id'],$arr)){echo "checked='checked'";}?>>
			
		</td>
			<td><?php echo $list['name'].' ( '.$list['code'].' )'?></td>
		</tr>
		<?php }?>
		<tr><td colspan="7" align="right">
				<p><?php echo get_phrase('note');?>: <?php echo get_phrase('subjects_assigned_to_timetable_cannot_be_unlinked');?></p>
				<p class="error"></p>
				<div class="float-right">
					<button type="submit" id="submit-btn" class="modal_save_btn">
						<?php echo get_phrase('assign');?>
					</button>
					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
						<?php echo get_phrase('cancel');?>
					</button>
				</div>
			</td></tr>
	</form>
</table>

<script>
$(document).ready(function(){
$('.error').text('');	
$('.per_day').change(function(){
					if(Number($(this).val())<Number($(this).attr('min')))
					{
						$(this).css('border','1px solid red');
						$('#submit-btn').attr('disabled',true);
					}

					else{
						$(this).css('border','1px solid');
						$('#submit-btn').removeAttr('disabled');
					}
				});
			$('.per_week').change(function(){
					if(Number($(this).val())<Number($(this).attr('min')) )
					{
						$(this).css('border','1px solid red');
						$('#submit-btn').attr('disabled',true);
					}
					else{
						$(this).css('border','1px solid');
						$('#submit-btn').removeAttr('disabled');
					}
				});

$('.subject').change(function(){
				$('.error').text('');
			});	
$('#submit-btn').click(function(){

                   // alert('hi');
					var teacher_id=$('#teacher_id').val();
					var sub_id=$('#sub_id').val();
					var subArr={};
					var a=0;
	
					subjectArr=$('.subject:checked').serializeArray();
			if(subjectArr.length>=0){
					$.each(subjectArr, function(i, field){
	 	
							subArr[a]={};
							subArr[a]['subject']=field.value;
							a++;
						});
					if(teacher_id!=''){

						$.ajax({
								type: "POST",
								url: "<?php echo base_url();?>subject/assign_teacher_subject",

								data: ({teacher_id:teacher_id,subArr:(JSON.stringify(subArr))}),					
								success: function(response) {
									//console.log(response);

									$('#modal_ajax').modal('hide');	
									$('#msg').text('<?php echo get_phrase("record_added");?>');

									$("#submit-btn").attr('disabled',true);
                                   // alert('hi');
									location.reload();
									
								}

							});
					}
					}
					else{
						
					}
	
				});
});	
</script>