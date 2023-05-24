<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
		<h3 class="system_name inline">
		  <!--  <i class="entypo-right-circled carrow">
		    </i>-->
		<img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/marks.png"><?php echo get_phrase('staff_evaluation_view'); ?>
		</h3>
	</div>
</div>

<?php
$staff_eval_id=str_decode($this->uri->segment(5));
$staff_id=str_decode($this->uri->segment(6));
$start_date=str_decode($this->uri->segment(7));
$end_date=str_decode($this->uri->segment(8));
$evaluation_date=str_decode($this->uri->segment(4));
if(isset($evaluation_date) && ($evaluation_date > 0))
{
	$staf_eval_query="SELECT * FROM ".get_school_db().".staff_evaluation WHERE staff_id=".$staff_id." AND evaluation_date='".$evaluation_date."' AND school_id=".$_SESSION['school_id']."";
$staf_eval_edit=$this->db->query($staf_eval_query)->result_array();
}

if($staf_eval_edit[0]['staff_eval_id']!="")
{
	$staff_ans_query="SELECT * FROM ".get_school_db().".staff_evaluation_answers WHERE staff_eval_id=".$staff_eval_id." AND school_id=".$_SESSION['school_id']."";
$staff_ans_edit=$this->db->query($staff_ans_query)->result_array();
}

foreach($staff_ans_edit as $staf_ans)
{
	$eval_response[$staf_ans['staff_eval_form_id']]['remarks']=$staf_ans['remarks'];
	$eval_response[$staf_ans['staff_eval_form_id']]['answer']=$staf_ans['answers'];
	
}

$eval="SELECT * FROM ".get_school_db().".staff_evaluation_questions WHERE status=1 AND school_id=".$_SESSION['school_id']." ";
 $eval_staff=$this->db->query($eval)->result_array();
 
 $qry = "SELECT * FROM ".get_school_db().".staff WHERE
school_id=".$_SESSION['school_id']." AND staff_id=".$staff_id;
$teacher=$this->db->query($qry)->result_array();


?>








<div class="row"><div class="col-sm-12">                
                    
<!--<a href="<?php echo base_url();?>staff_evaluation/evaluation/<?php echo $staff_id;?>/<?php echo $start_date;?>/<?php echo $end_date;?>" class="btn btn-primary pull-right"><?php echo get_phrase('back'); ?></a>-->
<br />
</div></div> 



<div class=" thisrow">
<div class="row">
<div class="col-lg-2 col-md-2 col-sm-2 text-center">
<?php
	$pic = display_link($teacher[0]['staff_image'],'staff');
?>
	<img src="<?php  if($teacher[0]['staff_image']!=''){
					echo $pic;

				}
				else 
				{
					echo base_url().'uploads/default.png';
				} ?>" alt="Teacher Image" width="100" />
</div>
<div class="col-lg-10 col-md-10 col-sm-10">
		
	<p><strong><?php echo get_phrase('name'); ?>:</strong> 
				<?php 
	 echo $teacher[0]['name']; ?></p>
			
	<p><strong><?php echo get_phrase('evaluation_date'); ?> :</strong>  <?php echo convert_date($evaluation_date); ?></p> 
	
	<p><strong><?php echo get_phrase('ratings'); ?> :</strong>  <?php echo get_evaluation_rating_by_id($staf_eval_edit[0]['answers'])->detail; ?></p>

	<p><strong><?php echo get_phrase('remarks'); ?>:</strong>  <?php echo  $staf_eval_edit[0]['remarks'];
		$attachment=$staf_eval_edit[0]['attachment'];
		if($attachment=="")
		{
			
		}
		else
		{?></p>
            <p><strong><?php echo get_phrase('attachment'); ?>:</strong>  <a target="_blank" href="<?php echo display_link($attachment,'staff_evaluation');?>"><span class="glyphicon glyphicon-download-alt"></span></a></p>
   <?php }	
                ?>
                
                <?php
                    $is_teacher = $this->db->query("select is_teacher from " . get_school_db() . ".designation WHERE designation_id = ".$teacher[0]['designation_id']." AND school_id =" . $_SESSION['school_id']."")->row();
                    if($is_teacher->is_teacher){
                 
                        $my_rating = $this->db->query("select AVG(rating) as average_rating from " . get_school_db() . ".teacher_rating WHERE teacher_id = ".$teacher[0]['staff_id']." AND school_id =" . $_SESSION['school_id']."")->row();
                        if(count($my_rating)>0){
                        ?>
                            
                            <div class="avg">
                                <div class='rating-stars' style="font-size: 7px;">
                                    <?php
                                        $style_avg = 0;
                                        if($my_rating->average_rating > 0 And $my_rating->average_rating < 1)
                                            $style_avg = $my_rating->average_rating*100;
                                        if($my_rating->average_rating > 1 And $my_rating->average_rating < 2)
                                            $style_avg = ($my_rating->average_rating - 1)*100;
                                        if($my_rating->average_rating > 2 And $my_rating->average_rating < 3)
                                            $style_avg = ($my_rating->average_rating - 2)*100;
                                        if($my_rating->average_rating > 3 And $my_rating->average_rating < 4)
                                            $style_avg = ($my_rating->average_rating - 3)*100;
                                        if($my_rating->average_rating > 4 And $my_rating->average_rating < 5)
                                            $style_avg = ($my_rating->average_rating - 4)*100;
                                        $style_avg = 100 - $style_avg;
                                        $style = 'background: -webkit-linear-gradient(180deg, #ccc '.$style_avg.'%, #FF912C 0%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;';
                                    ?>
                                    <ul id='stars'>
                                        <li class='star-item <?php echo ($my_rating->average_rating>0)?'selected':'';?>' title='Poor'>
                                            <i class='fa fa-star fa-fw' style="<?php echo ($my_rating->average_rating>0 And $my_rating->average_rating < 1)?$style:'';?>padding-right:0px;"></i>
                                        </li>
                                        <li class='star-item <?php echo ($my_rating->average_rating>1)?'selected':'';?>' title='Fair'>
                                            <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>1 And $my_rating->average_rating < 2)?$style:'';?> padding-right:0px;"></i>
                                        </li>
                                        <li class='star-item <?php echo ($my_rating->average_rating>2)?'selected':'';?>' title='Good'>
                                            <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>2 And $my_rating->average_rating < 3)?$style:'';?>padding-right:0px;"></i>
                                        </li>
                                        <li class='star-item <?php echo ($my_rating->average_rating>3)?'selected':'';?>' title='Excellent'>
                                            <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>3 And $my_rating->average_rating < 4)?$style:'';?>padding-right:0px;"></i>
                                        </li>
                                        <li class='star-item <?php echo ($my_rating->average_rating>4)?'selected':'';?>' title='Awesome'>
                                            <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>4 And $my_rating->average_rating < 5)?$style:'';?>padding-right:0px;"></i>
                                        </li>
                                    </ul>
                                </div>
                                Average Rating:  <strong><?php echo round($my_rating->average_rating, 2)?></strong>
                            </div>
                    <?php }
                        }?>
</div>
	


</div>

</div>




<table class="table table-striped table-bordered" id="admin_ajax_get_staff"  >
                	<thead>
                		<tr>
                    		<th style=" width:54px !important;">#</th>
                    		<th><?php echo get_phrase('Details');?></th>
                    		
                    		
                    		
                    		
                    		
						</tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;
                    	
                  if(count($eval_staff)>0){
					foreach($eval_staff as $row){
                    	?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td>
    							<div class="myttl"><?php echo $row['title'];?></div>
    							
    							<div><strong><?php echo get_phrase('rating'); ?>:</strong>
    								<?php echo get_evaluation_rating_by_id($eval_response[$row['staff_eval_form_id']]['answer'])->detail;?>
    							</div>
    							<div><strong><?php echo get_phrase('remarks'); ?>:</strong><?php echo $eval_response[$row['staff_eval_form_id']]['remarks'];?></div>
							</td>
                        </tr>
                        <?php } 
                    } ?>
                    </tbody>
                </table>
                
  