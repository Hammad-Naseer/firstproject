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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
    <div class="col-lg-5 col-md-5 col-sm-5">
        <h3 class="system_name inline">
            <span class="mynavicon"><i class="fa fa-bar-chart" aria-hidden="true"></i></span> <?php echo get_phrase('section_wise_attendance');?>
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
<form action="<?php echo base_url().'student_attendance_report/section_wise_attendance/'.$d_school_id?>" method="post" name="section_wise_attendance_form" id="section_wise_attendance_form">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="col-md-6 col-lg-6 col-sm-6">
            <label><?php echo get_phrase('select_date');?></label>
                <input class="form-control datepicker" type="text" name="select_date" id="select_date" value="<?php echo $select_date ?>" placeholder="Select Date" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                    <div id="d3"></div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6">
        <label id="section_id_filter_selection"><?php echo get_phrase('select_department')." / ".get_phrase('class')." / ".get_phrase('section');?></label>
            <select id="section_id_filter" class="selectpicker form-control" name="section_id">
            <option value="">
                <?php echo get_phrase('select_any_option');?>  
            </option>
            <?php echo department_class_section($section_id,$d_school_id);?>
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
            <a id="btn_show" href="<?php echo base_url().'student_attendance_report/section_wise_attendance/'.$d_school_id?>" class="btn btn-danger" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
            </div>
            <?php
            }
            ?>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6">
            <input class="btn btn-primary" type="submit" id="section_wise_attendance_pdf" value="<?php echo get_phrase('get_pdf');?>">

             <input class="btn btn-primary" type="submit" id="section_wise_attendance_excel" value="<?php echo get_phrase('get_excel');?>"> 
        </div>
    </div>
    <input type="hidden" id = "d_school_id" name="d_school_id" value="<?php echo $d_school_id; ?>">
</form>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
        <table id="table" class="table table-bordered table-hover table-striped table-condensed table-responsive table-sm" role="grid">
        <thead>
            <tr>
            <th><?php echo get_phrase('sr');?>.</th> 
            <th><?php echo get_phrase('department');?></th>
            <th><?php echo get_phrase('class');?></th>
            <th><?php echo get_phrase('section');?></th>
            <th><?php echo get_phrase('total_students');?></th>
            <th><?php echo get_phrase('present');?></th>
            <th><?php echo get_phrase('absent');?></th>
            <th><?php echo get_phrase('leave');?></th>
            </tr>
        </thead>
        <tbody style="width: 250px;">
        <?php
        $count = 0;
        $sec_ary = department_class_section_hierarchy($section_id,$d_school_id);
        foreach ($sec_ary as $key => $value)
        {
            $count++;
        ?>
        <tr>
            <td><?php echo $count;?></td>
            <td><?php echo $value['d'];?></td>
            <td><?php echo $value['c'];?></td>
            <td><?php echo $value['s'];?></td>

            <td>
            <?php
                echo $total_students_arr[$value['s_id']]['total_students'];
            ?>
            </td>
            <td>
            <?php
                 echo $attendance_status_arr[$value['s_id']][1];
            ?>
            </td>
            <td>
            <?php
                echo $attendance_status_arr[$value['s_id']][2];
            ?>
            </td>
            <td>
            <?php
                echo $attendance_status_arr[$value['s_id']][3];
            ?>
            </td>
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
        $('#table').DataTable({
            "lengthMenu": [25, 50, 75,100],
            //"scrollX": true
            "ordering": false
        });

        $('#section_wise_attendance_pdf').click(function(){
            $('#section_wise_attendance_form').attr('action', '<?php echo base_url(); ?>student_attendance_report/section_wise_attendance_pdf');
            $('#section_wise_attendance_form').submit();
        });

        $('#section_wise_attendance_excel').click(function(){
            $('#section_wise_attendance_form').attr('action', '<?php echo base_url(); ?>student_attendance_report/section_wise_attendance_excel');
            $('#section_wise_attendance_form').submit();
        });
        
    });
</script>