<?php 
$edit_data		=	$this->db->get_where(get_school_db().'.student_evaluation_questions' , array(
				'eval_id' => $param2,
				'school_id' =>$_SESSION['school_id']
				) )->result_array();
?>
<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
    	<div class="panel-title black2">
    		<i class="entypo-plus-circled"></i>
            <?php echo get_phrase('edit_student_evaluation_questions');?>
    	</div>
    </div>
    <div class="panel-body">
        <?php foreach($edit_data as $row):?>
        <?php echo form_open(base_url().'evaluation/stud_evaluation/do_update/'.$row['eval_id'] , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('title');?>
                    <span class="star">*</span></label>
                        <input type="text" class="form-control" name="name" value="<?php echo $row['title'];?>" maxlength="255" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('evaluation_type');?><span class="star">*</span></label>
                    <select class="form-control" name="eval_type" id="eval_type">
                        <option <?php echo ($row['type'] == '1' )?'selected':'';?> value="1"><?php echo get_phrase('exam');?></option>
                        <option <?php echo ($row['type'] == '2' )?'selected':'';?> value="2"><?php echo get_phrase('general');?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('evaluation_factor');?><span class="star">*</span></label>
                    <select class="form-control" name="factor" id="factor">
                        <?php echo get_evaluation_factors($row['factor']);?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('status');?>
                    <span class="star">*</span></label>
                     <?php $status=$row['status'];
                             $status_val="";
                             $status_val1="";
                             if($status==0)
                             {
							 	$status_val="selected";
							 }
							 if($status==1)
                             {
							 	$status_val1='selected';
							 }
                             ?>
                            <select class="form-control" name="status1" id="status1">
                                <option value="0" <?php echo $status_val;?>><?php echo get_phrase('inactive'); ?></option>
                                <option value="1" <?php echo $status_val1;?>><?php echo get_phrase('active'); ?></option>
                            </select>
                </div>
                
                <div class="form-group">
                    <div class="float-right">
    					<button type="submit" class="modal_save_btn">
    						<?php echo get_phrase('save');?>
    					</button>
    					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
    						<?php echo get_phrase('cancel');?>
    					</button>
    				</div>
                </div>
        </form>
        <?php endforeach;?>
    </div>
</div>