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