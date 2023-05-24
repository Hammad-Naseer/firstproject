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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h4 class="system_name inline">
          <?php echo get_phrase('submitted_assessments');?>
        </h4>
        <h4 class="text-center">
            <?php
                if(count($students) > 0){
                  echo "Assessment Title : ". $students[0]['assessment_title'];
                  $yearly_terms =  get_yearly_terms($_SESSION['academic_year_id'] , $students[0]['yearly_term_id'] );
                  echo "<br>";
                  echo "Yearly Term : ".$yearly_terms[0]['title'];
                }
            ?>
        </h4>
    </div> 
</div>

<div class="row">
	<div class="col-md-12">
    	<div class="tab-content">            
            <div class="tab-pane box active" id="list">
			    <table class="table table-bordered table_export" id="teacher_diary_datatable">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('s_no');?></div></th>
                           	<th><div><?php echo get_phrase('student_info');?></div></th>
                           	<th><div><?php echo get_phrase('roll_number');?></div></th>
                           	<th><div><?php echo get_phrase('date_submitted');?></div></th>
                           	<th><div><?php echo get_phrase('total_marks');?></div></th>
                           	<th><div><?php echo get_phrase('obtained_marks');?></div></th>
                           	<th><div><?php echo get_phrase('grade');?></div></th>

                            <th><div><?php echo get_phrase('action');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php 
                    	$j=0;
                    	foreach($students as $row):
                    	    
                    	$j++;
                    	$obtained_marks = '';
                    	$result_row = get_assessment_result_row($assessment_id , $row['student_id']);
                    	if($result_row != null){
                    	    $obtained_marks = $result_row->obtained_marks;
                    	}
                    	?>
                        <tr>
                        	<td><?php echo $j; ?></td>
    
							<td>
							    <?php 
							         $section_details = section_hierarchy($row['section_id']);
							         echo $row['student_name'] . '<br>' . 
							              $section_details['d'] . ' | ' . $section_details['c'] . ' | ' . $section_details['s']; 
							    ?>
							 </td>
							<td><?php echo $row['roll_number'];?></td>
							<td>
							    <?php 
							       if($row['is_submitted'] == 1){
							               echo date_view(get_submitted_assessment_date( $assessment_id , $row['section_id'] , $row['student_id'] ));
							       }else{
							           echo "-";
							       }
							    ?>
							</td>
							<td><?php echo $row['total_marks'];?></td>
							<td>
							    <?php 
							       if($row['is_submitted'] == 1){
							             echo $obtained_marks;
							       }else{
							           echo "-";
							       }
							    ?>
							 </td>
							<td><?php echo $row['grade_name']." - ".$row['comment'];?></td>
							<td>
							    <?php 
							       if($result_row != null){
							          echo '<span class="text-center text-success">Checked</span>'; 
							       }
							       else
							       {
							           if($row['is_submitted'] == 1){
							    ?>
							               <a class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/student_assessment_details/".$row['student_id']."/".$assessment_id;?>" target="_blank">View</a>
							    <?php
							           }
							           else
							           {
							               echo '<span class="text-center text-danger">Not Submitted</span>';
							           }
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