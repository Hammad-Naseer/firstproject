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
    /*.top{padding-top: 10px;}*/
    .blocks {
    margin: 0 auto;
    text-align: right;
}

</style>
<?php
      $d_school_id=$this->uri->segment(3);
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
    .table-condensed > thead > tr > th, .table-condensed > tbody > tr > th, .table-condensed > tfoot > tr > th, .table-condensed > thead > tr > td, .table-condensed > tbody > tr > td, .table-condensed > tfoot > tr > td
    {
        padding: 0px;   
    }
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td 
    {
        padding:  10px;
        border-bottom: 1px solid #CCC;
        border-left: 1px solid #CCC;
        text-align:center;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
    <div class="col-lg-5 col-md-5 col-sm-5">
        <h3 class="system_name inline">
            <span class="mynavicon"><i class="fa fa-bar-chart" aria-hidden="true"></i></span> <?php echo get_phrase('student_wise_attendance');?>
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
                    if ($img_height>80) {
                        $img_height = 80;
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


<div class="thisrow" style="padding:12px;">
<form action="<?php echo base_url().'student_attendance_report/student_wise_attendance/'.$d_school_id?>" method="post" name="student_wise_attendance_form" id="student_wise_attendance_form">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="col-md-6 col-lg-6 col-sm-6">
            <label><?php echo get_phrase('select_month');?></label>
            <input class="form-control datepicker" id="datepicker" data-date="" data-date-format="mm-yyyy" type="text"
            name="month_year" value="<?php echo $month_year ?>">
            <div id="d3"></div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6">
        <label id="section_id_filter_selection"><?php echo get_phrase('select_section');?></label>
            <select id="section_id_filter" class="selectpicker form-control" name="section_id">
            <option value="">
                <?php echo get_phrase('select_section');?>  
            </option>
            <?php
            echo department_class_section($section_id,$d_school_id);
            //echo section_list();
            ?>
            </select>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-6">
            <div class="col-md-3 col-lg-3 col-sm-3">
            <input type="hidden" name="apply_filter" value="1">
            <input type="submit" id="filter" value="<?php echo get_phrase('filter');?>" class="btn btn-primary">
            </div>
            <?php
            if ($apply_filter == 1)
            {
            ?>
            <div class="col-md-3 col-lg-3 col-sm-3">
            <a id="btn_show" href="<?php echo base_url().'student_attendance_report/student_wise_attendance/'.$d_school_id?>" class="btn btn-danger" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
            </div>
            <?php
            }
            ?>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6">
            <input class="btn btn-primary" type="submit" id="student_wise_attendance_pdf" value="<?php echo get_phrase('get_pdf');?>">
             <input class="btn btn-primary" type="submit" id="student_wise_attendance_excel" value="<?php echo get_phrase('get_excel');?>"> 
        </div>
    </div>
    <input type="hidden" id="d_school_id" name="d_school_id" value="<?php echo $d_school_id;?>">
</form>
</div>
<?php

if ($apply_filter!='' && $month_year!='' && $section_id!='')
{
?>
<center>
<div class="row">
    <div class="col-sm-12 text-center">
        <?php
        $full_date  =  $month_year;
        $month = split('/', $full_date);
        $month_num = $month[0];
        $year = $month[1];
        $monthName = date('F', mktime(0, 0, 0, $month_num, 10));
        // $timestamp = strtotime($full_date);
        // $day = strtolower(date('M', $timestamp));
        $id_arr = remove_prefix($section_id);
        $prefix = $id_arr['prefix'];
        $value = $id_arr['value'];
        $details=section_hierarchy($value,$d_school_id);
        
     ?>
     <ul class="breadcrumb" style="color: #000000;font-weight: bold;">
        <li>
            <?php echo $details['d'];?>
        </li>
        <li>
            <?php echo $details['c'];?>
        </li>
        <li>
            <?php echo $details['s'];?>
        </li>
        <li>
            <?php echo ucwords($monthName)." / ".$year;?>
        </li>
    </ul>
    </div>
</div>
</center>

<?php
}

?>

<div class="row">
    <div class="col-md-12" style="overflow: auto;">
        <div class="table-responsive">
        <table id="fixTable" class="table table-responsive table-condensed">
        <thead>
        <tr>
            <td>
                <?php echo get_phrase('Sr');?>
            </td>            
            <td>
                <?php echo get_phrase('Roll No');?>
            </td>
            <td>
                <?php echo get_phrase('student_name');?>
            </td>
            <?php
            $month_detail = split('/', $month_year);
            $month = intval($month_detail[0]);
            $year = $month_detail[1];
            $date_curr= date('t', mktime(0, 0, 0, $month, 1, $year));

                if($month=='' && $year=='')
                { 
                    $date_curr= date("t");
                    $month=date('m');
                    $year=date('Y');
                }             
                for($i=1;$i<=$date_curr;$i++)
                {
                
                $s=mktime(0,0,0,$month, $i, $year);
                $today_date= date('Y-m-d',$s);
                $dw = date( "D", strtotime($today_date));
                
                echo "<td>";
                echo $i;
                echo "<br/>";
                echo $dw;
                ?>
                </td>
                <?php
                } 
                ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $month_detail = split('/', $month_year);
        $month = intval($month_detail[0]);
        $year = $month_detail[1];
        $date_curr= date('t', mktime(0, 0, 0, $month, 1, $year));
        $count = 0;
        foreach ($attend_details_arr as $key => $value)
        {
            $count++;
        ?>
        <tr>
            <td><?php echo $count;?></td>
            <td><?php echo $value['roll'];?></td>
            <td><?php echo $value['name'];?></td>
            <?php
            for($i=1;$i<=$date_curr;$i++)
            {
            ?>
            <td>
            <?php 
                $attend_status = $value['attend'][$year][$month][$i];
                switch ($attend_status) {
                    case 1:
                        echo "P";
                        break;
                    case 2:
                        echo "A";
                        break;
                    case 3:
                        echo "L";
                        break;
                    
                    default:
                        echo "&nbsp;";
                        break;
                }
            ?>
            </td>
            <?php
            }
            ?>
        </tr>
        <?php
        }
        ?>
        </tbody>
        </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#filter').click(function(event){
            var datepicker =  $('#datepicker').val();
            var section_id = $('#section_id_filter').val();
            if (datepicker=="" || section_id=="")
            {
                event.preventDefault();
                $('#d3').html('select both values');
            }
        });

        $("#fixTable").tableHeadFixer({"left" : 2});
        $("#datepicker").datepicker({
            format: "mm/yyyy",
            startView: "months", 
            minViewMode: "months"
        });
        $('#student_wise_attendance_pdf').click(function(event){
            var datepicker =  $('#datepicker').val();
            var section_id = $('#section_id_filter').val();
            if (datepicker=="" || section_id=="")
            {
                event.preventDefault();
                $('#d3').html('select both values');
            }
            else
            {
                $('#student_wise_attendance_form').attr('action', '<?php echo base_url(); ?>student_attendance_report/student_wise_attendance_pdf');
                $('#student_wise_attendance_form').submit();
            }
        });

        $('#student_wise_attendance_excel').click(function(event){
            var datepicker =  $('#datepicker').val();
            var section_id = $('#section_id_filter').val();
            if (datepicker=="" || section_id=="")
            {
                event.preventDefault();
                $('#d3').html('select both values');
            }
            else
            {
                $('#student_wise_attendance_form').attr('action', '<?php echo base_url(); ?>student_attendance_report/student_wise_attendance_excel');
                $('#student_wise_attendance_form').submit();
            }
        });
    });
</script>