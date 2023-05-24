
<style>
.per_day {
    border: 1px solid #EEE;
    width: 50px !important;
}

.per_week {
    border: 1px solid #EEE;
    width: 50px !important;
}
</style>
<?php 
    
    $section_id = $this->uri->segment('4');
    $diary_id = $this->uri->segment('5');
    $subject = $this->uri->segment('6');
    


    $selected_qry = $this->db->query("select ds.student_id from 
        ".get_school_db().".diary_student ds
        inner join ".get_school_db().".student s on s.student_id = ds.student_id
        where
        ds.diary_id = $diary_id
        and s.student_status in (".student_query_status().")
        and s.section_id = $section_id
        and s.school_id = ".$_SESSION['school_id']."
        ")->result_array();
    
    foreach ($selected_qry as $key => $value) 
    {
        $selected_student[] = $value['student_id'];
    }

    $is_submitted = $this->db->query("select * from ".get_school_db().".diary
                where
                diary_id = $diary_id
                and school_id = ".$_SESSION['school_id']."
                "
                )->result_array();
                
?>
<div id="msg"></div>
<?php echo form_open_multipart(base_url().'diary/diarys/assign_subjects/', array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'id'=>'diary_edit_form'));?>

<table id="subject_teacher_add" cellpadding="0" cellspacing="0" border="0" class="table table-bordered">  
    <tr>
        <td colspan="7">   
        
        <?php
        $subj_array=get_subject_name($subject,1);
    	//print_r($subj_array);
        ?>
            
            
            <div class="myttl"><?php echo $subj_array['name'].'-'.$subj_array['code']; ?></div>
            <div><strong><?php echo get_phrase('title');?>: </strong><?php echo $is_submitted[0]['title']; ?></div>
            <div> <strong><?php echo get_phrase('assign_date');?>: </strong><?php echo convert_date($is_submitted[0]['assign_date']); ?></div>
            
            
            
            
            
            <div> <strong><?php echo get_phrase('department');?> / <?php echo get_phrase('class');?> / <?php echo get_phrase('section');?>: </strong>
            
            
            <?php $sec_array = section_hierarchy($section_id); ?>
            
            <ul class="breadcrumb breadcrumb2">
            	
            	<li><?php echo $sec_array['d'];?> </li>
            	<li><?php echo $sec_array['c'];?> </li>
            	<li><?php echo $sec_array['s'];?> </li>
            	
            </ul>
				
				
			</div>
            
            
            
         
                
        </td>
    </tr>
    
    <tr class="bold center">
        <td><input type="checkbox" id="selectall"></td>
        <td><?php echo get_phrase('roll_no');?></td>
        <td><?php echo get_phrase('student');?></td>
    </tr>
    <?php 
        $query = $this->db->query("select student_id, name, roll
            from ".get_school_db().".student
            where 
            section_id=$section_id 
            and school_id=".$_SESSION['school_id']."
            and student_status IN (".student_query_status().")
        ")->result_array();
        
        if(count($query)==0)
        {
			echo '<td colspan=2>No student enrolled in the selected section.</td>';
		}
       
        if(count($query) > 0)
        {?>
            <?php
            foreach($query as $rows)
            {
                if ($is_submitted[0]['is_submitted'] == 0)
                {
                    echo '<tr>';
                    $opt_selected='';
                    if( in_array($rows['student_id'], $selected_student))
                    {
                        $opt_selected="checked";
                    }
                    echo '<td>
                            <input type="checkbox" class="checkbox" name="student_id[]" id="student_id[]" value="'.$rows['student_id'].'" '.$opt_selected.' > 
                        </td>';

                    echo '<td>'.$rows['roll'].'</td>';
                    echo '<td>'.$rows['name'].'</td>';
                    echo '</tr>';
                }
                else
                {
                    if( in_array($rows['student_id'], $selected_student))
                    {
                        echo '<tr>';
                        $opt_selected="checked";
                        echo '<td>
                            <input type="checkbox" name="student_id[]" id="student_id[]" value="'.$rows['student_id'].'" checked disabled="true" > 
                        </td>';
                        echo '<td>'.$rows['roll'].'</td>';
                        echo '<td>'.$rows['name'].'</td>';
                        echo '</tr>';
                    }
                }
            }

           
            if ($is_submitted[0]['is_submitted'] == 0)
            {
            ?>
                <tr>
                    <td colspan="7">
                        <input type="hidden" name="diary_id" id="diary_id" value="<?php echo $diary_id ?>">
                        <span class="alert alert-danger" id="error_msg">
                        <?php echo get_phrase('please_select_aleast_one_student');?>
                        </span>
                        <div class="float-right">
                            <button type="submit" class="modal_save_btn"  id="submit-btn">
                            <?php echo get_phrase('assign');?>
                            </button>
                            <button type="submit" class="modal_save_btn"  id="submit-btn1">
                            <?php echo get_phrase('Assign_and_Submit_diary');?>
                            </button>
                            <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                            <?php echo get_phrase('cancel');?>
                            </button>
                        </div>
                        <input type="hidden" name="is_submitted1" 
                        id="is_submitted1" value="<?php echo get_phrase('submitt');?>" >
                    </td>
                </tr>
            <?php
            }?>
           
        <?php
        }
        
        ?>
</table>









</form>
<script>

	/////////////////////////////////////////////////////////////////////
	
	
		
	$(document).ready(function(){
		$("#error_msg").hide();
// onclick="return submit_func();"
	$('#submit_btn1').click(function()
    {
        if (confirm('If diary is submitted then it cannot be changed. Are you sure to submit?'))
        {
        	var ck_box =$('input[type="checkbox"]:checked').length;
    		if(ck_box == 0)
    		{
				$("#error_msg").show();
       			event.preventDefault();
			}
    		else if(ck_box > 0)
    		{
        		$("#error_msg").hide();
        		$('#is_submitted1').val(1);
        		//$("#diary_edit_form").submit();
    		}
            
            //$('#diary_edit_form').submit();
        }
        else
        {
            return false;
        }
    });
		
		$("#submit-btn").click(function()
		{
			
			var ck_box =$('input[type="checkbox"]:checked').length;
    		if(ck_box == 0)
    		{
				$("#error_msg").show();
       			event.preventDefault();
			}
    		else if(ck_box > 0)
    		{
        		$("#error_msg").hide();
        		//$("#diary_edit_form").submit();
    		}
		});
		
		
		$("#selectall").change(function(){
			
			
			$(".checkbox").prop('checked',$(this).prop("checked"));
			
		});
		
			
		$(".checkbox").change(function(){
			
			
		$("#selectall").prop("checked", false);
			
		});
	});


		
		
	
	
	//////////////////////////////////////////////////////////////



</script>