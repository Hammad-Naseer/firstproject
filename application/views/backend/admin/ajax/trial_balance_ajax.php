<style>
.balance_sheat_head{width:100%;display:inline-flex}.no-print{position:relative;top:15px;z-index:99999;border:2px solid #fff;border-radius:20px 20px 20px 0;box-shadow:1px 0 5px 1px #ccc;outline:0}.main-account{background-color:#012b3c!important;color:#fff}@media print{html{transform:scale(.8)}}.balance_sheat_head{text-align:center}.balance_sheat_head div.school_name{clear:both}.balance_sheat_head div.school_name img{width:220px;height:120px}.balance_sheat_head div.school_name h2{font-size:14px;font-weight:700}.balance_sheat_head div.current_date{clear:both}.balance_sheat_head div.current_date h2{font-size:14px;font-weight:700}
.main-head {
    background-color: #012b3c !important;
    color: white !important;
}
</style>
<form action="<?php echo base_url();?>transection_account/trial_balance_pdf" method="post">
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
<div class="col-lg-12 demo thisrow" id="trial_balance_print" style="">
    <!--<button id="print_btn" class="btn btn-primary" style="position: relative;top: 10px;"><?php echo get_phrase('print');?></button>-->
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
            <!--</div>-->
        <!--<div class="col-md-10 text-center">-->
        <!--    <h2><?php //echo $_SESSION['school_name']; ?> </h2>-->
        <!--    <h3><?php //echo get_phrase('trial_balance'); ?> <?php echo "From : " .$start_date."  To : ".$end_date; ?></h3>-->
        <!--</div>-->
    </div>
</div>
<div class="table-responsive">
<table class="table table-bordered datatable" id="table_export_exp">
    <thead>
    <tr style="background-color:white !important;">
        <td style="background-color:white !important;" colspan="4" align="center">
            <h2><?php echo $_SESSION['school_name']; ?></h2>
            <h3> <?php echo get_phrase('trial_balance'); ?> <?php echo "From : ".$start_date. " To : ".$end_date; ?></h3>
        </td>
    </tr>
    <tr>
        <th><div><?php echo get_phrase('account_title');?></div></th>
        <th class="span3"><div><?php echo get_phrase('chart_of_account');?></div></th>
        <th><div><?php echo get_phrase('debit');?></div></th>
        <th><div><?php echo get_phrase('credit');?></div></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $total_debit=0;
    $total_credit=0;
    
    foreach($parent_coa_list as $row){
        
        $count=0;
    ?>
        <tr class="main-account">
            <td><strong><?php echo $row['account_number']; ?></strong></td>
            <td><strong><?php echo $row['account_head']; ?></strong></td>
            <td><?php //echo $debit;  ?></td>
            <td><?php //echo $credit;  ?></td>
        </tr>
            <?php

                foreach($child_coa_list[$row['coa_id']] as $row1)
                {
                    $child_total = array('debit'=>0, 'credit'=>0);
                    $i = 1;
                    foreach ($row1['child_coa'] as $child)
                    {
                        $child_total['debit']=$child_total['debit']+$child['debit'];
                        $child_total['credit']=$child_total['credit']+$child['credit'];
                    }
            ?>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; >> <b><?php echo $row1['account_number']; ?></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; >> <b><?php echo $row1['account_head']; ?></b>
                        </td>
                        <td>
                            <?php
                                $amount = 0;
                                if($row1['type'] == 2){
                                    $amount = $child_total['debit'] - $child_total['credit'];
                                    $total_debit  = $total_debit  + $amount;
                                    if ($amount<0)
                                    {
                                        $d = (-1)*($amount);
                                        echo "(". number_format($d).")";
                                    }
                                    else
                                    {
                                        echo number_format($amount);
                                    }
                                }else
                                {
                                    echo "";
                                }
    
                            ?>
                        </td>
                        <td>
                            <?php
                                $amount1 = 0;
                                if($row1['type'] == 1){
                                    $amount1 = $child_total['credit'] - $child_total['debit'];
                                    $total_credit  = $total_credit + $amount1;
    
                                    if ($amount1<0){
                                        $c = (-1)*($amount1);
                                        echo "(".number_format($c).")";
                                    }else{
                                        echo number_format($amount1);
                                    }
                                }else{
                                    echo "";
                                }
                            ?>
                        </td>
                    </tr>

                <?php
                
                $sub_coa_rec=$this->db->query("select * from ".get_school_db().".chart_of_accounts where parent_id=".$row1['coa_id']." AND school_id = ".$_SESSION['school_id']."")->result_array();
                
                if(count($sub_coa_rec) >  0) {
                    
                    foreach($sub_coa_rec as $sub_ac){
                        echo '<tr>';    
                            $sub_child_total = array('debit'=>0, 'credit'=>0);
                        
                            echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; >> '.$sub_ac['account_number'].'</td>';
                            echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; >> '.$sub_ac['account_head'].'</td>';

                        ?>
                            <td>
                        <?php
                            
                            $get_debit_credit = $this->db->query("SELECT SUM(debit) AS D,SUM(credit) C FROM ".get_school_db().".journal_entry WHERE coa_id = '".$sub_ac['coa_id']."' AND DATE_FORMAT(`entry_date`, '%Y-%m-%d') BETWEEN '$start_date1' AND '$end_date1' ")->row();
                            $sub_child_total['debit'] = $get_debit_credit->D;
                            $sub_child_total['credit'] = $get_debit_credit->C;

                            $amount3 = 0;
                            if($sub_ac['account_type'] == 2)
                            {
                                $amount3 = $sub_child_total['debit'] - $sub_child_total['credit'];
                                if ($amount3<0)
                                {
                                    $f = (-1)*($amount3);
                                    echo "(".number_format($f).")";
                                }
                                else
                                {
                                    echo number_format($amount3);
                                }
                            }
                        ?>
                        </td>
                        <td>
                            <?php
                            $amount4 = 0;
                            if($sub_ac['account_type'] == 1)
                            {
                                $amount4 = $sub_child_total['credit'] - $sub_child_total['debit'];
                                // $total_credit  = $total_credit + $amount4;
                                if ($amount4<0)
                                {
                                    $e = (-1)*($amount4);
                                    echo "(".number_format($e).")";
                                }
                                else
                                {
                                    echo number_format($amount4);
                                }
                            }
                            else
                            {
                                echo "";
                            }
                            ?></td>
                        
                        </tr>
                    
                    <?php
                    $sub_sub_coa_rec=$this->db->query("select * from ".get_school_db().".chart_of_accounts where parent_id=".$sub_ac['coa_id']." AND school_id = ".$_SESSION['school_id']."")->result_array();
                    foreach($sub_sub_coa_rec as $sub_ac){
                        echo '<tr>';    
                            $sub_child_total = array('debit'=>0, 'credit'=>0);
                        
                            echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; >> '.$sub_ac['account_number'].'</td>';
                            echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; >> '.$sub_ac['account_head'].'</td>';

                        ?>
                        <td>
                        <?php
    
                            $get_debit_credit = $this->db->query("SELECT SUM(debit) AS D,SUM(credit) C FROM ".get_school_db().".journal_entry WHERE coa_id = '".$sub_ac['coa_id']."' AND DATE_FORMAT(`entry_date`, '%Y-%m-%d') BETWEEN '$start_date1' AND '$end_date1' ")->row();

                            $sub_child_total['debit'] = $get_debit_credit->D;
                            $sub_child_total['credit'] = $get_debit_credit->C;

                        $amount33 = 0;

                            $amount33 = $sub_child_total['debit'] - $sub_child_total['credit'];
                            // $total_debit  = $total_debit  + $amount33;

                            if ($amount33<0)
                            {
                                $f = (-1)*($amount33);
                                echo "(".number_format($f).")";
                            }
                            else
                            {
                                echo number_format($amount33);
                            }

                        ?>
                        </td>
                        <td><?php

                            $amount4 = 0;

                            if($sub_ac['type'] == 1)
                            {
                                $amount4 = $sub_child_total['credit'] - $sub_child_total['debit'];

                                $total_credit  = $total_credit + $amount4;

                                if ($amount4<0)
                                {
                                    $e = (-1)*($amount4);
                                    echo "(".number_format($e).")";
                                }
                                else
                                {
                                    echo number_format($amount4);
                                }
                                //echo $sub_child_total['credit'];
                            }
                            else
                            {
                                echo "";
                            }
                            ?></td>
                        
                        </tr>
                    <?php } ?>
                        
                    <?php } ?>
                    <tr>
                        <td>&nbsp;</td>
                         <td>&nbsp;</td>
                         <td>&nbsp;</td>
                         <td>&nbsp;</td>
                    </tr>
                    
                    <?php
                }
                
                }
                ?>

                     <tr>
                        <td>&nbsp;</td>
                         <td>&nbsp;</td>
                         <td>&nbsp;</td>
                         <td>&nbsp;</td>
                    </tr>
       <?php } ?>
        <tr class="main-head">

        <td></td>
        <td><strong> <?php echo get_phrase('total_values');?> (<?php echo get_phrase('pkr');?>)</strong> </td>
        <td>
            <strong>
            <?php
				//$total_debit = -8999;
				if ($total_debit<0)
				{
					$t_d = (-1)*($total_debit);
					echo "(".number_format($t_d).")";
				}
				else
				{
					echo number_format($total_debit);
				}
			   // echo $total_debit;
            ?>
            </strong>
        </td>
        <td>
            <strong>
            <?php
                if ($total_credit<0)
                {
                    $t_c = (-1)*($total_credit);
                    echo "(".number_format($t_c).")";
                }
                else
                {
                    echo number_format($total_credit);
                }
                 // echo $total_credit;
            ?>
            </strong>
        </td>


    </tr>
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

<script src="<?=base_url()?>assets/table2excel.js"></script>
<script>
    $(function () {
        $("#btnExport").click(function () {
            $("#table_export_exp").table2excel({
                filename: "Trial Balance From <?= $start_date ?> To <?= $end_date ?>.xls"
            });
        });
    });
</script>