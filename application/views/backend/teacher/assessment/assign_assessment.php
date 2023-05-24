    <?php
        $assessment_row = get_assessment_row($assessment_id);
        if($assessment_row == null){  exit; }
            
        $this->load->helper('teacher');
        $login_detail_id  = $_SESSION['login_detail_id'];
        $yearly_term_id   = $_SESSION['yearly_term_id'];
        $academic_year_id = $_SESSION['academic_year_id'];
        $section_arr      = get_time_table_teacher_section($login_detail_id, $yearly_term_id);
    ?>
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline">
                <?php echo get_phrase('assign_assessments');?>
            </h3>   
        </div> 
    </div>
    <div class="assign-assmnt col-lg-12 cols-sm-12">
        
    <div class="panel panel-default panel_self" data-step="1" data-position="left" data-intro="This Assessment has been already assigned to these sections">
        
        <?php
            if( count(get_assigned_assessments_sections($assessment_row->assessment_id)) > 0){
                $result = get_assigned_assessments_sections($assessment_row->assessment_id);
                foreach($result as $row_assigned){
        ?>
         
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $row_assigned['section_id'] ?>">
                                <span class="glyphicon glyphicon-file"></span>
                            This Assessment Is Already Assigned To <b> ( <?php $section_hierarchy =  section_hierarchy($row_assigned['section_id']);
                            echo $section_hierarchy['d']." / ". $section_hierarchy['c']." / ".$section_hierarchy['s'];
                            ?> )</b>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse<?php echo $row_assigned['section_id'] ?>" class="panel-collapse">
                        <div class="panel-body">
    
                            <div style="margin-top:10px;">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Student Name</th>
                                        <th>Roll #</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $section_student = get_assigned_assessment_students($row_assigned['section_id'] , $assessment_id);
                                            foreach($section_student as $student){
                                        ?>
                                              <tr>
                                                   <td><?php echo $student['name'] ?></td>
                                                   <td><?php echo $student['roll'] ?></td>
                                              </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                                
                        </div>
                    </div>
                </div>
            </div> 
        
        <?php
                }
            }
            
        ?>
  
    </div>        
        
        
    <div class="panel panel-default panel_self">
        
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>
    

    <div class="panel-heading">
        <h4 class="panel-title"><?php echo $assessment_row->assessment_title; ?></h4>
    </div>
    <form method="post" action="<?php echo base_url(); ?>assessment/save_assign_assessment" data-step="2" data-position="left" data-intro="Add Details">

            <div class="panel-body">
                
                <div class="form-group col-lg-6 col-sm-6">
                    <label>Assessment Date</label><span class="redEsteric">*</span>
                    <span style="float:right; color:red;" id="assessment_date_span"></span>
                    <?php
                       $currdate = date('m/d/Y');
                    ?>
                    <input type="date" class="form-control" id="assessment_date" name="assessment_date" value="<?php echo $currdate ?>" required/>
                </div>
                
                <div class="form-group col-lg-6 col-sm-6">
                    <label>Start time</label><span class="redEsteric">*</span>
                    <span style="float:right; color:red;" id="start_time_span"></span>
                    <input type="text" class="form-control time" id="start_time" name="start_time" required/>
                </div>
                
                <div class="form-group col-lg-6 col-sm-6">
                    <label>End time</label><span class="redEsteric">*</span>
                    <span style="float:right; color:red;" id="end_time_span"></span>
                    <input type="text" class="form-control time" id="end_time" name="end_time" required/>
                </div>
                
                <div class="form-group col-lg-6 col-sm-6">
                    <label>Select section</label><span class="redEsteric">*</span>
                    <span style="float:right; color:red;" id="section_id_span"></span>
                    <select id="section_id" class="dcs_list_add form-control" name="section_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <?php 
                            echo get_teacher_dep_class_section_list($section_arr);
                        ?>
                    </select>
                </div>
                
                <div class="form-group col-lg-6 col-sm-6">
                    <label>Select Subject</label><span class="redEsteric">*</span>
                    <span style="float:right; color:red;" id="subject_id_span"></span>
                    <select name="subject_id" id="subject_id" class="dcs_list_add form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <option value=""><?php echo get_phrase('Select_a_subject');?></option>
                    </select>
                </div>
                
                <div style="margin-top:10px;">
                    <table class="table table-bordered">
                    <thead>
                        <th><input type="checkbox" id="select-all" name="select-all" >Select All</th>
                        <th>Student Name</th>
                        <th>Roll #</th>
                    </thead>
                    <tbody id="students_list">
                        
                    </tbody>
                </table>
                </div>
                
                <div style="float:right;">
                    <div <?= check_sms_preference(2,"style","sms") ?>>
                        <label>SEND SMS </label>
                        <input type="checkbox" id="send_message" name="send_message">
                    </div>
                    <div <?= check_sms_preference(2,"style","email") ?>>
                        <label>SEND Email </label>
                        <input type="checkbox" id="send_email" name="send_email" >
                    </div>
                    
                    <div style="">
                        <input type="hidden" id="assessment_id" name="assessment_id" value="<?php echo $assessment_id ?>">
                        <button type="submit" id="submitbtn" class="modal_save_btn" onclick="return validate_form()" data-step="3" data-position="left" data-intro="Press this button & Assign Assessment">Assign Assessment</button>
                    </div>
                    
                    <div id="sectionerror" style="display:none">
                        <p class="text-center text-danger">Assessment Is Already Assigned To This Section</p>
                    </div>
                    
                    
                
                </div>

            </div>
            
        </form>    
    
   
    </div>
    </div>
    
    <script>
    
       $(document).ready(function(){
           
            $('.time').timepicker({scrollDefault: 'now'});
            var min = new Date();
            if(min.getMinutes() > 30){
                min.setHours(min.getHours() + 1);
                min.setMinutes(00);
            }
            else{
                min.setMinutes(30);
            }
                
            $('.time').timepicker('setTime', min);
            
            $( ".datepicker" ).datepicker(
                'setStartDate', new Date()
            );
       });
    
        
        $("#subject_id").change(function() 
        {
            var section_id = $('#section_id').val();
            
            $("#icon").remove();
            $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    
            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>assessment/get_section_studens",
                dataType: "html",
                success: function(response) {
                    var obj = JSON.parse(response);
                     $("#icon").remove();
                    var html = "";
                    for(var i = 0; i < obj.length; i++)
                    {
                        html+= "<tr><td><input class='std_checkbox' type='checkbox' name='student_id[]' value='"+obj[i]['student_id']+"'></td><td>"+obj[i]['name']+"</td><td>"+obj[i]['roll']+"</td></tr>"
                    }
                    $('#students_list').html(html);
    
                }
            });
    
        });
        
        $("#section_id").change(function() 
        {
        	$('#item_list').html('');
            var section_id = $(this).val();
    
            $("#icon").remove();
            $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    
            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>teacher/get_section_student_subject",
                dataType: "html",
                success: function(response) {
                    var obj = jQuery.parseJSON(response);
                    $("#icon").remove();
                    $("#subject_id").html(obj.subject);
                    
                    var assessment_id = $('#assessment_id').val();
                    
                    $.ajax({
                        type: 'POST',
                        data: {
                            section_id: section_id ,
                            assessment_id : assessment_id
                        },
                        url: "<?php echo base_url();?>assessment/check_assigned_assessment",
                        dataType: "html",
                        success: function(data) {
                            if(parseInt(data) == 1) {
                                  $('#sectionerror').css('display','block');
                                  $('#submitbtn').attr('disabled' , true);
                            }
                            else
                            {
                                  $('#sectionerror').css('display','none');
                                  $('#submitbtn').attr('disabled' , false);
                            }
            
                        }
                    });
                    
                    
                    
    
                }
            });
    
        });
        
        $('#select-all').click(function(event) {   
            if(this.checked) {
                // Iterate each checkbox
                $('.std_checkbox').each(function() {
                    this.checked = true;                        
                });
            } else {
                $('.std_checkbox').each(function() {
                    this.checked = false;                       
                });
            }
        });
        
        function validate_form()
        {
            
            var proceed  = true;
            
            var assessment_date = $('#assessment_date').val();
            if(assessment_date == ""){
                $('#assessment_date_span').html('Mandatory Filed');
                proceed = false;
            }else{
                $('#assessment_date_span').html('');
            }
            
            var start_time = $('#start_time').val();
            if(start_time == ""){
                $('#start_time_span').html('Mandatory Filed');
                proceed = false;
            }else{
                $('#start_time_span').html('');
            }
            
            var end_time = $('#end_time').val();
            if(end_time == ""){
                $('#end_time_span').html('Mandatory Filed');
                proceed = false;
            }else{
                $('#end_time_span').html('');
            }
            
            var section_id = $('#section_id').val();
            if(section_id == ""){
                $('#section_id_span').html('Mandatory Filed');
                proceed = false;
            }else{
                $('#section_id_span').html('');
            }
            
            var subject_id = $('#subject_id').val();
            if(subject_id == ""){
                $('#subject_id_span').html('Mandatory Filed');
                proceed = false;
            }else{
                $('#subject_id_span').html('');
            }
            
            var boxes = $('.std_checkbox:checkbox');
            if(boxes.length > 0) {
                if( $('.std_checkbox:checkbox:checked').length < 1) {
                    alert('Please select at least one student');
                    boxes[0].focus();
                    proceed =  false;
                }
            }
            
        }
    </script>
