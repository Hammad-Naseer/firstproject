<?php 
    $school_id=$_SESSION['school_id'];
    $param2= str_decode($this->uri->segment(3));
    $edit_data=$this->db->get_where(get_school_db().'.bank_account' , array('bank_account_id' => $param2,'school_id'=>$school_id))->result_array();
?>
<style>
    .panel-body {
        padding: 0px;
    }
    .back_space{
        margin-top: 10px !important;
        margin-bottom: 10px !important;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <h3 class="system_name inline back_space">
                <?php
                if(empty($param2))
                {
                    echo get_phrase('add_bank_account');
                }
                else 
                {
                    echo get_phrase('edit_bank_account');
                }
                ?>
            </h3>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-body">
            <?php
            $action = "";
            if(empty($param2))
            {
                $action = "bank_detail/add_edit/add";
            }
            else 
            {
                $action = "bank_detail/add_edit/edit/".$edit_data[0]['bank_account_id']."";
            }
            ?>
                <?php echo form_open(base_url().$action , array('id'=>'bank_add_edit_form','class' => 'form-horizontal form-groups-bordered validate row', 'enctype' => 'multipart/form-data'));?>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                    <?php echo get_phrase('bank_name');?>
                    <span class="star">*</span></label>
                    <input type="text" class="form-control" id="bank_name" name="bank_name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['bank_name'];?>">
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                    <?php echo get_phrase('bank_address');?><span class="star">*</span></label>
                    <input type="text" class="form-control" id="bank_address" name="bank_address" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['bank_address'];?>">
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                    <?php echo get_phrase('branch_name');?>
                    <span class="star">*</span></label>
                    <input type="text" class="form-control" name="branch_name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['branch_name'];?>">
                </div>
                <div class="col-md-6">
                    <label for="field-1">
                        <?php echo get_phrase('branch_code');?>
                        <span class="star">*</span>
                    </label>
                    <input type="text" class="form-control" name="branch_code" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['branch_code'];?>">
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('account_title');?>
                        <span class="star">*</span>
                    </label>
                    <input type="text" class="form-control" name="account_title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['account_title'];?>">
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('account');?> #
                        <span class="star">*</span>
                    </label>
                    <input type="text" class="form-control" name="account_number" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['account_number'];?>">
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('account_type');?>
                    </label>
                    <input type="text" class="form-control" name="account_type" value="<?php echo $edit_data[0]['account_type'];?>">
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('i-Ban');?> #
                    </label>
                    <input type="text" class="form-control" name="i_ban" value="<?php echo $edit_data[0]['iban_number'];?>">
                </div>
                <div class="col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('status');?>
                        <span class="star">*</span></label>
                    <?php
                    $selected = "";
                    if (isset($edit_data[0]['status']))
                    {
                        $selected = $edit_data[0]['status'];
                    }
                    echo status('status','form-control',$selected,'status');?>
                </div>
                <div class="col-md-6">
                    <label for="field-2" class="control-label">
                    <?php echo get_phrase('coa_fee_cash_receipt');?>
                    <span class="star">*</span></label>
                    <select name="coa_fee_cash_receipt" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <option value=""><?php echo get_phrase('select_chart_of_account');?></option>
                        <?php  coa_list_h(0,$edit_data[0]['coa_fee_cash_receipt']);?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="field-2" class="control-label">
                    <?php echo get_phrase(' coa_fee_check_receipt');?><span class="star">*</span></label>
                    <select name=" coa_fee_check_receipt" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                    <option value=""><?php echo get_phrase('select_chart_of_account');?></option>
                        <?php coa_list_h(0,$edit_data[0]['coa_fee_check_receipt']);?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="field-2" class="control-label">
                    <?php echo get_phrase('coa_cash_receipt');?><span class="star">*</span></label>
                    <select name="coa_cash_receipt" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                    <option value=""><?php echo get_phrase('select_chart_of_account');?></option>
                        <?php coa_list_h(0,$edit_data[0]['coa_cash_receipt']);?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="field-2" class="control-label">
                    <?php echo get_phrase('coa_check_receipt');?><span class="star">*</span></label>
                    <select name="coa_check_receipt" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                    <option value=""><?php echo get_phrase('select_chart_of_account');?></option>
                        <?php coa_list_h(0,$edit_data[0]['coa_check_receipt']);?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="field-2" class="control-label">
                    <?php echo get_phrase('coa_cash_payment');?><span class="star">*</span></label>
                    <select name="coa_cash_payment" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <option value=""><?php echo get_phrase('select_chart_of_account');?></option>
                        <?php coa_list_h(0,$edit_data[0]['coa_cash_payment']);?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label for="field-2" class="control-label">
                    <?php echo get_phrase('coa_check_payment');?><span class="star">*</span></label>
                    <select name="coa_check_payment" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <option value=""><?php echo get_phrase('select_chart_of_account');?></option>
                        <?php coa_list_h(0,$edit_data[0]['coa_check_payment']);?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('description');?>
                    </label>
                    <textarea rows="4" class="form-control" name="description" oninput="count_value('address_cont','address_count','200')" maxlength="200"><?php echo $edit_data[0]['description']; ?></textarea>
                </div>
                
                <?php
                /*
                <div class="border mgt10">
                    <div class="panel panel-default panel-shadow panel-collapse" data-collapsed="0">
                        <div class="panel-heading">
                            <div class="panel-options" style="float:left; text-align: left">
                            <a data-toggle="collapse" data-target="#info" class="text-left" style="float:left !important;"><h3 style="margin: 0px; padding: 0px 18px 10px 18px; border-bottom: 0px solid #000 !important;">
                            <?php echo get_phrase('add_more_information');?>
                            <i class="entypo-down-open"></i>
                            </h3>
                            </a>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div id="info" class="collapse">

                </div>
				<div class="border mgt10">
                    <div class="panel panel-default panel-shadow panel-collapse" data-collapsed="0">
                        <div class="panel-heading">
                            <div class="panel-options" style="float:left; text-align: left">
                            <a data-toggle="collapse" data-target="#sal_div" class="text-left" style="float:left !important;"><h3 style="margin: 0px; padding: 0px 18px 10px 18px; border-bottom: 0px solid #000 !important;">
                            <?php echo get_phrase('salary_settings');?>
                            <i class="entypo-down-open"></i>
                            </h3>
                            </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="sal_div" class="collapse">

                </div>
                */
                ?>
                <div class="col-md-12">
                    <div class="float-right">
    					<button type="submit" class="modal_save_btn" id="btn-dsb">
    						<?php echo get_phrase('save');?>
    					</button>
    					<button type="button" class="modal_cancel_btn" onclick="location.reload()">
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
$(window).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>