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
$(window).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>
<?php
    $query=$this->db->get_where(get_school_db().'.sms_settings' , array('school_id' =>$_SESSION['school_id']))->result_array();
    $val_ary=array();
    foreach($query as $rows){
        $val_ary[$rows['sms_type']]=array('sms_f_id'=>$rows['sms_f_id'],'sms_details'=>$rows['sms_details'],'email_status'=>$rows['email_status'],'status'=>$rows['status']);
    }
    {
?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
            </a>
            <h3 class="system_name inline">
                <?php echo get_phrase('sms_settings');?>
            </h3>
        </div>
    </div>
    
    <form data-step="1" data-position='top' data-intro="if you want send sms or email checked this options and press save button" "action="<?php echo base_url(); ?>sms_settings/save_sms_setting" id="disable_submit_btn" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" novalidate enctype="multipart/form-data">
        <div class="">
            <div class="row">  
                <!-- panel preview -->
                <div class="col-sm-12" >
                
                    <div class="panel panel-default">
                        <div class="panel-body form-horizontal payment-form">
                     <h4><?php echo get_phrase('attendance_sms_status');?></h4>
                        <div class="col-sm-12">
                         <div class="form-group">
                                <label for="concept" class="control-label"><?php echo get_phrase('sms_status');?><span class="star">*</span></label>
                                <!--<textarea maxlength="320" oninput="count_value('address','address_count','320')" class="form-control" id="sms_details" name="sms_details_vac"><?php  echo $val_ary[1]['sms_details']; ?></textarea>
                                -->
                                    <div class="col-lg-12" id="address_count">	
                                    	<input type="checkbox" name="status_vac"   <?php
                                        if($val_ary[1]['status']==1){
                                    	    echo "checked"; }      
                                         ?>/>
                                        <input type="hidden" name="sms_f_id_vac" value="<?php echo  $val_ary[1]['sms_f_id']; ?>"  />
                                    </div>
                                </div>
                            </div>
                       <div class="form-group">
                            <div class="col-sm-12">
                            <label for="concept" class="control-label"><?php echo get_phrase('email_status');?><span class="star">*</span></label>
                            <!--<textarea maxlength="320" oninput="count_value('address','address_count','320')" class="form-control" id="sms_details" name="sms_details_vac"><?php  echo $val_ary[1]['sms_details']; ?></textarea>
                            -->
                            <div class="col-lg-12" id="address_count">
                        	<input type="checkbox" name="email_status_vac"   <?php
                            if($val_ary[1]['email_status']==1){
                            	echo "checked"; }
                             ?>/>
 
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12" style="padding-left: 9px;">
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
}
?>