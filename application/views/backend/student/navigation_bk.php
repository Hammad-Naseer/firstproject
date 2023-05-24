<?php
/*echo "<pre>"; 
print_r($_SESSION);*/
?>

           
<style>
	
	
	.mynavimg{
	height: 17px;
    display: inline;
    margin-top: -4px;
	}
	.mynavicon{
		
	}
	.panel-danger > .panel-heading {
		
		
		
	color: #ffffff;
    background-color: #29638d;
    border-color: #29638d; 
		
		
	}
	
	.panel-heading > .panel-title{
		
		
		
		color:#FFF !important;
	}
	.fa fa-bell-o{color:#FFF !important;}
	
	.panel-danger {
    border-color: #29638d !important;
}
	.student_details{
		background-color:#f6f6f6;
		color:#FFF;
		width:100%;
		height:auto;
		padding:20px 0px;
		display:inline-block;
	}	
	
	.student_details li{    list-style: none; border-bottom:none !important;}
	.userpic{    
		height:70px;
    	width:70px;
 		border:2px solid #4a8cbb;;
	}
	
/* make sidebar nav vertical */ 
@media (min-width: 768px) {
	
  .sidebar-nav .navbar .navbar-collapse {
    padding: 0;
    max-height: none;
  }
  .sidebar-nav .navbar ul {
    float: none;
    display: block;
  }
  .sidebar-nav .navbar li {
    float: none;
    display: block;
  }
  .sidebar-nav .navbar li a {
    padding-top: 12px;
    padding-bottom: 12px;
  }
  .navbar-default {
    background-color: #fff;
    /*border-right: 1px solid #aaabae !important;*/
    border-bottom: 0;
    border-left: 0;
}
.page-container .sidebar-menu #main-menu{ margin-bottom:0px;}



}	
	
	
	
	
</style>
<div class=""  id="main-menu">
<div class="">
  <div class="">
    <div class="sidebar-nav">
      <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <span class="visible-xs navbar-brand"><strong></strong></span>
        </div>
        <div class="navbar-collapse collapse sidebar-navbar-collapse">
          
        <ul id="main-menu" class="coll nav navbar-nav">

			<!-- add class "multiple-expanded" to allow multiple submenus to open -->
			<!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
            
           
           <!-- DASHBOARD -->
         <div class="student_details"> 
            	
            	
            	
            <div class="col-sm-4">
  
            	<?php if($_SESSION['login_type'] == 6)
				{
					if ($_SESSION['student_image'] != "")
					{
					?>	
            			<img src="<?php echo display_link($_SESSION['student_image'],'student') ?>" alt="..." style="height:70px; width:70px;  border-radius: 35px;">
            		<?php
            		}
            		else
            		{?>
						<img src="<?php echo get_default_pic(); ?>" alt="..." style="height:70px; width:70px;  border-radius: 35px;">
            		<?php
            		}

				}	

				?>			
            </div>
            
            
            <div class="col-sm-8">
            <?php if($_SESSION['login_type'] == 6)
            {
            	
            ?>			
            												
                <div  style="color:#000 !important; padding-left: 12px; text-transform: capitalize; font-size:14px;">
                <div style="font-weight:bold;"><?php echo $_SESSION['student_name'] ; ?></div>
                <div style="text-indent:5px; font-size:12px;" ><?php echo $qr[0]['department_name']; ?></div>
                <div style="text-indent:10px; font-size:12px;"><?php  echo $qr[0]['class_name'] ; ?></div>
                <div style="text-indent:15px; font-size:12px;"><?php echo $qr[0]['section_name'] ; ?></div>
                </div>	
                
            <?php } ?>
            </div>
            
            </div>
              
           <li class="<?php if($page_name == 'dashboard')echo 'active';?> ">
				<a href="<?php echo base_url();?>parents/dashboard">
				
				<span class="mynavicon">
					<img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/dashboard.png">
					</span>
					
					
					
					<span><?php echo get_phrase('dashboard');?></span>
				</a>
           </li>

 <li class="<?php if($page_name == 'policies_listing' || $page_name == 'policies_add_edit' )echo 'active';?> ">
	                <a href="<?php echo base_url();?>parents/policies_listing">
	                    <span class="mynavicon"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/po.png"></span>
	                    <?php echo get_phrase('school_policies');?>
	                </a>
	            </li>

<li class="<?php if($page_name == 'diary')echo 'active';?> ">
				<a href="<?php echo base_url();?>parents/diary">
					<span class="mynavicon"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/diary.png"></span>
					<span><?php echo get_phrase('class_diary');?></span>
				</a>
           </li>
   
    <!-- NOTICEBOARD --> 
           <li class="<?php if($page_name == 'noticeboard' || $page_name == 'circulars')echo 'opened active';?> ">
				<a href="#">
					<!--<i class="entypo-doc-text-inv"></i>-->
					   <span class="mynavicon"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/notceboard.png"></span>
					<span><?php echo get_phrase('notices_and_circulars');?></span>
				</a>
              <ul>
                    <li class="<?php if($page_name == 'noticeboard')echo 'active';?> ">
                    <a href="<?php echo base_url();?>parents/noticeboard">
                    <span class="mynavicon"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/notice-board.png"></span>
                    <span><?php echo get_phrase('noticeboard');?></span>
                    </a>
                    </li>
            
                    <li class="<?php if($page_name == 'circulars')echo 'active';?> ">
                    <a href="<?php echo base_url();?>parents/circulars">
                   <span class="mynavicon"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/circular.png"></span>
                    <span><?php echo get_phrase('circulars');?></span>
                    </a>
                    </li>

              </ul>
           </li>
<!-- daily attendane -->
			 <li class="<?php if($page_name == 'manage_attendance' || $page_name == 'circulars')echo 'opened active';?> ">
				<a href="#">
					<!--<i class="entypo-doc-text-inv"></i>-->
					   <span class="mynavicon"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/results.png"></span>
					<span><?php echo get_phrase('Attendance');?></span>
				</a>
              <ul>
               <li class="<?php if($page_name == 'daily attandane' || $page_name == 'manage_attendance' )echo 'active';?> ">
<a href="<?php echo base_url();?>parents/manage_attendance">
	<span class="mynavicon"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/attendance.png"></span>
					
					<span><?php echo get_phrase('details');?></span>
				</a>
           </li>
                    <li class="<?php if($page_name == 'summary')echo 'active';?> ">
                    <a href="<?php echo base_url();?>parents/summary">
                    <span class="mynavicon"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/exams.png"></span>
                    <span><?php echo get_phrase('attendance_summary');?></span>
                    </a>
                    </li>
            
                    

              </ul>
           </li>


           

           <li class="<?php if($page_name == 'class_routine')echo 'active';?> ">
				<a href="<?php echo base_url();?>parents/class_routine">
				<span class="mynavicon"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/time-table-setting-icon.png"></span>
					<span><?php echo get_phrase('time_table');?></span>
				</a>
           </li>
       <!-- message -->
    <!--       <li class="<?php if($page_name == 'message' || $page_name=='teacher')echo 'active';?> ">-->
				<!--<a href="<?php echo base_url();?>parents/teacher_list">-->
				<!--<span class="mynavicon"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/message.png"></span>-->
				<!--	<span><?php echo get_phrase('message');?></span>-->
				<!--</a>-->
    <!--       </li>-->
           <!-- PAYMENT -->
    <!--       <li class="<?php if($page_name == 'invoice')echo 'active';?> ">-->
				<!--<a href="<?php echo base_url();?>parents/invoice">-->
				<!--	<span class="mynavicon">-->
				<!--	<img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/payment.png">-->
				<!--	</span>-->
				<!--	<span><?php echo get_phrase('payment');?></span>-->
				<!--</a>-->
    <!--       </li>-->
           <!-- EXAMS -->
           <li class="<?php if($page_name == 'exam' 
							   || $page_name == 'exam_routine'
							   || $page_name =='marks')echo 'opened active';?> ">
				<a href="#">
							<span class="mynavicon"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/exams.png"></span>
					<span><?php echo get_phrase('exams');?></span>
				</a>
                <ul>
					<li class="<?php if($page_name == 'exam_routine')echo 'active';?> ">
						<a href="<?php echo base_url();?>parents/exam_routine">
						
							
							 <span class="mynavicon"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/datesheet.jpeg"></span>
							
							  <?php echo get_phrase('date_sheet');?>
						</a>
					</li>
					<li class="<?php if($page_name == 'marks')echo 'active';?> ">
						<a href="<?php echo base_url();?>parents/marks">
						
							
							 <span class="mynavicon"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/results.png"></span>
							
							  <?php echo get_phrase('examination_result');?>
							  
							  
							
						</a>
					</li>
                    
                </ul>
           </li>
           
           
           
           
           
           <!-- Leave Requests -->
    <!--       <li class="<?php if($page_name == 'leave_request')echo 'active';?> ">-->
				<!--<a href="<?php echo base_url();?>parents/manage_leaves">-->
				<!--	 <span class="mynavicon">-->
				<!--	 <img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/leave3.png">-->
				<!--	 </span>-->
				<!--	<span><?php echo get_phrase('leave_request');?></span>-->
				<!--</a>-->
                
    <!--       </li>-->
           
           <li class="<?php if($page_name == 'manage_profile')echo 'active';?> ">
				<a href="<?php echo base_url();?>parents/manage_profile">
					 <span class="mynavicon">
					 <img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/profile-setting-2.png">
					 </span>
					<span><?php echo get_phrase('my_profile');?></span>
				</a>
                
           </li>
           
           
           
           
           

           
           
           
           
           
           
   
           
           
           
           
           
           
           
           
           
                 
           
</ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
  </div>
</div>
</div>



















