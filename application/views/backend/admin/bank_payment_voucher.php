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

.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    cursor: not-allowed;
    background-color: #fff !important;
}
.text_white{
     color:white !important;
}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php if(empty($param2))echo get_phrase('add_bank_payment_voucher'); else echo get_phrase('edit_bank_payment_voucher');?>
        </h3>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
    
    <ul class="nav nav-tabs" role="tablist">
    	<li class="nav-item">
    		<a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Supplier Payments</a>
    	</li>
    	<li class="nav-item">
    		<a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Other Payments</a>
    	</li>
    </ul><!-- Tab panes -->
    <div class="tab-content">
    	<div class="tab-pane active" id="tabs-1" role="tabpanel">
    		<?php echo form_open(base_url().'vouchers/add_bank_payment_voucher' , array('id'=>'bank_receipt_form','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                <div class="thisrow" style="padding:12px;">
                    <div class="row p-0">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <label><?php echo get_phrase('voucher_date');?></label>
                                <input class="form-control datepicker" type="text" name="voucher_date" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <label id="section_id_filter_selection"><?php echo get_phrase('supplier_details');?></label>
                                <select class="form-control select2 supplier_outstanding" name="supplier_id" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                <option value="">
                                <?php echo get_phrase('select_supplier');?>  
                                </option>
                                <?php echo get_supplier_list();?> 
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4 mt-4">
                    <div class="col-md-12">
                        <div class="supplier_data" style="margin-bottom: 15px;margin-top: 15px;"></div>
                    </div>
                </div>
                <div class="row py-4" style="background-color: #c0c0c04a;border-radius: 5px;border: 1px solid #CCC; margin-bottom: 10px;margin-left: auto;margin-right: auto;">
                    <div class="col-md-12 p-0">
                    <div class="row clearfix p-0">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-hover table-sortable" id="tab_logic">
                                <thead>
                                    <tr>
                                        <th style="width: 150px;"><?php echo get_phrase('Purchase_voucher');?></th>
                                        <th style="width: 150px;"><?php echo get_phrase('bank');?></th>
                                        <th style="width: 150px;"><?php echo get_phrase('cheque_no');?> #</th>
                                        <th><?php echo get_phrase('description');?></th>
                                        <th style="width: 150px;"><?php echo get_phrase('amount');?></th>
                                        <th style="width: 150px;">Attachement</th>
                                        <th><?php echo get_phrase('action');?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--class="hidden"-->
                                    <tr id='addr0' data-id="0">
                                        <td data-name="purchase_voucher_id[]">
                                            <select name="purchase_voucher_id[]" class="form-control supplier_outstanding_data" onclick="get_supplier_outstanding_amount(this)" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                                <option value=""><?php echo get_phrase('select_PV');?></option>
                                            </select>
                                        </td>
                                        <td data-name="bank_from_id[]">
                                            <select name="bank_from_id[]" class="form-control get_bank_id" onclick="get_cheque_number(this)" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                                <option value=""><?php echo get_phrase('select_bank');?></option>
                                                <?php echo get_bank_list();?>
                                            </select>
                                        </td>
                                        <td data-name="cheque_number[]">
                                            <input type="text" class="form-control cheque_number" name="cheque_number[]" placeholder="Cheque Number" value="" readonly="">
                                        </td>
                                        <td data-name="description[]">
                                            <textarea style="height: 50px;
                                            width: 100%" placeholder="Description" class="form-control" name="description[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>"></textarea>
                                        </td>
                                        <td data-name="amount[]">
                                            <input type="number" class="amount form-control pv_oustanding_amount" name="amount[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="" readonly="">
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
                            <p class="error text-danger font-weight-bold"></p>
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
                            <!-- <label><?php echo get_phrase('total_amount');?></label>
                            <input type="text" class="form-control" id="total" name="total"> -->
                            <!--<input type="submit" name="submit" value="submit" class="btn btn-primary">-->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    </div>
                    <div class="col-md-3">
                        <!--<input type="submit" name="submit" value="submit" class="btn btn-primary pull-right">-->
                    </div>
                </div>
            <?php echo form_close();?>
            
            <script>
                function get_cheque_number(get_data)
                {
                    // $(".get_bank_id").on('click',function(){
                        var bank_id = get_data.value;
                        var trid = $(get_data).closest('tr').attr('id');
                        var c = "#"+trid+" .cheque_number";
                        
                        if(bank_id != '' || bank_id != 0)
                        {
                            var get_prev_tr = $("#"+trid).prev().last().attr('id'); // Get Previous Tr Id
                            
                            if(get_prev_tr !== undefined)
                            {
                                var get_prev_cheque_num = $("#"+get_prev_tr+" .cheque_number").val(); // Get Previous CHeque Number Value
                                var chq_num = parseInt(get_prev_cheque_num);
                                $(c).val(chq_num+1);
                            }else{
                                $.ajax({
                        			url: '<?= base_url(); ?>vouchers/fetch_cheque_number',
                        			method: 'POST',
                        			dataType: 'JSON',
                        			data:{bank_id:bank_id},
                        			success:function(response)
                        			{
                        			    if(response.error){
                        			        $(".error").text(response.error);
                        			    }else{
                        			        $(c).val(response.cheque_number);
                        			    }
                        			} 
                        		});
                                
                                console.log('Can not find sibling');
                            }
                        }else{
                            $(c).val('');
                        }
                    // });
                }
            </script>
            
    	</div>
    	<div class="tab-pane" id="tabs-2" role="tabpanel">
    		<?php echo form_open(base_url().'vouchers/add_other_bank_payment_voucher' , array('id'=>'add_other_bank_payment_voucher','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                <div class="thisrow" style="padding:12px;">
                    <div class="row p-0">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <label><?php echo get_phrase('voucher_date');?></label>
                                <input class="form-control datepicker" type="text" name="voucher_date" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <label><?php echo get_phrase('select_coa');?></label>
                                <select class="form-control select2" name="coa_id" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    <option value=""><?php echo get_phrase('select_coa');?></option>
                                    <?php echo coa_list_h(0,0,0,0, $account_type,1); ?> 
                                </select>
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
                                        <th style="width: 150px;"><?php echo get_phrase('bank');?></th>
                                        <th style="width: 150px;"><?php echo get_phrase('cheque_no');?> #</th>
                                        <th><?php echo get_phrase('description');?></th>
                                        <th style="width: 150px;"><?php echo get_phrase('amount');?></th>
                                        <th style="width: 150px;">Attachement</th>
                                        <th><?php echo get_phrase('action');?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id='addr0' data-id="0">
                                        <td data-name="bank_from_id[]">
                                            <select name="bank_from_id[]" class="form-control get_bank_id_other" onclick="get_cheque_number_other(this)" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                                <option value=""><?php echo get_phrase('select_bank');?></option>
                                                <?php echo get_bank_list();?>
                                            </select>
                                        </td>
                                        <td data-name="cheque_number[]">
                                            <input type="text" class="form-control cheque_number_other" name="cheque_number[]" placeholder="Cheque Number" value="" readonly="">
                                        </td>
                                        <td data-name="description[]">
                                            <textarea style="height: 50px;
                                            width: 100%" placeholder="Description" class="form-control" name="description[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>"></textarea>
                                        </td>
                                        <td data-name="amount[]">
                                            <input type="number" class="form-control" name="amount[]" data-validate="required" data-message-required="<?php echo get_phrase('required');?>" placeholder="Amount" value="">
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
                            <p class="error text-danger font-weight-bold"></p>
                        </div>
                    </div>
                    <a id="add_row" class="modal_save_btn pull-right"><i class="fa fa-plus" aria-hidden="true" style="color: white;"></i></a>
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
                            <!-- <label><?php echo get_phrase('total_amount');?></label>
                            <input type="text" class="form-control" id="total" name="total"> -->
                            <!--<input type="submit" name="submit" value="submit" class="btn btn-primary">-->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    </div>
                    <div class="col-md-3">
                        <!--<input type="submit" name="submit" value="submit" class="btn btn-primary pull-right">-->
                    </div>
                </div>
            <?php echo form_close();?>
            
            <script>
                function get_cheque_number_other(get_data)
                {
                        var bank_id = get_data.value;
                        var trid = $(get_data).closest('tr').attr('id');
                        var c = "#"+trid+" .cheque_number_other";
                        
                        if(bank_id != '' || bank_id != 0)
                        {
                            var get_prev_tr = $("#"+trid).prev().last().attr('id'); // Get Previous Tr Id
                            
                            if(get_prev_tr !== undefined)
                            {
                                var get_prev_cheque_num = $("#"+get_prev_tr+" .cheque_number_other").val(); // Get Previous CHeque Number Value
                                var chq_num = parseInt(get_prev_cheque_num);
                                $(c).val(chq_num+1);
                            }else{
                                $.ajax({
                        			url: '<?= base_url(); ?>vouchers/fetch_cheque_number',
                        			method: 'POST',
                        			dataType: 'JSON',
                        			data:{bank_id:bank_id},
                        			success:function(response)
                        			{
                        			    debugger;
                        			    if(response.error){
                        			        $(".error").text(response.error);
                        			    }else{
                        			        $(c).val(response.cheque_number);
                        			    }
                        			} 
                        		});
                                
                                console.log('Can not find sibling');
                            }
                        }else{
                            $(c).val('');
                        }
                    // });
                }
            </script>
            
    	</div>
    </div>
</div>
<script>
    
    function get_supplier_outstanding_amount(get_data){

            var trid = $(get_data).closest('tr').attr('id');
            pv_id = get_data.value;
            var t = "#"+trid+" .pv_oustanding_amount";
            if(pv_id != '' || pv_id != 0)
            {
                $.ajax({
        			url: '<?= base_url(); ?>vouchers/fetch_pv_oustanding_amount',
        			method: 'POST',
        			dataType: 'JSON',
        			data:{pv_id:pv_id},
        			success:function(response)
        			{
        			    if(response.error){
        			        $(".error").text(response.error);
        			    }else{
        			        $(t).val(response.pv_amount);
        			    }
        			} 
        		});
            }else{
                $(t).val('');
            }
        // });
    }
    
    function get_cheque_number(get_data)
    {
        // $(".get_bank_id").on('click',function(){
            var bank_id = get_data.value;
            var trid = $(get_data).closest('tr').attr('id');
            var c = "#"+trid+" .cheque_number";
            
            if(bank_id != '' || bank_id != 0)
            {
                var get_prev_tr = $("#"+trid).prev().last().attr('id'); // Get Previous Tr Id
                
                if(get_prev_tr !== undefined)
                {
                    var get_prev_cheque_num = $("#"+get_prev_tr+" .cheque_number").val(); // Get Previous CHeque Number Value
                    var chq_num = parseInt(get_prev_cheque_num);
                    $(c).val(chq_num+1);
                }else{
                    $.ajax({
            			url: '<?= base_url(); ?>vouchers/fetch_cheque_number',
            			method: 'POST',
            			dataType: 'JSON',
            			data:{bank_id:bank_id},
            			success:function(response)
            			{
            			    if(response.error){
            			        $(".error").text(response.error);
            			    }else{
            			        $(c).val(response.cheque_number);
            			    }
            			} 
            		});
                    
                    console.log('Can not find sibling');
                }
            }else{
                $(c).val('');
            }
        // });
    }

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
        // $("#add_row").trigger("click");
        
        $(".supplier_outstanding").on('click',function(){
            var supplier_id = $(this).val();
            if(supplier_id != '' || supplier_id != 0)
            {
                $.ajax({
        			url: '<?= base_url(); ?>vouchers/fetch_supplier_outstanding',
        			method: 'POST',
        			data:{supplier_id:supplier_id},
        			success:function(response)
        			{
        			    $(".supplier_outstanding_data").html(response);
        			    
        			    $.ajax({
                			url: '<?= base_url(); ?>vouchers/fetch_supplier_details',
                			method: 'POST',
                			data:{supplier_id:supplier_id},
                			success:function(response)
                			{
                			    $(".supplier_data").html(response);
                			} 
                		});
        			} 
        		});
            }
        });
        
        $(".page-container").addClass("sidebar-collapsed");

    });

</script>