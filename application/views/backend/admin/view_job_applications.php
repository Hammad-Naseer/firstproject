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
               <?php echo get_phrase('Job Applications'); ?>
        </h3>
    </div>
</div>

<div class="col-lg-12 col-md-12" data-step="1" data-position='left' data-intro="jobs application records">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export">
	<thead>
		<tr>
			<th style="width:34px !important;"><?php echo get_phrase('s_no');?></th>
    		<th><?php echo get_phrase('Applied For');?></th>
    		<th><?php echo get_phrase('Applicant Details');?></th>
    		<th><?php echo get_phrase('status');?></th>
		    <th style="width:94px;"><?php echo get_phrase('options');?></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		    $count = 1;
		    foreach($application_details as $row):
		?>
		<tr>
			<td class="td_middle"><?php echo $count++;?></td>
			<td class="td_middle">
                <?php echo $row['job_title'];?><br>
                <?php echo get_job_type($row['job_type']);?><br>
                <?php echo $row['job_location'];?>
			</td>
			<td>
			    <strong>Name : </strong><?php echo $row['name'];?> <br>
			    <strong>Contact # : </strong><?php echo $row['mob_num'];?> <br>
			    <strong>Email : </strong><?php echo $row['email'];?><br>
			    <strong>Address : </strong><?php echo $row['address'];?> <br>
			    <?php if($row['attachment']){ ?>
			    <strong>Attachment : </strong><a href="<?php echo display_link($row['attachment'], "job_applications", 0, 0);?>" download="" ><i class="fa fa-download" aria-hidden="true"></i></a>
			    <?php } ?>
			</td>
			
			<td class="td_middle">
			<?php 
				if ($row['status'] == 1)
					echo "<span style='color:green'>Response has been Sent</span>";
				else
					echo "<span style='color:red'>Pending</span>";
			?>
			</td>
			<td class="td_middle">
			
                <div class="btn-group align-middle" data-step="2" data-position='left' data-intro="Send Response to job applicants ">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                    <?php echo get_phrase('action');?>
                    	
                    	<span class="caret">
                    	</span>
                    </button>
                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                    	<li>
                    		<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_job_response/<?php echo $row['job_application_id'];?>');">
                    			<?php echo get_phrase('send_response');?>
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
