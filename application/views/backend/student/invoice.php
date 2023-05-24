<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline"><?php echo get_phrase('payments');?></h3>
    </div>
	<div class="col-md-12">
        <div class="tab-pane box active table-responsive" id="list">
            <table  class="table table-bordered datatable" id="table_export">
            	<thead>
            		<tr>
                		<th><div><?php echo get_phrase('challan_number');?></div></th>
                		<th><div><?php echo get_phrase('month');?>/<?php echo get_phrase('year');?></div></th>
                		<th><div><?php echo get_phrase('total_amount');?></div></th>
                		<th><div><?php echo get_phrase('due_date');?></div></th>
                		<th><div><?php echo get_phrase('status');?></div></th>
					</tr>
				</thead>
                <tbody>
               <?php 
                    foreach($invoices as $row){
				    $class_unpaid="";
				   	if($row['status']!=5)
					{
						$class_unpaid="payment-due";
					}
			   ?>
                    <tr class="<?php echo $class_unpaid;?>">
                        <td><?php echo $row['chalan_form_number'];?></td>
                        <td><?php echo date('M',strtotime($row['fee_month_year']))." / ".date('Y', strtotime($row['fee_month_year']));?></td>
                        <td><?php echo $row['actual_amount'];?></td>
                        <td><?php echo convert_date($row['due_date']);?></td>
                        <td>
                            <?php 
                            
                        if($row['status']==5)
                        {
                            echo '<span style="color:green;">Paid<span>';}
                        else
                        {
                            echo '<span style="color:red;">Unpaid<span>';
                        }  
                    ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
		</div>
	</div>
</div>

