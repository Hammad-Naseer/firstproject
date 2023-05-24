<style>
  .tile-stats {
    min-height: 140px !important;
  }
  .system_name.inline {
        display: inline-block;
        margin: 0;
        padding: 20px 0 5px;
        width: 100%;
    }
  .img-res {
        float: none;
        height: 50px;
        width: auto;
    }
   .col-mh{
        color: #4a8cbb;
        font-size: 16px;
        font-weight: bold;
        padding-top: 20px;
        text-align: right;
        text-transform: uppercase;
    }
    
    .blocks {
        margin: 0 auto;
        text-align: right;
    }
</style>
    <?php
      $d_school_id = $d_school_id;
      $branch_name = "";
      if($d_school_id=="")
      {
        $d_school_id = $_SESSION['school_id'];
      }
      else
      {
        $school_details = get_school_details($d_school_id);
        $branch_name =  $school_details['name'];
        $branch_logo =  $school_details['logo'];
        $branch_folder =  $school_details['folder_name'];
      }
    ?>
<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td 
    {
        padding:  0px;
    }
    .responsive{
    	/*
    	max-height: 500px;
    	overflow-y: scroll;
    	*/
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
    <div class="col-lg-5 col-md-5 col-sm-5">
        <h3 class="system_name inline">
            <?php echo get_phrase('staff_timing_details');?>
        </h3>
    </div>
    <div class="col-lg-7 col-md-7 col-sm-7">
        <?php
        if ($branch_name!= "")
        {
        ?>
        <div class="row">
         <div class="col-md-10 blocks">
          <!-- <img class="img-res" src="<?php echo base_url();?>uploads/<?php echo $branch_folder."/".$branch_logo?>"> -->
          <?php

          $logo=system_path($branch_logo,$branch_folder,1);
            if($branch_logo=="" || !is_file($logo))
            {
            ?>
            <a href=""><img style="width: 150px;"  src="<?php echo base_url();?>assets/images/gsims_logo.png">
            </a>
            <?php
            }
            else
            {
                $img_size = getimagesize("uploads/".$branch_folder."/".$branch_logo."");
                $img_width = $img_size[0];
                $img_height = $img_size[1];

            ?>
            <a href="">
            <img class="img-rounded" style="
                margin-top: -9px;
                height:
                <?php
                    if ($img_height>60) {
                        $img_height = 60;
                    }
                    echo $img_height."px;";
                ?>
                " src="<?php echo base_url();?>uploads/<?php echo $branch_folder.'/'.$branch_logo; ?>">
            </a>
            <?php
            }

            ?>
          <span class="col-mh">
          <?php echo $branch_name ?>
          </span>
          </div>
          <div class="col-md-2 pull-right" style="margin-top: 9px;"><a href="<?php echo base_url().'reports/reports_listing/'.$d_school_id?>" class="btn btn-primary"><?php echo get_phrase('back'); ?></a>
          </div>
        </div>
        <?php
        }
        ?>
    </div>
    </div>
</div>

<div>
<form action="<?php echo base_url().'staff_attendance_report/staff_timing_detail/'.$d_school_id?>" method="post" name="staff_timing_detail_form" id="staff_timing_detail_form">
    <div class="row filterContainer">
        <div class="col-md-6 col-lg-6 col-sm-6">
	            <label><?php echo get_phrase('select_month');?></label>
	            <input class="form-control datepicker" id="datepicker" data-date="" data-date-format="mm-yyyy" type="text"
	            name="month_year" value="<?php echo $month_year ?>">
	            <div id="d3"></div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6">
    		 <label><?php echo get_phrase('select_staff');?></label>
            <select id="staff_id" class="selectpicker form-control" name="staff_id">
            <?php echo staff_option_list($staff_id,$d_school_id);?>
            </select>
    	</div>
        <div class="col-md-6 col-lg-6 col-sm-6 pt-3">
            <input type="hidden" name="apply_filter" value="1">
            <input type="submit" id="filter" value="<?php echo get_phrase('filter');?>" class="btn btn-primary">
            
            <?php
            if ($apply_filter == 1)
            {
            ?>
            <div class="col-md-3 col-lg-3 col-sm-3">
            <a id="btn_show" href="<?php echo base_url().'staff_attendance_report/staff_timing_detail/'.$d_school_id?>" class="btn btn-danger" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
            </div>
            <?php
            }
            ?> 
            <input class="btn btn-primary" type="submit" id="staff_timing_detail_pdf" value="<?php echo get_phrase('get_pdf');?>">
            <input class="btn btn-primary" type="submit" id="staff_timing_detail_excel" value="<?php echo get_phrase('get_excel');?>">
        </div>
    </div>
    <input type="hidden" name="d_school_id" name="d_school_id" value="<?php echo $d_school_id?>">
</form>
</div>
    <?php if ($apply_filter==1){ ?>
	        <center>
			<div class="row">
			    <div class="col-sm-12 text-center">
			         <ul class="breadcrumb" style="color: #000000;font-weight: bold;">
			        <li>
			        <?php 
			        	$staff_details_arr = get_staff_detail($staff_id);
	                	echo $staff_details_arr[0]['name'];
	                ?>
			        </li>
			        <li>
			        <?php
				        $month_detail = split('/', $month_year);
		                $month = intval($month_detail[0]);
		                $year = $month_detail[1];
		                $month_of_year = date("F-Y", mktime(0, 0, 0, $month+1, 0, $year));
		                echo $month_of_year;

			        ?>
			        </li>
			    </ul>
			    </div>
			</div>
			</center>
        <?php
        }
        ?>

<div class="col-lg-12 col-sm-12">
<table class="table table-bordered table-hover table-condensed table_export">
<thead>
	<tr>
		<th>Date</th>
		<th>Time In</th>
		<th>Time Out</th>
		<th>Time Count</th>
		<th>Total Time</th>
	</tr>
</thead>
<tbody>
<?php

if(count($date_ary)>0)
{
	$id=0;
	$total_monthly_time =0;
	foreach($date_ary as $dis_key=>$dis_val)
	{
		$id++;
		$total_monthly_time += $final_date[$dis_key]['time'];
?>
	<tr data-toggle="collapse" href=".col<?php echo $id;?>">
		<td><strong><i class="fa fa-plus" aria-hidden="true"></i><?php echo convert_date($dis_key); ?></strong></td>
		<td colspan="3">&nbsp;</td>
		<td>
			<strong>
			<?php
				 echo gmdate("H:i:s", $final_date[$dis_key]['time']);
				?>
			</strong>
		</td>
	</tr>

<?php
		$i=0;
		foreach($time_out[$dis_val] as $key1=>$val1)
		{
			$total_time_cur= strtotime($time_out[$dis_val][$i])-strtotime($time_in[$dis_val][$i]);
		?>
		<tr class="<?php echo "col".$id;?> collapse">
			<td>&nbsp;</td>
			<td>
				<?php 
				echo date('H:i:s',strtotime($time_in[$dis_val][$i]));
				?>	
			</td>
			<td>
				<?php 
				echo date('H:i:s',strtotime($time_out[$dis_val][$i]));
				?>
			</td>
			<td>
			<?php
			 	echo gmdate("H:i:s", $total_time_cur);
			 	?>	
			 </td>
			<td>
				&nbsp;
			</td>
		</tr>
		<?php
		$i++;
		}
		?>
		<tr class="<?php echo "col".$id;?> collapse" >
			<td>&nbsp;</td>
			<td>
				<?php
				echo date('H:i:s',strtotime($final_date[$dis_key]['extra_in']));
				?>
			</td>
			<td>NA</td>
			<td>NA</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
		<?php
	}
	?>
	<tr>
		<td colspan="3">&nbsp;</td>
		<td><?php echo get_phrase('total_monthly_time');?></td>
		<td><strong>
		<?php
		 	$monthly_total = seconds_to_hours($total_monthly_time);
		 	echo $monthly_total['h'].":".$monthly_total['m'].":".$monthly_total['s'];
		?>
		</strong>
		</td>
	</tr>
<?php
}
?>
</tbody>
</table>
</div>
<script>
	$(document).ready(function(){
		$("#datepicker").datepicker({
            format: "mm/yyyy",
            startView: "months", 
            minViewMode: "months"
        });

        $('#filter').click(function(event){
            var datepicker =  $('#datepicker').val();
            var staff_id = $('#staff_id').val();
            if (datepicker=="" || staff_id=="")
            {
                event.preventDefault();
                $('#d3').html('select both values');
            }
        });

        $('#staff_timing_detail_pdf').click(function(event){
        	var datepicker =  $('#datepicker').val();
            var staff_id = $('#staff_id').val();
            if (datepicker=="" || staff_id=="")
            {
                event.preventDefault();
                $('#d3').html('select both values');
            }
            else
            {
            	$('#staff_timing_detail_form').attr('action', '<?php echo base_url(); ?>staff_attendance_report/staff_timing_detail_pdf');
            	$('#staff_timing_detail_form').submit();
        	}
        });

        $('#staff_timing_detail_excel').click(function(event){
        	var datepicker =  $('#datepicker').val();
            var staff_id = $('#staff_id').val();
            if (datepicker=="" || staff_id=="")
            {
                event.preventDefault();
                $('#d3').html('select both values');
            }
            else
            {
            	$('#staff_timing_detail_form').attr('action', '<?php echo base_url(); ?>staff_attendance_report/staff_timing_detail_excel');
            	$('#staff_timing_detail_form').submit();
            }
            
        });
	});
</script>