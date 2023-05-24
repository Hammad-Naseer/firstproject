<style>
.table-sortable tbody tr {
/*cursor: move;*/
}
.table > tbody > tr > td {
     vertical-align: top;
}

button.modal_cancel_btn {
    position: relative;
    top: -5px;
    padding: 5px 4px 4px 6px;
    left: 6px;
}

</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php echo get_phrase('edit_bank_receipt_voucher');?>
        </h3>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
    <ul class="nav nav-tabs" role="tablist">
        <?php if($row_edit[0]['voucher_type'] == 1){ ?>
    	<li class="nav-item">
    		<a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Depositor Receipts</a>
    	</li>
    	<?php } ?>
    	<?php if($row_edit[0]['voucher_type'] == 2){ ?>
    	<li class="nav-item">
    		<a class="nav-link active" data-toggle="tab" href="#tabs-2" role="tab">Other Receipts</a>
    	</li>
    	<?php } ?>
    </ul><!-- Tab panes -->
    <?php if($row_edit[0]['voucher_type'] == 1){ ?>
    	<div class="tab-pane active" id="tabs-1" role="tabpanel">
            <?php echo form_open(base_url().'vouchers/bank_receipt_voucher_update' , array('id'=>'bank_receipt_form','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
        
            <input type="hidden" name="bank_receipt_id" value="<?php echo $row_edit[0]['bank_receipt_id'];?>">
            <input type="hidden" name="old_status" value="<?php echo $row_edit[0]['status'];?>">
            
            <div class="thisrow" style="padding:12px;">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <label><?php echo get_phrase('voucher_date');?></label>
                            <input class="form-control datepicker" type="text" name="voucher_date" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" value="<?php echo date_dash($row_edit[0]['voucher_date']);?>" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <label id="section_id_filter_selection"><?php echo get_phrase('depositor_details');?></label>
                            <select class="select2 form-control" name="depositor_id" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                            <option value="">
                            <?php echo get_phrase('select_depositor');?>  
                            </option>
                            <?php echo get_depositor_list($row_edit[0]['depositor_id']);?> 
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <label><?php echo get_phrase('voucher_number');?></label>
                            <input class="form-control" type="text" name="voucher_number" value="<?php echo $row_edit[0]['voucher_number'];?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row" style="background-color: #c0c0c04a;border-radius: 5px;border: 1px solid #CCC; margin-bottom: 10px;margin-left: auto;margin-right: auto;">
                <div class="col-md-12">
                    <!--<div class="col-md-12">-->
                        <table class="table table-bordered table-hover table-sortable mt-3 mb-4" id="tab_logic">
                            <thead>
                                <tr>
                                    <th style="width: 200px;"><?php echo get_phrase('method');?></th>
                                    <th style="width: 200px;"><?php echo get_phrase('deposit_slip');?> #</th>
                                    <th><?php echo get_phrase('description');?></th>
                                    <th style="width: 150px;"><?php echo get_phrase('deposit_bank');?></th>
                                    <th style="width: 170px;"><?php echo get_phrase('amount');?></th>
                                    <th style="width: 124px;"><i class="fa fa-paperclip fa-2" aria-hidden="true"></i></th>
                                    <th><?php echo get_phrase('action');?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $counter = 0;
                            $total_amount = 0;
                            foreach ($row_edit as $key => $value)
                            {
                                $total_amount += $value['amount'];
                            ?>
                            <tr id='addr<?php echo $counter;?>' data-id="<?php echo $counter;?>">
                                
                                <td data-name="method[]">
                                    <input class="form-control" type="hidden" name="bank_receipt_details_id[]" value="<?php echo $value['bank_receipt_details_id']?>">
                                    <select name="method[]" class="form-control" data-validate="required" data-message-required="Required">
                                        <option value=""><?php echo get_phrase('select_method');?></option>
                                        <?php echo cash_or_check($value['method']);?>
                                    </select>
                                    
                                </td>
                                <td data-name="deposit_slip_number[]">
                                    <input type="text" class="form-control" name="deposit_slip_number[]" placeholder="Deposit Slip #" value="<?php echo $value['deposit_slip_number']?>">
                                </td>
                                <td data-name="description[]">
                                    <textarea style="height: 50px;
                                    width: 100%;" placeholder="Description" class="form-control" name="description[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>"><?php echo $value['description']?>
                                    </textarea>
                                </td>
                                <td data-name="deposit_bank_id[]">
                                    <select name="deposit_bank_id[]" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                        <option value=""><?php echo get_phrase('select_bank');?></option>
                                        <?php echo get_bank_list($value['deposit_bank_id']);?>
                                    </select>
                                </td>
                                <td data-name="amount[]">
                                    <input type="number" class="form-control" class="amount" name="amount[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="<?php echo $value['amount']?>">
                                </td>
                                <td data-name="attachment[]">
                                <?php
                                $path=system_path($value['attachment'], 'bank_receipt');
                                if (file_exists($path))
                                {  
                                ?>
                                    <span style="float: left; display: inline-block;" data-toggle="tooltip" title="click to view">
                                        <a href="<?php echo display_link($value['attachment'], 'bank_receipt');
                                            ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    </span>
                                <?php
                                }
                                ?>
                                    <span class="upload-btn-wrapper" style="position: relative;overflow: hidden;display: inline-block;">
                                      <button class="btn_upload" style="border: 1px solid gray;color: gray;background-color: white !important;padding: 3px 10px;border-radius: 5px;font-size: 12px;">Upload a file</button>
                                      <input type="file" name="attachment[]" style="font-size: 100px;position: absolute;left: 0;top: 0;opacity: 0;" />
                                    </span>
                                    <span style="color: red;" id="img_g_msg"></span>
            
                                </td>
                                <td>
                                    <button class='modal_cancel_btn row-remove' id="rmv<?php echo $counter?>" onclick="remove_row('rmv<?php echo $counter?>')">
                                        <i class='fas fa-times' style='color: white !important;'></i>
                                    </button>
                                </td>
                            </tr>
                                <?php
                                $counter++;
                                }
                                ?>
                                <tr id='addr<?php echo $counter;?>' data-id="<?php echo $counter;?>" class="hidden">
                                <td data-name="method[]">
                                    <select name="method[]" class="form-control" data-validate="required" data-message-required="Required">
                                        <option value=""><?php echo get_phrase('select_method');?></option>
                                        <?php echo cash_or_check();?>
                                    </select>
                                    
                                </td>
                                <td data-name="deposit_slip_number[]">
                                    <input type="text" class="form-control" name="deposit_slip_number[]" placeholder="Deposit Slip #" value="">
                                </td>
                                <td data-name="description[]">
                                    <textarea style="height: 50px;
                                    width: 100%;" placeholder="Description" class="form-control" name="description[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    </textarea>
                                </td>
                                <td data-name="deposit_bank_id[]">
                                    <select name="deposit_bank_id[]" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                        <option value=""><?php echo get_phrase('select_bank');?></option>
                                        <?php echo get_bank_list();?>
                                    </select>
                                </td>
                                <td data-name="amount[]">
                                    <input type="number" class="form-control" class="amount" name="amount[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="">
                                </td>
                                <td data-name="attachment[]">
                                    <span class="upload-btn-wrapper" style="position: relative;overflow: hidden;display: inline-block;">
                                      <button class="btn_upload" style="border: 1px solid gray;color: gray;background-color: white !important;padding: 3px 10px;border-radius: 5px;font-size: 12px;">Upload a file</button>
                                      <input type="file" name="attachment[]" style="font-size: 100px;position: absolute;left: 0;top: 0;opacity: 0;" />
                                    </span>
                                    <span style="color: red;" id="img_g_msg"></span>
                                </td>
                                <td>
                                    <button class='btn btn-danger glyphicon glyphicon-remove row-remove' id="rmv<?php echo $counter?>" onclick="remove_row('rmv<?php echo $counter?>')"></button>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                    <!--</div>-->
                    <a id="add_row" class="modal_save_btn pull-right" style="margin-bottom: 15px;"><i class="fas fa-plus" aria-hidden="true" style="color: white;"></i></a> 
                </div>
            </div>
            
            <div class="thisrow" style="padding:12px;">
                <div class="row">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo get_phrase('voucher_status');?></label>
                            <select name="status" class="form-control">
                                <option value="">
                                    <?php echo get_phrase('select');?>
                                </option>
                                <?php echo status_list($row_edit[0]['status'],1);?>
                            </select>
                        </div>    
                        <div class="form-group">
                            <input type="submit" name="submit" value="submit" class="modal_save_btn pt-2 float-right">
                        </div>
                       <!--  <label><?php echo get_phrase('total_amount');?></label>
                        <input type="text" class="form-control" id="total" name="total" value="<?php echo $total_amount;?>"> -->
                    </div>
                </div>
            </div>
        
            <?php echo form_close();?>
        </div> 
    <?php } ?>
    <?php if($row_edit[0]['voucher_type'] == 2){ ?>
    	<div class="tab-pane active" id="tabs-2" role="tabpanel">
    	    <?php echo form_open(base_url().'vouchers/other_bank_receipt_voucher_update' , array('id'=>'other_bank_receipt_voucher_update','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
        
            <input type="hidden" name="bank_receipt_id" value="<?php echo $row_edit[0]['bank_receipt_id'];?>">
            <input type="hidden" name="old_status" value="<?php echo $row_edit[0]['status'];?>">
            
            <div class="thisrow" style="padding:12px;">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <label><?php echo get_phrase('voucher_date');?></label>
                            <input class="form-control datepicker" type="text" name="voucher_date" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" value="<?php echo date_dash($row_edit[0]['voucher_date']);?>" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <label id="section_id_filter_selection"><?php echo get_phrase('coa_id');?></label>
                            <select class="select2 form-control" name="credit_coa_id" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                <option value=""><?php echo get_phrase('select_coa');?></option>
                                <?php echo coa_list_h(0,$row_edit[0]['credit_coa_id'],0,0, $account_type,1); ?>
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <label><?php echo get_phrase('voucher_number');?></label>
                            <input class="form-control" type="text" name="voucher_number" value="<?php echo $row_edit[0]['voucher_number'];?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row" style="background-color: #c0c0c04a;border-radius: 5px;border: 1px solid #CCC; margin-bottom: 10px;margin-left: auto;margin-right: auto;">
                <div class="col-md-12">
                    <!--<div class="col-md-12">-->
                        <table class="table table-bordered table-hover table-sortable mt-3 mb-4" id="tab_logic">
                            <thead>
                                <tr>
                                    <th style="width: 200px;"><?php echo get_phrase('method');?></th>
                                    <th style="width: 200px;"><?php echo get_phrase('deposit_slip');?> #</th>
                                    <th><?php echo get_phrase('description');?></th>
                                    <th style="width: 150px;"><?php echo get_phrase('deposit_bank');?></th>
                                    <th style="width: 170px;"><?php echo get_phrase('amount');?></th>
                                    <th style="width: 124px;"><i class="fa fa-paperclip fa-2" aria-hidden="true"></i></th>
                                    <th><?php echo get_phrase('action');?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $counter = 0;
                            $total_amount = 0;
                            foreach ($row_edit as $key => $value)
                            {
                                $total_amount += $value['amount'];
                            ?>
                            <tr id='addr<?php echo $counter;?>' data-id="<?php echo $counter;?>">
                                
                                <td data-name="method[]">
                                    <input class="form-control" type="hidden" name="bank_receipt_details_id[]" value="<?php echo $value['bank_receipt_details_id']?>">
                                    <select name="method[]" class="form-control" data-validate="required" data-message-required="Required">
                                        <option value=""><?php echo get_phrase('select_method');?></option>
                                        <?php echo cash_or_check($value['method']);?>
                                    </select>
                                    
                                </td>
                                <td data-name="deposit_slip_number[]">
                                    <input type="text" class="form-control" name="deposit_slip_number[]" placeholder="Deposit Slip #" value="<?php echo $value['deposit_slip_number']?>">
                                </td>
                                <td data-name="description[]">
                                    <textarea style="height: 50px;
                                    width: 100%;" placeholder="Description" class="form-control" name="description[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>"><?php echo $value['description']?>
                                    </textarea>
                                </td>
                                <td data-name="deposit_bank_id[]">
                                    <select name="deposit_bank_id[]" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                        <option value=""><?php echo get_phrase('select_bank');?></option>
                                        <?php echo get_bank_list($value['deposit_bank_id']);?>
                                    </select>
                                </td>
                                <td data-name="amount[]">
                                    <input type="number" class="form-control" class="amount" name="amount[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="<?php echo $value['amount']?>">
                                </td>
                                <td data-name="attachment[]">
                                <?php
                                $path=system_path($value['attachment'], 'bank_receipt');
                                if (file_exists($path))
                                {  
                                ?>
                                    <span style="float: left; display: inline-block;" data-toggle="tooltip" title="click to view">
                                        <a href="<?php echo display_link($value['attachment'], 'bank_receipt');
                                            ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    </span>
                                <?php
                                }
                                ?>
                                    <span class="upload-btn-wrapper" style="position: relative;overflow: hidden;display: inline-block;">
                                      <button class="btn_upload" style="border: 1px solid gray;color: gray;background-color: white !important;padding: 3px 10px;border-radius: 5px;font-size: 12px;">Upload a file</button>
                                      <input type="file" name="attachment[]" style="font-size: 100px;position: absolute;left: 0;top: 0;opacity: 0;" />
                                    </span>
                                    <span style="color: red;" id="img_g_msg"></span>
            
                                </td>
                                <td>
                                    <button class='modal_cancel_btn row-remove' id="rmv<?php echo $counter?>" onclick="remove_row('rmv<?php echo $counter?>')">
                                        <i class='fas fa-times' style='color: white !important;'></i>
                                    </button>
                                </td>
                            </tr>
                                <?php
                                $counter++;
                                }
                                ?>
                                <tr id='addr<?php echo $counter;?>' data-id="<?php echo $counter;?>" class="hidden">
                                <td data-name="method[]">
                                    <select name="method[]" class="form-control" data-validate="required" data-message-required="Required">
                                        <option value=""><?php echo get_phrase('select_method');?></option>
                                        <?php echo cash_or_check();?>
                                    </select>
                                    
                                </td>
                                <td data-name="deposit_slip_number[]">
                                    <input type="text" class="form-control" name="deposit_slip_number[]" placeholder="Deposit Slip #" value="">
                                </td>
                                <td data-name="description[]">
                                    <textarea style="height: 50px;
                                    width: 100%;" placeholder="Description" class="form-control" name="description[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    </textarea>
                                </td>
                                <td data-name="deposit_bank_id[]">
                                    <select name="deposit_bank_id[]" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                        <option value=""><?php echo get_phrase('select_bank');?></option>
                                        <?php echo get_bank_list();?>
                                    </select>
                                </td>
                                <td data-name="amount[]">
                                    <input type="number" class="form-control" class="amount" name="amount[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="">
                                </td>
                                <td data-name="attachment[]">
                                    <span class="upload-btn-wrapper" style="position: relative;overflow: hidden;display: inline-block;">
                                      <button class="btn_upload" style="border: 1px solid gray;color: gray;background-color: white !important;padding: 3px 10px;border-radius: 5px;font-size: 12px;">Upload a file</button>
                                      <input type="file" name="attachment[]" style="font-size: 100px;position: absolute;left: 0;top: 0;opacity: 0;" />
                                    </span>
                                    <span style="color: red;" id="img_g_msg"></span>
                                </td>
                                <td>
                                    <button class='btn btn-danger glyphicon glyphicon-remove row-remove' id="rmv<?php echo $counter?>" onclick="remove_row('rmv<?php echo $counter?>')"></button>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                    <!--</div>-->
                    <a id="add_row" class="modal_save_btn pull-right" style="margin-bottom: 15px;"><i class="fa fa-plus" aria-hidden="true" style="color: white;"></i></a> 
                </div>
            </div>
            
            <div class="thisrow" style="padding:12px;">
                <div class="row">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo get_phrase('voucher_status');?></label>
                            <select name="status" class="form-control">
                                <option value="">
                                    <?php echo get_phrase('select');?>
                                </option>
                                <?php echo status_list($row_edit[0]['status'],1);?>
                            </select>
                        </div>    
                        <div class="form-group">
                            <input type="submit" name="submit" value="submit" class="modal_save_btn pt-2 float-right">
                        </div>
                       <!--  <label><?php echo get_phrase('total_amount');?></label>
                        <input type="text" class="form-control" id="total" name="total" value="<?php echo $total_amount;?>"> -->
                    </div>
                </div>
            </div>
        
            <?php echo form_close();?>
    	</div>
    <?php } ?>	
</div>
<script>


    $("#add_row").on("click", function() {
        // Dynamic Rows Code
        
        // Get max row id and set new id
        var newid = <?php echo ($counter);?>;
        $.each($("#tab_logic tr"), function() {
            if (parseInt($(this).data("id")) > newid) {
                newid = parseInt($(this).data("id"));
            }
        });
        newid++;
        
        
        var tr = $("<tr></tr>", {
            id: "addr"+newid,
            "data-id": newid
        });
        
        // loop through each td and create new elements with name of newid
        $.each($("#tab_logic tbody tr:nth(<?php echo $counter?>) td"), function() {
            var cur_td = $(this);
            
            var children = cur_td.children();
            
            // add new td and element if it has a nane
            if ($(this).data("name") != undefined) {
                var td = $("<td></td>", {
                    "data-name": $(cur_td).data("name")
                });
                
                var c = $(cur_td).find($(children[0]).prop('tagName')).clone().val("");
                c.attr("name", $(cur_td).data("name"));
                c.appendTo($(td));
                td.appendTo($(tr));
            } else {
                // var td = $("<td></td>", {
                //     'text': $('#tab_logic tr').length
                // }).appendTo($(tr));
            }
        });
        
        // add delete button and td
        
        $("<td></td>").append(
            $("<button class='modal_cancel_btn row-remove'><i class='fas fa-times' style='color: white !important;'></i></button>")
                .click(function() {
                    $(this).closest("tr").remove();
                })
        ).appendTo($(tr));
        
        
        // add the new row
        $(tr).appendTo($('#tab_logic'));
        
        $(tr).find("td button.row-remove").on("click", function() {
             $(this).closest("tr").remove();
        });
});

    $("#add_row").trigger("click");
   
// // we used jQuery'keyup'to trigger the computation as the user type

// $('.amount').keyup(function () {
//         var sum = 0;
//         $('.amount').each(function() {
//             sum += Number($(this).val());
//         });
//         $('#total').html(sum);
// });


function remove_row(remove_id){
    $('#'+remove_id).closest("tr").remove();
}
$(".page-container").addClass("sidebar-collapsed");

 </script>


