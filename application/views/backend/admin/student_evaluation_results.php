<?php
if (right_granted('staffevaluationsettings_view'))
{


if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
  ?>
    <script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
    </script>
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
            </a>
            <h3 class="system_name inline">
                   <?php echo get_phrase('student_evaluation_results'); ?>
            </h3>
        </div>
   </div>
    
    
    
<form id="filter" method="post" data-step="2" data-position='top' data-intro="use this filter for specific staff evalution record">
    <div class="row filterContainer" style="padding-top: 14px;margin:0px;margin-right: 30px !important;margin-left: 30px !important;">
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label class=" control-label"><?php echo get_phrase('select_evaluation_type');?><span class="red"> * </span>  </label>
            <select id="evaluation_types" name="type" class="selectpicker_type form-control" data-validate="required" data-message-required="Value Required">
                <option value=''><?php echo get_phrase('all_types');?></option>
                <option <?php if($type == '1') { echo 'selected';} ?> value='1'><?php echo get_phrase('exam');?></option>
                <option <?php if($type == '2') { echo 'selected';} ?> value='2'><?php echo get_phrase('general');?></option>
            </select>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label class=" control-label"><?php echo get_phrase('select_class_section');?><span class="red"> * </span>  </label>
            <select id="section_field" class="selectpicker form-control required-field" name="section_id" data-validate="required" data-message-required="Value Required">
                <?php echo section_selector($section_id);?>
            </select>
            <div id="mydiv" class="red"></div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label class=" control-label"><?php echo get_phrase('select_student');?><span class="red"> * </span>  </label>
            <select id="student_field" class="selectpicker form-control" name="section_id" data-validate="required" data-message-required="Value Required">
                <option value=""><?php echo get_phrase('all_students'); ?></option>
            </select>
            <div id="mydiv" class="red"></div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <input type="hidden" name="apply_filter" value="1" />
            <button type="submit" id="select"  class="modal_save_btn"  style="margin-top:0px;"><?php echo get_phrase('filter');?></button>
            <?php
            if($apply_filter == 1){
            ?>
                    <a href="<?php echo base_url(); ?>staff_evaluation/evaluation" class="modal_cancel_btn" id="btn_remove" style="margin-top:0px;"> 
                        <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter');?>
                    </a>
            <?php
            }
            ?>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div id="student_evaluation_data">
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
                $stud_eval_query = "SELECT * FROM ".get_school_db().".student_evaluation WHERE school_id=".$_SESSION['school_id']."";
                $student_evaluations = $this->db->query($stud_eval_query)->result_array();
                foreach($student_evaluations as $row){
                    $query="SELECT * FROM ".get_school_db().".student WHERE student_id=".$row['student_id']." AND school_id=".$_SESSION['school_id']." AND student_status IN (".student_query_status().") ORDER BY roll desc";
                    $students=$this->db->query($query)->row();
                    $details=section_hierarchy($students->section_id);
                    
                    
                    
            ?>
                <tr>
                    <td class="td_middle">
                        <?php echo $count++;?>
                    </td>
                    <td class="td_middle">
                        <p><?php echo $students->name?></p>
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
        </div>  
    </div>    
</div>

<?php
}
?>

<!--//***********************Date filter validation***********************-->
<script>
    $(document).ready(function() {
        $("#section_field").change(function(){
            $('#filter').validate();
            var section_id = $("#section_field").val();
            if (section_id != '') {
                $('#btn_remove').show();
                $("#stud").html("<div id='loading' class='loader'></div>");
                $.ajax({
                    type: 'POST',
                    data: {
                        section_id: section_id,
                    },
                    url: "<?php echo base_url();?>student_evaluation/get_selected_section_student",
                    dataType: "html",
                    success: function(response) {
                       // $("#select").attr('disabled','disabled');
                        $("#loading").remove();
                        $("#student_field").html(response);
                    }
                });
            } else{
                $("#student_field").html('<option value="">All Student</option>');
            }
        });

        $("#select").click(function(e){
            e.preventDefault(); 
            $('.required-message').remove();
            $('#filter').validate();
            var section_id = $("#section_field").val();
            var student_id = $("#student_field").val();
            var evaluation_types = $("#evaluation_types").val();
            if (section_id != ''){
                $('#btn_remove').show();
                $("#student_evaluation_data").html("<div id='loading' class='loader'></div>");
                $.ajax({
                    type: 'POST',
                    data: {
                        section_id: section_id,
                        student_id: student_id,
                        evaluation_types:evaluation_types,
                    },
                    url: "<?php echo base_url();?>student_evaluation/get_stud_eval_result",
                    dataType: "html",
                    success: function(response) {
                       // $("#select").attr('disabled','disabled');
                        $("#loading").remove();
                        $("#student_evaluation_data").html(response);
                        $('.table_export').dataTable();
                    }
                });
            } 
            else{
                var required_miss = false;
                 $('.required-field').each(function () {
                     if($(this).val() == ''){
                        required_miss = true;
                        $(this).after("<b class='required-message' style='color: red;font-size: 13px;font-weight: 500;'>Value Required</b>");
                     }
                });
            }
        });
    });

</script>
<!--//********************************************************************-->
