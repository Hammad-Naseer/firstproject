<div class="col-md-12">
    <style>
        .no-print{position:relative;top:15px;z-index:99999;border:2px solid #fff;border-radius:20px 20px 20px 0;box-shadow:1px 0 5px 1px #ccc;outline:0}.main-head{background-color:#012b3c!important;color:#fff!important}@media print{html{transform:scale(.8)}}.balance_sheat_head{text-align:center}.balance_sheat_head div.school_name{clear:both}.balance_sheat_head div.school_name img{width:220px!important;height:120px!important}.balance_sheat_head div.school_name h2{font-size:14px;font-weight:700}.balance_sheat_head div.current_date{clear:both}.balance_sheat_head div.current_date h2{font-size:14px;font-weight:700}td.coa_sub_head{text-decoration:underline;padding:0 0 0 22px!important}tr.coa_sub_total{font-style:italic;background-color:#f6fdfc!important;color:#5f5151!important}tr.total{background:#f5f5f6;font-weight:700}
    </style>
    <form action="<?php echo base_url();?>exams/exam_result_pdf/<?=$student_id?>/<?=$yearly_term_id?>/<?=$exam_id?>" method="post">
        <button type="submit" class="modal_save_btn no-print">Generate PDF Result</button>
    </form>
<?php
    $q="select s.student_id,s.name,s.roll,s.section_id,s.school_id from ".get_school_db().".student s where s.student_id=".$student_id."";
    $student_arr=$this->db->query($q)->result_array();
    $hirearchy=section_hierarchy($student_arr[0]['section_id']);
?>
    <div class="" style="margin-top:15px">
        <div class="">
            <table class="table table-bordered table-hover">
                <tr>
                    <td style="width: 172px;"><strong><?php echo get_phrase('name');?>:</strong> </td>
                    <td>
                        <div class="myttl">
                            <?php echo $student_arr[0]['name']?>
                        </div>    
                    </td>
                    <td><strong><?php echo get_phrase('roll'); ?>#:</strong> </td>
                    <td>
                        <div class="myttl">
                            <?php echo $student_arr[0]['roll']; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo get_phrase('department'); ?>/<?php echo get_phrase('class'); ?>/<?php echo get_phrase('section'); ?>: </strong>
                    </td>
                    <td colspan="3">
                        <ul class="breadcrumb breadcrumb2">
                            <li>
                                <?php echo $hirearchy['d'] ?>
                            </li>
                            <li>
                                <?php echo $hirearchy['c'] ?>
                            </li>
                            <li>
                                <?php echo $hirearchy['s'] ?>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <h3 class="text-center">Term Exams</h3>
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>
                    <div>
                        <?php echo get_phrase('subject');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('total_marks');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('marks_obtained');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('grade');?>
                    </div>
                </th>
            </tr>
        </thead>
        <?php   
                $obt=0; 
                if(count($result) > 0){
                foreach($result as $arr){
                	
                	$total_obtained = get_total_obtained($exam_id,$arr['marks_id'],$arr['subject_id']);
                	$total_marks    = get_total_marks($exam_id,$student_arr[0]['section_id'],$arr['subject_id']);
                	
                	$obt           +=$total_obtained;
                	$total         += $total_marks;
                	$grade_percent = intval( ($total_obtained/$total_marks) *100);
        ?>
        <tr>
            <td>
                <?php echo get_subject_name($arr['subject_id']); ?>
            </td>
            <td align="right" class="total">
                <?php echo get_total_marks($exam_id,$student_arr[0]['section_id'],$arr['subject_id']); ?>
            </td>
            <td align="right" class="obtained">
                <?php echo get_total_obtained($exam_id,$arr['marks_id'],$arr['subject_id']);	?>
            </td>
            <td>
                <?php 
                	echo get_grade($grade_percent);
                ?>
            </td>
        </tr>
        <?php }}?>
        
        <!-- 
        <tr>
            <td><?php echo get_phrase('total'); ?></td>
            <td align="right" id="total-marks">
                <?php echo $total; ?>
            </td>
            <td align="right" id="total-obt">
                <?php echo $obt; ?>
            </td>
            <td>
                <?php 
					$percent=($obt/$total *100);
					echo get_grade($percent).' ('.round($percent).'%)';
				?>
            </td>
        </tr>
        -->
    </table>
    
    
    <?php
        if(count($assm_result) > 0){
    ?>
    
    <hr>
    <h3 class="text-center">Term Assessments</h3>
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>
                    <div>
                        <?php echo get_phrase('assessment_title');?>
                    </div>
                </th>
                 <th>
                    <div>
                        <?php echo get_phrase('subject');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('total_marks');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('marks_obtained');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('grade');?>
                    </div>
                </th>
            </tr>
        </thead>
        <?php   
            $obt=0; 
            foreach($assm_result as $arr_row){
        ?>
        <tr>
            <td>
                <?php echo $arr_row['assessment_title']; ?>
            </td>
            <td>
                <?php echo $arr_row['subject_name']; ?>
            </td>
            <td align="right" class="total">
                <?php echo $arr_row['total_marks']; ?>
            </td>
            <td align="right" class="obtained">
                <?php echo $arr_row['obtained_marks']; ?>
            </td>
            <td>
                <?php echo $arr_row['grade_name']; ?>
            </td>
        </tr>
        <?php }?>
    </table>
    <?php
        }
    ?>
    
    <hr>
    <h3 class="text-center">Combined Result</h3>
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>
                    <div>
                        <?php echo get_phrase('subject');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('exam_weightage');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('assessments_weightage');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('total_marks');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('calculated_exam_marks');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('calculated_assessments_marks');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('total_obtained_marks');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('grade');?>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php   
            $obt=0; 
            $obt_total = 0;
            $total = 0;
            $paper_total_marks = 0;
            $obtained_paper_marks_total = 0;
            foreach($result as $arr){
                	
                	$total_obtained = get_total_obtained($exam_id,$arr['marks_id'],$arr['subject_id']);
                	$total_marks    = get_total_marks($exam_id,$student_arr[0]['section_id'],$arr['subject_id']);
                	
                	$paper_total_marks += $total_marks;
                	
                	$calculated_total          =  intval((( $arr['exam_percentage'] / 100 ) * $total_marks  )); // e.p percent marks of total paper marks
                	$obtained_marks_percentage =  intval((  $total_obtained/$total_marks) *100);               // percentage of obtained marks
                	$obtained_total            =  intval((( $obtained_marks_percentage / 100 ) * $calculated_total  )); // calcuated marks of total exam weightage marks
                	
                	$assessment_count = 0;
                	$assessment_total = 0;
                	$assessment_obtained = 0;
                	
                	if($arr['assessment_percentage'] != '' && $arr['assessment_percentage'] > 0){
                	
                    	if(count($assm_result) > 0){
                    	   foreach($assm_result as $arr_row){
                    	        if($arr_row['subject_id'] == $arr['subject_id']){
                    	             $assessment_count = $assessment_count + 1;
                    	             $assessment_total += $arr_row['total_marks'];
                    	             $assessment_obtained += $arr_row['obtained_marks'];
                    	        }
                    	   } // end of foreach
                    	}
                    	
                    	if($assessment_count > 0){
                    	    $calculated_total_assm          =  intval((( $arr['assessment_percentage'] / 100 ) * $total_marks  ));
                    	    $assm_obtained_marks_percentage =  intval((  $assessment_obtained/$assessment_total) *100);
                    	    $assm_obtained_total            =  intval((( $assm_obtained_marks_percentage / 100 ) * $calculated_total_assm  ));
                    	}
                    	
                	
                	}

                	

                	$obt            +=  $total_obtained;
                	$total          +=  $total_marks;
                	$grade_percent  =  intval( ($total_obtained/$total_marks) *100 );
        ?>
               <tr>
            <td>
                <?php echo get_subject_name($arr['subject_id']); ?>
            </td>
            <td align="right" class="total">
                <?php echo $arr['exam_percentage'] ?>
            </td>
            <td align="right" class="total">
                <?php echo $arr['assessment_percentage'] ?>
            </td>
            <td align="right" class="total">
                <?php 
                    $total_paper_marks = get_total_marks($exam_id,$student_arr[0]['section_id'],$arr['subject_id']);
                    echo $total_paper_marks;
                ?>
            </td>
            <td align="right" class="obtained">
                <?php 
                     if($assessment_count > 0){
                         echo $obtained_total . ' / ' . $calculated_total;
                     }
                     else
                     {
                        echo get_total_obtained($exam_id,$arr['marks_id'],$arr['subject_id']) . ' / ' . $total_paper_marks;
                     }
                      
                ?> <br>
            </td>
            <td>
                <?php 
                     echo ($assessment_count > 0 && $arr['assessment_percentage'] > 0) ? $assm_obtained_total . ' / ' . $calculated_total_assm : ''; 
                ?> <br>
            </td>
            <td align="right">
                <?php
                     if($assessment_count > 0){
                         echo $obtained_total + $assm_obtained_total;
                     }
                     else
                     {
                        echo get_total_obtained($exam_id,$arr['marks_id'],$arr['subject_id']);
                     }
                ?>
            </td>
            <td>
                <?php
                 
                     if($assessment_count > 0 && $arr['assessment_percentage'] > 0){
                           $obt_total     = $obtained_total + $assm_obtained_total;
                           $obtained_paper_marks_total += $obt_total;
        	               $grade_percent = intval( ($obt_total/$total_paper_marks) *100);
                     }
                     else
                     {
                        $obtained_paper_marks_total += $total_obtained; 
                        $grade_percent = intval( ($total_obtained/$total_paper_marks) *100);
                     }
                

                   echo get_grade($grade_percent);
				?>
            </td> 
        </tr>
        <?php 
            }
        ?>
        
        <tr>
            <td><?php echo get_phrase('total'); ?></td>
            <td></td>
            <td></td>
            <td align="right"><?php echo $paper_total_marks; ?></td>
            <td align="right"></td>
            <td align="right"></td>
            <td align="right">
                <?php echo $obtained_paper_marks_total; ?>
            </td>
            <td>
                <?php 
					$percent=($obtained_paper_marks_total/$paper_total_marks *100);
					echo get_grade($percent).' ('.round($percent).'%)';
				?>
            </td>
        </tr>
        
        </tbody>
    </table>
</div>