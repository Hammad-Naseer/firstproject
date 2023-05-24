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
    <script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
    </script>

    <div class="row ">
       <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
           <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
            </a>
            <h3 class="system_name inline capitalize"> 
                <?php echo get_phrase('student_attendance_summary'); ?>
            </h3>
        </div>
    </div>
    
    <div>
        <form id="filter" name="filter" method="post" action="<?php echo base_url();?>attendance_summary_student/view_student_summary" class="form-horizontal form-groups-bordered validate " style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px;">
            <div class="row filterContainer">
            <div class="col-lg-6 col-md-6 col-sm-6" data-step="1" data-position="top" data-intro="Step 1: select class - section">
                    <label id="section_id_filter_selection"></label>
                    <select id="section_id_filter"  class="selectpicker form-control" name="section_id" required>
                        <?php echo section_selector($section_filter);?>
                    </select>
                </div>
            <div class="col-lg-6 col-md-6 col-sm-6 mgt10" data-step="2" data-position="top" data-intro="Step 2: select student">
                    <select id="student_select" name="student_select" class="form-control" required>
                    <?php if(isset($student_filter))
                    {
						echo section_student($section_filter,$student_filter);
					}
					else
					{?>
					<option value=""><?php echo get_phrase('select_student'); ?></option>	
					<?php } ?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3" data-step="3" data-position="top" data-intro="Press this button to get student attendance summary report">
                    <input type="hidden" value="1" name="apply_filter">
                    <input type="submit" value="<?php echo get_phrase('filter'); ?>" class="modal_save_btn" id="btn_submit">
                    
                    <?php if($apply_filter){?>
                    <a href="<?php echo base_url(); ?>attendance_summary_student/view_student_summary" class="modal_cancel_btn" id="btn_remove">
                    <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>
                    <?php }?>

                </div>
            </div>
        </form>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
<?php if(count($attendance) > 0)
{?>
    <table class="table table-bordered " style="width: 100% !important;">
        <thead>
            <td class="td_middle"><?php echo get_phrase('month_-_year');?></td>
            <td class="td_middle"><?php echo get_phrase('present');?></td>
            <td class="td_middle"><?php echo get_phrase('absent');?></td>
            <td class="td_middle"><?php echo get_phrase('leave');?></td>
        </thead>
		<tbody>
		
		<?php 
		$attendance_arr=array();
		foreach($attendance as $row)
		{
		    $attendance_arr[$row['year_val']][$row['month_name']][$row['status']]=$row['status_count'];
		}
		
		$total_present=0;
		$total_absent=0;
		$total_leave=0;
		 while (strtotime($start_date) <= strtotime($end_date)) 
        {
        ?>
                         	
                <tr>
                    <td class="td_middle" >
                        <?php echo $month=date('F', strtotime($start_date));
                        echo "&nbsp";
                    	echo $year=date('Y', strtotime($start_date));
                    	echo "<br>";
                    	$start_date = date('d M Y', strtotime($start_date.
                            '+ 1 month'));
                         ?>  
                    </td>
                	<td class="td_middle" >
                    	<?php echo $present=$attendance_arr[$year][$month][1];
                    	    $total_present=$present+$total_present;    	
                    	?>
                	</td>
                	<td class="td_middle" >
                    	<?php echo $absent=$attendance_arr[$year][$month][2];
                    	    $total_absent=$absent+$total_absent;
                    	?>
                	</td>
                	<td class="td_middle" >
                    	<?php echo $leave=$attendance_arr[$year][$month][3];
                    	    $total_leave=$leave+$total_leave;
                    	?>
                	</td>
            	</tr>
    	<?php 
    	}
		?>
		<tr>
		<td class="td_middle" ><strong><?php echo get_phrase("total");?></strong></td>
		<td class="td_middle" ><?php echo $total_present;?></td>
		<td class="td_middle" ><?php echo $total_absent;?></td>
		<td class="td_middle" ><?php echo $total_leave;?></td>
		</tr>	
		
		</tbody>

    </table>

<?php
}
?>
</div>
<script>
$(document).ready(function() 
{
$("#section_id_filter").change(function() 
{	
	$('#attend_table').hide();
});

$("#student_select").change(function() 
{	
	$('#attend_table').hide();
});

	$("#section_id_filter").change(function() {
            var section_id = $(this).val();

            $("#icon").remove();
            $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>attendance_summary_student/get_section_student",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    if (response != "") {
                        $("#student_select").html(response);
                    }
                    if (response == "") {
                        $("#student_select").html('<select><option value=""><?php echo get_phrase('select_student'); ?></option></select>');
                    }
                }
            });



        });
});
    </script>