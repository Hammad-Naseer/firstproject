<style>
    .entypo-plus-circled:before {
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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline" data-step="1" data-position='left' data-intro="miscellaneous setting">
            <?php echo get_phrase('miscellaneous_settings');?>
        </h3>
        <?php
        /*
        if (right_granted('chartofaccount_manage'))
        {

        ?>
        <a style="margin-right: 10px;" class="btn btn-primary pull-right" id="myBtn" href="#" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/chart_of_accounts/');">
                <i class=" white entypo-plus-circled" style="color:#FFF !important"></i>
                <?php echo get_phrase('Add Chart of Account Title');?>
        </a>
        <?php
        }
        */
        ?>
    </div>
</div>

<?php
$account_type = 1;
?>

<div class="row mgt35">
    <!--Arrears Drop down start-->
    <div class="col-sm-6" data-step="2" data-position='top' data-intro="you can set arrears debit & credit chart of account and challan form debit & credit chart of account, select account then press save button">
        <div style="padding: 20px 20px 30px 20px;border: 1px solid #EEE;">
            <?php
                if (right_granted('chartofaccount_manage'))
                {
                    $get_val=$this->db->query("select * from ".get_school_db().".misc_challan_coa_settings where school_id=".$_SESSION['school_id']." and type='arrears_coa'")->result_array();
            ?>
                <div class="box-content">
                    <div class="panel-heading" style="background-color: #eee;">
                        <div class="panel-title black2">
                            <i class="entypo-plus-circled"></i>  
                            <?php echo get_phrase('chart_of_account_title_for_arrears');?>
                        </div>
                    </div>
                    <div id="alert-success-arrears" class="alert alert-success alert-dismissable" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo get_phrase('arrears_saved_successfully'); ?>
                    </div>
                    <form class="form-horizontal form-groups-bordered validate">
                            <h4><b class="custom_legend"><?php echo get_phrase('COA'); ?> 
                            <?php echo get_phrase('while_arrears_generation');?>
                           :</b></h4>
                            <div class="form-group">
                                <label for="field-2" class="control-label"><?php echo get_phrase('Debit');?><span class="star">*</span></label>
                                    <!--<input class="form-control" value="<?php echo $get_val[0]['generate_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                                <select class="form-control select2" name="generate_dr_coa_id" id="generate_dr_coa_id">
                                    <option value=""><?php echo get_phrase('select');?></option>
                                    <?php
                                    echo coa_list_h(0,$get_val[0]['generate_dr_coa_id'] ,0 , 0 , $account_type);
                                   // coa_list_h(0,$edit_data[0]['issue_dr_coa_id'] ,0 , 0 , $account_type);
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="field-2" class="control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span>   </label>
                                    <!-- <input class="form-control" value="<?php echo $get_val[0]['generate_cr_coa_id']; ?>" name="misc_id_1" id="misc_id_1" type="hidden">-->
                                <select class="form-control select2" name="generate_cr_coa_id" id="generate_cr_coa_id">
                                    <option value=""><?php echo get_phrase('select');?></option>
                                    <?php echo coa_list_h($parent_id=0,$get_val[0]['generate_cr_coa_id'] ,0 , 0 , $account_type); ?>
                                </select>
                            </div>
                        <!--COA while generating challan form End-->

                        <!--COA while issuing challan form Start-->
                       <?php /* <fieldset class="custom_legend">
                            <legend class="custom_legend">COA while issuing challan form:</legend>
                            <div class="form-group">
                                <label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('Debit');?><span class="star">*</span></label>
                                <div class="col-sm-8">
                                    <!--<input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                                    <select class="form-control" name="issue_dr_coa_id" id="issue_dr_coa_id">
                                        <option value=""><?php echo get_phrase('select');?></option>
                                        <?php
                                        echo coa_list_h(0,$get_val[0]['issue_dr_coa_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span>   </label><div class="col-sm-8">
                                    <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_cr_coa_id']; ?>" name="misc_id_1" id="misc_id_1" type="hidden">-->
                                    <select class="form-control" name="issue_cr_coa_id" id="issue_cr_coa_id">
                                        <option value=""><?php echo get_phrase('select');?></option>
                                        <?php
                                        echo coa_list_h($parent_id=0,$get_val[0]['issue_cr_coa_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset> */ ?>
                        <!--COA while issuing challan form End-->

                        <!--COA while reciving challan form Start-->
                            <h4><b class="custom_legend"><?php echo get_phrase('COA'); ?> 
                                <?php echo get_phrase('while_receiving_challan_form');?>:</b>
                            </h4>
                            <div class="form-group">
                                <label for="field-2" class="control-label"><?php echo get_phrase('Debit');?>  <span class="star">*</span>   </label>
                                <select class="form-control select2" name="receive_dr_coa_id" id="receive_dr_coa_id">
                                    <option value=""><?php echo get_phrase('select');?></option>
                                    <?php echo coa_list_h($parent_id=0,$get_val[0]['receive_dr_coa_id'] ,0 , 0 , $account_type); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="field-2" class="control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span></label>
                                <select class="form-control select2" name="receive_cr_coa_id" id="receive_cr_coa_id">
                                    <option value=""><?php echo get_phrase('select');?></option>
                                    <?php echo coa_list_h($parent_id=0,$get_val[0]['receive_cr_coa_id'] ,0 , 0 , $account_type); ?>
                                </select>
                            </div>
                            
                        <!--COA while reciving challan form End-->

                        <!--COA while cancelling challan form Start-->
                       <?php /* <fieldset class="custom_legend">
                            <legend class="custom_legend">COA while Cancelling challan form:</legend>
                            <div class="form-group">
                                <label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Debit');?>  <span class="star">*</span>   </label>
                                <div class="col-sm-8">
                                    <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                                    <select class="form-control" name="cancel_dr_coa_id" id="cancel_dr_coa_id">
                                        <option value=""><?php echo get_phrase('select');?></option>
                                        <?php
                                        echo coa_list_h($parent_id=0,$get_val[0]['cancel_dr_coa_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span>   </label><div class="col-sm-8">
                                    <!--<input class="form-control" value="<?php echo $get_val[0]['issue_cr_coa_id']; ?>" name="misc_id_1" id="misc_id_1" type="hidden">-->
                                    <select class="form-control" name="cancel_cr_coa_id" id="cancel_cr_coa_id">
                                        <option value=""><?php echo get_phrase('select');?></option>
                                        <?php
                                        echo coa_list_h($parent_id=0,$get_val[0]['cancel_cr_coa_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset> */ ?>
                        <!--COA while cancelling challan form End-->

                        <div class="form-group">
                            <div class="float-right">
            					<button type="submit" class="modal_save_btn" id="btn_save">
            						<?php echo get_phrase('save');?>
            					</button>
            				</div>
                        </div>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>

    </div>
    <!--Arrears Drop down End-->
    <!--Fee Drop down start-->
    <div class="col-sm-6" data-step="3" data-position='top' data-intro="you can set late fee debit & credit chart of account, select account then press save button">
        <div style="padding: 20px 20px 30px 20px;border: 1px solid #EEE;">
        <?php
            $get_val1=$this->db->query("select * from ".get_school_db().".misc_challan_coa_settings where school_id=".$_SESSION['school_id']." and type='late_fee_fine_coa'")->result_array();
            if (right_granted('chartofaccount_manage'))
            {
        ?>
            <div class="box-content">
                <div class="panel-heading" style="    background-color: #eee;">
                    <div class="panel-title black2">
                        <i class="entypo-plus-circled"></i> 
                        <?php echo get_phrase('chart_of_account_title_for_late_fee_fine');?>    
                    </div>
                </div>

                    <div id="alert-success-fee" class="alert alert-success alert-dismissable" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo get_phrase('fee_saved_successfully'); ?>
                    </div>

                    <form class="form-horizontal form-groups-bordered validate">
                        <!--COA while issuing challan form Start-->
                        <?php /*<fieldset class="custom_legend">
                            <legend class="custom_legend">COA while issuing challan form:</legend>
                            <div class="form-group">
                                <label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Debit');?>  <span class="star">*</span>   </label>
                                <div class="col-sm-8">
                                    <!--<input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                                    <select class="form-control" name="issue_dr_coa_id_1" id="issue_dr_coa_id_1">
                                        <option value=""><?php echo get_phrase('select');?></option>
                                        <?php
                                        echo coa_list_h($parent_id=0,$get_val1[0]['issue_dr_coa_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span>   </label><div class="col-sm-8">
                                    <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_cr_coa_id']; ?>" name="misc_id_1" id="misc_id_1" type="hidden">-->
                                    <select class="form-control" name="issue_cr_coa_id_1" id="issue_cr_coa_id_1">
                                        <option value=""><?php echo get_phrase('select');?></option>
                                        <?php
                                        echo coa_list_h($parent_id=0,$get_val1[0]['issue_cr_coa_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>*/ ?>
                        <!--COA while issuing challan form End-->

                        <!--COA while reciving challan form Start-->
                            <h4><b class="custom_legend"><?php echo get_phrase('COA'); ?> 
                            <?php echo get_phrase('while_receiving_challan_form');?>
                            :</b></h4>
                            <div class="form-group">
                                <label for="field-2" class="control-label"><?php echo get_phrase('Debit');?>  <span class="star">*</span>   </label>
                                <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                                <select class="form-control select2" name="receive_dr_coa_id_1" id="receive_dr_coa_id_1">
                                    <option value=""><?php echo get_phrase('select');?></option>
                                    <?php
                                    echo coa_list_h($parent_id=0,$get_val1[0]['receive_dr_coa_id'] ,0 , 0 , $account_type);
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="field-2" class="control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span></label>
                                <!--<input class="form-control" value="<?php echo $get_val[0]['issue_cr_coa_id']; ?>" name="misc_id_1" id="misc_id_1" type="hidden">-->
                                <select class="form-control select2" name="receive_cr_coa_id_1" id="receive_cr_coa_id_1">
                                    <option value=""><?php echo get_phrase('select');?></option>
                                    <?php echo coa_list_h($parent_id=0,$get_val1[0]['receive_cr_coa_id'] ,0 , 0 , $account_type); ?>
                                </select>
                            </div>
                        <!--COA while reciving challan form End-->

                        <!--COA while cancelling challan form Start-->
                        <?php /*<fieldset class="custom_legend">
                            <legend class="custom_legend">COA while Cancelling challan form:</legend>
                            <div class="form-group">
                                <label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Debit');?>  <span class="star">*</span>   </label>
                                <div class="col-sm-8">
                                    <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                                    <select class="form-control" name="cancel_dr_coa_id_1" id="cancel_dr_coa_id_1">
                                        <option value=""><?php echo get_phrase('select');?></option>
                                        <?php
                                        echo coa_list_h($parent_id=0,$get_val1[0]['cancel_dr_coa_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span>   </label><div class="col-sm-8">
                                    <!--<input class="form-control" value="<?php echo $get_val[0]['issue_cr_coa_id']; ?>" name="misc_id_1" id="misc_id_1" type="hidden">-->
                                    <select class="form-control" name="cancel_cr_coa_id_1" id="cancel_cr_coa_id_1">
                                        <option value=""><?php echo get_phrase('select');?></option>
                                        <?php
                                        echo coa_list_h($parent_id=0,$get_val1[0]['cancel_cr_coa_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset> */ ?>
                        <!--COA while cancelling challan form End-->

                        <!--<div class="form-control">-->
                            <div class="float-right">
            					<button type="submit" class="modal_save_btn" id="btn_save1">
            						<?php echo get_phrase('save');?>
            					</button>
            				</div>
                        <!--</div>-->

                    </form>
                </div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#btn_save1").click(function(e) {
                            e.preventDefault();

                            var check = false;
                            /*var issue_dr_coa_id_1 = $('#issue_dr_coa_id_1').val();
                            var issue_cr_coa_id_1 = $('#issue_cr_coa_id_1').val();*/

                            var receive_dr_coa_id_1 = $('#receive_dr_coa_id_1').val();
                            var receive_cr_coa_id_1 = $('#receive_cr_coa_id_1').val();

                            /*var cancel_dr_coa_id_1 = $('#cancel_dr_coa_id_1').val();
                            var cancel_cr_coa_id_1 = $('#cancel_cr_coa_id_1').val();*/
                            var coa_id = 4;


                            $.ajax({
                                type: 'POST',
                                data: {
                                    receive_dr_coa_id_1: receive_dr_coa_id_1,
                                    receive_cr_coa_id_1: receive_cr_coa_id_1
                                    },
                                url: "<?php echo base_url(); ?>chart_of_account/save_arrears1",
                                dataType: "html",
                                success: function(response) {
                                    $("#alert-success-fee").show().delay(5000).fadeOut();
                                }
                            });


                            $('input[type=text],select').not("input[type='hidden']").each(function(key, element)
                            {

                                var v =  $.trim(this.value);
                                // alert(v.length);

                                if( $.trim($(this).val()).length == 0)
                                {
                                    check = true;
                                }
                            });

                            if(check == false)
                            {

                               // $('#btn_save1').attr('disabled','disabled');
                             //   alert('Add Depositor form submitted');
                            }


                        });
                    });

                </script>
                <?php
            }
            ?>



        </div>


    </div>
    <!--Fee Drop down End-->

</div>













<script type="text/javascript">
    $(document).ready(function() {
        $("#btn_save").click(function(e) {
            e.preventDefault();


            var check = false;


            var generate_dr_coa_id = $('#generate_dr_coa_id').val();
            var generate_cr_coa_id = $('#generate_cr_coa_id').val();

           /* var issue_dr_coa_id = $('#issue_dr_coa_id').val();
            var issue_cr_coa_id = $('#issue_cr_coa_id').val();*/

            var receive_dr_coa_id = $('#receive_dr_coa_id').val();
            var receive_cr_coa_id = $('#receive_cr_coa_id').val();

            /*var cancel_dr_coa_id = $('#cancel_dr_coa_id').val();
            var cancel_cr_coa_id = $('#cancel_cr_coa_id').val();*/

            $.ajax({
                type: 'POST',
                data: {
                    generate_dr_coa_id: generate_dr_coa_id,
                    generate_cr_coa_id: generate_cr_coa_id,
                    receive_dr_coa_id: receive_dr_coa_id,
                    receive_cr_coa_id: receive_cr_coa_id
                },
                url: "<?php echo base_url();?>chart_of_account/save_arrears",
                dataType: "html",
                success: function(response) {
                    $("#alert-success-arrears").show().delay(5000).fadeOut();
                }
            });


            $('input[type=text],select').not("input[type='hidden']").each(function(key, element)
            {

                var v =  $.trim(this.value);
                // alert(v.length);

                if( $.trim($(this).val()).length == 0)
                {
                    check = true;
                }
            });

            if(check == false)
            {

               // $('#btn_save').attr('disabled','disabled');
               // alert('Arrears saved');
            }


        });





    });
</script>


