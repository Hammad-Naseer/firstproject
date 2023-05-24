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

<?php echo form_open(base_url().'vouchers/add_cash_payment_voucher' , array('id'=>'cash_payment_form','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <h3 class="system_name inline">
                <?php if(empty($param2))echo get_phrase('add_cash_payment_voucher'); else echo get_phrase('edit_cash_payment_voucher'); ?>
            </h3>
        </div>
    </div>

    <div class="row">
        <div class="thisrow" style="padding:12px;width: 100%;">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="col-md-6 col-lg-6 col-sm-6">
                    <label><?php echo get_phrase('voucher_date');?></label>
                    <input class="form-control datepicker" type="text" name="voucher_date" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                </div>
                <div class="col-md-6 col-lg-6 col-sm-6">
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

    <div class="row">
        <div class="col-md-12" style="background-color: #c0c0c04a;border: 1px solid #CCC;">
            <table class="table table-bordered table-hover mt-4 mb-4" id="tab_logic">
                <thead>
                    <tr>
                        <th><?php echo get_phrase('Debit_COA');?></th>
                        <th><?php echo get_phrase('description');?></th>
                        <th style="width: 170px;"><?php echo get_phrase('amount');?></th>
                        <th style="width: 250px;">Deposit Slip</th>
                        <th><?php echo get_phrase('action');?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr id='addr0' data-id="0" class="hidden">
                        <td data-name="expense_coa_id[]">
                            <select class="form-control" id="select2" name="expense_coa_id" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                            <option value="">
                            <?php echo get_phrase('select_suppliers_/_expenses');?>  
                            </option>
                            <?php coa_list_h(0,0 ,0 , 0 , $account_type,1); ?>
                            </select>
                        </td>
                        <td data-name="description[]">
                            <textarea placeholder="Description" class="form-control" name="description" style="height:40px;" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                            </textarea>
                        </td>
                        <td data-name="amount[]">
                            <input type="number" class="amount form-control" onkeyup="check_balance(this)" name="amount" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="">
                        </td>
                        <td data-name="attachment[]">
                            <input value="" type="file" class="form-control" name="attachment"  onchange="file_validate('attachment','doc','img_g_msg')">
                            <span style="color: red;" id="img_g_msg"></span>
                        </td>
                        <td data-name="del">
                            <button class='modal_cancel_btn row-remove'>
                                <i class="fas fa-times" style="color: white !important;"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="col-md-12">
                <p class="check_bal_err" style="color:red;"></p>
                <a id="add_row" class="modal_save_btn float-right mb-4 mt-2"><i class="fas fa-plus" aria-hidden="true" style="color: white;"></i></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-4">
        <div class="thisrow row" style="padding:12px;">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><?php echo get_phrase('voucher_status');?></label>
                    <select name="status" class="form-control" data-validate="required" data-message-required="Required">
                        <option value="">
                            <?php echo get_phrase('select_status');?>
                        </option>
                        <?php echo status_list(1);?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="submit" class="modal_save_btn float-right">
                </div>    
            </div>
        </div>
        </div>
    </div>
<?php echo form_close();?>

<script>
    $(document).ready(function() {
        $("#add_row").on("click", function() {
        // Dynamic Rows Code
        // Get max row id and set new id
        var newid = 0;
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
        $.each($("#tab_logic tbody tr:nth(0) td"), function() {
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
        
        // add the new row
        $(tr).appendTo($('#tab_logic'));
        $(tr).find('td > #select2').select2();
        $(tr).find("td button.row-remove").on("click", function() {
             $(this).closest("tr").remove();
             getPercentVal();
        });
    });
        // Sortable Code
        var fixHelperModified = function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
        
            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width())
            });
            
            return $helper;
        };
      
        $(".table-sortable tbody").sortable({
            helper: fixHelperModified      
        }).disableSelection();
    
        $(".table-sortable thead").disableSelection();
        $("#add_row").trigger("click");
    
    });
    
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