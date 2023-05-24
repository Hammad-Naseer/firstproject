<style type="text/css">
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    
    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
    .redEsteric
    {
        color:red;
    }
</style>

<link href="<?=base_url()?>assets/select-picker/bootstrap-select.min.css" rel="stylesheet">
<script src="<?=base_url()?>assets/select-picker/bootstrap-select.min.js"></script>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('question_bank_assessments'); ?>
        </h3>   
    </div> 
</div>

<div class="col-md-12">
        
        <div class="panel panel-primary" style="margin-bottom: 0;">
             
            <div class="panel-header bg-primary text-white" style="padding: 10px;font-weight: bold;"><b>Add Question Bank Assessments</b></div>
            <div class="panel-body">
                <form id="assessment_form" action="<?php echo base_url(); ?>question_bank/save_assessment" method="POST">
                    <div class="row">
                        <div class="col-lg-12 col-l=md-12 col-sm-12 col-xs-12" data-step="1" data-position='top' data-intro="enter assessment title">
                          <label for="assessment_title">Assessment Title</label><span class="redEsteric">*</span>
                          <span style="color:red;float: right;" id="assessment_title_span"></span>
                          <input type="text" class="form-control" name="assessment_title" id = "assessment_title" required>
                        </div>
                    </div>    
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="2" data-position='top' data-intro="select assessment yearly term">
                          <label for="yearly_term_id">Select Class</label><span class="redEsteric">*</span>
                          <span style="color:red;float: right;" id="system_class"></span>
                             <?php echo get_system_classes_list(); ?>
                        </div>
                        
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="2" data-position='top' data-intro="select assessment yearly term">
                          <label for="yearly_term_id">Select Subject</label><span class="redEsteric">*</span>
                          <span style="color:red;float: right;" id="system_subject"></span>
                          <?php echo get_system_subjects_list(); ?>
                        </div>
                        
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="2" data-position='top' data-intro="select assessment yearly term">
                          <label for="chapter_id">Select Chapter</label><span class="redEsteric">*</span>
                          <select name="chapter_id" id='chapter_id' class="form-control " >
                          <!--<select class='form-control' >-->
                            <option value="">Select Chapter</option>
                          </select>    
                        </div>
                        <!--selectpicker multiple data-actions-box="true"-->
                        
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="2" data-position='top' data-intro="select assessment yearly term">
                          <label for="yearly_term_id">Select Yearly Term</label><span class="redEsteric">*</span>
                          <span style="color:red;float: right;" id="yearly_term_id_span"></span>
                          <select  class="form-control" name="yearly_term_id" id = "yearly_term_id" required>
                             <?php echo yearly_terms_option_list($_SESSION['academic_year_id'] , $_SESSION['yearly_term_id']); ?>
                          </select>
                        </div>
                    
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="3" data-position='top' data-intro="enter total marks">
                          <label for="total_marks">Total Marks</label><span class="redEsteric">*</span>
                          <span style="color:red;float: right;" id="total_marks_span"></span>
                          <input type="number" class="form-control" name="total_marks" id = "total_marks" required>
                        </div>
                    
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="4" data-position='top' data-intro="enter no of MCQS">
                          <label for="mcq_questions">No of MCQ's </label>
                          <span style="color:red;float: right;" id="mcq_questions_span"></span>
                          <input type="number" class="form-control check" name="mcq_questions" id = "mcq_questions">
                        </div>
                    
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="5" data-position='top' data-intro="enter no of True/False">
                          <label for="true_false_questions">No of True/false</label>
                          <input type="number" class="form-control check" name="true_false_questions" id = "true_false_questions">
                        </div>
                    
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="6" data-position='top' data-intro="enter no of Fill in the Blanks">
                          <label for="fill_in_the_blanks_questions">No of blanks</label>
                          <input type="number" class="form-control check" name="fill_in_the_blanks_questions" id = "fill_in_the_blanks_questions">
                        </div>
                    
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="7" data-position='top' data-intro="enter no of shot questions">
                          <label for="short_questions">No of Short Questions</label>
                          <input type="number" class="form-control check" name="short_questions" id = "short_questions">
                        </div>
                    
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="8" data-position='top' data-intro="enter no of long questions">
                          <label for="long_questions">No of Long Questions</label>
                          <input type="number" class="form-control check" name="long_questions" id = "long_questions">
                        </div>
                    
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="9" data-position='top' data-intro="enter no of Pictorial questions">
                          <label for="pictorial_questions">No of Pictorial Questions</label>
                          <input type="number" class="form-control check" name="pictorial_questions" id = "pictorial_questions">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="10" data-position='top' data-intro="enter no of column matching questions">
                          <label for="match_questions">No of Column Matching Questions</label>
                          <input type="number" class="form-control check" name="match_questions" id = "match_questions">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="11" data-position='top' data-intro="enter no of drawing questions">
                          <label for="drawing_questions">No of Drawing Questions</label>
                          <input type="number" class="form-control check" name="drawing_questions" id = "drawing_questions">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" data-step="12" data-position='top' data-intro="enter remarks">
                          <label for="remarks">Remarks</label>
                          <textarea class="form-control" name="remarks" id = "remarks" rows="4" cols="50"></textarea>
                        </div>
                    </div>
                    
                    <div class="row pull-right">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <button data-step="13" data-position='top' data-intro="press this button save assessment then you add assessment questions" 
                               style="margin-top:20px !important;" type="submit" id="submit" class="btn btn-default submit_btn" 
                               onclick = "return validate_form();">Save Assessment</button>
                        </div>
                    </div>    
                    
                     
                </form>

            </div>
            
        </div>
    
    </div>

<script>

function validate_form()
{
    var proceed = true;
    
    if($('#assessment_title').val() == ""){
        $('#assessment_title_span').html('Assessment Title is mandatory field');
        proceed = false;
    }
    else{
        $('#assessment_title_span').html('');
    }
    
    if($('#yearly_term_id').val() == ""){
        $('#yearly_term_id_span').html('Term is mandatory field');
        proceed = false;
    }
    else{
        $('#yearly_term_id_span').html('');
    }
    
    if($('#total_marks').val() == ""){
        $('#total_marks_span').html('Total Marks is mandatory field');
        proceed = false;
    }
    else{
        $('#total_marks_span').html('');
    }
    
    if (   $('#mcq_questions').val() == ""  && 
           $('#true_false_questions').val() == ""  && 
           $('#fill_in_the_blanks_questions').val() == "" &&  
           $('#short_questions').val() == ""  && 
           $('#long_questions').val() == ""    && 
           $('#pictorial_questions').val() == "" && 
           $('#match_questions').val() == ""   && 
           $('#drawing_questions').val() == "" ) 
        {
               
               $('#mcq_questions_span').html('atleast 1 no of question type is mandatory');
               proceed = false;
            
        }
        else
        {
            $('#mcq_questions_span').html('');
        }
    
    
    return proceed;
}


$("#subject_id").change(function(){
    var subject_id = $(this).val();
    $("#icon").remove();
    $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    $.ajax({
        type: 'POST',
        data: {
            subject_id: subject_id
        },
        url: "<?php echo base_url();?>question_bank/get_chapters_list",
        dataType: "html",
        success: function(response) {
            $("#icon").remove();
            $("#chapter_id").html(response);
        }
    });
});
</script>