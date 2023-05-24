<?php
    $data['location_id']               = $param2;
    $data['school_id']                 = $_SESSION['school_id'];
    $data['school_db']                 = $_SESSION["school_db"];
    $data['user_login_detail_id']      = $_SESSION["login_detail_id"];
    $data['token']                     = $_SESSION["token"];
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => base_url().'api/api_location_edit',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $data,
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response);

?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title black2">
					<i class="entypo-plus-circled">
					</i>
					<?php echo get_phrase('edit_location'); ?>
				</div>
			</div>
			<div class="panel-body">
				<?php echo form_open(base_url().'location/edit_location' , array('id'=>'disable_submit_btn','class'  => 'form-horizontal form-groups-bordered validate','enctype'=> 'multipart/form-data'));?>
    				<div class="form-group">
    					<label for="field-1" class="control-label">
    						<?php echo get_phrase('select_country');?><span class="star">*</span>
    					</label>
    					<select name="loc_add_country" id="loc_add_country" class="form-control" required >
    						<?php echo country_option_list($result->data[0]->country_id);?>
    					</select>
    				</div>
    				<div class="form-group">
    					<label for="field-1" class="control-label">
    						<?php echo get_phrase('select_province');?>
                            /
                            <?php echo get_phrase('state');?>
                            <span class="star">*</span>
    					</label>
    					<select name="loc_add_province" id="loc_add_province" class="form-control" required >
    						<?php echo province_option_list($result->data[0]->country_id, $result->data[0]->province_id);?>
    					</select>
    				</div>
    				<div class="form-group">
    					<label for="field-1" class="control-label">
    						<?php echo get_phrase('select_city');?><span class="star">*</span>
    					</label>
    					<select name="loc_add_city" id="loc_add_city" class="form-control" required >
    					<?php echo city_option_list($result->data[0]->province_id,$result->data[0]->city_id);?>
    					</select>
    				</div>
    				<div class="form-group">
    					<label for="field-2" class="control-label">
    						<?php echo get_phrase('location');?><span class="star">*</span>
    					</label>
    					<input type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $result->data[0]->title;?>" >
    				</div>
    				<div class="form-group">
    					<label for="field-1" class="control-label">
    						<?php echo get_phrase('status');?>
    					</label>
    					<select name="loc_status" id="loc_status" class="form-control" required >
    						<option value="1" <?php if($result->data[0]->status == 1) echo 'selected';?> ><?php echo get_phrase('active');?></option>
    						<option value="0" <?php if($result->data[0]->status == 0) echo 'selected';?>><?php echo get_phrase('inactive');?></option>
    					</select>
    				</div>
    				<div class="form-group">
    					<div class="float-right">
        					<input type="hidden" name="location_id" value="<?php echo $param2;?>" >
        					<button type="submit" class="modal_save_btn">
        						<?php echo get_phrase('update');?>
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
	$('#loc_add_country').on('change',function(){
    	if( $('#loc_add_country').val() == "") {
    		$('#message').remove();
    	}else{
    		var loc_id = $('#loc_add_country').val();
    		console.log(loc_id);
    		$('#loc_add_country').after('<span id="message" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
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
	    }
	});
	
	$('#loc_add_province').change(function(){
		$('#message').remove();
		var prov_id = $('#loc_add_province').val();
		$('#loc_add_province').after('<span id="message" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
		
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