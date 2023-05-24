<style>
    .badge_ui{float:right;position:relative;top:-28px;}.panel-title a{color:black !important;}.header_assess{padding:20px;text-align:center;background:#139bda;color:#fff;margin:-5px;font-size:30px}
    .tab-pane{box-shadow: 4px 0px 10px 2px #ccc;border: 1px solid #ccc;}
</style>
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<script src="https://cdn.ckeditor.com/4.14.1/standard-all/ckeditor.js"></script>
<script src="https://www.wiris.net/demo/plugins/app/WIRISplugins.js?viewer=image"></script>
<div class="header_assess">
  <h1 style="color:white !important;"><?php echo $assessment_result[0]['assessment_title']; ?></h1>
</div>
<br>
<div class="col-md-12">
	<div class="tab-content">            
        <div class="tab-pane box active" id="list">
            <form action="<?php echo base_url().'assessment/mark_assessment' ?>" method="post">    
            <?php
            $count = 0;
            foreach($assessment_result as $result_row)
            {
                $count++;
                $id_name_attr = 'question_obtained_marks_'.$count;
            ?>
                <div class="panel-body">
                          <?php if($result_row['question_type_id'] == 3){ ?>      
                            <span>Q # <?php echo $count;?> : </span>
                            <?php 
                                $input_field = '<b><u>'.$result_row['answer'] . '</u></b>';
                                $altered_question_text = str_replace('_', $input_field, $result_row['question_text']);
                            ?>
                                <h4 class="panel-title"> 
                                    <a>
                                        <b><?php echo $altered_question_text; ?></b>
                                    </a>
                                </h4>
                            <?php }else{ ?> 
                                <h4 class="panel-title"> 
                                    <a>
                                        <b><?php echo "Q # ".$count.": ".$result_row['question_text']; ?></b>
                                    </a>
                                </h4>
                          <?php } ?>
                          <span class='badge badge-primary badge_ui'>Total Marks : <?php echo $result_row['question_total_marks'];?></span>
                          <input type="hidden" id="question_marks_<?php echo $count ?>"      name="question_marks_<?php echo $count ?>" value="<?php echo $result_row['question_total_marks'] ?>"> 
                          <input type="hidden" id="question_ids_<?php echo $count ?>"        name="question_ids_<?php echo $count ?>" value="<?php echo $result_row['question_id'] ?>">
                          <input type="hidden" id="question_type_ids_<?php echo $count ?>"   name="question_type_ids_<?php echo $count ?>" value="<?php echo $result_row['question_type_id'] ?>">
                          
                          <?php
                            if($result_row['question_type_id'] == 1) 
                            {
                                $option_row = get_option_text_by_option_number($result_row['question_id'] , $result_row['answer']);
                                echo '<div class="text-success answerp"><b>'. $option_row->option_text  . '</b></div>';
                                 
                                if($result_row['right_answer_key'] == $result_row['answer']){
                                    echo '<div class="badge badge-success">Correct Answer</div>';
                                    echo '<input type="hidden" class="marks_assigned" id="'.$id_name_attr.'" name="'.$id_name_attr.'" value="'.$result_row['question_total_marks'] .'">';
                                }else{
                                    echo '<div class="badge badge-danger">Wrong Answer</div>';
                                    echo '<input type="hidden" class="marks_assigned" id="'.$id_name_attr.'" name="'.$id_name_attr.'" value="0">';
                                    //echo '<div>Right answer was option '.get_correct_option_for_mcqs($result_row['question_id']).'</div>';
                                }
                            }else if($result_row['question_type_id'] == 2){
                                 echo '<div class="text-success answerp"><b>'. $result_row['answer'] . '</b></div>';
                                 
                                 if($result_row['right_answer_key'] == $result_row['answer']){
                                     echo '<div class="badge badge-success">Correct Answer</div>';
                                     echo '<input type="hidden" class="marks_assigned" id="'.$id_name_attr.'" name="'.$id_name_attr.'" value="'.$result_row['question_total_marks'] .'">';
                                 }
                                 else
                                 {
                                     echo '<div class="badge badge-danger">Wrong Answer</div>';
                                     echo '<input type="hidden" class="marks_assigned" id="'.$id_name_attr.'" name="'.$id_name_attr.'" value="0">';
                                 }
                                 
                            }else if($result_row['question_type_id'] == 3){
                                echo '<div class="text-success answerp"><b>Ans: &nbsp;&nbsp;'. $result_row['answer'] . '</b></div>';
                                $right_key = explode(",",trim($result_row['right_answer_key']));
                                // print_r($right_key);
                                // if(strtolower($result_row['right_answer_key']) == strtolower($result_row['answer'])){
                                if(in_array($result_row['answer'], $right_key)){
                                    echo '<div class="badge badge-success">Correct Answer</div>';
                                    echo '<input type="hidden" class="marks_assigned" id="'.$id_name_attr.'" name="'.$id_name_attr.'" value="'.$result_row['question_total_marks'] .'">';
                                }else{
                                    echo '<div class="badge badge-danger">Wrong Answer</div>';
                                    echo '<input type="hidden" class="marks_assigned" id="'.$id_name_attr.'" name="'.$id_name_attr.'" value="0">';
                                }    
                            }else if($result_row['question_type_id'] == 4){
                                echo '<div class="text-success answerp">'. $result_row['answer'] . '</div>';
                          ?>
                                <div class="answerp">
                                    <br>
                                    <label>Assign Marks</label>
                                    <input type="text" class="form-control teacher_marks" style="width:20%" id="<?php echo $id_name_attr ?>" name="<?php echo $id_name_attr ?>" >
                                    <span style="color:red" id="marks_span_<?php echo $count ?>"></span>    
                                </div>     
                            <?php }else if($result_row['question_type_id'] == 5){
                                 echo '<div class="text-success answerp">'. $result_row['answer'] . '</div>'; ?>
                                 <div class="answerp">
                                     <br>
                                     <label>Assign Marks</label>
                                     <input type="text" class="form-control teacher_marks" style="width:20%" id="<?php echo $id_name_attr ?>"
                                        name="<?php echo $id_name_attr ?>" >
                                     <span style="color:red" id="marks_span_<?php echo $count ?>"></span>    
                                 </div> 
                          <?php
                          }
                            else if($result_row['question_type_id'] == 6){ ?>     
                                <div class="text-success answerp"> 
                                    <img height="350px" width="350px" style="padding-top: 10px;padding-bottom: 10px;"  src="<?php echo base_url()."uploads/".$_SESSION['folder_name']."/pictorial_question/".$result_row['image_url']; ?>" />
                                 </div>
                                 <div class="text-success answerp"> <?php echo $result_row['answer'] ?> </div>
                                 
                                 <div class="answerp">
                                     <br>
                                     <label>Assign Marks</label>
                                     <input type="text" class="form-control teacher_marks" style="width:20%" id="<?php echo $id_name_attr ?>"
                                        name="<?php echo $id_name_attr ?>" >
                                     <span style="color:red" id="marks_span_<?php echo $count ?>"></span>
                                 </div> 
                                 
                          <?php
                          }
                            else if($result_row['question_type_id'] == 7){
                              $matching_question_option = get_matching_question_option_solution($result_row['question_id']);
                              $total_correct_marks = 0;
                          ?>     
                               <table class="table table-bordered">
                                        <input type="hidden"  name="matching_question" value="7" />
                                        <thead>
                                            <th>Column A</th>
                                            <th>Column B</th>
                                            <th>Student Answer</th>
                                            <th>Result</th>
                                            <th><b>Marks</b></th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total_option = count($matching_question_option);
                                            
                                            foreach($matching_question_option as $matching_questions)
                                            {
                                                $opt_obtained_marks = 0;
                                            ?>
                                                <input type="hidden" id="assessment_matching_solution_id_<?php echo $count ?>"   
                                                                     name="assessment_matching_solution_id_<?php echo $count ?>[]" 
                                                                     value="<?php echo $matching_questions['assessment_matching_solution_id'] ?>">
                                                <tr>
                                                    <td>
                                                        <?php echo $matching_questions['left_side_text']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $matching_questions['right_side_text']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $matching_questions['option_number']; ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            if ( $matching_questions['right_answer'] == $matching_questions['option_number']){
                                                                $total_correct_marks += $matching_questions['option_marks']; 
                                                                $opt_obtained_marks = $matching_questions['option_marks'];
                                                                echo '<div class="badge badge-success">Correct Answer</div>';
                                                            }
                                                            else
                                                            {
                                                                $total_correct_marks += 0;
                                                                $opt_obtained_marks = 0;
                                                                echo '<div class="badge badge-danger">Wrong Answer</div>';
                                                            }
                                                         ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $opt_obtained_marks . '/' . $matching_questions['option_marks']; ; ?>
                                                    </td>
                                                </tr>
                                                
                                                <input type="hidden" id="option_marks_obtained_<?php echo $count ?>"   
                                                                     name="option_marks_obtained_<?php echo $count ?>[]" 
                                                                     value="<?php echo $opt_obtained_marks; ?>">
                                            <?php
                                            }
                                            
                                            echo '<input type="hidden" class="marks_assigned" id="'.$id_name_attr.'" name="'.$id_name_attr.'" value="'.$total_correct_marks .'">';
                                            ?>
                                            
                                        </tbody>
                                    </table>  
                          <?php
                          }
                            else if($result_row['question_type_id'] == 8){
                          ?>     <div class="text-success answerp"> 
                                    <img height="350px" width="350px" style="padding-top: 10px;padding-bottom: 10px;" src="<?php echo base_url()."uploads/".$_SESSION['folder_name']."/drawing/".$result_row['drawing_sheet_url']; ?>" />
                                 </div>
                                 
                                 <div class="answerp">
                                     <br>
                                     <label>Assign Marks</label>
                                     <input type="text" class="form-control teacher_marks" style="width:20%" id="<?php echo $id_name_attr ?>"
                                        name="<?php echo $id_name_attr ?>" >
                                     <span style="color:red" id="marks_span_<?php echo $count ?>"></span>
                                 </div> 
                                 
                          <?php
                          }
                          ?>
                </div>
            <?php } ?>

                <div class="panel-body">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" name="remarks" id="remarks" rows="4" cols="50"></textarea>
                        <input type="hidden" id="total_marks" name="total_marks" value="<?php echo $assessment_result[0]['total_marks'] ?>">
                        <input type="hidden" id="assessment_id" name="assessment_id" value="<?php echo $assessment_result[0]['assessment_id'] ?>">
                        <input type="hidden" id="section_id" name="section_id" value="<?php echo $assessment_result[0]['section_id'] ?>">
                        <input type="hidden" id="subject_id" name="subject_id" value="<?php echo $assessment_result[0]['subject_id'] ?>">
                        <input type="hidden" id="student_id" name="student_id" value="<?php echo $assessment_result[0]['student_id'] ?>">
                        <input type="hidden" id="question_count" name="question_count" value="<?php echo $count ?>">
                        <br><br>
                        <button type="submit" id="submit" class="btn btn-primary float-right" onclick = "return validate_form();">Save Result</button>    
                    </div>
                    <p id="AssignedMarks" style="color:red">Assigned Marks : <span id="marksassigned">0</span></p>
                    <div class="row" id="ValidationMessageRow">
                        <div class="col-md-12">
                            <p id="ValidationMessage" style="color:red"></p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>    

<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replaceClass="editor";
    
    $(document).ready(function(){
        var marks_assigned = 0;

        
        marks_assigned =  getAssignedMarks();
        $('#marksassigned').html(marks_assigned);
        
        $(".teacher_marks").blur(function(){
            var marks = $.trim($(this).val());
            
            var ids = $(this).attr('id');
            var splitted = ids.split('_');
            var question_no = splitted[3];

            
            if(marks != ''){
                
                var $regexnumber = /^[0-9-]*$/;
                if (marks.match($regexnumber)) {
                    if(marks.length > 1){
                         var firstChar = parseInt(marks.charAt(0));
                         if(firstChar == 0){
                            $(this).val('');
                            $('#marks_span_'+question_no).html('Marks Can Not Start With A Zero');  
                         }
                    }
                    var total_marks = $('#question_marks_'+question_no).val();
                    if(parseInt(marks) > parseInt(total_marks)){
                       $(this).val('');
                       $('#marks_span_'+question_no).html('Marks Assigned Can Not Be Greater Than Question Total Marks');
                    }
                    else{
                        marks_assigned =  getAssignedMarks();
                        $('#marksassigned').html(marks_assigned);
                        $('#marks_span_'+question_no).html(''); 
                    }
                   
                }
                else
                {
                    debugger;
                    $('#marks_span_'+question_no).html('Marks Should be a numeric value'); 
                    $(this).val('');
               }
            }
        });
        
        
    });
    
    function validate_form(){
      
        $('.teacher_marks').each(function(){
            if($.trim($(this).val()) == ''){
                $('#ValidationMessage').html('Assign Marks Field Can Not Be Empty');
                return false;
            }
            else{
                $('#ValidationMessage').html('');
                return true;
            }
        });
        
    }
    
    
    function getAssignedMarks(){
    
        var sum = 0;
        
        $('.marks_assigned').each(function(){
            sum += parseInt($(this).val());
        });
        
        
        $(".teacher_marks").each(function(){
            var marks = $.trim($(this).val());
            if(marks != ''){
                var $regexnumber = /^[0-9-]*$/;
                if (marks.match($regexnumber)) {
                    sum += parseInt(marks);
                }
            }
       });
        
       return sum;
    
    }
    
    
</script>