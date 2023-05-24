<style>
.blue {
    color: #e99711;
}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('staff_attendance_summary');?>
        </h3>
    </div>
</div>

<div class="row filterContainer" data-step="1" data-position='top' data-intro="Please select staff and press filter button to get complete summary report">
    <div class="col-sm-12">
        <form id="filter" name="filter" method="post" action="<?php echo base_url();?>attendance_summary_staff/view_attendance_summary" class="form-horizontal form-groups-bordered validate" style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px;">
            <div class="row">
                <div class="col-sm-6">
                    <select id="staff_id" class="form-control" name="staff_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <?php echo staff_list('',$staff_id);?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <input type="submit" class="modal_save_btn" id="btn_view" value="<?php echo get_phrase('filter');?>" style="margin-top:-0px;" />
                    <input type="hidden" name="apply_filter"  value="1" />
                    <?php if($apply_filter){?>
                    <a href="<?php echo base_url(); ?>attendance_summary_staff/view_attendance_summary" class="modal_cancel_btn" > <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>
                    <?php }?>
                </div>
            </div>
        </form>        
    </div>
</div>
<?php 
if(isset($staff_id) && $staff_id!="")
{
?>
    <div class="col-lg-12 col-sm-12">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered mt-4" id="staff_attend_tbl" aria-describedby="table_export_info">
            <thead>
                <tr>
                    <th class="td_middle" ><?php echo get_phrase('month_-_year');?></th>
                    <th class="td_middle" ><?php echo get_phrase('present');?></th>
                    <th class="td_middle" ><?php echo get_phrase('absent');?></th>
                    <th class="td_middle" ><?php echo get_phrase('leave');?></th>
                </tr>
            </thead>
            <tbody>
            
            <?php
                $array_attend=array();
                
                
                
                $q="select a.status,count(a.status) as status_count,month(a.date) as month, YEAR(a.date) as year,monthname(a.date) as month_name FROM ".get_school_db().".attendance_staff a
                INNER JOIN  ".get_school_db().".staff staff ON staff.staff_id = a.staff_id
                WHERE 
                staff.staff_id =$staff_id 
                AND a.school_id=".$_SESSION['school_id']."
                group by status, month, year
                order by  month, year";
                
                
                
                $qur_red=$this->db->query($q)->result_array();
                $qur_array=array();
                foreach($qur_red as $row)
                {
                	$qur_array[$row['year']][$row['month_name']][$row['status']]=$row['status_count'];
                }
                
                $total_present=0;
                $total_absent=0;
                $total_leave=0;
                while (strtotime($start_date) <= strtotime($end_date)) 
                {
                ?>
                             	
                    <tr>
                        <td class="td_middle">
                            <?php echo $month=date('F', strtotime($start_date));
                            echo "&nbsp";
                            echo $year=date('Y', strtotime($start_date));
                            echo "<br>";
                            $start_date = date('d M Y', strtotime($start_date.
                            '+ 1 month'));
                            ?>  
                        </td>
                        
                        <td class="td_middle">
                            <?php 
                            echo $present=$qur_array[$year][$month][1];
                            $total_present=$present+$total_present;    	
                            ?>
                        </td>
                        <td class="td_middle">
                            <?php 
                            echo $absent=$qur_array[$year][$month][2];
                            $total_absent=$absent+$total_absent;
                            
                            ?>
                        </td>
                        <td class="td_middle">
                            <?php 
                            
                            echo $leave=$qur_array[$year][$month][3];
                            $total_leave=$leave+$total_leave;
                            ?>
                        </td>
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
    $('#staff_id').change(function(){
    	$('#staff_attend_tbl').hide();
    });	
});
</script>