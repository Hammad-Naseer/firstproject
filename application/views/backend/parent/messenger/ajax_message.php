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
