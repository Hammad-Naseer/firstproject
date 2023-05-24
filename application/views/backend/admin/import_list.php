<style>
    .counter{width:40px;height:40px;display:block;position:relative;top:-42px;left:-20px;box-shadow:4px 3px 0 4px #00c0ef;color:#fff;background:#222d32;border-radius:20px;text-align:center;line-height:1.4;font-size:25px;font-weight:700}.m-t{margin-top:-20px}.count_records{position:relative;top:15px;font-weight:700;font-size:18px;font-family:Circular-Loom;color:#ff5722}
    
    .divBlur{
        opacity: 0.2;
        cursor: no-drop;
        transition:all 0.50s ease-in-out;
    }

    .divBlurButton{
        pointer-events: none;
        cursor: not-allowed;
        opacity: 0.65;
        filter: alpha(opacity=65);
        -webkit-box-shadow: none;
        box-shadow: none;
    }
    
    
    .hideMsg {
      display: none;
      transition:all 0.50s ease-in-out;
    }
        
    .divBlur:hover + .hideMsg {
        display: block;
        color: red;
        position: relative;
        top: -260px;
        left: 70px;
        font-weight: 700;
        transition:all 0.50s ease-in-out;
    }
    
    .download_file {
        font-weight: 700;
        color: white;
        background: #2196f3;
        padding: 6px 10px 6px 10px;
        cursor: pointer;
        transition:all 0.50s;
    }
    
    .download_file:hover{
        background: blue;
        color: white !important;
    }
    
    input.form-control {
        padding: 2px !important;
    }
    
    .box.box-info{
        margin-bottom: 42px;
        border: 1px solid #cccccc87;
        box-shadow: 1px 1px 2px 1px #cccc;
    }

</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Import Excel Files
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                
                <?php
                
                    if($this->session->flashdata('success_message'))
                    {
                       echo '<div align="center">
                         <div class="alert alert-success alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          '.$this->session->flashdata('success_message').'
                         </div> 
                        </div>';
                    }
                    
                    if($this->session->flashdata('error_message'))
                    {
                       echo '<div align="center">
                         <div class="alert alert-danger alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          '.$this->session->flashdata('error_message').'
                         </div> 
                        </div>';
                    }
                    
                    $acad_year        =  check_import_status_bit("acad_year");
                    $acad_terms       =  check_import_status_bit("acad_terms");
                    $department       =  check_import_status_bit("department");
                    $class            =  check_import_status_bit("class");
                    $class_section    =  check_import_status_bit("class_section");
                    $subject_category =  check_import_status_bit("subject_category");
                    $subjects         =  check_import_status_bit("subjects");
                    $student_category =  check_import_status_bit("student_category");
                    $student          =  check_import_status_bit("student");
                    $designation      =  check_import_status_bit("designation");
                    $staff            =  check_import_status_bit("staff");
                
                ?>
                <?php //echo ($acad_terms == 1 && $acad_year == 1) ? "divBlur" : ""  ?>
                
                <?php
                    $acad_term_class = "";
                    $department_class = "";
                    $classes_class = "";
                    $class_section_class = "";
                    $subject_category_class = "";
                    $subjects_class = "";
                    $student_category_class = "";
                    $student_class = "";
                    $designation_class = "";
                    $staff_class = "";
                    
                    if($acad_year == 1 && $acad_terms == 0)
                    {
                        $acad_term_class = "";
                        $BlurMsg = "Template Uploaded Successfully";
                    }else{
                        $acad_term_class = "divBlur";
                        //$BlurMsg = "Please upload the above sheets then you have permission this area";
                    }
                    
                    if($acad_year == 1 && $acad_terms == 1 && $department == 0)
                    {
                        $department_class = "";
                    }else{
                        $department_class = "divBlur";
                        //$BlurMsg = "Please upload the above sheets then you have permission this area";
                    }
                    
                    if($acad_year == 1 && $acad_terms == 1 && $department == 1 && $class == 0)
                    {
                        $classes_class = "";
                    }else{
                        $classes_class = "divBlur";
                        //$BlurMsg = "Please upload the above sheets then you have permission this area";
                    }
                    
                    if($acad_year == 1 && $acad_terms == 1 && $department == 1 && $class == 1 && $class_section == 0)
                    {
                        $class_section_class = "";
                    }else{
                        $class_section_class = "divBlur";
                        //$BlurMsg = "Please upload the above sheets then you have permission this area";
                    }
                    
                    if($acad_year == 1 && $acad_terms == 1 && $department == 1 && $class == 1 && $class_section == 1 && $subject_category == 0)
                    {
                        $subject_category_class = "";
                    }else{
                        $subject_category_class = "divBlur";
                        //$BlurMsg = "Please upload the above sheets then you have permission this area";
                    }
                    
                    if($acad_year == 1 && $acad_terms == 1 && $department == 1 && $class == 1 && $class_section == 1 && $subject_category == 1 && $subjects == 0)
                    {
                        $subjects_class = "";
                    }else{
                        $subjects_class = "divBlur";
                        //$BlurMsg = "Please upload the above sheets then you have permission this area";
                    }
                    
                    if($acad_year == 1 && $acad_terms == 1 && $department == 1 && $class == 1 && $class_section == 1 && $subject_category == 1 && $subjects == 1 && $student_category == 0)
                    {
                        $student_category_class = "";
                    }else{
                        $student_category_class = "divBlur";
                        //$BlurMsg = "Please upload the above sheets then you have permission this area";
                    }
                    
                    if($acad_year == 1 && $acad_terms == 1 && $department == 1 && $class == 1 && $class_section == 1 && $subject_category == 1 && $subjects == 1 && $student_category == 1 && $student == 0)
                    {
                        $student_class = "";
                    }else{
                        $student_class = "divBlur";
                        //$BlurMsg = "Please upload the above sheets then you have permission this area";
                    }
                    
                    if($acad_year == 1 && $acad_terms == 1 && $department == 1 && $class == 1 && $class_section == 1 && $subject_category == 1 && $subjects == 1 && $student_category == 1 && $student == 1 && $designation == 0)
                    {
                        $designation_class = "";
                    }else{
                        $designation_class = "divBlur";
                        //$BlurMsg = "Please upload the above sheets then you have permission this area";
                    }
                    
                    if($acad_year == 1 && $acad_terms == 1 && $department == 1 && $class == 1 && $class_section == 1 && $subject_category == 1 && $subjects == 1 && $student_category == 1 && $student == 1 && $designation == 1 && $staff == 0)
                    {
                        $staff_class = "";
                    }else{
                        $staff_class = "divBlur";
                        //$BlurMsg = "Please upload the above sheets then you have permission this area";
                    }
                ?>
                
                <!--Import Academic Year-->
                <div class="box box-info <?php echo ($acad_year == 1) ? "divBlur" : ""  ?>">
                    <div class="box-header "></div>
                    <div class="box-body" style="height: 200px !important;">
                        <span class="counter">1</span>
                        <h3 class="m-t">Import Excel File Academic Year Details</h3>
                        <span class="pull-right">
                            <a href="<?=base_url()?>assets/excel_templates/Academic Year.xlsx" class="download_file" download="">Download Academic Year Template</a>
                        </span>
                        <form action="<?php echo base_url(); ?>sheets/save_acadamic_year" method="POST" enctype="multipart/form-data">
                            <div class="form-grup">
                                <label>Select Excel File</label>
                                <input type="file" name="excel" class="form-control" accept=".xls,.xlsx" required />
                                <span id="excel_span" style="color:red;"></span>
                            </div>
                            <div class="form-group" style="position:relative;top:20px;">
                                <span class="pull-left count_records">
                                    Total Academic Year Records: <?= get_school_academic_year_count(); ?>
                                </span>
                                <button type="submit" name="import" class="modal_save_btn pull-right">Import Academic Year</button>
                            </div>
                       </form>
                    </div>
                </div>
                <div class="hideMsg"></div> <!-- <?= $BlurMsg ?>   -->
                <!--Import Academic Term-->
                <div class="box box-info <?=$acad_term_class?>">
                    <div class="box-header "></div>
                    <div class="box-body" style="height: 200px !important;">
                        <span class="counter">2</span>
                        <h3 class="m-t">Import Excel File Academic Term Details</h3>
                        <span class="pull-right">
                            <a href="<?=base_url()?>assets/excel_templates/Academic Term.xlsx" class="download_file" download="">Download Academic Term Template</a>
                        </span>
                        <form action="<?php echo base_url(); ?>sheets/save_acadamic_term" method="POST" enctype="multipart/form-data">
                            <div class="form-grup">
                                <label>Select Excel File</label>
                                <input type="file" name="excel" class="form-control" accept=".xls,.xlsx" required />
                                <span id="excel_span" style="color:red;"></span>
                            </div>
                            <div class="form-group" style="position:relative;top:20px;">
                                <span class="pull-left count_records">
                                    Total Academic Term Records: <?= get_school_academic_terms_count(); ?>
                                </span>
                                <button type="submit" name="import" class="modal_save_btn pull-right">Import Academic Term</button>
                            </div>
                       </form>
                    </div>
                </div>
                <div class="hideMsg"></div>
                <!--Import Departments-->
                <div class="box box-info <?=$department_class?>" >
                    <div class="box-header "></div>
                    <div class="box-body" style="height: 200px !important;">
                        <span class="counter">3</span>
                        <h3 class="m-t">Import Excel File Department Details</h3>
                        <span class="pull-right">
                            <a href="<?=base_url()?>assets/excel_templates/Department.xlsx" class="download_file" download="">Download Department Template</a>
                        </span>
                        <form action="<?php echo base_url(); ?>sheets/save_departments" method="POST" enctype="multipart/form-data">
                            <div class="form-grup">
                                <label>Select Excel File</label>
                                <input type="file" name="excel" class="form-control" accept=".xls,.xlsx" required />
                                <span id="excel_span" style="color:red;"></span>
                            </div>
                            <div class="form-group" style="position:relative;top:20px;">
                                <span class="pull-left count_records">
                                    Total Departments Records: <?= get_school_departments_count(); ?>
                                </span>
                                <button type="submit" name="import" class="modal_save_btn pull-right">Import Departments</button>
                            </div>
                       </form>
                    </div>
                </div>
                <div class="hideMsg"></div>
                <!--Import Class-->
                <div class="box box-info <?=$classes_class?>">
                    <div class="box-header "></div>
                    <div class="box-body" style="height: 200px !important;">
                        <span class="counter">4</span>
                        <h3 class="m-t">Import Excel File Class Details</h3>
                        <span class="pull-right">
                            <a href="<?=base_url()?>assets/excel_templates/Classes.xlsx" class="download_file" download="">Download Class Template</a>
                        </span>
                        <form action="<?php echo base_url(); ?>sheets/save_class" method="POST" enctype="multipart/form-data">
                            <div class="form-grup">
                                <label>Select Excel File</label>
                                <input type="file" name="excel" class="form-control" accept=".xls,.xlsx" required />
                                <span id="excel_span" style="color:red;"></span>
                            </div>
                            <div class="form-group" style="position:relative;top:20px;">
                                <span class="pull-left count_records">
                                    Total Classes Records: <?= get_school_classes_count(); ?>
                                </span>
                                <button type="submit" name="import" class="modal_save_btn pull-right">Import Classes</button>
                            </div>
                       </form>
                    </div>
                </div>
                <div class="hideMsg"></div>
                <!--Import Class - Section-->
                <div class="box box-info <?=$class_section_class?>">
                    <div class="box-header "></div>
                    <div class="box-body" style="height: 200px !important;">
                        <span class="counter">5</span>
                        <h3 class="m-t">Import Excel File Class Section Details</h3>
                        <span class="pull-right">
                            <a href="<?=base_url()?>assets/excel_templates/Class Section.xlsx" class="download_file" download="">Download Class Section Template</a>
                        </span>
                        <form action="<?php echo base_url(); ?>sheets/save_class_section" method="POST" enctype="multipart/form-data">
                            <div class="form-grup">
                                <label>Select Excel File</label>
                                <input type="file" name="excel" class="form-control" accept=".xls,.xlsx" required />
                                <span id="excel_span" style="color:red;"></span>
                            </div>
                            <div class="form-group" style="position:relative;top:20px;">
                                <span class="pull-left count_records">
                                    Total Class Section Records: <?= get_school_class_sections_count(); ?>
                                </span>
                                <button type="submit" name="import" class="modal_save_btn pull-right">Import Class-Section</button>
                            </div>
                       </form>
                    </div>
                </div>
                <div class="hideMsg"></div>
                <!--Import Subject Category-->
                <div class="box box-info <?=$subject_category_class?>">
                    <div class="box-header "></div>
                    <div class="box-body" style="height: 200px !important;">
                        <span class="counter">6</span>
                        <h3 class="m-t">Import Excel File Subject Category Details</h3>
                        <span class="pull-right">
                            <a href="<?=base_url()?>assets/excel_templates/Subject Category.xlsx" class="download_file" download="">Download Subject Category Template</a>
                        </span>
                        <form action="<?php echo base_url(); ?>sheets/save_subject_category" method="POST" enctype="multipart/form-data">
                            <div class="form-grup">
                                <label>Select Excel File</label>
                                <input type="file" name="excel" class="form-control" accept=".xls,.xlsx" required />
                                <span id="excel_span" style="color:red;"></span>
                            </div>
                            <div class="form-group" style="position:relative;top:20px;">
                                <span class="pull-left count_records">
                                    Total Subject Category Records: <?= get_school_subject_categories_count(); ?>
                                </span>
                                <button type="submit" name="import" class="modal_save_btn pull-right">Import Subject Category</button>
                            </div>
                       </form>
                    </div>
                </div>
                <div class="hideMsg"></div>
                <!--Import Subject -->
                <div class="box box-info <?=$subjects_class?>">
                    <div class="box-header "></div>
                    <div class="box-body" style="height: 200px !important;">
                        <span class="counter">7</span>
                        <h3 class="m-t">Import Excel File Subjects Details</h3>
                        <span class="pull-right">
                            <a href="<?=base_url()?>assets/excel_templates/Subjects.xlsx" class="download_file" download="">Download Subject Template</a>
                        </span>
                        <form action="<?php echo base_url(); ?>sheets/save_subject" method="POST" enctype="multipart/form-data">
                            <div class="form-grup">
                                <label>Select Excel File</label>
                                <input type="file" name="excel" class="form-control" accept=".xls,.xlsx" required />
                                <span id="excel_span" style="color:red;"></span>
                            </div>
                            <div class="form-group" style="position:relative;top:20px;">
                                <span class="pull-left count_records">
                                    Total Subject Records: <?= get_school_subjects_count(); ?>
                                </span>
                                <button type="submit" name="import" class="modal_save_btn pull-right">Import Subjects</button>
                            </div>
                       </form>
                    </div>
                </div>
                <div class="hideMsg"></div>
                <!--Import Student Category-->
                <div class="box box-info <?=$student_category_class?>">
                    <div class="box-header "></div>
                    <div class="box-body" style="height: 200px !important;">
                        <span class="counter">9</span>
                        <h3 class="m-t">Import Excel File Student Category Details</h3>
                        <span class="pull-right">
                            <a href="<?=base_url()?>assets/excel_templates/Student Category.xlsx" class="download_file" download="">Download Student Category Template</a>
                        </span>
                        <form action="<?php echo base_url(); ?>sheets/save_student_category" method="POST" enctype="multipart/form-data">
                            <div class="form-grup">
                                <label>Select Excel File</label>
                                <input type="file" name="excel" class="form-control" accept=".xls,.xlsx" required />
                                <span id="excel_span" style="color:red;"></span>
                            </div>
                            <div class="form-group" style="position:relative;top:20px;">
                                <span class="pull-left count_records">
                                    Total Student Category Records: <?= get_school_student_categories_count(); ?>
                                </span>
                                <button type="submit" name="import" class="modal_save_btn pull-right">Import Student Category</button>
                            </div>
                       </form>
                    </div>
                </div>
                <div class="hideMsg"></div>
                <!--Import Students-->
                <div class="box box-info <?=$student_class?>">
                    <div class="box-header "></div>
                    <div class="box-body" style="height: 200px !important;">
                        <span class="counter">10</span>
                        <h3 class="m-t">Import Excel File Student Details</h3>
                        <span class="pull-right">
                            <a href="<?=base_url()?>assets/excel_templates/Student Details.xlsx" class="download_file" download="">Download Student Template</a>
                        </span>
                        <form  action="<?php echo base_url(); ?>sheets/save_imported_students" method="POST" enctype="multipart/form-data">
                            <div class="form-grup">
                                <label>Select Excel File</label>
                                <input type="file" name="excel" class="form-control" accept=".xls,.xlsx" required />
                                <span id="excel_span" style="color:red;"></span>
                            </div>
                            <div class="form-group" style="position:relative;top:20px;">
                                <span class="pull-left count_records">
                                    Total Student Records: <?= get_school_students_count(); ?>
                                </span>
                                <button type="submit" name="import" class="modal_save_btn pull-right">Import Students</button>
                            </div>
                       </form>
                    </div>
                </div>
                <div class="hideMsg"></div>
                <!-- Import Designation -->
                <div class="box box-info <?=$designation_class?>">
                    <div class="box-header "></div>
                    <div class="box-body" style="height: 200px !important;">
                        <span class="counter">11</span>
                        <h3 class="m-t">Import Excel File Designation Details</h3>
                        <span class="pull-right">
                            <a href="<?=base_url()?>assets/excel_templates/Designation.xlsx" class="download_file" download="">Download Designation Template</a>
                        </span>
                        <form  action="<?php echo base_url(); ?>sheets/save_designation" method="POST" enctype="multipart/form-data">
                            <div class="form-grup">
                                <label>Select Excel File</label>
                                <input type="file" name="excel" class="form-control" accept=".xls,.xlsx" required />
                                <span id="excel_span" style="color:red;"></span>
                            </div>
                            <div class="form-group" style="position:relative;top:20px;">
                                <span class="pull-left count_records">
                                    Total Designation Records: <?= get_school_designation_count(); ?>
                                </span>
                                <button type="submit" name="import" class="modal_save_btn pull-right">Import Designation</button>
                            </div>
                       </form>
                    </div>
                </div>
                <div class="hideMsg"></div>
                <!-- Import Staff -->
                <div class="box box-info <?=$staff_class?>">
                    <div class="box-header "></div>
                    <div class="box-body" style="height: 200px !important;">
                        <span class="counter">12</span>
                        <h3 class="m-t">Import Excel File Staff Details</h3>
                        <span class="pull-right">
                            <a href="<?=base_url()?>assets/excel_templates/Staff details.xlsx" class="download_file" download="">Download Staff Template</a>
                        </span>
                        <form  action="<?php echo base_url(); ?>sheets/save_staff" method="POST" enctype="multipart/form-data">
                            <div class="form-grup">
                                <label>Select Excel File</label>
                                <input type="file" name="excel" class="form-control" accept=".xls,.xlsx" required />
                                <span id="excel_span" style="color:red;"></span>
                            </div>
                            <div class="form-group" style="position:relative;top:20px;">
                                <span class="pull-left count_records">
                                    Total Staff Records: <?= get_school_staff_count(); ?>
                                </span>
                                <button type="submit" name="import" class="modal_save_btn pull-right">Import Staff</button>
                            </div>
                       </form>
                    </div>
                </div>
                <div class="hideMsg"></div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('.divBlur button').addClass('divBlurButton');
        $('.divBlur .download_file').addClass('divBlurButton');
    });
    function validate_file_value(){
        var excel = $('#excel').val();
        var proceed = true;
        if(excel == "")
        {
            $('#excel_span').html('File is required');
            proceed = false;
        }
        return proceed;
    }
    
    
</script>