<?php 
//to check acad_year status
if($type == '1'){
    $p="SELECT y.academic_year_id as academic_year_id FROM ".get_school_db().".exam e INNER JOIN ".get_school_db().".yearly_terms y ON e.yearly_terms_id=y.yearly_terms_id WHERE e.exam_id=".$exam_id;
    $acad=$this->db->query($p)->result_array();
    $status=academic_year_status($acad[0]['academic_year_id']);
    $status_val=$status[0]['is_closed'];
}
else{
    $status_val = 0;
}
if($subject_id != '0'){
    $q="select *
    from (
    select se.stud_eval_id, se.remarks, se.answers,se.school_id, se.subject_id, se.exam_id, se.attachment, se.evaluated_by, se.who_evaluated,s.name,s.student_id,s.roll from ".get_school_db().".student s
        LEFT JOIN ".get_school_db().".student_evaluation se
        ON (s.student_id=se.student_id and se.exam_id=".$exam_id.")
        WHERE s.school_id =".$_SESSION['school_id']." and s.section_id=".$section_id." and se.subject_id = ".$subject_id." and s.student_status in (".student_query_status().") And (se.who_evaluated IS NULL OR se.who_evaluated = ".$_SESSION['user_id'].") 
        UNION
        select se.stud_eval_id, se.remarks, se.answers,se.school_id, se.subject_id, se.exam_id, se.attachment, se.evaluated_by, se.who_evaluated,s.name,s.student_id,s.roll from ".get_school_db().".student s
        LEFT JOIN ".get_school_db().".student_evaluation se
        ON (s.student_id=se.student_id and se.exam_id=".$exam_id.")
        WHERE s.school_id =".$_SESSION['school_id']." and s.section_id=".$section_id." and s.student_status in 
        (".student_query_status().") And s.student_id NOT IN (SELECT sse.student_id FROM  ".get_school_db().".student_evaluation as sse WHERE sse.who_evaluated = ".$_SESSION['user_id']." AND sse.subject_id = ".$subject_id." AND  sse.exam_id=".$exam_id.")
    ) a order by student_id";
    
}
else{
    $q="select *
    from (
        select se.stud_eval_id, se.remarks, se.answers,se.school_id, se.subject_id, se.exam_id, se.attachment, se.evaluated_by, se.who_evaluated,s.name,s.student_id,s.roll from ".get_school_db().".student s
        LEFT JOIN ".get_school_db().".student_evaluation se
        ON (s.student_id=se.student_id and se.exam_id=".$exam_id.")
        WHERE s.school_id =".$_SESSION['school_id']." and s.section_id=".$section_id." and s.student_status in (".student_query_status().") And (se.who_evaluated IS NULL OR se.who_evaluated = ".$_SESSION['user_id'].") UNION
        select se.stud_eval_id, se.remarks, se.answers,se.school_id, se.subject_id, se.exam_id, se.attachment, se.evaluated_by, se.who_evaluated,s.name,s.student_id,s.roll from ".get_school_db().".student s
        LEFT JOIN ".get_school_db().".student_evaluation se
        ON (s.student_id=se.student_id and se.exam_id=".$exam_id.")
        WHERE s.school_id =".$_SESSION['school_id']." and s.section_id=".$section_id." and s.student_status in (".student_query_status().") And s.student_id NOT IN (SELECT sse.student_id FROM  ".get_school_db().".student_evaluation as sse WHERE sse.who_evaluated = ".$_SESSION['user_id']." AND sse.exam_id=".$exam_id.")
    ) a order by student_id";
}
// 	print_r($_SESSION);
$studentArr=$this->db->query($q)->result_array();
if(sizeof($studentArr)>0){
?>
<div id="session" style="display:none">
    <?php

   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      Record Saved
     </div> 
    </div>';

  ?></div>
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export">
    <thead>
        <tr>
            <th style="width:34px;">
                <?php echo get_phrase('s_no');?>
            </th>
            <th style="width:34px;">
                <?php echo get_phrase('roll');?>#
            </th>
            <th style="width:34px;">
            	<?php echo get_phrase('student');?>
            </th>
            <th style="width:94px;">
                <?php echo get_phrase('options');?>
            </th>
        </tr>
    </thead>
    <tbody>
    <?php 
        $s=0;
        foreach($studentArr as $st)
        {
        $s++;
        
        // if($st['evaluated_by'] == 2 And $st['who_evaluated'] != $_SESSION['user_id'])
        //     continue;
    ?>
        <tr>
            <td class="td_middle">
                <?php echo $s ; ?>
            </td>
            <td>
                <?php echo $st['roll'] ; ?>
            </td>
            <td>
                <?php 
                    echo $st['name'];
                    $check = false;
                    if($st['stud_eval_id']>0 And $st['evaluated_by'] == $evaluated_by And $st['subject_id'] == $subject_id And $st['who_evaluated'] == $_SESSION['user_id'])
                    {
						echo "<strong> (Already Evaluated)</strong>";
						$check = true;
					}
                ?>
            </td>
            <td class="td_middle">
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        <?php echo get_phrase('action');?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                        <!-- EDITING LINK -->
                        <?php
                            //if(in_array('noticeboard_edit',$package_rights))
                            
                            if($status_val==0)
                            {
                            ?>
                                <li>
                                <a href="<?php echo base_url();?>student_evaluation/stud_evaluation_form/<?php echo $st['student_id'];?>/<?php echo $section_id;?>/<?php echo $type;?>/<?php echo $exam_id;?>/<?php echo $subject_id;?>" >
                                <i class="entypo-pencil"></i>
                                <?php echo get_phrase('manage_evaluation');?>
                                </a>
                                </li>
                            <?php
                            }
                            if($check){
                        ?>
                        
                                <li class="divider"></li>
                                <li>
                     <?php
                     /*           
                                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_view_stud_evaluation/<?php echo $st['student_id'];?>/<?php echo $exam_id;?>');">
                                        <i class="entypo-pencil"></i>
                                        <?php echo get_phrase('view');?>
                                    </a>
                    */
                    ?>
                                    <a href="<?php echo base_url();?>student_evaluation/modal_view_student_evaluation/<?php echo $st['student_id'];?>/<?php echo $section_id;?>/<?php echo $type;?>/<?php echo $exam_id;?>/<?php echo $subject_id;?>" >
                                        <i class="entypo-eye"></i>
                                        <?php echo get_phrase('view');?>
                                    </a>
                                </li>
                                <?php 
                            }
                                     //echo $status_val;
                                 if($status_val==0)
                                 {
                                ?>
                                <li class="divider"></li>
                                <li>
                                    <a href="#" onclick="confirm_modal('<?php echo base_url();?>student_evaluation/stud_eval/delete/<?php echo $st['stud_eval_id'];?>/<?php echo $exam_id;?>/<?php echo $section_id;?>/<?php echo $st['attachment'];?>');">
                                        <i class="entypo-trash"></i>
                                        <?php echo get_phrase('delete');?>
                                    </a>
                                </li>
                                <?php
                                }
                                ?>
                    </ul>
                </div>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php }else echo "No Record Found";?>
<script>
    $(".table_export").DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
</script>