<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <h3><?php echo get_phrase('student_evaluation');?></h3>
        <!--<a href="<?php echo base_url();?>student_evaluation/stud_eval/<?php echo $exam_id;?>/<?php echo $section_id;?>" class="btn btn-primary"><?php echo get_phrase('back');?></a>-->
    </div>
</div>
<div class="box-content">
<?php


$stud_eval_query="SELECT * FROM ".get_school_db().".student_evaluation WHERE student_id=".$student_id." AND exam_id=".$exam_id." AND school_id=".$_SESSION['school_id']." And evaluated_by = 1 And who_evaluated = ".$_SESSION['user_id'];
$stud_eval_edit=$this->db->query($stud_eval_query)->result_array();

$url = "";
$url = base_url('student_evaluation/stud_eval/create');
if($stud_eval_edit[0]['stud_eval_id']!="")
{
$stud_ans_query="SELECT * FROM ".get_school_db().".student_evaluation_answers WHERE stud_eval_id=".$stud_eval_edit[0]['stud_eval_id']."  AND school_id=".$_SESSION['school_id']."";
	$stud_ans_edit=$this->db->query($stud_ans_query)->result_array();
	$url= base_url('student_evaluation/stud_eval/do_update');
}


$eval_response=array();
foreach($stud_ans_edit as $stud_ans)
{
	$eval_response[$stud_ans['eval_id']]['answer']=$stud_ans['answers'];
	$eval_response[$stud_ans['eval_id']]['remarks']=$stud_ans['remarks'];
	$eval_response[$stud_ans['eval_id']]['std_eval_ans_id']=$stud_ans['std_eval_ans_id'];
}
//print_r($eval_response);

$res_array=array();
$misc_set="SELECT * FROM ".get_school_db().".evaluation_ratings WHERE status=1 AND type='stud_eval' AND school_id=".$_SESSION['school_id']." ";
	$res=$this->db->query($misc_set)->result_array();
	if(isset($res[0]['detail']) && ($res[0]['detail']!=""))
	{
		$res_array=explode(",",$res[0]['detail']);
	}
?>

        <!-- echo form_open($url.'/'.$student_id.'/'.$exam_id, array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top')); -->

        <form action="<?php echo $url.'/'.$student_id.'/'.$exam_id.'/'.$section_id; ?>" method="post" class="form-horizontal form-groups-bordered validate" enctype="multipart/form-data">
            <input type="hidden" name="evaluated_by" value="1"/>
            <?php
$query="SELECT * FROM ".get_school_db().".student WHERE student_id=$student_id AND school_id=".$_SESSION['school_id']." AND student_status IN (".student_query_status().") ORDER BY roll desc";

$students=$this->db->query($query)->result_array();
foreach($students as $student)
{?>
                <?php $details=section_hierarchy($student['section_id']);?>
                <div class=" thisrow pd10">
                    <div class="row">
                        <div class="col-sm-6 myttl">
                            <?php echo $student['name']; ?><span style="font-size:12px;"> (<?php echo get_phrase('roll');?>#: <?php echo $student['roll']; ?>)</span></div>
                        <div class="col-sm-6" style="padding-top: 2px;color: #0a73b7;padding-left: 0px;">
                            <strong><?php echo get_phrase('class');?>/<?php echo get_phrase('section');?>:  </strong>
                            <?php echo $details['c'];?>/
                            <?php echo $details['s'];?>
                        </div>
                    </div>
                    <?php
 }
   ?>
                        <div class="row">
                            <div class="col-sm-12" style="padding-top: 2px;color: #0a73b7;">
                                <?php
                                    if($exam != '0' And $type != '2'){
                                        $query_exam="SELECT * FROM ".get_school_db().".exam WHERE exam_id=$exam_id AND school_id=".$_SESSION['school_id']." ";
                                        $quer_exam= $this->db->query($query_exam)->result_array();
                                        echo "<strong>Exam:  </strong>".$quer_exam[0]['name']." (".date('d-M-Y',strtotime($quer_exam[0]['start_date']))." to ".date('d-M-Y',strtotime($quer_exam[0]['end_date'])).")"."<br/>"; 
                                    }
                                    else{
                                        echo 'General Evaluation';
                                    }
                                ?>
                            </div>
                        </div>
                </div>
                <?php
				$eval="SELECT * FROM ".get_school_db().".student_evaluation_questions WHERE status=1 AND type = '".$type."' AND school_id=".$_SESSION['school_id']." ";
				$eval_std=$this->db->query($eval)->result_array();
				if(count($eval_std)>0)
				{
				 ?>
                    <table class="table table-bordered datatable">
                        <thead>
                            <tr>
                                <th style="width:30px;">
                                    <div>
                                        <?php echo get_phrase('#');?>
                                    </div>
                                </th>
                                <th>
                                    <div>
                                        <?php echo get_phrase('questions');?>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <?php
						 $count=1;
						 
						 foreach($eval_std as $row)
						 { ?>
                            <tr>
                                <td>
                                    <?php echo $count ?>
                                </td>
                                <td class="myttl">
                                    <input type="hidden" name="evaluation[<?php echo $count;?>][eval_id]" value="<?php echo $row['eval_id'];?>">
                                    <?php echo $row['title']; ?>
                                    <input type="hidden" name="std_eval_array[<?php echo $count;?>][std_eval_id]" value="<?php echo $eval_response[$row['eval_id']]['std_eval_ans_id'];?>">
                                    <select name="evaluation[<?php echo $count;?>][answers_select]" class="form-control  mgt10 required-field" required>
                                        <option value="">
                                        <?php echo get_phrase('select_rating');?>
                                        </option>
                                        <?php 
                                        $misc_set="SELECT * FROM ".get_school_db().".evaluation_ratings WHERE status=1 AND type='stud_eval' AND school_id=".$_SESSION['school_id']." ";
										$res=$this->db->query($misc_set)->result_array();
										foreach($res as $val){
											$opt_selected="";
											if(isset($eval_response[$row['eval_id']]['answer']) && ($eval_response[$row['eval_id']]['answer'] == $val['misc_id'])){
												$opt_selected="selected";
											}
											echo '<option value="'.$val['misc_id'].'" '.$opt_selected.'>'.$val['detail'].'</option>';
										}					
										?>
                                    </select>
                                    <textarea  placeholder="Remarks" required name="evaluation[<?php echo $count ?>][response]" id="evaluation[<?php echo $count;?>][response]" class="form-control mgt10 required-field" maxlength="250" oninput="count_value('evaluation[<?php echo $count;?>][response]','address_count<?php echo $count;?>','250')"><?php echo $eval_response[$row['eval_id']]['remarks'];?></textarea>
                 <div id="address_count<?php echo $count; ?>" class="col-sm-12 "></div>                   
                                </td>
                            </tr>
                            <?php
 	$count++;
 }
 
?>
                    </table>
                    <?php
}
?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="2">
                                        <div>
                                            <?php echo get_phrase('Administrator remarks');?>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tr>
                                <td>
                                    <?php echo get_phrase('ratings');?><span class="red"> * </span>
                                </td>
                                <td>
                                    <?php 
                                    echo '<select name="teacher_answers" class="form-control required-field" data-validate="required" data-message-required="Value Required" required><option value="">Select</option>';
					            		foreach($res as $val){
											$opt_selected="";
											if(isset($eval_response[$row['eval_id']]['answer']) && ($eval_response[$row['eval_id']]['answer'] == $val['misc_id'])){
												$opt_selected="selected";
											}
											echo '<option value="'.$val['misc_id'].'" '.$opt_selected.'>'.$val['detail'].'</option>';
										}
								 	echo '</select>';
					            	
					            	?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo get_phrase('remarks');?>
                                </td>
                                <td>
                                    <textarea name="remarks" id="remarks" maxlength="500" oninput="count_value('remarks','remarks_count','500')" rows="5" class="form-control required-field" required><?php echo trim($stud_eval_edit[0]['remarks']);?></textarea>
                             <div id="remarks_count" class="col-sm-12 "></div>       
                 
                                </td>
                                <input type="hidden" name="stud_eval_id" value="<?php echo $stud_eval_edit[0]['stud_eval_id'];?>">
                            </tr>
            				<tr>
          						<td><?php echo get_phrase('Attachment');?></td>
            					<td>
        		<input value="" type="file" class="form-control" name="image1" id="image1" onchange="file_validate('image1','doc','img_g_msg')">
<span style="color: green;"><?php echo get_phrase('allowed_file_size');?>:  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx</span>
                   <br />
<span style="color: red;" id="img_g_msg"></span>
<br />
            						<span id="id_file">	
<?php
	$attachment=$stud_eval_edit[0]['attachment'];	
 $val_im=display_link($attachment,'student_evaluation',0,0); 
 if($val_im!=""){
 ?>	
 <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><?php echo get_phrase('form_attachment');?></a>

<a onclick="delete_files('<?php echo $attachment; ?>','student_evaluation','stud_eval_id','<?php echo $stud_eval_edit[0]['stud_eval_id']; ?>','attachment','student_evaluation','id_file',2)" href="#" class="img-responsive img-thumbnail"><?php echo get_phrase('delete_attachment');?></a>



<?php
}
 ?>
</span> 	
            					</td>	
            				</tr>
                        </table>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12 mgt10">
                                <input type="submit" id="select" class="btn btn-primary" value="<?php echo get_phrase('save');?>" />
                            </div>
                        </div>
</form>
</div>



<script>
    $("#select").click(function (e) {
        var required_miss = false;
        $('.required-message').remove();
         $('.required-field').each(function () {
             if($(this).val() == ''){
                required_miss = true;
                $(this).after("<b class='required-message' style='color: red;font-size: 13px;font-weight: 500;'>Value Required</b>");
             }
        });
        if(required_miss)
            e.preventDefault(); 
    });
</script>

