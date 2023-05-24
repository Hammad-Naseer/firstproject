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
            <?php echo get_phrase('trial_balance_report');?> <br>
            <?php echo "From : " .$start_date."  To : ".$end_date; ?>
        </h4>
    </div>
</div>




<div id="table_data" style="margin-top: 20px;">
    <table border="1px">
    <thead>
        <tr>
            <th>
                <?php echo get_phrase('account_title'); ?>
            </th>
            <th>
                <?php echo get_phrase('chart_of_account'); ?>
            </th>
            <th>
                <?php echo get_phrase('debit'); ?>
            </th>
            <th>
                <?php echo get_phrase('credit'); ?>
            </th>
        </tr>
    </thead>
    <tbody>
    <?php
    $total_debit=0;
    $total_credit=0;
    
    foreach($parent_coa_list as $row){
        //print_r($row);
        $count=0;
        ?>
        <tr>
            <td><strong><?php echo $row['account_number']; ?></strong></td>
            <td><strong><?php echo $row['account_head']; ?></strong></td>
            <td><?php //echo $debit;  ?></td>
            <td><?php //echo $credit;  ?></td>
        </tr>
              <?php  /*$coa_rec=$this->db->query("select * from ".get_school_db().".chart_of_accounts where parent_id=".$row['coa_id']."
                AND school_id = ".$_SESSION['school_id']."")->result_array();*/

                foreach($child_coa_list[$row['coa_id']] as $row1)
                {

                    $child_total = array('debit'=>0, 'credit'=>0);
                    ?>

                    <?php
                    $i = 1;
                    foreach ($row1['child_coa'] as $child)
                    {
                        $child_total['debit']=$child_total['debit']+$child['debit'];
                        $child_total['credit']=$child_total['credit']+$child['credit'];
                    }

                    //if ($row['type'] == 2)
                    //{
                        //$total_debit = $total_debit + $child_total['debit'];
                   // }

                    //if ($row['type'] == 1)
                    //{
                    //    $total_credit = $total_credit + $child_total['credit'];
                   // }

                   ?>
                    <tr>
                        <td><?php echo $row1['account_number']; //echo ++$count; ?></td>

                        <td>
                           <?php echo $row1['account_head']; ?>
                        </td>
                        <td><?php
                            $amount = 0;
                            if($row1['type'] == 2)
                            {
                                $amount = $child_total['debit'] - $child_total['credit'];

                                $total_debit  = $total_debit  + $amount;

                                if ($amount<0)
                                {
                                    $d = (-1)*($amount);
                                    echo "(".number_format($d).")";
                                }
                                else
                                {
                                    echo number_format($amount);
                                }
                            }
                            else
                            {
                                echo "";
                            }

                            ?></td>
                        <td><?php

                            $amount1 = 0;

                            if($row1['type'] == 1)
                            {
                                $amount1 = $child_total['credit'] - $child_total['debit'];

                                $total_credit  = $total_credit + $amount1;

                                if ($amount1<0)
                                {
                                    $c = (-1)*($amount1);
                                    echo "(".number_format($c).")";
                                }
                                else
                                {
                                    echo number_format($amount1);
                                }
                                //echo $child_total['credit'];
                            }
                            else
                            {
                                echo "";
                            }
                            ?></td>
                    </tr>

                    <?php

                }?>

                     <tr>
                        <td>&nbsp;</td>
                         <td>&nbsp;</td>
                         <td>&nbsp;</td>
                         <td>&nbsp;</td>
                    </tr>
       <?php 
       // $total_debit+=$debit;
       // $total_credit+=$credit;
        }
        ?>
    <tr>

        <td></td>
        <td><strong> <?php echo get_phrase('total_values');?> (<?php echo get_phrase('pkr');?>)</strong> </td>
        <td>
            <strong>
            <?php
				//$total_debit = -8999;
				if ($total_debit<0)
				{
					$t_d = (-1)*($total_debit);
					echo "(".$t_d.")";
				}
				else
				{
					echo $total_debit;
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
    <?php
        if (empty($parent_coa_list)) {
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


