<?php 
//edit 
    get_instance()->load->helper('coa');
    if(preg_match("/\/(\d+)$/",$_SERVER['REQUEST_URI'],$matches)){
    $id=$matches[1];
    $edit_data=$this->db->get_where(get_school_db().'.chart_of_accounts' , array('coa_id' => $id) )->result_array();
    $account_type = 0;
}

/*if($edit_data){
$url='chart_of_account/coa/edit/';
}*/
/*else{
$url='chart_of_account/coa/create/';
}*/
//populate parent head
//$this->db->select('coa_id,account_head');
//$this->db->from('chart_of_accounts');
//$parent_id_array=$this->db->get()->result_array();
?>
<div class="row">
	<div class="col-md-12">
			<div class="panel-heading">
				<div class="panel-title black2" >
					<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_chart_of_account'); ?>
				</div>
			</div>
			<div class="panel-body">	
				<?php echo form_open(base_url().'chart_of_account/coa/create/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data','id'=>'add_chart_of_account'));?>
                <?php
                
                if ($edit_data[0]['coa_id'] > 0)
                {
                    ?>
                    <div class="form-group">
                        <label for="field-2" class="control-label"><?php echo get_phrase('parent_head'); ?>  <span class="star">*</span>   </label>
                        <select name="parent_id" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('Select');?></option>
                            <?php
                            coa_list_h(0,$edit_data[0]['coa_id'] ,0, 0, $account_type); ?>
                        </select>
                    </div>
                <?php }else{ ?>
                    <input type="hidden"  name="parent_id" value="0" >
                <?php } ?>
				<div class="form-group">
					<label for="field-1" class="control-label"><?php echo get_phrase('Account_title');?><span class="star">*</span></label>
					<input maxlength="250" type="text" class="form-control" name="account_title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
				</div>
<?php /*
                <input type="hidden" name="parent_id" value="<?php echo $edit_data[0]['coa_id'];?>"/>
*/  ?>
			<!--Account type start-->	
                <div class="form-group">
                    <label for="field-2" class="control-label"><?php echo get_phrase('account_type');?>
                    <span class="star">*</span></label>
                    <select name="account_type" class="form-control" required>
                        <?php echo debit_credit_optoin(); ?>
                    </select>
				</div>
              <!--Account type End-->
                
				<div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('account_number_/_code');?><span class="star">*</span></label>
					<input maxlength="100" type="text" class="form-control" name="account_number" id="account_number" value="" data-start-view="2" required>
				</div>
				<!--Status Start-->
                <div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('status');?></label>
					<?php
					//if($_SESSION['user_login']==1)
					//{ ?>
                    <select name="status" class="form-control" required>
                        <?php echo coa_status_option(); ?>
                    </select>
					
				</div>
                         <!--Status End-->
                        <!--Is Actibe Start-->
                            <div class="form-group">
                                <label for="field-2" class="control-label"><?php echo get_phrase('is_active');?></label>
                                <select name="is_active" class="form-control" required>
                                   <?php echo debit_credit_active_optoin(); ?>
                                </select>
                           </div>
                           <!--Is Active End-->

                        <!--- chart of account types start -->
                            <div class="form-group">
                            <fieldset class="custom_legend">
                                <legend class="custom_legend"><?php echo get_phrase('areas_where_this_COA_title_can_be_used'); ?>:</legend>
                                <!--<div class="col-md-12">-->
                                    <div class="form-check form-check-inline" style="float:left;">
                                        <label class="form-check-label">
                                            <input name="coa_type[]" class="form-check-input" type="checkbox" id="inlineCheckbox1" value="1"> <?php echo get_phrase('fee'); ?>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline" style="float:left;">
                                        <label class="form-check-label">
                                            <input name="coa_type[]" class="form-check-input" type="checkbox" id="inlineCheckbox2" value="2"> <?php echo get_phrase('discount'); ?>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline" style="float:left;">
                                        <label class="form-check-label">
                                            <input name="coa_type[]" class="form-check-input" type="checkbox" id="inlineCheckbox3" value="3"> <?php echo get_phrase('Debitor'); ?>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline" style="float:left;">
                                        <label class="form-check-label">
                                            <input name="coa_type[]" class="form-check-input" type="checkbox" id="inlineCheckbox3" value="4"> <?php echo get_phrase('Creditor'); ?>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline" style="float:left;">
                                        <label class="form-check-label">
                                            <input name="coa_type[]" class="form-check-input" type="checkbox" id="inlineCheckbox3" value="5"> <?php echo get_phrase('payment_detail'); ?>
                                        </label>
                                    </div>
                                <!--</div>-->
                            </fieldset>
                        </div>

                        <!--- chart of account types End -->



                        <div class="form-group">
                            <div class="float-right">
                                <button type="submit" id="btn_save" class="modal_save_btn">
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
	
$('#account_number').change(function(){
	
	var account_number=$(this).val();
	$('#message').remove();
	$('#loader').remove();


	$('#account_number').after("<div id='loader' class='loader_small'></div>")
	
	$.ajax({
			type: 'POST',
			data: {account_number:account_number},
			 url: "<?php echo base_url();?>chart_of_account/account_number_val",
			dataType: "html",
			success: function(response)
            {
				if($.trim(response)=="no")
				{
					$('#account_number').before('<span id="message"><?php echo get_phrase('this_number_is_already_exist'); ?></span>');
					$('#btn_save').attr('disabled','true');
						$('#loader').remove();
				}
				else
                {
				$('#btn_save').removeAttr('disabled');	
					$('#loader').remove();
				}
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
.form-check-label {

    padding: 0 19px 19px 0;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

</style>

<script src="<?php echo base_url(); ?>assets/js/common.js"></script>