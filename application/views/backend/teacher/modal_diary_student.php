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
     $selected_student = array();
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

<form action="<?php echo base_url() ?>teacher/diary/assign_subjects" method="post" id="assign_subject">
<table id="subject_teacher_add" cellpadding="0" cellspacing="0" border="0" class="table">
    
    <tr>
        <td colspan="7">   
            <h3><?php echo $subject; ?></h3>
            <strong>Title: </strong><?php echo $is_submitted[0]['title']; ?><br> 
            <strong>Assigned Date: </strong><?php echo convert_date($is_submitted[0]['assign_date']); ?><br> 
        </td>
    </tr>
    <tr class="bold center">
        <td><input type="checkbox" onClick="toggle(this)" id="select_all">Select All</td>
        <td>Roll No</td>
        <td>Student</td>
    </tr>
    <?php 
        $query = $this->db->query("select student_id, name, roll 
            from ".get_school_db().".student
            where 
            section_id=$section_id 
            and school_id=".$_SESSION['school_id']."
            and student_status IN (".student_query_status().")
        ")->result_array();
       
        if(count($query) > 0)
        {?>
            <?php
            foreach($query as $rows)
            {
                if ($is_submitted[0]['is_submitted'] == 0)
                {
                    echo '<tr>';
                    $opt_selected='';
                    if (is_array($selected_student)) {
                        if( in_array($rows['student_id'], $selected_student , true))
                        {
                            $opt_selected="checked";
                        }
                    }
                    echo '<td>
                            <input type="checkbox" class="std_checkbox" name="student_id[]" value="'.$rows['student_id'].'" '.$opt_selected.' data-validate="required" data-message-required="value required"> 
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
                            <input type="checkbox" class="std_checkbox" name="student_id[]" value="'.$rows['student_id'].'" checked disabled="true" > 
                        </td>';
                        echo '<td>'.$rows['roll'].'</td>';
                        echo '<td>'.$rows['name'].'</td>';
                        echo '</tr>';
                    }
                }
            }
            ?>
            <tr style="text-align: center;">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                    <div <?= check_sms_preference(3,"style","sms") ?>>
                        <?php echo get_phrase('send_sms');?> : 
                        <input type="checkbox" id="send_message" name="send_message" class="" value="0"  />
                    </div>
                </td>
            </tr>
            <tr style="text-align: center;">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                    <div <?= check_sms_preference(3,"style","email") ?>>
                        <?php echo get_phrase('send_email');?> : 
                        <input type="checkbox"  id="send_email" name="send_email" class="" value="0"  />
                    </div>    
                </td>
            </tr>
           <?php
            if ($is_submitted[0]['is_submitted'] == 0)
            {
            ?>
                <tr>
                    <td colspan="7">
                        <input type="hidden" name="diary_id" value="<?php echo $diary_id ?>">
                        <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close" style="float:right;">
    						<?php echo get_phrase('cancel');?>
    					</button>
    					&nbsp;&nbsp;
                        <button type="button" id="submit-btn" class="modal_save_btn" style="float:right;position: relative;left: -10px;" >
                            <?php echo get_phrase('Assign');?>
                        </button>
                    </td>
                </tr>
            <?php
            }?>
           
        <?php
        }
        else
           echo 'No student enrolled in the selected section.';
        ?>
</table>
    
</form>
<script>
    function toggle(source) {
        checkboxes = document.getElementsByClassName('std_checkbox');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }
</script>


<script>
 
    
$("#submit-btn").click(function(){
if( $("input:checkbox").filter(":checked").length > 1 ) {  
    
    $("#assign_subject").submit();
    
}else{
            alert("Atleast Check One"); 
}
});



        
</script>

