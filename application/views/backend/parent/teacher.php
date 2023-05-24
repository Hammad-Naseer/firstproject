<style>
.mia{background-color:#2a648d!important;padding:7px;border-radius:5px;color:#f4f4f4;font-weight:700}.mia:hover{background-color:#065d85!important;padding:7px;border-radius:5px;color:#fff;font-weight:700}.view_message{border:1px solid #e4e4e4;min-height:100px;padding:20px;float:left;margin-bottom:20px;margin-right:20px}.sub_details{margin-top:15px;font-size:14px}.teacher_icon{width:70px;height:80px;float:left;margin-right:20px}.chat_teacher_subjects{background:#012b3c;padding:10px;color:#fff;border-radius:40px;margin-bottom:10px}.chat_send{background:#02658d;color:#fff;padding:5px;border-radius:30px;position:relative;left:50%;top:-10px;border:3px solid #fff}.chat_unread_messegs{background:#02658d;color:#fff;padding:5px;border-radius:30px;position:relative;top:10px;border:1px solid #fff}





@media only screen and (min-width: 576px)  {
.view_message {
        width: 46%;
}   
}
@media only screen and (max-width: 575px)  {
.view_message {
        width: 100%;
}   
}

</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('Messages'); ?>
        </h3>
    </div>
</div>
<div class="row">
    
    <?php
        $section_id = $_SESSION['section_id'];
        $school_id = $_SESSION['school_id'];
        $yearly_term_id = $_SESSION['yearly_term_id'];
      
    
        $sub_arr = $this->db->query("select sub.* from ".get_school_db().".subject sub inner join ".get_school_db().".subject_section ss on sub.subject_id=ss.subject_id where ss.section_id = ".$section_id." and ss.school_id = ".$school_id." ")->result_array();
    ?>

    <div class="col-lg-12 col-md-12 col-sm-12" data-step="1" data-position='top' data-intro="all messages record">  
        <div class="message_box">
            <?php 
            $student_id= $_SESSION['student_id'];
            $school_id=$_SESSION['school_id'];
            $teacher_arr1 = $this->db->query("select staff.staff_id as teacher_id, staff.user_login_detail_id as user_id,staff.name, staff.staff_image as teacher_image from " . get_school_db() . ".class_routine cr inner join " . get_school_db() . ".class_routine_settings crs on crs.c_rout_sett_id=cr.c_rout_sett_id and crs.section_id = $section_id inner join " . get_school_db() . ".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id inner join " . get_school_db() . ".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id inner join " . get_school_db() . ".staff on staff.staff_id = st.teacher_id where staff.school_id = " . $_SESSION['school_id'] . " GROUP BY staff.name ")->result_array();
            foreach($teacher_arr1 as $teacher_arr){
                $teacher_id = $teacher_arr['teacher_id'];
                
            ?>
            
            <?php //$teacher_arr = get_subject_time_table_teacher($section_id,$row['subject_id'],$yearly_term_id); ?>
                <div class="view_message" style="background-color:white">
                    <div class="teacher_icon">
                        <?php if($teacher_arr['teacher_image']==""){ ?>
                        <img src="<?php echo get_default_pic();?>" width="70" height="70"/>
                        <?php }else{ ?>
                        <img src="<?php echo display_link($teacher_arr['teacher_image'],'staff');?>" width="70" height="70" />
                        <?php } ?>
                    </div>
                    <div class="sub_details">
                        <div>
                            <strong><?php echo $teacher_arr['name'];?></strong>
                        </div>
                    </div>
                    <hr style="margin-top: 50px;margin-bottom: -10px;">
                    <br>
                        <h5><b>Subject</b></h5>
                        <!--<div class="chat_teacher_subjects">-->
                            <?php
                                $user_login_detail_id = $teacher_arr['user_id'];
                                $query = "select s.* FROM 
                        		".get_school_db().".class_routine cr 
                                    inner join ".get_school_db().".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
                                    inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
                                    inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
                                    inner join ".get_school_db().".subject s on s.subject_id=st.subject_id
                                    inner join ".get_school_db().".staff staff on staff.staff_id=st.teacher_id
                                    inner join ".get_school_db().".subject_section SS on SS.subject_id = st.subject_id
                                    inner join ".get_school_db().".class_section cs on cs.section_id = crs.section_id
                                    inner join ".get_school_db().".class on class.class_id = cs.class_id
                                    inner join ".get_school_db().".departments d on d.departments_id = class.departments_id
                                    where 
                                    staff.user_login_detail_id = $user_login_detail_id
                                    and cr.school_id=".$_SESSION['school_id']."
                                    and crs.section_id = $section_id
                                    group by s.subject_id
                                    ";
                         
                        	$subject_arr = $this->db->query($query)->result_array();
                  //  echo "<pre>";   print_r($subject_arr);
                            
                        	foreach($subject_arr as $row)
                        	{

                        	    // Unread Messages
                                $unread_arr = $this->db->query("select count(*) as unread
                                            from ".get_school_db().".messages
                                            WHERE student_id = ".$student_id."
                                            and school_id=".$_SESSION['school_id']."
                                            and messages_type = 0
                                            and is_viewed = 0 
                                            and teacher_id = '$teacher_id'
                                            and subject_id = ".$row['subject_id']."
                                            ")->row();
                                            $subject_id = $row['subject_id'];
                                           //	print_r($subject_id);
                                $unread_msgs = $unread_arr->unread;
                                 
                                
                                if($unread_msgs > 0 )
                                {
                                    $unread_counters = '<span class="badge badge-light float-right">'.$unread_msgs.'</span>';
                                    	echo '<div class="chat_teacher_subjects">' .$row['name'].' - '.$row['code'].' ' .$unread_counters. '</div>
                        		    <a class="chat_send" href="'.base_url().'parents/message/'.intval($teacher_arr['teacher_id']).'/'.$row['subject_id'].'">
                                        <span>View / Send Messages</span>
                                    </a>';
                                    
                                }
                                	echo '<div class="chat_teacher_subjects">' .$row['name'].' - '.$row['code'].'</div>
                        		    <a class="chat_send" href="'.base_url().'parents/message/'.intval($teacher_arr['teacher_id']).'/'.$row['subject_id'].'">
                                        <span>View / Send Messages</span>
                                    </a>';
                        	
                        	}
                            ?>
                        <!--</div>-->
                </div>
                <?php }?>
        </div>

</div>
</div>
