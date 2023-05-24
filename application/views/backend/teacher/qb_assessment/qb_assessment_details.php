<?php
if(!isset($assessment_id))  { exit; }

$assessment_row    =  get_assessment_row($assessment_id);
if($assessment_row == null) {  exit; }

?>

<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

<style type="text/css">

    .login-card-body { 
        background: #fff;
        border-top: 0;
        color: #666;
        padding: 20px;
        border: 1px solid black;
    }
   
    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 0 solid rgba(0,0,0,.125);
        border-radius: .25rem;
    }
   
    .errorEstarics{
        color:red;
        font-size:0.9em;
        padding-left:1px;
    }
    
    .errorspan{
        color:red;
        font-size:0.9em;
    }
    
    /* 
    .btnChangeQuestionBox{
        padding:4px 5px !important;
        position: relative;
        left: -15px;
        top: -20px;
        border-radius: 0px !important;
        background: #FF5722 !important;
        border: 1px solid #FF5722 !important;
    }
    */
    
    .btnChangeQuestionBox{
        width: auto;
        font-size: 12px;
        position: relative;
        left: -15px;
        top: -20px;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        /*margin: 20px;*/
        padding:10px 15px 10px 15px;
        height: auto;
        text-align:center;
        border: none;
        background-size: 300% 100%;
        border-radius: 50px !important;
        moz-transition: all .4s ease-in-out;
        -o-transition: all .4s ease-in-out;
        -webkit-transition: all .4s ease-in-out;
        transition: all .4s ease-in-out;
    }
    
    .btnChangeQuestionBox {
        background-image: linear-gradient(to right, #fc6076, #ff9a44, #ef9d43, #e75516) !important;
        box-shadow:       3px 4px 0px 5px #fff !important;
    }
    
    .btnChangeQuestionBox:hover {
        background-position: 100% 0 !important;
        moz-transition: all .4s ease-in-out;
        -o-transition: all .4s ease-in-out;
        -webkit-transition: all .4s ease-in-out;
        transition: all .4s ease-in-out;
    }
    
    .btnChangeQuestionBox:focus {
        outline: none !important;
    }
    
    .label__checkbox {
       display: none;
    }

    .label__check {
        display: inline-block;
        border-radius: 50%;
        border: 5px solid rgba(0,0,0,0.1);
        background: white;
        vertical-align: middle;
        margin-right: 20px;
        width: 2em;
        height: 2em;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border .3s ease;
          
        i.icon {
            opacity: 0.2;
            font-size: ~'calc(1rem + 1vw)';
            color: transparent;
            transition: opacity .3s .1s ease;
            -webkit-text-stroke: 3px rgba(0,0,0,.5);
        }
          
        &:hover {
            border: 5px solid rgba(0,0,0,0.2);
        }
    }

    .label__checkbox:checked + .label__text .label__check {
        animation: check .5s cubic-bezier(0.895, 0.030, 0.685, 0.220) forwards;
        .icon {
            opacity: 1;
            transform: scale(0);
            color: white;
            -webkit-text-stroke: 0;
            animation: icon .3s cubic-bezier(1.000, 0.008, 0.565, 1.650) .1s 1 forwards;
        }
    }

    .center {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%,-50%);
    }
    

    @keyframes icon {
        from {
            opacity: 0;
            transform: scale(0.3);
        }
        to {
            opacity: 1;
            transform: scale(1)
        }
    }

    @keyframes check {
  0% {
    width: 1.5em;
    height: 1.5em;
    border-width: 5px;
  }
  10% {
    width: 1.5em;
    height: 1.5em;
    opacity: 0.1;
    background: rgba(0,0,0,0.2);
    border-width: 15px;
  }
  12% {
    width: 1.5em;
    height: 1.5em;
    opacity: 0.4;
    background: rgba(0,0,0,0.1);
    border-width: 0;
  }
  50% {
    width: 2em;
    height: 2em;
    background: #00d478;
    border: 0;
    opacity: 0.6;
  }
  100% {
    width: 2em;
    height: 2em;
    background: #00d478;
    border: 0;
    opacity: 1;
  }
}
    
    .qb_card_header{
        background: #03A9F4 !important;
        padding: 0.45rem 0.90rem !important;
        margin-bottom:8px;
        cursor:pointer;
    }
    
    #bank_1,#bank_2,#bank_3,#bank_4,#bank_5,#bank_6,#bank_7,#bank_8{
        overflow-y: scroll;
        overflow-x: scroll;
        height:auto;
        border: 1px solid #cccccc4f !important;
        padding: 28px 5px 5px 22px !important;
        box-shadow: 0px 4px 10px 2px #ccc6 !important;
    }
    
    .checkbox-inline, .radio-inline{
        vertical-align:unset !important;
    }
    
    .answer_tag{
        background: #3ba53f;
        color: white;
        padding: 3px 10px 3px 10px;
        border-radius: 10px;
    }

</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline"> <?php echo get_phrase('online_assessment_questions'); ?> </h3>
    </div>
</div>

<form action="<?php echo base_url().'question_bank/save_assessment_details' ?>" method="post" enctype="multipart/form-data">
    
    <div class="row">
    <div class="col-md-12">
        <div class="panel-group" id="accordion">
            
            <?php
            
                $q_count = 0;  
                $count   = 0;
                
                if($assessment_row->mcq_questions > 0) {
            ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <?php 
                              $start = $count + 1;
                              $end   = $count + $assessment_row->mcq_questions;
                            ?>
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseMCQ">MCQ Questions (<?php echo $start . ' - ' . $end ?>)</a>
                        </h4>
                    </div>
                    <div id="collapseMCQ" class="panel-collapse">
                        <div class="panel-body">

                            <?php
                                $q_count += $assessment_row->mcq_questions;
                                for($i = 1; $i <= $q_count; $i++){
                                    $count = $count + 1;
                            ?>      
                                        <div style="border:1px solid grey;padding:5px 5px 5px 5px;display:none" id="manual_<?php echo $count ?>">
                                            <button class="btnChangeQuestionBox" type="button" id="btn_manual_<?php echo $count ?>">Add Question From Question Bank</button>
                                            <p class="text-success text-center"><b>Question <?php echo $count ?></b></p>
                                            
                                            <div class="form-group">
                                              <label for="question_statement_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                              <textarea class="form-control rounded-0 statement mathdoxformula" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3"></textarea>
                                            </div>
                                            <div class="form-group">
                                              <label for="question_marks_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                              <input type="text" class="form-control marks" for="question_marks_<?php echo $count ?>" id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>">
                                            </div>
             
                                            <p class="text-success text-center"><b>Question <?php echo $count ?> Options</b></p>
                                            <p class="text-danger text-center"><b>At Least 3 Options Are Mandatory</b></p>
                                            
                                            <?php
                                             for($j = 1; $j <= 5; $j++){
                                            ?>
                                            <div class="form-group">
                                              <label for="question_option_<?php echo $count.'_'.$j ?>"><b>Enter Option <?php echo $j ?></b></label>
                                              <input type="text" class="form-control <?php echo $count?>" id="question_option_<?php echo $count.'_'.$j ?>" name="question_option_<?php echo $count?>[]">
                                            </div>
                                            <?php
                                             }
                                            ?>
                                            
                                            <div class="form-group">
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
                                            
                                            <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="1">
                                            
                                            
                                        </div>
                                        <div style="border:1px solid grey;padding:5px 5px 5px 5px;" id="bank_<?php echo $count ?>">
                                            <button class="btnChangeQuestionBox" type="button" id="btn_bank_<?php echo $count ?>">Add Question Manually</button>
                                            
                                             <?php echo get_questions_from_question_bank($count , 1 , $assessment_row->system_class , $assessment_row->system_subject , $assessment_row->system_chapter); ?>
                                            
                                        </div>
                                        <div style="height:3px;"></div>
                                        <input type="hidden" id="question_selection_type_<?php echo $count ?>" name="question_selection_type_<?php echo $count ?>" value="2" />
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
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFillBlanks">Fill In The Blank Questions (<?php echo $start . ' - ' . $end ?>)</a>
                        </h4>
                    </div>
                    <div id="collapseFillBlanks" class="panel-collapse collapse">
                        <div class="panel-body">
                            
                            
                            <?php
                                $q_count = $assessment_row->fill_in_the_blanks_questions;
                                for($i = 1; $i <= $q_count; $i++){
                                    $count = $count + 1;
                            ?>      
                                        <div style="border:1px solid grey;padding:5px 5px 5px 5px;display:none" id="manual_<?php echo $count ?>">
                                            <button class="btnChangeQuestionBox" type="button" id="btn_manual_<?php echo $count ?>">Add Question From Question Bank</button>
                                            <p class="text-success text-center"><b>Question <?php echo $count ?></b></p>
                                            
                                            <p class="text-danger text-center"><b>Please use single underscore(_) for adding a blank in the question </b></p>
                                            
                                            <div class="form-group">
                                              <label for="question_statement_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                              <textarea class="form-control rounded-0 nonckstatement mathdoxformula" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3"></textarea>
                                            </div>
                                            <div class="form-group">
                                              <label for="question_marks_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                              <input type="text" class="form-control marks" id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>">
                                            </div>
            
                                            <div class="form-group">
                                              <label for="question_correct_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Correct Answer <span class="errorEstarics">*</span></b></label>
                                              <input type="text" class="form-control fillintheblankscorrectanswer" id="question_correct_<?php echo $count ?>" 
                                                                                        name="question_correct_<?php echo $count ?>">
                                            </div>
                                            
                                            <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="3">
                                            
                                        </div>
                                        <div style="border:1px solid grey;padding:5px 5px 5px 5px;" id="bank_<?php echo $count ?>">
                                            <button class="btnChangeQuestionBox" type="button" id="btn_bank_<?php echo $count ?>">Add Question Manually</button>
                                             
                                            <?php echo get_questions_from_question_bank($count , 3 , $assessment_row->system_class , $assessment_row->system_subject , $assessment_row->system_chapter); ?>
                                        
                                        </div>
                                        <div style="height:3px;"></div>
                                        <input type="hidden" id="question_selection_type_<?php echo $count ?>" name="question_selection_type_<?php echo $count ?>" value="2" />
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
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTrueFalse">True False Questions (<?php echo $start . ' - ' . $end ?>)</a>
                        </h4>
                    </div>
                    <div id="collapseTrueFalse" class="panel-collapse collapse">
                        <div class="panel-body">

                            <?php
                                $q_count = $assessment_row->true_false_questions;
                                for($i = 1; $i <= $q_count; $i++){
                                   $count = $count + 1;
                            ?>      
                                    <div style="border:1px solid grey;padding:5px 5px 5px 5px;display:none" id="manual_<?php echo $count ?>">
                                        <button class="btnChangeQuestionBox" type="button" id="btn_manual_<?php echo $count ?>">Add Question From Question Bank</button>                 
                                        
                                        <p class="text-success text-center"><b>Question <?php echo $count ?></b></p>
                                        
                                        <div class="form-group">
                                          <label for="question_statement_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                          <textarea class="form-control rounded-0 statement mathdoxformula" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                          <label for="question_marks_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control marks" id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                          <label for="question_correct_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Correct Answer</b></label>
                                          <select class="form-control correctanswer" id="question_correct_<?php echo $count ?>" name="question_correct_<?php echo $count ?>">
                                                <option value="">Select Correct Answer</option>
                                                <option value="true">True</option>
                                                <option value="false">False</option>
                                          </select>
                                        </div>
                                        
                                        <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="2">
                                        
                                    </div>
                                    <div style="border:1px solid grey;padding:5px 5px 5px 5px;" id="bank_<?php echo $count ?>">
                                        <button class="btnChangeQuestionBox" type="button" id="btn_bank_<?php echo $count ?>">Add Question Manually</button>
                                        
                                        <?php echo get_questions_from_question_bank($count , 2 , $assessment_row->system_class , $assessment_row->system_subject , $assessment_row->system_chapter); ?>
                                        
                                    </div>
                                    <div style="height:3px;"></div>
                                    <input type="hidden" id="question_selection_type_<?php echo $count ?>" name="question_selection_type_<?php echo $count ?>" value="2" />
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
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseShort">Short Questions (<?php echo $start . ' - ' . $end ?>)</a>
                        </h4>
                    </div>
                    <div id="collapseShort" class="panel-collapse collapse">
                        <div class="panel-body">

                            <?php
                                $q_count = $assessment_row->short_questions;
                                for($i = 1; $i <= $q_count; $i++){
                                   $count = $count + 1;
                        ?>      
                                    <div style="border:1px solid grey;padding:5px 5px 5px 5px;display:none" id="manual_<?php echo $count ?>">
                                        
                                        <button class="btnChangeQuestionBox" type="button" id="btn_manual_<?php echo $count ?>">Add Question From Question Bank</button>
                                        <p class="text-success text-center"><b>Question <?php echo $count ?></b></p>
                                        
                                        <div class="form-group">
                                          <label for="question_statement_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                          <textarea class="form-control rounded-0 statement mathdoxformula" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3"></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                          <label for="question_marks_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control marks" id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>">
                                        </div>
                                        
                                        <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="4">
                                        
                                    </div>
                                    <div style="border:1px solid grey;padding:5px 5px 5px 5px;" id="bank_<?php echo $count ?>">
                                        <button class="btnChangeQuestionBox" type="button" id="btn_bank_<?php echo $count ?>">Add Question Manually</button>
                                        
                                        <?php echo get_questions_from_question_bank($count , 4 , $assessment_row->system_class , $assessment_row->system_subject , $assessment_row->system_chapter); ?>
                                        
                                    </div>
                                    <div style="height:3px;"></div>
                                    <input type="hidden" id="question_selection_type_<?php echo $count ?>" name="question_selection_type_<?php echo $count ?>" value="2" />
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
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseLong">Long Questions (<?php echo $start . ' - ' . $end ?>)</a>
                        </h4>
                    </div>
                    <div id="collapseLong" class="panel-collapse collapse">
                        <div class="panel-body">

                            <?php
                                $q_count = $assessment_row->long_questions;
                                for($i = 1; $i <= $q_count; $i++){
                                   $count = $count + 1;
                        ?>      
                                    <div style="border:1px solid grey;padding:5px 5px 5px 5px;display:none" id="manual_<?php echo $count ?>">
                                        
                                        <button class="btnChangeQuestionBox" type="button" id="btn_manual_<?php echo $count ?>">Add Question From Question Bank</button>
                                        <p class="text-success text-center"><b>Question <?php echo $count ?></b></p>
                                        
                                        <div class="form-group">
                                          <label for="question_statement_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                          <textarea class="form-control rounded-0 statement editor" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3"></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                          <label for="question_marks_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control marks" id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>">
                                        </div>
                                        
                                        <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="5">
                                        
                                    </div>
                                    <div style="border:1px solid grey;padding:5px 5px 5px 5px;" id="bank_<?php echo $count ?>">
                                        <button class="btnChangeQuestionBox" type="button" id="btn_bank_<?php echo $count ?>">Add Question Manually</button>
                                        
                                        <?php echo get_questions_from_question_bank($count , 5 , $assessment_row->system_class , $assessment_row->system_subject , $assessment_row->system_chapter); ?>
                                        
                                    </div>
                                    <div style="height:3px;"></div>
                                    <input type="hidden" id="question_selection_type_<?php echo $count ?>" name="question_selection_type_<?php echo $count ?>" value="2" />
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
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapsePictorial">Picture Description Questions (<?php echo $start . ' - ' . $end ?>)</a>
                        </h4>
                    </div>
                    <div id="collapsePictorial" class="panel-collapse collapse">
                        <div class="panel-body">

                            <?php
                               $q_count = $assessment_row->pictorial_questions;
                               for($i = 1; $i <= $q_count; $i++){
                                   $count = $count + 1;
                        ?>      
                                    <div style="border:1px solid grey;padding:5px 5px 5px 5px;display:none" id="manual_<?php echo $count ?>">
                                        <button class="btnChangeQuestionBox" type="button" id="btn_manual_<?php echo $count ?>">Add Question From Question Bank</button>      
                                        
                                        <p class="text-success text-center"><b>Question <?php echo $count ?></b></p>
                                        
                                        <div class="form-group">
                                          <label for="question_statement_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                          <textarea class="form-control rounded-0 nonckstatement" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3"></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                          <label for="question_attachment_<?php echo $count ?>"><b>Attach Image <span class="errorEstarics">*</span></b></label>
                                          <input type="file" class="form-control rounded-0 attachment" id="question_attachment_<?php echo $count ?>" name="question_attachment_<?php echo $count ?>" >
                                        </div>
                                        
                                        <div class="form-group">
                                          <label for="question_marks_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control marks" id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>">
                                        </div>
                                        
                                        <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="6">
                                        
                                    </div>
                                    <div style="border:1px solid grey;padding:5px 5px 5px 5px;" id="bank_<?php echo $count ?>">
                                        <button class="btnChangeQuestionBox" type="button" id="btn_bank_<?php echo $count ?>">Add Question Manually</button>
                                        
                                        <?php echo get_questions_from_question_bank($count , 6 , $assessment_row->system_class , $assessment_row->system_subject , $assessment_row->system_chapter); ?>
                                        
                                    </div>
                                    <div style="height:3px;"></div>
                                    <input type="hidden" id="question_selection_type_<?php echo $count ?>" name="question_selection_type_<?php echo $count ?>" value="2" />
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
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseMatching">Matching Questions (<?php echo $start . ' - ' . $end ?>)</a>
                        </h4>
                    </div>
                    <div id="collapseMatching" class="panel-collapse collapse">
                        <div class="panel-body">

                            <?php
                                   $q_count = $assessment_row->match_questions;
                                   for($i = 1; $i <= $q_count; $i++){
                                       $count = $count + 1;
                            ?>      
                                        <div style="border:1px solid grey;padding:5px 5px 5px 5px;display:none" id="manual_<?php echo $count ?>">
                                            
                                            <button class="btnChangeQuestionBox" type="button" id="btn_manual_<?php echo $count ?>">Add Question From Question Bank</button>
                                            <p class="text-success text-center"><b>Question <?php echo $count ?></b></p>
                                            
                                            <div class="form-group">
                                              <label for="question_statement_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                              <textarea class="form-control rounded-0 nonckstatement" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3"></textarea>
                                            </div>
                                            
                                            <p class="text-success text-center"><b>Question <?php echo $count ?> Options</b></p>
                                            <p class="text-danger text-center"><b>At Least 3 Options Are Mandatory</b></p>
                                            
                                            <div class="form-group">
                                              <label for="select_<?php echo $count ?>">Select # of Options for Question <?php echo $count ?></label>
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
                                        <div style="border:1px solid grey;padding:5px 5px 5px 5px;" id="bank_<?php echo $count ?>">
                                            <button class="btnChangeQuestionBox" type="button" id="btn_bank_<?php echo $count ?>">Add Question Manually</button>
                                            
                                            <?php echo get_questions_from_question_bank($count , 7 , $assessment_row->system_class , $assessment_row->system_subject , $assessment_row->system_chapter); ?>
                                            
                                        </div>
                                        <div style="height:3px;"></div>
                                        <input type="hidden" id="question_selection_type_<?php echo $count ?>" name="question_selection_type_<?php echo $count ?>" value="2" />
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
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapsedrawing">Drawing Questions (<?php echo $start . ' - ' . $end ?>)</a>
                        </h4>
                    </div>
                    <div id="collapsedrawing" class="panel-collapse collapse">
                        <div class="panel-body">

                            <?php
                                $q_count = $assessment_row->drawing_questions;
                                for($i = 1; $i <= $q_count; $i++){
                                   $count = $count + 1;
                        ?>      
                                    <div style="border:1px solid grey;padding:5px 5px 5px 5px;display:none" id="manual_<?php echo $count ?>">
                                        
                                        <button class="btnChangeQuestionBox" type="button" id="btn_manual_<?php echo $count ?>">Add Question From Question Bank</button>                                    
                                        <p class="text-success text-center"><b>Question <?php echo $count ?></b></p>
                                        
                                        <div class="form-group">
                                          <label for="question_statement_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Statement <span class="errorEstarics">*</span></b></label>
                                          <textarea class="form-control rounded-0 nonckstatement" id="question_statement_<?php echo $count ?>" name="question_statement_<?php echo $count ?>" rows="3"></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                          <label for="question_marks_<?php echo $count ?>"><b>Enter Question <?php echo $count ?> Total Marks <span class="errorEstarics">*</span></b></label>
                                          <input type="text" class="form-control marks" id="question_marks_<?php echo $count ?>" name="question_marks_<?php echo $count ?>">
                                        </div>
                                        
                                        <input type="hidden" id="question_type_<?php echo $count ?>" name="question_type_<?php echo $count ?>" value="8">
                                        
                                    </div>
                                    <div style="border:1px solid grey;padding:5px 5px 5px 5px;" id="bank_<?php echo $count ?>">
                                        <button class="btnChangeQuestionBox" type="button" id="btn_bank_<?php echo $count ?>">Add Question Manually</button>
                                        
                                        <?php echo get_questions_from_question_bank($count , 8 , $assessment_row->system_class , $assessment_row->system_subject , $assessment_row->system_chapter);  ?>
                                        
                                    </div>
                                    <div style="height:3px;"></div>
                                    <input type="hidden" id="question_selection_type_<?php echo $count ?>" name="question_selection_type_<?php echo $count ?>" value="2" />
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
        <div class="col-md-12">
            <div class="float-right">
                <input type="hidden"  id="assessment_id"   name="assessment_id"   value="<?php echo $assessment_id ?>">
                <input type="hidden"  id="question_count"  name="question_count"  value="<?php echo $count ?>">
                <input type="button"  id="validateBtn"     name="validateBtn"     value="Save Question Paper"     class="btn btn-success">
                <input type="submit"  id="submitBtn"       name="submitBtn"       value="Save Question Paper"     class="btn btn-success" style="display:none">
                <input type="hidden"  id="total_assessment_marks"                 value="<?php echo $assessment_row->total_marks;  ?>">
            </div>
            <!--onclick="return validateAssessmentForm()"-->
        </div>
    </div>

</form>

<div class="row">
    <div class="col-md-12">
        <div class="float-right">
            <p id="Total Marks"    style="color:green">Total Marks  : <span id="totalmarks"><?php echo $assessment_row->total_marks ?></span></p> 
            <p id="AssignedMarks"  style="color:red">Assigned Marks : <span id="marksassigned">0</span></p>      
        </div>
    </div>
</div>

<div class="row" id="ValidationMessageRow">
    <div class="col-md-12">
        <p id="ValidationMessage" style="color:red"></p>
    </div>
</div>

<link rel="stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" >
<script                src  = "https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" ></script>
<script                src  = "https://cdn.ckeditor.com/4.14.1/standard-all/ckeditor.js" ></script>

<script>

    $('.statement').each(function(){
        
        var $this       = $(this);
        var ids         = $this.attr('id');
        var splitted    = ids.split('_');
        var question_no = splitted[2];
            
        CKEDITOR.plugins.addExternal('ckeditor_wiris', 'https://www.wiris.net/demo/plugins/ckeditor/', 'plugin.js');
            
        CKEDITOR.config.uiColor = '#427fa6';
        CKEDITOR.config.width   = '100%'; 
             
        CKEDITOR.replace('question_statement_'+question_no, {
            extraPlugins: 'ckeditor_wiris',
            height: 200
        });
            
    });
 
</script>

<script type="text/javascript">

    $(document).ready(function() {
        
        var total_marks    = $('#total_assessment_marks').val();
        var marks_assigned = 0;
        
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
        
        $(".btnChangeQuestionBox").on("click", function(){
  
           var ids         = $(this).attr('id');
           var splitted    = ids.split('_');
           var question_no = splitted[2];
           var boxtype     = splitted[1];
           
           if(boxtype == 'bank'){ 
                $('#bank_'+question_no).css('display','none');
                $('#manual_'+question_no).css('display','block');
                $('#question_selection_type_'+question_no).val(1);
                
                if( $('#question_type_'+question_no).val() != "7" )
                {
                    if($('#qb_question_marks_'+question_no).val() != ''){
                        marks_assigned = getAssignedMarks() - parseInt($('#qb_question_marks_'+question_no).val());  
                        $('#marksassigned').html(marks_assigned);  
                    }
                    $('#qb_question_marks_'+question_no).val(''); // empty marks fields in question bank
                } 
                else
                {
                    $('.qb'+question_no).each(function(){
                        var $this  = $(this);
                        if($this.val() != ''){
                            marks_assigned = getAssignedMarks() - parseInt($this.val());  
                            $('#marksassigned').html(marks_assigned);  
                        }
                        $this.val('');
                    });
                }
                
            }
           else
           {
               $('#bank_'+question_no).css('display','block');
               $('#manual_'+question_no).css('display','none');
               $('#question_selection_type_'+question_no).val(2);
               
               if( $('#question_type_'+question_no).val() != "7" )
               {
                    if($('#question_marks_'+question_no).val() != ''){
                        marks_assigned = getAssignedMarks() - parseInt($('#question_marks_'+question_no).val());  
                        $('#marksassigned').html(marks_assigned);  
                    }
                    $('#question_marks_'+question_no).val('');  // empty marks fields in manual question
               }
               else
               {
                    $('.'+question_no).each(function(){
                        var $this  = $(this);
                        if($this.val() != ''){
                            marks_assigned = getAssignedMarks() - parseInt($this.val());  
                            $('#marksassigned').html(marks_assigned);  
                        }
                        $this.val('');
                    });
               }
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
                       toastr.error('Marks Assigned Can Not Be Greater Than Total Assessment Marks');
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
                       toastr.error('Marks Assigned Can Not Be Greater Than Total Assessment Marks');
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
    
    
    $('#validateBtn').click(function(){
        
   
        var isValid        = true;
        var message        = '';
        var marks_assigned = getAssignedMarks();
        var total_marks    = $('#total_assessment_marks').val();
        
        var question_count = $('#question_count').val();
        
        /*
            1 MCQ Questions
            2 True False Questions
            3 Fill In The Blanks Questions
            4 Short Questions
            5 Long Questions
            6 Pictorial Questions
            7 Matching Questions
            8 Drawing Questions
        */
        
        // loop through all questions
        for(index = 1; index <= question_count;  index++){
           
           var question_type           =  parseInt($('#question_type_'+index).val());
           var question_selection_type =  $('#question_selection_type_'+index).val();
           if(question_selection_type == 1)
	       {
	           
	          if(question_type == 1)
	          {
	              
                    var emptyCount = 0;
                    $('.'+index).each(function(){
                          if($.trim($(this).val()) == ''){
                              emptyCount = parseInt(emptyCount);
                              emptyCount = emptyCount + 1;
                              emptyCount = parseInt(emptyCount);
                          }
                    });
                    
                    if(emptyCount >= 3){
                           isValid = false;
                           message += 'At Least Three Options Are Required In Question '+index+'<br>';
                    }
                    
                    if( $.trim($('#question_marks_'+index).val()) == "" ){
    	                 isValid = false;
    	                 message += 'Marks Field Is Required In Question '+index+'<br>';
    	                 $('#question_marks_'+index).css('border','1px solid red');
    	            }
    	            
    	            if( $.trim($('#question_correct_'+index).val()) == "" ){
    	                 isValid = false;
    	                 message += 'Correct Answer Field Is Required In Question '+index+'<br>';
    	                 $('#question_correct_'+index).css('border','1px solid red');
    	            }
	              
	          }
	          
	          if(question_type == 4  || question_type == 5 || question_type == 8)
	          {
	              
	              if( $.trim($('#question_marks_'+index).val()) == "" ){
    	                 isValid = false;
    	                 message += 'Marks Field Is Required In Question '+index+'<br>';
    	                 $('#question_marks_'+index).css('border','1px solid red');
    	          } 
    	          
    	          if( question_type == 8 ){
    	              
    	              if( $.trim($('#question_statement_'+index).val()) == "" ){
    	                  isValid = false;
    	                  message += 'Statement Field Is Required In Question '+index+'<br>';
    	                  $('#question_statement_'+index).css('border','1px solid red');
    	              }
    	          
    	          }
    	          
	              
	          }
	          
	          if(question_type == 2)
	          {
	            
    	          if( $.trim($('#question_marks_'+index).val()) == "" ){
    	                 isValid = false;
    	                 message += 'Marks Field Is Required In Question '+index+'<br>';
    	                 $('#question_marks_'+index).css('border','1px solid red');
    	          }
    	          if( $.trim($('#question_correct_'+index).val()) == "" ){
    	                 isValid = false;
    	                 message += 'Correct Answer Field Is Required In Question '+index+'<br>';
    	                 $('#question_correct_'+index).css('border','1px solid red');
    	          }
	              
	          }
	          
	          if(question_type == 3){
	              
	               if( $.trim($('#question_statement_'+index).val()) == "" ){
    	                 isValid = false;
    	                 message += 'Statement Field Is Required In Question '+index+'<br>';
    	                 $('#question_statement_'+index).css('border','1px solid red');
    	           }
    	           else{
    	             
    	                var question         = $('#question_statement_'+index).val();
    	                var count_underscore = (question.match(/_/g) || []).length;
                        if(parseInt(count_underscore) == 0){
                            isValid = false;
                            message += 'Underscore(_) Is Required For Adding A Blank In Question '+index+'<br>';
                        }
                        else if(parseInt(count_underscore) > 1){
                            isValid = false;
                            message += 'Only One Underscore Is Allowed In Question '+index+'<br>';
                        }
    	               
    	           }
    	           
    	           
    	           
    	           if( $.trim($('#question_marks_'+index).val()) == "" ){
    	                 isValid = false;
    	                 message += 'Marks Field Is Required In Question '+index+'<br>';
    	                 $('#question_marks_'+index).css('border','1px solid red');
    	           }
    	           if( $.trim($('#question_correct_'+index).val()) == "" ){
    	                 isValid = false;
    	                 message += 'Correct Answer Field Is Required In Question '+index+'<br>';
    	                 $('#question_correct_'+index).css('border','1px solid red');
    	           }
    	           
    	           
    	           
	              
	          }
	          
	          if(question_type == 6)
	          {
	              
	              if( $.trim($('#question_statement_'+index).val()) == "" ){
    	                  isValid = false;
    	                  message += 'Statement Field Is Required In Question '+index+'<br>';
    	                  $('#question_statement_'+index).css('border','1px solid red');
    	          }
	              
	              if( $.trim($('#question_marks_'+index).val()) == "" ){
    	                 isValid = false;
    	                 message += 'Marks Field Is Required In Question '+index+'<br>';
    	                 $('#question_marks_'+index).css('border','1px solid red');
    	          } 
    	          
    	          if( $.trim($('#question_attachment_'+index).val()) == "" ){
                        isValid = false;
                        message += 'Image Attachment Is Required In Question '+index+'<br>';
                        $('#question_attachment_'+index).css('border','1px solid red');
                  }
	              
	          }
	          
	          if(question_type == 7)
	          {
	              
	              if( $.trim($('#question_statement_'+index).val()) == "" ){
    	                 isValid = false;
    	                 message += 'Statement Field Is Required In Question '+index+'<br>';
    	                 $('#question_statement_'+index).css('border','1px solid red');
    	          }
    	          
    	          if( $.trim($('#select_'+index).val()) == "" ){
    	                 isValid = false;
    	                 message += 'Options Field Is Required In Question '+index+'<br>';
    	                 $('#select_'+index).css('border','1px solid red');
    	          }
    	          
    	          
    	          
    	          var emptyCountMatching = 0;
    	          
                  for(j = 1; j <= 5; j++){
                        var left_option  =  $.trim( $('#left_option_'+index+'_'+j).val() );
                        var right_option =  $.trim( $('#right_option_'+index+'_'+j).val() );
                        var right_answer =  $.trim( $('#right_answer_'+index+'_'+j).val() );
                        var option_marks  =  $.trim( $('#marks_'+index+'_'+j).val() );
                        
                        if(left_option == '' && right_option == '' && right_answer == '' && option_marks == ''){
                              emptyCountMatching = parseInt(emptyCountMatching);
                              emptyCountMatching = emptyCountMatching + 1;
                              emptyCountMatching = parseInt(emptyCountMatching);
                        } 
                  }
                  
                  if(emptyCountMatching >= 3){
                       isValid = false;
                       message += 'At Least Three Options Are Required In Question '+index+'<br>';
                  }
	             
	              
	          }
	           
	       }
	       else
	       {
	           
	          if(question_type == 1)
	          {
	              
	              if( $.trim($('#qb_question_marks_'+index).val()) == "" ){
	                  isValid = false;   
	                  message += 'Marks Field Is Required In Question '+index+'<br>';
	                  $('#qb_question_marks_'+index).css('border','1px solid red');
	              }
	              
	          }
	          
	          if(question_type == 4  || question_type == 5 || question_type == 8)
	          {
	              
	            if( $.trim($('#qb_question_marks_'+index).val()) == "" ){
	                  isValid = false;
	                  message += 'Marks Field Is Required In Question '+index+'<br>';
	                  $('#qb_question_marks_'+index).css('border','1px solid red');
	            }  
	              
	          }
	          
	          if(question_type == 2  || question_type == 3)
	          {
	              
	            if( $.trim($('#qb_question_marks_'+index).val()) == "" ){
	                  isValid = false;
	                  message += 'Marks Field Is Required In Question '+index+'<br>';
	                  $('#qb_question_marks_'+index).css('border','1px solid red');
	            }  
	              
	          }
	          
	          if(question_type == 6)
	          {
	              
	             if( $.trim($('#qb_question_marks_'+index).val()) == "" ){
	                  isValid = false;
	                  message += 'Marks Field Is Required In Question '+index+'<br>';
	                  $('#qb_question_marks_'+index).css('border','1px solid red');
	             } 
	              
	          }
	          
	          if(question_type == 7)
	          {
	             var isValidMarksMatchingQuestion  = true;
	             $('.qb'+index).each(function(){
                        var $this  = $(this);
                        if($.trim($this.val()) == ''){
                             isValid = false;
                             isValidMarksMatchingQuestion = false;
                             $this.css('border','1px solid red'); 
                        }
                });
                
                if(!isValidMarksMatchingQuestion){
                    message += 'Marks Field Is Required In Question '+index+'<br>';
                }
	              
	          }
	           
	           
	       }
            
        }
        
    
        if(isValid){
            
            if(marks_assigned > total_marks){
                isValid = false;
                message += 'Marks Assigned Can Not Be Greater Than Total Assessment Marks<br>';
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
                 $('#validateBtn').attr("disabled","disabled");
                 $('#submitBtn').click();
            }
            
        }
        else
        {
            $('#ValidationMessage').html(message);
        }
        
        
    });
    
    
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
        
         var id          = select.id;  
         var total_count = select.value;
         var res         = id.split("_");
         var html        = "";
        
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
         
         debugger;
         $('#body_'+res[1]).html(html);
         debugger;
        
    }
</script>





