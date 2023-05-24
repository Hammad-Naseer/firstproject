<div class="sidebar-menu">
		
		
		
		
		
		
		
		<header >
		
		
	<div class="logodiv">
		
	<img src="uploads/old_logo.png" class="logo_img">
		
		<p class="logo_text">School Management</p>
		
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
				<a href="<?php echo base_url();?><?php echo $account_type;?>/dashboard">
					<i class="entypo-gauge"></i>
					<span><?php echo get_phrase('dashboard');?></span>
				</a>
           </li>
           
           <!-- STUDENT -->
           
           <li class="<?php if($page_name == 'student_information')echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-users"></i>
					<span><?php echo get_phrase('student_information');?></span>
				</a>
                <ul>
                    <?php $classes	=	$this->db->get('class')->result_array();
                    foreach ($classes as $row):?>
                    <li class="<?php if ($page_name == 'student_information' && $class_id == $row['class_id']) echo 'active';?>">
                        <a href="<?php echo base_url();?><?php echo $account_type;?>/student_information/<?php echo $row['class_id'];?>">
                            <span><?php echo get_phrase('class');?> <?php echo $row['name'];?></span>
                        </a>
                    </li>
                    <?php endforeach;?>
                </ul>
           </li>
           
           <!-- PRINCIPALS -->
           <li class="<?php if($page_name == 'principal' )echo 'active';?> ">
				<a href="<?php echo base_url();?><?php echo $account_type;?>/principal_list">
					<i class="entypo-users"></i>
					<span><?php echo get_phrase('principals');?></span>
				</a>
           </li>
           
           <!-- TEACHER -->
           <li class="<?php if($page_name == 'teacher' )echo 'active';?> ">
				<a href="<?php echo base_url();?><?php echo $account_type;?>/teacher_list">
					<i class="entypo-users"></i>
					<span><?php echo get_phrase('teacher');?></span>
				</a>
           </li>
           
           <!-- LIBRARIAN -->
           <li class="<?php if($page_name == 'library' )echo 'active';?> ">
				<a href="<?php echo base_url();?><?php echo $account_type;?>/library_list">
					<i class="entypo-users"></i>
					<span><?php echo get_phrase('librarians');?></span>
				</a>
           </li>
          
          <!-- LIBRARY -->
           <li class="<?php if($page_name == 'book' ||
		   							$page_name == 'book_issue' ||
										$page_name == 'book_category' ||
											$page_name == 'book_request' )echo 'opened active';?> ">
				<a href="#">
					<i class="entypo-book"></i>
					<span><?php echo get_phrase('manage_library');?></span>
				</a>
                <ul>
                    <li class="<?php if($page_name == 'book')echo 'active';?> ">
                                    <a href="<?php echo base_url();?><?php echo $account_type;?>/book">
                                        <i class="entypo-dot"></i>
                                        <span><?php echo get_phrase('all_books');?></span>
                                    </a>
					</li>
                    <li class="<?php if($page_name == 'book_category')echo 'active';?> ">
                                    <a href="<?php echo base_url();?><?php echo $account_type;?>/book_category">
                                        <i class="entypo-dot"></i>
                                        <span><?php echo get_phrase('add_category');?></span>
                                    </a>
					</li>
                     <li class="<?php if($page_name == 'book_request')echo 'active';?> ">
                                    <a href="<?php echo base_url();?><?php echo $account_type;?>/book_request">
                                        <i class="entypo-dot"></i>
                                        <span><?php echo get_phrase('book_requests');?></span>
                                    </a>
					</li>
                     <li class="<?php if($page_name == 'book_issue')echo 'active';?> ">
                                    <a href="<?php echo base_url();?><?php echo $account_type;?>/book_issue">
                                        <i class="entypo-dot"></i>
                                        <span><?php echo get_phrase('issued_books');?></span>
                                    </a>
					</li>
                </ul>
           </li>

            
           <!-- TRANSPORT -->
           <li class="<?php if($page_name == 'transport')echo 'active';?> ">
				<a href="<?php echo base_url();?><?php echo $account_type;?>/transport">
					<i class="entypo-location"></i>
					<span><?php echo get_phrase('transport');?></span>
				</a>
           </li>
            
           <!-- NOTICEBOARD -->
            <li class="<?php if($page_name == 'noticeboard')echo 'active';?> ">
	            <a href="<?php echo base_url();?><?php echo $account_type;?>/noticeboard">
    	        <i class="entypo-doc-text-inv"></i>
        	    <span><?php echo get_phrase('noticeboard');?></span>
            	</a>
            </li>

           <!-- ACCOUNT -->
           <li class="<?php if($page_name == 'manage_profile')echo 'active';?> ">
				<a href="<?php echo base_url();?><?php echo $account_type;?>/manage_profile">
					<i class="entypo-lock"></i>
					<span><?php echo get_phrase('account');?></span>
				</a>
           </li>
		</ul>
</div>