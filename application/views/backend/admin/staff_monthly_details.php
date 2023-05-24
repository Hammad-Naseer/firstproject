<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <!-- <i class="entypo-right-circled carrow">
                        </i> -->
            <span class="mynavicon"><i class="fa fa-bar-chart" aria-hidden="true"></i></span> <?php echo get_phrase('staff_monthly_details');?>
        </h3>
        <?/*php 
        if (right_granted('staff_manage'))
        {*/?>
            <a href="<?php echo base_url(); ?>reports/reports_listing" class="btn btn-primary pull-right">
                <!-- <i class="entypo-plus-circled"></i> -->
                <?php echo get_phrase('back');?>
            </a>
        <?php /*}*/

        ?>
    </div>
</div>

<div class="thisrow" style="padding:12px;">
<form action="<?php echo base_url(); ?>staff_attendance_report/staff_monthly_details" method="post" name="staff_monthly_details_form" id="staff_monthly_details_form">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="col-md-6 col-lg-6 col-sm-6">
                <label><?php echo get_phrase('select_month');?></label>
                <input class="form-control datepicker" id="datepicker" data-date="" data-date-format="mm-yyyy" type="text"
                name="month_year" value="<?php echo $month_year ?>">
                <div id="d3"></div>
            </div>

            <div class="col-md-6 col-lg-6 col-sm-6">
             <label><?php echo get_phrase('select_staff');?></label>
            <select id="staff_id" class="selectpicker form-control" name="staff_id">
            <?php echo staff_option_list($staff_id);?>
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
            <a id="btn_show" href="<?php echo base_url(); ?>staff_attendance_report/staff_monthly_details" class="btn btn-danger" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
            </div>
            <?php
            }
            ?>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6">
            <input class="btn btn-primary" type="submit" id="staff_monthly_details_pdf" value="<?php echo get_phrase('get_pdf');?>">
            <input class="btn btn-primary" type="submit" id="staff_monthly_details_pdf_excel" value="<?php echo get_phrase('get_excel');?>">
        </div>
    </div>
</form>
</div>