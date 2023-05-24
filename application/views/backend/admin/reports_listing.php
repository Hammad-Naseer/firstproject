<style>
    h4.panel-title{
       display:-webkit-inline-box; 
    }

    .top {
        position: relative;
        top: 29px;
    }
[data-toggle="collapse"]:after { 
    margin-left: 4px;
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
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline">
                   <?php echo get_phrase('reports'); ?>
            </h3>
        </div>
        <?php
            if ($branch_name!= "")
            {
            ?>
            <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
             <div class="col-md-10 blocks">
              <?php

              $logo=system_path($branch_logo,$branch_folder,1);
                if($branch_logo=="" || !is_file($logo))
                {
                ?>
                <a href=""><img style="width: 150px; height: 100px;"  src="<?php echo base_url();?>assets/images/gsims_logo.png">
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
              <div class="col-md-2 pull-right" style="margin-top: 9px;"><a href="<?php echo base_url();?>branch_reporting/branch_reports_listing" class="btn btn-primary"><?php echo get_phrase('back'); ?></a>
              </div>
            </div>
        <?php
            }
        ?>
</div>

<div class="panel-group report-listing" id="accordion1" data-step="1" data-position="top" data-intro="you can see current active school reports. collapse each report then choose which report you want to select field/options then press the submit button to get a report">
  <!-- <div class="panel panel-default">
      <div class="panel-heading">
          <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">
              <i class="fa fa-file-text-o" aria-hidden="true"></i>
              <?php echo get_phrase('student_fee_reports');?>
              </a>
          </h4>
      </div>
      <div id="collapseOne" class="panel-collapse collapse">
      
      <div class="panel-body">
          <div class="panel-group" id="accordion2">
                <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOneOne">
                                <i class="fa fa-plus-square" aria-hidden="true"></i>
                                <?php echo get_phrase('unpaid_students');?>
                                </a>
                            </h4>
                            <a class="full_listing" href="<?php echo base_url().'reports/unpaid_students/'.$d_school_id?>">
                            <i class="fa fa-table" aria-hidden="true"></i>
                            <?php echo get_phrase('click_here_for_details');?></a>
                        </div>
                        <div id="collapseOneOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
            
                  <div class="col-md-6">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                    <div class="panel-title pull-left"><?php echo get_phrase('departmentwise');?></div>
                    </div>
                    <div class="panel-body">
                    
                    <form action="<?php echo base_url().'reports/unpaid_students/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
            
                    <div class="col-md-8">
                      <label>
                      <?php echo get_phrase('select_department');?></label>
                          <select id="department_id_filter" class="selectpicker form-control" name="section_id">
                            <?php
                            echo department_list('',$d_school_id);
                            ?>
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
                    <form action="<?php echo base_url().'reports/unpaid_students/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                    <div class="col-md-8">
                      <label><?php echo get_phrase('select_class');?></label>
                          <select id="class_id_filter" class="selectpicker form-control" name="section_id">
                            <?php
                            echo class_list('',$d_school_id);
                            ?>
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
                  <div class="row">
            
                  <div class="col-md-6">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                    <div class="panel-title pull-left"><?php echo get_phrase('sectionwise');?></div>
                    </div>
                    <div class="panel-body">
            
                    <form action="<?php echo base_url().'reports/unpaid_students/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
            
                    <div class="col-md-8">
                      <label><?php echo get_phrase('select_section');?></label>
                          <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                            <?php
                            echo section_list('',$d_school_id);
                            ?>
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
            
                    <form action="<?php echo base_url().'reports/unpaid_students/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
            
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
            
        
                  <div class="row">
                  <div class="col-md-6">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                    <div class="panel-title pull-left"><?php echo get_phrase('datewise');?></div>
                    </div>
                    <div class="panel-body">
            
                    <form action="<?php echo base_url().'reports/unpaid_students/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
            
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
                    
                    <form action="<?php echo base_url().'reports/unpaid_students/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
            
                    <div class="col-md-8">
                      <label id="section_id_filter_selection"><?php echo get_phrase('select_section');?></label>
                            <select id="stud_sec_id_filter" class="selectpicker form-control" name="section_id">
                            <option value="">
                            <?php echo get_phrase('select_section');?>
                            </option>
                            <?php echo department_class_section($section_id,$d_school_id);?>
                            </select>
                    </div>
            
                    <div class="col-md-8">
                    <label><?php echo get_phrase('select_student');?></label>
                          <select id="student_select" class="form-control"  name="student_id">
                                <option value=""><?php echo get_phrase('select_student'); ?></option>
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOneTwo">
                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                            <?php echo get_phrase('paid_students');?>
                            </a>
                        </h4>
                        <a class="full_listing" href="<?php echo base_url().'reports/paid_students/'.$d_school_id?>">
                            <i class="fa fa-table" aria-hidden="true"></i>
                            <?php echo get_phrase('click_here_for_details');?></a>
                    </div>
                    <div id="collapseOneTwo" class="panel-collapse collapse">
                        <div class="panel-body">
                          
                  <div class="row">
            
                  <div class="col-md-6">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                    <div class="panel-title pull-left"><?php echo get_phrase('departmentwise');?></div>
                    </div>
                    <div class="panel-body">
                    
                    <form action="<?php echo base_url().'reports/paid_students/'.$d_school_id?>" method="post" name="paid_student_form" id="paid_student_form">
            
                    <div class="col-md-8">
                      <label><?php echo get_phrase('select_department');?></label>
                          <select id="department_id_filter" class="selectpicker form-control" name="section_id">
                            <?php
                            echo department_list('',$d_school_id);
                            ?>
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
            
                    <form action="<?php echo base_url().'reports/paid_students/'.$d_school_id?>" method="post" name="paid_student_form" id="paid_student_form">
            
                    <div class="col-md-8">
                      <label><?php echo get_phrase('select_class');?></label>
                          <select id="class_id_filter" class="selectpicker form-control" name="section_id">
                            <?php
                            echo class_list('',$d_school_id);
                            ?>
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
                  <div class="row">
            
                  <div class="col-md-6">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                    <div class="panel-title pull-left"><?php echo get_phrase('sectionwise');?></div>
                    </div>
                    <div class="panel-body">
            
                    <form action="<?php echo base_url().'reports/paid_students/'.$d_school_id?>" method="post" name="paid_student_form" id="paid_student_form">
            
                    <div class="col-md-8">
                      <label><?php echo get_phrase('select_section');?></label>
                          <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                            <?php
                            echo section_list('',$d_school_id);
                            ?>
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
            
                    <form action="<?php echo base_url().'reports/paid_students/'.$d_school_id?>" method="post" name="paid_student_form" id="paid_student_form">
            
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
            
                  <div class="row">
                  <div class="col-md-6">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                    <div class="panel-title pull-left"><?php echo get_phrase('datewise');?></div>
                    </div>
                    <div class="panel-body">
            
                    <form action="<?php echo base_url().'reports/paid_students/'.$d_school_id?>" method="post" name="paid_student_form" id="paid_student_form">
            
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
                    
                    <form action="<?php echo base_url().'reports/paid_students/'.$d_school_id?>" method="post" name="paid_student_form" id="paid_student_form">
            
                    <div class="col-md-8">
                      <label id="section_id_filter_selection"><?php echo get_phrase('select_section');?></label>
                            <select id="paid_stud_sec_id_filter" class="selectpicker form-control" name="section_id">
                            <option value="">
                            <?php echo get_phrase('select_section');?>
                            </option>
                            <?php echo department_class_section($section_id,$d_school_id);?>
                            </select>
                    </div>
            
                    <div class="col-md-8">
                    <label><?php echo get_phrase('select_student');?></label>
                          <select id="paid_student_select" class="form-control"  name="student_id">
                                <option value=""><?php echo get_phrase('select_student'); ?></option>
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOneThree">
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
                        
                  <div class="row">
            
                  <div class="col-md-6">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                    <div class="panel-title pull-left"><?php echo get_phrase('departmentwise');?></div>
                    </div>
                    <div class="panel-body">
                    
                    <form action="<?php echo base_url().'reports/students_arrears/'.$d_school_id?>" method="post" name="students_arrears_form" id="students_arrears_form">
            
                    <div class="col-md-8">
                      <label><?php echo get_phrase('select_department');?></label>
                          <select id="department_id_filter" class="selectpicker form-control" name="section_id">
                            <?php
                            echo department_list('',$d_school_id);
                            ?>
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
            
                    <form action="<?php echo base_url().'reports/students_arrears/'.$d_school_id?>" method="post" name="students_arrears_form" id="students_arrears_form">
            
                    <div class="col-md-8">
                      <label><?php echo get_phrase('select_class');?></label>
                          <select id="class_id_filter" class="selectpicker form-control" name="section_id">
                            <?php
                            echo class_list('',$d_school_id);
                            ?>
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
            
                  <div class="row">
            
                  <div class="col-md-6">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                    <div class="panel-title pull-left"><?php echo get_phrase('sectionwise');?></div>
                    </div>
                    <div class="panel-body">
            
                    <form action="<?php echo base_url().'reports/students_arrears/'.$d_school_id?>" method="post" name="students_arrears_form" id="students_arrears_form">
            
                    <div class="col-md-8">
                      <label><?php echo get_phrase('select_section');?></label>
                          <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                            <?php
                            echo section_list('',$d_school_id);
                            ?>
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
            
                    <form action="<?php echo base_url().'reports/students_arrears/'.$d_school_id?>" method="post" name="students_arrears_form" id="students_arrears_form">
            
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
        
            
                  <div class="row">
                  <div class="col-md-6">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                    <div class="panel-title pull-left"><?php echo get_phrase('datewise');?></div>
                    </div>
                    <div class="panel-body">
            
                    <form action="<?php echo base_url().'reports/students_arrears/'.$d_school_id?>" method="post" name="students_arrears_form" id="students_arrears_form">
            
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
                    
                    <form action="<?php echo base_url().'reports/students_arrears/'.$d_school_id?>" method="post" name="students_arrears_form" id="students_arrears_form">
            
                    <div class="col-md-8">
                      <label id="section_id_filter_selection"><?php echo get_phrase('select_section');?></label>
                            <select id="arrears_stud_sec_id_filter" class="selectpicker form-control" name="section_id">
                            <option value="">
                            <?php echo get_phrase('select_section');?>
                            </option>
                            <?php echo department_class_section($section_id,$d_school_id);?>
                            </select>
                    </div>
            
                    <div class="col-md-8">
                    <label><?php echo get_phrase('select_student');?></label>
                          <select id="arrears_student_select" class="form-control"  name="student_id">
                                <option value=""><?php echo get_phrase('select_student'); ?></option>
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
                <div class="panel panel-default">
                  <div class="panel-heading">
                      <h4 class="panel-title">
                          <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOneFour">
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
                        
                    <div class="row">
                    <div class="col-md-6">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                    <div class="panel-title pull-left"><?php echo get_phrase('datewise');?></div>
                    </div>
                    <div class="panel-body">
            
                    <form action="<?php echo base_url().'reports/section_wise_paid_fee/'.$d_school_id?>" method="post" name="section_wise_paid_fee_form" id="section_wise_paid_fee_form">
            
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
                    
                    <form action="<?php echo base_url().'reports/section_wise_paid_fee/'.$d_school_id?>" method="post" name="section_wise_paid_fee_form" id="section_wise_paid_fee_form">
            
                    <div class="col-md-8">
                      <label><?php echo get_phrase('select_department');?></label>
                          <select id="department_id_filter" class="selectpicker form-control" name="section_id">
                            <?php
                            echo department_list('',$d_school_id);
                            ?>
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
                  <div class="row">
                    <div class="col-md-6">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                    <div class="panel-title pull-left"><?php echo get_phrase('classwise');?></div>
                    </div>
                    <div class="panel-body">
            
                    <form action="<?php echo base_url().'reports/section_wise_paid_fee/'.$d_school_id?>" method="post" name="section_wise_paid_fee_form" id="section_wise_paid_fee_form">
            
                    <div class="col-md-8">
                      <label><?php echo get_phrase('select_class');?></label>
                          <select id="class_id_filter" class="selectpicker form-control" name="section_id">
                            <?php
                            echo class_list('',$d_school_id);
                            ?>
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
            
                    <form action="<?php echo base_url().'reports/section_wise_paid_fee/'.$d_school_id?>" method="post" name="section_wise_paid_fee_form" id="section_wise_paid_fee_form">
            
                    <div class="col-md-8">
                      <label><?php echo get_phrase('select_section');?></label>
                          <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                            <?php
                            echo section_list('',$d_school_id);
                            ?>
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
  </div>-->
<?PHP 

$quer = "select  * from " . get_school_db() . ".attendance_type  where school_id=" . $_SESSION['school_id'] . "";
        $attendance_count = $this->db->query($quer)->result_array();
       
$type = $attendance_count[0]['login_type'];
if($type == 1){
?>
 <!-- -------- Student fee collapse end ---------- --> 


<!-- -------- Student attendance collapse start ---------- --> 
<div class="panel panel-default">
  <div class="panel-heading">
      <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo">
          <i class="fa fa-file-text-o" aria-hidden="true"></i>
          <?php echo get_phrase('student_attendance_report');?>
          </a>
      </h4>
  </div>
  <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body">

      <!-- section wise attendance report -->
        <div class="panel-group" id="accordion2">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwoOne">
                <i class="fa fa-plus-square" aria-hidden="true"></i>
                <?php echo get_phrase('section_wise_attendance_report');?></a>
              </h4>
              <a class="full_listing" href="<?php echo base_url().'student_attendance_report/section_wise_attendance/'.$d_school_id?>">
                <i class="fa fa-table" aria-hidden="true"></i>
                <?php echo get_phrase('click_here_for_details');?></a>
            </div>
         <div id="collapseTwoOne" class="panel-collapse collapse in">
        <div class="panel-body">

        <div class="row">

        <div class="col-md-6">

        <div class="panel panel-default">
        <div class="panel-heading">
        <div class="panel-title pull-left"><?php echo get_phrase('departmentwise');?></div>
        </div>
        <div class="panel-body">
        
        <form action="<?php echo base_url().'student_attendance_report/section_wise_attendance/'.$d_school_id?>" method="post" name="section_wise_attendance_form" id="section_wise_attendance_form">

        <div class="col-md-8">
          <label><?php echo get_phrase('select_department');?></label>
              <select id="department_id_filter" class="selectpicker form-control" name="section_id">
                <?php
                echo department_list('',$d_school_id);
                ?>
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

        <form action="<?php echo base_url().'student_attendance_report/section_wise_attendance/'.$d_school_id?>" method="post" name="section_wise_attendance_form" id="section_wise_attendance_form">

        <div class="col-md-8">
          <label><?php echo get_phrase('select_class');?></label>
              <select id="class_id_filter" class="selectpicker form-control" name="section_id">
                <?php
                echo class_list('',$d_school_id);
                ?>
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

        <form action="<?php echo base_url().'student_attendance_report/section_wise_attendance/'.$d_school_id?>" method="post" name="section_wise_attendance_form" id="section_wise_attendance_form">

        <div class="col-md-8">
          <label><?php echo get_phrase('select_section');?></label>
              <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                <?php
                echo section_list('',$d_school_id);
                ?>
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
        <div class="panel-title pull-left"><?php echo get_phrase('datewise');?></div>
        </div>
        <div class="panel-body">
        
        <form action="<?php echo base_url().'student_attendance_report/section_wise_attendance/'.$d_school_id?>" method="post" name="section_wise_attendance_form" id="section_wise_attendance_form">

        <div class="col-md-8">
          <label><?php echo get_phrase('select_date');?></label>
              <input class="form-control datepicker" type="text" name="select_date" id="select_date" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
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


        <!-- student wise attendance reports -->

        <div class="panel-group" id="accordion2">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwoTwo">
                <i class="fa fa-plus-square" aria-hidden="true"></i>
                <?php echo get_phrase('student_wise_monthly_attendance_report');?></a>
              </h4>
              <a class="full_listing" href="<?php echo base_url().'student_attendance_report/student_wise_attendance/'.$d_school_id?>">
                <i class="fa fa-table" aria-hidden="true"></i>
                <?php echo get_phrase('click_here_for_details');?></a>
            </div>
         <div id="collapseTwoTwo" class="panel-collapse collapse">
        <div class="panel-body">
        <!-- 1st row -->
        <div class="row">

        <div class="col-md-12">

        <div class="panel panel-default">
        <div class="panel-heading">
        <div class="panel-title pull-left"><?php echo get_phrase('sectionwise');?></div>
        </div>
        <div class="panel-body">

        <form action="<?php echo base_url().'student_attendance_report/student_wise_attendance/'.$d_school_id?>" method="post" name="student_wise_attendance_form" id="student_wise_attendance_form">

        <div class="col-md-5">
          <label><?php echo get_phrase('select_date');?></label>

              <input class="form-control datepicker" id="datepicker" data-date="" data-date-format="mm-yyyy" type="text" name="month_year" value="">
        </div>
        <div class="col-md-5">
          <label><?php echo get_phrase('select_section');?></label>
              <select id="student_attend_section_id_filter" class="selectpicker form-control" name="section_id">
                <?php
                echo section_list('',$d_school_id);
                ?>
              </select>
        </div>
        <div id="d3"></div>
        <div class="col-md-2 top">
              <input style="float: right;" type="submit" id="student_attend_submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
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
<!-- -------- Student attendance collapse end ------------ -->
<?php
}
if($type == 0){
?>

<!-- -------- Student Subjectwise attendance collapse start ---------- --> 
<div class="panel panel-default">
  <div class="panel-heading">
      <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion7" href="#collapseSeven">
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
                <a data-toggle="collapse" data-parent="accordion7" href="#collapseSevenOne">
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
        
        <form action="<?php echo base_url().'student_attendance_report/subjectwise_attendance/'.$d_school_id?>" method="post" name="section_wise_attendance_form" id="section_wise_attendance_form">

        <div class="col-md-12">
              <span class="text-center" style="font-size:0.8em;color:red;font-weight:bold:padding:5px" id="subjectwiseattendancemsgspan"></span>
        </div>

        <div class="col-md-3">
          <label><?php echo get_phrase('start_date');?></label>
              <input class="form-control datepicker1" type="text" name="start_date_subjectwise" id="start_date_subjectwise" value="" readonly="readonly" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
        </div>
        <div class="col-md-3">
          <label><?php echo get_phrase('end_date');?></label>
              <input class="form-control datepicker1" type="text" name="end_date_subjectwise" id="end_date_subjectwise" value="" readonly="readonly" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
        </div>
        <div class="col-md-3">
             <label><?php echo get_phrase('section');?></label>
            <select id="section_id_subjectwise" class="selectpicker form-control" name="section_id_subjectwise">
                            <?php echo section_selector($section_id);?>
            </select>
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
<?php } ?>

<!-- -------- Staff attendance collapse start ------------ -->
  <div class="panel panel-default">
      <div class="panel-heading">
          <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion1" href="#collapseThree">
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
                              <a data-toggle="collapse" data-parent="#accordion2" href="#collapseThreeOne">
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

              <form action="<?php echo base_url().'staff_attendance_report/staff_monthly_attendance/'.$d_school_id?>" method="post" name="staff_monthly_attendance_form" id="staff_monthly_attendance_form">

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
                              <a data-toggle="collapse" data-parent="#accordion2" href="#collapseThreeTwo">
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

              <form action="<?php echo base_url().'staff_attendance_report/staff_timing_detail/'.$d_school_id?>" method="post" name="staff_timing_detail_form" id="staff_timing_detail_form">

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

<!-- ---------- Staff listing collapse start ------------ -->
<div class="panel panel-default">
      <div class="panel-heading">
          <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion1" href="#collapseFour">
              <i class="fa fa-file-text-o" aria-hidden="true"></i>
              <?php echo get_phrase('staff_listing_report');?>
              </a>
          </h4>
      </div>
      <div id="collapseFour" class="panel-collapse collapse">
          <div class="panel-body">
              <div class="panel-group" id="accordion2">
                  <div class="panel panel-default">
                      <div class="panel-heading">
                          <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion2" href="#collapseFourOne">
                              <i class="fa fa-plus-square" aria-hidden="true"></i>
                              <?php echo get_phrase('staff_listing');?>
                              </a>
                          </h4>
                          <a class="full_listing" href="<?php echo base_url().'staff_attendance_report/staff_listing/'.$d_school_id;?>">
                          <i class="fa fa-table" aria-hidden="true"></i>
                         <?php echo get_phrase('click_here_for_details');?></a>
                      </div>
                      <div id="collapseFourOne" class="panel-collapse collapse in">
                          <div class="panel-body">
                     <!--   1st row   -->
          <div class="row">
          <div class="col-md-6">
            <div class="panel panel-default">
            <div class="panel-heading">
            <div class="panel-title pull-left"><?php echo get_phrase('keyword_search');?></div>
            </div>
            <div class="panel-body">

              <form action="<?php echo base_url().'staff_attendance_report/staff_listing/'.$d_school_id;?>" method="post" name="staff_listing_form" id="staff_listing_form">

                  <div class="col-md-8">
                  <label><?php echo get_phrase('keyword_search');?></label>
                  <input type="text" name="staff_search" class="form-control" value="" placeholder="Keyword">
                  </div>
                  <div id="d3"></div>
                  <div class="col-md-4 top">
                  <input style="float: right;" type="submit" id="staff_listing_submit" value="Get Report" class="btn btn-primary">
                  </div>
              </form>

            </div>
            </div>
          </div>
           <div class="col-md-6">
            <div class="panel panel-default">
            <div class="panel-heading">
            <div class="panel-title pull-left"><?php echo get_phrase('designation');?></div>
            </div>
            <div class="panel-body">

              <form action="<?php echo base_url().'staff_attendance_report/staff_listing/'.$d_school_id;?>" method="post" name="staff_listing_form" id="staff_listing_form">

                  <div class="col-md-8">
                  <label><?php echo get_phrase('select_designation');?></label>
                  <select id="designation_id" class="selectpicker form-control" name="designation_id">
                  <option><?php echo get_phrase('select_designation');?></option>
                  <?php echo designation_list_h(0,0,0,$d_school_id);?>
                  </select>
                  </div>
                  <div id="d3"></div>
                  <div class="col-md-4 top">
                  <input style="float: right;" type="submit" id="staff_listing_submit" value="Get Report" class="btn btn-primary">
                  </div>
              </form>

            </div>
            </div>
          </div>
          </div>
          </br>
          <!--   2nd row   -->
          
           <div class="row">
          <div class="col-md-6">
            <div class="panel panel-default">
            <div class="panel-heading">
            <div class="panel-title pull-left"><?php echo get_phrase('staff_type');?></div>
            </div>
            <div class="panel-body">

              <form action="<?php echo base_url().'staff_attendance_report/staff_listing/'.$d_school_id;?>" method="post" name="staff_listing_form" id="staff_listing_form">

                  <div class="col-md-8">
                  <label><?php echo get_phrase('select_staff_type');?></label>
                  <select id="staff_type" class="selectpicker form-control" name="staff_type">
                     <?php echo get_staff_type_h();?>
                  </select>
                  </div>
                  <div id="d3"></div>
                  <div class="col-md-4 top">
                  <input style="float: right;" type="submit" id="staff_listing_submit" value="Get Report" class="btn btn-primary">
                  </div>
              </form>

            </div>
            </div>
          </div>
           <div class="col-md-6">
            <div class="panel panel-default">
            <div class="panel-heading">
            <div class="panel-title pull-left"><?php echo get_phrase('staff');?></div>
            </div>
            <div class="panel-body">

              <form action="<?php echo base_url().'staff_attendance_report/staff_listing/'.$d_school_id;?>" method="post" name="staff_listing_form" id="staff_listing_form">

                  <div class="col-md-8">
                  <label><?php echo get_phrase('select_staff');?></label>
                  <select id="staff_id" class="selectpicker form-control" name="staff_id">
                  <?php echo staff_list(0,$staff_id,0,0,$d_school_id); ?>
                  </select>
                  </div>
                  <div id="d3"></div>
                  <div class="col-md-4 top">
                  <input style="float: right;" type="submit" id="staff_listing_form_submit" value="Get Report" class="btn btn-primary">
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

<!-- ---------- Admission Report collapse start ------------ -->
<div class="panel panel-default">
      <div class="panel-heading">
          <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion1" href="#collapseFive">
              <i class="fa fa-file-text-o" aria-hidden="true"></i>
              <?php echo get_phrase('students_admission_report');?>
              </a>
          </h4>
      </div>
      <div id="collapseFive" class="panel-collapse collapse">
          <div class="panel-body">
              <div class="panel-group" id="accordion2">
                  <div class="panel panel-default">
                      <div class="panel-heading">
                          <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion2" href="#collapseFourOne">
                              <i class="fa fa-plus-square" aria-hidden="true"></i>
                              <?php echo get_phrase('students_admission_report');?>
                              </a>
                          </h4>
                          <a class="full_listing" href="<?php echo base_url().'staff_attendance_report/staff_listing/'.$d_school_id;?>">
                          <i class="fa fa-table" aria-hidden="true"></i>
                         <?php echo get_phrase('click_here_for_details');?></a>
                      </div>
                      <div id="collapseFourOne" class="panel-collapse collapse in">
                          <div class="panel-body">
                          <!--   2nd row   -->
                           <div class="row">
                                <div class="col-md-6">
                                    <form action="<?php echo base_url().'reports/section_wise_students_list/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                                      <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <div class="panel-title pull-left"><?php echo get_phrase('sectionwise');?></div>
                                        </div>
                                      <div class="panel-body">
                                          <div class="col-md-8">
                                            <label><?php echo get_phrase('select_section');?></label>
                                                <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                                                  <?php
                                                  echo section_list('',$d_school_id);
                                                  ?>
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
                                    <form action="<?php echo base_url().'reports/section_wise_students_list/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                                      <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <div class="panel-title pull-left"><?php echo get_phrase('department_wise');?></div>
                                        </div>
                                      <div class="panel-body">
                                          <div class="col-md-8">
                                                <label><?php echo get_phrase('select_department');?></label>
                                                <select id="department_id_filter" class="selectpicker form-control" name="department">
                                                <?php echo department_list('',$d_school_id); ?>
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
                                    <form action="<?php echo base_url().'reports/section_wise_students_list/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                                      <div class="panel panel-default">
                                        <div class="panel-heading">
                                          <div class="panel-title pull-left"><?php echo get_phrase('academic_year_wise');?></div>
                                        </div>
                                      <div class="panel-body">
                                          <div class="col-md-8">
                                                <label><?php echo get_phrase('select_academic_year');?></label>
                                                <select id="department_id_filter" class="selectpicker form-control" name="academic_year">
                                                <?php echo academic_year_option_list('',$d_school_id); ?>
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
                            
                                    <form action="<?php echo base_url().'reports/section_wise_students_list/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                            
                                    <div class="col-md-12">
                                      <label><?php echo get_phrase('select_class');?></label>
                                      <select id="class_id_filter" class="selectpicker form-control" name="class">
                                        <?php echo class_list('',$d_school_id); ?>
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

<!-- ---------- Staff listing collapse end ------------ -->

<script>
  $(document).ready(function()
  {
      
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
      
      
        $('#subjectwiseattendancebtn').click(function(){
                var isvalid = true;
                var errors = '';
                if ( $('#section_id_subjectwise').val() == "" ){
                      isvalid = false;
                      errors += 'Please select the section <br>';
                }
              
                if ( $('#start_date_subjectwise').val() != "" &&  $('#end_date_subjectwise').val() != "" ){
                      
                    if ( $('#start_date_subjectwise').val()  >  $('#end_date_subjectwise').val() ){
                        isvalid = false;
                        errors += 'Start Date can not be greater than the End Date <br>';
                    }
                      
                }
                
                if(isvalid){
                  $('#subjectwiseattendancemsgspan').html('');    
                  return true;
                }
                else
                {
                  $('#subjectwiseattendancemsgspan').html(errors);    
                  return false;
                }
              
        });
      
      

    $('i').click(function(){
        $(this).toggleClass('fa-plus-square fa-minus-square');
      });

   var current_date = get_current_date();
   var first_day = get_month_first_date();

   //unpaid students section and date functions
   var startdateval = $('#startdate').val();
   var enddateval = $('#enddate').val();

   if(startdateval==""){
       $('#startdate').val(first_day);
   }
   if (enddateval=="") {
        $('#enddate').val(current_date);
   }

  $("#stud_sec_id_filter").change(function() {
      var section_id = $(this).val();
      var div_id = $('#student_select').attr('id');
      var d_school_id = "<?php echo $d_school_id; ?>";
      get_student_list(section_id,div_id,d_school_id);
  });

  $('#date_filter').change(function(){
     var duration = $(this).val();
     var startdate = $('#startdate').attr('id');
     var enddate = $('#enddate').attr('id');
     get_durations(duration,startdate,enddate);
  });

  //paid students section and date functions
  var paidstartdateval = $('#paidstartdate').val();
  var paidenddateval = $('#paidenddate').val();
   
   if (paidstartdateval==""){
       $('#paidstartdate').val(first_day);
    }
   if (paidenddateval=="") {
        $('#paidenddate').val(current_date);
    }
  $("#paid_stud_sec_id_filter").change(function() {
      var section_id = $(this).val();
      var div_id = $('#paid_student_select').attr('id');
      var d_school_id = "<?php echo $d_school_id; ?>";
      get_student_list(section_id,div_id,d_school_id);
  });

  $('#paid_date_filter').change(function(){
     var duration = $(this).val();
     var startdate = $('#paidstartdate').attr('id');
     var enddate = $('#paidenddate').attr('id');
     get_durations(duration,startdate,enddate);
  });

  //students arrears section and date functions
  var arrearsstartdateval = $('#arrearsstartdate').val();
  var arrearsenddateval = $('#arrearsenddate').val();
   
   if (arrearsstartdateval==""){
       $('#arrearsstartdate').val(first_day);
    }
   if (arrearsenddateval=="") {
        $('#arrearsenddate').val(current_date);
    }
  $('#arrears_date_filter').change(function(){
     var duration = $(this).val();
     var startdate = $('#arrearsstartdate').attr('id');
     var enddate = $('#arrearsenddate').attr('id');
     get_durations(duration,startdate,enddate);
  });

  $("#arrears_stud_sec_id_filter").change(function() {
      var section_id = $(this).val();
      var div_id = $('#arrears_student_select').attr('id');
      var d_school_id =  "<?php echo $d_school_id; ?>";
      get_student_list(section_id,div_id,d_school_id);
  });

    
  //class wise paid date functions
  var class_wisestartdateval = $('#class_wisestartdate').val();
  var class_wiseenddateval = $('#class_wiseenddate').val();
   
   if (class_wisestartdateval==""){
       $('#class_wisestartdate').val(first_day);
    }
   if (class_wiseenddateval=="") {
        $('#class_wiseenddate').val(current_date);
    }
  $('#class_wise_date_filter').change(function(){
     var duration = $(this).val();
     var startdate = $('#class_wisestartdate').attr('id');
     var enddate = $('#class_wiseenddate').attr('id');
     get_durations(duration,startdate,enddate);
  });

  //_______________attendance collapse start____________

  //section wise attendance functions
  var select_dateval = $('#select_date').val();
   if (select_dateval==""){
       $('#select_date').val(current_date);
    }
  //______________studnet wise attendance functions_________
    $("#datepicker").datepicker({
            format: "mm/yyyy",
            startView: "months", 
            minViewMode: "months"
    });
    var datepicker_val = $('#datepicker').val();
    if (datepicker_val=="")
    {
      var dt = new Date();
      var m = dt.getMonth()+1; 
      var y = dt.getFullYear();
      $('#datepicker').val(m + "/" + y);
    }

    $('#student_attend_submit').click(function(event){
      var datepicker =  $('#datepicker').val();
      var section_id = $('#student_attend_section_id_filter').val();
      if (datepicker=="" || section_id=="")
      {   event.preventDefault();
          $('#d3').html('select both values');
      }
    });
    //______________studnet wise attendance functions end___

    //______________staff attendance functions______________
    $("#staff_datepicker").datepicker({
            format: "mm/yyyy",
            startView: "months", 
            minViewMode: "months"
    });
    var datepicker_val = $('#staff_datepicker').val();
    if (datepicker_val=="")
    {
      var dt = new Date();
      var m = dt.getMonth()+1; 
      var y = dt.getFullYear();
      $('#staff_datepicker').val(m + "/" + y);
    }
    $("#staff_timing_datepicker").datepicker({
            format: "mm/yyyy",
            startView: "months", 
            minViewMode: "months"
    });
    var datepicker_val = $('#staff_timing_datepicker').val();
    if (datepicker_val=="")
    {
      var dt = new Date();
      var m = dt.getMonth()+1; 
      var y = dt.getFullYear();
      $('#staff_timing_datepicker').val(m + "/" + y);
    }
    
    //______________staff attendance functions end___________

  });
</script>

<script>
  function get_student_list(section_id,div_id,d_school_id)
  {
    div_id = div_id;
    sect = section_id.substring(0,1);
        if (sect=='s') {
            section_id_length = section_id.length;
            section_id = section_id.substring(1,section_id_length);
            $.ajax({
            type: 'POST',
            data: {
                section_id: section_id,
                d_school_id: d_school_id
            },
            url: "<?php echo base_url();?>reports/get_section_student",
            dataType: "html",
            success: function(response) {
                $("#icon").remove();
                if (response != "") {
                    $("#"+div_id).html(response);
                }
                if (response == "") {
                    $("#"+div_id).html('<select><option value=""><?php echo get_phrase('select_student'); ?></option></select>');
                }
            }
        });
      }
  }

  function get_durations(duration,startdate,enddate)
  {
		var today = new Date();
		 var dd = today.getDate();
		 var mm = today.getMonth()+1; //January is 0!
		 var yyyy = today.getFullYear();
     if(dd<10)
      {
          dd='0'+dd;
      } 
      if(mm<10)
      {
          mm='0'+mm;
      }
		 var current_date = dd+'/'+mm+'/'+yyyy;

    if (duration=='one_month')
	   {
		 var today = new Date();
		 var previous_date = new Date().setDate(today.getDate()-30);
		 var prevoius_month_date = new Date(previous_date);
		 var pdd = prevoius_month_date.getDate();
		 var pmm = prevoius_month_date.getMonth()+1; //January is 0!
		 var pyyyy = prevoius_month_date.getFullYear();

    if(pdd<10)
    {
        pdd='0'+pdd;
    } 
    if(pmm<10)
    {
        pmm='0'+pmm;
    }
		 
		 prevoius_month_date = pdd+'/'+pmm+'/'+pyyyy;

		 $("#"+startdate).val(prevoius_month_date);
		 $('#'+enddate).val(current_date);
		 
	   }
    if (duration=='two_months')
       {
         var today = new Date();
         var previous_date = new Date().setDate(today.getDate()-60);
         var prevoius_month_date = new Date(previous_date);
         var pdd = prevoius_month_date.getDate();
         var pmm = prevoius_month_date.getMonth()+1; //January is 0!
         var pyyyy = prevoius_month_date.getFullYear();

         if(pdd<10)
          {
              pdd='0'+pdd;
          } 
          if(pmm<10)
          {
              pmm='0'+pmm;
          }

         prevoius_month_date = pdd+'/'+pmm+'/'+pyyyy;

         $("#"+startdate).val(prevoius_month_date);
         $('#'+enddate).val(current_date);
       }
       if (duration=='six_months')
       {
         var today = new Date();
         var previous_date = new Date().setDate(today.getDate()-180);
         var prevoius_month_date = new Date(previous_date);
         var pdd = prevoius_month_date.getDate();
         var pmm = prevoius_month_date.getMonth()+1; //January is 0!
         var pyyyy = prevoius_month_date.getFullYear();

         if(pdd<10)
          {
              pdd='0'+pdd;
          } 
          if(pmm<10)
          {
              pmm='0'+pmm;
          }

         prevoius_month_date = pdd+'/'+pmm+'/'+pyyyy;

         $("#"+startdate).val(prevoius_month_date);
         $('#'+enddate).val(current_date);

       }
  }
</script>

<script>
    //----current date----
   function get_current_date()
   {
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth()+1; //January is 0!
      var yyyy = today.getFullYear();
      if(dd<10)
      {
          dd='0'+dd;
      } 
      if(mm<10)
      {
          mm='0'+mm;
      } 
      var current_date = dd+'/'+mm+'/'+yyyy;

      return current_date;
   }
    
    //first day of the current month
   function get_month_first_date()
   {
    var today = new Date();
      var mm = today.getMonth()+1; //January is 0!
      var yyyy = today.getFullYear();
      var current_month = mm+" "+yyyy;
      var arrMonth = current_month.split(" ");
      var first = new Date(arrMonth[0] + " 1 " + arrMonth[1]);
      var first_day = moment(first).format("DD/MM/YYYY");
      return first_day;
   }
</script>
</div>