<table class="table table-striped table-bordered table_export" data-step="3" data-position='top' data-intro="staff evalution record">
    <thead>
        <tr>
            <th style=" width:54px !important;"><?php echo get_phrase('s_no');?></th>
            <th>
                <?php echo get_phrase('student');?>
            </th>
            <th>
                 <?php echo get_phrase('type');?>
            </th>
            <th>
                 <?php echo get_phrase('subject');?>
            </th>
            <th>
                 <?php echo get_phrase('who_evaluated');?>
            </th>
            <th>
                 <?php echo get_phrase('general_remarks');?>
            </th>
            <th>
                 <?php echo get_phrase('remarks');?>
            </th>
        </tr>
    </thead>
    <tbody>
    <?php 
        $count = 1;
        $where = '';
        if($student_id != '')
            $where .= "student.student_id = ".$student_id. " And ";
        if($section_id != '')
            $where .= "student.section_id = ".$section_id. " And ";
            
        if($evaluation_types == 2){
            $where .= "student_evaluation.exam_id = 0 And ";
        }
        else if($evaluation_types == 1){
            $where .= "student_evaluation.exam_id <> 0 And ";
        }
        $stud_eval_query = "SELECT * FROM ".get_school_db().".student_evaluation JOIN ".get_school_db().".student ON (student_evaluation.student_id = student.student_id) WHERE ".$where." student_evaluation.school_id=".$_SESSION['school_id']." AND student_status IN (".student_query_status().")";
           
        $student_evaluations = $this->db->query($stud_eval_query)->result_array(); 
        // echo "<pre>";
        //     print_r($student_evaluations);
        
        foreach($student_evaluations as $row){
            $stud_ans_query="SELECT * FROM ".get_school_db().".student_evaluation_answers WHERE stud_eval_id=".$row['stud_eval_id']."  AND school_id=".$_SESSION['school_id']."";
            $stud_ans_edit=$this->db->query($stud_ans_query)->result_array();
            $details=section_hierarchy($row['section_id']);
    ?>
       <tr>
            <td class="td_middle">
                <?php echo $count++;?>
            </td>
            <td class="td_middle">
                <p><?php echo $row['name']?></p>
                <p><?php echo $details['c']; ?>/<?php echo $details['s']; ?></p>
            </td>
            <td>
                <?php
                    if($row['exam_id'] == '0')
                        echo "<strong>General</strong>";
                    else
                        echo "<strong>Exam</strong>";
                ?>
            </td>
            <td>
                <?php
                if($row['subject_id'] == '0'){
                    echo "--";
                }
                else{
                    echo get_subject_name($row['subject_id']);
                }
                ?>
            </td>
            <td>
                <?php
                    if($row['evaluated_by'] == 1){
                        echo "Admin (Admin)";
                    }
                    else{
                        $staff="SELECT name FROM ".get_school_db().".staff WHERE staff_id=".$row['who_evaluated']." AND school_id=".$_SESSION['school_id']."";
                        $staff=$this->db->query($staff)->row();
                        echo $staff->name;
                        echo "(Teacher)";
                    }
                
                ?>
            </td>
            <td>
                <div><strong><?php echo get_phrase('rating');?>:</strong> <?php echo get_evaluation_rating_by_id($row['answers'])->detail;?></div>  
                <div><strong><?php echo get_phrase('remarks');?>:</strong>  <?php echo $row['remarks'];?></div>        
                <?php
                    	$attachment=$row['attachment'];	
                        $val_im=display_link($attachment,'student_evaluation',0,0); 
                        if($val_im!=""){
                    ?>	
                    <div><strong><?php echo get_phrase('attachment');?>:</strong>
                    <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><span class="glyphicon glyphicon-download-alt"></span></a>
                    <?php  } ?>
                </div> 
                
            </td>
            <td>
                <?php
                $stud_ans_query="SELECT title, answers, remarks FROM ".get_school_db().".student_evaluation_answers JOIN ".get_school_db().".student_evaluation_questions ON (student_evaluation_answers.eval_id = student_evaluation_questions.eval_id) WHERE status = 1 And stud_eval_id=".$row['stud_eval_id']."  AND student_evaluation_answers.school_id=".$_SESSION['school_id']."";
                $stud_answers=$this->db->query($stud_ans_query)->result_array();
                // echo "<pre>";
                // print_r($stud_answers);
                $question_num = 1;
                foreach($stud_answers as $answer){
                ?>
                    <div><strong><?php echo $question_num++;?>) <?php echo $answer['title'];?></strong></div> 
                    <div><strong><?php echo get_phrase('rating');?>:</strong> <?php echo get_evaluation_rating_by_id($answer['answers'])->detail;?></div>  
                    <div><strong><?php echo get_phrase('remarks');?>:</strong>  <?php echo $answer['remarks'];?></div>        
                    <?php
                        	$attachment=$answer['attachment'];	
                            $val_im=display_link($attachment,'staff_evaluation',0,0); 
                            if($val_im!=""){
                        ?>	
                        <div><strong><?php echo get_phrase('attachment');?>:</strong>
                        <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><span class="glyphicon glyphicon-download-alt"></span></a>
                        <?php  } ?>
                    </div> 
                <?php
                }
                ?>
                
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>