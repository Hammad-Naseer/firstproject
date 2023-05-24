<style>
    .balance_sheat_head{
        width: 100%;
        display: inline-flex;
    }

    .no-print{
        position: relative;
        top: 15px;
        z-index: 99999;
        border: 2px solid white;
        border-radius: 20px 20px 20px 0px;
        box-shadow: 1px 0px 5px 1px #ccc;
        outline:none;
    }
</style>
<form action="<?php echo base_url();?>transection_account/balance_sheet_pdf" method="post">
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
<div class="col-lg-12 demo thisrow" id="trial_balance_print" style="">
    <div class="balance_sheat_head row">
    </div>
</div>
<div class="table-responsive" id="table_export_exp">
    <table class="table table-bordered datatable" id="table_export_exp">
        <thead>
            <tr>
                <td colspan="2" align="center" style="background-color:white !important">
                    <h2 style="font-size:18px !Important;line-height: 4;"><?php echo $_SESSION['school_name']; ?> <?php echo get_phrase('balance_sheet'); ?> <?php echo $from_to; ?></h2>
                </td>
            </tr>
            <tr>
                <th width="50%" class="span3"><div><?php echo get_phrase('title');?></div></th>
                <th width="50%"><div><?php echo get_phrase('amount');?></div></th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td><strong><?php echo $data_frp_array['balance_sheet_assets']['account_head']; ?></strong></td>
            <td><?php //echo $debit;  ?></td>
        </tr>
        <?php
            $total_assets = 0;
            foreach($child_coa_list['balance_sheet_assets'] as $ch_key=>$ch_val)
            {
                $total_sub_assets = 0;
        ?>
            <tr>
                <td class="coa_sub_head"><?php echo $ch_val['account_head']; ?></td>
                <td><?php //echo $debit;  ?></td>
            </tr>
            <?php

            foreach($ch_val['child_coa']as $ch_key1=>$ch_val1)
            {
                $total_debit = 0;
                $total_credit = 0;
                $amount = 0;
                if ($ch_val1['account_type'] == 2)
                {
                    $amount = $ch_val1['debit'] - $ch_val1['credit'];
                }
                elseif ($ch_val1['account_type'] == 1)
                {
                    $amount = $ch_val1['credit'] - $ch_val1['debit'];
                }

                $total_sub_assets = $total_sub_assets + $amount;
                if ($ch_val['coa_id'] != $ch_val1['coa_id'])
                {
            ?>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $ch_val1['account_head']; ?></td>
                    <td>
                        <?php
                            if ($amount < 0) {
                                echo "(" . number_format((-1 * $amount)) . ")";
                            } else {
                                echo number_format($amount);
                            }
                        ?>
                    </td>
                </tr>
            <?php } } ?>
            <tr>
                <td class="coa_sub_total"><?php echo get_phrase('total') . ' ' . $ch_val['account_head']; ?></></td>
                <td>
                <?php
                    if ($total_sub_assets < 0) {
                        echo "(" . number_format((-1 * $total_sub_assets)) . ")";
                    }else
                    {
                        echo number_format($total_sub_assets);
                    }
                ?> 
                </td>
            </tr>
            <tr class="custom_tr_space" >
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            <?php
            $total_assets += $total_sub_assets;
            }
        ?>
        <tr class="total">
            <td><?php echo get_phrase('total_Assets'); ?></td>
            <td><strong><?php echo number_format($total_assets);  ?></strong></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><strong><?php echo $data_frp_array['balance_sheet_liabilities']['account_head']; ?></strong></td>
            <td><?php //echo $debit;  ?></td>
        </tr>
        <?php
        $total_liabilities = 0;

        foreach($child_coa_list['balance_sheet_liabilities'] as $ch_key=>$ch_val)
        {
            $total_debit = 0;
            $total_credit = 0;
            $amount = 0;
            $total_sub_liabilities = 0;

            foreach($ch_val['child_coa']as $ch_key1=>$ch_val1)
            {
             // if ($ch_val['coa_id'] != $ch_val1['coa_id'])
                {
                    $total_debit = 0;
                    $total_credit = 0;
                    $amount = 0;

                    if ($ch_val1['account_type'] == 2)
                    {
                        $amount = $ch_val1['debit'] - $ch_val1['credit'];
                    }
                    elseif ($ch_val1['account_type'] == 1)
                    {
                        $amount = $ch_val1['credit'] - $ch_val1['debit'];
                    }
                    $total_sub_liabilities = $total_sub_liabilities + $amount;
                    if ($ch_val['coa_id'] != $ch_val1['coa_id'])
                    {

                ?>
                        <tr>
                            <td class="coa_sub_head"><?php echo $ch_val1['account_head']; ?></td>
                            <td>
                            <?php
                                if ($amount < 0) {
                                    echo "(" . number_format((-1 * $amount)) . ")";
                                } else {
                                    echo number_format($amount);
                                }
                                ?>
                            </td>
                        </tr>
                        <?php } } ?>
                <?php } ?>
            </tr>

            <tr class="coa_sub_total">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo get_phrase('total'). ' ' .$ch_val['account_head'] ; ?></td></td>
                <td>
                    <?php
                        if ($total_sub_liabilities < 0)
                        {
                            echo "(".number_format((-1*$total_sub_liabilities)).")";
                        }
                        else
                        {
                            echo number_format($total_sub_liabilities);
                        }
    
                        $total_liabilities += $total_sub_liabilities;
                    ?> 
                </td>
            </tr>
        <?php } ?>
        <?php /* Total Profit Start */ ?>
        <tr class="total">
            <td><strong><?php echo get_phrase('total_net_profit'); ?></strong></td>
            <td><strong>
                <?php 
                    $net_profit = $revenue-$expanse;
                    echo number_format($net_profit);
                ?>
            </strong></td>
        </tr>
        <?php /* Total Profit End */ ?>
        <tr class="total">
            <td><strong><?php echo get_phrase('total_liabilities'); ?></strong></td>
            <td><strong><?php echo number_format($total_liabilities);  ?></strong></td>
        </tr>
        <?php /* Total liabilities End */ ?>

        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>

        <tr class="total">
            <td><strong><?php echo get_phrase('total_libilites_and_equity'); ?></strong></td>
            <td><strong><?php echo number_format($total_liabilities+$net_profit);  ?></strong></td>
        </tr>

        <?php /* Net Income End */ ?>

        </tbody>
    </table>

</div>

<!-- Get trial balace End --->



<!--
<button id="cmd">generate PDF</button>-->


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



<style type="text/css">
    @media print
    {
        html
        {
            transform: scale(0.8);
        }
    }
    .balance_sheat_head
    {
        /* border:1px solid crimson !important;*/
        text-align: center;
    }
    .balance_sheat_head div.school_name
    {
        clear:both;

    }
    .balance_sheat_head div.school_name img
    {
       width:220px;
       height: 120px;

    }
    .balance_sheat_head div.school_name h2{
        font-size: 14px;
        font-weight: bold;
    }
    .balance_sheat_head div.current_date
    {
        clear:both;

    }
    .balance_sheat_head div.current_date h2{
        font-size: 14px;
        font-weight: bold;
    }
    td.coa_sub_head
    {
        text-decoration: underline;

        padding:10px 0px 10px 23px !important;

    }

    td.coa_sub_total
    {
        font-style: italic;
        padding: 0 0 0 22px !important;
    }
    tr.total{
        background:#f5f5f6;
        font-weight: bold;
    }
    tr.custom_tr_space{

    }

</style>



<script src="<?=base_url()?>assets/table2excel.js"></script>
<script>
    $(function () {
        $("#btnExport").click(function () {
            $("#table_export_exp").table2excel({
                filename: "Balance Sheet From <?= $start_date ?> To <?= $end_date ?>.xls"
            });
        });
    });
</script>