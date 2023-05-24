<?php 
$edit_data		=	$this->db->get_where(get_school_db().'.evaluation_ratings' , array(
				'misc_id' => $param2,
				'school_id' =>$_SESSION['school_id']
				) )->result_array();
?>
<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
    	<div class="panel-title black2">
    		<i class="entypo-plus-circled"></i>
            <?php echo get_phrase('edit_student_evaluation_rating');?>
    	</div>
    </div>
    <div class="panel-body">
        <?php foreach($edit_data as $row):?>
        <?php echo form_open(base_url().'evaluation/evaluation_rating/do_update/'.$row['misc_id'] , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('title');?>
                    <span class="star">*</span></label>
                        <input type="text" class="form-control" name="detail" value="<?php echo $row['detail'];?>" maxlength="255" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
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
                            <select class="form-control" name="status" id="status1">
                                <option value="0" <?php echo $status_val;?>><?php echo get_phrase('inactive'); ?></option>
                                <option value="1" <?php echo $status_val1;?>><?php echo get_phrase('active'); ?></option>
                            </select>
                </div>
                <input type="hidden" name="type" id="type" value="stud_eval" />
                    <input type="hidden" name="misc_id" id="misc_id" value="<?php echo $misc['misc_id'];?>" />
                
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