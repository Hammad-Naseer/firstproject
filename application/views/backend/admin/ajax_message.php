

<?php
        $img_src = get_default_pic();
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