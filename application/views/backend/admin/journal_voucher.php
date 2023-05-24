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
            <?php if(empty($param2))echo get_phrase('journal_voucher'); else echo get_phrase('edit_journal_voucher');?>
        </h3>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
<?php echo form_open(base_url().'vouchers/add_journal_voucher' , array('id'=>'journal_voucher_form','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
<div class="thisrow" style="padding:12px;">
    <div class="row p-0">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="col-md-6 col-lg-6 col-sm-6">
                <label><?php echo get_phrase('voucher_date');?></label>
                <input class="form-control datepicker" type="text" name="voucher_date" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
            </div>
        </div>
    </div>
</div>
<div class="row py-4" style="background-color: #c0c0c04a;border-radius: 5px;border: 1px solid #CCC; margin-bottom: 10px;margin-left: auto;margin-right: auto;">
    <div class="col-md-12 p-0">
        <div class="row clearfix p-0">
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered table-hover table-sortable" id="tab_logic">
                    <thead>
                        <tr>
                            <th style="width: 170px;"><?php echo get_phrase('debit_COA');?></th>
                            <th style="width: 170px;"><?php echo get_phrase('credit_COA');?></th>
                            <th style="width: 120px;"><?php echo get_phrase('amount');?></th>
                            <th><?php echo get_phrase('description');?></th>
                            <th style="width: 250px;">Attachement</th>
                            <th><?php echo get_phrase('action');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id='addr0' data-id="0" class="hidden">
                            
                            <td data-name="debit_coa_id[]">
                                <select name="debit_coa_id[]" id="select2" class="form-control"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                    <option value=""><?php echo get_phrase('select_coa');?></option>
                                    <?php coa_list_h(0,0,0,0, $account_type); ?>
                                </select>
                            </td>
                            
                            <td data-name="credit_coa_id[]">
                                <select name="credit_coa_id[]" id="select2" class="form-control"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                    <option value=""><?php echo get_phrase('select_coa');?></option>
                                    <?php coa_list_h(0,0,0,0, $account_type); ?>
                                </select>
                            </td>
                            
                            <td data-name="amount[]">
                                <input type="number" class="amount form-control" name="amount[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="">
                            </td>
                            
                            <td data-name="description[]">
                                <textarea style="height: 50px;
                                width: 100%;" placeholder="Description" class="form-control" name="description[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                </textarea>
                            </td>
                            
                            
                            <td data-name="attachment[]">
                                <input value="" type="file" class="form-control" name="attachment[]"  onchange="file_validate('attachment','doc','img_g_msg')">
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
            </div>
        </div>
        <a id="add_row" class="modal_save_btn pull-right"><i class="fas fa-plus" aria-hidden="true" style="color: white;"></i></a>
</div>
</div>
<div class="thisrow" style="padding:12px;">
    <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-6 pt-2">
            <div class="form-group">
                <label><?php echo get_phrase('voucher_status');?></label>
                <select name="status" class="form-control" data-validate="required" data-message-required="Required">
                    <option value="">
                        <?php echo get_phrase('select_status');?>
                    </option>
                    <?php echo status_list(1); ?>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" value="submit" class="modal_save_btn pt-2 float-right">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
    </div>
    <div class="col-md-3">
    </div>
</div>

<?php echo form_close();?>
</div>

<script>
    $(document).ready(function() {
    
    var tr;

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
        
        // add delete button and td
        /*
        $("<td></td>").append(
            $("<button class='btn btn-danger glyphicon glyphicon-remove row-remove'></button>")
                .click(function() {
                    $(this).closest("tr").remove();
                })
        ).appendTo($(tr));
        */
        
        // add the new row
        $(tr).appendTo($('#tab_logic'));
        $(tr).find('td > #select2').select2();
        $(tr).find("td button.row-remove").on("click", function() {
             $(this).closest("tr").remove();
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

   

    // we used jQuery'keyup'to trigger the computation as the user type
    // $('.amount').keyup(function () {
    //         var sum = 0;
    //         $('.amount').each(function() {
    //             sum += Number($(this).val());
    //         });
    //         $('#total').val(sum);
    // });
    });
    
    $(".page-container").addClass("sidebar-collapsed");
    
</script>