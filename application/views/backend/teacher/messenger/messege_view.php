<?php 
    $img_src = "";
    $student_arr = $this->db->query(" select *  from ".get_school_db().".student where student_id = $student_id
                                      and school_id = ".$_SESSION['school_id']." ")->result_array();
    if (count($student_arr)>0)
    {
        if($student_arr[0]['image']=="" && ! file_exists(system_path($student_arr[0]['image'],'student')))
        {
            $img_src = get_default_pic();
        }
        else
        {
            $img_src = display_link($student_arr[0]['image'],'student');
		}
    }
?>
<link rel="stylesheet" href="<?=base_url()?>assets/css/messenger.css">
<div class="messaging">
  <div class="inbox_msg">
	<div class="inbox_people">
	</div>
	<div class="mesgs" id="mesgs">
	    <div id="navbar">
          <a href="#">
              <img src="<?=$img_src;?>" width="35">
          </a>
          <a href="#" class="nav_lnk"><?php echo $student_arr[0]['name']; ?></a>
          <a href="#" class="nav_lnk"><?php echo get_subject_name($subject_id); ?></a>
        </div>
	  <div class="msg_history" id="msg_history">
	    <?php
        if(count($rows)>0)
		{
            foreach($rows as $rr)
			{
				$msg_css = "";
				if($rr->messages_type==1)
                {
        ?>            
                    <div class="incoming_msg textmessage" style="padding: 10px !important;">
            		  <div class="incoming_msg_img"> <img src="<?php echo $img_src; ?>" alt="sunil" style="width: 30px !important;"> </div>
            		  <div class="received_msg">
            			<div class="received_withd_msg">
            			  <p>
            			      <?php  echo $rr->messages; ?>
            			  </p>
            			  <span class="time_date"> <?php  echo date('d-M-Y h:i A', strtotime($rr->message_time)); ?> </span></div>
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
            			      <?php  echo $rr->messages; ?>
            			  </p>
            			<span class="time_date"> <?php  echo date('d-M-Y h:iA', strtotime($rr->message_time)); ?> </span> </div>
            		</div>
		<?php
				}
			}
		}  
		?>  

	  </div>
	  
	  <?php
	    $subject_id = intval($this->uri->segment(4));
        $section_id = intval($this->uri->segment(6));
	  ?>
	  
	  <!--
	  <?php echo form_open(base_url().'teacher/message_send/', array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>
      -->	  
          <input id="dep_c_s_id"             name="dep_c_s_id"          type="hidden" value="<?php echo $section_id ?>"  id="dep_c_s_id" >
          <input id="subject"                name="subject"             type="hidden" value="<?php echo $subject_id ?>">
          <input id="previous_message_id"    name="previous_message_id" type="hidden" value="<?php echo $previous_message_id; ?>">
          <input id="parent_message_id"      name="parent_message_id"   type="hidden" value="<?php echo $parent_message_id; ?>">
          <input id="to"                     name="to"                  type="hidden" value="<?php echo $to; ?>" />
          <input id="student_name"           name="student_name"        type="hidden" value="<?php echo $student_name; ?>" />
          <input id="student_id"             name="student_id"          type="hidden" value="<?php echo $student_id; ?>" />
          <input id="subject_id"             name="subject_id"          type="hidden" value="<?php echo $subject_id; ?>" />
          <input id="back_subject_id"        name="back_subject_id"     type="hidden" value="<?php echo $subject_id; ?>" />
          <input id="back_section_id"        name="back_section_id"     type="hidden" value="<?php echo $section_id; ?>" />
    	  
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
        
        let message    = $('#message').val();
        let previous_message_id = $('#previous_message_id').val();
        let parent_message_id   = $('#parent_message_id').val();
        
        //empty message field
        $('#message').val('');
        
        var url = '<?php echo base_url();?>teacher/message_send';
        $.ajax({
            type: 'POST',
            data : {student_id:student_id , subject_id:subject_id , message:message , previous_message_id:previous_message_id , parent_message_id:parent_message_id},
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
        
        var url = '<?php echo base_url();?>teacher/update_chat_message';
        $.ajax({
            type: 'POST',
            data : {student_id:student_id , subject_id:subject_id},
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