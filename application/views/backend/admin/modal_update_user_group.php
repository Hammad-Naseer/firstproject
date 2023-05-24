<?php //session_start(); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('update_user_group');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url().'user/update_user_group' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
					
					<div class="form-group">
						<label for="field-1" class="control-label"><?php echo get_phrase('user_group');?></label>
						<select class="form-control" name="user_group_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
						    <?php echo user_group_option_list();?>
						</select>
					</div>
                    <div class="form-group">
						<div class="float-right">
        					<button type="submit" id="btn1" class="modal_save_btn">
        						<?php echo get_phrase('update');?>
        					</button>
        					<input type="hidden" name="user_type" value="<?php echo $this->uri->segment(4); ?>" />
					        <input type="hidden" name="user_id" value="<?php echo $this->uri->segment(5); ?>" />
        					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
        						<?php echo get_phrase('cancel');?>
        					</button>
        				</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<style>
	.glyphicon-refresh-animate {
    -animation: spin .7s infinite linear;
    -webkit-animation: spin2 .7s infinite linear;
}

@-webkit-keyframes spin2 {
    from { -webkit-transform: rotate(0deg);}
    to { -webkit-transform: rotate(360deg);}
}

@keyframes spin {
    from { transform: scale(1) rotate(0deg);}
    to { transform: scale(1) rotate(360deg);}
}
</style>