<style>
.entypo-plus-circled:before
{
	color: #000 !important;
}
</style>
<?php
if($this->session->flashdata('club_updated')){
    echo '<div align="center">
	<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	'.$this->session->flashdata('club_updated').'
	</div> 
	</div>';
}
?>

<script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
</script>

<style>
    .active {
        color: red !important;
    }
    .panel-title.black2.hdng-clr {
    color: #fff !important;
    }
    .panel-title.black2.hdng-clr .entypo-plus-circled:before {
    color: #fff !important;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline" data-step="1" data-position='left' data-intro="voucher setting">
            
            <?php echo get_phrase('voucher_settings');?>
        </h3>
    </div>
</div>
<?php
$account_type = 1;
?>
<div class="row">
    <!--cash receipt Drop down start-->
    <div class="col-sm-6" data-step="2" data-position='top' data-intro="you can set voucher receipt chart of account, select account then press save button">
        <div style="padding: 20px 20px 30px 20px; border: 1px solid #eee;">
            <?php
            //if (right_granted('chartofaccount_manage'))
            {
                $get_val=$this->db->query("select * from ".get_school_db().".cash_voucher_settings where school_id=".$_SESSION['school_id']." and 	cash_voucher_type='Receipt'")->result_array();
                ?>
                <div class="box-content">
                    <div class="panel-heading" style="background-color: #02658d;">
                        <div class="panel-title black2  hdng-clr">
                        <i class="entypo-plus-circled"></i>
                            <?php echo get_phrase('chart_of_account_title_for_cash_receipt');?>
                            </div>
                    </div>
                    <form class="form-horizontal form-groups-bordered validate" action="<?php echo base_url();?>vouchers/add_cash_receipt_voucher_setting" method="post">

                    <!--COA while cash Receipt Start-->
                    <fieldset class="custom_legend">
                        <legend class="custom_legend"><?php echo get_phrase('COA'); ?> 
                        
                        <?php echo get_phrase('while_cash_receipt');?>
                       :</legend>
                        <div class="form-group">
                            <label for="field-2" class="col-sm-12 control-label text-left"><?php echo get_phrase('cash_receipt');?><span class="star">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control select2" name="cash_receipt" id="cash_receipt" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" required>
                                    <option value=""><?php echo get_phrase('select');?></option>
                                    <?php
                                    echo coa_list_h($parent_id=0,$get_val[0]['coa_id'],0,0,$account_type);
                                    ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <!--COA while cash Receipt End-->

                    <div class="col-sm-12 mgt10">
                        <button id="btn_save" class="btn btn-primary pull-right" type="submit"><?php echo get_phrase('save');?></button>
                    </div>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <!--cash receipt Drop down End-->

    <!--cash payment Drop down start-->
    <div class="col-sm-6" data-step="3" data-position='top' data-intro="you can set voucher cash chart of account, select account then press save button">
        <div style="padding: 20px 20px 30px 20px; border: 1px solid #eee;">
            <?php
            $get_val1=$this->db->query("select * from ".get_school_db().".cash_voucher_settings where school_id=".$_SESSION['school_id']." and 	cash_voucher_type='Payment'")->result_array();

            //if (right_granted('chartofaccount_manage'))
            {
                ?>
                <div class="box-content">
                    <div class="panel-heading" style="    background-color: #02658d;">
                        <div class="panel-title black2 hdng-clr">
                            <i class="entypo-plus-circled">
                            </i> 
                            <?php echo get_phrase('chart_of_account_title_for_cash_payment');?>
                        </div>
                    </div>
                    <form class="form-horizontal form-groups-bordered validate" action="<?php echo base_url()?>vouchers/add_cash_payment_voucher_setting" method="post" >
                        <!--COA while cash Payment Start-->
                        <fieldset class="custom_legend">
                            <legend class="custom_legend"><?php echo get_phrase('COA'); ?> 
                            
                            <?php echo get_phrase('while_cash_payment');?>
                            :</legend>
                            <div class="form-group">
                                <label for="field-2" class="col-sm-12  control-label text-left"><?php echo get_phrase('cash_payment');?>  <span class="star">*</span>   </label>
                                <div class="col-sm-12">
                                    <select class="form-control select2" name="cash_payment" id="cash_payment">
                                        <option value=""><?php echo get_phrase('select');?></option>
                                        <?php
                                        echo coa_list_h($parent_id=0,$get_val1[0]['coa_id'] ,0 , 0 , $account_type);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <!--COA while cash Payment End-->
                        <div class="col-sm-12 mgt10">
                            <button id="btn_save1" class="btn btn-primary pull-right" type="submit"><?php echo get_phrase('save');?></button>
                        </div>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <!--cash payment Drop down End-->
</div>

<div class="row mgt35">
    <!--Bank Payment tax Drop down start-->
    <div class="col-sm-6" data-step="4" data-position='top' data-intro="you can set chart of account for tax while bank payment voucher, select account then press save button">
        <div style="padding: 20px 20px 30px 20px; border: 1px solid #eee;">
            <?php
                $get_val=$this->db->query("select * from ".get_school_db().".cash_voucher_settings where school_id=".$_SESSION['school_id']." and 	cash_voucher_type='Tax'")->result_array();
                ?>
                <div class="box-content">
                    <div class="panel-heading" style="background-color: #02658d;">
                        <div class="panel-title black2  hdng-clr">
                        <i class="entypo-plus-circled"></i>
                            <?php echo get_phrase('chart_of_account_title_for_tax_while_bank_payment');?>
                            </div>
                    </div>
                    <form class="form-horizontal form-groups-bordered validate" action="<?php echo base_url();?>vouchers/add_tax_setting" method="post">

                    <!--COA while cash Receipt Start-->
                    <fieldset class="custom_legend">
                        <legend class="custom_legend"><?php echo get_phrase('COA'); ?> 
                        
                        <?php echo get_phrase('tax_while_bank_payment');?>
                       :</legend>
                        <div class="form-group">
                            <label for="field-2" class="col-sm-12 control-label text-left"><?php echo get_phrase('tax_while_bank_payment');?><span class="star">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control select2" name="tax_while_bank_payment" id="tax_while_bank_payment" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" required>
                                    <option value=""><?php echo get_phrase('select');?></option>
                                    <?php
                                        echo coa_list_h($parent_id=0,$get_val[0]['coa_id'],0,0,$account_type);
                                    ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <!--COA while cash Receipt End-->

                    <div class="col-sm-12 mgt10">
                        <button id="btn_save" class="btn btn-primary pull-right" type="submit"><?php echo get_phrase('save');?></button>
                    </div>
                    </form>
                </div>
        </div>
    </div>
    <!--Bank Payment tax Drop down End-->
</div>