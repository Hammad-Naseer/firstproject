<?php 
    $urlArr=explode('/',$_SERVER['REQUEST_URI']);
    $resArr=explode('-',end($urlArr));
    $subject_id=$resArr[0];
    $class_routine_id=$resArr[1];
    $section_id=$resArr[2];
    $day=$resArr[3];
    $period_num=$resArr[4];
    $c_rout_setting_id=$resArr[5];
?>

<div class="tab-pane box" id="add" style="padding: 5px">
    <div class="panel-heading">
		<div class="panel-title black2" >
            <?= 'Assign teacher for '.get_subject_name($subject_id); ?>
		</div>
	</div>
    <?php  
        
        $q2= "SELECT s.name as teacher,s.staff_id as teacher_id,s.periods_per_week,s.periods_per_day,st.subject_teacher_id 
			FROM ".get_school_db().".staff s	
			INNER JOIN ".get_school_db().".subject_teacher st ON st.teacher_id=s.staff_id 
			INNER JOIN ".get_school_db().".designation d  ON (s.designation_id = d.designation_id AND d.is_teacher=1)	
			WHERE 
			st.subject_id=$subject_id 
			and st.school_id=".$_SESSION['school_id']."";

	    $teachers=$this->db->query($q2)->result_array();
     	$q3="select st.teacher_id, cs.section_id 
    	 	from  ".get_school_db().".class_routine cr  
    	 	inner join ".get_school_db().".class_routine_settings cs on cr.c_rout_sett_id=cs.c_rout_sett_id 
    	 	inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id 
    	 	inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id	
    	 	where 
    	 	cr.day='".$day."' 
    	 	and cr.subject_id=$subject_id
    	 	and cr.period_no=$period_num 
    	 	and st.school_id=".$_SESSION['school_id']." 
    	 	";
    	 	/*and cs.section_id<>".$section_id."*/
        $assigned=$this->db->query($q3)->result_array();
    
    //print_r($assigned);
    $disabled_teachers = array();
    if(sizeof($assigned)>0)
    {
     	foreach($assigned as $a)
     	{
			$assigned_teachers[]=$a['teacher_id'];
			if ($a['section_id'] != $section_id)
			{
				$disabled_teachers[]=$a['teacher_id'];
			}
		}
	}
	    
	   
	$i = 1;
	$str='';
	if(sizeof($teachers)>0)
	{
	?>
    <table border='1' style='margin-top:10px;text-align:center;' class='table table-bordered'>
        <form name='subject_teacher' id='subject_teacher' method='post' class='form-horizontal form-groups-bordered validate'>
            <tr>
                <td>#</td>
                <td><?php echo get_phrase('teacher');?></td>
                <td><?php echo get_phrase('per_day');?></td>
                <td><?php echo get_phrase('er_week');?></td>
                <td><?php echo get_phrase('status');?></td>
            </tr>
            <?php  
            foreach($teachers as $row)
            {
		    	$dis='';
		    	$assigned_var='Available';
				$period_week_count='';
				$period_day_count='';
				$checked='';
	
				$week="SELECT count(st.teacher_id) as count FROM ".get_school_db().".time_table_subject_teacher ttst 
				inner join ".get_school_db().".class_routine cr on cr.class_routine_id=ttst.class_routine_id 
				inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
				where st.teacher_id=".$row['teacher_id']." and st.school_id=".$_SESSION['school_id']."";
				
				$perids_per_week=$this->db->query($week)->result_array();
				$period_week_count=$perids_per_week[0]['count'].' / '.$row['periods_per_week'];
				 $day1="SELECT count(st.teacher_id) as count FROM ".get_school_db().".time_table_subject_teacher ttst 
				inner join ".get_school_db().".class_routine cr on cr.class_routine_id=ttst.class_routine_id 
				inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
				where st.teacher_id=".$row['teacher_id']." and cr.day='".$day."' and st.school_id=".$_SESSION['school_id']."";
				
				$perids_per_day=$this->db->query($day1)->result_array();
				$period_day_count=$perids_per_day[0]['count'].' / '.$row['periods_per_day'];
				
			
				if(in_array($row["teacher_id"],$assigned_teachers) )
				{
					$assigned_var="Assigned";
					$checked="checked=checked";

					if(in_array($row["teacher_id"],$disabled_teachers))
					{
						$dis="disabled";
						$assigned_var="Assigned In Other Section";
					}
				}
				else
				{
				
					if($perids_per_week[0]['count']>=$row['periods_per_week'])
					{
						$dis="disabled";
						$assigned_var="Weekly Limit Reached";
					}
					else
					{
						
						if($perids_per_day[0]['count']>=$row['periods_per_day'])
						{
							$dis="disabled";
							$assigned_var="Daily Limit Reached";
						}
					}
				}	
					
			?>
            <tr>
                <td>
                    <input class="teacher" <?php echo $checked;?>
                    <?php echo $dis;?>  type="checkbox" name="teachers" id="teacher" value="<?php echo $row['subject_teacher_id']?>"></input>
                </td>
                <td>
                    <?php echo $row['teacher'];?>
                </td>
                <td>
                    <?php echo $period_day_count;?>
                </td>
                <td>
                    <?php echo $period_week_count;?>
                </td>
                <td>
                    <?php echo $assigned_var;?>
                </td>
            </tr>
            <?php if ($i % 3== 0) echo "<tr>";
 	$i++;
	}
	}?>
            <td colspan='5' align='right'>
                <div id="error"></div>
                <div class="float-right">
					<button type="submit" id='submit-btn' class="modal_save_btn">
						<?php echo get_phrase('assign');?>
					</button>
					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
						<?php echo get_phrase('cancel');?>
					</button>
				</div>
            </td>
            </tr>
        </form>
    </table>
</div>
<script type="text/javascript">
$(document).ready(function() {

    document.getElementById('subject_teacher').onsubmit = function() {
        return false;
    };
    $('#submit-btn').click(function(e) {


        var class_routine_id = '<?php echo $class_routine_id;?>';
        var subject_id = $('#subject_id').val();
        var teacher_id = $('.teacher:checked').serializeArray();

        $('form#subject_teacher').validate();
        if (class_routine_id != '' && subject_id != '') {

            $('#submit-btn').attr('disabled',true);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>time_table/edit_assign_teacher",

                data: ({
                    class_routine_id: class_routine_id,
                    subject_id: subject_id,
                    teacher_id: teacher_id
                }),
                success: function(response) 
                {
                   //alert('dddd');
                    //$('#submit-btn').attr('disabled',true);
                    $('#modal_ajax').modal('hide');
                    load_table('<?php echo $c_rout_setting_id;?>', '<?php echo $section_id;?>',true);
                    // location.reload();	
                    //$("#select").trigger('click');
                }

            });
        }
        /*else{
        	$('#error').html("<b>select atleast one teacher to continue</b>");
        }*/
    });

});
</script>
