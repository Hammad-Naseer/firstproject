<?php
//error_reporting(E_ALL);

$school_id=$_SESSION['school_id'];

$edit_data=$this->db->get_where(get_school_db().'.account_transection' , array('transection_id' => $param2,'school_id'=>$school_id))->result_array();

?>

    <div class="row">
        <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title black black2" >
                    <i class="entypo-plus-circled"></i>
                    <?php echo get_phrase('add_new_transaction');?>
                </div>
            </div>
            <div class="panel-body">

                <?php echo form_open(base_url().'transection_account/account_transection/add_edit/' , array('id'=>'transection_add',
                    'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>



                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('type');?></label>

                    <div class="col-sm-8">
                        <?php

                        echo type("type","form-control", $edit_data[0]['type'])

                        ?>
                    </div>
                </div>



                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('title');?></label>

                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['title'];?>">




                        <input type="hidden" name="transection_id" value="<?php echo $edit_data[0]['transection_id'];   ?>">




                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('chart_of_account');?></label>

                    <div class="col-sm-8">
                        <select name="coa_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select');?></option>

                            <?php

                            coa_list_h(0,$edit_data[0]['coa_id'],0,0,0); ?>

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('voucher_no');?></label>

                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="voucher_num" value="<?php echo $edit_data[0]['voucher_num']; ?>" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('method');?></label>

                    <div class="col-sm-8">
                        <?php

                        echo Method("method","form-control",$edit_data[0]['method']);

                        ?>

                    </div>
                </div>


                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('amount');?></label>

                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="amount" value="<?php echo $edit_data[0]['amount']; ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('date');?></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control datepicker" name="date" value="<?php echo date_dash($edit_data[0]['date']); ?>" data-format="dd/mm/yyyy" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('detail');?></label>
                    <div class="col-sm-8">
                        <textarea name="detail" class="form-control" ><?php echo $edit_data[0]['detail']; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('receipt_num');?></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="receipt_num" value="<?php echo $edit_data[0]['receipt_num']; ?>" >
                    </div>
                </div>



                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('action');?></label>
                    <div class="col-sm-8">
                        <?php

                        echo isprocessed('isprocessed',"form-control",$edit_data[0]['isprocessed'])

                        ?>


                        <span style="color: red;"><?php echo get_phrase('note');?> : "<?php echo get_phrase('submit');?>" <?php echo get_phrase('status_cannot_be_modified');?>, "<?php echo get_phrase('save');?>" <?php echo get_phrase('status_can_be_modified');?></span>

                    </div>


                </div>



                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('');?></label>

                    <div class="col-sm-8">



                        <button type="submit" class="btn btn-default"><?php echo get_phrase('save');?></button>


                    </div>
                </div>

                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>