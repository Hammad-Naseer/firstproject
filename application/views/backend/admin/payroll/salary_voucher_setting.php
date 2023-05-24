<style>
.entypo-plus-circled:before
{
	color: #000 !important;
}
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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline" data-step="1" data-position='left' data-intro="salary annexure COA settings">
            
            <?php echo get_phrase('salary_voucher_setting');?>
        </h3>
    </div>
</div>
<?php
$account_type = 1;
?>
<div class="row">
    <div class="col-sm-6" data-step="2" data-position='top' data-intro="you can set which coa will be debit while sallary booking for gross salary">
        <div style="padding: 20px 20px 30px 20px; border: 1px solid #eee;">
            <?php
            //if (right_granted('chartofaccount_manage'))
            {
                $get_val=$this->db->query("select * from ".get_school_db().".salary_voucher_settings where school_id=".$_SESSION['school_id']." and salary_type='gross_salary'")->result_array();
                ?>
                <div class="box-content">
                    <div class="panel-heading" style="background-color: #02658d;">
                        <div class="panel-title black2  hdng-clr">
                        <i class="entypo-plus-circled"></i>
                            <?php echo get_phrase('chart_of_account_title_for_gross_salary');?>
                            </div>
                    </div>
                    <form class="form-horizontal form-groups-bordered validate" action="<?php echo base_url();?>payroll/add_gross_salary_setting" method="post">

                    <!--COA while cash Receipt Start-->
                    <fieldset class="custom_legend">
                        <div class="form-group">
                            <label for="field-2" class="col-sm-12 control-label text-left"><?php echo get_phrase('gross_salary_coa');?> (debit) <span class="star">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control select2" name="gross_salary_coa" id="gross_salary_coa" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" required>
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
    
    <div class="col-sm-6" data-step="3" data-position='top' data-intro="you can set which coa will be debit while sallary booking for other allownces">
        <div style="padding: 20px 20px 30px 20px; border: 1px solid #eee;">
            <?php
            $get_val1=$this->db->query("select * from ".get_school_db().".salary_voucher_settings where school_id=".$_SESSION['school_id']." and salary_type='other_allownces'")->result_array();

            //if (right_granted('chartofaccount_manage'))
            {
                ?>
                <div class="box-content">
                    <div class="panel-heading" style="background-color: #02658d;">
                        <div class="panel-title black2 hdng-clr">
                            <i class="entypo-plus-circled">
                            </i> 
                            <?php echo get_phrase('chart_of_account_title_for_other_allownces');?>
                        </div>
                    </div>
                    <form class="form-horizontal form-groups-bordered validate" action="<?php echo base_url()?>payroll/add_other_allownces_setting" method="post" >
                        <!--COA while cash Payment Start-->
                        <fieldset class="custom_legend">
                            <div class="form-group">
                                <label for="field-2" class="col-sm-12  control-label text-left"><?php echo get_phrase('other_allownces_coa');?> (debit) <span class="star">*</span>   </label>
                                <div class="col-sm-12">
                                    <select class="form-control select2" name="other_allownces_coa" id="other_allownces_coa" required>
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
    
</div>

<div class="row">
    <div class="col-sm-6" data-step="4" data-position='top' data-intro="you can set which coa will be credit while sallary booking for Income Tax Payable">
        <div style="padding: 20px 20px 30px 20px; border: 1px solid #eee;">
            <?php
                $get_val=$this->db->query("select * from ".get_school_db().".salary_voucher_settings where school_id=".$_SESSION['school_id']." and salary_type ='income_tax_payable'")->result_array();
                ?>
                <div class="box-content">
                    <div class="panel-heading" style="background-color: #02658d;">
                        <div class="panel-title black2  hdng-clr">
                        <i class="entypo-plus-circled"></i>
                            <?php echo get_phrase('chart_of_account_title_for_income_tax_payable');?>
                            </div>
                    </div>
                    <form class="form-horizontal form-groups-bordered validate" action="<?php echo base_url();?>payroll/add_income_tax_payable_setting" method="post">
                        
                    <fieldset class="custom_legend">
                        <div class="form-group">
                            <label for="field-2" class="col-sm-12 control-label text-left"><?php echo get_phrase('income_tax_payable');?> (credit) <span class="star">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control select2" name="income_tax_payable_coa" id="income_tax_payable_coa" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" required>
                                    <option value=""><?php echo get_phrase('select');?></option>
                                    <?php
                                        echo coa_list_h($parent_id=0,$get_val[0]['coa_id'],0,0,$account_type);
                                    ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>

                    <div class="col-sm-12 mgt10">
                        <button id="btn_save" class="btn btn-primary pull-right" type="submit"><?php echo get_phrase('save');?></button>
                    </div>
                    </form>
                </div>
        </div>
    </div>
    
    <div class="col-sm-6" data-step="4" data-position='top' data-intro="you can set which coa will be credit while sallary booking for Income Tax Payable">
        <div style="padding: 20px 20px 30px 20px; border: 1px solid #eee;">
            <?php
                $get_val=$this->db->query("select * from ".get_school_db().".salary_voucher_settings where school_id=".$_SESSION['school_id']." and salary_type ='salaries_payable'")->result_array();
                ?>
                <div class="box-content">
                    <div class="panel-heading" style="background-color: #02658d;">
                        <div class="panel-title black2  hdng-clr">
                        <i class="entypo-plus-circled"></i>
                            <?php echo get_phrase('chart_of_account_title_for_salaries_payable');?>
                            </div>
                    </div>
                    <form class="form-horizontal form-groups-bordered validate" action="<?php echo base_url();?>payroll/add_salaries_payable_setting" method="post">
                        
                    <fieldset class="custom_legend">
                        <div class="form-group">
                            <label for="field-2" class="col-sm-12 control-label text-left"><?php echo get_phrase('salaries_payable');?> (credit) <span class="star">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control select2" name="salaries_payable_coa" id="salaries_payable_coa" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" required>
                                    <option value=""><?php echo get_phrase('select');?></option>
                                    <?php
                                        echo coa_list_h($parent_id=0,$get_val[0]['coa_id'],0,0,$account_type);
                                    ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>

                    <div class="col-sm-12 mgt10">
                        <button id="btn_save" class="btn btn-primary pull-right" type="submit"><?php echo get_phrase('save');?></button>
                    </div>
                    </form>
                </div>
        </div>
    </div>
</div>