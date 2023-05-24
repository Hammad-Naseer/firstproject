<?php
if (!$month_filter)
{
    $date = get_phrase(date('d'));
}
?>
<style>
.holiday {
    opacity: 0.3 !important;
    background-color: #cacaca !important;
    border-right: none !important;
}
.hw{height:30px; width:30px; border:1px solid #CCC !important;}
.red{background-color: #f2dede;}
.green{background-color: #dff0d8;}
.blue{background-color: #d9edf7;}
.grey{background-color: #efefef;}
.ml{    padding-left: 5px;
padding-right: 23px;
padding-top: 6px;}
.bl{border:1px solid #000 !important;
}

.current-day {
border: 2px solid #000000 !important;
border-left: 2px solid #000000 !important;
}
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('student_attendance_summary'); ?>
        </h3>
    </div>
</div>

<form method="post" action="<?php echo base_url();?>teacher/student_summary" class="form"  style="overflow-x:auto !important;">
    
    <div class="row filterContainer">
        <div class="col-lg-4 col-md-4 col-sm-4" data-step="1" data-position='top' data-intro="Select Class">
            <div class="form-group">
    			<select id="section_filter" name="section_filter" class="form-control" required>
                    <?php
                        $login_detail_id = $_SESSION['login_detail_id'];
                        $d_c_s_sec = get_teacher_dep_class_section($login_detail_id);
                        $time_table_t_sec = get_time_table_teacher_section($login_detail_id);
                        $teacher_section = array_unique(array_merge($d_c_s_sec, $time_table_t_sec));
                        echo get_teacher_dep_class_section_list($teacher_section, $section);?>
                </select>
    		</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-4" data-step="2" data-position='top' data-intro="Select Student">
            <div class="form-group">
    			<select name="student_select" id="student_select" class="form-control">
                   <?php 
                   if(isset($section_filter) && isset($student_filter))
                   {
				   	echo section_student($section_filter , $student_filter);
				   }
				   else{?>
				   	<option value=""><?php echo get_phrase("select_student");?></option>
				  <?php }
                   ?>
                </select>
    		</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-4" data-step="3" data-position='top' data-intro="Press Filter button to view student attendance summary">
            <div class="form-group">
    			<button type="submit" value="" class="modal_save_btn"/><?php echo get_phrase('filter');?></button>
                <?php
                if($filter)
                {?>
                    <a href="<?php echo base_url();?>teacher/student_summary" class="modal_cancel_btn pull-right" >
                            <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                        </a>
                <?php
                }
                ?>
    		</div>
		</div>
	</div>
	
</form>
<?php if(count($attendance) > 0){ ?>
<div class="col-lg-12 col-md-12" data-step="4" data-position='top' data-intro="Student Attendance Summary">
<table class="table table-bordered" id="attend_table">
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
                         	
        <tr><td class="td_middle"><?php echo $month=date('F', strtotime($start_date));
        echo "&nbsp";
    	echo $year=date('Y', strtotime($start_date));
    	echo "<br>";
    	$start_date = date('d M Y', strtotime($start_date.
            '+ 1 month'));
         ?>  
         </td>
         
    	<td class="td_middle"><?php 
    	echo $present=$attendance_arr[$year][$month][1];
    	$total_present=$present+$total_present;    	
    	?></td>
    	<td class="td_middle"><?php 
    	echo $absent=$attendance_arr[$year][$month][2];
    	$total_absent=$absent+$total_absent;
    	
    	?></td>
    	<td class="td_middle"><?php 
    	
    	echo $leave=$attendance_arr[$year][$month][3];
    	$total_leave=$leave+$total_leave;
    	?></td>
    	</tr>
    	<?php 
    		}
		?>
		<tr>
		<td class="td_middle"><strong><?php echo get_phrase("total");?></strong></td>
		<td class="td_middle"><?php echo $total_present;?></td>
		<td class="td_middle"><?php echo $total_absent;?></td>
		<td class="td_middle"><?php echo $total_leave;?></td>
		</tr>	
		
		</tbody>
</table>
</div>

<?php
}
?>

<script>
	
$(document).ready(function(){
    $('#student_select').change(function(){
	    $('#attend_table').hide();
	});
	
    $('#section_filter').change(function(){
	    $('#attend_table').hide();
	});	
	
	$("#section_filter").change(function() {
            var section_id = $(this).val();

            $("#icon").remove();
            $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>teacher/get_section_student",
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
<script>
    jQuery(document).ready(function () 
    {
        jQuery('.dcs_list').on('change', function (){
            var id=this.id;
            var selected = jQuery('#'+ id +' :selected');
            var group = selected.parent().attr('label');
            jQuery(this).siblings('label').text(group);
        });
    });
</script>