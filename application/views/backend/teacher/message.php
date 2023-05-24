<style>
    .mynavimg {
        height: 17px;
        display: inline;
        margin-top: -4px;
    }

    .btn-primary {
        background-color: #3576a5 !important;
    }
    .myrow {
    margin-top: 10px;
    border-bottom: 1px solid #00859a;
    padding-top: 10px;
    border-top: 1px solid #00859a;
    padding-bottom: 27px;
    background-color: rgba(204, 204, 204, 0.14);
    }

    .mycolor {
        color: #00859a;
        font-weight: bold;
    }

    .mybg {
        background-color: #f5f5f6;
    }

    .mymsgz {
        padding-left: 15px;
        padding-top: 15px;
        margin-bottom: 5px;
    }

    .sms {
        font-weight: bold;
        font-size: 12px;
        padding-left: 58px;
        text-transform: capitalize;
        color: #247336;
    }

    .text {
        overflow: hidden;
    }

    .height {
        height: 294px;
        overflow: hidden;
    }

    .rmb {
        color: #FFF;
        background-color: #007565;
        padding: 7px;
        border-radius: 5px;
    }

    .rmb:hover {
        color: #FFF;
    }

    .rmb:focus {
        color: #FFF;
    }

    .pagination>li {
        display: inline;
        padding: 0px !important;
        margin: 0px !important;
        border: none !important;
    }

    .modal-backdrop {
        z-index: -1 !important;
    }


    /*
    Fix to show in full screen demo
    */

    iframe {
        height: 700px !important;
    }

    .btn {
        display: inline-block;
        padding: 6px 12px !important;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .btn-primary {
        color: #fff !important;
        background: #428bca !important;
        border-color: #357ebd !important;
        box-shadow: none !important;
    }

    .btn-danger {
        color: #fff !important;
        background: #d9534f !important;
        border-color: #d9534f !important;
        box-shadow: none !important;
    }

    #teacher_message_wrapper {
        border: 1px solid #CCC;
    }
	.teacher-msg{
		text-align:right;
	}
	.parent-msg{
		text-align:left;
	}
	.teacher-msg .description,
	.parent-msg .description{
		font-size:11px;
		font-style:italic;
		font-weight:bold;
		padding-top:5px;
		display:block;
	}
</style>

<script>
    $(document).ready(function() {
        $('#teacher_message').DataTable({
            "bSort": false
        });
    });
</script>


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
$(window).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>
<?php
//$subject_id = intval($this->uri->segment(5));
$subject_id = intval($this->uri->segment(4));
$section_id = intval($this->uri->segment(6));
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <?php
        echo form_open(base_url().'teacher/student_list/', array('class' => 'form-horizontal form-groups-bordered validate')); 
        ?>
            <input type="hidden" name="dep_c_s_id" id="dep_c_s_id" value="<?php echo $section_id ?>">
            <input type="hidden" name="subject" value="<?php echo $subject_id ?>">
            <input type="submit"  name="back_to_listing" value="<?php echo get_phrase('back_to_listing');?>" class="btn btn-primary pull-right" style="font-size:12px; background:#507895 !important;">

        </form>
        <h3 class="system_name inline">
               <?php echo get_phrase('messages_list'); ?>
        </h3>
    </div>
</div>
<div class="active tab-pane" id="activity">
	
    <div class="col-lg-12 col-sm-12 col-md-12">
        <table class="table table-responsive">
            <tr>
                <td>
                    <?php 
                    $student_arr = $this->db->query("select * 
                                from ".get_school_db().".student 
                                where student_id = $student_id
                                and school_id = ".$_SESSION['school_id']."
                                ")->result_array();
                    //print_r($student_arr);
                    if (count($student_arr)>0)
                    {
                        if($student_arr[0]['image']=="" && ! file_exists(system_path($student_arr[0]['image'],'student')))
                        {
					?>
                        <img src="<?php echo get_default_pic();?>" width="60" />
                    <?php   
                        }
                        else
                        {
					?>
                         <img src="<?php echo display_link($student_arr[0]['image'],'student');?>" width="60" />
                     <?php 
					 }
					 ?>
                </td>
                <td>
                   <strong style="font-size:14px;">
				   <?php
                    echo $student_arr[0]['name']; 
                    $sec_arr = section_hierarchy($student_arr[0]['section_id']);
                    //echo '<br>'.$sec_arr['d'].' -> '.$sec_arr['c'].' -> '.$sec_arr['s'].'<br>';
                    }
                    ?></strong><br>
                    <?php echo get_subject_name($subject_id); ?>
                </td>
                <td>
                    <?php echo form_open(base_url().'teacher/message_send/', array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>
                    <div class="form-group margin-bottom-none">
                        <div class="col-sm-9">
                            <textarea name="message" class="form-control input-sm mybg" autofocus placeholder="<?php echo get_phrase('type_your_message_here');?>" id="message_input" maxlength="1000" oninput="count_value('message_input','detail_count','1000')" required></textarea>
                            <div id="detail_count"></div>
                            <input name="previous_message_id" type="hidden" value="<?php echo $previous_message_id; ?>">
                            <input name="parent_message_id" type="hidden" value="<?php echo $parent_message_id; ?>">
                            <input name="to" type="hidden" value="<?php echo $to; ?>" />
                            <input name="student_name" type="hidden" value="<?php echo $student_name; ?>" />
                            <input name="student_id" type="hidden" value="<?php echo $student_id; ?>" />
                            <input name="subject_id" type="hidden" value="<?php echo $subject_id; ?>" />

                            <input name="back_subject_id" type="hidden" value="<?php echo $subject_id; ?>" />
                            <input name="back_section_id" type="hidden" value="<?php echo $section_id; ?>" />
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-primary btn-sm" style="font-size:12px; background:#507895 !important;"><?php echo get_phrase('send_message');?></button>
                        </div>
                    </div>
                    </form>
                </td>

            </tr>
        </table>
    </div>
    <div class="col-lg-12 col-sm-12 col-md-12">
        <table id="teacher_message" class="table table-striped table-hover " cellspacing="0" width="100%" style="cursor:pointer;">
        <thead>
            <tr>
                <th style="    border: none; background-color:#FFF;">
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(count($rows)>0)
			{
            foreach($rows as $rr)
			{
				$msg_css = "";
				if($rr->messages_type==1)
                {
                   $msg_css = "parent-msg"; 
				}
                else
				{
					$msg_css = "teacher-msg";
				}
             ?>
                <tr>
                    <td>
                        <div class="post mymsgz <?php  echo $msg_css; ?> ">
                            <div class="user-block">
                                <span class="username">
									<?php  echo $rr->messages; ?>
                                </span>
                            </div>
                            <span class="description"><?php 
                                echo date('d-M-Y h:iA', strtotime($rr->message_time)); ?>
                            </span>
                          </div>
                    </td>
                </tr>
                <?php   
				}
			}  
			?>
        </tbody>
    </table>
    </div>
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
    if (readmore.text() == 'Read More Messages') {
        readmore.text("Read less");
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




