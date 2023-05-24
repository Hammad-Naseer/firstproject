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
               <?php echo get_phrase('staff_salary'); ?>
        </h3>
    </div>
</div>
<form action="<?php echo base_url(); ?>payroll/view_staff_salary" method="post" class="validate" id="filter" data-step="2" data-position='top' data-intro="use this filter for specific staff salary record">
    <div class="row filterContainer">
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label for="staff_id"><b>Staff</b></label>
            <select id="staff_id" class="form-control" name="staff_id" required>
                <?php echo staff_list('',$staff_id); ?>
            </select>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label for="month"><b>Select Month</b></label>
            <select id="month" name="month" class="form-control" >
                <?php echo month_option_list($month); ?>
            </select>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label for="year"><b>Select Year</b></label>
            <select id="year" name="year" class="form-control">
                <?php echo year_option_list(date('Y')-1,date('Y')+1,$year); ?>
            </select>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
            <input type="hidden" name="apply_filter" value="1" />
            <button type="submit" class="modal_save_btn" id="btn_submit"><?php echo get_phrase('filter'); ?></button>
            <?php if($apply_filter == 1){ ?>
                <a href="<?php echo base_url(); ?>payroll/view_staff_salary" class="modal_cancel_btn" id="btn_remove"> <i class="fa fa-remove"></i>
                    <?php echo get_phrase('remove_filter');?>
                </a>
            <?php } ?>
        </div>
    </div>
        
</form>

<div class="col-lg-12 col-md-12">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export">
	<thead>
		<tr>
			<th style="width:34px !important;"><?php echo get_phrase('s_no');?>		
		</th>
		<th>
			<?php echo get_phrase('Staff_details');?>
		</th>
		<th>
			<?php echo get_phrase('salary_details');?>
		</th>
		<th>
			<?php echo get_phrase('Dates');?>
		</th>
		<th>
			<?php echo get_phrase('status');?>
		</th>
		
		<th style="width:94px;">
			<?php echo get_phrase('options');?>	
		</th>
		</tr>
	</thead>
	<tbody>
		<?php 
	    $count = 1;
	    
	    foreach($staff_salary as $row)
	    {
		?>
		<tr>
			<td class="td_middle">
				<?php echo $count++;?>
			</td>
			<td>
			    <?php 
			        echo $row['name'];
			    ?>
			</td>
			<td>
			    <strong>Basic Salary : </strong><?php echo $row['basic_salary'];?></br>
			    <strong>Earned Salary : </strong><?php echo $row['earned_salary'];?></br>
			    <strong>Income Tax : </strong><?php echo $row['income_tax_deduction'];?><br>
			    <strong>Net Salary : </strong><?php echo $row['net_salary'];?>
			</td>
			<td>
			    <strong>Salary Month : </strong><?php echo month_of_year($row['month']);?></br>
			    <strong>Salary Year : </strong><?php echo $row['year'];?>
			</td>
			<td>
			<?php 
				if ($row['is_paid'] == 1)
					echo "<span style='color:green'>Paid</span>";
				else
					echo "<span style='color:red'>Unpaid</span>";
			?>
			</td>
			<td class="td_middle">
		
			<div class="btn-group align-middle" data-step="6" data-position='left' data-intro="if you want salary record edit or delete then press this button you have the option edit/delete">
					<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
					<?php echo get_phrase('action');?>
						
						<span class="caret">
						</span>
					</button>
					<ul class="dropdown-menu dropdown-default pull-right" role="menu">
						
					
						<!-- EDITING LINK -->
						<li>
							<a href="<?php echo base_url()."payroll/salary_details/".str_encode($row['s_s_s_id']) ?>">
								<i class="entypo-eye"></i>
								<?php echo get_phrase('view_details');?>
							</a>
						</li>
					
						<li class="divider"></li>

						<li>
							<a href="<?php echo base_url()."payroll/print_salary_slip/".str_encode($row['s_s_s_id']) ?>">
								<i class="entypo-print"></i>
								<?php echo get_phrase('print');?>
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

</script>

<!--Datatables Add Button Script-->
