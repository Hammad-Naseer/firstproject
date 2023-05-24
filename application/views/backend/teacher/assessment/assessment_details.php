<?php
    if(!isset($assessment_id)){
        exit;
    }
    $assessment_row = get_assessment_row($assessment_id);
    if($assessment_row == null){  exit; }
?>


<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<style type="text/css">
    .login-card-body{background:#fff;border-top:0;color:#666;padding:20px;border:1px solid #000}.card{position:relative;display:-ms-flexbox;display:flex;-ms-flex-direction:column;flex-direction:column;min-width:0;word-wrap:break-word;background-color:#fff;background-clip:border-box;border:0 solid rgba(0,0,0,.125);border-radius:.25rem}.errorEstarics{color:red;font-size:.9em;padding-left:1px}.errorspan{color:red;font-size:.9em}
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('online_assessment_questions'); ?>
        </h3>
    </div>
</div>

<form action="<?php echo base_url().'assessment/save_assessment_details' ?>" method="post" enctype="multipart/form-data" data-step="1" data-position="top" data-intro="collapse to add question details" class="introjs-showElement introjs-relativePosition">
<div class="row">
    <div class="col-md-12">
        <div class="panel-group" id="accordion">
            <?php
                $q_count              = 0;  
                $count                = 0;
                if($assessment_row->mcq_questions > 0) {
            ?>
                    <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <?php 
                              $start = $count + 1;
                              $end   = $count + $assessment_row->mcq_questions;
                            ?>
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseMCQ">MCQ Questions (<?php echo $start . ' - ' . $end ?>) &nbsp;&nbsp; <small class="text-white">No Of Question ( <?= $assessment_row->mcq_questions ?> )</small></a>
                        </h4>
                    </div>
                    <div id="collapseMCQ" class="panel-collapse collapse">
                        <div class="panel-body">

                            <?php
                                   $q_count += $assessment_row->mcq_questions;
                                   for($i = 1; $i <= $q_count; $i++){
                                       $count = $count + 1;
                            ?>      
                                    <div style="border:1px solid grey;padding:5px 5px 5px 5px;">
                                        
                                        
                                        <h4 class="text-success text-center"><b>Question  # <?php echo $count ?></b></h4>
                                        <div class="row">
                                        <div class="form-group col-md-12">
                                          <label for="question_statement_<?php echo $count ?>"><b>Enter Question  # <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                          <textarea class="form-control rounded-0 statement mathdoxformula" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3" required></textarea>
                                        </div>
                                        
         
                                        <p class="text-success text-center col-md-12"><b>Question # <?php echo $count ?> Options</b></p>
                                        <p class="text-danger text-center col-md-12"><b>At Least 3 Options Are Mandatory</b></p>
                                        <br>
                                        <?php
                                         for($j = 1; $j <= 5; $j++){
                                        ?>
                                        <div class="form-group col-md-6">
                                          <label for="question_option_<?php echo $count.'_'.$j ?>"><b>Enter Option <?php echo $j ?></b></label>
                                          <input type="text" class="form-control <?php echo $count?>" id="question_option_<?php echo $count.'_'.$j ?>" name="question_option_<?php echo $count?>[]" placeholder="Enter Option <?php echo $j ?>">
                                        </div>
                                        <?php
                                         }
                                        ?>
                                        
                                        
                                            <div class="form-group col-md-6">
                                              <label for="question_correct_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Correct Option <span class="errorEstarics">*</span></b></label>
                                              <select class="form-control correctoptionmcq" id="question_correct_<?php echo $count ?>" name="question_correct_<?php echo $count ?>">
                                                    <option value="">Select Correct Answer</option>
                                                    <option value="1">Option 1</option>
                                                    <option value="2">Option 2</option>
                                                    <option value="3">Option 3</option>
                                                    <option value="4">Option 4</option>
                                                    <option value="5">Option 5</option>
                                              </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="question_marks_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                                <input type="text" class="form-control marks" for="question_marks_<?php echo $count ?>" id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>" placeholder="Enter total marks">
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="1">
                                        
                                        
                                    </div>
                                    <div style="height:10px;"></div>
                            <?php
                            }
                            ?>
                            
                        </div>
                    </div>
                </div>
            <?php
                }
                if($assessment_row->fill_in_the_blanks_questions > 0) {
            ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <?php 
                              $start = $count + 1;
                              $end   = $count + $assessment_row->fill_in_the_blanks_questions;
                            ?>
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFillBlanks">Fill In The Blank Questions (<?php echo $start . ' - ' . $end ?>) &nbsp;&nbsp; <small class="text-white">No Of Question ( <?= $assessment_row->fill_in_the_blanks_questions ?> )</small></a>
                        </h4>
                    </div>
                    <div id="collapseFillBlanks" class="panel-collapse collapse">
                        <div class="panel-body">
                            
                            
                            <?php
                               $q_count = $assessment_row->fill_in_the_blanks_questions;
                               for($i = 1; $i <= $q_count; $i++){
                                   $count = $count + 1;
                            ?>      
                                <div style="border:1px solid grey;padding:5px 5px 5px 5px;">
                                    
                                    
                                    <h4 class="text-success text-center"><b>Question # <?php echo $count ?></b></h4>
                                    <p class="text-danger text-center"><b>Please use single underscore(_) for adding a blank in the question </b></p>
                                    <div class="form-group">
                                      <label for="question_statement_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                      <textarea class="form-control rounded-0 nonckstatement mathdoxformula" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                          <label for="question_marks_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control marks" id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>" placeholder="Enter total marks">
                                        </div>
        
                                        <div class="form-group col-md-6">
                                            <label for="question_correct_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Correct Answer <span class="errorEstarics">*</span></b></label>
                                            <input type="text" class="form-control fillintheblankscorrectanswer" id="question_correct_<?php echo $count ?>" name="question_correct_<?php echo $count ?>"  placeholder="Enter correct answer">
                                            <p class="text-danger"><b>Please use comma (,) for adding a multiple correct options </b></p>                                                                            
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="3">
                                    
                                </div>
                                <div style="height:3px;"></div>
                        <?php
                               }
                            ?>       
                            

                        </div>
                    </div>
                </div>
            <?php
                }
                if($assessment_row->true_false_questions > 0) {
            ?>    
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <?php 
                              $start = $count + 1;
                              $end   = $count + $assessment_row->true_false_questions;
                            ?>
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTrueFalse">True False Questions (<?php echo $start . ' - ' . $end ?>) &nbsp;&nbsp; <small class="text-white">No Of Question ( <?= $assessment_row->true_false_questions ?> )</small></a>
                        </h4>
                    </div>
                    <div id="collapseTrueFalse" class="panel-collapse collapse">
                        <div class="panel-body">

                            <?php
                               $q_count = $assessment_row->true_false_questions;
                               for($i = 1; $i <= $q_count; $i++){
                                   $count = $count + 1;
                            ?>      
                                <div style="border:1px solid grey;padding:5px 5px 5px 5px;">
                                    
                                    
                                    <h4 class="text-success text-center"><b>Question # <?php echo $count ?></b></h4>
                                    
                                    <div class="form-group">
                                      <label for="question_statement_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                      <textarea class="form-control rounded-0 statement mathdoxformula" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3" required></textarea>
                                    </div>
                                    <div class="row">
                                    <div class="form-group col-md-6">
                                      <label for="question_marks_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                      <input type="text" class="form-control marks" id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>" placeholder="Enter total marks">
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                      <label for="question_correct_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Correct Answer</b></label>
                                      <select class="form-control correctanswer" id="question_correct_<?php echo $count ?>" name="question_correct_<?php echo $count ?>">
                                            <option value="">Select Correct Answer</option>
                                            <option value="true">True</option>
                                            <option value="false">False</option>
                                      </select>
                                    </div>
                                    </div>
                                    
                                    <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="2">
                                    
                                </div>
                                <div style="height:3px;"></div>
                            <?php
                               }
                            ?>       
                            
                        </div>
                    </div>
                </div>
            <?php
                }
                if($assessment_row->short_questions > 0) {
            ?>    
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <?php 
                              $start = $count + 1;
                              $end   = $count + $assessment_row->short_questions;
                            ?>
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseShort">Short Questions (<?php echo $start . ' - ' . $end ?>)
                                &nbsp;&nbsp; <small class="text-white">No Of Question ( <?= $assessment_row->short_questions ?> )</small>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseShort" class="panel-collapse collapse">
                        <div class="panel-body">

                            <?php
                               $q_count = $assessment_row->short_questions;
                               for($i = 1; $i <= $q_count; $i++){
                                   $count = $count + 1;
                        ?>      
                                <div style="border:1px solid grey;padding:5px 5px 5px 5px;">
                                    
                                    
                                    <h4 class="text-success text-center"><b>Question # <?php echo $count ?></b></h4>
                                    
                                    <div class="form-group">
                                      <label for="question_statement_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                      <textarea class="form-control rounded-0 statement mathdoxformula" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3" required></textarea>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                          <label for="question_marks_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control marks " id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>" placeholder="Enter total marks">
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                          <label for="required_lines_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Required Lines<span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control required_lines" id="required_lines_<?php echo $count ?>" name="required_lines_<?php echo $count ?>" placeholder="Enter required lines">
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="4">
                                    
                                </div>
                                <div style="height:3px;"></div>
                        <?php
                               }
                            ?>

                        </div>
                    </div>
                </div>
            <?php
                }
                if($assessment_row->long_questions > 0) {
            ?>    
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <?php 
                              $start = $count + 1;
                              $end   = $count + $assessment_row->long_questions;
                            ?>
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseLong">Long Questions (<?php echo $start . ' - ' . $end ?>)
                                &nbsp;&nbsp; <small class="text-white">No Of Question ( <?= $assessment_row->long_questions ?> )</small>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseLong" class="panel-collapse collapse">
                        <div class="panel-body">

                            <?php
                               $q_count = $assessment_row->long_questions;
                               for($i = 1; $i <= $q_count; $i++){
                                   $count = $count + 1;
                        ?>      
                                <div style="border:1px solid grey;padding:5px 5px 5px 5px;">
                                    
                                    
                                    <h4 class="text-success text-center"><b>Question # <?php echo $count ?></b></h4>
                                    
                                    
                                        <div class="form-group">
                                          <label for="question_statement_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                          <textarea class="form-control rounded-0 statement editor" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3" required></textarea>
                                        </div>
                                    <div class="row">    
                                        <div class="form-group col-md-6">
                                          <label for="question_marks_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control marks" id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>" placeholder="Enter total marks">
                                        </div>
                                        <div class="form-group col-md-6">
                                          <label for="required_lines_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Required Lines<span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control required_lines" id="required_lines_<?php echo $count ?>" name="required_lines_<?php echo $count ?>" placeholder="Enter required lines">
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="5">
                                    
                                </div>
                                <div style="height:3px;"></div>
                        <?php
                               }
                            ?>

                        </div>
                    </div>
                </div>
            <?php
                }
                if($assessment_row->pictorial_questions > 0) {
            ?>    
                <div class="panel panel-default">
                    <div class="panel-heading"> 
                        <h4 class="panel-title">
                            <?php 
                              $start = $count + 1;
                              $end   = $count + $assessment_row->pictorial_questions;
                            ?>
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapsePictorial">Picture Description Questions (<?php echo $start . ' - ' . $end ?>)
                                &nbsp;&nbsp; <small class="text-white">No Of Question ( <?= $assessment_row->pictorial_questions ?> )</small>
                            </a>
                        </h4>
                    </div>
                    <div id="collapsePictorial" class="panel-collapse collapse">
                        <div class="panel-body">

                            <?php
                               $q_count = $assessment_row->pictorial_questions;
                               for($i = 1; $i <= $q_count; $i++){
                                   $count = $count + 1;
                        ?>      
                                <div style="border:1px solid grey;padding:5px 5px 5px 5px;">
                                    
                                    
                                    <h4 class="text-success text-center"><b>Question # <?php echo $count ?></b></h4>
                                    
                                    <div class="form-group">
                                      <label for="question_statement_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                      <textarea class="form-control rounded-0 statement mathdoxformula" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3" required></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                      <label for="question_attachment_<?php echo $count ?>"><b>Attach Image <span class="errorEstarics">*</span></b></label>
                                      <input type="file" class="form-control rounded-0 attachment" id="question_attachment_<?php echo $count ?>" name="question_attachment_<?php echo $count ?>" required>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                          <label for="question_marks_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control marks" id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>" placeholder="Enter total marks">
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                          <label for="required_lines_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Required Lines<span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control required_lines" id="required_lines_<?php echo $count ?>" name="required_lines_<?php echo $count ?>" placeholder="Enter required lines">
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="6">
                                    
                                </div>
                                <div style="height:3px;"></div>
                        <?php
                               }
                            ?>

                        </div>
                    </div>
                </div>
            <?php
                }
                if($assessment_row->match_questions > 0) {
            ?>    
                <div class="panel panel-default">
                    <div class="panel-heading"> 
                        <h4 class="panel-title">
                            <?php 
                              $start = $count + 1;
                              $end   = $count + $assessment_row->match_questions;
                            ?>
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseMatching">Matching Questions (<?php echo $start . ' - ' . $end ?>)
                                &nbsp;&nbsp; <small class="text-white">No Of Question ( <?= $assessment_row->match_questions ?> )</small>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseMatching" class="panel-collapse collapse">
                        <div class="panel-body">

                            <?php
                                   $q_count = $assessment_row->match_questions;
                                   for($i = 1; $i <= $q_count; $i++){
                                       $count = $count + 1;
                            ?>      
                                    <div style="border:1px solid grey;padding:5px 5px 5px 5px;">
                                        
                                        
                                        <h4 class="text-success text-center"><b>Question # <?php echo $count ?></b></h4>
                                        
                                        <div class="form-group">
                                          <label for="question_statement_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                          <textarea class="form-control rounded-0 nonckstatement" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3" required></textarea>
                                        </div>
                                        
                                        <p class="text-success text-center"><b>Question # <?php echo $count ?> Options</b></p>
                                        <p class="text-danger text-center"><b>At Least 3 Options Are Mandatory</b></p>
                                        
                                        <div class="form-group">
                                          <label for="select_<?php echo $count ?>">Select # of Options for Question # <?php echo $count ?></label>
                                          <select class="form-control" id="select_<?php echo $count ?>" onchange="createOptions(this)">
                                            <option value="">Select # of Options</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                          </select>
                                        </div>
                                        
                                        
                                        <table class="table table-primary">
                                            <thead>
                                                <th><b>Index</b></th>
                                                <th><b>Column A</b></th>
                                                <th><b>Column B</b></th>
                                                <th><b>Answer Key</b></th>
                                                <th><b>Marks</b></th>
                                            </thead>
                                            <tbody id="body_<?php echo $count ?>">
                                                
                                            <?php
                                            /*
                                                for($j = 1; $j <= 5; $j++){
                                            ?>
                                                
                                                <tr>
                                                    <td><?php echo $j ?></td>
                                                    <td>
                                                        <input type="text" class="form-control <?php echo $count?>" id="left_option_<?php echo $count.'_'.$j ?>" name="left_option_<?php echo $count?>[]">
                                                    </td>
                                                    <td>
                                                         <input type="text" class="form-control <?php echo $count?>" id="right_option_<?php echo $count.'_'.$j ?>" name="right_option_<?php echo $count?>[]">
                                                    </td>
                                                    <td>
                                                         <select class="form-control correctoptionmatching" id="right_answer_<?php echo $count.'_'.$j ?>" name="right_answer_<?php echo $count?>[]">
                                                                <option value="">Select Correct Answer</option>
                                                                <?php
                                                                    for($k = 1; $k <= 5; $k++){
                                                                ?>
                                                                <option value="<?php echo $k; ?>"><?php echo $k; ?></option>
                                                                <?php
                                                                    }
                                                                ?>
                                                          </select> 
                                                    </td>
                                                    <td>
                                                        <input type="text" class="matching_marks form-control <?php echo $count?>" id="marks_<?php echo $count.'_'.$j ?>" name="marks_<?php echo $count?>[]">
                                                    </td>
                                                </tr>
                                                
                                            <?php
                                                }
                                            */
                                            ?>
                                        
                                             </tbody>
                                        </table>
                                        
                                        <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="7">
                                        
                                        
                                    </div>
                                    <div style="height:3px;"></div>
                            <?php
                              }
                            ?>
                            

                        </div>
                    </div>
                </div>
            <?php
                }
                if($assessment_row->drawing_questions > 0) {
            ?>    
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <?php 
                              $start = $count + 1;
                              $end   = $count + $assessment_row->drawing_questions;
                            ?>
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapsedrawing">Drawing Questions (<?php echo $start . ' - ' . $end ?>)
                                &nbsp;&nbsp; <small class="text-white">No Of  # ( <?= $assessment_row->drawing_questions ?> )</small>
                            </a>
                        </h4>
                    </div>
                    <div id="collapsedrawing" class="panel-collapse collapse">
                        <div class="panel-body">

                            <?php
                               $q_count = $assessment_row->drawing_questions;
                               for($i = 1; $i <= $q_count; $i++){
                                   $count = $count + 1;
                        ?>      
                                <div style="border:1px solid grey;padding:5px 5px 5px 5px;">
                                    
                                    
                                    <h4 class="text-success text-center"><b>Question # <?php echo $count ?></b></h4>
                                    
                                    <div class="form-group">
                                      <label for="question_statement_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                      <textarea class="form-control rounded-0 statement mathdoxformula" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3" required></textarea>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                          <label for="question_marks_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control marks" id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>" placeholder="Enter required lines">
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                          <label for="required_lines_<?php echo $count ?>"><b>Enter Question # <?php echo $count ?> Required Lines<span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control required_lines" id="required_lines_<?php echo $count ?>" name="required_lines_<?php echo $count ?>" placeholder="Enter required lines">
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="8">
                                    
                                </div>
                                <div style="height:3px;"></div>
                        <?php
                               }
                            ?>

                        </div>
                    </div>
                </div>
            <?php
                }
            ?> 
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-right" data-step="2" data-position="top" data-intro="click to save the assessment " class="introjs-showElement introjs-relativePosition">
                
                <input type="hidden" id="assessment_id" name="assessment_id" value="<?php echo $assessment_id ?>">
                <input type="hidden" id="question_count" name="question_count" value="<?php echo $count ?>">
                <input type="submit" id="validateBtn" name="validateBtn" value="Save Question Paper" 
                onclick="return validateAssessmentForm()" class="modal_save_btn" >  
    </div>
</div>

</form>

<div class="row">
    <div class="col-md-12">
          <p id="Total Marks" style="color:green">Total Marks : <span id="totalmarks"><?php echo $assessment_row->total_marks ?></span></p> 
          <p id="AssignedMarks" style="color:red">Assigned Marks : <span id="marksassigned">0</span></p>      
    </div>
</div>



<div class="row" id="ValidationMessageRow">
        <div class="col-md-12">
                <p id="ValidationMessage" style="color:red"></p>
        </div>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script src="https://cdn.ckeditor.com/4.14.1/standard-all/ckeditor.js"></script>
<script>

    $('.statement').each(function(){
        
            var $this = $(this);
            var ids = $this.attr('id');
            var splitted = ids.split('_');
            var question_no = splitted[2];
            
            CKEDITOR.plugins.addExternal('ckeditor_wiris', 'https://www.wiris.net/demo/plugins/ckeditor/', 'plugin.js');
            
            
            CKEDITOR.config.uiColor = '#427fa6';
            CKEDITOR.config.width = '100%'; 
    
            CKEDITOR.replace('question_statement_'+question_no, {
                extraPlugins: 'ckeditor_wiris',
                height: 200,
                
              // Remove the redundant buttons from toolbar groups defined above.
              removeButtons: 'Styles,removeFormat,Strike,Anchor,SpellChecker,PasteFromWord,Image,Source,Text,Copy,Paste,Cut,plaintext,Undo,Redo,About'
            });
            
    });
 
</script>
<script type="text/javascript">
    $( document ).ready(function() {
        
        
        let total_marks = '<?php echo $assessment_row->total_marks ?>';
        let marks_assigned = 0;
        
         
        toastr.options = {
          "debug": false,
          "positionClass": "toast-bottom-right" ,       //"toast-top-full-width",
          "onclick": null,
          "fadeIn": 300,
          "fadeOut": 1000,
          "timeOut": 5000,
          "extendedTimeOut": 1000
        };
        
       $(window).keydown(function(event){
              if(event.keyCode == 13) {
                   event.preventDefault();
                   return false;
              }
       });
           
       // change will be required here 
       /*
       $(".matching_marks").blur(function(){
            var marks = $.trim($(this).val());
            if(marks != ''){
                   var $regexnumber = /^[0-9-]*$/;
                   if (marks.match($regexnumber)) {
                        
                        marks_assigned = getAssignedMarks();    
                        $('#marksassigned').html(marks_assigned);
                       
                        if(marks_assigned > total_marks){
                           toastr.error('Marks Assigned Can Not Be Greater Than Total Assignment Marks');
                        }
                       
                   }
                   else
                   {
                        toastr.error('Marks Should be a numeric value');
                        $(this).val('');
                   }
            }
       });
       */
       
        $('body').delegate('.matching_marks','blur',function(){
            var marks = $.trim($(this).val());
            if(marks != ''){
                   var $regexnumber = /^[0-9-]*$/;
                   if (marks.match($regexnumber)) {
                        
                        marks_assigned = getAssignedMarks();    
                        $('#marksassigned').html(marks_assigned);
                       
                        if(marks_assigned > total_marks){
                           toastr.error('Marks Assigned Can Not Be Greater Than Total Assignment Marks');
                        }
                       
                   }
                   else
                   {
                        toastr.error('Marks Should be a numeric value');
                        $(this).val('');
                   }
            }
        });
       
       
       
       $(".marks").blur(function(){
            var marks = $.trim($(this).val());
            if(marks != ''){
                   var $regexnumber = /^[0-9-]*$/;
                   if (marks.match($regexnumber)) {
                        
                        marks_assigned = getAssignedMarks();    
                        $('#marksassigned').html(marks_assigned);
                       
                        if(marks_assigned > total_marks){
                           toastr.error('Marks Assigned Can Not Be Greater Than Total Assignment Marks');
                        }
                       
                   }
                   else
                   {
                        debugger;
                        toastr.error('Marks Should be a numeric value');
                        $(this).val('');
                   }
            }
       });
     
       $('.attachment').bind('change', function() {   
                var exts = ['png','jpg','jpeg','pdf','gif'];
                file = this.value;
                if ( file ) {
                    var get_ext = file.split('.');
                    get_ext = get_ext.reverse();
                    if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ){
                            
                    } 
                    else {
                        toastr.error('Only jpg,jpeg,png,pdf file allowed');
                        $(this).val('');
                    }
                }
      });        
    
    });
    

    function validateAssessmentForm(){
      
        var isValid        = true;
        var message        = '';
        var marks_assigned = getAssignedMarks();
        var total_marks    = '<?php echo $assessment_row->total_marks ?>';
        
        /*
        $('.statement').each(function(){
            
            var ids = $(this).attr('id');
            var splitted = ids.split('_');
            var question_no = splitted[2];
            
           
           if($.trim($(this).val()) == ''){
               isValid = false;
               message += 'Question Statement Is Required In Question # '+ question_no  +'<br>';
               //message = 'Question Statement Is Required<br>';
               return false;
           }
            
        });
        */
        
        $('.required_lines').each(function(){
            var ids = $(this).attr('id');
            var splitted = ids.split('_');
            var question_no = splitted[2];
            if($(this).val() == ''){
               isValid = false;
               message += 'Required Lines are mandatory In Question # '+ question_no  +'<br>';
               return false;
            }
        });
        
        $('.attachment').each(function(){
            var ids = $(this).attr('id');
            var splitted = ids.split('_');
            var question_no = splitted[2];
            if($(this).val() == ''){
               isValid = false;
               message += 'Image Attachment Is Required In Question # '+ question_no  +'<br>';
               //message += 'Image Attachment Is Required<br>';
               return false;
            }
        });
        
        $('.marks').each(function(){
            $marks = $(this);
            var lenofids = $marks.attr('id').split('_');
            var question_no = lenofids[2];
            console.log(lenofids);
            
            if($.trim($(this).val()) == ''){
               isValid = false;
               message += 'Question Total Marks Are Required In Question # '+ question_no  +'<br>';
               //message += 'Question Total Marks Are Required<br>';
               return false;
            }  
        });
        
        $('.fillintheblankscorrectanswer').each(function(){
            var ids = $(this).attr('id');
            var splitted = ids.split('_');
            var question_no = splitted[2];
            if($.trim($(this).val()) == ''){
               isValid = false;
               message += 'Fill In The Blanks Correct Answer Is Required In Question # '+ question_no  +'<br>';
               //message += 'Fill In The Blanks Correct Answer Is Required<br>';
               return false;
            }
        });
        
        $('.correctanswer').each(function(){
            var ids = $(this).attr('id');
            var splitted = ids.split('_');
            var question_no = splitted[2];
            
            if($.trim($(this).val()) == ''){
               isValid = false;
               message += 'True/False Correct Answer Is Required In Question # '+ question_no  +'<br>';
               //message += 'True/False Correct Answer Is Required<br>';
               return false;
            }
            
        });
        
        $('.correctoptionmcq').each(function(){
           

            var ids = $(this).attr('id');
            var splitted = ids.split('_');
            var question_no = splitted[2];
            
           
           if($.trim($(this).val()) == ''){
               isValid = false;
               message += 'MCQ Correct Option Is Required In Question # '+ question_no  +'<br>';
               //message += 'MCQ Correct Option Is Required<br>';
               return false;
           }
            
        });
        
        $('.correctoptionblanks').each(function(){
            

            var ids = $(this).attr('id');
            var splitted = ids.split('_');
            var question_no = splitted[2];
            

           
           if($.trim($(this).val()) == ''){
               isValid = false;
               message += 'Fill In The Blanks Correct Option Is Required In Question # '+ question_no  +'<br>';
               //message += 'Fill In The Blanks Correct Option Is Required<br>';
               return false;
           }
            
        });

        if(isValid){
            
                //$('.statement').each(function(){  
                $('.nonckstatement').each(function(){  
                    var $this = $(this);
                    var ids = $this.attr('id');
                    var splitted = ids.split('_');
                    var question_no = splitted[2];

                    if( $('#question_type_'+question_no).val() === "1")
                    {
                        var emptyCount = 0;
                        $('.'+question_no).each(function(){
                              if($.trim($(this).val()) == ''){
                                  emptyCount = parseInt(emptyCount);
                                  emptyCount = emptyCount + 1;
                                  emptyCount = parseInt(emptyCount);
                              }
                        });
                        
                        if(emptyCount == 3){
                               isValid = false;
                               message += 'At Least Three Options Are Required In Question # '+ question_no  +'<br>';
                               //message += 'At Least Three Options Are Required<br>';
                               return false;
                        }
                    }
                    
                    
                    if( $('#question_type_'+question_no).val() === "7")
                    {
                        var emptyCountMatching = 0;
                        for(j = 1; j <= 5; j++){
                            var left_option  =  $.trim( $('#left_option_'+question_no+'_'+j).val() );
                            var right_option =  $.trim( $('#right_option_'+question_no+'_'+j).val() );
                            var right_answer =  $.trim( $('#right_answer_'+question_no+'_'+j).val() );
                            var total_marks  =  $.trim( $('#marks_'+question_no+'_'+j).val() );
                            
                            if(left_option == '' && right_option == '' && right_answer == '' && total_marks == ''){
                                  emptyCountMatching = parseInt(emptyCountMatching);
                                  emptyCountMatching = emptyCountMatching + 1;
                                  emptyCountMatching = parseInt(emptyCountMatching);
                            } 
                        }

                        
                        if(emptyCountMatching == 3){
                               isValid = false;
                               message += 'At Least Three Options Are Required In Question # '+ question_no  +'<br>';
                               //message += 'At Least Three Options Are Required For Matching Question<br>';
                               return false;
                        }
                    }
                    
                
                });
   
        }
        
        if(isValid){
            
                $('.nonckstatement').each(function(){
                   
                    
                    var question    = $(this).val();
                    
                    var ids         = $(this).attr('id');
                    var splitted    = ids.split('_');
                    var question_no = splitted[2];
                    
                    if( $('#question_type_'+question_no).val() === "3" ) {
                        
                        var count_underscore = (question.match(/_/g) || []).length;
                        if(parseInt(count_underscore) == 0){
                            isValid = false;
                            //message += 'Underscore(_) Is Required For Adding A Blank<br>';
                            message += 'Underscore(_) Is Required For Adding A Blank In Question # '+ question_no  +'<br>';
                            return false; 
                        }
                        else if(parseInt(count_underscore) > 1){
                            isValid = false;
                            message += 'Only One Underscore Is Allowed In Question # '+ question_no  +'<br>';
                            //message += 'Only One Underscore Is Allowed In Fill In The Blank Question<br>';
                            return false; 
                        }
                        
                    }
                    
                });
   
        }
        
        if(marks_assigned > total_marks){
                isValid = false;
                message += 'Marks Assigned Can Not Be Greater Than Total Assignment Marks<br>';
        }
        
        if(marks_assigned < total_marks){
                isValid = false;
                message += 'Marks Assigned Should Be Equal To Total Assignment Marks<br>';
        }
        
        if(!isValid){
             $('#ValidationMessage').html(message);
        }
        else
        {
             $('#ValidationMessage').html('');
        }

        return isValid ? true : false;
        
         
        
    }
    
    // change will be required here too
    function getAssignedMarks(){
        
        var sum = 0;
        $(".marks").each(function(){
            var marks = $.trim($(this).val());
            if(marks != ''){
                   var $regexnumber = /^[0-9-]*$/;
                   if (marks.match($regexnumber)) {
                       
                       sum += parseInt(marks);
 
                   }
            }
        });
       
       
        $(".matching_marks").each(function(){
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
    
    function createOptions(select){
        var id = select.id;  
        var total_count = select.value;
        var res = id.split("_");
        var html = "";
         for(var j = 1; j <= total_count; j++)
         {
             html += 
             `<tr><td>`+j+`</td><td><input type="text" class="form-control `+res[1]+`" id="left_option_`+res[1]+`_`+j+`" name="left_option_`+res[1]+`[]">
             </td>
             <td>
             <input type="text" class="form-control `+res[1]+`" id="right_option_`+res[1]+`_`+j+`" name="right_option_`+res[1]+`[]">
             </td>
             <td>
             <select class="form-control correctoptionmatching" id="right_answer_`+res[1]+`_`+j+`" name="right_answer_`+res[1]+`[]">
                <option>Select Correct Answer</option>`;
                
                for(var k = 1; k <= total_count; k++)
                {
                    html +=`<option value="`+k+`">`+k+`</option>`;
                }
                
            html += `</select> 
            </td>
            <td>
                <input type="text" class="matching_marks  form-control `+res[1]+`" id="marks_`+res[1]+`_`+j+`" name="marks_`+res[1]+`[]">
            </td>
            </tr>`;
             
         }
         
        $('#body_'+res[1]).html(html);
    }
    
</script>


</body>
</html>



