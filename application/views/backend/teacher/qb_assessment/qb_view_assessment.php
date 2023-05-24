<?php

if($this->session->flashdata('club_updated'))
{
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
}

?>

<script>
$( window ).on("load",function() 
{
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>
<style>
    .thisrow{
        background-color: #f0f0f0;
        padding-bottom: 10px;
        border-radius: 5px;
        margin-top: 10px !important;
        padding-top: 15px;
        margin-bottom: 13px;
        border: 1px solid #CCC;
        padding:15px;
    }
</style>


<?php
        $this->load->helper('teacher');
        $login_detail_id    = $_SESSION['login_detail_id'];
        $yearly_term_id_a   = $_SESSION['yearly_term_id'];
        $academic_year_id   = $_SESSION['academic_year_id'];
        $section_arr        = get_time_table_teacher_section($login_detail_id, $yearly_term_id_a);
?>

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

<form action="<?php echo base_url().'question_bank/qb_view_assessment' ?>" method="POST" data-step="2" data-position='top' data-intro="use this filter get assessment specific records">
    <div class="row filterContainer">
    
    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group">
              <span style="color:red;float: right;" id="yearly_term_id_span"></span>
              <select  class="form-control" name="yearly_term_id" id = "yearly_term_id" >
                 <?php echo yearly_terms_option_list($_SESSION['academic_year_id'] , $yearly_term_id); ?>
              </select>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group">
            <span style="float:right; color:red;" id="section_id_span"></span>
            <select id="section_id" class="dcs_list_add form-control" name="section_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                <?php 
                    echo get_teacher_dep_class_section_list($section_arr , $section_id);
                ?>
            </select>
        </div>
    </div>
    
    <!-- 

	<div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group">
                <select name="dep_c_s_id" id="dep_c_s_id" class="dcs_list form-control" >
                        <?php //echo get_teacher_dep_class_section_list($teacher_section, $section); ?>
                </select>
		</div>
	</div>
	
	-->
			
	<div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group">
            <select name="subject" id="subject_list" class="form-control" >
                <option value=""><?php echo get_phrase('select_subject');?></option>
            </select>	
		</div>
					
	</div>
    
	<div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group">
           <input type="hidden" name="apply_filter" value="1">
           <button type="submit" class="btn btn-primary"> Filter</button>
           <?php
            if ($apply_filter == 1)
            {
            ?>
              <a href="<?php echo base_url(); ?>assessment/view_assessment" class="btn btn-danger"><i class="fa fa-remove"></i> Remove</a>
            <?php
            
            }
            
          ?>
        </div>
	</div>
	
	</div>
</form>


<div>
	<div class="col-md-12">
	    <table class="table table-striped table-bordered table_export" data-step="3" data-position='top' data-intro="assessment records">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('s_no');?></div></th>
                    		<th style="width:250px;"><div><?php echo get_phrase('question_bank Assessment Details');?></div></th>
                           	<th><div><?php echo get_phrase('question_details');?></div></th>
                           	
                           	
                            <th><div><?php echo get_phrase('action');?></div></th>
                            <th><div><?php echo get_phrase('assign');?></div></th>
                            <th><div><?php echo get_phrase('submitted_assessments');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php 
                    	$j=$start_limit;
                    	$count = 1;foreach($assessments as $row):
                    	$j++;
                    	?>
                        <tr>
                        	<td class="td_middle"><?php echo $j; ?></td>
                        	
                        	<td class="td_middle">
                        	    <strong>Title :</strong>
                        	    <?php echo $row['assessment_title'];?>
                        	    <br>
                        	    <strong>Name :</strong>
                        	    <?php echo get_teacher_name($row['teacher_id']);?>
                        	    <br>
                        	    
                        	    <strong>Yearly Term :</strong>
                        	    <?php
                        	        $yearly_terms =  get_yearly_terms($_SESSION['academic_year_id'] , $row['yearly_term_id'] );
                        	        echo $yearly_terms[0]['title'];
                        	    ?>
                        	    <br>
                        	    <strong>Total Marks :</strong>
                        	    <?php echo $row['total_marks'];?>
                        	    <?php
                                	if($row['is_completed'] == 1 && $row['is_assigned'] == 1)
                                	{
                                	    echo get_assessment_time($row['assessment_id']);
                                	}else{
                                	    echo "<br><strong>Status :</strong><span style='color:red;'>Not Assigned</span>";
                                	}
                            	?>
                        	</td>
							<td>
							    <?php 
							        
							        if($row['mcq_questions'] > 0 ){
							            echo "<strong>No of MCQ's : </strong><span class='badge badge-info badge-roundless pull-right'>".$row['mcq_questions']."</span><br><br>";
							        }
							        if($row['true_false_questions'] > 0 ){
							            echo "<strong>No of True/False : </strong><span class='badge badge-success badge-roundless pull-right'>".$row['true_false_questions']."</span><br><br>";
							        }
							        if($row['fill_in_the_blanks_questions'] > 0 ){ 
							            echo "<strong>No of Blanks : </strong><span class='badge badge-info badge-roundless pull-right'>".$row['fill_in_the_blanks_questions']."</span><br><br>";
							        }
							        if($row['short_questions'] > 0 ){   
							            echo "<strong>No of Short Q's : </strong><span class='badge badge-success badge-roundless pull-right'>".$row['short_questions']."</span><br><br>";
							        }
							        if($row['long_questions'] > 0 ){   
							            echo "<strong>No of Long Q's </strong><span class='badge badge-info badge-roundless pull-right'>".$row['long_questions']."</span><br><br>";
							        }
							        if($row['pictorial_questions'] > 0 ){   
							            echo "<strong>No of Pictorial Q's</strong><span class='badge badge-success badge-roundless pull-right'>".$row['pictorial_questions']."</span><br><br>";
							        }
							        if($row['match_questions'] > 0 ){   
							            echo "<strong>No of Column Matching Q's</strong><span class='badge badge-info badge-roundless pull-right'>".$row['match_questions']."</span><br><br>";
							        }
							        if($row['drawing_questions'] > 0 ){   
							            echo "<strong>No of Drawing Q's</strong><span class='badge badge-success badge-roundless pull-right'>".$row['drawing_questions']."</span><br><br>";
							        }
							    ?>
							</td>
							<td class="td_middle">
							    <?php
							      if($row['is_completed'] == 0){
							    ?>
							       <a data-step="4" data-position='top' data-intro="click this button add assessment details" class="btn btn-primary btn-sm" href="<?php echo base_url()."question_bank/assessment_details/".$row['assessment_id'];?>" target="_blank">Add Details</a>
							    <?php
							      }
							      else{
							    ?>
							       <a data-step="5" data-position='top' data-intro="click this button view assessment details" class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/view_assessment_form/".$row['assessment_id'];?>" target="_blank">View Details</a>
							    <?php
							      }
							    ?>
							    
							</td>
							<td class="td_middle">
							    <?php
							      if($row['is_completed'] == 1){
							    ?>
							      <a data-step="6" data-position='top' data-intro="click this button assign assessment to students" class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/assign_assessment/".$row['assessment_id'];?>" target="_blank">Assign</a> 
							    <?php
							      }
							    ?>
							    
							</td>
							
							<td class="td_middle">
							     <a class="btn btn-primary btn-sm" href="<?php echo base_url()."question_bank/view_assessments_submitted/".$row['assessment_id'];?>" target="_blank">View Answer Sheets</a> 
							</td>
                            
                            
        				</tr>
                 <?php endforeach;?>
                 </tbody>
                </table>
	</div>
</div>

<script>

    $("#section_id").change(function() {
        	$('#item_list').html('');
            var section_id = $(this).val();
    
            $("#icon").remove();
            $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    
            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>teacher/get_section_student_subject",
                dataType: "html",
                success: function(response) {
                    var obj = jQuery.parseJSON(response);
                    $("#icon").remove();
                    $("#subject_list").html(obj.subject);
    
                }
            });
    
        });
            
        <!--Datatables Add Button Script-->
        var datatable_btn = "<a href='<?php echo base_url();?>question_bank/create_question_bank' data-step='1' data-position='top' data-intro='click this button to add new assessment' class='modal_open_btn'><?php echo get_phrase('create_question_bank_assessment');?></a>"; 
        
</script>


