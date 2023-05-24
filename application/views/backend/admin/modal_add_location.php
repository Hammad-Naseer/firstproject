<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title  black2" >
					<i class="entypo-plus-circled"></i>
                    <?php echo get_phrase('add_location');?>
				</div>
			</div>
			<div class="panel-body">
				<?php echo form_open(base_url().'location/add_location' , array('id'=>'disable_submit_btn','class'=>'form-horizontal form-groups-bordered validate','enctype'=> 'multipart/form-data'));?>
				<div class="form-group">
					<label for="field-1" class="control-label">
						<?php echo get_phrase('select_country');?><span class="star">*</span>
					</label>
					<select name="loc_add_country" id="loc_add_country" class="form-control" required>
						<?php echo country_option_list();?>
					</select>
				</div>
    			<div class="form-group">
    				<label for="field-1" class="control-label">
    		            <?php echo get_phrase('select_province');?> / <?php echo get_phrase('state');?>
    		            <span class="star">*</span>
    				</label>
    				<select name="loc_add_province" id="loc_add_province" class="form-control" required ></select>
    			</div>
    			<div class="form-group">
    				<label for="field-1" class="control-label">
    					<?php echo get_phrase('select_city');?><span class="star">*</span>
    				</label>
    				<select name="loc_add_city" id="loc_add_city" class="form-control" required ></select>
    			</div>
    			<div class="form-group">
    				<label for="field-2" class="control-label">
    					<?php echo get_phrase('location');?><span class="star">*</span>
    				</label>
    				<input maxlength="500" type="text" class="form-control" name="title" required >
    			</div>
    			<div class="form-group">
    				<label for="field-1" class="control-label">
    					<?php echo get_phrase('status');?>
    				</label>
    				<select name="loc_status" id="loc_status" class="form-control" required >
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

<script>
	
	
	
//$('input').setCustomValidity('Please select an item in the list');
	
	
	
	
	
	
	$('#loc_add_country').on('change',function()
	{
		var loc_id = $('#loc_add_country').val();
		console.log(loc_id);
		$('#loc_add_country').after('<span id="message" class="myloader" ></span>');
		
		$.ajax({
			
			url:"<?php echo base_url();?>location/get_province_list",
			type:'post',
			data:{id:loc_id},
			dataType:'html',
			success:function(res)
			{
				$('#message').remove();
				$('#loc_add_province').html(res);
			}
		});
	});
	
	$('#loc_add_province').change(function()
	{
		var prov_id = $('#loc_add_province').val();
		$('#loc_add_province').after('<span id="message" class="myloader" ></span>');
		
		$.ajax({
			url:"<?php echo base_url();?>location/get_city_list",
			type:'post',
			data:{id:prov_id},
			dataType:'html',
			success:function(res)
			{
				$('#message').remove();
				$('#loc_add_city').html(res);
			}
		});
	});
</script>
