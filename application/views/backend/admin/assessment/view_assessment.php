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

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('online_assessments');?>
        </h3>
    </div> 
</div>

    <form action="<?php echo base_url().'adm_assessment/view_assessment' ?>" method="POST">
        <div>
            <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific assessments records">
                <div class="col-md-4">
                   <div class="form-group">
                      <label for="yearly_term_id">Select Yearly Term</label>
                      <span style="color:red;float: right;" id="yearly_term_id_span"></span>
                      <select  class="form-control" name="yearly_term_id" id = "yearly_term_id" >
                         <?php echo yearly_terms_option_list($_SESSION['academic_year_id'] , $yearly_term_id); ?>
                      </select>
                    </div>
                </div>
                <div class="col-md-4">
                   <div class="form-group">
                      <label for="teacher_id">Select Teacher</label>
                      <span style="color:red;float: right;" id="yearly_term_id_span"></span>
                      <select  class="form-control" name="teacher_id" id = "teacher_id" >
                        <?php echo teacher_designation_option_list($teacher_id);?>
                      </select>
                    </div>
                </div>
                <div class="col-md-4">
                   <div class="form-group">
                        <label>Select Section</label>
                        <span style="float:right; color:red;" id="section_id_span"></span>
                        <select id="section_id" class="dcs_list_add form-control" name="section_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <?php 
                                echo section_selector();
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Select Subject</label>
                        <span style="float:right; color:red;" id="subject_id_span"></span>
                        
                        <select name="subject_id" id="subject_id" class="form-control">
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" style="margin-top: 30px;">
                        <input type="hidden" name="apply_filter" value="1">
                       <button type="submit" class="btn btn-primary"> Filter</button>
                       <?php if ($apply_filter == 1){?>
                          <a href="<?php echo base_url(); ?>adm_assessment/view_assessment" class="btn btn-danger"><i class="fa fa-remove"></i> Remove</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </form>



<!--<div class="row">-->
	<div class="col-md-12">
    	<div class="tab-content">            
            <div class="tab-pane box active" id="list">
			    <table class="table table-bordered table_export" data-step="6" data-position="top" data-intro="assessment records">
                	<thead>
                		<tr>
                    		<th style="width:50px;"><div><?php echo get_phrase('s_no');?></div></th>
                    		<th><div><?php echo get_phrase('Assessment Details');?></div></th>
                           	<th style="width:150px;"><div><?php echo get_phrase('question_details');?></div></th>
                           	
                            <th style="width:150px;"><div><?php echo get_phrase('action');?></div></th>
                            
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
                        	<td>
                        	    <strong>Title :</strong>
                        	    <?php echo $row['assessment_title'];?>
                        	    <br>
                        	    <strong>Yearly Term :</strong>
                        	    <?php
                        	        $yearly_terms =  get_yearly_terms($_SESSION['academic_year_id'] , $row['yearly_term_id'] );
                        	        echo $yearly_terms[0]['title'];
                        	    ?>
                        	    <br>
                        	    <strong>Total Marks :</strong>
                        	    <?php echo $row['total_marks'];?> <br>
                        	    <?php echo get_assessment_time($row['assessment_id']); ?>
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
							    <a data-step="7" data-position="left" data-intro="click this button view assessment details" class="btn btn-primary btn-sm" href="<?php echo base_url()."adm_assessment/view_assessment_details/".$row['assessment_id'];?>" target="_blank">View Details</a>
							</td>
                            
                            
        				</tr>
                 <?php endforeach;?>
                 </tbody>
                </table>               
			</div>
            
		
		
		</div>
	</div>
<!--</div>-->

<script>
        
        $("#section_id").val('<?=$section_id?>');
        $("#teacher_id").val('<?=$teacher_id?>');
        
        $("#section_id").change(function() {
            var section_id = $(this).val();

            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');

            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>academic_planner/get_section_subject",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();

                    $("#subject_id").html(response);


                }
            });



        });
        
</script>



<style>

.loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #63b7e7; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
    margin-right: auto;
    margin-left: auto;
}

.loader_small {
       border: 7px solid #f3f3f3;
    border-top: 7px solid #63b7e7;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    margin-right: auto;
    margin-left: auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}



</style>
