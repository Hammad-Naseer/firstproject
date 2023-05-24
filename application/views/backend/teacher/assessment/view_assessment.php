<style>
    @media (max-width: 420px){
        .dataTables_wrapper > div.dataTables_filter {
            height: 218px !important;
        }
        
    }
</style>
<?php
    if($this->session->flashdata('club_updated'))
    {
        echo "<script>sweet_message('".$this->session->flashdata('club_updated')."','success')</script>";
    }
?>
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
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('online_assessments'); ?>
        </h3>
    </div>
</div>

<form action="<?php echo base_url().'assessment/view_assessment' ?>" method="POST" data-step="2" data-position='top' data-intro="Please select the filters and press Filter button to get specific assessments records">
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
                        <?php echo get_teacher_dep_class_section_list($teacher_section, $section); ?>
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
	    <div class="table-responsive">
	    <table class="table table-striped table-bordered table_export  table-responsive" data-step="3" data-position='top' data-intro="assessment records">
        	<thead>
        		<tr>
            		<th style="width:50px;"><div><?php echo get_phrase('s_no');?></div></th>
            		<th class="assessment_th_mobile"><div><?php echo get_phrase('Assessment Details');?></div></th>
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
                	<td style="padding: 0px 0px;">
                            <div class="member-entry" style="margin-top:0px;margin-bottom:0px;"> 
                                <a> 
                                   <i class="fas fa-file-alt" style="font-size:90px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
                                </a> 
                                <div class="member-details"> 
                                    <h4> 
                                        <a href="#"><?php echo $row['name'];?> </a> 
                                    </h4> 
                                    <div class="row info-list p-0" style="color: black;"> 
                                        <div class="col-sm-4 col-xs-4"> 
                                            <strong>Title:</strong>
                                            <?php echo ucfirst($row['assessment_title']);?>
                                            <br>
                                            <strong>Teacher:</strong>
                                            <?php echo get_teacher_name($row['teacher_id']);?>
                                            <br>
                                            <strong>Yearly Terms:</strong>
                                            <?php
                                                $yearly_terms =  get_yearly_terms($_SESSION['academic_year_id'] , $row['yearly_term_id'] );
                                                echo $yearly_terms[0]['title'];
                                            ?>
                                            <br>
                                            <?php
                                            	if($row['assessment_type'] == 1)
                                            	{
                                            	    echo '<button class="btnChangeQuestionBox" type="button">Manual Assessment</button>';
                                            	}else if($row['assessment_type'] == 2){
                                            	    echo '<button class="btnChangeQuestionBox" type="button">Question Bank Assessment</button>';
                                            	}
                                        	?>
                                        </div> 
                                        <div class="col-sm-4 col-xs-4"> 
                                            <strong>Total Marks:</strong>
                                            <?php echo $row['total_marks']; ?>
                                            <br>
                                            <strong>Allowed No Of Attempts:</strong>
                                            <?php echo $row['total_attempts']; ?>
                                            <br>
                                            <?php
                                            	if($row['is_completed'] == 1 && $row['is_assigned'] == 1)
                                            	{
                                            	    echo get_assessment_time($row['assessment_id']);
                                            	}else{
                                            	    echo "<strong>Status :</strong><span style='color:red;'>Not Assigned</span>";
                                            	}
                                        	?>
                                            <br>
                                        	
                                        </div> 
                                        <div class="col-sm-4 col-md-4 col-xs-4 col-lg-3"> 
                                            <?php 
                                                if($row['mcq_questions'] > 0 ){
                                                    echo "<div class='mcq_info'><strong class='min-space'>MCQ's : </strong><span class='badge badge-info badge-roundless pull-right no_q'>".$row['mcq_questions']."</span></div>";
                                                }
                                                if($row['true_false_questions'] > 0 ){
                                                    echo "<div class='mcq_info'><strong class='min-space'>True/False : </strong><span class='badge badge-success badge-roundless pull-right no_q'>".$row['true_false_questions']."</span></div>";
                                                }
                                                if($row['fill_in_the_blanks_questions'] > 0 ){ 
                                                    echo "<div class='mcq_info'><strong class='min-space'>Blanks : </strong><span class='badge badge-info badge-roundless pull-right no_q'>".$row['fill_in_the_blanks_questions']."</span></div>";
                                                }
                                                if($row['short_questions'] > 0 ){   
                                                    echo "<div class='mcq_info'><strong class='min-space'>Short Q's : </strong><span class='badge badge-success badge-roundless pull-right no_q'>".$row['short_questions']."</span></div>";
                                                }
                                                if($row['long_questions'] > 0 ){   
                                                    echo "<div class='mcq_info'><strong class='min-space'>Long Q's </strong><span class='badge badge-info badge-roundless pull-right no_q'>".$row['long_questions']."</span></div>";
                                                }
                                                if($row['pictorial_questions'] > 0 ){   
                                                    echo "<div class='mcq_info'><strong class='min-space'>Pictorial Q's</strong><span class='badge badge-success badge-roundless pull-right no_q'>".$row['pictorial_questions']."</span></div>";
                                                }
                                                if($row['match_questions'] > 0 ){   
                                                    echo "<div class='mcq_info'><strong class='min-space'>Column Matching Q's</strong><span class='badge badge-info badge-roundless pull-right no_q'>".$row['match_questions']."</span></div>";
                                                }
                                                if($row['drawing_questions'] > 0 ){   
                                                    echo "<div class='mcq_info'><strong class='min-space'>Drawing Q's</strong><span class='badge badge-success badge-roundless pull-right no_q'>".$row['drawing_questions']."</span></div>";
                                                }
                                            ?>
                                        </div> 
                                        <div class="clear"></div> 
                                        <div class="col-sm-12 col-xs-12 ststs-asesmnt"> 
                                        <?php 
                                            if($row['is_completed'] == 0){
                                                if($row['assessment_type'] == 1) {
                                        ?>
                                            <a data-step="4" data-position='top' data-intro="click this button to add assessment details" class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/assessment_details/".$row['assessment_id'];?>" target="_blank">Add Questions</a> &nbsp;&nbsp;
                                        <?php }else if($row['assessment_type'] == 2){ ?>
                                            <a data-step="4" data-position='top' data-intro="click this button to add assessment details" class="btn btn-primary btn-sm" href="<?php echo base_url()."question_bank/assessment_details/".$row['assessment_id'];?>" target="_blank">Add Questions</a> &nbsp;&nbsp;
                                        <?php } ?>
        							        <a data-step="5"  style="background: #fda119 !important;border: 1px solid orange !important;" data-position='top' data-intro="click this button to edit assessment details" class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/edit/".$row['assessment_id'];?>">Edit No Of Questions</a> &nbsp;&nbsp;
        							        <a data-step="6"  style="background: #fda119 !important;border: 1px solid orange !important;" data-position='top' data-intro="click this button to delete assessment" class="btn btn-primary btn-sm" onclick="confirm_modal('<?php echo base_url();?>assessment/delete_assessment/<?php echo $row['assessment_id']; ?>');" href="#">Delete</a> &nbsp;&nbsp;
        							        
        							    <?php }else { ?>
        							        <a data-step="7" data-position='top' data-intro="click this button to view assessment details" class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/view_assessment_form/".$row['assessment_id'];?>" target="_blank">View Details</a> &nbsp;&nbsp;
        							    <?php } if($row['is_completed'] == 1){ ?>
        							        <a data-step="8" data-position='top' data-intro="click this button assign assessment to students" class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/assign_assessment/".$row['assessment_id'];?>" target="_blank">Assign</a> &nbsp;&nbsp;
        							    <?php }if($row['is_completed'] == 1 && $row['is_assigned'] == 0){ ?>
            							    <a style="background: #fda119 !important;border: 1px solid orange !important;" data-step="9" data-position='top' data-intro="click this button assign assessment to students" class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/edit_assessment_details/".$row['assessment_id'];?>" target="_blank">Edit Details</a> &nbsp;&nbsp;
            							<?php }if($row['is_completed'] == 1 && $row['is_assigned'] == 1){ ?>
            							<a class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/view_assessments_submitted/".$row['assessment_id'];?>" target="_blank">View Answer Sheets</a> &nbsp;&nbsp;
                                        <?php } ?>
                                        </div>
                                    </div> 
                                </div> 
                            </div>
                        </td>
                	
				</tr>
         <?php endforeach;?>
         </tbody>
        </table>
        </div>
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
                data: { section_id: section_id },
                url: "<?php echo base_url();?>teacher/get_section_student_subject",
                dataType: "html",
                success: function(response) 
                {
                    var obj = jQuery.parseJSON(response);
                    $("#icon").remove();
                    $("#subject_list").html(obj.subject);
                }
            });
    
        });
        
        var datatable_btn_url   =  '<?php echo base_url();?>assessment/create_assessment';
        var datatable_btn_url_1 =  '<?php echo base_url();?>question_bank/create_question_bank';
        var datatable_btn       =  "<a href="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new assessment' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('create_manual_assessment');?></a><a href="+datatable_btn_url_1+" data-step='1' data-position='left' data-intro='Press this button to add new assessment' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('create_QB_assessment');?></a>";    


</script>