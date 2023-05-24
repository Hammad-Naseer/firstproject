<?php 
    $student_id = base64_decode($this->uri->segment(3));
?>

    <style>
        .panel-default>.panel-body{min-height:650px; !important;}
        .panel-default>.panel-heading{padding:10px;color:#ffffff !important;}
        .red{color:red;font-weight:700}.orange{color:#319cff;font-weight:700}.green{color:#208e1a;font-weight:700}
        .mytt{font-size:15px;color:#0a73b7;font-weight:700}.due{color:#972d2d}.mygrey{color:#a6a6a6}.dataTables_wrapper{box-shadow:0 0 10px 1px #cccccc94;background:#fff!important}
        .table-info{background:#fff;padding:20px;margin:0 0}@media only screen and (max-width:460px){table#DataTables_Table_0{width:90%!important}}
        #student_subject_holder li.list-group-item span {color: #1a7866; }#student_subject_holder li.list-group-item {font-weight: 600; color: #000;}.diary-search span {font-weight: 500;color: #000;line-height: 2;}
     .diary-tree li{list-style-type:none;margin:0;padding:10px 5px 0 5px;position:relative}.diary-tree li::after,.diary-tree li::before{content:'';left:-20px;position:absolute;right:auto}
     .diary-tree li::before{bottom:50px;height:100%;top:0;width:1px}.diary-tree li::after{height:20px;top:25px;width:25px}
     .diary-tree li span{-webkit-border-radius:5px;border-radius:2px;color: white;display:inline-block;padding:6px 8px;text-decoration:none;cursor:pointer;width:100%;}.diary-tree>ul>li::after,.diary-tree>ul>li::before{border:0}
     .diary-tree li:last-child::before{height:27px}.diary-tree li span:hover{color:white !important;}.diary-tree li span a{font-size:16px;color:#fff!important}.diary-tree [aria-expanded=false]>.expanded,.diary-tree [aria-expanded=true]>
     .collapsed{display:none}.diary-tree [data-toggle=collapse]:after{margin-top:4px}.diary-tree{margin-left:-44px}.diary-tree i{font-size:18px!important}.row.dairy-row-set{min-height:562px}
     .springss{position:absolute;display:flex;left:24.7%;flex-direction:column;z-index:99}.spring1{margin-top:5px}
     .diary-tree li span {.diary-ul>li span{color:#000!important}.diary-ul>li i{color:#000!important}.dairy-row-set td,.dairy-row-set th{font-size:16px;padding:14px 6px!important}.diary-tree li span:hover a{color:#fff!important}.diary-tree li span:hover i{color:#fff!important}}    
    </style>

    <div class="row">
  		<div class="col-sm-3 p-3">
  		    <div class="accordion" id="accordionExample">
              <div class="text-center">
                <?php 
                    $link = display_link($data->image,'profile_pic',1);
                    if (file_exists($link)) {
                        echo '<img rc="<?php echo  $link;?>" class="avatar img-circle img-thumbnail" alt="avatar" width="150" >';
                    }else{
                        echo '<img class="avatar img-circle img-thumbnail" alt="avatar" src="'. get_default_pic().'" width="150">';
                    }
                ?>
                <br>
                <br>
                <!--<h6>Upload a different photo...</h6>-->
                <!--<input type="file" class="text-center center-block file-upload">-->
                <?php 
                    $barcode = system_path($data->barcode_image,'student');
                    if (file_exists($barcode)) {
                        $path = base_url().$barcode;
                       echo '<img src="'.$path.'" alt="barcode" width="150" >';
                    }else{
                        echo "No Barcode found";
                    }
                ?>
                <br>
                <br>
                <span class="badge badge-info"><?php echo student_status($data->student_status); ?></span>
                <?php
                    $std_data = get_student_attendance_details($student_id, date('Y-m-d'));
                  
                    $attendance_id = $std_data->attendance_id;
                  
                    $check_in = $std_data->check_in;
                    $check_out = $std_data->check_out;
                    $not_marked ="Not Marked";
                    $leave ="Leave";
                    $absent ="Absent";
                ?>
                  <?php  
                   if($std_data->status == 3){
                        echo "<span class='badge badge-warning text-dark'>" .$leave."</span>";
                     }
                    else if($std_data->status == 2){
                     echo "<span class='badge badge-primary'>" .$absent."</span>";
                    }
                    else if($std_data->status == 1){
                           if(empty($check_in) && empty($check_out)){
                              echo "<strong>".get_phrase("check_in")."</strong>:"?> <?php echo '00:00:00';
                              echo '<br>';echo "<strong>".get_phrase("check_out")."</strong>:"?> <?php echo '00:00:00';
                           }
                           else if(!empty($check_in) && empty($check_out)){
                               echo "<strong>".get_phrase("check_in")."</strong>:".$check_in;
                               echo "<br><br>";
                               echo "<input type='hidden' id='attendance_id' value='$attendance_id' />";
                               ?>
                               <button  class='btn btn-danger' type='button' onclick='mark_check_out()' >Check Out</button>
                               <?php
                            }
                           else if(!empty($check_in) && !empty($check_out)){
                                 echo "<strong>".get_phrase("check_in")."</strong>:".$check_in;
                                  echo "<br>";
                                  echo "<strong>".get_phrase("check_out")."</strong>:".$check_out;
                               echo "<br><br>";
                               ?>
                                
                               <?php
                            }
                    }
                 else{
                       echo "<span class='badge badge-danger'>" .$not_marked."</span>";
                   }
               ?>
              </div>
              <br>
              <div class="panel panel-default">
                <div class="panel-heading" id="headingOne" data-toggle="collapse" data-target="#basic_info" aria-expanded="true" aria-controls="basic_info" ><i class="fas fa-user fa-1x"></i>BASIC INFO</div>
                
                <div id="basic_info" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="panel-body">
                    <table class="table">
                            <tr><td>Name : <?php echo $data->name;?></td></tr>
                            <tr><td>Class/Section : <?php $section_hierarchy =  section_hierarchy($data->section_id); echo $section_hierarchy['d']." / ".$section_hierarchy['c']." / ".$section_hierarchy['s']; ?></td></tr>
                            <tr><td>Roll# : <?php echo $data->roll;?></td></tr>
                            <tr><td>ID# : <?php echo $data->id_no;?></td></tr>
                            <tr><td>Gender : <?php echo $data->gender;?></td></tr>
                            <tr><td>Religion : <?php echo religion($data->religion);?></td></tr>
                            <tr><td>Birthday : <?php echo $data->birthday;?></td></tr>
                            <tr><td>Blood Gr : <?php echo $data->bd_group;?></td></tr>
                            
                    </table>
                </div>
                </div>
              </div>
              
              <div class="panel panel-default">
                <div class="panel-heading" id="headingTwo" data-toggle="collapse" data-target="#contact_info" aria-expanded="true" aria-controls="contact_info"><i class="fas fa-phone fa-1x"></i>CONTACT INFO</div>
                
                <div id="contact_info" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                <div class="panel-body">
                    <table class="table">
                            <tr><td>Address : <?php echo $data->address;?></td></tr>
                            <tr><td>Phone# : <?php echo $data->phone;?></td></tr>
                            <tr><td>Email : <?php echo $data->email;?></td></tr>
                            <tr><td>Mobile# : <?php echo $data->mob_num;?></td></tr>
                            <tr><td>Emg# : <?php echo $data->emg_num;?></td></tr>
                    </table>
                </div>
                </div>
              </div>
              
              <div class="panel panel-default">
                <div class="panel-heading" id="headingThree" data-toggle="collapse" data-target="#parent_info" aria-expanded="true" aria-controls="parent_info"><i class="fas fa-user fa-1x"></i>PARENTAL INFO</div>
                
                <div id="parent_info" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                <div class="panel-body">
                    <table class="table">
                        
                            <?php
                            if($data->parent_id > 0){
                                $parent_details =  get_parent_details($data->parent_id);
                            ?>
                            <tr><td>Name : <?php echo $parent_details->p_name;?></td></tr>
                            <tr><td>ID# : <?php echo $parent_details->id_no;?></td></tr>
                            <tr><td>Occupation : <?php echo $parent_details->occupation;?></td></tr>
                            <tr><td>Contact# : <?php echo $parent_details->contact;?></td></tr>
                            <?php
                            }else{
                                echo "<tr class='text-center'><td>No Record Found</td></tr>";
                            }
                            ?>
                    </table>
                </div>
                </div>
              </div>
              
              <div class="panel panel-default">
                <div class="panel-heading" d="headingFour" data-toggle="collapse" data-target="#academic_info" aria-expanded="true" aria-controls="academic_info"><i class="fas fa-book fa-1x"></i>ACADEMIC INFO</div>
                
                <div id="academic_info" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                <div class="panel-body">
                    <table class="table">
                            <tr><td>Admission Date : <?php echo $data->adm_date;?></td></tr>
                            <tr><td>Academic Year : <?php $academic_year =  get_academic_year_dates($data->academic_year_id); echo $academic_year->title;?></td></tr>
                    </table>
                </div>
                </div>
              </div>
              
            </div>
          
        </div>
        
        
    	<div class="col-sm-9 p-0">
    	    
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#attendance_details" onclick="get_student_attendance()">Attendance</a></li>
                <li><a data-toggle="tab" href="#fee_details">Fee Details</a></li>
                <li><a data-toggle="tab" href="#chalan_details" onclick="get_student_chalan_details()">Chalan Details</a></li>
                <li><a data-toggle="tab" href="#class_subjects" onclick="get_student_subjects()">Class Subjects</a></li>
                <li><a data-toggle="tab" href="#class_diaries" onclick="get_student_subjectsdiary()">Class Diaries</a></li>
                <li><a data-toggle="tab" href="#class_assessments" onclick="get_student_assessments()">Class Assessments</a></li>
                <li><a data-toggle="tab" href="#marks_sheet">Marks Sheet</a></li>
             </ul>
             
            <div class="tab-content">
                    <!-- ******** attendance tab start ******** -->
                <div class="tab-pane active" id="attendance_details">
                <hr>
                <div class="panel panel-default">
                    <div class="panel-heading">ATTENDANCE DETAILS</div>
                        <div class="panel-body">
                            <label class="form-label">Select Month</label>
                            <select name="month" class="form-control" onchange="get_monthly_attendance(this)" >
                                <?php 
                                    $acadmic_year_start = $this->db->query("select start_date from ".get_school_db().".acadmic_year where academic_year_id =".$_SESSION['academic_year_id']." and school_id=".$_SESSION['school_id']." ")->result_array();
                                    
                                    $year=date('Y');
                                    $month = date('F');
                                    $selected = date('m',strtotime($month)).'-'.$year;
                                    echo month_year_option($acadmic_year_start[0]['start_date'], date('Y-m-d'), $selected);
                                ?>
                            </select>
                            <br>
                            <div id="student_attendance_holder">
                                
                            </div>
                        </div>
                </div>
                  
                </div>
                    <!-- ******** attendance tab ends ******** -->
                 
                    <!-- ******** fee details tab start ******** -->
                <div class="tab-pane" id="fee_details">
                   <hr>
                   
                   <div class="panel panel-default">
                    <div class="panel-heading">FEE DETAILS</div>
                        <div class="panel-body">
                        <?php
                            $school_id = $_SESSION['school_id'];
                            $section_id = $this->input->post('section_id');
                            if(empty($year))
                            {
                                $year = 0;
                            }
                            $piad_query = $this->db->query("select * from " . get_school_db() . ".student_chalan_form where student_id = $student_id and status = 5 and s_c_f_year = $year and school_id = $school_id")->result_array();
                        
                            $unpiad_query = $this->db->query("select * from " . get_school_db() . ".student_chalan_form where student_id = $student_id and status = 4 and s_c_f_year = $year and school_id = $school_id")->result_array();
                        ?>
                        <div class="col-md-6">
                            <h4><b>Paid Challan Records</b></h4>
                            <table class="table table-bordered" data-step="6" data-position='top' data-intro="all paid fee records">
                                <thead>
                                    <th>
                                        <?php echo get_phrase('sn.no'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('paid_date'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('month'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('challan_no'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('amount'); ?>
                                    </th>
                                    
                                </thead>
                                <tbody>
                                <?php
                                if (count($piad_query) > 0)
                                {
                                    $i = 1;
                                    foreach ($piad_query as $row)
                                    {
                                        $get_month = '01-'.$row['s_c_f_month'].'-'.$row['s_c_f_year'];
                                ?>
                                        <tr>
                                            <td>
                                              <?php echo $i++; ?>
                                            </td>
                                            <td>
                                                <?php echo convert_date($row['received_date']); ?>
                                            </td>
                                            <td>
                                                <?php echo date('m/Y',strtotime($get_month)); ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $get_scfID = get_chalan_scfid($row['chalan_form_number']);
                                                ?>
                                                <a href="<?=base_url()?>class_chalan_form/edit_chalan_form/<?= $get_scfID ?>" target="_blank" class="text-primary" title="view chalan form">
                                                    <?= $row['chalan_form_number']; ?>
                                                    <b><i class="fas fa-info-circle"></i></b>
                                                </a>
                                            </td>
                                            <td>
                                                <?php echo number_format($row['actual_amount']); ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }else{
                                    echo "<tr><td colspan='5'>No Record Found</td></tr>";
                                }
                                
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4><b>Un-Paid Challan Records</b></h4>
                            <table class="table table-bordered" data-step="7" data-position='top' data-intro="all un-paid fee records">
                                <thead>
                                    <th>
                                        <?php echo get_phrase('sn.no'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('issue_date'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('month'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('challan_no'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('amount'); ?>
                                    </th>
                                    
                                </thead>
                                <tbody>
                                <?php
                                if (count($unpiad_query) > 0)
                                {
                                    $i = 1;
                                    foreach ($unpiad_query as $row)
                                    {
                                        $get_month = '01-'.$row['s_c_f_month'].'-'.$row['s_c_f_year'];
                                ?>
                                        <tr>
                                            <td>
                                              <?php echo $i++; ?>
                                            </td>
                                            <td>
                                                <?php echo convert_date($row['issue_date']); ?>
                                            </td>
                                            <td>
                                                <?php echo date('m/Y',strtotime($get_month)); ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $get_scfID = get_chalan_scfid($row['chalan_form_number']);
                                                ?>
                                                <a href="<?=base_url()?>class_chalan_form/edit_chalan_form/<?= $get_scfID ?>" target="_blank" class="text-primary" title="view chalan form">
                                                    <?= $row['chalan_form_number']; ?>
                                                    <b><i class="fas fa-info-circle"></i></b>
                                                </a>
                                            </td>
                                            <td>
                                                <?php echo number_format($row['actual_amount']); ?>
                                            </td>
                                            
                                        </tr>
                                        <?php
                                    }
                                }else{
                                    echo "<tr><td  colspan='5'>No Record Found</td></tr>";
                                }
                                
                                ?>
                                </tbody>
                            </table>
                        </div>
                       
                        </div>
                  </div>
                   
                </div>
                    <!-- ******** fee details tab start ******** -->
                
                    <!-- ******** chalan details tab starts ******** -->
                <div class="tab-pane" id="chalan_details">
                  <hr> 
                  <div class="panel panel-default">
                    <div class="panel-heading">CHALAN DETAILS</div>
                        <div class="panel-body">
                            <div id="student_chalan_details_holder">
                              
                 	        </div>
                        </div>
                  </div>
                </div>
                    <!-- ******** chalan details tab ends ******** -->
                    
                    <!-- ******** class subjects tab starts ******** -->
                <div class="tab-pane" id="class_subjects">
                  <hr> 
                  <div class="panel panel-default">
                    <div class="panel-heading">ASSIGNED SUBJECTS</div>
                        <div class="panel-body">
                        <table class="table">
                        <ul class="list-group" id="student_subject_holder">
                          
             	        </ul>
     	                </table>
                        </div>
                  </div>
                </div>
                    <!-- ******** class subjects tab ends ******** -->
                    
                    <!-- ******** class diaries tab starts ******** -->
                <div class="tab-pane" id="class_diaries">
                  <hr>
                  
                  <div class="panel panel-default">
                    <div class="panel-heading">ASSIGNED DIARIES</div>
                    <div class="panel-body">
                        <div class="row dairy-row-set px-4"> 
                            <div class="col-sm-12 col-md-3  diary-left pr-5">
                                <h3>Subjects Diary</h3>
                                <div class="diary-subjects diary-subs" >  
                                    
                                </div> 
                            </div> 
                            <div class="springss">
                                <?php echo diary_springs(43); ?>
                            </div>
                            <div class="col-sm-12 col-md-9 bg-light diary-right pl-5">
                                <div class="diary-notes">
                                    <div class="row pl-3 contentdiary">  
                                    </div> 
                                </div>
                            </div> 
                        </div>  
                    </div>
                  </div>
                </div>
                    <!-- ******** class diaries tab ends ******** -->
                    
                    <!-- ******** class assessments tab starts ******** -->
                <div class="tab-pane" id="class_assessments">
                  <hr>
                  
                  <div class="panel panel-default">
                    <div class="panel-heading">ASSIGNED ASSESSMENTS</div>
                        <div class="panel-body">
                        <table class="table">
                        <ul class="list-group list-group-flush" id="student_assessments_holder">
                          
             	        </ul>
     	                </table>
                        </div>
                  </div>
                </div>
                    <!-- ******** class assessments tab ends ******** -->
                
                    <!-- ******** class marks sheet tab starts ******** -->
                <div class="tab-pane" id="marks_sheet">
                  <hr>
                  <div class="panel panel-default">
                    <div class="panel-heading">MARKS SHEET</div>
                        <div class="panel-body">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <label id="exams_filter_selection"></label>                            
                                <select id="exam_id" name="exam_id" class="selectpicker form-control" onchange="get_exam_marksheet(this)">	                                              
                                <?php
                                    $select_year = array(3);
                                    echo yearly_term_selector_exam('',$select_year); 
                                ?> 
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div id="student_exams_holder">
                                
                                </div>
                            </div>
                        </div>
                  </div>
                </div>
                    <!-- ******** class marks sheet tab ends ******** -->
                    
            </div>
              
        </div>

    </div>
    
    <script>
    
        $(document).ready(function(){
            let date = new Date();
            var month = date.toLocaleString('en-us', { month: 'long' });
            var year = date.getFullYear();
            get_student_attendance(month , year);
            $(".page-container").addClass("sidebar-collapsed");
            
           
        });
        
         function mark_check_out()
            {
                var attendance_id  = $('#attendance_id').val();
                // alert(attendance_id);
                 $.ajax({
    			type: "POST",
    			data:{attendance_id:attendance_id},
    			url: '<?php echo base_url()."attendance/individual_mark_student_check_out";?>',
    			dataType:"html",
    			success: function(response){
    			 location.reload();
    			}
    		});
                
                //debugger;
            }
        function get_monthly_attendance(m){
            var str = m.value;
            const dateArray = str.split("-");
            var month = dateArray[0];
            var year = dateArray[1];
            var fullmonth = getMonthName(month);
            get_student_attendance(fullmonth , year);
        }
        
        function getMonthName(month){
          const d = new Date();
          d.setMonth(month-1);
          const monthName = d.toLocaleString("default", {month: "long"});
          return monthName;
        }
    
        function get_student_attendance(mnth , year){
            var month = mnth;
            var student_id = "<?php echo $student_id; ?>";
            var year = year;
            $.ajax({
    			type: "POST",
    			data:{student_id:student_id},
    			data: {month:month,year:year,stud_id:student_id},
    			url: '<?php echo base_url()."dashboard/get_student_attendance";?>',
    			dataType:"html",
    			beforeSend: function() {
                    $('#loader').removeClass('hidden')
                },
    			success: function(response){
    				$('#student_attendance_holder').html(response);
    			},
    			complete: function(){
                    $('#loader').addClass('hidden')
                },
    		});
        }
        
        function get_student_chalan_details(){
            var student_id = "<?php echo $student_id; ?>";
            $.ajax({
    			type: "POST",
    			data:{student_id:student_id},
    			url: '<?php echo base_url()."dashboard/get_student_chalan_details";?>',
    			dataType:"html",
    			beforeSend: function() {
                    $('#loader').removeClass('hidden')
                },
    			success: function(response){
    				$('#student_chalan_details_holder').html(response);
    			},
    			complete: function(){
                    $('#loader').addClass('hidden')
                },
    		});
        }
    
        function get_student_subjects(){
            var student_id = "<?php echo $student_id; ?>";
            $.ajax({
    			type: "POST",
    			data:{student_id:student_id},
    			url: '<?php echo base_url()."dashboard/get_student_subjects";?>',
    			dataType:"html",
    			beforeSend: function() {
                    $('#loader').removeClass('hidden')
                },
    			success: function(response){
    				$('#student_subject_holder').html(response);
    			},
    			complete: function(){
                    $('#loader').addClass('hidden')
                },
    		});
        }   
            
        function get_student_subjectsdiary(){
            var student_id = "<?php echo $student_id; ?>";
            $.ajax({
    			type: "POST",
    			data:{student_id:student_id},
    			url: '<?php echo base_url()."dashboard/get_student_subjectsdiary";?>',
    			dataType:"html",
    			beforeSend: function() {
                    $('#loader').removeClass('hidden')
                },
    			success: function(response){
    				$('.diary-subs').html(response);
    			},
    			complete: function(){
                    $('#loader').addClass('hidden')
                },
    		});
        }
        
        function getsubdiary(subject_id,section_id){
            var student_id = "<?php echo $student_id; ?>";
            $.ajax({
    			type: "POST",
    			data:{student_id:student_id,subject_id:subject_id,section_id:section_id},
    			url: '<?php echo base_url()."dashboard/getsubdiary";?>',
    			dataType:"html",
    			beforeSend: function() {
                    $('#loader').removeClass('hidden')
                },
    			success: function(response){
    				$('.subjects'+subject_id).html(response);
    			},
    			complete: function(){
                    $('#loader').addClass('hidden')
                },
    		});
        }
         
        function getdiarycontent(diaryb_id) {
        
           $.ajax ({ 
               url:"<?= base_url() ?>dashboard/getdiarycontent",
               type:"POST",
               data: {
                   diaryb_id : diaryb_id
               },
               cache: false,
               beforeSend: function() {
                    $('#loader').removeClass('hidden')
                },
               success: function (response) {
                   $(".contentdiary").html(response);
               },
    			complete: function(){
                    $('#loader').addClass('hidden')
                },
           })
        }
        
        function get_student_assessments(){
            debugger;
            var student_id = "<?php echo $student_id; ?>";
            $.ajax({
    			type: "POST",
    			data:{student_id:student_id},
    			url: '<?php echo base_url()."dashboard/get_student_assessments";?>',
    			dataType:"html",
    			beforeSend: function() {
                    $('#loader').removeClass('hidden')
                },
    			success: function(response){
    				$('#student_assessments_holder').html(response);
    			},
    			complete: function(){
                    $('#loader').addClass('hidden')
                },
    		});
        }
        
        function get_exam_marksheet(exam){
            
            var exam_id = exam.value;
            var student_id = "<?php echo $student_id; ?>";
            $.ajax({
    			type: "POST",
    			data:{
    			    student_id:student_id,
    			    exam_id:exam_id
    			},
    			url: '<?php echo base_url()."dashboard/get_exam_marksheet";?>',
    			dataType:"html",
    			beforeSend: function() {
                    $('#loader').removeClass('hidden')
                },
    			success: function(response){
    				$('#student_exams_holder').html(response);
    			},
    			complete: function(){
                    $('#loader').addClass('hidden')
                },
    		});
        }
        
        
        
        
    </script>
    
    