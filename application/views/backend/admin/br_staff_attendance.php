<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <span class="mynavicon"><i class="fa fa-bar-chart" aria-hidden="true"></i></span> <?php echo get_phrase('branches_staff_attendance');?>
        </h3>
        <?/*php 
        if (right_granted('staff_manage'))
        {*/?>
            <a href="<?php echo base_url(); ?>branch_reporting/branch_reports_listing" class="btn btn-primary pull-right">
                <?php echo get_phrase('back');?>
            </a>
        <?php /*}*/

        ?>
    </div>
</div>


<form action="<?php echo base_url(); ?>branch_reporting/br_staff_attendance" method="post" name="br_staff_attendance_form" id="br_staff_attendance_form">
    <div class="row filterContainer">
        <div class="col-md-6" data-step="1" data-position="top" data-intro="select date">
            <div class="form-group">
              <label><?php echo get_phrase('attendance_date');?></label>
              <input class="form-control datepicker" type="text" name="attend_date" id="attend_date" 
                     value="<?php echo $attend_date ?>" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
              <div id="d3"></div>
            </div>
        </div>
        <div class="col-md-6" data-step="5" data-position="top" data-intro="press filter button to filter record">
            <div class="form-group" style="margin-top: 30px;">
               <input type="hidden" name="apply_filter" value="1">
               <input type="submit" id="filter" value="<?php echo get_phrase('filter');?>" class="btn btn-primary">
               <?php if ($apply_filter == 1){?>
                  <a href="<?php echo base_url(); ?>branch_reporting/br_staff_attendance" class="btn btn-danger"><i class="fa fa-remove"></i> Remove</a>
               <?php } ?>
               <input class="btn btn-primary" type="submit" id="br_staff_attendance_pdf" value="<?php echo get_phrase('get_pdf');?>">
               <input class="btn btn-primary" type="submit" id="br_staff_attendance_excel" value="<?php echo get_phrase('get_excel');?>">
            </div>
        </div>
    </div>
</form>


<div class="col-md-12">
    <table id="table_export" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th style="width: 20px;"><?php echo get_phrase('sr');?>.</th>
                <th style="width: 80px;"><?php echo get_phrase('logo');?></th>
                <th><?php echo get_phrase('name');?></th>
                <th><?php echo get_phrase('staff_count');?></th>
                <th><?php echo get_phrase('present');?></th>
                <th><?php echo get_phrase('absent');?></th>
                <th><?php echo get_phrase('leave');?></th>
            </tr>
        </thead>
        <tbody style="width: 250px;">
        <?php
        $count = 0;
        foreach ($branch_arr as $key => $value)
        {
            $count++;
        ?>
            <tr>
                <td><?php echo $count;?></td>
                <td>
                    <?php
                    $folder_name=$value['folder_name'];
                    if($value['logo']!="")
                    {
                    ?>
                    <img class="img-responsive" style="max-width:80px; max-height: 50px;" src="<?php echo display_link($value['logo'],$folder_name,1);?>" />
                    <?php
                    }
                    ?>
                </td>
                <td>
                    <?php echo $value['name'];?><br>
                    <?php echo $value['address'];?>
                </td>
                <td>
                    <?php
                    if (($staff_count[$value['school_id']]) != 0)
                    {
                        echo $staff_count[$value['school_id']];
                    }
                    else
                    {
                        echo "0";
                    }
                    ?>  
                </td>
                <td>
                    <?php
                        echo $br_staff_attend[$value['school_id']][1];
                    ?>
                </td>
                <td>
                    <?php
                        echo $br_staff_attend[$value['school_id']][2];
                    ?>
                </td>
                <td>
                    <?php
                        echo $br_staff_attend[$value['school_id']][3];
                    ?>
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
        $('#table').DataTable();

        $('#br_staff_attendance_pdf').click(function(){
            $('#br_staff_attendance_form').attr('action', '<?php echo base_url(); ?>branch_reporting/br_staff_attendance_pdf');
            $('#br_staff_attendance_form').submit();
        });

        $('#br_staff_attendance_excel').click(function(){
            $('#br_staff_attendance_form').attr('action', '<?php echo base_url(); ?>branch_reporting/br_staff_attendance_excel');
            $('#br_staff_attendance_form').submit();
        });

    });
</script>