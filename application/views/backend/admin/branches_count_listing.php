<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <!-- <i class="entypo-right-circled carrow">
                        </i> -->
            <span class="mynavicon"><i class="fa fa-bar-chart" aria-hidden="true"></i></span> <?php echo get_phrase('branches_count');?>
        </h3>
        <?/*php 
        if (right_granted('staff_manage'))
        {*/?>
            <a href="<?php echo base_url(); ?>branch_reporting/branch_reports_listing" class="btn btn-primary pull-right">
                <!-- <i class="entypo-plus-circled"></i> -->
                <?php echo get_phrase('back');?>
            </a>
        <?php /*}*/

        ?>
    </div>
</div>

<div class="thisrow" style="padding:12px;">
<form action="" method="post" name="branches_count_form" id="branches_count_form">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="col-md-6 col-lg-6 col-sm-6">
             <label><?php echo get_phrase('attendance_date');?></label>
                <input class="form-control datepicker" type="text" name="attend_date" id="attend_date" value="<?php echo $attend_date ?>" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                    <div id="d3"></div>
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
            <a id="btn_show" href="<?php echo base_url(); ?>branch_reporting/branches_count" class="btn btn-danger" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
            </div>
            <?php
            }
            ?>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6">
            
                <input class="btn btn-primary" type="submit" id="branches_count_pdf" value="<?php echo get_phrase('get_pdf');?>">
           
                <input class="btn btn-primary" type="submit" id="branches_count_excel" value="<?php echo get_phrase('get_excel');?>">
            
        </div>
    </div>
</form>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
        <table id="table" class="table table-bordered table-hover table-striped table-condensed table-responsive table-sm" role="grid">
        <thead>
            <tr>
                <th style="width: 20px;"><?php echo get_phrase('sr');?>.</th>
                <th style="width: 80px;"><?php echo get_phrase('logo');?></th>
                <th><?php echo get_phrase('name');?></th>
                <th><?php echo get_phrase('student_count');?></th>
                <th><?php echo get_phrase('staff_count');?></th>
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
                    if (($student_count[$value['school_id']]) != 0)
                    {
                        echo $student_count[$value['school_id']];
                    }
                    else
                    {
                        echo "0";
                    }
                    ?> 
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
        $('#table').DataTable();

        $('#branches_count_pdf').click(function(){
            $('#branches_count_form').attr('action', '<?php echo base_url(); ?>branch_reporting/branches_count_pdf');
            $('#branches_count_form').submit();
        });

        $('#branches_count_excel').click(function(){
            $('#branches_count_form').attr('action', '<?php echo base_url(); ?>branch_reporting/branches_count_excel');
            $('#branches_count_form').submit();
        });

    });
</script>