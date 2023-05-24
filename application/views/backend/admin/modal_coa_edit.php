<?php
get_instance()->load->helper('coa');

$school_id=$_SESSION['school_id'];
if(preg_match("/\/(\d+)$/",$_SERVER['REQUEST_URI'],$matches)){
    $id=$matches[1];
   /* $edit_data=$this->db->get_where(get_school_db().'.chart_of_accounts' , array('coa_id' => $id,'school_id'=>$school_id) )->result_array();*/

    $edit_data_str = "select coa.* from ".get_school_db().".chart_of_accounts as coa
                          Inner join ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                          WHERE s_coa.school_id= $school_id
                          AND coa.coa_id = $id";
    $edit_data = $this->db->query($edit_data_str)->result_array();

    $edit_data_type =$this->db->get_where(get_school_db().'.chart_of_account_types' , array('coa_id' => $id/*,'school_id'=>$school_id*/) )->result_array();

}
if($edit_data){
    $url='chart_of_account/coa/edit/';

}
else{
    $url='chart_of_account/coa/create/';
}
//populate parent head
//$this->db->select('coa_id,account_head');
//$this->db->from(get_school_db().'.chart_of_accounts');
//$parent_id_array=$this->db->get()->result_array();

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title black2">
                    <i class="entypo-plus-circled"></i>
                    <?php echo get_phrase('edit_chart_of_account');?>
                </div>
            </div>
            <div class="panel-body">
                <?php //print_r($edit_data); ?>
                <?php echo form_open(base_url().$url , array('id'=>'edit_chart_of_account','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                <input type="hidden" id="coa_id"  name="coa_id" value="<?php echo $edit_data[0]['coa_id']?>" >
                <!--Parent Head Start-->
                <?php /*  <div class="form-group">
					<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('Parent Head');?></label>
					<div class="col-sm-8">
						
                        
                       if($edit_data[0]['parent_id'] == 0) {
								?>
                                <label for="field-1" class="col-sm-8 control-label">None</label>
                                <?php
								} else {
								 $parent_edit_data = $this->db->get_where(get_school_db().'.chart_of_accounts' , array('coa_id' => $edit_data[0]['parent_id'],'school_id'=>$school_id) )->result_array();
								 //print_r($parent_edit_data);
								 ?>
                                 <label for="field-1" class="col-sm-8 control-label"><?php echo $parent_edit_data[0]['account_head']."<i class='fa myarrow fa-arrows-h' aria-hidden='true'></i>".$parent_edit_data[0]['account_number'];?></label>
                                 <?php
								}
                        
                        
					</div>
				</div>*/ ?>
                <!--Parent head End-->



                <?php
                if($edit_data[0]['parent_id'] > 0)
                {
                    ?>
                    <div class="form-group">
                        <label for="field-2" class="control-label"><?php echo get_phrase('parent_head');?>  <span class="star">*</span>   </label>
                        <select name="parent_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select');?></option>
                            <?php
                            coa_list_h(0,$edit_data[0]['parent_id'] ,0 , 0 , $account_type=0,$edit_data[0]['coa_id']); ?>
                        </select>
                    </div>

                    <?php
                }
                else
                {
                    ?>
                    <input type="hidden"  name="parent_id" value="0" >
                    <?php
                }
                ?>

                <!--Account Title start-->
                <div class="form-group">
                    <label for="field-1" class="control-label"><?php echo get_phrase('account_title');?><span class="star">*</span></label>
                    <input maxlength="250" type="text" class="form-control" name="account_title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php if($edit_data[0]['account_head'])echo $edit_data[0]['account_head']?>" autofocus>
                </div>
                <!--Account Title End-->

                <!--Account Type start-->
                <div class="form-group">
                    <label for="field-2" class="control-label"><?php echo get_phrase('account_type');?><span class="star">*</span></label>
                    <?php
                       /* $options = array(
                        '' => get_phrase('select_account_type'),
                        '1' => get_phrase('credit'),
                        '2' => get_phrase('Debit')
                        );
                        $class = 'class="form-control"';
                        echo form_dropdown('account_type',$options,$edit_data[0]['account_type'] , $class);*/
                    ?>
                    <select name="account_type" class="form-control" required>
                        <?php echo debit_credit_optoin($edit_data[0]['account_type']); ?>
                    </select>
                </div>
                <!--Account Type End-->

                <!--Account Number Start-->
                <div class="form-group">
                    <label for="field-2" class="control-label"><?php echo get_phrase('account_number'); ?> / <?php echo get_phrase('code');?>
                    <span class="star">*</span></label>
                    <input maxlength="100" type="text" class="form-control" name="account_number" id="account_number" value="<?php if($edit_data[0]['account_number'])echo $edit_data[0]['account_number']?>" data-start-view="2">
                </div>
                <!--Account Number end-->

                <!--Status code start-->
                <div class="form-group">
                    <label for="field-2" class="control-label"><?php echo get_phrase('Status');?></label>
                    <?php
                    if($_SESSION['user_login']==1)
                    { ?>
                    <select name="status" class="form-control" required>
                        <?php
                        echo coa_status_option($edit_data[0]['status']);
                        }else{
                            $hd_var=0;
                        }
                        ?>
                    </select>
                </div>
                <!--Status code End-->

                <!--Is Actibe Start-->
                <div class="form-group">
                    <label for="field-2" class="control-label"><?php echo get_phrase('is_active');?></label>
                    <?php /*$options = array(
                        '2'         => 'Yes',
                        '1'           => 'No',
                    );
                    $options = array('' => get_phrase('select_status'),'1'=>'Yes' , '0'=>'No');
                    $class = 'class="form-control"';
                    $selected = $edit_data[0]['is_active'];
                    echo form_dropdown('is_active', $options, $selected , $class);*/

                    ?>
                    <select name="is_active" class="form-control" required>
                        <?php echo debit_credit_active_optoin($edit_data[0]['is_active']); ?>
                    </select>
                </div>
                <!--Is Active End-->



                <!--- chart of account types start -->
                <div class="form-group">
                    <fieldset class="custom_legend">
                        <legend class="custom_legend">
                            <?php echo get_phrase('areas_where_this_COA_title_can_be_used');?>
                            :</legend>
                        <!--<div class="col-md-12">-->
                            <div class="form-check form-check-inline" style="float:left;">
                                <label class="form-check-label">
                                    <?php
                                    foreach($edit_data_type as $type_value) {
                                        $coa_type_array[$type_value['coa_type']] = $type_value['coa_type'];
                                    }
                                    ?>
                                    <input name="coa_type[]" class="form-check-input" type="checkbox" id="inlineCheckbox1"
                                           value="1" <?php if(array_search(1,$coa_type_array)){ ?> checked <?php } ?> > <?php echo get_phrase('fee');?>
                                </label>
                            </div>
                            <div class="form-check form-check-inline" style="float:left;">
                                <label class="form-check-label">
                                    <input name="coa_type[]" class="form-check-input" type="checkbox" id="inlineCheckbox2"
                                           value="2" <?php if(array_search(2,$coa_type_array)){ ?> checked <?php } ?>> <?php echo get_phrase('discount');?>
                                </label>
                            </div>
                            <div class="form-check form-check-inline" style="float:left;">
                                <label class="form-check-label">
                                    <input name="coa_type[]" class="form-check-input" type="checkbox" id="inlineCheckbox3"
                                           value="3" <?php if(array_search(3,$coa_type_array)){ ?> checked <?php } ?>> <?php echo get_phrase('debitor');?>
                                </label>
                            </div>
                            <div class="form-check form-check-inline" style="float:left;">
                                <label class="form-check-label">
                                    <input name="coa_type[]" class="form-check-input" type="checkbox" id="inlineCheckbox3"
                                           value="4" <?php if(array_search(4,$coa_type_array)){ ?> checked <?php } ?>> <?php echo get_phrase('creditor');?>
                                </label>
                            </div>
                            <div class="form-check form-check-inline" style="float:left;">
                                <label class="form-check-label">
                                    <input name="coa_type[]" class="form-check-input" type="checkbox" id="inlineCheckbox3"
                                           value="5" <?php if(array_search(5,$coa_type_array)){ ?> checked <?php } ?>><?php echo get_phrase('payment_detail');?>
                                </label>
                            </div>
                        <!--</div>-->
                    </fieldset>
                </div>

                <!--- chart of account types End -->






                <!--Save Charts of acount start-->
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
                <!--Save Charts of Account End-->
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<script>
    $('#account_number').change(function(){


        var account_number=$(this).val();
        var coa_id=$('#coa_id').val();
       // alert(coa_id);

        $('#message').remove();
        $('#loader').remove();
        $('#account_number').after("<div id='loader' class='loader_small'></div>")

        $.ajax({
            type: 'POST',
            data: {account_number:account_number},
            url: "<?php echo base_url();?>chart_of_account/account_number_val/"+coa_id+"",
            dataType: "html",
            success: function(response) {
                if($.trim(response)=="no"){
                   // alert('hi');
                    $('#account_number').before('<span id="message"><?php echo get_phrase('account_number_already_used'); ?></span>');
                   // $('#btn_save').attr('disabled','true');
                    $('#loader').remove();

                }else{
                 //   $('#btn_save').removeAttr('disabled');
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
