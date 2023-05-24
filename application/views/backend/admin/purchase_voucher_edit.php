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
            <?php echo get_phrase('edit_purchase_voucher_voucher');?>
        </h3>
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12">
<?php echo form_open(base_url().'vouchers/purchase_voucher_update' , array('id'=>'purchase_voucher_form','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>

<input type="hidden" name="purchase_voucher_id" value="<?php echo $row_edit[0]['purchase_voucher_id'];?>">
<input type="hidden" name="old_status" value="<?php echo $row_edit[0]['status'];?>">

<div class="thisrow" style="padding:12px;">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="col-md-4 col-lg-4 col-sm-4">
                <label><?php echo get_phrase('voucher_date');?></label>
                <input class="form-control datepicker" type="text" name="voucher_date" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" value="<?php echo date_dash($row_edit[0]['voucher_date']);?>" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4">
                <label id="section_id_filter_selection"><?php echo get_phrase('supplier_details');?></label>
                <select class="select2 form-control" name="supplier_id" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                    <option value=""><?php echo get_phrase('select_supplier'); ?></option>
                    <?php echo get_supplier_list($row_edit[0]['supplier_id']);?>
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
    
        <!--<div class="col-md-12">-->
            <table class="table table-bordered table-hover table-sortable mt-3 mb-4" id="tab_logic">
                <thead>
                    <tr>
                        <th style="width: 150px;">
                        <?php echo get_phrase('bill_no');?> #</th>
                        <th><?php echo get_phrase('description');?></th>
                        <th style="width: 100px;"><?php echo get_phrase('quantity');?></th>
                        <th style="width: 100px;"><?php echo get_phrase('amount');?></th>
                        <th style="width: 170px;"><?php echo get_phrase('debit_COA');?></th>
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
                    <td data-name="bill_number[]">
                        <input type="hidden" name="purchase_voucher_details_id[]" value="<?php echo $value['purchase_voucher_details_id']; ?>">
                        <input type="text" class="form-control" name="bill_number[]" placeholder="Cheque Number #" value="<?php echo $value['bill_number']?>">
                    </td>
                    <td data-name="description[]">
                        <textarea placeholder="Description" class="form-control" name="description[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>"><?php echo $value['description']?>
                        </textarea>
                    </td>
                    <td data-name="quantity[]">
                        <input type="number" class="quantity form-control" name="quantity[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Quantity" value="<?php echo $value['qty']?>">
                    </td>
                    <td data-name="amount[]">
                        <input type="number" class="form-control" class="amount" name="amount[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="<?php echo $value['amount']?>">
                    </td>
                    <td data-name="debit_coa_id[]">
                        <select name="debit_coa_id[]" id="eselect2" class="form-control select2"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select_coa');?></option>
                            <?php coa_list_h(0,$value['debit_coa_id'],0,0, $account_type); ?>
                        </select>
                    </td>
                    <td data-name="attachment[]">
                    <?php
                    $path=system_path($value['attachment'], 'purchase_voucher');
                    if (file_exists($path))
                    {  
                    ?>
                        <span style="float: left; display: inline-block;" data-toggle="tooltip" title="click to view">
                            <a href="<?php echo display_link($value['attachment'], 'purchase_voucher');
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
                    <td data-name="remove_tr[]">
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
                        <td data-name="bill_number[]">
                            <input type="text" class="form-control" name="bill_number[]" placeholder="Bill Number #" value="">
                        </td>
                        <td data-name="description[]">
                            <textarea placeholder="Description" class="form-control" name="description[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                            </textarea>
                        </td>
                        <td data-name="quantity[]">
                            <input type="number" class="quantity form-control" name="quantity[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Quantity" value="">
                        </td>
                        <td data-name="amount[]">
                            <input type="number" class="form-control" class="amount" name="amount[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="">
                        </td>
                        <td data-name="debit_coa_id[]">
                            <select name="debit_coa_id[]" id="select2" class="form-control"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                <option value=""><?php echo get_phrase('select_coa');?></option>
                                <?php coa_list_h(0,0,0 , 0 , $account_type); ?>
                            </select>
                        </td>
                        <td data-name="attachment[]">
                            <span class="upload-btn-wrapper" style="position: relative;overflow: hidden;display: inline-block;">
                              <button class="btn_upload" style="border: 1px solid gray;color: gray;background-color: white !important;padding: 3px 10px;border-radius: 5px;font-size: 12px;">Upload a file</button>
                              <input type="file" name="attachment[]" style="font-size: 100px;position: absolute;left: 0;top: 0;opacity: 0;" />
                            </span>
                            <span style="color: red;" id="img_g_msg"></span>
                        </td>
                        <td data-name="remove_tr[]">
                            <button class='modal_cancel_btn row-remove' id="rmv<?php echo $counter?>" onclick="remove_row('rmv<?php echo $counter?>')">
                                <i class='fas fa-times' style='color: white !important;'></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        <!--</div>-->
    <div class="col-md-12">
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

<!--<p>-->
<!--    <span style="color: green;"> <?php //echo get_phrase('allowed_file_size');?> :  2 MB(2048 kbs), <?php //echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx</span>-->
<!--</p>-->
<?php echo form_close();?>
</div>
<script>

    var tr;
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
                var td = $("<td></td>", {
                    'text': $('#tab_logic tr').length
                }).appendTo($(tr));
            }
        });
        
        // add delete button and td
        //<button class='modal_cancel_btn row-remove'><i class='fas fa-times' style='color: white !important;'></i></button>
        // $("<td></td>").append(
        //     $("")
        //         // .click(function() {
        //         //     $(this).closest("tr").remove();
        //         // })
        // ).appendTo($(tr));
        
        
        // add the new row
        $(tr).appendTo($('#tab_logic'));
        $(tr).find('td > #select2').select2();
        
        $(tr).find("td button.row-remove").on("click", function() {
             $(this).closest("tr").remove();
        });
});

    // $("#add_row").trigger("click");
   
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


