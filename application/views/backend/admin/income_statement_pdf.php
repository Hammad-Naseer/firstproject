<style>

    table {  
    color: #333;
    font-family: Helvetica, Arial, sans-serif;
    border-collapse: 
    collapse; border-spacing: 0;
    width: 100%;
    border: 1px solid black;
    font-size: 12px;
    }
    td, th { /* No more visible border height: 25px; */
    transition: all 0.3s;  /* Simple transition for hover effect 
    */
    padding-left: 3px;
    }
    th {  
    background: #DFDFDF;  /* Darken header a bit */
    font-weight: bold;
    }
    td {  
    background: #FAFAFA;
    }
    /* Cells in even rows (2,4,6...) are one color */        
    tr:nth-child(even) td { background: #F1F1F1; }         
    tr:nth-child(odd) td { background: #FEFEFE; }  
    tr td:hover { background: #666; color: #FFF; }  
    
</style>
<div id="header" style="height: 100px; width: 100%;">
    <div style="float: left; margin-top: -15px; height: 100px; width: 150px;"> 
        <?php
        $d_school_id = $_SESSION['school_id'];
        {
            $logo=system_path($_SESSION['school_logo']);
            if($_SESSION['school_logo']=="" || !is_file($logo))
            {
            ?>
            <a href="">
                <img style="width: 150px; height: 100px; margin-top: -15px;" src="<?php //echo base_url();?>assets/images/gsims_logo.png">
            </a>
            <?php
            }
            else
            {
                $img_size = getimagesize("uploads/".$_SESSION['folder_name']."/".$_SESSION['school_logo']."");
                $img_width = $img_size[0];
                $img_height = $img_size[1];

            ?>
            <a href="">
            <img style="margin-top: -15px;
                width:
                <?php
                    if ($img_width>150) {
                        $img_width = 150;
                    }
                    echo $img_width."px;";
                ?>
                height:
                <?php
                    if ($img_height>100) {
                        $img_height = 100;
                    }
                    echo $img_height."px;";
                ?>
                " src="<?php //echo base_url();?>uploads/<?php echo $_SESSION['folder_name'].'/'.$_SESSION['school_logo']; ?>">
            </a>
            <?php
            }
        }
        
        ?>
    </div>
    <div style="float: left; margin-top: -15px; margin-left: 100px;"> 
        <h2 style="text-transform: uppercase; letter-spacing: 5px;">
            <b><?php echo $_SESSION['school_name']; ?></b>
        </h2>
        <h4 style="margin-top: -25px;margin-left: -50px !important; text-transform: uppercase; letter-spacing: 5px;">
            <?php echo get_phrase('income_statement_report');?><br>
            <?php echo "From : " .$start_date."  To : ".$end_date; ?>
        </h4>
    </div>
</div>




<div id="table_data" style="margin-top: 20px;">
    <table border="1px">
    <thead>
        <tr>
            <th>
                <?php echo get_phrase('title'); ?>
            </th>
            <th>
                <?php echo get_phrase('amount'); ?>
            </th>
        </tr>
    </thead>
    <tbody>
    <tr>
            <td><strong><b><?php echo $data_frp_array['income_stmt_sales']['account_head']; ?></strong></b></td>
            <td><?php //echo $debit;  ?></td>
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
                                echo "(" . number_format((-1 * $amount)) . ")";
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
            if ($total_sales_child < 0)
            {
                echo "(".number_format((-1*$total_sales_child)).")";
            }
            else
            {
                echo number_format($total_sales_child);
            }
            //echo $total_sales; ?> </td>
        </tr>
        <?php
        }
        ?>
    <tr class="total">
        <td><strong><?php echo get_phrase('total_revenue'); ?></strong></td>
        <td>
            <?php
            if ($total_sales < 0)
            {
            echo "(".number_format((-1*$total_sales)).")";
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
    <tr>
        <td><strong><?php echo $data_frp_array['income_stmt_expense']['account_head']; ?></strong></td>
        <td><?php //echo $debit;  ?></td>
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
                                echo "(" .number_format( (-1 * $amount) ). ")";
                            } else {
                                echo number_format($amount);
                            }

                            ?></td>
                    </tr>
                    <?php
                }
               // $total_expense += $total_sub_expense;
            }
            ?>
            <tr class="coa_sub_total">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo get_phrase('total'). ' ' .$ch_val['account_head'] ; ?></td>
                <td><?php
                    if ($total_sub_expense < 0)
                    {
                        echo "(".number_format((-1*$total_sub_expense)).")";
                    }
                    else
                    {
                        echo number_format($total_sub_expense);
                    }
                    //echo $total_sales; ?> </td>
            </tr>
            <?php
        }
        ?>
        <tr class="total">
            <td><strong><?php echo get_phrase('total_expense'); ?></strong></td>
            <td><?php

                if ($total_sub_expense < 0)
                {
                    echo "(".number_format((-1*$total_sub_expense)).")";
                }
                else
                {
                    echo number_format($total_sub_expense);
                }

                ///echo $total_expense;  ?></td>
        </tr>
        <?php /* Total expense End */ ?>


        <?php /* Net Income start */ ?>

        <tr>
            <td><strong>&nbsp;</strong></td>
            <td><strong>&nbsp;</strong></td>
        </tr>

        <tr class="total">
            <td><strong><?php echo get_phrase('net_profit');  ?></strong></td>
            <td><strong><?php $net_income = $total_sales-$total_sub_expense;

                    if ($net_income < 0)
                    {
                        echo "(".number_format((-1*$net_income)).")";
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
    <?php
        if (empty($data_frp_array)) {
        ?>
        <div style="text-align: center;">
            <h2><?php echo get_phrase('no_data_available');?></h2>
        </div>
        <?php
        }
    ?>
</div>



<div style="margin-top: 15px;">
	<table>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
			<?php 
				echo get_phrase('Date');?> :
				<?php
				    echo date("Y-m-d h:i:s a");
				?>
			</td>
		</tr>
	</table>
</div>


