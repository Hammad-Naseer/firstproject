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

<div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h4 class="system_name inline">
          <?php echo get_phrase('submitted_assessments');?>
        </h4>
        <h4 class="system_name text-center">
             <?php
                if(count($students) > 0){
                  echo "Assessment Title : ". $students[0]['assessment_title'];
                  $yearly_terms =  get_yearly_terms($_SESSION['academic_year_id'] , $students[0]['yearly_term_id'] );
                  echo "<br>";
                  echo "Yearly Term : ". $yearly_terms[0]['title'];
                }
             ?>
        </h4>
    </div> 



<div class="col-md-12">
    	<div class="tab-content">            
            <div class="tab-pane box active" id="list">
			    <table class="table table-bordered table-responsive" id="table_export">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('s_no');?></div></th>
                           	<th><div><?php echo get_phrase('student_info');?></div></th>
                           	<th><div><?php echo get_phrase('roll_number');?></div></th>
                           	<th><div><?php echo get_phrase('subject');?></div></th>
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
                        	<td class="td_middle"><?php echo $j; ?></td>
    
							<td>
							    <?php 
							         $section_details = section_hierarchy($row['section_id']);
							         echo $row['student_name'] . '<br>' . 
							              $section_details['d'] . ' | ' . $section_details['c'] . ' | ' . $section_details['s']; 
							    ?>
							 </td>
							<td><?php echo $row['roll_number'];?></td>
							<td><?php echo $row['subject_name'];?></td>
							<td>
							    <?php 
							       if($row['is_submitted'] == 1){
							               echo date_view(get_submitted_assessment_date( $assessment_id , $row['section_id'] , $row['student_id'] ));
							       } 
							    ?>
							</td>
							<td><?php echo $row['total_marks']; ?></td>
							<td><?php echo $obtained_marks;?></td>
							<td><?php echo $row['grade_name']; ?></td>
							<td class="td_middle">
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
