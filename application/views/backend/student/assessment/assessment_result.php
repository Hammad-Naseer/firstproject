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
    .self_class{
        float: right;
        background-color: #3498c6;
        padding: 10px 5px 0px 5px;
        border-radius: 5px;
    }
    .text-color{
        color:white;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('assessment_result');?>
        </h3>
    </div> 
</div>

<!--<div class="row">-->
	<div class="col-md-12">
    	<div class="tab-content">            
            <div class="tab-pane box active" id="list">
			    <table class="table table-bordered table-responsive export_export" id="teacher_diary_datatable" data-step="1" data-position='top' data-intro="Assessment results record">
                	<thead>
                		<tr>
                    		<th style="width:250px;"><div><?php echo get_phrase('Assessment Details');?></div></th>
                           	<th><?php echo get_phrase('total_marks');?></th>
                           	<th><?php echo get_phrase('obtained_marks');?></th>
                           	<th><?php echo get_phrase('grade');?></th>
                           	<th><?php echo get_phrase('result_date');?></th>
                           	<th><?php echo get_phrase('result_details');?></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php 
                    	$j=$start_limit;
                    	
                    	$count = 1;
                    	
                    	if(count($result_arr) > 0)
                  
                        	foreach($result_arr as $row):
                        	$j++;
                        	$assessment_time_date = get_assessment_time_date($row['ass_id'] , $_SESSION['student_id']);
                        
                        	?>
                            <tr>
                            	<td>
                            	    <strong>Title :</strong>
                            	    <?php echo $row['assessment_title'];?>
                            	    <br>
                            	    <strong>Teacher Name :</strong>
                            	    <?php echo $row['teacher_name'];?>
                            	    <br>
                            	    <strong>Subject Name :</strong>
                            	    <?php echo $row['subject_name']."-".$row['subject_code'];?>
                            	    <br>
                            	    <strong>Date :</strong>
                            	    <?php echo date('d-M-Y',strtotime($assessment_time_date[0]['assessment_date']));?>
                            	    <br>
                            	    <strong>Start Time :</strong>
                            	    <?php echo $assessment_time_date[0]['start_time'];?>
                            	    <br>
                            	    <strong>End Time :</strong>
                            	    <?php echo $assessment_time_date[0]['end_time'];?>
                            	    <br>
                            	    <strong>Yearly Term :</strong>
                            	    <?php
                            	        $yearly_terms =  get_yearly_terms($_SESSION['academic_year_id'] , $row['yearly_term_id'] );
                            	        echo $yearly_terms[0]['title'];
                            	    ?>
                            	    <br>
                            	    <strong>Total Marks :</strong>
                            	    <?php echo $row['assessment_total_marks'];?>
                            	    
                            	</td>
    							<td><?php echo $row['assessment_total_marks'];?></td>
    							<?php if($row['assessment_result_id'] != "" && $row['assessment_result_id'] > 0){
    							?>
    							<td><?php echo $row['obtained_marks'];?></td>
    							<td>
    							    <?php
    							        if($row['grade_id'] != "" || $row['grade_id'] != NULL){
    							            $grade_details = get_assessment_grade($row['grade_id']);
    							            echo "Grade : ".$grade_details[0]['grade_point']."<br>";
    							            echo "Comment : ".$grade_details[0]['comment']."<br>";
    							            echo "Remarks : ".$row['result_remarks'];
    							        }       
    							    ?>
    							</td>
    							<td><?php echo  date('d-M-Y',strtotime($row['result_date']));?></td>
    							<?php }else{
    							    echo "<td class='td_middle text-danger' colspan='3'> Assessment is not marked Yet</td>";
    							}
    							?>
    							<td class="td_middle">
    							    <a data-step="2" data-position='top' data-intro="Click this button to view result sheet" class="btn btn-primary btn-sm" href="<?php echo base_url().'assessment/result_details/'.$row['ass_id']?>">
    							        View Details
    							    </a>
    							</td>
                                
                                
            				</tr>
                            <?php endforeach;
                        }
                        else
                        {
                        ?>
                            <tr>
                            	<td colspan="9" style="text-align: center;"> No Result Found</td>
                            </tr>
                        <?php
                        }
                        ?>
                 </tbody>
                </table>   
			</div>
		</div>
	</div>
<!--</div>-->
