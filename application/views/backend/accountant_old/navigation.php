<div class="sidebar-menu">

		
		
		
		
		
		
		<header >
		
		
	<div class="logodiv">
		
		<img src="<?php echo base_url();?>uploads/old_logo.png" class="logo_img">
		

		
		</div>
		
		
		
		
		
			<div>
			<?php 
$qurr=$this->db->query('select * from settings where type="logo"')->result_array();

$logo= $qurr[0]['description'];

    
     ?>
			<center>
				<a href="<?php echo base_url();?>"><img src="<?php echo base_url();?>uploads/<?php echo $logo; ?>" class="logoz">
				</a>
			</center>
			</div>
   
		
		</header>
		
		
		
		
		
		<div style="border-top:1px solid rgba(69, 74, 84, 0.7);"></div>	
		<ul id="main-menu" class="">
			<!-- add class "multiple-expanded" to allow multiple submenus to open -->
			<!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
            
           
           <!-- DASHBOARD -->
           <li class="<?php if($page_name == 'dashboard')echo 'active';?> ">
				<a href="<?php echo base_url();?>accountant/dashboard">
					<i class="entypo-gauge"></i>
					<span><?php echo get_phrase('dashboard');?></span>
				</a>
           </li>
           
           <!-- STUDENT -->
			<li class="<?php if($page_name == 'student_add' || 
									$page_name == 'student_information' || 
										$page_name == 'student_marksheet')
											echo 'opened active has-sub';?> ">
				<a href="#">
					<i class="fa fa-group"></i>
					<span><?php echo get_phrase('student');?></span>
				</a>
				<ul>
                	<!-- STUDENT ADMISSION -->
					<li class="<?php if($page_name == 'student_add')echo 'active';?> ">
						<a href="<?php echo base_url();?>accountant/student_add">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('Add_student');?></span>
						</a>
					</li>
                  
                  <!-- STUDENT INFORMATION -->
					<li class="<?php if($page_name == 'student_information')echo 'opened active';?> ">
						<a href="#">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('student_information');?></span>
						</a>
                        <ul>
                        	<?php
                        	 $this->db->order_by("order_by", "asc");
                        	 $classes	=	$this->db->get('class')->result_array();
							foreach ($classes as $row):?>
							<li class="<?php if ($page_name == 'student_information' && $class_id == $row['class_id']) echo 'active';?>">
								<a href="<?php echo base_url();?>accountant/student_information/<?php echo $row['class_id'];?>">
									<span><?php echo get_phrase('class');?> <?php echo $row['name'];?></span>
								</a>
							</li>
                            <?php endforeach;?>
                        </ul>
					</li>
                    
                  <!-- STUDENT MARKSHEET 
					<li class="<?php if($page_name == 'student_marksheet')echo 'opened active';?> ">
						<a href="<?php echo base_url();?>accountant/student_marksheet/<?php echo $row['class_id'];?>">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('student_marksheet');?></span>
						</a>
                        <ul>
                        	<?php $classes	=	$this->db->get('class')->result_array();
							foreach ($classes as $row):?>
							<li class="<?php if ($page_name == 'student_marksheet' && $class_id == $row['class_id']) echo 'active';?>">
								<a href="<?php echo base_url();?>accountant/student_marksheet/<?php echo $row['class_id'];?>">
									<span><?php echo get_phrase('class');?> <?php echo $row['name'];?></span>
								</a>
							</li>
                            <?php endforeach;?>
                        </ul>
					</li>-->
				</ul>
			</li>
        
 <!--/////////////////////////////////////         <li class="<?php if($page_name == 'concession_get_all' )echo 'active';?> ">
				<a href="<?php echo base_url();?>accountant/concession_get_all">
					<i class="entypo-users"></i>
					<span><?php echo get_phrase('All Concession');?></span>
				</a>
           </li>
-->
<li class="<?php if($page_name == 'get_pending_chalan' )echo 'active';?> ">
				<a href="<?php echo base_url();?>accountant/get_pending_chalan">
					<i class="fa fa-clock-o" aria-hidden="true"></i>
<span><?php echo get_phrase('All pending chalan');?></span>
				</a>
           </li>
           
           
             <li class="<?php if($page_name == 'get_payed_chalan' )echo 'active';?> ">
				<a href="<?php echo base_url();?>accountant/get_payed_chalan">
<i class="fa fa-credit-card-alt" aria-hidden="true"></i>
<span><?php echo get_phrase('All Paid Chalan');?></span></a>
</li>



<li class="<?php if($page_name == 'chart_of_accounts' )echo 'active';?> ">
				<a href="<?php echo base_url();?>chart_of_account/coa_list">
<i class="fa fa-line-chart" aria-hidden="true"></i>
<span><?php echo get_phrase('Chart of Accounts');?></span></a>
</li>
  <li class="<?php if($page_name == 'account_transection' )echo 'active';?> ">
				<a href="<?php echo base_url();?>transection_account/account_transection">
<i class="fa fa-cc-mastercard" aria-hidden="true"></i>
<span><?php echo get_phrase('account_transection');?></span></a>
</li>

<li class="<?php if($page_name == 'chalan_account' )echo 'active';?> ">
<a href="<?php echo base_url();?>fee_types/fee_types_c">
<i class="fa fa-list-ol" aria-hidden="true"></i>
<span><?php echo get_phrase('Fee types');?></span></a>
</li>





  <li class="<?php if($page_name == 'discount_list' )echo 'active';?> ">
				<a href="<?php echo base_url();?>discount/discount_list">
	<i class="fa fa-certificate" aria-hidden="true"></i>
<span><?php echo get_phrase('discount_Types');?></span></a>
</li>



  <li class="<?php if($page_name == 'class_chalan_f' )echo 'active';?> ">
<a href="<?php echo base_url();?>class_chalan_form/class_chalan_f">
<i class="fa fa-file-text" aria-hidden="true"></i>
<span><?php echo get_phrase('class_chalan');?></span></a>
</li>












            <!-- TEACHER 
           <li class="<?php if($page_name == 'teacher' )echo 'active';?> ">
				<a href="<?php echo base_url();?>accountant/teacher">
					<i class="entypo-users"></i>
					<span><?php echo get_phrase('teacher');?></span>
				</a>
           </li>
            
           <!-- PARENT 
           <li class="<?php if($page_name == 'parent')echo 'opened active';?> ">
				<a href="<?php echo base_url();?>accountant/parent">
					<i class="entypo-user"></i>
					<span><?php echo get_phrase('parent');?></span>
				</a>
                <ul>
					<?php $classes	=	$this->db->get('class')->result_array();
                    foreach ($classes as $row):?>
                    <li class="<?php if ($page_name == 'parent' && $class_id == $row['class_id']) echo 'active';?>">
                        <a href="<?php echo base_url();?>accountant/parent/<?php echo $row['class_id'];?>">
                            <span><?php echo get_phrase('class');?> <?php echo $row['name'];?></span>
                        </a>
                    </li>
                    <?php endforeach;?>
                </ul>
           </li>
            
           <!-- CLASS 
           <li class="<?php if($page_name == 'class')echo 'active';?> ">
				<a href="<?php echo base_url();?>accountant/classes">
					<i class="entypo-flow-tree"></i>
					<span><?php echo get_phrase('class');?></span>
				</a>
                
           </li>
            
           <!-- SUBJECT 
           <li class="<?php if($page_name == 'subject')echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-docs"></i>
					<span><?php echo get_phrase('subject');?></span>
				</a>
              <ul>
                  <?php $classes	=	$this->db->get('class')->result_array();
                  foreach ($classes as $row):?>
                  <li class="<?php if ($page_name == 'subject' && $class_id == $row['class_id']) echo 'active';?>">
                      <a href="<?php echo base_url();?>accountant/subject/<?php echo $row['class_id'];?>">
                          <span><?php echo get_phrase('class');?> <?php echo $row['name'];?></span>
                      </a>
                  </li>
                  <?php endforeach;?>
              </ul>
           </li>
            
            
            
           <!-- Diary 
           <li class="<?php if($page_name == 'diary')echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-comment "></i>
					<span><?php echo get_phrase('manage_diary');?></span>
				</a>
              <ul>
                  <?php $classes	=	$this->db->get('class')->result_array();
                  foreach ($classes as $row):?>
                  <li class="<?php if ($page_name == 'diary' && $class_id == $row['class_id']) echo 'active';?>">
                      <a href="<?php echo base_url();?>accountant/diary/<?php echo $row['class_id'];?>">
                          <span><?php echo get_phrase('class');?> <?php echo $row['name'];?></span>
                      </a>
                  </li>
                  <?php endforeach;?>
              </ul>
           </li>
            
           <!-- CLASS ROUTINE 
           <li class="<?php if($page_name == 'class_routine')echo 'active';?> ">
				<a href="<?php echo base_url();?>accountant/class_routine">
					<i class="entypo-target"></i>
					<span><?php echo get_phrase('class_routine');?></span>
				</a>
           </li>
           
           <!-- Academic Session Management 
           <li class="<?php if($page_name == 'academic_session_management')echo 'active';?> ">
				<a href="<?php echo base_url();?>accountant/academic_session_management">
					<i class="entypo-calendar"></i>
					<span><?php echo get_phrase('academic_session_management');?></span>
				</a>
           </li>
           
           
           <!-- Academic Planner --
           <li class="<?php if($page_name == 'academic_planner')echo 'active';?> ">
				<a href="<?php echo base_url();?>accountant/academic_planner">
					<i class="entypo-calendar"></i>
					<span><?php echo get_phrase('academic_planner');?></span>
				</a>
           </li>
           
           <!-- DAILY ATTENDANCE 
           
           <li class="<?php if($page_name == 'manage_attendance' || $page_name == 'manage_attendance_teacher')echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-docs"></i>
					<span><?php echo get_phrase('daily_attendance');?></span>
				</a>
              <ul>
                         <li class="<?php if($page_name == 'manage_attendance')echo 'active';?> ">
                        <a href="<?php echo base_url();?>accountant/manage_attendance/<?php echo date("d/m/Y");?>">
                            <i class="entypo-progress-2"></i>
                            <span><?php echo get_phrase('daily_student_attendance');?></span>
                        </a>
                        
                   </li>
                    
                    <li class="<?php if($page_name == 'manage_attendance_teacher')echo 'active';?> ">
                        <a href="<?php echo base_url();?>accountant/manage_attendance_teacher/<?php echo date("d/m/Y");?>">
                            <i class="entypo-progress-2"></i>
                            <span><?php echo get_phrase('daily_teacher_attendance');?></span>
                        </a>
                        
                  		 </li>
                                 
              </ul>
           </li>
           
           <!-- DAILY ATTENDANCE --
           <li class="<?php if($page_name == 'manage_leaves' ||$page_name == 'manage_leaves_student' || $page_name == 'manage_leaves_teacher')echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-progress-2"></i>
					<span><?php echo get_phrase('manage_leaves');?></span>
				</a>
              <ul>
                   <li class="<?php if($page_name == 'manage_leaves')echo 'active';?> ">
                        <a href="<?php echo base_url();?>accountant/manage_leaves">
                            <i class="entypo-dot"></i>
                            <span><?php echo get_phrase('add_leave_category');?></span>
                        </a>
                   </li>
                   
                   <li class="<?php if($page_name == 'manage_leaves_student')echo 'active';?> ">
                        <a href="<?php echo base_url();?>accountant/manage_leaves_student">
                            <i class="entypo-dot"></i>
                            <span><?php echo get_phrase('student_leaves');?></span>
                        </a>
                        
                   </li>
                   <li class="<?php if($page_name == 'manage_leaves_teacher')echo 'active';?> ">
                        <a href="<?php echo base_url();?>accountant/manage_leaves_teacher">
                            <i class="entypo-dot"></i>
                            <span><?php echo get_phrase('teacher_leaves');?></span>
                        </a>
				   </li>
              </ul>
           </li>
           
           
           
           
           <!-- EXAMS --
           <li class="<?php if($page_name == 'exam' ||
		   								$page_name == 'grade' ||
												$page_name == 'marks' ||
														$page_name == 'exam_routine')echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-graduation-cap"></i>
					<span><?php echo get_phrase('exam');?></span>
				</a>
                <ul>
					<li class="<?php if($page_name == 'exam')echo 'active';?> ">
						<a href="<?php echo base_url();?>accountant/exam">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('exam_list');?></span>
						</a>
					</li>
					<li class="<?php if($page_name == 'grade')echo 'active';?> ">
						<a href="<?php echo base_url();?>accountant/grade">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('exam_grades');?></span>
						</a>
					</li>
					<li class="<?php if($page_name == 'marks')echo 'active';?> ">
						<a href="<?php echo base_url();?>accountant/marks">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('manage_marks');?></span>
						</a>
					</li>
                    <li class="<?php if($page_name == 'exam_routine')echo 'active';?> ">
						<a href="<?php echo base_url();?>accountant/exam_routine">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('manage_exam_routine');?></span>
						</a>
					</li>
                </ul>
           </li>
            
           <!-- PAYMENT --
           <li class="<?php if($page_name == 'invoice')echo 'active';?> ">
				<a href="<?php echo base_url();?>accountant/invoice">
					<i class="entypo-credit-card"></i>
					<span><?php echo get_phrase('payment');?></span>
				</a>
           </li>
            
           <!-- LIBRARY --
           <li class="<?php if($page_name == 'book' ||
		   							$page_name == 'book_issue' ||
										$page_name == 'book_category' ||
											$page_name == 'book_request'||
												$page_name == 'library')echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-book"></i>
					<span><?php echo get_phrase('library');?></span>
				</a>
                <ul>
                    <li class="<?php if($page_name == 'library')echo 'active';?> ">
                                    <a href="<?php echo base_url();?>accountant/librarian">
                                        <i class="entypo-dot"></i>
                                        <span><?php echo get_phrase('add_librarian');?></span>
                                    </a>
					</li>
                    <li class="<?php if($page_name == 'book')echo 'active';?> ">
                                    <a href="<?php echo base_url();?>accountant/book">
                                        <i class="entypo-dot"></i>
                                        <span><?php echo get_phrase('all_books');?></span>
                                    </a>
					</li>
                    <li class="<?php if($page_name == 'book_category')echo 'active';?> ">
                                    <a href="<?php echo base_url();?>accountant/book_category">
                                        <i class="entypo-dot"></i>
                                        <span><?php echo get_phrase('add_category');?></span>
                                    </a>
					</li>
                     <li class="<?php if($page_name == 'book_request')echo 'active';?> ">
                                    <a href="<?php echo base_url();?>accountant/book_request">
                                        <i class="entypo-dot"></i>
                                        <span><?php echo get_phrase('book_requests');?></span>
                                    </a>
					</li>
                     <li class="<?php if($page_name == 'book_issue')echo 'active';?> ">
                                    <a href="<?php echo base_url();?>accountant/book_issue">
                                        <i class="entypo-dot"></i>
                                        <span><?php echo get_phrase('issued_books');?></span>
                                    </a>
					</li>
                </ul>
           </li>

           <!-- TRANSPORT --
           
            <li class="<?php if($page_name == 'transport' || $page_name == 'transport_driver')echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-location"></i>
					<span><?php echo get_phrase('manage_transport');?></span>
				</a>
                <ul>
					<li class="<?php if($page_name == 'transport')echo 'active';?> ">
						<a href="<?php echo base_url();?>accountant/transport">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('transport_routes');?></span>
						</a>
					</li>
					<li class="<?php if($page_name == 'transport_driver')echo 'active';?> ">
						<a href="<?php echo base_url();?>accountant/transport_driver">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('transport_driver');?></span>
						</a>
					</li>
				</ul>
           </li>
           
           
           <!--<li class="<?php if($page_name == 'transport')echo 'active';?> ">
				<a href="<?php echo base_url();?>accountant/transport">
					<i class="entypo-location"></i>
					<span><?php echo get_phrase('transport');?></span>
				</a>
           </li>-->
            
           <!-- DORMITORY --
           <li class="<?php if($page_name == 'dormitory')echo 'active';?> ">
				<a href="<?php echo base_url();?>accountant/dormitory">
					<i class="entypo-home"></i>
					<span><?php echo get_phrase('dormitory');?></span>
				</a>
           </li>

           <!-- NOTICEBOARD --
          <li class="<?php if($page_name == 'noticeboard' || $page_name == 'circulars')echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-doc-text-inv"></i>
					<span><?php echo get_phrase('notices');?></span>
				</a>
              <ul>
                    <li class="<?php if($page_name == 'noticeboard')echo 'active';?> ">
                    <a href="<?php echo base_url();?>accountant/noticeboard">
                    <i class="entypo-dot"></i>
                    <span><?php echo get_phrase('noticeboard');?></span>
                    </a>
                    </li>
            
                    <li class="<?php if($page_name == 'circulars')echo 'active';?> ">
                    <a href="<?php echo base_url();?>accountant/circulars">
                    <i class="entypo-dot"></i>
                    <span><?php echo get_phrase('circulars');?></span>
                    </a>
                    </li>

              </ul>
           </li>
           <!-- SETTINGS --
           <li class="<?php if($page_name == 'system_settings' ||
		   								$page_name == 'manage_language')echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-lifebuoy"></i>
					<span><?php echo get_phrase('settings');?></span>
				</a>
                <ul>
					<li class="<?php if($page_name == 'system_settings')echo 'active';?> ">
						<a href="<?php echo base_url();?>accountant/system_settings">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('general_settings');?></span>
						</a>
					</li>
					<li class="<?php if($page_name == 'manage_language')echo 'active';?> ">
						<a href="<?php echo base_url();?>accountant/manage_language">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('language_settings');?></span>
						</a>
					</li>
                </ul>
           </li>
            
           <!-- ACCOUNT --
           <li class="<?php if($page_name == 'manage_profile')echo 'active';?> ">
				<a href="<?php echo base_url();?>accountant/manage_profile">
					<i class="entypo-lock"></i>
					<span><?php echo get_phrase('account');?></span>
				</a>
           </li>
         -->
                
         

           
		</ul>
        		
</div>