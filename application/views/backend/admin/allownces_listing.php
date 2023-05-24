<style>
	.err_div{position: absolute;
        color: red;
        text-align: center;
	}
</style>
<?php
		
  if($this->session->flashdata('club_updated')){
     echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('allownces'); ?>
        </h3>
    </div>
</div>
<div class="col-lg-12 col-md-12">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export">
	<thead>
		<tr>
			<th><?php echo get_phrase('s_no');?>		
		</th>
		<th>
			<?php echo get_phrase('allownce_title');?>
		</th>
		<th>
			<?php echo get_phrase('allownce_percentage');?>
		</th>
		<th>
			<?php echo get_phrase('status');?>
		</th>
		
		<th>
			<?php echo get_phrase('action');?>	
		</th>
		</tr>
	</thead>
	<tbody>
		<?php 
	    $count = 1;
	    foreach($allownces as $row)
	    {
		?>
		<tr>
			<td class="td_middle">
				<?php echo $count++;?>
			</td>
			<td>
			    <?php echo $row['allownce_title'];?>
			</td>
			<td>
			    <?php echo $row['allownce_percentage'];?>
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
		
			<div class="btn-group align-middle" data-step="6" data-position='left' data-intro="if you want allownce record edit or delete then press this button you have the option edit/delete">
					<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
					<?php echo get_phrase('action');?>
						
						<span class="caret">
						</span>
					</button>
					<ul class="dropdown-menu dropdown-default pull-right" role="menu">
						
					
						<!-- EDITING LINK -->
						<li>
							<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_allownce/<?php echo $row['allownce_id'];?>');">
								<i class="entypo-pencil">
								</i>
								<?php echo get_phrase('edit');?>
							</a>
						</li>
					
						<li class="divider">
						</li>

						<!-- DELETION LINK -->
						<li>
							<a href="#" onclick="confirm_modal('<?php echo base_url();?>payroll/delete_allownces/<?php echo $row['allownce_id'];?>');">
								<i class="entypo-trash">
								</i>
								<?php echo get_phrase('delete');?>

							</a>
						</li>
					</ul>
				</div>
				
			</td>
		</tr>
		<?php
	    }
		?>
	</tbody>
</table>
</div>

<script type="text/javascript">

$(window).on("load" , function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});



<?php 
//if(right_granted('locations_manage'))
{
    ?>
    var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_allownce/")';
    var datatable_btn     = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Add new allownce here' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_allownce');?></a>";    
<?php } ?>

</script>

<!--Datatables Add Button Script-->
