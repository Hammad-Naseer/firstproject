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
            <?php if(empty($param2))echo get_phrase('add_cash_receipt_voucher'); else echo get_phrase('edit_cash_receipt_voucher'); ?>
        </h3>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
    <ul class="nav nav-tabs" role="tablist">
    	<li class="nav-item">
    		<a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Depositer Receipt</a>
    	</li>
    	<li class="nav-item">
    		<a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Other Receipt</a>
    	</li>
    </ul>
    <div class="tab-content">
    	<div class="tab-pane active" id="tabs-1" role="tabpanel">
    	    <?php echo form_open(base_url().'vouchers/add_cash_receipt_voucher' , array('id'=>'cash_receipt_form','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                <div class="thisrow" style="padding:12px;">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <label><?php echo get_phrase('voucher_date');?></label>
                                <input class="form-control datepicker" type="text" name="voucher_date" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <label id="section_id_filter_selection"><?php echo get_phrase('depositor_details');?></label>
                                <select class="select2 form-control" name="depositor_id" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                <option value="">
                                <?php echo get_phrase('select_depositor');?>  
                                </option>
                                <?php echo get_depositor_list();?> 
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12" style="background-color: #c0c0c04a;border: 1px solid #CCC;">
                        <table class="table table-bordered table-hover mt-4 mb-4" id="tab_logic">
                            <thead>
                                <tr>
                                    <th><?php echo get_phrase('description');?></th>
                                    <th style="width: 170px;"><?php echo get_phrase('amount');?></th>
                                    <th style="width: 250px;">Deposit Slip</th>
                                    <th><?php echo get_phrase('action');?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id='addr0' data-id="0" class="hidden">
                                    <td data-name="description[]">
                                        <textarea placeholder="Description" class="form-control" name="description" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                        </textarea>
                                    </td>
                                    <td data-name="amount[]">
                                        <input type="number" class="amount form-control" name="amount" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="">
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
        </div>
        <div class="tab-pane" id="tabs-2" role="tabpanel">
    	    <?php echo form_open(base_url().'vouchers/add_other_cash_receipt_voucher' , array('id'=>'add_other_cash_receipt_voucher','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                <div class="thisrow" style="padding:12px;">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <label><?php echo get_phrase('voucher_date');?></label>
                                <input class="form-control datepicker" type="text" name="voucher_date" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <label id="section_id_filter_selection"><?php echo get_phrase('select_coa');?></label>
                                <select class="form-control select2" name="credit_coa_id" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    <option value=""><?php echo get_phrase('select_coa');?></option>
                                    <?php echo coa_list_h(0,0,0,0, $account_type,1); ?> 
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12" style="background-color: #c0c0c04a;border: 1px solid #CCC;">
                        <table class="table table-bordered table-hover mt-4 mb-4" id="tab_logic1">
                            <thead>
                                <tr>
                                    <th><?php echo get_phrase('description');?></th>
                                    <th style="width: 170px;"><?php echo get_phrase('amount');?></th>
                                    <th style="width: 250px;">Deposit Slip</th>
                                    <th><?php echo get_phrase('action');?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id='addr0' data-id="0" class="hidden">
                                    <td data-name="description[]">
                                        <textarea placeholder="Description" class="form-control" name="description" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                        </textarea>
                                    </td>
                                    <td data-name="amount[]">
                                        <input type="number" class="amount form-control" name="amount" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="">
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
                            <a id="add_row1" class="modal_save_btn float-right mb-4 mt-2"><i class="fa fa-plus" aria-hidden="true" style="color: white;"></i></a>
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
        </div>
    </div> 
</div>    

<script>
    $(document).ready(function() {
        $("#add_row").on("click", function() {
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
        
        $(tr).find("td button.row-remove").on("click", function() {
             $(this).closest("tr").remove();
        });
    });
        $("#add_row1").on("click", function() {
            var newid = 0;
            $.each($("#tab_logic1 tr"), function() {
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
            $.each($("#tab_logic1 tbody tr:nth(0) td"), function() {
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
                        'text': $('#tab_logic1 tr').length
                    }).appendTo($(tr));
                }
            });
            
            // add the new row
            $(tr).appendTo($('#tab_logic1'));
            
            $(tr).find("td button.row-remove").on("click", function() {
                 $(this).closest("tr").remove();
            });
        });

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
        $("#add_row1").trigger("click");
        $(".page-container").addClass("sidebar-collapsed");
    });
</script>