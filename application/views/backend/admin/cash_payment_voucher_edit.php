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
            <?php echo get_phrase('edit_cash_payment_voucher');?>
        </h3>
    </div>
</div>

<div class="col-md-12">
    <?php echo form_open(base_url().'vouchers/cash_payment_voucher_update' , array('id'=>'cash_payment_form','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
    <input type="hidden" name="cash_payment_id" value="<?php echo $row_edit[0]['cash_payment_id'];?>">
    <div class="thisrow" style="padding:12px;">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="col-md-4 col-lg-4 col-sm-4">
                    <label><?php echo get_phrase('voucher_date');?></label>
                    <input class="form-control datepicker" type="text" name="voucher_date" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" value="<?php echo date_dash($row_edit[0]['voucher_date']);?>" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                </div>
                <div class="col-md-4 col-lg-4 col-sm-4">
                    <label><?php echo get_phrase('voucher_number');?></label>
                    <input class="form-control" type="text" name="voucher_number" value="<?php echo $row_edit[0]['voucher_number'];?>" readonly>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-4">
                    <h4 style="margin-top: 34px;" class="text-center">Available Balance:
                    <b><?php 
                        $get_cash_voucher_coa = get_cash_voucher_coa_settings('Payment');
                        echo "<span id='balance'>".get_coa_cacl_balance($get_cash_voucher_coa)."</span>";
                        echo "<input type='hidden' id='balance_hidden' value='".get_coa_cacl_balance($get_cash_voucher_coa)."'>";
                    ?>
                        <input type='hidden' id='sum_amount'>
                    </b>
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row" style="background-color: #c0c0c04a;border: 1px solid #CCC; margin-bottom: 10px;">
                <table class="table table-bordered table-hover table-sortable mt-4 mb-4" id="tab_logic">
                    <thead>
                        <tr>
                            <th><?php echo get_phrase('Debit_COA');?></th>
                            <th><?php echo get_phrase('description');?></th>
                            <th  style="width: 170px;"><?php echo get_phrase('amount');?></th>
                            <th style="width: 124px;">Deposit Slip</th>
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
                        <td data-name="expense_coa_id[]">
                            <select class="select2 form-control" name="expense_coa_id[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                <option value="">
                                    <?php echo get_phrase('select_suppliers_/_expenses');?>  
                                </option>
                                <?php coa_list_h(0,$value['expense_coa_id'] ,0 , 0 , $account_type); ?>
                            </select>
                        </td>
                        <td data-name="description[]">
                            <textarea placeholder="Description" class="form-control" name="description[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>"><?php echo $value['description']?>
                            </textarea>
                        </td>
                        <td data-name="amount[]">
                            <input type="number" class="form-control amount" name="amount[]" onkeyup="check_balance(this)" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="<?php echo $value['amount']?>">
                        </td>
                        <td data-name="attachment[]" align="center">
                        <?php
                        $path=system_path($value['attachment'], 'cash_payment');
                        if (file_exists($path))
                        {  
                        ?>
                            <span style=" display: inline-block;" data-toggle="tooltip" title="click to view">
                                <a href="<?php echo display_link($value['attachment'], 'cash_payment');
                                    ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </span>
                            <br>
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
                                <i class="fas fa-times" style="color: white !important;"></i>
                            </button>
                        </td>
                    </tr>
                        <?php
                        $counter++;
                        }
                        ?>
                    <tr id='addr<?php echo $counter;?>' data-id="<?php echo $counter;?>" class="hidden">
                        <td data-name="expense_coa_id[]">
                            <select class="form-control" id="select2" name="expense_coa_id[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                <option value="">
                                    <?php echo get_phrase('select_suppliers_/_expenses');?>  
                                </option>
                                <?php coa_list_h(0,$value['expense_coa_id'] ,0 , 0 , $account_type); ?>
                            </select>
                        </td>
                        <td data-name="description[]">
                            <textarea placeholder="Description" class="form-control" name="description[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                            </textarea>
                        </td>
                        <td data-name="amount[]">
                            <input type="number" class="form-control amount" name="amount[]" onkeyup="check_balance(this)" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="">
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
                <div class="col-md-12">
                    <a id="add_row" class="modal_save_btn float-right mb-4"><i class="fas fa-plus" aria-hidden="true" style="color: white;"></i></a> 
                </div>
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
                <input type="submit" name="submit" value="submit" class="modal_save_btn float-right">
            </div>
        </div>
    </div>
    </div>
    <?php echo form_close();?>
</div>

<script>
    
    $("#add_row").on("click", function() {
        // Dynamic Rows Code
        // alert("Ok");
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


function check_balance(val)
    {
        setTimeout(function(){
            var balance = $("#balance_hidden").val();
            var amount_val = $("#sum_amount").val();
            console.log(amount_val);
            if(Number(amount_val) > Number(balance))
            {
                $(".check_bal_err").text("available amount is less than your desired amount please debit required amount first");
                val.value = '';
                $(".modal_save_btn").attr("disabled",true);
            }else{
                $(".check_bal_err").text('');
            }
        }, 1500);
        getPercentVal();
    }
    
    getPercentVal();
    function getPercentVal() {
        var total = 0;
        var allPercentVal = document.querySelectorAll('.amount');
        for (var i = 0; i < allPercentVal.length; i++) {
            if (allPercentVal[i].value > 0) {
                var ele = allPercentVal[i];
                total += parseInt(ele.value);
            }
        }
        var balance = document.getElementById("balance_hidden").value;
        var remain_balance = document.getElementById("balance").innerHTML = (balance - total);
        document.getElementById("sum_amount").value = total;
        if(remain_balance < 0)
        {
            document.getElementById("balance").innerHTML = 0.00;
        }
    }

 </script>


