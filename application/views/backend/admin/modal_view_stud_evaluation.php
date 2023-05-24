<div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h3>
               
                <?php echo get_phrase('student_evaluation');?>
               
            </h3>
</div>
</div>
<?php
//$student_id=$param2;
//$exam_id=$param3;

$stud_eval_query="SELECT * FROM ".get_school_db().".student_evaluation WHERE student_id=".$student_id." AND exam_id=".$exam_id." AND subject_id = ".$subject_id." AND school_id=".$_SESSION['school_id']."";
$stud_eval_edit=$this->db->query($stud_eval_query)->result_array();
if($stud_eval_edit[0]['stud_eval_id']!="")
{
	 $stud_ans_query="SELECT * FROM ".get_school_db().".student_evaluation_answers WHERE stud_eval_id=".$stud_eval_edit[0]['stud_eval_id']."  AND school_id=".$_SESSION['school_id']."";
$stud_ans_edit=$this->db->query($stud_ans_query)->result_array();
}

$eval_response=array();
foreach($stud_ans_edit as $stud_ans)
{
	$eval_response[$stud_ans['eval_id']]['answer']=$stud_ans['answers'];
	$eval_response[$stud_ans['eval_id']]['remarks']=$stud_ans['remarks'];
	$eval_response[$stud_ans['eval_id']]['std_eval_ans_id']=$stud_ans['std_eval_ans_id'];
}

$res_array=array();
$misc_set="SELECT * FROM ".get_school_db().".evaluation_ratings WHERE status=1 AND type='stud_eval' AND school_id=".$_SESSION['school_id']." ";
	$res=$this->db->query($misc_set)->result_array();
	if(isset($res[0]['detail']) && ($res[0]['detail']!=""))
	{
		$res_array=explode(",",$res[0]['detail']);
	}
	
$query="SELECT * FROM ".get_school_db().".student WHERE student_id=$student_id AND school_id=".$_SESSION['school_id']." AND student_status IN (".student_query_status().") ORDER BY roll desc";

$students=$this->db->query($query)->result_array();
foreach($students as $student)
{ ?>











<?php $details=section_hierarchy($student['section_id']); ?>
<br/>
 <!--<a href="<?php echo base_url();?>student_evaluation/stud_eval/<?php echo $exam_id;?>/<?php echo $section_id;?>" class="btn btn-primary"> <?php echo get_phrase('back');?></a>    	-->
	         	
      <div class=" thisrow pd10">
      
      
      <div class="row">
      	<div class="col-sm-6 myttl"><?php echo $student['name']; ?><span style="font-size:12px;"> ( <?php echo get_phrase('roll');?>#:<?php echo $student['roll']; ?> )</span></div>
    
      	<div class="col-sm-6" style="    padding-top: 2px;
    color: #0a73b7;     padding-left: 0px;">
      	
      	
      	<strong> <?php echo get_phrase('class');?>/ <?php echo get_phrase('section');?>:  </strong> <?php echo $details['c']; ?>/<?php echo $details['s']; ?></div>
		
          </div>                   
	
  
 	

 	











 
 
 <?php }
 
 $query_exam="SELECT * FROM ".get_school_db().".exam WHERE exam_id=$exam_id AND school_id=".$_SESSION['school_id']." ";
$quer_exam= $this->db->query($query_exam)->result_array();

?>
  <div class="row">
 <div class="col-sm-12"  style="    padding-top: 2px;
    color: #0a73b7;">

<?php
if($exam != '0' And $type != '2'){
    echo "<strong>Exam </strong>".$quer_exam[0]['name']." (".date('d-M-Y',strtotime($quer_exam[0]['start_date']))." to ".date('d-M-Y',strtotime($quer_exam[0]['end_date'])).")"."<br/>";       
}
else{
    echo 'General Evaluation';
}

?>

 	
 </div>      
    </div>      
           
               
  </div>
  
<?php







$eval="SELECT * FROM ".get_school_db().".student_evaluation_questions WHERE status=1 AND type='".$type."' AND school_id=".$_SESSION['school_id']." ";
 $eval_std=$this->db->query($eval)->result_array();
 ?>
<table class="table table-striped table-bordered" id="admin_ajax_get_staff"  >
                	<thead>
                		<tr>
                    		<th style=" width:54px !important;">#</th>
                    		<th><?php echo get_phrase('questions');?></th>
                    		<th><?php echo get_phrase('Rating');?></th>
                    		<th><?php echo get_phrase('remarks');?></th>
                    		
                    		
                    		
                    		
						</tr>
					</thead>
                    <tbody>
<?php
if(count($eval_std)>0)
{
	$count=1;
 foreach($eval_std as $row)
 { ?>
 <tr>
               <td><?php echo $count++;?></td>
               <td><?php echo $row['title'];?></td>
               <td><?php echo get_evaluation_rating_by_id($eval_response[$row['eval_id']]['answer'])->detail;?></td>
               <td><?php echo $eval_response[$row['eval_id']]['remarks'];
                  ?>
               
               
               	
               </td>
               
 </tr>
                            
 <?php 
 }
}
 ?>
</tbody>
</table> 
<?php 
     echo "<strong>Ratings : ". get_evaluation_rating_by_id($stud_eval_edit[0]['answers'])->detail."</strong>";
     echo "<br>";
     echo "<strong>Remarks : </strong>".$stud_eval_edit[0]['remarks'];
      $attachment=$stud_eval_edit[0]['attachment'];
		if($attachment=="")
		{
			
		}
		else
		{?>
             <a target="_blank" href="<?php echo display_link($attachment,'student_evaluation');?>"><span class="glyphicon glyphicon-download-alt"></span></a>
   <?php }	
            
     ?>
