<?php
    if($this->session->flashdata('club_updated')){
    	echo '<div align="center">
    	<div class="alert alert-success alert-dismissable">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        '.$this->session->flashdata('club_updated').'
    	</div> 
    	</div>';
    }
?>

<script>
$(window).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});

</script>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php echo get_phrase('deductions'); ?>
        </h3>
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
    </div>
</div>

    <div class="col-lg-12 col-md-12 col-sm-12">
        <table class="table table-bordered table_export" id="deductions" data-step="2" data-position='top' data-intro="deduction records">
            <thead>
                <tr>
                    <th style="width:34px;">
                        <?php echo get_phrase('s_no');?>
                    </th>
                    <th>
                        <?php echo get_phrase('deduction_title');?>
                    </th>
                    <!--<th>-->
                    <!--    <?php //echo get_phrase('deduction_percentage');?>-->
                    <!--</th>-->
                    <th>
                        <?php echo get_phrase('credit_COA');?>
                    </th>
                    <th>
                        <?php echo get_phrase('status');?>
                    </th>
                    <th style="width:100px;">
                        <?php echo get_phrase('actions');?>
                    </th>
                </tr>
            </thead>
            <tbody>
            
        <?php if(count($deductions)>0)
        {
		  $a=0;

            foreach ($deductions as $row)
            {
        ?>
                <?php $a++; ?>
            <tr>
                <td><?= $a; ?></td>
                <td><?= $row['deduction_title']; ?></td>
                <!--<td><?= $row['deduction_percentage']; ?>%</td>-->
                <td>
                <?php
                    $coa_details =  get_coa($row['credit_coa_id']);
                    echo $coa_details['account_head']." (".$coa_details['account_number'].")";
                ?>
                </td>
                <td>
    			<?php 
    				if ($row['status'] == 1)
    					echo "<span style='color:green'>Active</span>";
    				else
    					echo "<span style='color:red'>Inactive</span>";
    			?>
    			</td>
                <td class="td_middle">
        			<?php //if (right_granted(array('locations_manage', 'locations_delete'))) { ?>
        				<div class="btn-group align-middle" data-step="3" data-position='left' data-intro="press this to edit or delete the deduction details">
        					<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        					<?php echo get_phrase('action');?>
        						
        						<span class="caret">
        						</span>
        					</button>
        					<ul class="dropdown-menu dropdown-default pull-right" role="menu">
        						<?php 
                                //if (right_granted('locations_manage'))
                                //{
                           		 ?>
        					
        						<!-- EDITING LINK -->
        						<li>
        							<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_deduction/<?php echo $row['deduction_id'];?>');">
        								<i class="entypo-pencil">
        								</i>
        								<?php echo get_phrase('edit');?>
        							</a>
        						</li>
        						<?php 
        						//}
        						
        						//if (right_granted('locations_delete'))
        						//{
        						?>
        						<li class="divider">
        						</li>
        
        						<!-- DELETION LINK -->
        						<li>
        							<a href="#" onclick="confirm_modal('<?php echo base_url();?>payroll/delete_deduction/<?php echo $row['deduction_id'];?>');">
        								<i class="entypo-trash">
        								</i>
        								<?php echo get_phrase('delete');?>
        
        							</a>
        						</li>
        						<?php 
        						//}
        						?>
        					</ul>
        				</div>
        			<?php 
        			// }
        			?>	
        			</td>
                </tr>
            <?php } } ?>
            </tbody>
        </table>
    </div>

<script>

    var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_deduction/")';
    var datatable_btn     = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add deduction details' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_deduction');?></a>";    

</script>