<?php 
    $img_src = get_default_pic();
    
    $subject_id = intval($this->uri->segment(3));
    $student_id = intval($this->uri->segment(4));
    $teacher_id = intval($this->uri->segment(5));
    
    $param6 = intval($this->uri->segment(6));
    $param7 = intval($this->uri->segment(7));
    $param8 = intval($this->uri->segment(8));
?>
<link rel="stylesheet" href="<?=base_url()?>assets/css/messenger.css">
<div class="messaging">
  <div class="inbox_msg">
	<div class="inbox_people">
	</div>
	<div class="mesgs" id="mesgs">
	    
	    <div id="navbar">
            <div class="row" style="padding: 20px; !important">
                <div class="col-lg-4 col-md-4 col-sm-4 myttl">
                    <strong> <?php echo get_phrase('student');?>:</strong> <?php echo get_student_name($student_id); ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 myttl">
                    <strong> <?php echo get_phrase('teacher');?>:</strong>
                    <?php 
        			       $staf_arr = get_staff_detail($teacher_id);
                           echo $staf_arr[0]['name'];
                    ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 myttl">
                      <?php echo get_subject_name($subject_id); ?> 
                </div>
            </div>
        </div>
        
	  <div class="msg_history" id="msg_history">
	    <?php
        if(count($messages_data)>0)
		{
		    //print_r($messages_data);exit;
            foreach($messages_data as $rr)
			{
				$msg_css = "";
				if($rr['messages_type'] ==1)
                {
        ?>            
                    <div class="incoming_msg textmessage" style="padding: 10px !important;">
            		  <div class="incoming_msg_img"> <img src="<?php echo $img_src; ?>" alt="sunil" style="width: 30px !important;"> </div>
            		  <div class="received_msg">
            			<div class="received_withd_msg">
            			  <p>
            			      <?php  echo $rr['messages']; ?>
            			  </p>
            			  <span class="time_date"> <?php  echo date('d-M-Y h:iA', strtotime($rr['message_time'])); ?> </span></div>
            		  </div>
            		</div>
		<?php		
				}
                else
				{
		?>
            		<div class="outgoing_msg textmessage">
            		  <div class="sent_msg">
            		      <p>
            			      <?php  echo $rr['messages']; ?>
            			  </p>
            			<span class="time_date"> <?php  echo date('d-M-Y h:iA', strtotime($rr['message_time'])); ?> </span> </div>
            		</div>
		<?php
				}
			}
		}  
		?>  

	  </div>
	  
	  <!--
	  <?php echo form_open(base_url().'message/message_send'); ?>
      -->	  
            <input name="subject_id"   type="hidden" value="<?php echo $subject_id; ?>" />
            <input name="student_id"   type="hidden" value="<?php echo $student_id; ?>" />
            <input name="teacher_id"   type="hidden" value="<?php echo $teacher_id; ?>" />
            <input name="student_name" type="hidden" value="<?php echo $student_name; ?>" /> 
    	  
    	  <div class="type_msg">
    		<div class="input_msg_write">
    		  <input type="text" id="message" name="message" class="write_msg" placeholder="Type a message" />
    		  <button class="msg_send_btn" disabled="disabled" type="submit"><i class="fa fa-paper-plane text-white" aria-hidden="true"></i></button>
    		</div>
    	  </div>
    	  
	  <!-- </form> -->
	  
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
        
        let student_id = '<?php echo $student_id; ?>';
        let subject_id = '<?php echo $subject_id; ?>';
        let teacher_id = '<?php echo $teacher_id; ?>';
        
        let message    = $('#message').val();

        //empty message field
        $('#message').val('');
        
        var url = '<?php echo base_url();?>message/message_send';
        $.ajax({
            type: 'POST',
            data : {student_id:student_id , subject_id:subject_id , message:message , teacher_id:teacher_id},
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
        let student_id = '<?php echo $student_id; ?>';
        let subject_id = '<?php echo $subject_id; ?>';
        let teacher_id = '<?php echo $teacher_id; ?>';
        
        var url = '<?php echo base_url();?>message/update_chat_message';
        $.ajax({
            type: 'POST',
            data : {student_id:student_id , subject_id:subject_id , teacher_id:teacher_id},
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