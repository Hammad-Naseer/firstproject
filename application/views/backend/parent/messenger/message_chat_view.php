<link rel="stylesheet" href="<?=base_url()?>assets/css/messenger.css">
	<div class="container">
				<div class="row">
					<div class="col-lg-4">
					      <h3> Teacher/Subject</h3>
					     
					    <?php
					     $teacher_arr = get_staff_detail($teachers_id);
					  //  echo "<pre>"; print_r($_SESSION);
					     //  print_r($_SESSION);
					       // $student_id= $_SESSION['student_id'];
            //                 $school_id=$_SESSION['school_id'];
            //                 $section_id = $_SESSION['section_id'];
            //                 $teacher_arr1 = $this->db->query("select staff.staff_id as teacher_id, crs.section_id, staff.user_login_detail_id as user_id,staff.name,st.teacher_id, staff.staff_image as teacher_image
            //                 from " . get_school_db() . ".class_routine cr inner join " . get_school_db() . ".class_routine_settings crs on crs.c_rout_sett_id=cr.c_rout_sett_id and crs.section_id = $section_id
            //                 inner join " . get_school_db() . ".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
            //                 inner join " . get_school_db() . ".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
            //                 inner join " . get_school_db() . ".staff on staff.staff_id = st.teacher_id
            //                 where staff.school_id = " . $_SESSION['school_id'] . " 
            //                 GROUP BY staff.name ")->result_array();
                          
            //                  foreach($teacher_arr1 as $teacher_arr){
            //                  $teacher_id = $teacher_arr['teacher_id'];
                               
            //                  $login_detail_id = $teacher_arr['user_id'];
            //                  $section_id = $teacher_arr['section_id'];
            //                  }
					       //     $user_login_detail_id = $teacher_arr['user_id'];
            //                     $query = "select s.* FROM 
            //             		".get_school_db().".class_routine cr 
            //                         inner join ".get_school_db().".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
            //                         inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
            //                         inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
            //                         inner join ".get_school_db().".subject s on s.subject_id=st.subject_id
            //                         inner join ".get_school_db().".staff staff on staff.staff_id=st.teacher_id
            //                         inner join ".get_school_db().".subject_section SS on SS.subject_id = st.subject_id
            //                         inner join ".get_school_db().".class_section cs on cs.section_id = crs.section_id
                                   
            //                         inner join ".get_school_db().".class on class.class_id = cs.class_id
            //                         inner join ".get_school_db().".departments d on d.departments_id = class.departments_id
            //                         where 
            //                         staff.staff_id  = $teachers_id
            //                         and cr.school_id=".$_SESSION['school_id']."
            //                         and crs.section_id = $section_id
            //                         group by s.subject_id
            //                         ";
            //             	$subject_arr = $this->db->query($query)->result_array();
            //             		foreach($subject_arr as $row)
            //             	{

            //             	    // Unread Messages
            //                     $unread_arr = $this->db->query("select count(*) as unread
            //                                 from ".get_school_db().".messages
            //                                 WHERE student_id = ".$student_id."
            //                                 and school_id=".$_SESSION['school_id']."
            //                                 and messages_type = 0
            //                                 and is_viewed = 0 
            //                                 and teacher_id = '$teacher_id'
            //                                 and subject_id = ".$row['subject_id']."
            //                                 ")->row();
            //                                 $subject_id = $row['subject_id'];
                                           	
            //                     $unread_msgs = $unread_arr->unread;
                                 
                                
            //                     if($unread_msgs > 0 )
            //                     {
            //                         $unread_counters = '<span class="badge badge-light float-right">'.$unread_msgs.'</span>';
            //                         	echo '<div class="chat_teacher_subjects">' .$row['name'].' - '.$row['code'].' ' .$unread_counters. '</div>
                                    	
            //             		    ';
            //                     }
            //                     	echo '<a class="chat_teacher_subjects" href="'.base_url().'parents/message/'.intval($teacher_arr['teacher_id']).'/'.$row['subject_id'].'">' .$row['name'].' - '.$row['code'].' <br> ' .$unread_counters. '</a>
            //             		    ';
                        	
            //             	}
                        	//echo $this->db->last_query();
					 // print_r($subject_arr);
				
					    ?>
					   
					  
					    </div>
					<div class="col-lg-8">    
<div class="messaging">
  <div class="inbox_msg">
		<?php 
            $teacher_arr = get_staff_detail($teachers_id);
            $img_src = "";
		    if($teacher_arr[0]['staff_image']==""){
                $img_src = get_default_pic();
            }
            else{
                $img_src = display_link($teacher_arr[0]['staff_image'],'staff');
            }
        ?>  
	<div class="mesgs" id="mesgs">
	    <div id="navbar">
          <a href="#">
              <img src="<?=$img_src;?>" width="35">
          </a>
          <a href="#" class="nav_lnk"><?php echo $teacher_arr[0]['name']; ?></a>
          <a href="#" class="nav_lnk"><?php echo get_subject_name($subject_id); ?></a>
        </div>
	  <div class="msg_history" id="msg_history">
	      
	    <?php
        if(count($rows_1)>0)
		{
            foreach($rows_1 as $rr)
			{
				if($rr->messages_type==0)
                {
        ?>            
                    <div class="incoming_msg textmessage" style="padding: 10px !important;">
            		  <div class="incoming_msg_img"> <img src="<?php echo $img_src; ?>" style="width: 30px !important;" alt="sunil"> </div>
            		  <div class="received_msg">
            			<div class="received_withd_msg">
            			  <p><?php echo $rr->messages; ?> </p>
            			  <span class="time_date">
            			     <?php echo date('d-M-Y h:i A', strtotime($rr->message_time)); ?></span></div>
            		  </div>
            		</div>
		<?php		
				}
                else
				{
		?>
            		<div class="outgoing_msg textmessage">
            		  <div class="sent_msg">
            			<p><?php echo $rr->messages; ?></p>
            			<span class="time_date">
            			    <?php echo date('d-M-Y h:i A', strtotime($rr->message_time)); ?></span> </div>
            		</div>
		<?php
				}
			}
		}  
		?>  

	  </div>
	  <!-- 
	  <?php echo form_open(base_url().'parents/message_send/'.$this->uri->segment(3).'/'.$this->uri->segment(4) , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>
	  -->  
	    <input id="previous_message_id" name="previous_message_id" type="hidden" value="<?php echo $previous_message_id; ?>">
        <input id="parent_message_id" name="parent_message_id" type="hidden" value="<?php echo $parent_message_id; ?>">
        <input id="to" name="to" type="hidden" value="<?php echo $to; ?>" />
        <input id="student_name" name="student_name" type="hidden" value="<?php echo $student_name; ?>" />
        
        <input id="teacher_id" name="teacher_id" type="hidden" value="<?php echo $this->uri->segment(3); ?>" />
        <input id="subject_id" name="subject_id" type="hidden" value="<?php echo $this->uri->segment(4); ?>" />
        
	  <div class="type_msg">
		<div class="input_msg_write">
		  <input type="text" id="message" name="message" class="write_msg" placeholder="Type a message" required="required" />
		  <button class="msg_send_btn" type="submit" disabled="disabled"><i class="fa fa-paper-plane text-white" aria-hidden="true"></i></button>
		</div>
	  </div>
	  
	  <!-- </form>  -->
	</div>
  </div>
</div>
</div>
</div>
</div>


<script>
    $(document).ready(function(){
        $('.textmessage').last().attr('tabindex', '-1');
        $('.textmessage').last().focus();
        $('.textmessage').last().removeAttr('tabindex');
    });
    
    
    setInterval(function(){
        updateMessageList();
    },10000);
    
    
    $(".write_msg").on("keyup",function(e){
        if($.trim($(this).val()) != ""){
            $(".msg_send_btn").removeAttr("disabled");
            /*
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13){
            $(".msg_send_btn").trigger("click");
            $(this).val("");
            }
            */
        }
        else
        {
            $(".msg_send_btn").attr("disabled","disabled");
        }
    });
    
    
    $('.msg_send_btn').click(function(){
        let teacher_id = '<?php echo $teachers_id; ?>';
        let subject_id = '<?php echo $subject_id; ?>';
        
        let message    = $('#message').val();
        let previous_message_id = $('#previous_message_id').val();
        let parent_message_id   = $('#parent_message_id').val();
        
        //empty message field
        $('#message').val('');
        
        var url = '<?php echo base_url();?>parents/message_send';
        $.ajax({
            type: 'POST',
            data : {teacher_id:teacher_id , subject_id:subject_id , message:message , previous_message_id:previous_message_id , parent_message_id:parent_message_id},
            url: url,
            dataType: "html",
            success: function(response) {
                
                $('#message').val('');
                updateMessageList();
                
            }
        });  
    });
    
    
    
    function updateMessageList()
    {
        let teacher_id = '<?php echo $teachers_id; ?>';
        let subject_id = '<?php echo $subject_id; ?>';
        
        var url = '<?php echo base_url();?>parents/update_chat_message';
        $.ajax({
            type: 'POST',
            data : {teacher_id:teacher_id , subject_id:subject_id},
            url: url,
            dataType: "html",
            success: function(response) {
                console.log('interval called');
                $('#msg_history').empty();
                $('#msg_history').html(response);  
                
                $('.textmessage').last().attr('tabindex', '-1');
                $('.textmessage').last().focus();
                $('.textmessage').last().removeAttr('tabindex');
                
            }
        });
   
    }
    
    // When the user scrolls the page, execute myFunction
    window.onscroll = function() {myFunction()};
    
    // Get the navbar
    var navbar = document.getElementById("navbar");
    
    // Get the offset position of the navbar
    var sticky = navbar.offsetTop;
    
    // Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
    function myFunction() {
      if (window.pageYOffset >= sticky) {
        navbar.classList.add("sticky")
      } else {
        navbar.classList.remove("sticky");
      }
    }
</script>