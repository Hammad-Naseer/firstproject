<style>

.navigation-backups h4{
	margin:20px 0px 10px 0px;
}
.navigation-backups li a{
	float:right;
	background:none !important;
	line-height:0 !important;
	color:blue !important;
	border:none;
}
.navigation-backups li a:hover,
.navigation-backups li a:focus{
	background:none !important;
	border:none !important;
	color:#000 !important;
}
.navigation-backups li{
	border:1px solid transparent !important;
	border-bottom:1px solid #e4e4e4 !important;
	margin-bottom:0px !important;
}

</style>
<?php
    if($this->session->flashdata('club_updated')){
      echo '<div align="center">
        <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        '.$this->session->flashdata('club_updated').'
        </div> 
    </div>';
  }
  ?>
    <script>
        $(window).on("load",function() {
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 3000);
        });
    </script>
 <div class="row">
       <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline capitalize">
                <?php echo get_phrase('backup');?>  
            </h3>
       </div>
 </div>
 
 
 <div class="row mng-backup">
 	<div class="col-sm-12 p-0" data-step="1" data-position="top" data-intro="Press any button to download specific backup">
 		<ul class="list-group navigation-backups" style="box-shadow: 0px 0px 10px 1px #cccccc69;padding: 10px;">
      		<div class="schoolmanagement">
            	<h4><?php echo get_phrase('school_management'); ?></h4>
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('city_locations');?>
    <?php if (right_granted('locations_backup'))
    {
    ?>  			
                <a href="<?php echo base_url();?>export/location_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
    <?php
    }
    ?>  			
                </li>
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('school_policies');?>
    <?php if (right_granted('school_policies_backup'))
    {?>  
    <a href="<?php echo base_url();?>zip/policies_download" class="btn btn-primary"><?php echo get_phrase('download_attachments');?></a>			
    <a href="<?php echo base_url();?>export/policies_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
    <?php
    }
    ?>  			
                </li>
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('academic_year_and_terms');?>
    <?php if (right_granted('academic_year_&_terms_backup'))
    {?>  			
    <a href="<?php echo base_url();?>export/academic_year_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
    <?php
    }
    ?>   			
                </li>
                
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('school_yacations');?>
    <?php if (right_granted('vacation_settings_backup'))
    {?>  			
    <a href="<?php echo base_url();?>export/vacation_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
    <?php
    }
    ?>   			
                </li>
                
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('leave_Categories');?>
    <?php if (right_granted('manage_leave_category_backup'))
    {?>  			
    <a href="<?php echo base_url();?>export/leave_category_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
    <?php
    }
    ?>   			
                </li>
                
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('departments');?>
    <?php if (right_granted('departments_backup'))
    {?>  			
    <a href="<?php echo base_url();?>export/department_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
    <?php
    }
    ?>   			
                </li>
                
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('classes');?>
    <?php if (right_granted('classes_backup'))
    {?>  			
    <a href="<?php echo base_url();?>export/class_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
    <?php
    }
    ?>   			
                </li>
                
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('sections');?>
    <?php if (right_granted('sections_backup'))
    {?>  			
    <a href="<?php echo base_url();?>export/section_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
    <?php
    }
    ?>   			
                </li>
                
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('subjects');?>
    <?php if (right_granted('subject_backup'))
    {?>  			
    <a href="<?php echo base_url();?>export/subject_csv" class="btn btn-primary"><?php echo get_phrase('download_date');?></a>
    <?php
    }
    ?>   			
                </li>
    			<li class="list-group-item">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4" style="padding-top:10px;">  			
                    <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('time_table');?>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8">
        	<div class="row">
				<?php echo form_open(base_url().'export/time_table_csv' , array('id'=>'disable_submit_btn','class' => 'form-horizontal validate','target'=>'_top'));?>
                <div class="col-lg-6 col-md-6 col-sm-6">
                <select id="section_id" class="selectpicker form-control" name="section_id" data-validate="required" data-message-required="Value Required">
                                <?php echo section_selector();?>
                            </select> 
                </div>
                <?php					
                if (right_granted('manage_time_table_backup'))
                {?> 
                <div class="col-lg-6 col-md-6 col-sm-6">
                <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>		
                </div>
            </div>
        </div>
    </div> 
    <?php
    }
    ?> 
    <?php
    echo form_close();?>  			
                </li>
                
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('student_evaluation settings');?>
                <?php if (right_granted('student_evaluation_settings_backup'))
                {?>  			
                <a href="<?php echo base_url();?>export/stud_eval_settings_csv" class="btn btn-primary">
				<?php echo get_phrase('download_data');?></a>
                <?php
                }
                ?>   			
                            </li>
                
    			<li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('staff_Evaluation Settings');?>
    <?php if (right_granted('staff_evaluation_settings_backup'))
    {?>  			
    <a href="<?php echo base_url();?>export/staff_eval_settings_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>  
    <?php
    }
    ?>   			
                </li>
                
			</div>
  			
            <div class="accountmanagement">
            	<h4><?php echo get_phrase('account_management');?></h4>
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('');?><?php echo get_phrase('chart_of_accounts');?>
                <?php if (right_granted('chart_of_account_backup'))
                {?>  			
                <a href="<?php echo base_url();?>export/chart_of_account" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
                <?php
                }
                ?>   			
                            </li>
                            
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('fee_types');?>
                <?php if (right_granted('fee_type_backup'))
                {?>  			
                <a href="<?php echo base_url();?>export/fee_types_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
                <?php
                }
                ?>   			
                            </li>
                            
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('discount_types');?>
                <?php if (right_granted('discount_type_backup'))
                {?>  			
                <a href="<?php echo base_url();?>export/discount_types_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
                <?php
                }
                ?>   			
                            </li>
                            
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('manage_transactions');?>
                <?php if (right_granted('manage_account_transactions_backup'))
                {?>  			
                <a href="<?php echo base_url();?>export/manage_transactions" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
                <?php
                }
                ?>   			
                            </li>
  			</div>
            
            <div class="staffmanagement">
            	<h4><?php echo get_phrase('staff_management');?></h4>
            	 <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('designation');?>
            	  <?php if (right_granted('designation_backup'))
                {?> 
            	<a href="<?php echo base_url();?>export/designation_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
                <?php
                }
                ?>  
                </li>  
                
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('staff');?>
                <?php if (right_granted('staff_backup'))
                {?>  			
                <a href="<?php echo base_url();?>export/staff_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
                <a href="<?php echo base_url();?>zip/staff_download" class="btn btn-primary"><?php echo get_phrase('download_attachments');?></a>
                <?php
                }
                ?>   			
                            </li>  
                            
                <li class="list-group-item">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top:10px;">  			
                                <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('view_staff_attendance');?>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                    	<div class="row">
							<?php echo form_open(base_url().'export/staff_attendance_csv' , array('id'=>'disable_submit_btn','class' => 'form-horizontal validate','target'=>'_top'));?>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                            <select id="academic_year" name="academic_year" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                        <?php echo academic_year_option_list($year);?>
                                    </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                            <select id="staff_id" class="form-control" name="staff_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                             <?php
                            echo staff_list();
                            ?>
                                                    </select>
                            </div>
                            <?php					
                            if (right_granted('staff_attendance_backup'))
                            {?> 
                            <div class="col-lg-4 col-md-4 col-sm-4">
                            <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>		
                            </div>
                        </div>
                    </div>
                </div> 
                <?php
                }
                ?> 
                <?php
                echo form_close();?>  			
                            </li>
                
                <li class="list-group-item">
                <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top:10px;">  		
                <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('staff_leaves');?>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9">
                	<div class="row">
                	<?php echo form_open(base_url().'export/manage_leaves_staff_csv' , array('class' => 'form-horizontal validate','target'=>'_top','id'=>'staff_leave_form'));?>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="academic_year4" name="academic_year4" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    <?php echo academic_year_option_list($year);?>
                                </select>
                        </div>
                         <div class="col-lg-3 col-md-3 col-sm-3">
 <select name="staff_id4" id="staff_id4" 
 class="form-control required" required="required" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" > 
				
        <option value='' ><?php echo get_phrase('select_staff');?></option>
                                                <?php 
                            $qry = "SELECT * FROM ".get_school_db().".staff WHERE
                            school_id=".$_SESSION['school_id']." ";
                            $query=$this->db->query($qry)->result_array();
                            foreach($query as $teacher)
                            {
                            ?>
                <option value="<?php echo $teacher['staff_id'];?>" >
                                                    <?php echo $teacher['name'];?>
                                                </option>
                                                <?php
                            }
                            ?>
                                            </select>
                       </div> 
                        
                <?php if (right_granted('staff_leaves_backup'))
                {?>  			
                
                <a href="#" class="btn btn-primary" id="staff_leave_btn"><?php echo get_phrase('download_attachments');?></a>
                
                <div class="col-lg-3 col-md-3 col-sm-3">
                <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>
                </div>
                
               </div>
                </div>
                </div> 
                <?php
                }
                ?> 
                <?php
                echo form_close();?>  			
                </li> 	
                            
                <li class="list-group-item">
                    <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4" style="padding-top:10px;">  			
                                <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('staff_evaluation');?>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8">
                    	<div class="row">
							<?php echo form_open(base_url().'export/staff_evaluation_csv' , array('class' => 'form-horizontal validate','target'=>'_top', 'name'=>'staff_evaluation', 'id'=>'staff_evaluation') );?>
							<div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="academic_year5" name="academic_year5" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    <?php echo academic_year_option_list($year);?>
                                </select>
                        </div>
                    
                            <div class="col-lg-3 col-md-3 col-sm-3">
 <select name="staff_id5" id="staff_id5" 
 class="form-control required" required="required" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" > 
				
        <option value='' ><?php echo get_phrase('select_staff'); ?></option>
                                                <?php 
                            $qry = "SELECT * FROM ".get_school_db().".staff WHERE
                            school_id=".$_SESSION['school_id']." ";
                            $query=$this->db->query($qry)->result_array();
                            foreach($query as $teacher)
                            {
                            ?>
                <option value="<?php echo $teacher['staff_id'];?>" >
                                                    <?php echo $teacher['name'];?>
                                                </option>
                                                <?php
                            }
                            ?>
                                            </select>
                            <div id="error" style="display:none"></div>
                            </div>
            
                            <?php					
                            if (right_granted('staff_evaluation_backup'))
             
                            {?>
                            
                      
                            <div class="col-lg-6 col-md-6 col-sm-6">
                       
        <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>	
        <a href="<?php echo base_url();?>zip/staff_evaluation_download" class="btn btn-primary" id="staff_eval_btn"><?php echo get_phrase('download_attachments');?></a>     
      
	
               
                            </div>
                        </div>
                    </div>
                    </div> 
                    <?php
                    }
                    ?> 
                    <?php
                    echo form_close();?>  			
                 </li>
			</div>
            
            <div class="studentmanagement">
            	<h4><?php echo get_phrase('student_management');?></h4>
                <li class="list-group-item">
                <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top:10px;">  
                <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('candidates');?>
                </div>
                 <div class="col-lg-9 col-md-9 col-sm-9">
                	<div class="row">
                	<?php echo form_open(base_url().'export/candidate_information_csv' , array('class' => 'form-horizontal validate','target'=>'_top','id'=>'candidate_form'));?>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="academic_year3" name="academic_year3" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    <?php echo academic_year_option_list($year);?>
                                </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="section_id3" class="selectpicker form-control" name="section_id3" data-validate="required" data-message-required="Value Required">
                                                <?php echo section_selector($section_id);?>
                                            </select>
                        </div>
                <?php if (right_granted('candidate_list_backup'))
                {?> 
                 			
                
                <a href="#" class="btn btn-primary" id="candidate_btn"><?php echo get_phrase('download_attachments');?></a>
                <div class="col-lg-3 col-md-3 col-sm-3">
                <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>
                        </div>
                 </div>
                </div>
                </div> 
                <?php
                }
                ?> 
                <?php
                echo form_close();?>  			
                </li>
                
                <li class="list-group-item">
                <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top:10px;">  			
                <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('students');?>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9">
                	<div class="row">
                	<?php echo form_open(base_url().'export/student_information_csv' , array('class' => 'form-horizontal validate','target'=>'_top','id'=>'student_form'));?>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="academic_year2" name="academic_year2" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    <?php echo academic_year_option_list($year);?>
                                </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="section_id2" class="selectpicker form-control" name="section_id2" data-validate="required" data-message-required="Value Required">
                                                <?php echo section_selector($section_id);?>
                                            </select>
                        </div>
                <?php if (right_granted('students_backup'))
                {?>
                
                 <a href="#" class="btn btn-primary" id="student_button"><?php echo get_phrase('');?><?php echo get_phrase('download_attachments');?></a>			
               
                <div class="col-lg-3 col-md-3 col-sm-3">
                <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>
                        </div>
               
                </div>
                </div>
                </div> 
                <?php
                }
                ?> 
                <?php
                echo form_close();?>  			
                </li>
                
                <li class="list-group-item">
                <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top:10px;">  			
                            <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('view_student_attendance');?>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9">
                	<div class="row">
                	<?php echo form_open(base_url().'export/stud_attendance_csv' , array('id'=>'stud_attendance_csv','class' => 'form-horizontal validate','target'=>'_top'));?>
                	<div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="academic_year9" name="academic_year9" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    <?php echo academic_year_option_list($year);?>
                                </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="section_id9" class="selectpicker form-control" name="section_id9" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                            <?php echo section_selector();?>
                                        </select>
                        </div>
                        
                        
                        <?php					
                        if (right_granted('view_student_attendance_backup'))
                        {?> 
                        <div class="col-lg-6 col-md-6 col-sm-6">
                        <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>
                        </div>
                    </div>
                </div>
                </div> 
                <?php
                }
                ?> 
                <?php
                echo form_close();?> 
                            
                            </li>  
                
                <li class="list-group-item">
                <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top:10px;">  	
                <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('manage_student_leaves');?> 
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9">
                	<div class="row">
                	<?php echo form_open(base_url().'export/manage_leaves_student_csv' , array('class' => 'form-horizontal validate','target'=>'_top','id'=>'stud_leaves_form'));?>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="academic_year7" name="academic_year7" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    <?php echo academic_year_option_list($year);?>
                                </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="section_id7" class="selectpicker form-control" name="section_id7" data-validate="required" data-message-required="Value Required">
                                                <?php echo section_selector($section_id);?>
                                            </select>
                        </div>
                <?php if (right_granted('student_leaves_backup'))
                {?>  			
                
                <a href="#" class="btn btn-primary" id="stud_leaves_btn"><?php echo get_phrase('download_attachments');?></a>
                <div class="col-lg-3 col-md-3 col-sm-3">
                <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>
                        </div>
                </div>
                </div>
                </div> 
                <?php
                }
                ?> 
                <?php
                echo form_close();?>  			
                </li>
                
                <li class="list-group-item">
                <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top:10px;">  	
                <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('diary');?> 
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9">
                	<div class="row">
                	<?php echo form_open(base_url().'export/diary_csv' , array('class' => 'form-horizontal validate','target'=>'_top','id'=>'diary_form'));?>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="academic_year8" name="academic_year8" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    <?php echo academic_year_option_list($year);?>
                                </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="section_id8" class="selectpicker form-control" name="section_id8" data-validate="required" data-message-required="Value Required">
                                                <?php echo section_selector($section_id);?>
                                            </select>
                        </div>
                <?php if (right_granted('diary_backup'))
                {?>  			
                
                <a href="#" class="btn btn-primary" id="diary_btn"><?php echo get_phrase('download_attachments');?></a>
                <div class="col-lg-3 col-md-3 col-sm-3">
                <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>
                </div>
                
                 </div>
                </div>
                </div> 
                <?php
                }
                ?> 
                <?php
                echo form_close();?>  			
                </li>
                
                <li class="list-group-item">
                <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top:10px;">  	
                <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('exam_results');?>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9">
                	<div class="row">
                	<?php echo form_open(base_url().'export/exam_results_csv' , array('class' => 'form-horizontal validate','target'=>'_top','id'=>'manage_marks_form'));?>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                       <select id="exams_id13" name="exams_id13" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">
                            <?php
                       
 echo yearly_term_selector_exam();
 ?>
                        </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="section_id13" class="selectpicker form-control" name="section_id13" data-validate="required" data-message-required="Value Required">
                                                <?php echo section_selector($section_id);?>
                                            </select>
                        </div>
                <?php if (right_granted('manage_marks_backup'))
                {?>  			
                  <div class="col-lg-3 col-md-3 col-sm-3">
                <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>
                        </div>
                </div>
                </div>
                </div> 
                <?php
                }
                ?> 
                <?php
                echo form_close();?>  			
                </li>
                
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('exam_grades');?> 
                <?php if (right_granted('exams_grades_backup'))
                {?>  			
                 <a href="<?php echo base_url();?>export/exam_grades_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
                <?php
                }
                ?>   				
                </li>	
                
                
                
                <li class="list-group-item">
                <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4" style="padding-top:10px;">  			
                            <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('student_evaluation');?>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                	<div class="row">
						<?php echo form_open(base_url().'export/student_evaluation_csv' , array('class' => 'form-horizontal validate','target'=>'_top','id'=>'stud_eval_form'));?>
						<div class="col-lg-3 col-md-3 col-sm-3">
						 <select id="exam_id6" name="exam_id6" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">
                        <?php
                       
                         echo yearly_term_selector_exam(); ?>
                    </select>
                        </div>
						
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="section_id6" class="selectpicker form-control" name="section_id6" data-validate="required" data-message-required="Value Required">
                                                <?php echo section_selector($section_id);?>
                                            </select>
                        </div>
                        <?php					
                        if (right_granted('student_evaluation_settings_backup'))
                        {?> 
                         <a href="#" class="btn btn-primary" id="stud_eval_btn"><?php echo get_phrase('download_attachments');?></a>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>		
                        </div>
                    </div>
                </div>
                </div> 
                <?php
                }
                ?> 
                <?php
                echo form_close();?>  			
                            </li>   
  			</div>
            
            <div class="academicplanner">
            	<h4><?php echo get_phrase('academic_planner'); ?></h4>
                <li class="list-group-item">
                <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top:10px;">  			
                            <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('manage_academic_planner');?>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9">
                	<div class="row">
						<?php echo form_open(base_url().'export/acad_planner_csv' , array('id'=>'acad_planner_csv','class' => 'form-horizontal validate','target'=>'_top'));?>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="academic_year1" name="academic_year1" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    <?php echo academic_year_option_list($year);?>
                                </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                         <select id="subject_id1" class="selectpicker form-control" name="subject_id1" data-validate="required" data-message-required="Value Required">
                         <option value=""><?php echo get_phrase('select_subject'); ?></option>
                        <?php
                        $q="SELECT s.*,sc.title as subj_categ_title FROM ".get_school_db().".subject s
		LEFT JOIN ".get_school_db().".subject_category sc
		ON s.subj_categ_id=sc.subj_categ_id
		 WHERE s.school_id=".$_SESSION['school_id']."";
		 $subjects=$this->db->query($q)->result_array();
		 
		 foreach($subjects as $subj)
		 {
		 	$subj_list="";
		 	$subj_categ="";
		 	if($subj['subj_categ_title']!="")
		 	{
				$subj_categ=$subj['subj_categ_title'].' - ';
			}
		 	$subj_list=$subj_categ.$subj['name'].' ('.$subj['code'].')';
		 	?>
		  <option value="<?php echo $subj['subject_id'];?>"><?php echo $subj_list;?></option>   
		 	<?php	
		 }
		 ?>
                       
                                             <?php
                                                 
            $str .= '<option value="'.$row['country_id'].'" '.$opt_selected.'>'.$row['title'].'</option>';?>
                                            </select>
                        </div>
                        <?php					
                        if (right_granted('academic_planner_backup'))
                        {?> 
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        	<a href="<?php echo base_url();?>zip/acad_planner_download" class="btn btn-primary" style="padding-top:12px !important;"><?php echo get_phrase('download_attachments');?></a>	
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3">
                        	<button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>
                        </div>
                    </div>
                </div>
                </div> 
                <?php
                }
                ?> 
                <?php
                echo form_close();?>  			
                            </li>
            </div>
            
            <div class="noticescirculars">
  				<h4><?php echo get_phrase('notices_and_circulars');?></h4>
                <li class="list-group-item">
                <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top:10px;">  
                <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('noticeboard');?>
                 </div>
                  <div class="col-lg-9 col-md-9 col-sm-9">
                	<div class="row">
                	<?php echo form_open(base_url().'export/noticeboard_csv' , array('class' => 'form-horizontal validate','target'=>'_top','id'=>'noticeboard_form'));?>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                        <select id="academic_year10" name="academic_year10" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    <?php echo academic_year_option_list($year);?>
                                </select>
                        </div>
                        
                <?php if (right_granted('noticeboard_backup'))
                {?>  			
                 <div class="col-lg-6 col-md-6 col-sm-6">
                <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>
                    </div>
                </div>
                </div>
                </div> 
                <?php
                }
                ?> 
                <?php
                echo form_close();?>  			
                </li>
                
                <li class="list-group-item">
                <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top:10px;">  	
                <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('circulars');?>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9">
                	<div class="row">
                	<?php echo form_open(base_url().'export/circular_csv' , array('class' => 'form-horizontal validate','target'=>'_top','id'=>'circular_form'));?>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="academic_year11" name="academic_year11" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    <?php echo academic_year_option_list($year);?>
                                </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                        <select id="section_id11" class="selectpicker form-control" name="section_id11" data-validate="required" data-message-required="Value Required">
                                                <?php echo section_selector($section_id);?>
                                            </select>
                        </div>
                <?php if (right_granted('circular_backup'))
                {?>  			
                 
                 <a href="<?php echo base_url();?>zip/circular_download" class="btn btn-primary" id="circular_btn"><?php echo get_phrase('download_attachments');?></a>  
                  <div class="col-lg-3 col-md-3 col-sm-3">
                <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>
                        </div>
                </div>
                </div>
                </div> 
                <?php
                }
                ?> 
                <?php
                echo form_close();?>  			
                </li>
            </div>	
			
            <div class="conversation">
            	<h4><?php echo get_phrase('messages');?></h4>
                <li class="list-group-item">
                <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top:10px;">  			
                            <i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('messages');?>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9">
                	<div class="row">
						<?php echo form_open(base_url().'export/messages_csv' , array('id'=>'messages_csv_form','class' => 'form-horizontal validate','target'=>'_top'));?>
						<div class="col-lg-2 col-md-2 col-sm-2">
						<select id="academic_year12" name="academic_year12" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                                    <?php echo academic_year_option_list($year);?>
                                </select>
						</div>
						
                        <div class="col-lg-2 col-md-2 col-sm-2">
                        <select id="teacher_id12" name="teacher_id12" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                                <?php echo get_teacher_option_list(intval($teacher_id));?>
                                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                        <select id="section_id12" name="section_id12" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                                <?php echo section_selector(intval($section_id)); ?>
                        </select>
                        </div>
                        <?php					
                        if (right_granted('messages_backup'))
                        {?> 
                        <div class="col-lg-4 col-md-4 col-sm-4">
                        <button type="submit" class="btn btn-primary"><?php echo get_phrase('download_data');?></button>	
                        </div>
                    </div>
                </div>
                </div> 
                <?php
                }
                ?> 
                <?php
                echo form_close();?>  			
                            </li>
            </div>
  			
            <div class="branches">
            	<h4>Branches</h4>
                <li class="list-group-item"><i class="fa fa-dot-circle-o" aria-hidden="true"> </i><?php echo get_phrase('branches');?>
                <?php if (right_granted('branches_backup'))
                {?>  			
                 <a href="<?php echo base_url();?>export/branch_csv" class="btn btn-primary"><?php echo get_phrase('download_data');?></a>
                 <a href="<?php echo base_url();?>zip/branch_download" class="btn btn-primary"><?php echo get_phrase('download_attachments');?></a>
                <?php
                }
                ?>   				
                </li>
            </div>	    
  		 		
		</ul>
 	</div>
 </div>
 

 <script>
$(document).ready(function(){
   

 	//var frm=$('#staff_evaluation');
 	//var frm_action=document.staff_evaluation.action;
 	//alert(frm_action);
 	
 	//var base='<?php echo base_url();?>';
	//var new_action=base+'zip/staff_evaluation_download/';
 	//document.staff_evaluation.action = new_action;

    //var	frm_action=document.staff_evaluation.action;
 	//alert(frm_action);
 //$("#staff_evaluation").submit(function(){
   // $(#staff_evaluation).attr("action", "frm_action")
     //  .attr("method", "post");
       //$(#staff_evaluation).submit();
 //})
 
 
 $('#link').click(function(){
 	$('#error').hide();
 	var staff=$('#staff_id_new option:selected').val();
 	//alert(staff);
 	if(staff=='')
    {
       // alert(staff);
       $('#error').text("Please Select Staff").show();
        
        //var base1='<?php echo base_url();?>';
var final1='#'+staff
             $('#link').attr('href',final1);
       // $(this).text("invalid selection");
    }
    if(staff!='')
    {
    	$('#error').text("Please Select Staff").hide();
       //alert(staff);
          var base='<?php echo base_url();?>';
var final=base+'zip/staff_evaluation_download/'+staff
             $('#link').attr('href',final);
        $('#error').hide();
    }
 	
});

 $('#candidate_btn').click(function(e){
 e.preventDefault();
 var acad_year=$('#academic_year3').val();
 var section_id=$('#section_id3').val();
 if(acad_year!='' && section_id!='')
 {
 	$('#msg_text').remove();
 	$('#msg_text2').remove();
 	var base='<?php echo base_url();?>';
$('#candidate_form').attr('action', base+'zip/candidates_download/'+acad_year+'/'+section_id).submit();
 }
 else if(acad_year == '' && section_id == '')
 {
 	$('#academic_year3').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 	$('#section_id3').after("<span id='msg_text2' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 else if(acad_year == '')
 {
 	$('#academic_year3').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 else if(section_id == '')
 {
 	$('#section_id3').after("<span id='msg_text2' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 
 
});

$('#academic_year3').change(function(){
	$('#msg_text').remove();
 	$('#msg_text2').remove();
});

$('#section_id3').change(function(){
	$('#msg_text').remove();
 	$('#msg_text2').remove();
});

 $('#student_button').click(function(e){
 e.preventDefault();
 var acad_year=$('#academic_year2').val();
 var section_id=$('#section_id2').val();
 if(acad_year!='' && section_id!='')
 {
 	$('#msg_text').remove();
 	$('#msg_text2').remove();
 	var base='<?php echo base_url();?>';
$('#student_form').attr('action', base+'zip/students_download/'+acad_year+'/'+section_id).submit();
 }
 else if(acad_year == '' && section_id == '')
 {
 	$('#academic_year2').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 	$('#section_id2').after("<span id='msg_text2' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 else if(acad_year == '')
 {
 	$('#academic_year2').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 else if(section_id == '')
 {
 	$('#section_id2').after("<span id='msg_text2' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 
 
});

$('#academic_year2').change(function(){
	$('#msg_text').remove();
 	$('#msg_text2').remove();
});

$('#section_id2').change(function(){
	$('#msg_text').remove();
 	$('#msg_text2').remove();
});


$('#staff_leave_btn').click(function(e){
 e.preventDefault();
 var acad_year=$('#academic_year4').val();
 var staff_id=$('#staff_id4').val();
 
 
 if(acad_year!='' && staff_id!='')
 {
 	$('#msg_text').remove();
 	$('#msg_text2').remove();
 	var base='<?php echo base_url();?>';
$('#staff_leave_form').attr('action', base+'zip/staff_leaves_download/'+acad_year+'/'+staff_id).submit();
 }
 
 else if(acad_year == '' && staff_id == '')
 {
 	$('#academic_year4').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 	$('#staff_id4').after("<span id='msg_text2' class='red'<?php echo get_phrase('value_required'); ?></span>");
 }
 else if(acad_year == '')
 {
 	$('#academic_year4').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 else if(section_id == '')
 {
 	$('#staff_id4').after("<span id='msg_text2' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 
 
 
});

$('#academic_year4').change(function(){
	$('#msg_text').remove();	
	$('#msg_text2').remove();
});
$('#staff_id4').change(function(){
	$('#msg_text').remove();
 	$('#msg_text2').remove();
});

$('#staff_eval_btn').click(function(e){
 e.preventDefault();
 var acad_year=$('#academic_year5').val();
 var staff_id=$('#staff_id5').val();
 
 
 if(acad_year!='' && staff_id!='')
 {
 	$('#msg_text').remove();
 	$('#msg_text2').remove();
 	var base='<?php echo base_url();?>';
$('#staff_evaluation').attr('action', base+'zip/staff_evaluation_download/'+acad_year+'/'+staff_id).submit();
 }
 
 else if(acad_year == '' && staff_id == '')
 {
 	$('#academic_year5').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 	$('#staff_id5').after("<span id='msg_text2' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 else if(acad_year == '')
 {
 	$('#academic_year5').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 else if(staff_id == '')
 {
 	$('#staff_id5').after("<span id='msg_text2' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 
 
 
});

$('#academic_year5').change(function(){
	$('#msg_text').remove();	
	$('#msg_text2').remove();
});
$('#staff_id5').change(function(){
	$('#msg_text').remove();
 	$('#msg_text2').remove();
});


$('#stud_eval_btn').click(function(e){
 e.preventDefault();
 var exam_id=$('#exam_id6').val();
 var section_id=$('#section_id6').val();
 
 
 if(exam_id!='' && section_id!='')
 {
 	$('#msg_text').remove();
 	$('#msg_text2').remove();
 	var base='<?php echo base_url();?>';
$('#stud_eval_form').attr('action', base+'zip/student_evaluation_download/'+exam_id+'/'+section_id).submit();
 }
 
 else if(exam_id == '' && section_id == '')
 {
 	$('#exam_id6').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 	$('#section_id6').after("<span id='msg_text2' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 else if(exam_id == '')
 {
 	$('#exam_id6').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 else if(section_id == '')
 {
 	$('#section_id6').after("<span id='msg_text2' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 
 
 
});

$('#exam_id6').change(function(){
	$('#msg_text').remove();	
	$('#msg_text2').remove();
});
$('#section_id6').change(function(){
	$('#msg_text').remove();
 	$('#msg_text2').remove();
});


$('#stud_leaves_btn').click(function(e){
 e.preventDefault();
 var acad_year=$('#academic_year7').val();
 var section_id=$('#section_id7').val();
 
 
 if(acad_year!='' && section_id!='')
 {
 	$('#msg_text').remove();
 	$('#msg_text2').remove();
 	var base='<?php echo base_url();?>';
$('#stud_leaves_form').attr('action', base+'zip/student_leaves_download/'+acad_year+'/'+section_id).submit();
 }
 
 else if(acad_year == '' && section_id == '')
 {
 	$('#academic_year7').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 	$('#section_id7').after("<span id='msg_text2' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 else if(acad_year == '')
 {
 	$('#academic_year7').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 else if(section_id == '')
 {
 	$('#section_id7').after("<span id='msg_text2' class='red'>Value Required</span>");
 }
 
 
 
});

$('#academic_year7').change(function(){
	$('#msg_text').remove();	
	$('#msg_text2').remove();
});
$('#section_id7').change(function(){
	$('#msg_text').remove();
 	$('#msg_text2').remove();
});


$('#diary_btn').click(function(e){
 e.preventDefault();
 var acad_year=$('#academic_year8').val();
 var section_id=$('#section_id8').val();
 
 
 if(acad_year!='' && section_id!='')
 {
 	$('#msg_text').remove();
 	$('#msg_text2').remove();
 	var base='<?php echo base_url();?>';
$('#diary_form').attr('action', base+'zip/diary_download/'+acad_year+'/'+section_id).submit();
 }
 
 else if(acad_year == '' && section_id == '')
 {
 	$('#academic_year8').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 	$('#section_id8').after("<span id='msg_text2' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 else if(acad_year == '')
 {
 	$('#academic_year8').after("<span id='msg_text' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 else if(section_id == '')
 {
 	$('#section_id8').after("<span id='msg_text2' class='red'><?php echo get_phrase('value_required'); ?></span>");
 }
 
 
 
});

$('#academic_year8').change(function(){
	$('#msg_text').remove();	
	$('#msg_text2').remove();
});
$('#section_id8').change(function(){
	$('#msg_text').remove();
 	$('#msg_text2').remove();
});


$('#circular_btn').click(function(e){
 e.preventDefault();
 var acad_year=$('#academic_year11').val();
 
 
 
 if(acad_year!='')
 {
 	$('#msg_text').remove();
 	$('#msg_text2').remove();
 	var base='<?php echo base_url();?>';
$('#circular_form').attr('action', base+'zip/circular_download/'+acad_year).submit();
 }
 else if(acad_year == '')
 {
 	$('#academic_year11').after("<span id='msg_text' class='red'>Value Required</span>");
 }
});

$('#academic_year11').change(function(){
	$('#msg_text').remove();	
	$('#msg_text2').remove();
});



});

</script>
