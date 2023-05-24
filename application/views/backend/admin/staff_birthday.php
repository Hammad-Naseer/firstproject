
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
               <?php echo get_phrase('staff_birthday'); ?>
        </h3>
    </div>
</div>

    <form method="post" action="<?=base_url()?>c_student/staff_birthday" class="form-groups-bordered">
        <div class="row filterContainer" data-step="1" data-position='top' data-intro="Please select the filters and press filter button to get record">
             
            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                <label for="joining_date"><b>Start Date</b></label>
                <input type="date" name="start_date" id="start_date" class="form-control" placeholder="Start Date" value="<?php echo $start_date; ?>" required/>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                <label for="leaving_date"><b>End Data</b></label>
                <input type="date" name="end_date" id="end_date" class="form-control" placeholder="End Date" value="<?php echo $end_date; ?>" required/>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                <input type="hidden" name="apply_filter" value="1">
                <input type="submit" class="modal_save_btn" value="<?php echo get_phrase('filter');?>"></input>
                
                <?php if($apply_filter == 1){?>
                <a href="<?php echo base_url(); ?>c_student/staff_birthday" class="modal_cancel_btn" id="btn_remove"> 
                <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>
                <?php }?>
            </div>
            
        </div>
    </form> 

<div class="col-lg-12 col-md-12">
     
    <form  action="<?php echo base_url()?>c_student/wish_staff_birthday" method="post">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export">
	<thead>
		<tr>
			<th class="td_middle"><?php echo get_phrase('s_no');?></th>
    		<th><?php echo get_phrase('staff_name');?></th>
    		<th class="td_middle"><?php echo get_phrase('date_of_birth');?></th>
    		<th class="td_middle"><?php echo get_phrase('Wish Birthday');?></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		    $count = 1;
		    foreach($staff_birthday as $row):
		?>
		<tr>
		    <td class="td_middle">
				<?php echo $count++;?>
			</td>
			<td>
				<?php echo $row->name;?>
			</td>
			<td class="td_middle">
				<?php echo date_view($row->dob);?>
			</td>
            <td class="td_middle">
                <div <?= check_sms_preference(16,"style","sms") ?>>
                    <input type="checkbox" class="std_checkbox" name="staff_id[]" value="<?php echo $row->staff_id; ?>">
                </div>    
            </td>
		</tr>
		<?php endforeach;?>
	</tbody>
    </table>
    <div class="form-group text-right">
        <button class="modal_save_btn" type="submit">Send Wishes</button>
    </div>
    </form>
    
</div>

<script type="text/javascript">

$(window).on("load" , function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});

</script>
<!--//***********************Date filter validation***********************-->
<script>
    $("#start_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("start_date").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
        // }
    });
    
    $("#end_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementById("end_date").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("end_date").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
</script>
<!--//********************************************************************-->
