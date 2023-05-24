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
        <h4 style="margin-top: -25px; text-transform: uppercase; letter-spacing: 5px;">
            <?php echo get_phrase('ledger_report');?>
        </h4>
        <b>FROM : </b> <?= date_dash($start_date); ?> - <b>To : </b> <?= date_dash($end_date); ?>
    </div>
</div>




<div id="table_data" style="margin-top: 20px;">
    <table border="1px">
    <thead>
        <tr>
            <th>
                <?php echo get_phrase('type'); ?>
            </th>
            <th>
                <?php echo get_phrase('date'); ?>
            </th>
            <th>
                <?php echo get_phrase('transection_detail'); ?>
            </th>
            <th>
                <?php echo get_phrase('debit'); ?>
            </th>
            <th>
                <?php echo get_phrase('credit'); ?>
            </th>
            <th>
                <?php echo get_phrase('balance'); ?>
            </th>
        </tr>
    </thead>
    <tbody>
    <?php
        $j=$start_limit+1;
        foreach ($journal_result as $row)
        {
    ?>
        <tr>
            <td>
              <?php echo journal_entry_type($row['entry_type']); ?>
            </td>
            <td>
                <?php
                echo date("d/m/Y",strtotime($row['entry_date'])); ?>
            </td>
            <td>
                <?php echo $row['detail'];
                ?>
            </td>
            <td>
                <?php
                if ($row['debit'] < 0)
                {
                    $d = (-1) * ($row['debit']);
                    echo "(" . number_format($d) . ")";
                } else
                {
                    echo number_format($row['debit']);
                    //echo "=";
                    //echo $row['coa_id'];
                }

                $debit += $row['debit'];

                ?>
            </td>
            <td>
                <?php
                if ($row['credit'] < 0)
                {
                    $c = (-1) * ($row['credit']);
                    echo "(" . number_format($c) . ")";
                } else
                {
                    echo number_format($row['credit']);
                    //echo "=";
                    // echo $row['coa_id'];
                }
                $credit += $row['credit'];
                ?>
            </td>
            <td><?php
                $balance = $debit - $credit;
                if ($balance < 0)
                {
                    $b = (-1) * ($balance);
                    echo "(" . $b . ")";
                } else
                {
                    echo number_format($balance);
                }
                ?></td>
        </tr>
    <?php
        $j++;
        }
    ?>
    </tbody>
    </table>
    <?php
        if (empty($journal_result)) {
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


