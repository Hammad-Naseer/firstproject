



<?php
if (right_granted(array('message_manage')))
{
$subject_id = intval($this->uri->segment(3));
$student_id = intval($this->uri->segment(4));
$teacher_id = intval($this->uri->segment(5));

$param6 = intval($this->uri->segment(6));
$param7 = intval($this->uri->segment(7));
$param8 = intval($this->uri->segment(8));

    if($this->session->flashdata('club_updated'))
    {
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

    #example_wrapper {
        border: 1px solid #CCC;
    }
</style>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <h3 class="system_name inline capitalize">
                <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/brn.png"> <?php echo get_phrase('messages_list');?>
            </h3>
            <a href="<?php echo base_url()?>message/messages_student_list/<?php echo $param6.'/'.$param7.'/'.$param8 ?>" class="btn btn-primary pull-right"> <?php echo get_phrase('back_to_listing');?></a>
        </div>
    </div> 
    
    <div class="row">
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
    
    <br><br>
    
    <div class="row">
         <!-- 'class' => 'form-horizontal form-groups-bordered validate' , array('id'=>'disable_submit_btn') -->
        
            <div class="col-lg-12 col-md-12">
                
                <?php echo form_open(base_url().'message/message_send'); ?>
                
                <div class="form-group">
                    <textarea name="message" id="message_input" class="form-control input-sm mybg" autofocus placeholder="Type your message here." 
                     maxlength="1000" oninput="count_value('message_input','area_count1','1000')" required></textarea>  
                </div>
                 <div class="form-group">
                    <input name="subject_id" type="hidden" value="<?php echo $subject_id; ?>" />
                    <input name="student_id" type="hidden" value="<?php echo $student_id; ?>" />
                    <input name="teacher_id" type="hidden" value="<?php echo $teacher_id; ?>" />
                    <input name="student_name" type="hidden" value="<?php echo $student_name; ?>" /> 
                    <button type="submit" class="btn btn-primary btn-sm"> <?php echo get_phrase('send_message');?></button>   
                </div>
                
                </form>
                
            </div>
        
    </div>

    <table class="table table-bordered table_export">
        <thead>
            <tr>
                
                <th>
                    <div>
                        <?php echo get_phrase('message_detail');?>
                    </div>
                </th>
                
            </tr>
        </thead>
        <tbody>
        <?php
        foreach($messages_data as $row)
        {
            ?>
            <tr>
                
                <td>
                    <div class="post mymsgz">
                        <div class="user-block">
                            <span class="username" style="padding-bottom:15px; display:block;">
                 
                                <?php if( $row['messages_type'] == 1)
                                {
                                    echo " <span style=' color: #4a8cbb; font-weight:bold;' > ".get_phrase('parent').": </span>"; }
                                else
                                { 
                                    echo " <span style='color: #13b113; font-weight:bold; '>  ".get_phrase('teacher').": </span>"; 
                                } 
                                echo $row['messages']; 
                            ?>
                            </span>
                        </div>
                        <strong>
                            <span class="description">
                            <?php 
                                echo date('d-M-Y h:iA', strtotime($row['message_time']));

                                if( $row['is_viewed'] == 1)
                                { 
                                    echo ' Seen <i class="fa-check-circle"></i>';
                                }
                                else
                                {
                                    echo ' Unseen <i class="fa-check-circle-o"></i>';
                                }
                                $sent_by = get_user_login_detail($row['sent_by'] );
                                echo '<br>'; 
                                echo 'Sent By: '.$sent_by['name'];
                             ?>
                                 
                             </span>
                        </strong>
                                                    
                        </div>
                </td>
                
            </tr>
        <?php 
        }
        ?>
        </tbody>
    </table>

<?php
}
?>