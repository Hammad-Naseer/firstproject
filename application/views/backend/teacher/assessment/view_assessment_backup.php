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
$( window ).load(function() 
{
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});

</script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
          <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/diary.png">  <?php echo get_phrase('assessments');?>
        </h3>   
        <a href="<?php echo base_url();?>assessment/create_assessment" class="btn btn-primary pull-right">
                <i class="entypo-plus-circled"></i>
                <?php echo get_phrase('create_assessment');?>                
        </a> 
    </div> 
</div>

<div class="row">
	<div class="col-md-12">
    	<div class="tab-content">            
            <div class="tab-pane box active" id="list">
			    <table class="table table-bordered" id="teacher_diary_datatable">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('s_no');?></div></th>
                    		<th style="width:250px;"><div><?php echo get_phrase('Assessment Details');?></div></th>
                           	<th><div><?php echo get_phrase('no_of_mcq');?></div></th>
                           	<th><div><?php echo get_phrase('no_of_true/false');?></div></th>
                           	<th><div><?php echo get_phrase('no_of_blanks');?></div></th>
                           	<th><div><?php echo get_phrase('no_of_short_questions');?></div></th>
                           	<th><div><?php echo get_phrase('no_of_long_questions');?></div></th>
                           	<th><div><?php echo get_phrase('no_of_pictorial_questions');?></div></th>
                           	
                           	
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
                        	<td><?php echo $j; ?></td>
                        	<td>
                        	    <strong>Title :</strong>
                        	    <?php echo $row['assessment_title'];?>
                        	    <br>
                        	    <strong>Date :</strong>
                        	    <?php echo date('d-M-Y',strtotime($row['inserted_at']));?>
                        	    <br>
                        	    <strong>Yearly Term :</strong>
                        	    <?php
                        	        $yearly_terms =  get_yearly_terms($_SESSION['academic_year_id'] , $row['yearly_term_id'] );
                        	        echo $yearly_terms[0]['title'];
                        	    ?>
                        	    <br>
                        	    <strong>Total Marks :</strong>
                        	    <?php echo $row['total_marks'];?>
                        	</td>
							<td><?php echo $row['mcq_questions'];?></td>
							<td><?php echo $row['true_false_questions'];?></td>
							<td><?php echo $row['fill_in_the_blanks_questions'];?></td>
							<td><?php echo $row['short_questions'];?></td>
							<td><?php echo $row['long_questions'];?></td>
							<td><?php echo $row['pictorial_questions'];?></td>
							<td>
							    <?php
							      if($row['is_completed'] == 0){
							    ?>
							       <a class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/assessment_details/".$row['assessment_id'];?>" target="_blank">View</a>
							    <?php
							      }
							      else{
							    ?>
							       <a class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/view_assessment_form/".$row['assessment_id'];?>" target="_blank">View</a>
							    <?php
							      }
							    ?>
							    
							</td>
							<td>
							    <?php
							      if($row['is_completed'] == 1){
							    ?>
							      <a class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/assign_assessment/".$row['assessment_id'];?>" target="_blank">Assign</a> 
							    <?php
							      }
							    ?>
							    
							</td>
							
							<td>
							    <?php
							      $actual_date = date('Y-m-d',strtotime($row['assessment_date']));
							      if($row['end_time'] != ''){
							          $end_t = date("H:i", (strtotime($row['end_time'])-(60)));
							      }
							      else
							      {
							          $end_t = '';
							      }
							      
	                              $current_t = date("H:i", strtotime("now"));
							      if( $row['is_completed'] == 1 && $actual_date == date('Y-m-d')){
	                                  if($current_t > $end_t && $end_t != '')
	                                  {
							    ?>
							            <a class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/view_assessments_submitted/".$row['assessment_id'];?>" target="_blank">View</a> 
							    <?php
	                                  }
							      }
							      if( $row['is_completed'] == 1 && date('Y-m-d') > $actual_date  ){
							    ?>
							            <a class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/view_assessments_submitted/".$row['assessment_id'];?>" target="_blank">View</a> 
							    <?php
							      }
							    ?>
							    
							</td>
                            
                            
        				</tr>
                 <?php endforeach;?>
                 </tbody>
                </table>               
			</div>
            
		
		
		</div>
	</div>
</div>




<style>
di

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
