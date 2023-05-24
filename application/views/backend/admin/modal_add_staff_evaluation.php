<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
		<div class="panel-title black2">
			<i class="entypo-plus-circled"></i>
            <?php echo get_phrase('add_staff_evaluation_questions');?>
        </div>
	</div>
	<div class="panel-body">
        <?php echo form_open(base_url().'staff_evaluation/staff_eval/create' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('title');?><span class="star">*</span></label>
                <input maxlength="255" type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('evaluation_factor');?><span class="star">*</span></label>
                <select class="form-control" name="factor" id="factor">
                    <?php echo get_evaluation_factors();?>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('status');?><span class="star">*</span></label>
                <select class="form-control" name="status" id="status">
                    <option value="0"><?php echo get_phrase('inactive');?></option>
                    <option value="1"><?php echo get_phrase('active');?></option>	
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
    </div>    
</div>   