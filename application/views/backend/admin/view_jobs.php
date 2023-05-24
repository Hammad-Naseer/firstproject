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
               <?php echo get_phrase('jobs'); ?>
        </h3>
    </div>
</div>
<form action="<?php echo base_url();?>jobs/view_jobs" method="post" id="jobsform">
    <div class="row filterContainer">
        <div class="col-lg-4 col-md-4 col-sm-4">
        	<div class="form-group">
        	        <label for="job_type"><b>Select Job Type</b></label>
        	        <select name="job_type" id="job_type" class="form-control">
        				<?php echo jobs_option_list($job_type);?>
        			</select>
        			<div class="err_div"></div>
        	</div>	
        </div>	
        <div class="col-lg-4 col-md-4 col-sm-4" data-step="1" data-position='bottom' data-intro="filter for specific records" style="margin-top: 20px;">
        	<input type="hidden" name="apply_filter" value="1">		
        	<button class="modal_save_btn" type="submit"><?php echo get_phrase('filter'); ?> </button>
			<?php if ($apply_filter == 1) { ?> 
                <a href="<?php echo base_url();?>jobs/view_jobs"  class="modal_cancel_btn" id="btn_remove" style="padding:5px 5px !important; ">			
    			       <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?>
    			</a>
            <?php } ?>	
        </div>
    </div>	        
</form>
<div class="col-lg-12 col-md-12" data-step="3" data-position='bottom' data-intro="Jobs Details">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export">
	<thead>
		<tr>
			<th style="width:34px !important;"><?php echo get_phrase('s_no');?></th>
    		<th style="width:170px !important;"><?php echo get_phrase('Job Details');?></th>
    		<th style="width:100px !important;"><?php echo get_phrase('Type');?></th>
    		<th style="width:100px !important;"><?php echo get_phrase('Dates');?></th>
    		<th><?php echo get_phrase('Description');?></th>
    		<th><?php echo get_phrase('status');?></th>
		    <th style="width:94px;"><?php echo get_phrase('options');?></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		    $count = 1;
		    foreach($jobs_details as $row):
		?>
		<tr>
			<td class="td_middle"><?php echo $count++;?></td>
			<td class="td_middle">
			    <strong>Title : </strong><?php echo $row['job_title'];?> <br>
			    <strong>Qualifications : </strong><?php echo $row['qualifications'];?> <br>
			    <strong>Carrer Level : </strong><?php echo $row['carrer_level'];?> <br>
			    <strong>Experience : </strong><?php echo $row['experience'];?>
			</td>
			<td class="td_middle">
			    <strong>Type : </strong><?php echo get_job_type($row['job_type']);?> <br>
			    <strong>Location : </strong><?php echo $row['job_location'];?> <br>
			</td>
			<td class="td_middle">
			    <strong>Posting on : </strong><?php echo date_view($row['job_posting_date']);?> <br>
			    <strong>End On : </strong><?php echo date_view($row['job_end_date']);?> <br>
			</td>
			<td><?php echo $row['job_description'];?></td>
			
			<td class="td_middle">
			<?php 
				if ($row['job_status'] == 1)
					echo "<span style='color:green'>Active</span>";
				else
					echo "<span style='color:red'>Inactive</span>";
			?>
			</td>
			<td class="td_middle">
			
                <div class="btn-group align-middle" data-step="4" data-position='left' data-intro="jobs record can be edit or delete by clicking this button">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                <?php echo get_phrase('action');?>
                	
                	<span class="caret">
                	</span>
                </button>
                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                	
                
                	<!-- EDITING LINK -->
                	<li>
                		<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_edit_job/<?php echo $row['job_id'];?>');">
                			<i class="entypo-pencil">
                			</i>
                			<?php echo get_phrase('edit');?>
                		</a>
                	</li>
                	<li class="divider">
                	</li>
                
                	<!-- DELETION LINK -->
                	<li>
                		<a href="#" onclick="confirm_modal('<?php echo base_url();?>jobs/delete_job/<?php echo $row['job_id'];?>');">
                			<i class="entypo-trash">
                			</i>
                			<?php echo get_phrase('delete');?>
                
                		</a>
                	</li>
                </ul>
                </div>

			</td>
		</tr>
		<?php endforeach;?>
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
<?php //if(right_granted('manageleavecategory_manage'))
{ ?>
<script>
    var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_edit_job/")';
    var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='2' data-position='left' data-intro='Press this button to post a new job' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('post_new_job');?></a>";    
</script>
<?php } ?>
