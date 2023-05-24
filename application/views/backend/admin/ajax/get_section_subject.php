
<?php
$subj_categ_id=$_POST['subj_categ_id'];
if(isset($_POST['section_id']))
{
	$section_id=$_POST['section_id'];
}
if(isset($subj_categ_id) && $subj_categ_id > 0)
{
	$subj_categ_query=" INNER JOIN ".get_school_db().".subject_category sc
ON s.subj_categ_id=sc.subj_categ_id ";
$subj_categ=" AND sc.subj_categ_id=".$subj_categ_id;
}

$q="SELECT * from ".get_school_db().".subject s ".$subj_categ_query." where s.school_id=".$_SESSION['school_id']."".$subj_categ;
	$subject_list=$this->db->query($q)->result_array();
	$section_detail=(section_hierarchy($section_id));
?>


<div id="msg"></div>



<table id="subject_teacher_add" cellpadding="0" cellspacing="0" border="0" class="table table-responsive">
    <form id="subject_teacher" name="subject_teacher" method="post" class="form-horizontal form-groups-bordered validate">
        <tr class="bold center">
           <td></td>
            <td></td>
            <td colspan="2"><?php echo get_phrase('periods_per_day');?></td>
            <td colspan="2"><?php echo get_phrase('periods_per_week');?></td>
        </tr>
        <tr class="bold center">
            <td><?php echo get_phrase('add');?></td>
            <td><?php echo get_phrase('subject');?></td>
            <td><?php echo get_phrase('assigned');?></td>
            <td><?php echo get_phrase('total');?></td>
            <td><?php echo get_phrase('assigned');?></td>
            <td><?php echo get_phrase('total');?></td>
        </tr>
        <?php 
            if(count($subject_list)==0)
    	    {
    		    echo "<tr><td colspan=5>No subject found</td></tr>";
    	    }
    		$q2="select subject_id,section_id,periods_per_week,periods_per_day from ".get_school_db().".subject_section where school_id=".$_SESSION['school_id']." AND section_id=".$section_id."";
    		//exit;
    		$selected=$this->db->query($q2)->result_array();
    		foreach($selected as $sel){
    			$selArr['subject_id'][]=$sel['subject_id'];
    			$selArr[$sel['subject_id']]['periods_per_day']=$sel['periods_per_day'];
    			$selArr[$sel['subject_id']]['periods_per_week']=$sel['periods_per_week'];
    		}
    		$i = 1;
		?>
		<tr>
		<?php foreach($subject_list as $row){?>
       <td>
       	<input <?php if(subject_count_class_routine_day($row[ 'subject_id'],$section_id)>0)echo "checked='checked'";else if(in_array($row['subject_id'],$selArr['subject_id'])){echo "checked='checked'";}?> type="checkbox" class="subject" name="subject_id[]" id="subject_id" value="<?php echo $row['subject_id'];?>">
       </td>   
        <td>
           <?php echo  $row['name'].' ( '.$row['code'].' )';?>
        </td>
        <td>
            <?php echo (subject_count_class_routine_day($row['subject_id'],$section_id)?subject_count_class_routine_day($row['subject_id'],$section_id):0)?>
        </td>
        <td>
            <input style="width:60px" min="<?php echo subject_count_class_routine_day($row['subject_id'],$section_id)?>" type="number" class="per_day" value="<?php echo $selArr[$row['subject_id']]['periods_per_day'];?>" name="per_day" id="per_day<?php echo $row['subject_id'];?>" maxlength="3"></input>
        </td>
        <td>
            <?php echo (subject_count_class_routine_week($row['subject_id'],$section_id))?>
        </td>
        <td>
            <input style="width:80px" type="number" min="<?php echo subject_count_class_routine_week($row['subject_id'],$section_id)?>" class="per_week" value="<?php echo $selArr[$row['subject_id']]['periods_per_week'];?>" name="per_week" id="per_week<?php echo $row['subject_id'];?>" maxlength="3"></input>
            <input type="hidden" id="sub_id" value="<?php echo $row['subject_id'];?>" maxlength="3"></input>
        </td>
        <?php 
            if($i % 1 == 0) echo "</tr><tr>";
		    	$i++;
		    }
		    echo "</tr>";
		?>
        <tr>
            <td colspan="7">
                <p class="red"><?php echo get_phrase('note');?>: <?php echo get_phrase('subjects_assigned_to_timetable_cannot_be_unlinked');?></p>
                <!--<input type="submit" id="submit-btn" class="modal_save_btn" value="<?php echo get_phrase('assign');?>" style="float:right;"></input>-->
                <div class="float-right">
                    <button type="submit" id="submit-btn" class="modal_save_btn"><?php echo get_phrase('assign');?></button>
                    <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                        <?php echo get_phrase('cancel');?>
                    </button>
                </div>
            </td>
        </tr>
    </form>
</table>

<script type="text/javascript">
$(document).ready(function() {
    $('.per_day').change(function() {
        if (Number($(this).val()) < Number($(this).attr('min'))) {
            $(this).css('border', '1px solid red');
            $('#submit-btn').attr('disabled', true);
        } else {
            $(this).css('border', '1px solid');
            $('#submit-btn').removeAttr('disabled');
        }
    });
    $('.per_week').change(function() {
        if (Number($(this).val()) < Number($(this).attr('min'))) {
            $(this).css('border', '1px solid red');
            $('#submit-btn').attr('disabled', true);
        } else {
            $(this).css('border', '1px solid');
            $('#submit-btn').removeAttr('disabled');
        }
    });
    $('#submit-btn').click(function() {
        var section_id = $('#section_id').val();
        var sub_id = $('#sub_id').val();
        var subArr = {};
        var a = 0;

        subjectArr = $('.subject:checked').serializeArray();

        $.each(subjectArr, function(i, field) {

            subArr[a] = {};
            subArr[a]['per_day'] = $('#per_day' + field.value).val();

            subArr[a]['per_week'] = $('#per_week' + field.value).val();
            subArr[a]['subject'] = field.value;
            a++;
        });
        if (section_id != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>subject/assign_section_subject",
                data: ({
                    section_id: section_id,
                    subArr: (JSON.stringify(subArr))
                }),
                success: function(response) {
                    //console.log(response);
                    $('#modal_ajax').modal('hide');
                    $('#msg').text('<?php echo get_phrase("record_added");?>');
                    location.reload();
                }
            });
        }
    });
});
</script>