<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title  black2" >
					<i class="entypo-plus-circled"></i>
                    <?php echo get_phrase('add_allownces');?>
				</div>
			</div>
			<div class="panel-body">
				<?php echo form_open(base_url().'payroll/add_allownces' , array('id'=>'disable_submit_btn','class'=>'form-horizontal form-groups-bordered validate','enctype'=> 'multipart/form-data'));?>

    			<div class="form-group">
    				<label for="field-2" class="control-label">
    					<?php echo get_phrase('allownce_title');?><span class="star">*</span>
    				</label>
    				<input maxlength="500" type="text" class="form-control" name="allownce_title" required >
    			</div>
    			<!--<div class="form-group">-->
    			<!--	<label for="field-2" class="control-label">-->
    			<!--		<?php echo get_phrase('allownce_percentage');?><span class="star">*</span>-->
    			<!--	</label>-->
    			<!--	<input maxlength="100" type="number" class="form-control" name="allownce_percentage" required >-->
    			<!--</div>-->
    			<div class="form-group">
    				<label for="field-1" class="control-label">
    					<?php echo get_phrase('status');?>
    				</label>
    				<select name="status" id="status" class="form-control" required >
    					<option value="1"><?php echo get_phrase('active');?></option>
    					<option value="0">
    					<?php echo get_phrase('incactive');?></option>
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
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>
