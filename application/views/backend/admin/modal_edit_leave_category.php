<?php 
    $edit_data = $this->db->get_where(get_school_db().'.leave_category' , array(
	    'leave_category_id' => $param2,
	    'school_id' =>$_SESSION['school_id']
	) )->result_array();
?>
<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
		<div class="panel-title black2">
			<i class="entypo-plus-circled"></i>
            <?php echo get_phrase('edit_leave_category');?>
        </div>
	</div>
    <div class="panel-body">
        <?php foreach($edit_data as $row):?>
        <?php echo form_open(base_url().'leave/manage_leaves/do_update/'.$row['leave_category_id'] , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('title');?>
                    <span class="star">*</span></label>
                    <input type="text" class="form-control" name="name" value="<?php echo $row['name'];?>" maxlength="255" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                    <input type="hidden" class="form-control" name="leave_category_id" value="<?php echo $row['leave_category_id'];?>"/>
                </div>
                <div class="form-group">
                  <div class="float-right">
                        <button type="submit" class="modal_save_btn"><?php echo get_phrase('edit_category');?></button>
                        <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                            <?php echo get_phrase('cancel');?>
                        </button>
                    </div>
                </div>
        </form>
        <?php endforeach;?>
    </div>
</div>