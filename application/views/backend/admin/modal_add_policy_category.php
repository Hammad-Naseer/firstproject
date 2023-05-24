<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title" style="color:#818da1" >
					<i class="entypo-plus-circled text-white">
					</i>
					<?php echo get_phrase('add_policy_category');?>
				</div>
			</div>
			<div class="panel-body">
				<?php echo form_open(base_url().'policies/policy_categories/add' , array('id'=>'disable_submit_btn','class'  => 'form-horizontal form-groups-bordered validate','enctype'=> 'multipart/form-data'));?>
				<div class="form-group">
					<label for="field-2" class="control-label">
						<?php echo get_phrase('title');?><span class="star">*</span>
					</label>
					<input maxlength="250" id="title" type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" >
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
<script>

$(document).ready(function(){

$('#title').change(function(){
	
$(this).val();
	
	
});



});
		
	
	
</script>