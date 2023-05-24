<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('branch_reports');?>
        </h3>
    </div>
</div>
<style>
.top{
  margin-top: 20px;
}
.full_listing{
    margin-top: -28px;
    margin-right: 31px;
}
[data-toggle="collapse"]:after { 
    margin-left: 6px;
}

input#submit {
    margin-top: 10px!important;
}


@media only screen and (min-width: 768px) {    
  .full_listing {
    float: right !important;
  } 
}

@media only screen and (max-width: 768px) {    
  .full_listing { 
    margin-left: 15px;
  }
  .brnch-reprt-lstng .panel-body {
    padding: 8px;
  }
  .brnch-reprt-lstng .panel-body input.btn {
    width: 100%;
  }
}

</style>

<!-- -------- branch Reports collapse start ---------- --> 

<div class="panel panel-default brnch-reprt-lstng">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" href="#collapseOne">
            <i class="fa fa-file-text-o" aria-hidden="true"></i>
            <?php echo get_phrase('branches_reports');?>
            </a>
        </h4>
    </div>
    <div id="collapseOne" class="panel-collapse" data-step="1" data-position="top" data-intro="you can see every branch reports. collapse each report then choose which report you want to select branch then select field/options then press the submit button to get a report">
        <div class="panel-body">
            <div class="panel-group" id="accordion2">
                <!-- OneTwo start -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapseOneTwos"> <!-- data-parent="#accordion22"  --> 
                                <i class="fa fa-plus-square" aria-hidden="true"></i>
                                <?php echo get_phrase('branch_student_fee_reports');?>
                            </a>
                        </h4>
                        <a class="full_listing" href="<?php echo base_url().'branch_reporting/br_staff_attendance'?>">
                        <i class="fa fa-table" aria-hidden="true"></i><?php echo get_phrase('click_here_for_details');?></a>
                    </div>
                    <div class="panel-body">
                        <div id="collapseOneTwos" class="panel-collapse collapse">
                            <!-- OneOne unpaid students-->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseOneOnes"> <!-- data-parent="#accordion22"   -->
                                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                                        <?php echo get_phrase('unpaid_students');?>
                                        </a>
                                    </h4>
                                    <a class="full_listing" href="<?php echo base_url().'reports/unpaid_students/'.$d_school_id?>">
                                    <i class="fa fa-table" aria-hidden="true"></i>
                                    <?php echo get_phrase('click_here_for_details');?></a>
                                </div>
                                <div id="collapseOneOnes" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('departmentwise');?></div>
                            </div>
                            <div class="panel-body">
                            
                            <form action="<?php echo base_url().'branch_reporting/unpaid_students/'?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_depart" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                              <label>
                              <?php echo get_phrase('select_department');?></label>
                                  <select id="department_id_filter" class="selectpicker form-control depart_data" name="section_id">
                                    
                                  </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                    
                            </div>
                            </div>
                          </div>
                                            <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('classwise');?></div>
                            </div>
                            <div class="panel-body">
                            <form action="<?php echo base_url().'branch_reporting/unpaid_students/'?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_class" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                              <label><?php echo get_phrase('select_class');?></label>
                                  <select id="class_id_filter" class="selectpicker form-control classs_data" name="section_id">
                                  </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                    
                            </div>
                            </div>
                          </div>
                                        </div>
                                        </br>
                                        <!-- 2nd row -->
                                        <div class="row">
                                            <div class="col-md-6">
                                            <div class="panel panel-default">
                                            <div class="panel-heading">
                                            <div class="panel-title pull-left"><?php echo get_phrase('sectionwise');?></div>
                                            </div>
                                            <div class="panel-body">
                                    
                                            <form action="<?php echo base_url().'branch_reporting/unpaid_students/'?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                                            <div class="col-sm-12">
                                                <label>
                                              <?php echo get_phrase('select_branch');?></label>
                                                <select class="form-control branch_dropdown_for_section" name="d_school_id">
                                                    <option value="">Select Branch</option>
                                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                              <label><?php echo get_phrase('select_section');?></label>
                                                  <select id="section_id_filter" class="selectpicker form-control section_data" name="section_id">
                                                  </select>
                                            </div>
                                            <div class="col-md-4 top">
                                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                                            </div>
                                            </form>
                                    
                                            </div>
                                            </div>
                                    </div>              
                                            <div class="col-md-6">
                                            <div class="panel panel-default">
                                            <div class="panel-heading">
                                            <div class="panel-title pull-left"><?php echo get_phrase('keyword_search');?></div>
                                            </div>
                                            <div class="panel-body">
                                    
                                            <form action="<?php echo base_url().'branch_reporting/unpaid_students/'?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                                            <div class="col-sm-12">
                                                <label>
                                              <?php echo get_phrase('select_branch');?></label>
                                                <select class="form-control" name="d_school_id">
                                                    <option value="">Select Branch</option>
                                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                              <label><?php echo get_phrase('search_keyword');?></label>
                                                  <input type="text" name="std_search" class="form-control" value="" placeholder="Keyword">
                                            </div>
                                            <div class="col-md-4 top">
                                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                                            </div>
                                    
                                            </form>
                                            </div>
                                            </div>
                                    </div>
                                        </div>
                                        </br>
                                        <!-- 3rd row -->
                                        <div class="row">
                                            <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('datewise');?></div>
                            </div>
                            <div class="panel-body">
                    
                            <form action="<?php echo base_url().'branch_reporting/unpaid_students/'?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                            <label><?php echo get_phrase('select_duration');?></label>
                              <select id="date_filter" class="form-control"  name="date_filter">
                                <option value=""><?php echo get_phrase('select_duration'); ?></option>
                                <option value="one_month"><?php echo get_phrase('one_month');?></option>
                                <option value="two_months"><?php echo get_phrase('two_months');?></option>
                                <option value="six_months"><?php echo get_phrase('six_months');?></option>
                              </select>
                            </div>
                    
                            <div class="col-md-8">
                              <label><?php echo get_phrase('start_date');?></label>
                                  <input class="form-control datepicker" type="text" name="startdate" id="startdate" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                                <label><?php echo get_phrase('end_date');?></label>
                                  <input class="form-control datepicker" type="text" name="enddate" id="enddate" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                            </div>
                            <div class="col-md-4" style="margin-top: 70px;">
                              <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                    
                            </form>
                            </div>
                            </div>
                          </div>
                                            <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('studentwise');?></div>
                            </div>
                    
                            <div class="panel-body">
                            
                            <form action="<?php echo base_url().'branch_reporting/unpaid_students/'?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_section branch_ids" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                              <label id="section_id_filter_selection"><?php echo get_phrase('select_section');?></label>
                                    <select id="stud_sec_id_filter" class="selectpicker form-control section_data get_student_by_section" name="section_id">
                                    </select>
                            </div>
                            <div class="col-md-8">
                            <label><?php echo get_phrase('select_student');?></label>
                                <select id="student_select" class="form-control std_data"  name="student_id">
                                    
                                </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                            </div>
                            </div>
                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    		<!-- OneTwo paid students-->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseOneTwoOne"> <!-- data-parent="#accordion22"  -->
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                                            <?php echo get_phrase('paid_students');?>
                                        </a>
                                    </h4>
                                    <a class="full_listing" href="<?php echo base_url().'reports/paid_students/'.$d_school_id?>">
                                        <i class="fa fa-table" aria-hidden="true"></i>
                                        <?php echo get_phrase('click_here_for_details');?>
                                    </a>
                                </div>
                            <div id="collapseOneTwoOne" class="panel-collapse collapse">
                                <div class="panel-body">
                                              <!-- 1st row -->
                          <div class="row">
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('departmentwise');?></div>
                            </div>
                            <div class="panel-body">
                            
                            <form action="<?php echo base_url().'branch_reporting/paid_students/'?>" method="post" name="paid_student_form" id="paid_student_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_depart" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                              <label><?php echo get_phrase('select_department');?></label>
                                  <select id="department_id_filter" class="selectpicker form-control depart_data" name="section_id">
                                  </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                    
                            </div>
                            </div>
                          </div>
                    
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('classwise');?></div>
                            </div>
                            <div class="panel-body">
                    
                            <form action="<?php echo base_url().'branch_reporting/paid_students/'?>" method="post" name="paid_student_form" id="paid_student_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_class" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                              <label><?php echo get_phrase('select_class');?></label>
                                  <select id="class_id_filter" class="selectpicker form-control classs_data" name="section_id">
                                  </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                    
                            </div>
                            </div>
                          </div>
                          </div>
                          </br>
                            <!-- 2nd row -->
                          <div class="row">
                    
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('sectionwise');?></div>
                            </div>
                            <div class="panel-body">
                    
                            <form action="<?php echo base_url().'branch_reporting/paid_students/'?>" method="post" name="paid_student_form" id="paid_student_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_section" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                              <label><?php echo get_phrase('select_section');?></label>
                                  <select id="section_id_filter" class="selectpicker form-control section_data" name="section_id">
                                  </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                    
                            </div>
                            </div>
                          </div>
                    
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('keyword_search');?></div>
                            </div>
                            <div class="panel-body">
                    
                            <form action="<?php echo base_url().'branch_reporting/paid_students/'?>" method="post" name="paid_student_form" id="paid_student_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                              <label><?php echo get_phrase('search_keyword');?></label>
                                  <input type="text" name="std_search" class="form-control" value="" placeholder="Keyword">
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                    
                            </form>
                            </div>
                            </div>
                          </div>
                          </div>
                          </br>
                          <!-- 3rd row -->
                    
                          <div class="row">
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('datewise');?></div>
                            </div>
                            <div class="panel-body">
                    
                            <form action="<?php echo base_url().'branch_reporting/paid_students/'?>" method="post" name="paid_student_form" id="paid_student_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                            <label><?php echo get_phrase('select_duration');?></label>
                              <select id="paid_date_filter" class="form-control"  name="date_filter">
                                <option value=""><?php echo get_phrase('select_duration'); ?></option>
                                <option value="one_month"><?php echo get_phrase('one_month');?></option>
                                <option value="two_months"><?php echo get_phrase('two_months');?></option>
                                <option value="six_months"><?php echo get_phrase('six_months');?></option>
                              </select>
                            </div>
                    
                            <div class="col-md-8">
                              <label><?php echo get_phrase('start_date');?></label>
                                  <input class="form-control datepicker" type="text" name="startdate" id="paidstartdate" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                                <label><?php echo get_phrase('end_date');?></label>
                                  <input class="form-control datepicker" type="text" name="enddate" id="paidenddate" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                            </div>
                            <div class="col-md-4" style="margin-top: 70px;">
                              <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                    
                            </form>
                            </div>
                            </div>
                          </div>
                    
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('studentwise');?></div>
                            </div>
                    
                            <div class="panel-body">
                            
                            <form action="<?php echo base_url().'branch_reporting/paid_students/'?>" method="post" name="paid_student_form" id="paid_student_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_section branch_ids" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                              <label id="section_id_filter_selection"><?php echo get_phrase('select_section');?></label>
                                    <select id="stud_sec_id_filter" class="selectpicker form-control section_data get_student_by_section" name="section_id">
                                    </select>
                            </div>
                            <div class="col-md-8">
                            <label><?php echo get_phrase('select_student');?></label>
                                <select id="student_select" class="form-control std_data"  name="student_id">
                                    
                                </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                            </div>
                            </div>
                          </div>
                          </div>
                    
                    
                                </div>
                            </div>
                        </div>
                    		<!-- OneThree students arrears-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapseOneThree"> <!-- data-parent="#accordion22" -->
                                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                                    <?php echo get_phrase('students_arrears');?>
                                    </a>
                                </h4>
                                <a class="full_listing" href="<?php echo base_url().'reports/students_arrears/'.$d_school_id?>">
                                    <i class="fa fa-table" aria-hidden="true"></i>
                                    <?php echo get_phrase('click_here_for_details');?></a>
                            </div>
                            <div id="collapseOneThree" class="panel-collapse collapse">
                                <div class="panel-body">
                                
                    
                    
                                <!-- 1st row -->
                          <div class="row">
                    
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('departmentwise');?></div>
                            </div>
                            <div class="panel-body">
                            
                            <form action="<?php echo base_url().'branch_reporting/students_arrears/'?>" method="post" name="students_arrears_form" id="students_arrears_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_depart" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                              <label><?php echo get_phrase('select_department');?></label>
                                  <select id="department_id_filter" class="selectpicker form-control depart_data" name="section_id">
                                  </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                    
                            </div>
                            </div>
                          </div>
                    
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('classwise');?></div>
                            </div>
                            <div class="panel-body">
                    
                            <form action="<?php echo base_url().'branch_reporting/students_arrears/'?>" method="post" name="students_arrears_form" id="students_arrears_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_class" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                              <label><?php echo get_phrase('select_class');?></label>
                                  <select id="class_id_filter" class="selectpicker form-control classs_data" name="section_id">
                                  </select>
                            </div>
                            <div class="col-md-4 top">
                                 <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                    
                            </div>
                            </div>
                          </div>
                          </div>
                          </br>
                    
                           <!-- 2nd row -->
                          <div class="row">
                    
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('sectionwise');?></div>
                            </div>
                            <div class="panel-body">
                    
                            <form action="<?php echo base_url().'branch_reporting/students_arrears/'?>" method="post" name="students_arrears_form" id="students_arrears_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_section" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-8">
                              <label><?php echo get_phrase('select_section');?></label>
                                  <select id="section_id_filter" class="selectpicker form-control section_data" name="section_id">
                                  </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                    
                            </div>
                            </div>
                          </div>
                    
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('keyword_search');?></div>
                            </div>
                            <div class="panel-body">
                    
                            <form action="<?php echo base_url().'branch_reporting/students_arrears/'?>" method="post" name="students_arrears_form" id="students_arrears_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-8">
                              <label><?php echo get_phrase('search_keyword');?></label>
                                  <input type="text" name="std_search" class="form-control" value="" placeholder="Keyword">
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                    
                            </form>
                            </div>
                            </div>
                          </div>
                          </div>
                          </br>
                    
                                <!-- 3rd row -->
                    
                          <div class="row">
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('datewise');?></div>
                            </div>
                            <div class="panel-body">
                    
                            <form action="<?php echo base_url().'branch_reporting/students_arrears/'?>" method="post" name="students_arrears_form" id="students_arrears_form">
                                <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-8">
                            <label><?php echo get_phrase('select_duration');?></label>
                              <select id="arrears_date_filter" class="form-control"  name="date_filter">
                                <option value=""><?php echo get_phrase('select_duration'); ?></option>
                                <option value="one_month"><?php echo get_phrase('one_month');?></option>
                                <option value="two_months"><?php echo get_phrase('two_months');?></option>
                                <option value="six_months"><?php echo get_phrase('six_months');?></option>
                              </select>
                            </div>
                    
                            <div class="col-md-8">
                              <label><?php echo get_phrase('start_date');?></label>
                                  <input class="form-control datepicker" type="text" name="startdate" id="arrearsstartdate" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                                <label><?php echo get_phrase('end_date');?></label>
                                  <input class="form-control datepicker" type="text" name="enddate" id="arrearsenddate" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                            </div>
                            <div class="col-md-4" style="margin-top: 70px;">
                              <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                    
                            </form>
                            </div>
                            </div>
                          </div>
                    
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('studentwise');?></div>
                            </div>
                    
                            <div class="panel-body">
                            
                            <form action="<?php echo base_url().'branch_reporting/students_arrears/'?>" method="post" name="students_arrears_form" id="students_arrears_form">
                                <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_section branch_ids" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                              <label id="section_id_filter_selection"><?php echo get_phrase('select_section');?></label>
                                    <select id="stud_sec_id_filter" class="selectpicker form-control section_data get_student_by_section" name="section_id">
                                    </select>
                            </div>
                            <div class="col-md-8">
                            <label><?php echo get_phrase('select_student');?></label>
                                <select id="student_select" class="form-control std_data"  name="student_id">
                                    
                                </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                            </div>
                            </div>
                          </div>
                          </div>
                    
                    
                              </div>
                            </div>
                        </div>                        
                    		<!-- OneFour classwise paid-->
                        <div class="panel panel-default">
                          <div class="panel-heading">
                              <h4 class="panel-title">
                                  <a data-toggle="collapse" href="#collapseOneFour"> <!-- data-parent="#accordion22" -->
                                  <i class="fa fa-plus-square" aria-hidden="true"></i>
                                  <?php echo get_phrase('classwise_payments');?>
                                  </a>
                              </h4>
                              <a class="full_listing" href="<?php echo base_url().'reports/section_wise_paid_fee/'.$d_school_id?>">
                                    <i class="fa fa-table" aria-hidden="true"></i>
                                    <?php echo get_phrase('click_here_for_details');?></a>
                          </div>
                          <div id="collapseOneFour" class="panel-collapse collapse">
                            <div class="panel-body">
                                <!-- 1st row -->
                            <div class="row">
                            <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('datewise');?></div>
                            </div>
                            <div class="panel-body">
                    
                            <form action="<?php echo base_url().'branch_reporting/section_wise_paid_fee/'?>" method="post" name="section_wise_paid_fee_form" id="section_wise_paid_fee_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-8">
                            <label><?php echo get_phrase('select_duration');?></label>
                              <select id="class_wise_date_filter" class="form-control"  name="date_filter">
                                <option value=""><?php echo get_phrase('select_duration'); ?></option>
                                <option value="one_month"><?php echo get_phrase('one_month');?></option>
                                <option value="two_months"><?php echo get_phrase('two_months');?></option>
                                <option value="six_months"><?php echo get_phrase('six_months');?></option>
                              </select>
                            </div>
                    
                            <div class="col-md-8">
                              <label><?php echo get_phrase('start_date');?></label>
                                  <input class="form-control datepicker" type="text" name="startdate" id="class_wisestartdate" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                                <label><?php echo get_phrase('end_date');?></label>
                                  <input class="form-control datepicker" type="text" name="enddate" id="class_wiseenddate" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                            </div>
                            <div class="col-md-4" style="margin-top: 70px;">
                              <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                    
                            </form>
                            </div>
                            </div>
                          </div>
                    
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('departmentwise');?></div>
                            </div>
                            <div class="panel-body">
                            
                            <form action="<?php echo base_url().'branch_reporting/section_wise_paid_fee/'?>" method="post" name="section_wise_paid_fee_form" id="section_wise_paid_fee_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_depart" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-8">
                              <label><?php echo get_phrase('select_department');?></label>
                                  <select id="department_id_filter" class="selectpicker form-control depart_data" name="section_id">
                                  </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                    
                            </div>
                            </div>
                          </div>
                          </div>
                          </br>
                                <!-- 2nd row -->
                          <div class="row">
                            <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('classwise');?></div>
                            </div>
                            <div class="panel-body">
                    
                            <form action="<?php echo base_url().'branch_reporting/section_wise_paid_fee/'?>" method="post" name="section_wise_paid_fee_form" id="section_wise_paid_fee_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_class" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-8">
                              <label><?php echo get_phrase('select_class');?></label>
                                  <select id="class_id_filter" class="selectpicker form-control classs_data" name="section_id">
                                  </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                    
                            </div>
                            </div>
                          </div>
                    
                          <div class="col-md-6">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left"><?php echo get_phrase('sectionwise');?></div>
                            </div>
                            <div class="panel-body">
                    
                            <form action="<?php echo base_url().'branch_reporting/section_wise_paid_fee/'?>" method="post" name="section_wise_paid_fee_form" id="section_wise_paid_fee_form">
                            <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control branch_dropdown_for_section" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-8">
                              <label><?php echo get_phrase('select_section');?></label>
                                  <select id="section_id_filter" class="selectpicker form-control section_data" name="section_id">
                                  </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                            </div>
                            </form>
                    
                            </div>
                            </div>
                          </div>
                          </div>
                    
                            </div>
                          </div>
                        </div>
                    </div>
                    </div>
                </div>
                <!-- OneTwo End -->
              
                <!-- OneTwo start -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapseOneTwoT"> <!-- data-parent="#accordion23" -->
                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                            <?php echo get_phrase('branch_staff_attendance');?>
                            </a>
                        </h4>
                        <a class="full_listing" href="<?php echo base_url().'branch_reporting/br_staff_attendance'?>">
                        <i class="fa fa-table" aria-hidden="true"></i><?php echo get_phrase('click_here_for_details');?></a>
                    </div>
                    <div id="collapseOneTwoT" class="panel-collapse collapse">
                        <div class="panel-body">
                            <!-- -------- Staff attendance collapse start ------------ -->
                      <div class="panel panel-default">
                          <div class="panel-heading">
                              <h4 class="panel-title">
                                  <a data-toggle="collapse"  href="#collapseThree"> <!-- data-parent="#accordion23" -->
                                  <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                  <?php echo get_phrase('staff_attendance_report');?>
                                  </a>
                              </h4>
                          </div>
                          <div id="collapseThree" class="panel-collapse collapse">
                              <div class="panel-body">
                                  <div class="panel-group" id="accordion2">
                                      <div class="panel panel-default">
                                          <div class="panel-heading">
                                              <h4 class="panel-title">
                                                  <a data-toggle="collapse" href="#collapseThreeOne"> <!-- data-parent="#accordion2"  -->
                                                  <i class="fa fa-plus-square" aria-hidden="true"></i>
                                                  <?php echo get_phrase('staff_monthly_attendance');?>
                                                  </a>
                                              </h4>
                                              <a class="full_listing" href="<?php echo base_url().'staff_attendance_report/staff_monthly_attendance/'.$d_school_id?>">
                                              <i class="fa fa-table" aria-hidden="true"></i>
                                             <?php echo get_phrase('click_here_for_details');?></a>
                    
                                          </div>
                                          <div id="collapseThreeOne" class="panel-collapse collapse in">
                                              <div class="panel-body">
                                                
                              <div class="row">
                              <div class="col-md-12">
                                <div class="panel panel-default">
                                <div class="panel-heading">
                                <div class="panel-title pull-left">Monthwise</div>
                                </div>
                                <div class="panel-body">
                    
                                  <form action="<?php echo base_url().'branch_reporting/staff_monthly_attendance/'?>" method="post" name="staff_monthly_attendance_form" id="staff_monthly_attendance_form">
                                        <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                                      <div class="col-md-5">
                                      <label>Select Date</label>
                                      <input class="form-control datepicker" id="staff_datepicker" data-date="" data-date-format="mm-yyyy" type="text" name="month_year" value="">
                                      </div>
                                      <div id="d3"></div>
                                      <div class="col-md-2 top">
                                      <input style="float: right;" type="submit" id="student_attend_submit" value="Get Report" class="btn btn-primary">
                                      </div>
                                  </form>
                    
                                </div>
                                </div>
                              </div>
                    
                              </div>
                    
                                              </div>
                                          </div>
                                      </div>
                                      </br>
                    
                    
                                      <div class="panel-group" id="accordion2">
                                      <div class="panel panel-default">
                                          <div class="panel-heading">
                                              <h4 class="panel-title">
                                                  <a data-toggle="collapse" href="#collapseThreeTwo"> <!-- data-parent="#accordion23"  -->
                                                  <i class="fa fa-plus-square" aria-hidden="true"></i>
                                                  <?php echo get_phrase('staff_monthly_timing_details');?>
                                                  </a>
                                              </h4>
                                              <a class="full_listing" href="<?php echo base_url().'staff_attendance_report/staff_timing_detail/'.$d_school_id;?>">
                                              <i class="fa fa-table" aria-hidden="true"></i>
                                             <?php echo get_phrase('click_here_for_details');?></a>
                                          </div>
                                          <div id="collapseThreeTwo" class="panel-collapse collapse">
                                              <div class="panel-body">
                                                
                              <div class="row">
                              <div class="col-md-12">
                                <div class="panel panel-default">
                                <div class="panel-heading">
                                <div class="panel-title pull-left">Monthwise</div>
                                </div>
                                <div class="panel-body">
                    
                                  <form action="<?php echo base_url().'branch_reporting/staff_timing_detail/'?>" method="post" name="staff_timing_detail_form" id="staff_timing_detail_form">
                                        <div class="col-sm-12">
                                <label>
                              <?php echo get_phrase('select_branch');?></label>
                                <select class="form-control" name="d_school_id">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch_arr as $key => $value) { ?>
                                    <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                                      <div class="col-md-5 col-lg-5 col-sm-5">
                                      <label>Select Date</label>
                                      <input class="form-control datepicker" id="staff_timing_datepicker" data-date="" data-date-format="mm-yyyy" type="text" name="month_year" value="">
                                      </div>
                    
                                      <div class="col-md-5 col-lg-5 col-sm-5">
                                       <label><?php echo get_phrase('select_staff');?></label>
                                          <select id="staff_id" class="selectpicker form-control" name="staff_id">
                                          <?php echo staff_option_list($staff_id,$d_school_id);?>
                                          </select>
                                      </div>
                                      <div id="d3"></div>
                                      <div class="col-md-2 top">
                                      <input style="float: right;" type="submit" id="student_attend_submit" value="Get Report" class="btn btn-primary">
                                      </div>
                                  </form>
                    
                                </div>
                                </div>
                              </div>
                    
                              </div>
                    
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                    
                                  <!-- <div class="panel panel-default">
                                      <div class="panel-heading">
                                          <h4 class="panel-title">
                                              <a data-toggle="collapse" data-parent="#accordion2" href="#collapseThreeThree">
                                              <i class="fa fa-plus-square" aria-hidden="true"></i>
                                              <?php //echo get_phrase('staff_monthly_details');?>
                                              </a>
                                          </h4>
                                          <a class="full_listing" href="<?php //echo base_url();?>staff_attendance_report/staff_monthly_details">
                                              <i class="fa fa-table" aria-hidden="true"></i>
                                             <?php //echo get_phrase('click_here_for_details');?></a>
                                      </div>
                                      <div id="collapseThreeThree" class="panel-collapse collapse">
                                          <div class="panel-body">Panel 3.2</div>
                                      </div>
                                  </div> --> 
                              </div>
                          </div>
                      </div>
                    <!-- ---------- Staff attendance collapse end ------------ -->
                        </div>
                    </div>
                </div>
                </div>
                <!-- OneTwo End -->
              
                <!-- OneThree Start -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapseSevenOne"> <!-- data-parent="#accordion222"  -->
                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                            <?php echo get_phrase('branch_students_attendance');?>
                            </a>
                        </h4>
                        <a class="full_listing" href="<?php echo base_url().'branch_reporting/br_studnets_attendance'?>">
                        <i class="fa fa-table" aria-hidden="true"></i><?php echo get_phrase('click_here_for_details');?></a>
                    </div>
                    <div id="collapseSevenOne" class="panel-collapse collapse">
                        <div class="panel-body">
                            <!-- -------- Student Subjectwise attendance collapse start ---------- --> 
<div class="panel panel-default">
  <div class="panel-heading">
      <h4 class="panel-title">
          <a data-toggle="collapse" href="#collapseSeven"> <!-- data-parent="#accordion222"  -->
          <i class="fa fa-file-text-o" aria-hidden="true"></i>
          <?php echo get_phrase('student_subjectwise_attendance_report');?>
          </a>
      </h4>
  </div>
  <div id="collapseSeven" class="panel-collapse collapse">
      <div class="panel-body">

      <!-- section wise attendance report -->
        <div class="panel-group" id="accordion7">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapseSevenOne"> <!-- data-parent="accordion7"  -->
                <i class="fa fa-plus-square" aria-hidden="true"></i>
                <?php echo get_phrase('section_subjectwise_attendance_report');?></a>
              </h4>
              <a class="full_listing" href="<?php echo base_url().'student_attendance_report/subjectwise_attendance/'.$d_school_id?>">
                <i class="fa fa-table" aria-hidden="true"></i>
                <?php echo get_phrase('click_here_for_details');?></a>
            </div>
         <div id="collapseSevenOne" class="panel-collapse collapse in">
        <div class="panel-body">

        <div class="row">

        <div class="col-md-12">

        <div class="panel panel-default">
        <div class="panel-heading">
        <div class="panel-title pull-left"><?php echo get_phrase('subjectwise');?></div>
        </div>
        <div class="panel-body">
        
        <form action="<?php echo base_url().'branch_reporting/subjectwise_attendance/'?>" method="post" name="section_wise_attendance_form" id="section_wise_attendance_form">

        <div class="col-md-12">
              <span class="text-center" style="font-size:0.8em;color:red;font-weight:bold:padding:5px" id="subjectwiseattendancemsgspan"></span>
        </div>
        <div class="col-sm-12">
            <label>
          <?php echo get_phrase('select_branch');?></label>
            <select class="form-control branch_dropdown_for_depart" name="d_school_id">
                <option value="">Select Branch</option>
                <?php foreach ($branch_arr as $key => $value) { ?>
                <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-3">
          <label><?php echo get_phrase('start_date');?></label>
              <input class="form-control" type="date" name="start_date_subjectwise" id="start_date_subjectwise" value="" style="background-color:#FFF !important;">
        </div>
        <div class="col-md-3">
          <label><?php echo get_phrase('end_date');?></label>
              <input class="form-control" type="date" name="end_date_subjectwise" id="end_date_subjectwise" value="" style="background-color:#FFF !important;">
        </div>
        <div class="col-md-3">
             <label><?php echo get_phrase('section');?></label>
            <select id="section_id_subjectwise" class="selectpicker form-control section_data" name="section_id_subjectwise">
                            <?php echo section_selector($section_id); ?>
            </select>
            <?php //echo section_selector($section_id);?>
        </div>
        <div class="col-md-3 top">
              <input style="float: right;" id="subjectwiseattendancebtn" type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
        </div>
        </form>

        </div>
        </div>

        </div>
        

        </div>
        </div>
        </div>
        </div>




      </div>
  </div>
</div>
</div>
<!-- -------- Student Subjectwise attendance collapse end ------------ -->
                        </div>
                    </div>
                </div>
                <!-- OneThree End -->
                
                <!-- ---------- Admission Report collapse start ------------ -->
                <div class="panel panel-default">
                  <div class="panel-heading">
                      <h4 class="panel-title">
                          <a data-toggle="collapse"  href="#collapseFive"> <!-- data-parent="#accordion1" -->
                          <i class="fa fa-plus-square" aria-hidden="true"></i>
                          <?php echo get_phrase('branch_students_admission_report');?>
                          </a>
                      </h4>
                  </div>
                  <div id="collapseFive" class="panel-collapse collapse">
                      <div class="panel-body">
                          <div class="panel-group" id="accordion2">
                              <div class="panel panel-default">
                                  <div class="panel-heading">
                                      <h4 class="panel-title">
                                          <a data-toggle="collapse" href="#collapseFourOne">  <!-- data-parent="#accordion2" -->
                                          <i class="fa fa-plus-square" aria-hidden="true"></i>
                                          <?php echo get_phrase('students_admission_report');?>
                                          </a>
                                      </h4>
                                      <a class="full_listing" href="<?php echo base_url()?>">
                                      <i class="fa fa-table" aria-hidden="true"></i>
                                     <?php echo get_phrase('click_here_for_details');?></a>
                                  </div>
                                  <div id="collapseFourOne" class="panel-collapse collapse in">
                                      <div class="panel-body">
                                      <!--   2nd row   -->
                                       <div class="row">
                                            <div class="col-md-6">
                                                <form action="<?php echo base_url().'branch_reporting/section_wise_students_list/'?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                                                  <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                      <div class="panel-title pull-left"><?php echo get_phrase('sectionwise');?></div>
                                                    </div>
                                                  <div class="panel-body">
                                                     <div class="col-sm-12">
                                                        <label>
                                                      <?php echo get_phrase('select_branch');?></label>
                                                        <select class="form-control branch_dropdown_for_section" name="d_school_id">
                                                            <option value="">Select Branch</option>
                                                            <?php foreach ($branch_arr as $key => $value) { ?>
                                                            <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
            
                                                      <div class="col-md-8">
                                                        <label><?php echo get_phrase('select_section');?></label>
                                                            <select id="section_id_filter" class="selectpicker form-control section_data" name="section_id">
                                                            </select>
                                                      </div>
                                                      <div class="col-md-4 top">
                                                            <input type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary btn-block">
                                                      </div>
                                                    
                                                    </div>
                                                  </div>
                                                </form>
                                            </div>
                                            <div class="col-md-6">
                                                <form action="<?php echo base_url().'branch_reporting/section_wise_students_list/'?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                                                  <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                      <div class="panel-title pull-left"><?php echo get_phrase('department_wise');?></div>
                                                    </div>
                                                  <div class="panel-body">
                                                      <div class="col-sm-12">
                                                            <label>
                                                          <?php echo get_phrase('select_branch');?></label>
                                                            <select class="form-control branch_dropdown_for_depart" name="d_school_id">
                                                                <option value="">Select Branch</option>
                                                                <?php foreach ($branch_arr as $key => $value) { ?>
                                                                <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
            
                                                      <div class="col-md-8">
                                                            <label><?php echo get_phrase('select_department');?></label>
                                                            <select id="department_id_filter" class="selectpicker form-control depart_data" name="department">
                                                            </select>
                                                      </div>
                                                      <div class="col-md-4 top">
                                                            <input type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary btn-block">
                                                      </div>
                                                    
                                                    </div>
                                                  </div>
                                                </form>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <form action="<?php echo base_url().'branch_reporting/section_wise_students_list/'?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                                                  <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                      <div class="panel-title pull-left"><?php echo get_phrase('academic_year_wise');?></div>
                                                    </div>
                                                  <div class="panel-body">
                                                      <div class="col-sm-12">
                                                        <label>
                                                      <?php echo get_phrase('select_branch');?></label>
                                                        <select class="form-control branch_dropdown_for_year" name="d_school_id">
                                                            <option value="">Select Branch</option>
                                                            <?php foreach ($branch_arr as $key => $value) { ?>
                                                            <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
            
                                                      <div class="col-md-8">
                                                            <label><?php echo get_phrase('select_academic_year');?></label>
                                                            <select id="department_id_filter" class="selectpicker form-control year_data" name="academic_year">
                                                            </select>
                                                      </div>
                                                      <div class="col-md-4 top">
                                                            <input type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary btn-block">
                                                      </div>
                                                    
                                                    </div>
                                                  </div>
                                                </form>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="panel panel-default">
                                                <div class="panel-heading">
                                                <div class="panel-title pull-left"><?php echo get_phrase('datewise');?></div>
                                                </div>
                                                <div class="panel-body">
                                        
                                                <form action="<?php echo base_url().'branch_reporting/section_wise_students_list/'?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                                                <div class="col-sm-12">
                                            <label>
                                          <?php echo get_phrase('select_branch');?></label>
                                            <select class="form-control branch_dropdown_for_class" name="d_school_id">
                                                <option value="">Select Branch</option>
                                                <?php foreach ($branch_arr as $key => $value) { ?>
                                                <option value="<?= $value['school_id'] ?>"><?= $value['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
            
                                                <div class="col-md-12">
                                                  <label><?php echo get_phrase('select_class');?></label>
                                                  <select id="class_id_filter" class="selectpicker form-control classs_data" name="class">
                                                  </select>
                                                </div>
                                        
                                                <div class="col-md-6">
                                                    <label><?php echo get_phrase('start_date');?></label>
                                                    <input class="form-control" type="date" name="startdate" id="startdate" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                                                </div>
                                                <div class="col-md-6">
                                                    <label><?php echo get_phrase('end_date');?></label>
                                                    <input class="form-control" type="date" name="enddate" id="enddate" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                                                </div>
                                                <div class="col-md-12" style="margin-top: 70px;">
                                                  <input type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                                                </div>
                                        
                                                </form>
                                                </div>
                                                </div>
                                              </div>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                      </div>
                  </div>
              </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- -------- branch Reports collapse end ------------ -->

<!-- -------- branches List collapse end ------------ -->


<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" href="#collapseTwo"> <!-- data-parent="#accordion1"  -->
            <i class="fa fa-file-text-o" aria-hidden="true"></i>
            <?php echo get_phrase('branches_list');?>       
            </a>
        </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="panel-group" id="accordion2">
                <?php foreach ($branch_arr as $key => $value){?>
                <div class="panel-group" id="accordion2_<?php echo $value['school_id'] ?>">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php
                            /*
                            <img class="img-responsive" style="max-width:80px; max-height: 50px;" src="<?php echo display_link($value['logo'],$value['folder_name'],1);?>" />
                            */
                            ?>
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapseTwo_<?php echo $value['school_id'] ?>"> <!-- data-parent="#accordion2"  -->
                                <i class="fa fa-plus-square" aria-hidden="true"></i>
                                <?php echo $value['name']?>
                                </a>
                            </h4>
                            <a class="full_listing" href="<?php echo base_url().'reports/reports_listing/'.$value['school_id']?>">
                            <i class="fa fa-table" aria-hidden="true"></i><?php echo get_phrase('click_here_for_details');?>
                            </a>
                        </div>
                        <div id="collapseTwo_<?php echo $value['school_id'] ?>" class="panel-collapse collapse">
                            <div class="panel-body">       
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
  $(document).ready(function()
  {
  	$('i').click(function(){
        $(this).toggleClass('fa-plus-square fa-minus-square');
    });
    var today = new Date();
    var startDate = new Date( today.getFullYear() , today.getMonth ,1);
        $('.datepicker1').datepicker({
              format: 'dd/mm/yyyy',
              keyboardNavigation:false,
              forceParse: false,
              autoclose:true,
              endDate: '+0d',
              defaultDate : new Date(),
              startDate: '-3d'
        }).datepicker('setStartDate' , startDate); 
        //______________staff attendance functions______________
    $("#staff_datepicker").datepicker({
            format: "mm/yyyy",
            startView: "months", 
            minViewMode: "months"
    });
    var datepicker_val = $('#staff_datepicker').val();
    
    if ( $('#start_date_subjectwise').val() != "" &&  $('#end_date_subjectwise').val() != "" ){
                      
        if ( $('#start_date_subjectwise').val()  >  $('#end_date_subjectwise').val() ){
            isvalid = false;
            errors += 'Start Date can not be greater than the End Date <br>';
        }     
    }
    
        $(".branch_dropdown_for_depart").change(function() {
            var school = $(this).val();
            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');

            $.ajax({
                type: 'POST',
                data: {
                    school: school
                },
                url: "<?php echo base_url();?>branch_reporting/get_depart_by_school",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    $(".depart_data").html(response);
                }
            });
        });
        
        $(".branch_dropdown_for_class").change(function() {
            var school = $(this).val();
            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');

            $.ajax({
                type: 'POST',
                data: {
                    school: school
                },
                url: "<?php echo base_url();?>branch_reporting/get_class_by_school",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    $(".classs_data").html(response);
                }
            });
        });
        
        $(".branch_dropdown_for_section").change(function() {
            var school = $(this).val();
            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');

            $.ajax({
                type: 'POST',
                data: {
                    school: school
                },
                url: "<?php echo base_url();?>branch_reporting/get_section_by_school",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    $(".section_data").html(response);
                }
            });
        });
        
        $(".branch_dropdown_for_year").change(function() {
            var school = $(this).val();
            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');

            $.ajax({
                type: 'POST',
                data: {
                    school: school
                },
                url: "<?php echo base_url();?>branch_reporting/get_year_by_school",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    $(".year_data").html(response);
                }
            });
        });
        
        $(".branch_dropdown_for_year").change(function() {
            var school = $(this).val();
            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');

            $.ajax({
                type: 'POST',
                data: {
                    school: school
                },
                url: "<?php echo base_url();?>branch_reporting/get_year_by_school",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    $(".year_data").html(response);
                }
            });
        });
        
        $(".get_student_by_section").change(function() {
            var section = $(this).val();
            var school = $('.branch_ids').val();
            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');
            $.ajax({
                type: 'POST',
                data: {
                    section: section,
                    school: school
                },
                url: "<?php echo base_url();?>branch_reporting/get_student_by_section",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    $(".std_data").html(response);
                }
            });
        });
  });
</script>


