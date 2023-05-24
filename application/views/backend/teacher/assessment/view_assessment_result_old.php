<style>
    .container_self
    {
        width: 95%;
        margin-top: 10px;
    }
    .panel_heading_self
    {
        height: 30px;
        padding: 5px;
        font-size: 18px;
    }
    .panel_self
    {
        color: black;
    }
    .redEsteric
    {
        color:red;
    }
</style>

    <?php
        $this->load->helper('teacher');
        $login_detail_id  = $_SESSION['login_detail_id'];
        $yearly_term_id   = $_SESSION['yearly_term_id'];
        $academic_year_id = $_SESSION['academic_year_id'];
        $section_arr      = get_time_table_teacher_section($login_detail_id, $yearly_term_id);
    ?>

    <div class="container container_self">
        
        
    <div class="panel panel-default panel_self">
        
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    

    <div class="panel-heading panel_heading_self">Filter Assessment Results</div>
    

        <div class="panel-body">
            <form method="post" action="<?php echo base_url(); ?>shoaib/filter_assessment_results">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Select Section</label><span class="redEsteric">*</span>
                        <span style="float:right; color:red;" id="section_id_span"></span>
                        <select id="section_id" class="dcs_list_add form-control" name="section_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <?php 
                                echo get_teacher_dep_class_section_list($section_arr);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Select Subject</label><span class="redEsteric">*</span>
                        <span style="float:right; color:red;" id="subject_id_span"></span>
                        <select name="subject_id" id="subject_id" class="dcs_list_add form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select_a_subject');?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Select Assessment</label><span class="redEsteric">*</span>
                        <span style="float:right; color:red;" id="assessment_id_span"></span>
                        <select name="assessment_id" id="assessment_id" class="dcs_list_add form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select_an_assessment');?></option>
                        </select>
                    </div>
                </div>    
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" onclick="return validate_form()">Filter</button>
                    </div>
                </div>    
            </div>
            </form>    
        </div>
    
    
    </div>
    
    
    <?php
      if( isset($students) && count($students) > 0 ){
    ?>
    <table class="table table-success table-bordered" id="studentsTable">
    <thead>
      <tr>
        <th>Assessment Title</th>
        <th>Subject</th>
        <th>Yearly Term</th>
        <th>Student Info</th>
        <th>Status</th>
        <th>Date Submitted</th>
        <th>Total Marks</th>
        <th>Obtained Marks</th>
        <th>Remarks</th>
      </tr>
    </thead>
    <tbody>
      <?php
         foreach($students as $student){
      ?>
          <tr>
            <td><?php echo $student['assessment_title'] ?></td>
            <td><?php echo $student['subject_name'] ?></td>
            <td><?php echo $student['yearly_term_name'] ?></td>
            <td>
                <?php 
                    echo $student['student_name'] . '<br>' .
                         $student['roll_number'] . '<br>'  .
                         $student['department_name'] . ' | ' . $student['section_name'] . ' | ' . $student['class_name'];
                ?>
            </td>
            <td>
                <?php 
                  echo $student['is_submitted'] == 1 ? 'Submitted' : 'Not Submitted';
                ?>
            </td>
            <td>
                <?php 
                    if($student['is_submitted'] == 1){
						echo date_view($student['result_date']);
					}  
				?>
			</td>
            <td><?php echo $student['total_marks'] ?></td>
            <td><?php echo $student['obtained_marks'] ?></td>
            <td><?php echo $student['remarks'] ?></td>
          </tr>
      <?php
         }
      ?>

    </tbody>
  </table>
    <?php
      }
    ?>
    
    
    
    
    </div>
    
    <script>
    
       $(document).ready(function(){
           
           
            $('#studentsTable').DataTable();
           
            let section_id     =  '<?php echo isset($section_id)    ?  $section_id : '' ?>';
    		let subject_id     =  '<?php echo isset($subject_id)    ?  $subject_id : '' ?>';
    		let assessment_id  =  '<?php echo isset($assessment_id) ?  $assessment_id : '' ?>';
    		
    		if(section_id != ''){
    		      $('#section_id').val(section_id);
    		}
    		if(subject_id != ''){
    		      get_section_subject(section_id);
    		}
    		if(assessment_id != ''){
    		      get_subject_assessments(subject_id);
    		}
    		
    		if(subject_id != '' && assessment_id == ''){
    		      get_subject_assessments(subject_id);
    		}
    		
    		
    		setTimeout(function(){ 
    		  if(subject_id != ''){
    		      $('#subject_id').val(subject_id);
    		  }
    		  if(assessment_id != ''){
    		      $('#assessment_id').val(assessment_id);
    		  } 

    		}, 1000);

       });
    
        
        $("#section_id").change(function() 
        {
        	$('#item_list').html('');
            var section_id = $(this).val();
    
            $("#icon").remove();
            $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    
            get_section_subject(section_id);
    
        });
        
        
        $("#subject_id").change(function() 
        {
            var subject_id = $(this).val();
    
            $("#icon").remove();
            $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    
            get_subject_assessments(subject_id);
    
        });
        
        function validate_form()
        {
            var proceed  = true;

            var section_id = $('#section_id').val();
            if(section_id == ""){
                $('#section_id_span').html('Mandatory Filed');
                proceed = false;
            }else{
                $('#section_id_span').html('');
            }
            
            return proceed;
        }
        
        
        function get_section_subject(section_id){
            
            
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
                }
            });
            
            
        }
        
        
        function get_subject_assessments(subject_id){
            
            
            $.ajax({
                type: 'POST',
                data: {
                    subject_id: subject_id
                },
                url: "<?php echo base_url();?>shoaib/get_subject_assessments",
                dataType: "html",
                success: function(response) {
                    var obj = jQuery.parseJSON(response);
                    $("#icon").remove();
                    $("#assessment_id").html(obj.assessment);
    
                }
            });
            
            
        }
        
        
    </script>