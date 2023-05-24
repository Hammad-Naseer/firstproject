<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <!--<a style="float:right;font-size:12px;color:white;" href="<?php echo base_url();?>staff_evaluation/evaluation/<?php echo $staff_id;?>/<?php echo $start_date;?>/<?php echo $end_date;?>" class="modal_save_btn"><?php echo get_phrase('back'); ?></a>-->
        <h3 class="system_name inline">
            <?php echo get_phrase('staff_evaluation'); ?>
        </h3>
    </div>
</div>

    <?php
        $evaluation_date="";
        $staff_eval_id=str_decode($this->uri->segment(3));
        $evaluation_date=str_decode($this->uri->segment(4));
        $staff_id=str_decode($this->uri->segment(6));
        if (empty($staff_id))
        {
        	$staff_id = 0;
        }
        $disable="";
        if(!empty($evaluation_date))
        {
        	$staf_eval_query="SELECT * FROM ".get_school_db().".staff_evaluation WHERE staff_id=".$staff_id." AND evaluation_date='".$evaluation_date."' AND school_id=".$_SESSION['school_id']."";
        	$staf_eval_edit=$this->db->query($staf_eval_query)->result_array();
        	$disable="readonly";
        }
        
        $url="";
        $url='staff_evaluation/evaluation/create/';
        if($staf_eval_edit[0]['staff_eval_id']!="")
        {
        	$staff_ans_query="SELECT * FROM ".get_school_db().".staff_evaluation_answers WHERE staff_eval_id=".$staff_eval_id." AND school_id=".$_SESSION['school_id']."";
            $staff_ans_edit=$this->db->query($staff_ans_query)->result_array();
            $url='staff_evaluation/evaluation/do_update/';
        }
        
        $eval_response=array();
        foreach($staff_ans_edit as $staf_ans)
        {
        	$eval_response[$staf_ans['staff_eval_form_id']]['answer']=$staf_ans['answers'];
        	$eval_response[$staf_ans['staff_eval_form_id']]['remarks']=$staf_ans['remarks'];
        	$eval_response[$staf_ans['staff_eval_form_id']]['staf_eval_ans_id']=$staf_ans['staf_eval_ans_id'];
        	$eval_response[$staf_ans['staff_eval_form_id']]['staf_eval_ans_id']=$staf_ans['staf_eval_ans_id'];
        	$eval_response[$staf_ans['staff_eval_form_id']]['staff_eval_id']=$staf_ans['staff_eval_id'];
        }
        
        $res_array=array();
        $misc_set="SELECT * FROM ".get_school_db().".evaluation_ratings WHERE status=1 AND type='staff_eval' AND school_id=".$_SESSION['school_id']." ";
    	$res=$this->db->query($misc_set)->result_array();
    	if(isset($res[0]['detail']) && ($res[0]['detail']!=""))
    	{
    		$res_array=explode(",",$res[0]['detail']);
    	}
    ?>

<?php echo form_open_multipart(base_url().$url , array('id'=>'staff_add','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
    <div class="row mb-4">
        <?php if ($staff_id ==0) { ?>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <label class=" control-label"><?php echo get_phrase('select_staff');?><span class="red"> * </span>  </label>
                <select name="staff_id_select" id="staff_id_select" class="form-control required-field" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" <?php echo $disable;?>>
                    <option value=""><?php echo get_phrase('select_staff');?></option>
                    <?php
                        $qry = "SELECT * FROM ".get_school_db().".staff WHERE
                        school_id=".$_SESSION['school_id']." ";
                        $query=$this->db->query($qry)->result_array();
                        foreach($query as $teacher)
                        {
                            $selected="";
                            if($staff_id==$teacher['staff_id'])
                            {
                            	$selected="selected";
                            }
                    ?> 
                    <option value="<?php echo $teacher['staff_id'];?>" <?php echo $selected;?>><?php echo $teacher['name'];?></option>
                    <?php } ?>
            	  </select>	                                        
            </div>
        <?php } ?>
            <div class="col-lg-6 col-md-6 col-sm-6">
            	<label class="control-label"><?php echo get_phrase('evaluation_date');?><span class="red"> * </span></label>
            	<input type="text" class="form-control datepicker required-field" name="evaluation_date" id="evaluation_date" value="<?php if(isset($evaluation_date) && ($evaluation_date > 0)){echo date_dash($evaluation_date);}?>" data-format="dd/mm/yyyy" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
            </div>
            <div class="teacher-detail">
            <?php if ($staff_id > 0){ ?>
                <input type="hidden" name="staff_id_select" value="<?= $staff_id ?>">
                <div class="col-lg-4 col-md-4 col-sm-4 text-center">
            		<?php
                		$qry = "SELECT * FROM ".get_school_db().".staff WHERE
                		school_id=".$_SESSION['school_id']." AND staff_id=".$staff_id;
                		$teacher=$this->db->query($qry)->result_array();
            			$pic = display_link($teacher[0]['staff_image'],'staff');
            		?>
        			<img src="<?php if($teacher[0]['staff_image']!=''){ echo $pic; }else{echo base_url().'uploads/default.png';} ?>" alt="Staff Image" width="100" />
        		</div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                	<p><strong><?php echo get_phrase('teacher_name'); ?>: </strong><?php echo $teacher[0]['name'] ?></p>
                	<p><strong><?php echo get_phrase('designation'); ?>: </strong> 
                	<?php 
                	    $designation_details_arr = designation_details($teacher[0]['designation_id']);
                    	echo $designation_details_arr[0]['title'];
                	?>
                	</p>
                    <p><strong><?php echo get_phrase('employee_code'); ?>: </strong><?php echo $teacher[0]['employee_code'] ?> </p>
                </div>
            <?php } ?>   
            </div>
    </div>
<?php
    $eval="SELECT * FROM ".get_school_db().".staff_evaluation_questions WHERE status=1 AND school_id=".$_SESSION['school_id']." ";
    $eval_staff=$this->db->query($eval)->result_array();
    if(count($eval_staff)>0)
    {
?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <table class="table table-responsive table-bordered " style="width:100%;">
            <?php
            $c=0;
            foreach($eval_staff as $row)
            { $c++;
            ?>
         	<tr>
         		<td colspan="3" style="padding: 0px;background-color: #eee;margin-top: 10px;">
             	    <?php echo '<input type="hidden" name="staff_eval_array['.$c.'][staff_eval_form_id]" value="'.$row['staff_eval_form_id'].'">';?>
             		<div class="myttl">
                		<?php echo $c; ?>. 	<?php echo $row['title']; ?>
            		</div>
        		</td>
            </tr>
         	<tr>
         	    <td style="width:150px;"><?php echo get_phrase('ratings'); ?></td>
        		<td colspan="2">
        		<?php
            		echo '<input type="hidden" name="staff_eval_array['.$c.'][staf_eval_ans_id]" value="'.$eval_response[$row['staff_eval_form_id']]['staf_eval_ans_id'].'">';
        	    	echo '<select name="staff_eval_array['.$c.'][answers_select]" class="form-control m required-field">';
                	echo '<option value="">Select Rating</option>';
        	        $misc_set="SELECT * FROM ".get_school_db().".evaluation_ratings WHERE status=1 AND type='staff_eval' AND school_id=".$_SESSION['school_id']." ";
        	        $res=$this->db->query($misc_set)->result_array();
                // 	$res_array=explode(",",$res[0]['detail']);
                	foreach($res as $val)
                	{
                		$opt_selected="";
                		if(isset($eval_response[$row['staff_eval_form_id']]['answer']) && ($eval_response[$row['staff_eval_form_id']]['answer'] == $val['misc_id']))
                		{
                			$opt_selected="selected";
                		}
                		echo '<option value="'.$val['misc_id'].'" '.$opt_selected.'>'.$val['detail'].'</option>';
        	        }
        	    ?>
             	    </select>
        	     </td>
        	 
        </tr>
         	<tr>
             	<td><?php echo get_phrase('remarks'); ?></td>
                <td colspan="2">
            	    <?php echo '<textarea name="staff_eval_array['.$c.'][response]" id="response_id[]" rows="3" class="form-control m required-field">'.$eval_response[$row['staff_eval_form_id']]['remarks'].'</textarea>'; ?>
             	</td>
         	</tr>
            <?php } ?>
        </table>
    </div>
</div>
<?php } ?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
    <table class="table table-bordered" style="width:100%;">
 	<tr>
 		<td colspan="3" style="padding: 0px;background-color: #eee;margin-top: 10px;">
            <div class="myttl" style="padding: 10px;">
                <?php echo get_phrase('general_remarks');?>
            </div>
        </th>    
    </tr>
    <tr>
        <td style="width:150px;"><?php echo get_phrase('ratings');?></td>
        <td colspan="2">
        <?php 
            echo '<select name="ratings" class="form-control required-field"><option value="">Select Rating</option>';
            foreach($res as $val)
    	    {
    		    $selected="";
        		if(isset($staf_eval_edit[0]['answers']) && ($staf_eval_edit[0]['answers'] ==$val['misc_id']))
    	    	{
    			    $selected="selected";
    		    }
        		echo '<option value="'.$val['misc_id'].'" '.$selected.'>'.$val['detail'].'</option>';
    	    }
         	echo '</select>';    	
        ?>
		</td>	
    </tr>
    <tr>
    	<td><?php echo get_phrase('remarks');?></td>
    	<td colspan="2"><textarea name="remarks" id="remarks" rows="5" class="form-control required-field" data-validate="required" data-message-required="Value Required"><?php echo $staf_eval_edit[0]['remarks'];?></textarea></td>
    	<input type="hidden" name="staff_eval_id" value="<?php echo $staf_eval_edit[0]['staff_eval_id'];?>">
    </tr>
    <tr>
        <td><?php echo get_phrase('Attachment');?></td>
        <td>
        	<input value="" type="file" class="form-control" name="image1" id="image1" onchange="file_validate('image1','doc','img_g_msg')">
            <span style="color: green;"><?php echo get_phrase('allowed_file_size'); ?>:  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types'); ?>: png, jpeg, jpg, pdf, doc, docx</span>
            <br />
            <span style="color: red;" id="img_g_msg"></span>
            <br />
            <span id="id_file">	
            <?php
            	$attachment=$staf_eval_edit[0]['attachment'];	
                $val_im=display_link($attachment,'staff_evaluation',0,0); 
                if($val_im!=""){
            ?>	
            <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><?php echo get_phrase('form_attachment'); ?></a>
            <a onclick="delete_files('<?php echo $attachment; ?>','staff_evaluation','staff_eval_id','<?php echo $staf_eval_edit[0]['staff_eval_id']; ?>','attachment','staff_evaluation','id_file',2)" href="#" class="img-responsive img-thumbnail"><?php echo get_phrase('delete_attachment'); ?></a>
            <?php } ?>
            </span> 				
        </td>	
    </tr>
                
</table>
</div>
    <div class="col-md-12 col-lg-12 col-sm-12 mgt10">
        <div class="float-right">
			<button type="submit" id="select" class="modal_save_btn">
				<?php echo get_phrase('save');?>
			</button>
			<button type="button" class="modal_cancel_btn" onclick="location.reload()">
				<?php echo get_phrase('cancel');?>
			</button>
		</div>
    </div>
</div>
<?php echo form_close();?>


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