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
            <a href=""><img style="width: 150px; height: 100px; margin-top: -15px;" src="<?php //echo base_url();?>assets/images/gsims_logo.png"></a>
        <?php } else {
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
            <?php echo $_SESSION['school_name']; ?>
        </h2>
        <h4 style="margin-top: -15px; text-transform: uppercase; letter-spacing: 5px;">
            <?php echo get_phrase('purchase_voucher');?>
        </h4>
    </div>
</div>


<div id="filters" style="padding: 5px;">
		<table>
			<tr>
				<td>
					<strong>
	            	<?php echo get_phrase('voucher_number');?> :
	            	</strong>
	            	<?php echo $purchase_voucher_detail_arr[0]['voucher_number'];?>
            	</td>
				<td>
					<strong>
	            	<?php echo get_phrase('voucher_date');?> :
	            	</strong>
	            	<?php 
	            		echo date_view($purchase_voucher_detail_arr[0]['voucher_date']);
	            	?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>
	            	<?php echo get_phrase('supplier');?> :
	            	</strong>
	            	<?php
	            	$depositor_detail = get_supplier_details($purchase_voucher_detail_arr[0]['supplier_id']);
	            	echo $depositor_detail[0]['name'];
	            	?>
				</td>
				<td>
					<strong>
	            	<?php echo get_phrase('date_posted');?> :
	            	</strong>
	            	<?php echo date_view($purchase_voucher_detail_arr[0]['date_posted']);?>
				</td>
			</tr>
		</table>
</div>






<div id="table_data" style="margin-top: 20px;">
    <table border="1px">
    <thead>
    <tr>
    <th><?php echo get_phrase('bill_number');?></th>
    <th><?php echo get_phrase('description');?></th>
    <th><?php echo get_phrase('quantity');?></th>
    <th><?php echo get_phrase('amount');?></th>
    </tr>
    </thead>
    <tbody>
    <?php
        $total = 0;
        foreach ($purchase_voucher_detail_arr as $key => $value) {
    ?>
    <tr>
        <td><?php echo $value['bill_number'];?></td>
        <td><?php echo $value['description'];?></td>
        <td><?php echo $value['qty'];?></td>
        <td>
	        <?php
	        	$total += $value['amount'];
	        	echo $value['amount'];
	        ?>
        </td>
    </tr>

    <?php } if ($total>0) { ?>
    <tr>
        <td colspan="3" style="text-align: right;"><?php echo get_phrase('total');?></td>
        <td><?php echo $total;?></td>
        <td></td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
    <?php if (empty($purchase_voucher_detail_arr)) { ?>
        <div style="text-align: center;">
            <h2><?php echo get_phrase('no_data_available');?></h2>
        </div>
    <?php } ?>
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
				echo get_phrase('submitted_by');?> :
				<?php
				$user_detail = get_user_login_detail($purchase_voucher_detail_arr[0]['submitted_by']);
				echo $user_detail['name'];
				?>
			</td>
			<td>
			<?php
				echo get_phrase('approved_by');?> :
				<?php
				$user_detail = get_user_login_detail($purchase_voucher_detail_arr[0]['posted_by']);
				echo $user_detail['name'];
				?>
			</td>
		</tr>
	</table>
</div>


