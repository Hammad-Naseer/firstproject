<style>
    .container_self{width:95%;margin-top:10px}.panel_heading_self{height:30px;padding:5px;font-size:18px}f .panel_self{color:#000}.redEsteric{color:red}
</style>

    <?php
        $this->load->helper('teacher');
        $login_detail_id  = $_SESSION['login_detail_id'];
        $yearly_term_id   = $_SESSION['yearly_term_id'];
        $academic_year_id = $_SESSION['academic_year_id'];
        $section_arr      = get_time_table_teacher_section($login_detail_id, $yearly_term_id);
    ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
            </a>
            <h3 class="system_name inline">
                <?php echo get_phrase('view_assessment_results'); ?>
            </h3>
        </div>
    </div>
    <form method="post" action="<?php echo base_url(); ?>assessment/filter_assessment_results">
        <div class="row filterContainer px-3">
            <div class="col-md-6" data-step="1" data-position='top' data-intro="select class - section">
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
            <div class="col-md-6" data-step="2" data-position='top' data-intro="select subject">
                <div class="form-group">
                    <label>Select Subject</label><span class="redEsteric">*</span>
                    <span style="float:right; color:red;" id="subject_id_span"></span>
                    <select name="subject_id" id="subject_id" class="dcs_list_add form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <option value=""><?php echo get_phrase('select_a_subject');?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-12" data-step="3" data-position='top' data-intro="press this button to get assessment result">
                <div class="form-group">
                    <button type="submit" class="modal_save_btn" onclick="return validate_form()">Filter</button>
                </div>
            </div>    
        </div>
    </form>    

    <?php if( isset($assessments) && count($assessments) > 0 ){?>
    <div class="col-lg-12 col-sm-12">
        <table class="table table-bordered table_export table-responsive">
    <thead>
      <tr>
        <th class="td_middle">Sr.</th>
        <th>Assessment Details</th>
        <th>Question Details</th>
        <th>View Details</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $count = 0;
         foreach($assessments as $row){
        $count++;
      ?>
            <tr>
              <td class="td_middle"><?php echo $count;?></td>
              <td>
                	    <strong>Title :</strong>
                	    <?php echo $row['assessment_title'];?>
                	    <br>
                	    <strong>Yearly Term :</strong>
                	    <?php
                	        $yearly_terms =  get_yearly_terms($_SESSION['academic_year_id'] , $row['yearly_term_id'] );
                	        echo $yearly_terms[0]['title'];
                	    ?>
                	    <br>
                	    <strong>Total Marks :</strong>
                	    <?php echo $row['total_marks'];?> <br>
                	    <?php echo get_assessment_time($row['assessment_id']); ?>
                </td>
                <td>
				    <?php 
				            echo "<strong class='min-space' >MCQ's : </strong><span class='badge badge-info badge-roundless pull-right'>".$row['mcq_questions']."</span><br><br>";
				            
				            echo "<strong class='min-space' >True/False : </strong><span class='badge badge-success badge-roundless pull-right'>".$row['true_false_questions']."</span><br><br>";
				            
				            echo "<strong class='min-space' >Blanks : </strong><span class='badge badge-info badge-roundless pull-right'>".$row['fill_in_the_blanks_questions']."</span><br><br>";
				            
				            echo "<strong class='min-space' >Short Q's : </strong><span class='badge badge-success badge-roundless pull-right'>".$row['short_questions']."</span><br><br>";
				            
				            echo "<strong class='min-space' >Long Q's </strong><span class='badge badge-info badge-roundless pull-right'>".$row['long_questions']."</span><br><br>";
				            
				            echo "<strong class='min-space' >Pictorial Q's</strong><span class='badge badge-success badge-roundless pull-right'>".$row['pictorial_questions']."</span><br><br>";
				            
				            echo "<strong class='min-space' >Column Matching Q's</strong><span class='badge badge-info badge-roundless pull-right'>".$row['match_questions']."</span><br><br>";
				    ?>
				</td>
                <td class="td_middle">
					<a class="btn btn-primary btn-sm" href="<?php echo base_url()."assessment/view_assessments_submitted/".$row['assessment_id'];?>" target="_blank">View</a> 
				</td>
        	</tr>
      <?php
         }
      ?>

    </tbody>
  </table>
    </div>
    <?php } ?>
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
                url: "<?php echo base_url();?>assessment/get_subject_assessments",
                dataType: "html",
                success: function(response) {
                    var obj = jQuery.parseJSON(response);
                    $("#icon").remove();
                    $("#assessment_id").html(obj.assessment);
    
                }
            });
            
            
        }
        
        
    </script>