<?php  if($this->session->flashdata('club_updated')){
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
<?php
$quer = "select  * from " . get_school_db() . ".attendance_type  where school_id=" . $_SESSION['school_id'] . "";
        $attendance_count = $this->db->query($quer)->result_array();
       
$type = $attendance_count[0]['login_type'];
?>
<div class="row">


        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <h3 class="system_name inline">
                <!--  <i class="entypo-right-circled carrow">
                        </i>-->
                <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/message.png"> 
                <?php echo get_phrase('attendance_type');?>
                
            </h3>
        </div>
    </div>
    
    
    
    <form action="<?php echo base_url(); ?>attendance_type/save_attendance_setting" id="disable_submit_btn" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" novalidate enctype="multipart/form-data">
        <div class="">
            <div class="row">
            
            <input type="hidden" name="school_id" value="<?php echo $_SESSION['school_id']; ?>" />
                <!-- panel preview -->
                <div class="col-sm-12">
                
                    <div class="panel panel-default">
                        <div class="panel-body form-horizontal payment-form">
                     <h4><?php echo get_phrase('select_attendance');?></h4>
                         <div class="form-group">
                                <label for="concept" class="col-sm-3 control-label">
                                <?php echo get_phrase('day_wise_attendance');?><span class="star"></span></label>
         <div class="col-sm-9">
         
         
<!--<textarea maxlength="320" oninput="count_value('address','address_count','320')" class="form-control" id="sms_details" name="sms_details_vac"><?php  echo $val_ary[1]['sms_details']; ?></textarea>
-->


<div class="col-lg-12" id="address_count">
	<input type="radio" class="custom-control-input" id="defaultUnchecked" name="attendnce_type" value="1"
  <?php	if($type==1){ echo "checked"; } ?> />

	
</div>
                                </div>
                            </div>

   <div class="form-group">
       <label for="concept" class="col-sm-3 control-label">
        <?php echo get_phrase('subject_wise_attendance');?><span class="star"></span></label>
         <div class="col-sm-9">
         
         
<!--<textarea maxlength="320" oninput="count_value('address','address_count','320')" class="form-control" id="sms_details" name="sms_details_vac"><?php  echo $val_ary[1]['sms_details']; ?></textarea>
-->

<div class="col-lg-12" id="address_count">
	<input type="radio" class="custom-control-input" id="defaultUnchecked" name="attendnce_type" value="0" <?php	if($type == 0){ echo "checked"; } ?> />

 
</div>
  </div>
 </div>

<div class="col-sm-offset-3" style="padding-left: 9px;">
                                <button id="main_btn" name="submit" class="btn btn-primary "><?php echo get_phrase('save');?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / panel preview -->
            </div>






        </div>
    </form>
    <script>
    $(document).ready(function() {
    });

function valadation(x, y) {
        //alert(x);
        //alert(y);
        var count = $('#' + y).val().length;

        if (count > x || count == 0) {
            $('#error_text').remove();
            $('#' + y).css('border', '1px solid red');
            $('#' + y).before('<p id="error_text" style="color:red;"><?php echo get_phrase('charactor_must_be_less_then'); ?>' + x + '</p>');
            $('#main_btn').prop('disabled', true);


        } else {
            $('#' + y).css('border', '1px solid green');
            $('#error_text').remove();


            $('#main_btn').prop('disabled', false);
        }

        var flag = $("#error_text").html();


        if (flag == undefined) {

            $('#main_btn').prop('disabled', false);

        } else {
            $('#main_btn').prop('disabled', true);
        }




    }
    </script>
<?php

?>