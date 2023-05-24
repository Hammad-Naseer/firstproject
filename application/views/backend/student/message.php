<style>
.mynavimg {
    height: 17px;
    display: inline;
    margin-top: -4px;
}

.btn-primary {
    background-color: #3576a5 !important;
}
</style>
<script>
$(document).ready(function() {
    $('#example').DataTable({
        "bSort": false
    });


});
</script>
<!--
 <script language="JavaScript" src="https://code.jquery.com/jquery-1.12.3.js" type="text/javascript"></script>
 
 
 
<script language="JavaScript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></script>



<script language="JavaScript" src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js" type="text/javascript"></script>


<link rel="stylesheet" href=" https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.bootstrap.min.css">-->
<style>
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
$(window).load(function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <h3 class="myfont" style="margin-top:22px;"><?php echo get_phrase('messages_list');?></h3>
    </div>
</div>
<div class="active tab-pane" id="activity">
	<div class="row">
    	<div class="col-lg-12 col-sm-12 col-md-12">
        <table class="table table-responsive table-striped">
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
                
                <td>
                	   <div class="teachersubject">
                    	<?php echo get_subject_name($subject_id); ?>
                    </div>
                	
                	
                </td>
                
                
                
                <td>
                    <?php echo form_open(base_url().'parents/message_send/'.$this->uri->segment(3).'/'.$this->uri->segment(4) , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>
                    <div class="form-group margin-bottom-none">
                            <textarea name="message" id="message_input" class="form-control input-sm mybg" autofocus placeholder="<?php echo get_phrase('type_your_message_here');?>" maxlength="1000" oninput="count_value('message_input','area_count1','1000')" required></textarea>
                            <div id="area_count1" class="col-sm-12 "></div>
                            <input name="previous_message_id" type="hidden" value="<?php echo $previous_message_id; ?>">
                            <input name="parent_message_id" type="hidden" value="<?php echo $parent_message_id; ?>">
                            <input name="to" type="hidden" value="<?php echo $to; ?>" />
                            <input name="student_name" type="hidden" value="<?php echo $student_name; ?>" />
                            
                    </div>
                    
                </td>
                <td width="50">
                	<button type="submit" class="btn btn-primary btn-sm" style="background-color:#4a8cbb !important; font-size:12px; margin-top:15px !important;"><?php echo get_phrase('send_message');?></button>
                </td>
                </form>
            </tr>
        </table>
    </div>
    </div>
    <table id="example" class="table table-striped table-hover chatroom" cellspacing="0" width="100%" style="cursor:pointer;">
        <thead>
            <tr>
                <th style="border: none; background-color:#FFF;">
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
//print_r($rows);
 if(count($rows_1)>0){
    //print_r($rows_1);
foreach($rows_1 as $rr){
    //print_r($rr);
    //echo $rr->student_id;
            
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
                                echo date('d-M-Y h:iA', strtotime($rr->message_time)); ?></span></em>
                            
                            <!-- /.user-block -->
                            <!--<p>
    <span class="sms">  
    
    <q> 
       <?php echo $rr->messages; ?> 
       
     </q>   
       
        </span>        
                  </p>-->
                        </div>
                    </td>
                </tr>
                <?php   } }  ?>
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
