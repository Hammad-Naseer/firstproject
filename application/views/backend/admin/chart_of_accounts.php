<?php 
//edit 
get_instance()->load->helper('coa');
if(preg_match("/\/(\d+)$/",$_SERVER['REQUEST_URI'],$matches)){
$id=$matches[1];
$edit_data=$this->db->get_where(get_school_db().'.chart_of_accounts' , array('coa_id' => $id) )->result_array();
}

if($edit_data){
$url='chart_of_account/coa/edit/';
}
else{
$url='chart_of_account/coa/create/';
}
//populate parent head
//$this->db->select('coa_id,account_head');
//$this->db->from('chart_of_accounts');
//$parent_id_array=$this->db->get()->result_array();
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title black2" >
					<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_chart_of_account');?>
				</div>
			</div>
			<div class="panel-body">
				
				<?php echo form_open(base_url().$url , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
				<input type="hidden"  name="coa_id" value="<?php echo $edit_data[0]['coa_id']?>" >
			
			
				<div class="form-group">
					<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('Account_title');?></label>
                        
					<div class="col-sm-8">
						<input maxlength="250" type="text" class="form-control" name="account_title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php if($edit_data[0]['account_head'])echo $edit_data[0]['account_head']?>" autofocus>
					</div>
				</div>

<input type="hidden" name="parent_id" value="0"/>
				<!--<div class="form-group">
					<label for="field-1" class="col-sm-4 control-label"><?php //echo get_phrase('Parent Head');?></label>
                        
					<div class="col-sm-8">
						<select name="parent_id" class="form-control">
						<option value="">None</option>
						
				<?php  
//coa_list_h($parent_id=0,$selected=0);

 ?>
</select>
</div>
</div>-->
				<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('account_type');?></label>
<div class="col-sm-8">
<select name="account_type" class="form-control">

<option value="1" <?php if($edit_data[0]['account_type']==1){echo "selected=selected";}?>><?php echo get_phrase('credit'); ?></option>
<option value="2"<?php if($edit_data[0]['account_type']==2){echo "selected=selected";} ?>><?php echo get_phrase('debit'); ?></option>
					</select>
					</div> 
				</div>
				<div class="form-group">
					<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('account_number');?></label>
                        
					<div class="col-sm-8">
						<input maxlength="100" type="text" class="form-control" name="account_number" id="account_number" value="<?php if($edit_data[0]['account_number'])echo $edit_data[0]['account_number']?>" data-start-view="2">
					</div> 
				</div>
				<div class="form-group">
					<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('Status');?></label>
                        
					<div class="col-sm-8">
								
							<?php
				
		if($_SESSION['user_login']==1){ ?>
			
	
	<select name="status" class="form-control">
	
	<?php

echo coa_status_option();	

}else{
		
		$hd_var=0;
	}	
?>
</select>		
						
						
						
						
						
					</div> 
				</div>
                <div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<button id="btn_save" type="submit" class="btn btn-info" ><?php echo get_phrase('save');?></button>
					</div>
				</div>


<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>

<script>
	
$('#account_number').change(function(){
	var account_number=$(this).val();
	$('#message').remove();
	$('#loader').remove();
	$('#account_number').after("<div id='loader' class='loader_small'></div>")
	//alert('hi');
	$.ajax({
			type: 'POST',
			data: {account_number:account_number},
			 url: "<?php echo base_url();?>chart_of_account/account_number_val",
			dataType: "html",
			success: function(response) {
				if($.trim(response)=="no"){
					$('#account_number').before('<span id="message"><?php echo get_phrase('this_number_already_exists'); ?></span>');
					$('#btn_save').attr('disabled','true');
				}else{
					$('#btn_save').removeAttr('disabled');
				}
				$('#loader').remove();
			 }
		});
});	
	
	
	
</script>


<style>
#message{
	
	color: red;
	
	
}
.loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #63b7e7; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
    margin-right: auto;
    margin-left: auto;
}

.loader_small {
       border: 7px solid #f3f3f3;
    border-top: 7px solid #63b7e7;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    margin-right: auto;
    margin-left: auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

</style>

