<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <!--  <i class="entypo-right-circled carrow">
                        </i>-->
            <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/cash.png"><?php echo get_phrase('bank_receipt_voucher_detail'); ?>
        </h3>
        <?php 
        //if (right_granted('staff_manage'))
        { ?>
            <a href="<?php echo base_url(); ?>vouchers/bank_receipt_voucher_listing" class="btn btn-primary pull-right">
               <?php echo get_phrase('back');?>
            </a>
        <?php } ?>
    </div>
</div>
<?php
// echo '<pre>';
// print_r($bank_receipt_arr);
?>

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
                <?php echo date_view($bank_receipt_arr[0]['voucher_date']);?>     
            </td>
        </tr>
        <tr>    
            <td>
                <strong>
                    <span class="glyphicon glyphicon-user  text-primary"></span>    
                    <?php echo get_phrase('depositor_details');?>
                </strong>
            </td>
            <td class="text-primary">
            <?php
                $depositor_details =get_depositor_details($bank_receipt_arr[0]['depositor_id']);
                echo $depositor_details[0]['name'];
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
                <?php echo $bank_receipt_arr[0]['voucher_number'];?> 
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
                <?php echo status_check($bank_receipt_arr[0]['status']);?> 
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
                        <?php echo get_phrase('method');?>
                    </td>
                    <td>
                        <?php echo get_phrase('deposit_slip_number');?>
                    </td>
                    <td>
                        <?php echo get_phrase('description');?>
                    </td>
                    <td>
                        <?php echo get_phrase('deposit_bank_id');?>
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
            
        <?php if(count($bank_receipt_detail_arr)>0)
        {
            // echo '<pre>';
            // print_r($bank_receipt_detail_arr);
            foreach ($bank_receipt_detail_arr as $row)
            {
            ?>
                <tr>
                    <td>
                    <?php echo method_check($row['method']);?>
                    </td>
                    <td>
                    <?php echo $row['deposit_slip_number'];?>
                    </td>
                    <td>
                    <?php echo $row['description'];?>
                    </td>
                    <td>
                    <?php
                        $bank_details = get_bank_details($row['deposit_bank_id']);
                        echo $bank_details[0]['bank_name'];
                    ?>
                    </td>
                    <td>
                    <?php echo $row['amount'];?>
                    </td>
                    <td>
                    <!-- <?php //echo $row['attachment'];?> -->
                    <?php
                    $path=system_path($row['attachment'], 'bank_receipt');
                    if (file_exists($path))
                    {  
                    ?>
                        <span style="float: left; display: inline-block;" data-toggle="tooltip" title="click to view">
                            <a href="<?php echo display_link($row['attachment'], 'bank_receipt');
                                ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i>
                                <?php echo get_phrase('view_file');?>
                            </a>
                        </span>
                    <?php
                    }
                    ?>
                    </td>
                </tr>
            <?php
            }
            ?>
                <tr>
                    <td colspan="6"><?php
                        echo $this->pagination->create_links();
                        ?></td>
                </tr>

                <tr>
                    <td colspan="6"><?php
                        echo "<strong>".get_phrase('total_records') .":</strong>".$total_records."";
                        ?></td>
                </tr>
                <?php }

                else {
                    ?>
                   <tr>
                       <td colspan="3">

                           <?php echo get_phrase('no_records_found'); ?>..
                       </td>
                   </tr>

            <?php }?>
            </tbody>
        </table>
    </div>
</div>