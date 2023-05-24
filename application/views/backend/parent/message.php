<?php  
 if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
 
  ?>
<script>
$(window).on("load" , function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Watch Tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('messages_list'); ?>
        </h3>
    </div>
</div>

<div class="active tab-pane" id="activity">
	
	<div class="col-lg-12 col-sm-12 col-md-12">
    <table class="table table-responsive ">
        <tr>
            <td width="100">
                <?php 
        $teacher_arr = get_staff_detail($teachers_id);
        if($teacher_arr[0]['staff_image']=="")
        {?>
                <img src="<?php echo get_default_pic();?>" width="60" />
                <?php   
        }
        else
        {?>
                <img src="<?php echo display_link($teacher_arr[0]['staff_image'],'staff');?>" width="60" />
                
                
            </td>
            
            <td>
            	<div class="teachername" style="margin-top:10px; font-size:14px;">
					<strong><?php
                    }
                        echo $teacher_arr[0]['name']; 
                    ?></strong>
                </div>
             
            </td>
            
            <td style="padding-top: 5%;">
            	<div class="teachersubject">
                	<?php echo get_subject_name($subject_id); ?>
                </div>
            </td>
            
            
            <?php echo form_open(base_url().'parents/message_send/'.$this->uri->segment(3).'/'.$this->uri->segment(4) , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>
                <td style="padding-left: 5%;">
                    <div class="form-group">
                            <textarea name="message" id="message_input" class="form-control input-sm mybg" 
                                      autofocus placeholder="<?php echo get_phrase('type_your_message_here');?>" 
                                      maxlength="1000" rows="5" oninput="count_value('message_input','area_count1','1000')" required></textarea>
                            <div id="area_count1" class="col-sm-12 "></div>
                    </div>
                            <input name="previous_message_id" type="hidden" value="<?php echo $previous_message_id; ?>">
                            <input name="parent_message_id" type="hidden" value="<?php echo $parent_message_id; ?>">
                            <input name="to" type="hidden" value="<?php echo $to; ?>" />
                            <input name="student_name" type="hidden" value="<?php echo $student_name; ?>" />
                    
                </td>
                <td width="50" style="padding-top: 1%;padding-left: 5%;">
                	<button type="submit" class="btn btn-primary btn-sm" style="background-color:#4a8cbb !important; font-size:12px; margin-top:15px !important;"><?php echo get_phrase('send_message');?></button>
                </td>
            </form>
        </tr>
    </table>
    </div>
    <table id="example" class="table table-striped table-hover chatroom " cellspacing="0" width="100%" style="cursor:pointer;">
        <thead>
            <tr>
                <th style="border: none; background-color:#FFF;">
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(count($rows_1)>0){
            foreach($rows_1 as $rr){
            ?>
                <tr>
                    <td>
                        <div class="post mymsgz">
                        	<div class="user-block">
                                <span class="username" style="padding-bottom:15px; display:block;">
                                 <?php if($rr->messages_type==1){
                                 echo ""; }else{ echo ""; } echo $rr->messages; ?></span>
                            </div>
                            <em><span class="description" style="font-size:11px;"><?php 
                                echo date('d-M-Y h:i A', strtotime($rr->message_time)); ?></span></em>
                                <?php echo $rr->messages; ?> 
                        </div>
                    </td>
                </tr>
                <?php
                } }
                ?>
                
        </tbody>
    </table>
</div>
<script>
$(document).ready(function() {
    $('.text').each(function(element, index) {
        if ($(this)[0].scrollHeight > $(this).height()) {
            $(this).next().show()
        } else {
            $(this).next().hide()
        }
    })
})

function changeheight(obj) {
    var fullHeight = $(obj).parent().prev().get(0).scrollHeight
    var readmore = $(obj);
    if (readmore.text() == 'read more messages') {
        readmore.text("<?php echo get_phrase('read_less');?>");
        $(obj).parent().prev().data('oldHeight', $(obj).parent().prev().height())
        $(obj).parent().prev().animate({
            'height': fullHeight
        }, 350)
    } else {
        readmore.text("<?php echo get_phrase('read_more_messages');?>");
        $(obj).parent().prev().animate({
            'height': $(obj).parent().prev().data('oldHeight')
        }, 350)
    }
};
</script>

<script>
$(document).ready(function() {
    $('#example').DataTable({
        "bSort": false
    });


});
</script>
