<?php
$q2="select s.name as subject, s.code as subject_code, ss.subject_id,ss.section_id,ss.periods_per_week,ss.periods_per_day from ".get_school_db().".subject_section ss inner join ".get_school_db().".subject s on s.subject_id=ss.subject_id where ss.school_id=".$_SESSION['school_id']." AND section_id=".$section_id." order by subject ASC ";
        $selected=$this->db->query($q2)->result_array();
if(count($selected) > 0)
{
?>
 <table class="table chi table-bordered table-responsive table-condensed table-striped mytable2" id="demo<?php echo $row['section_id']; ?>">
                        <tr style="text-align: center; font-weight:bold;">
                       		<td><?php echo get_phrase('s_no');?></td>
                            <td><?php echo get_phrase('subject');?> </td>
                            <td colspan="2"><?php echo get_phrase('periods_per_week');?></td>
                            <td colspan="2"><?php echo get_phrase('periods_per_day');?></td>
                        </tr>
                        <tr style="text-align: center; font-weight:bold;">
                            <td> </td>
                            <td><?php echo get_phrase('assigned');?></td>
                            <td><?php echo get_phrase('total');?></td>
                            <td><?php echo get_phrase('assigned');?></td>
                            <td><?php echo get_phrase('total');?></td>
                        </tr>
                        <?php 
                        $count=1;
                        foreach($selected as $sel){?>
                        <tr>
                        <td><?php echo $count;?></td>
                            <td>
                                <?php echo $sel['subject'].' - '.$sel['subject_code']; ?> </td>
                            <td class="text-center">
                                <?php echo  (subject_count_class_routine_week($sel['subject_id'],$sel['section_id'])?subject_count_class_routine_day($sel['subject_id'],$sel['section_id']):0) ; ?>
                            </td>
                            <td class="text-center">
                                <?php echo $sel['periods_per_week'] ; ?>
                            </td>
                            <td class="text-center">
                                <?php echo (subject_count_class_routine_day($sel['subject_id'],$sel['section_id'])?subject_count_class_routine_day($sel['subject_id'],$sel['section_id']):0) ;  ?> </td>
                            <td class="text-center">
                                <?php echo $sel['periods_per_day'] ; ?> </td>
                        </tr>
                        <?php 
                        $count++;
                        } ?>
                    </table>
<?php
}
else
{
	echo "<?php echo get_phrase('no_subjects_assigned');?>";
}