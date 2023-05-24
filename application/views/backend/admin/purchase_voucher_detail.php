<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php echo get_phrase('purchase_voucher_detail'); ?>
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
                <strong>
                    <span class="glyphicon glyphicon-calendar text-primary"></span>
                    <?php echo get_phrase('voucher_date');?>
                </strong>
            </td>
            <td class="text-primary">
                <?php echo date_view($purchase_voucher_qur[0]['voucher_date']);?>     
            </td>
        </tr>
        <tr>    
            <td>
                <strong>
                    <span class="glyphicon glyphicon-user  text-primary"></span>    
                    <?php echo get_phrase('supplier_details');?>
                </strong>
            </td>
            <td class="text-primary">
            <?php
                $supplier_details =get_supplier_details($purchase_voucher_qur[0]['supplier_id']);
                echo $supplier_details[0]['name'];
            ?>    
            </td>
        </tr>
        <tr>        
            <td>
                <strong>
                    <span class="glyphicon glyphicon-cloud text-primary"></span>  
                    <?php echo get_phrase('voucher_number');?>     
                </strong>
            </td>
            <td class="text-primary">
                <?php echo $purchase_voucher_qur[0]['voucher_number'];?> 
            </td>
        </tr>
        <tr>        
            <td>
                <strong>
                    <span class="glyphicon glyphicon-cloud text-primary"></span>  
                    <?php echo get_phrase('status');?>     
                </strong>
            </td>
            <td class="text-primary">
                <?php echo status_check($purchase_voucher_qur[0]['status']);?> 
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
                        <?php echo get_phrase('bill_number');?>
                    </td>
                    <td>
                        <?php echo get_phrase('description');?>
                    </td>
                    <td>
                        <?php echo get_phrase('quantity');?>
                    </td>
                    <td>
                        <?php echo get_phrase('amount');?>
                    </td>
                    <td>
                        <?php echo get_phrase('attachment');?>
                    </td>
                </tr>
            </thead>
            <tbody>
            
        <?php if(count($purchase_voucher_detail_arr)>0)
        {
            // echo '<pre>';
            // print_r($bank_payment_detail_arr);
            foreach ($purchase_voucher_detail_arr as $row)
            {
            ?>
                <tr>

                    <td>
                    <?php echo $row['bill_number'];?>
                    </td>
                    <td>
                    <?php echo $row['description'];?>
                    </td>
                    <td>
                    <?php echo $row['qty'];?>
                    </td>
                    <td>
                    <?php echo $row['amount'];?>
                    </td>
                    <td>
                    <!-- <?php //echo $row['attachment'];?> -->
                    <?php
                    $path=system_path($row['attachment'], 'bank_payment');
                    if (file_exists($path)){  
                    ?>
                        <span style="float: left; display: inline-block;" data-toggle="tooltip" title="click to view">
                            <a href="<?php echo display_link($row['attachment'], 'bank_payment');
                                ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i>
                                <?php echo get_phrase('view_file');?>
                            </a>
                        </span>
                    <?php } ?>
                    </td>
                </tr>
            <?php } } ?>
            </tbody>
        </table>
    </div>
</div>