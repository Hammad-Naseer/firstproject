<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php echo get_phrase('view_cheque_book_details'); ?>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-lg-6 col-sm-6">
        <div class="table-responsive">
            <table class="table table-condensed table-responsive table-user-information">
                <tbody>
                    <tr>        
                        <td>
                            <strong><?php echo get_phrase('bank_title');?></strong>
                        </td>
                        <td class="text-primary">
                            <?php echo $bank_arr[0]['bank_name'];?>     
                        </td>
                    </tr>
                    <tr>    
                        <td>
                            <strong><?php echo get_phrase('branch_code');?></strong>
                        </td>
                        <td class="text-primary">
                        <?php
                            echo $bank_arr[0]['branch_code'];
                        ?>    
                        </td>
                    </tr>
                    <tr>        
                        <td>
                            <strong><?php echo get_phrase('account_title');?></strong>
                        </td>
                        <td class="text-primary">
                            <?php echo $bank_arr[0]['account_title'];?>
                            -
                            <?php echo $bank_arr[0]['account_number'];?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0px;">
        <table class="table table-bordered datatable" id="staff">
            <thead>
                <tr>
                    <td>
                        <?php echo get_phrase('batch_number');?>
                    </td>
                    <td>
                        <?php echo get_phrase('cheque_start_leaf');?>
                    </td>
                    <td>
                        <?php echo get_phrase('cheque_end_leaf');?>
                    </td>
                    <td>
                        <?php echo get_phrase('active_cheque_leaf');?>
                    </td>
                    <td>
                        <?php echo get_phrase('status');?>
                    </td>
                    <td>
                        <?php echo get_phrase('date_added');?>
                    </td>
                    <td>
                        <?php echo get_phrase('action');?>
                    </td>
                </tr>
            </thead>
            <tbody>
            
        <?php if(count($bank_chequebook_detail_arr)>0)
        {
           
            foreach ($bank_chequebook_detail_arr as $row)
            {
            ?>
                <tr>
                    <td>
                        <?php echo $row['batch_number'];?>
                    </td>
                    <td>
                        <?php echo $row['start_cheque_number'];?>
                    </td>
                    <td>
                        <?php echo $row['end_cheque_number'];?>
                    </td>
                    <td>
                        <?php echo $row['current_cheque_number'];?>
                    </td>
                    <td>
                        <?php if($row['status'] == 1){ echo '<span class="text-success">Active</span>'; }else{ echo '<span class="text-danger">Inactive</span>'; }?>
                    </td>
                    <td>
                        <?php echo date_view($row['inserted_at']);?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                <li>
                                    <a onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_cheque_book/<?php echo $row["b_c_b_id"]."/edit";?>');">
                                        <i class="fas fa-money-bill-alt"></i>
                                        <?php echo get_phrase('edit_cheque_book');?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
        <?php
                
            } 
        }else{
        ?>
        
            <tr>
                <td colspan = "7" class="td_middle">
                    No Checkbook added.
                </td>
            </tr>
            
        <?php
        }
            ?>
            </tbody>
        </table>
    </div>
</div>