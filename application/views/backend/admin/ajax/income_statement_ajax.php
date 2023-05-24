<style>
.no-print{position:relative;top:15px;z-index:99999;border:2px solid #fff;border-radius:20px 20px 20px 0;box-shadow:1px 0 5px 1px #ccc;outline:0}.main-head{background-color:#012b3c!important;color:#fff!important}@media print{html{transform:scale(.8)}}.balance_sheat_head{text-align:center}.balance_sheat_head div.school_name{clear:both}.balance_sheat_head div.school_name img{width:220px!important;height:120px!important}.balance_sheat_head div.school_name h2{font-size:14px;font-weight:700}.balance_sheat_head div.current_date{clear:both}.balance_sheat_head div.current_date h2{font-size:14px;font-weight:700}td.coa_sub_head{text-decoration:underline;padding:0 0 0 22px!important}tr.coa_sub_total{font-style:italic;background-color:#f6fdfc!important;color:#5f5151!important}tr.total{background:#f5f5f6;font-weight:700}
</style>
<form action="<?php echo base_url();?>transection_account/income_statement_pdf" method="post">
    <input type="hidden" name="start_date" value="<?= $start_date ?>">
    <input type="hidden" name="end_date" value="<?= $end_date ?>">
    <button type="submit" class="modal_save_btn no-print">Generate PDF Report</button>
</form>
<form action="<?php echo base_url();?>transection_account/trial_balance_excel" method="post">
    <input type="hidden" name="coa_id" value="<?= $coa_id ?>">
    <input type="hidden" name="start_date" value="<?= $start_date ?>">
    <input type="hidden" name="end_date" value="<?= $end_date ?>">
    <button type="button" id="btnExport" class="modal_save_btn no-print" style="background:#008000e3 !important;">Generate Excel Report</button>
</form>
<!--<div id="print_btn" class="btn btn-primary"><?php echo get_phrase('print');?></div>-->
<div class="col-lg-12 demo thisrow" id="trial_balance_print" style="border: 1px solid #cccccc9c !important;">
        <!--<div class="balance_sheat_head row">-->
            <!--<div class="col-md-2">-->
            <?php

            // $logo=system_path($_SESSION['school_logo']);

            // if($_SESSION['school_logo']=="" || !is_file($logo))
            // {
                ?>
                <!--<img style="height:120px;" src="<?php echo base_url();?>assets/images/gsims_logo.png">-->
                <?php
            // }
            // else
            // {
                ?>
                <!--<img style="height: 120px;" src="<?php echo base_url();?>uploads/<?php echo $_SESSION['folder_name'].'/'.$_SESSION['school_logo']; ?>">-->
                <?php
            // }
            ?>
        <!--    </div>-->
        <!--    <div class="col-md-10 text-center">-->
        <!--        <h2><?php //echo $_SESSION['school_name']; ?></h2>-->
        <!--        <h3> <?php //echo get_phrase('income_statement'); ?> <?php echo "From : ".$start_date. " To : ".$end_date; ?></h3>-->
        <!--    </div>-->
        <!--</div>-->

<div class="table-responsive">
<table class="table table-bordered datatable" id="table_export_exp">
    <thead>
    <tr style="background-color:white !important;">
        <td style="background-color:white !important;" colspan="2" align="center">
            <h2><?php echo $_SESSION['school_name']; ?></h2>
            <h3> <?php echo get_phrase('income_statement'); ?> <?php echo "From : ".$start_date. " To : ".$end_date; ?></h3>
        </td>
    </tr>
    <tr>
        <th class="span3" style="width:60%"><div><?php echo get_phrase('title');?></div></th>
        <th><div><?php echo get_phrase('amount');?></div></th>
    </tr>
    </thead>
    <tbody>
        <tr class="main-head">
            <td colspan="2" align="center"><strong><b><?php echo $data_frp_array['income_stmt_sales']['account_head']; ?></strong></b></td>
        </tr>
        <?php

             $total_sales = 0;
             $net_income = 0;

        foreach($child_coa_list['income_stmt_sales'] as $ch_key=>$ch_val)
        {
            $total_sales_child = 0;
            $total_debit = 0;
            $total_credit = 0;
            $amount = 0;
            ?>
            <tr>
                <td class="coa_sub_head"><?php echo $ch_val['account_head'] ; ?></td>
                <td>&nbsp;</td>
            </tr>
            <?php
            foreach($ch_val['child_coa']as $ch_key1=>$ch_val1)
            {

                if ($ch_val1['account_type'] == 2)
                {
                    $amount = $ch_val1['debit'] - $ch_val1['credit'];
                }
                elseif ($ch_val1['account_type'] == 1)
                {
                    $amount = $ch_val1['credit'] - $ch_val1['debit'];
                }

                //$amount = $total_debit - $total_credit;
                $total_sales = $total_sales + $amount;
                $total_sales_child = $total_sales_child + $amount;
                if ($ch_val['coa_id'] != $ch_val1['coa_id'])
                {
                    ?>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $ch_val1['account_head']; ?></td>
                        <td><?php


                            if ($amount < 0) {
                                echo "(" . (-1 * $amount) . ")";
                            } else {
                                echo number_format($amount);
                            }

                            ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr class="coa_sub_total main-head">
                    <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo get_phrase('total'). ' ' .$ch_val['account_head'] ; ?></b></td>
                <td><?php
                    if ($total_sales_child < 0)
                    {
                        echo "(".(-1*$total_sales_child).")";
                    }
                    else
                    {
                        echo number_format($total_sales_child);
                    }
                    //echo $total_sales; ?> </td>
                </tr>
                <tr>
                    <td colspan="2"><strong>&nbsp;</strong></td>
                </tr>
        <?php
        }
        ?>
        
    <tr class="total main-head">
        <td><strong><?php echo get_phrase('total_revenue'); ?></strong></td>
        <td>
            <?php
            if ($total_sales < 0)
            {
            echo "(".(-1*$total_sales).")";
            }
            else
            {
              echo number_format($total_sales);
            }
            //echo $total_sales; ?> </td>
    </tr>
    <tr>
        <td><strong>&nbsp;</strong></td>
        <td><strong>&nbsp;</strong></td>
    </tr>
    <?php /* Total expense start */ ?>
    <tr class="main-head">
        <td colspan="2" align="center"><strong><?php echo $data_frp_array['income_stmt_expense']['account_head']; ?></strong></td>
    </tr>
        <?php
        $total_expense = 0;
        foreach($child_coa_list['income_stmt_expense'] as $ch_key=>$ch_val)
        {


            $total_debit = 0;
            $total_credit = 0;
            $amount = 0;
            $total_sub_expense = 0;
            ?>
            <tr>
                <td class="coa_sub_head"><?php echo $ch_val['account_head'] ; ?></td>
                <td>&nbsp;</td>
            </tr>
            <?php
            foreach($ch_val['child_coa']as $ch_key1=>$ch_val1)
            {

                if ($ch_val1['account_type'] == 2)
                {
                    $amount = $ch_val1['debit'] - $ch_val1['credit'];
                }
                elseif ($ch_val1['account_type'] == 1)
                {
                    $amount = $ch_val1['credit'] - $ch_val1['debit'];
                }

                //$amount = $total_debit - $total_credit;
                $total_sub_expense = $total_sub_expense + $amount;

                if ($ch_val['coa_id'] != $ch_val1['coa_id'])
                {
                    ?>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $ch_val1['account_head']; ?></td>
                        <td><?php


                            if ($amount < 0) {
                                echo "(" . (-1 * $amount) . ")";
                            } else {
                                echo number_format($amount);
                            }

                            ?></td>
                    </tr>
                    <?php
                }
                
            }
            ?>
            <tr class="coa_sub_total">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo get_phrase('total'). ' ' .$ch_val['account_head'] ; ?></td>
                <td><?php
                    if ($total_sub_expense < 0)
                    {
                        echo "(".(-1*$total_sub_expense).")";
                        $total_expense -= $total_sub_expense;
                    }
                    else
                    {
                        echo number_format($total_sub_expense);
                        $total_expense += $total_sub_expense;

                    }
                    
                    
                    //echo $total_sales; ?> </td>
            </tr>
            <?php
        }
        ?>
        <tr class="total main-head">
            <td><strong><?php echo get_phrase('total_expense'); ?></strong></td>
            <td><?php
                if ($total_expense < 0)
                {
                    echo "(".(-1*$total_expense).")";
                }
                else
                {
                    echo number_format($total_expense);
                }

                ///echo $total_expense;  ?></td>
        </tr>
        <?php /* Total expense End */ ?>


        <?php /* Net Income start */ ?>

        <tr>
            <td><strong>&nbsp;</strong></td>
            <td><strong>&nbsp;</strong></td>
        </tr>

        <tr class="total main-head">
            <td><strong><?php echo get_phrase('net_profit');  ?></strong></td>
            <td><strong><?php $net_income = $total_sales-$total_expense;

                    if ($net_income < 0)
                    {
                        echo "(".(-1*$net_income).")";
                    }
                    else
                    {
                        echo number_format($net_income);
                    }


                    ?></strong></td>
        </tr>

        <?php /* Net Income End */ ?>

    </tbody>
</table>

</div>
</div>

<script type="text/javascript">

    $( document ).ready(function()
    {
        $( '#print_btn' ).click( function ()
        {
            var printContents = document.getElementById( 'trial_balance_print' ).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        });
    } );
</script>
<script src="<?=base_url()?>assets/table2excel.js"></script>
<script>
    $(function () {
        $("#btnExport").click(function () {
            $("#table_export_exp").table2excel({
                filename: "Income Statement From <?= $start_date ?> To <?= $end_date ?>.xls"
            });
        });
    });
</script>