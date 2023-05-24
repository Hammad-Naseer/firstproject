<?php
if($this->session->flashdata('flash_message')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('flash_message').'
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
                <?php echo get_phrase('mark_previous_attendance'); ?>
            </h3>
        </div>
    </div>
    
    <div>
        <form id="filter" name="filter" method="post" action="<?php echo base_url();?>attendance/previous_attendance" class="form-horizontal form-groups-bordered validate " style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px;">
            <div class="row filterContainer">
                <div class="col-lg-4 col-md-4 col-sm-6" data-step="1" data-position="top" data-intro="Step 1: select class - section">
                <label id="section_id_filter_selection"></label>
                <select id="section_id_filter"  class="selectpicker form-control" name="section_id" required>
                    <?php echo section_selector($section_filter);?>
                </select>
            </div>
                <div class="col-lg-4 col-md-4 col-sm-6 mgt10" data-step="2" data-position="top" data-intro="Step 2: select student">
                    <select id="student_select" name="student_select" class="form-control" required>
                    <?php 
                        if(isset($student_filter)){
    						echo section_student($section_filter,$student_filter);
    					}else{
    				?>
					    <option value=""><?php echo get_phrase('select_student'); ?></option>	
					<?php } ?>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6" data-step="3" data-position="top" data-intro="Step 3: select attedance date">
                    <label id="section_id_filter_selection"></label>
                    <input type="date" class="form-control" name="att_date" id="attend_date" value="<?php if(isset($date)): echo $date; endif; ?>" required>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3" data-step="4" data-position="top" data-intro="Press this button to get student attendance summary report">
                    <input type="hidden" value="1" name="apply_filter">
                    <input type="submit" value="<?php echo get_phrase('filter'); ?>" class="modal_save_btn" id="btn_submit">
                    
                    <?php if($apply_filter){ ?>
                        <a href="<?php echo base_url(); ?>attendance/previous_attendance" class="modal_cancel_btn" id="btn_remove">
                        <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>
                    <?php }?>

                </div>
            </div>
        </form>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
<?php if(count($data) > 0 )
{?>
    <table class="table table-bordered " style="width: 100% !important;">
        <thead>
            <td class="td_middle"><?php echo get_phrase('student');?></td>
            <td class="td_middle"><?php echo get_phrase('date');?></td>
            <td class="td_middle"><?php echo get_phrase('attendance');?></td>
        </thead>
		<tbody>
            <tr>
                <td class="td_middle" >
                    <?php $std = student_details($data[0]->student_id); echo $std[0]['name']; ?>  
                </td>
            	<td class="td_middle" >
                	<?php echo date_view($data[0]->date) ?>
            	</td>
            	<td class="td_middle" >
                	<?php if($data[0]->status == 1): ?>
                    	Already Present
                    <?php elseif($data[0]->status == 2): ?>
                    	Absent <br><br>
                    <?php elseif($data[0]->status == 3): ?>
                    	Leave <br><br>	
                	<?php endif; ?>
                	<?php if($data[0]->status != 1): ?>
                    	<a href="#" class="modal_save_btn" onclick="confirm_modal('<?php echo base_url();?>attendance/mark_previous_attendance/<?php echo $data[0]->student_id.'/'.$data[0]->date;?>');">
                            <?php echo get_phrase('mark_present');?>
                        </a>
                    <?php endif; ?>    
            	</td>
        	</tr>
		</tbody>
    </table>

<?php }elseif($apply_filter == 1){ ?>
<h4 class="text-center text-danger mt-4">Attendance Not Found</h4>
<?php } ?>
</div>
<script>
$(document).ready(function() 
{
    $("#attend_date").change(function () {
        var attend_date = s_d($("#attend_date").val());
       if ((Date.parse(attend_date) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("end_date").value = "";    
        }
    });
    
    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
    
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