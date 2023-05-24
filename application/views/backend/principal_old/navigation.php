<div class="sidebar-menu">
		
		
		
		
		
		
		
		<header >
		
		
	<div class="logodiv">
		
		<img src="uploads/old_logo.png" class="logo_img">
		
		
		
		</div>
		
		
		
		
			<div>
			<?php 
$qurr=$this->db->query('select * from settings where type="logo"')->result_array();

$logo= $qurr[0]['description'];

    
     ?>
			<center>
				<a href="<?php echo base_url();?>"><img src="uploads/<?php echo $logo; ?>" class="logoz">
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
				<a href="<?php echo base_url();?>index.php?principal/dashboard">
					<i class="entypo-gauge"></i>
					<span><?php echo get_phrase('dashboard');?></span>
				</a>
           </li>
           
           
            
           <!-- TEACHER -->
           <li class="<?php if($page_name == 'teacher' )echo 'active';?> ">
				<a href="<?php echo base_url();?>index.php?principal/teacher_list">
					<i class="entypo-users"></i>
					<span><?php echo get_phrase('teacher');?></span>
				</a>
           </li>
            
            
           <!-- CLASS ROUTINE -->
           <li class="<?php if($page_name == 'class_routine')echo 'active';?> ">
				<a href="<?php echo base_url();?>index.php?principal/class_routine">
					<i class="entypo-target"></i>
					<span><?php echo get_phrase('class_routine');?></span>
				</a>
           </li>
           
                     
           
           <!-- Academic Planner -->
           <?php
           /*
           <li class="<?php if($page_name == 'academic_planner')echo 'active';?> ">
				<a href="<?php echo base_url();?>index.php?principal/academic_planner">
					<i class="entypo-calendar"></i>
					<span><?php echo get_phrase('academic_planner');?></span>
				</a>
           </li>
           */
           ?>
            
           <!-- EXAMS -->
           <li class="<?php if($page_name == 'exam' ||
		   								$page_name == 'exam_routine')echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-graduation-cap"></i>
					<span><?php echo get_phrase('exam');?></span>
				</a>
                <ul>
					
					<li class="<?php if($page_name == 'marks')echo 'active';?> ">
						<a href="<?php echo base_url();?>index.php?principal/marks">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('manage_marks');?></span>
						</a>
					</li>
                    <li class="<?php if($page_name == 'exam_routine')echo 'active';?> ">
						<a href="<?php echo base_url();?>index.php?principal/exam_routine">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('manage_exam_routine');?></span>
						</a>
					</li>
                </ul>
           </li>
           <!-- EXAMS -->
           <li class="<?php if($page_name == 'manage_attendance' || $page_name == 'manage_attendance_teacher' ) echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-clipboard"></i>
					<span><?php echo get_phrase('Daily Attendence');?></span>
				</a>
                <ul>
					
					<li class="<?php if($page_name == 'manage_attendance')echo 'active';?> ">
						<a href="<?php echo base_url();?>index.php?principal/manage_attendance/<?php echo date("d/m/Y");?>">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('student_attendence');?></span>
						</a>
					</li>
                    
                    <li class="<?php if($page_name == 'manage_attendance_teacher')echo 'active';?> ">
						<a href="<?php echo base_url();?>index.php?principal/manage_attendance_teacher/<?php echo date("d/m/Y");?>">
							<span><i class="entypo-dot"></i> <?php echo get_phrase('teacher_attendence');?></span>
						</a>
					</li>
                </ul>
           </li>
           
           <!-- PAYMENT -->
           <?php /*
           <li class="<?php if($page_name == 'invoice')echo 'active';?> ">
				<a href="<?php echo base_url();?>index.php?principal/invoice">
					<i class="entypo-credit-card"></i>
					<span><?php echo get_phrase('payment');?></span>
				</a>
           </li>
           */
           ?>
            
            
           <!-- LIBRARY -->
           <?php
           /*
           <li class="<?php if($page_name == 'book' || $page_name == 'book_issue' || $page_name == 'book_request' )echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-book"></i>
					<span><?php echo get_phrase('library');?></span>
				</a>
                <ul>
                    <li class="<?php if($page_name == 'book')echo 'active';?> ">
                        <a href="<?php echo base_url();?>index.php?principal/book">
                            <i class="entypo-dot"></i>
                            <span><?php echo get_phrase('all_books');?></span>
                        </a>
                    </li>
                    <li class="<?php if($page_name == 'book_request')echo 'active';?> ">
                        <a href="<?php echo base_url();?>index.php?principal/book_request">
                            <i class="entypo-dot"></i>
                            <span><?php echo get_phrase('book_requests');?></span>
                        </a>
					</li>
                    <li class="<?php if($page_name == 'book_issue')echo 'active';?> ">
                        <a href="<?php echo base_url();?>index.php?principal/book_issue">
                            <i class="entypo-dot"></i>
                            <span><?php echo get_phrase('issued_books');?></span>
                        </a>
					</li>
                </ul>
           </li>
           */
           ?>
 
           <!-- TRANSPORT -->
           <?php
           /*
           <li class="<?php if($page_name == 'transport')echo 'active';?> ">
				<a href="<?php echo base_url();?>index.php?principal/transport">
					<i class="entypo-location"></i>
					<span><?php echo get_phrase('transport');?></span>
				</a>
           </li>
           */
           ?>
            
           <!-- NOTICEBOARD -->
          <li class="<?php if($page_name == 'noticeboard' || $page_name == 'circulars')echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-doc-text-inv"></i>
					<span><?php echo get_phrase('notices');?></span>
				</a>
              <ul>
                    <li class="<?php if($page_name == 'noticeboard')echo 'active';?> ">
                    <a href="<?php echo base_url();?>index.php?principal/noticeboard">
                    <i class="entypo-dot"></i>
                    <span><?php echo get_phrase('noticeboard');?></span>
                    </a>
                    </li>
            
                    <li class="<?php if($page_name == 'circulars')echo 'active';?> ">
                    <a href="<?php echo base_url();?>index.php?principal/circulars">
                    <i class="entypo-dot"></i>
                    <span><?php echo get_phrase('circulars');?></span>
                    </a>
                    </li>

              </ul>
           </li>
            
            
           <!-- ACCOUNT -->
           <li class="<?php if($page_name == 'manage_profile')echo 'active';?> ">
				<a href="<?php echo base_url();?>index.php?principal/manage_profile">
					<i class="entypo-lock"></i>
					<span><?php echo get_phrase('account');?></span>
				</a>
           </li>
                
           
           
		</ul>
        		
</div>