<style>
    .per_day{    border: 1px solid #e1dfdf; width:50px  !important;}
    .per_week{    border: 1px solid #e1dfdf; width:50px !important;}
</style>

<?php 
    $urlArr = explode('/',$_SERVER['REQUEST_URI']);
    $resArr=end($urlArr);
    $subject_id=$resArr;
?>
<div id="msg"></div>
<form id="subject_teacher" name="subject_teacher" method="post" class="form-horizontal form-groups-bordered validate">               
<table id="subject_teacher_add" cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
	<tr>
			<td colspan="7">			
				<div class="panel-title black2">
				<?php echo get_phrase('assign_section_to');?>
				<span class="text-white">
				<?php  echo get_subject_name($subject_id)?>
				</span>
					<input type="hidden" name="subject_id" id="subject_id" value="<?php echo $subject_id; ?>">
				</div>
			</td>
		</tr>
	<tr class="bold">
    	<td></td> 
    	<td colspan="2">
            <?php echo get_phrase('periods_per_day');?>
        </td>
    	<td colspan="2">
            <?php echo get_phrase('perionds_per_week');?>
        </td>
	</tr>	
	<tr class="bold">
	    <td style="text-align: left;"> <?php echo get_phrase('name');?></td> 
	    <td><?php echo get_phrase('assigned');?></td>	<td><?php echo get_phrase('total');?></td>	<td><?php echo get_phrase('assigned');?></td>	<td><?php echo get_phrase('total');?></td>	
	</tr>
		<?php
        $class_routine_arr=$this->db->query($q="select distinct cs.section_id from ".get_school_db().".class_routine_settings cs  inner join ".get_school_db().".class_routine cr on cr.c_rout_sett_id=cs.c_rout_sett_id where cr.subject_id=".$subject_id." AND cr.school_id=".$_SESSION['school_id']."")->result_array();
		foreach($class_routine_arr as $cr)
		{
			$all_sub[]=$cr['section_id'];
		}
        
        $query=$this->db->query("select  distinct cs.section_id,cs.title as section_name,d.departments_id,c.class_id,d.title as department_name,c.name as class_name from ".get_school_db().".class_section cs inner join ".get_school_db().".class c on c.class_id=cs.class_id inner join ".get_school_db().".departments d on d.departments_id=c.departments_id where  d.school_id=".$_SESSION['school_id']. ' order by d.title,c.name,cs.title asc ')->result_array();

        $sectionsChecked=$this->db->query("select section_id from ".get_school_db().".subject_section where subject_id=".$subject_id." AND school_id=".$_SESSION['school_id']."")->result_array();
		$match_arr=array();
		foreach($sectionsChecked as $arr)
		{
			$match_arr[]=$arr['section_id'];
		}
		 $ret_value= $query;  

        $new = array();	
			
        foreach ($ret_value as $a){
            $new[$a['department_name']][$a['class_name']][$a['section_id']] = $a['section_name'];
        }			
        
        foreach($new as $dep=>$cls){
	
            foreach($cls as $class=>$secArry){
	?> 
	
	<tr>
		<td colspan="5" ><span class="myttl"> <?php echo $class ; ?> </span> </td></tr>
	

	
	
	
	
	

	
	<?php foreach($secArry as $key=>$section)
	{$q2="select subject_id,section_id,periods_per_week,periods_per_day from ".get_school_db().".subject_section where school_id=".$_SESSION['school_id']." AND section_id=".$key."";
		$selected=$this->db->query($q2)->result_array();
		foreach($selected as $sel){
			$selArr['subject_id'][]=$sel['subject_id'];
			$selArr[$sel['subject_id']]['periods_per_day']=$sel['periods_per_day'];
			$selArr[$sel['subject_id']]['periods_per_week']=$sel['periods_per_week'];

		}
		?>
		
		
		<tr><td style='text-align:left;'><input type='checkbox' name='section_id' class='section' value="<?php echo $key; ?>" id='section_id' <?php if(in_array($key,$match_arr)){echo 'checked="checked"';	}
		//if(in_array($key,$all_sub)){echo "disabled='disabled'";}?>
		 >
		
		<?php echo $section;?></td>
		<td><?php echo (subject_count_class_routine_day($subject_id,$key)?subject_count_class_routine_day($subject_id,$key):0)?></td>
		
		
		
		
		
		<td>
		    <input style="width:60px" min="0" type="number"  class="form-control per_day" value="<?php echo $selArr[$subject_id]['periods_per_day'];?>" 
		           name="per_day" id="per_day<?php echo $key;?>"  maxlength="3" readonly>
		</td>
		
		
		
		
		
		<td><?php echo (subject_count_class_routine_week($subject_id,$key))?></td>	
		<td>
		    <input style="width:80px" type="number"  min="<?php echo subject_count_class_routine_week($subject_id,$key)?>" 
		           class="form-control per_week" value="<?php echo $selArr[$subject_id]['periods_per_week'];?>" 
		           name="per_week" id="per_week<?php echo $key;?>" maxlength="3" placeholder="Per Week" readonly></td>
		<input type="hidden" id="sub_id" value="<?php echo $key;?>" maxlength="3"  >
		</tr>
		
<?php }?>
	
<?php }

}?>

		</tr>
		<tr>
		    <td colspan="5">
				<p class="red">
                    <?php echo get_phrase('note');?>: 
                    <?php echo get_phrase('section_assigned_in_time_table_or_datesheet_can_not_be_unlinked');?><br><br>
                    To change per day and per week <a href="<?php echo base_url() ?>departments/section_listing">Click here</a>
                </p>
				<p class="error"></p>
				<div class="float-right">
                    <button type="button" id="submit-btn" class="modal_save_btn"><?php echo get_phrase('assign');?></button>
                    <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                        <?php echo get_phrase('cancel');?>
                    </button>
                </div>
			</td>
		</tr>
	
</table>
</form>
<script type="text/javascript">

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
			$('.section').change(function(){
				$('.error').text('');
			});
			
			
			$('#submit-btn').click(function(){
			    //alert("Ok");
				$('.error').text('');
					var subject_id=$('#subject_id').val();
					//var sub_id=$('#sub_id').val();
					var secArr={};
					var a=0;
	
					sectiontArr=$('.section:checked').serializeArray();
				if(sectiontArr.length>0){
				$.each(sectiontArr, function(i, field){
	 	
							secArr[a]={};
							secArr[a]['per_day']=$('#per_day'+field.value).val();
							secArr[a]['per_week']=$('#per_week'+field.value).val();
							secArr[a]['section']=field.value;
							a++;
						});
					//alert(section_id);
					if(section_id!=''){
		
						$.ajax({
							type: "POST",
							url: "<?php echo base_url(); ?>subject/assign_subject_section",
							data: ({subject_id:'<?php echo $subject_id?>',secArr:(JSON.stringify(secArr))}),					
							success: function(response) {
								//console.log(response);
								$('#modal_ajax').modal('hide');	
								$('#msg').text('<?php echo get_phrase('record_added'); ?>');		
							    location.reload();
							}
						});
					}	
				}
				
					
	
				});
		});
</script>