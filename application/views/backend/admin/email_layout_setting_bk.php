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
// echo "<pre>";
// print_r($_SESSION);
// exit;
$query=$this->db->get_where(get_school_db().'.email_layout_settings' , array('school_id' =>$_SESSION['school_id']))->result_array();
$flag="do_insert";  
if(count($query) > 0)
{
  $flag="do_update";
}

//if (right_granted('managechallanform_layout'))
{
?>
    <div class="row">
        <div class="col-lg- col-md-6 col-xs-6 myt topbar">
            <h3 class="system_name inline">
                <!--  <i class="entypo-right-circled carrow">
                        </i>-->
                <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/challan-form-setting.png">
                <?php echo get_phrase('email_layout'); ?> 
            </h3>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-6 myt topbar">
            <h3 class="system_name inline">
                <a class="btn btn-sm" style="float:right;" href="<?php echo base_url();?>templates/email_layout_view" >
                    <?php echo get_phrase('email_layout_view'); ?>
                </a> 
            </h3>
        </div>
    </div>
    <form id="chalan_settings_form" action="<?php echo base_url(); ?>templates/email_layout_setting/<?php echo $flag;?>" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" novalidate enctype="multipart/form-data">
        <div class="">
            <div class="row">
                <!-- panel preview -->
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-body form-horizontal payment-form">
                            <div class="form-group">
                                <label for="concept" class="col-sm-3 control-label"><?php echo get_phrase('name'); ?><span class="star">*</span></label>
                                <div class="col-sm-9">
                                    <input maxlength="50" type="text" class="form-control" id="school_name" name="school_name" value="<?php echo  $query[0]['school_name']; ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="concept" class="col-sm-3 control-label"><?php echo get_phrase('display_address'); ?><span class="star">*</span></label>
                                <div class="col-sm-9">
                                    <textarea maxlength="200" oninput="count_value('address','address_count','200')" class="form-control" id="address" id="address" name="address"><?php  echo $query[0]['address']; ?></textarea>
                                    <div class="col-lg-12" id="address_count"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="concept" class="col-sm-3 control-label"><?php echo get_phrase('terms'); ?><span class="star">*</span></label>
                                <div class="col-sm-9">
                                    <textarea maxlength="600" oninput="count_value('terms','terms_count','600')" id="terms" class="form-control" rows="7" name="terms" required><?php echo $query[0]['terms'];?></textarea>
                                    <div class="col-lg-12" id="terms_count"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="concept" class="col-sm-3 control-label"><?php echo get_phrase('logo'); ?><span class="star">*</span></label>
                                <div class="col-sm-9">
                                    <div>
                                        <input name="logo" id="logo" type="file"  onchange="file_validate('logo','img','img_f_msg')" name="" style="margin-top:5px;    padding-left: 9px;">
                                        <span style="color: green;"><?php echo get_phrase('allowed_file_size'); ?>: 200kb, <?php echo get_phrase('allewed_file_types'); ?> : png, jpg, jpeg </span>
                                       <br />
                                       <span style="color: red;" id="img_f_msg"></span>	
                                        <input type="hidden" class="form-control" name="del_logo" value="<?php echo  $query[0]['logo']; ?>">
                                        <input type="hidden" name="email_layout_id" value="<?php echo $query[0]['email_layout_id'];?>" />
                                    </div>
                                    <?php
                                    // echo "<pre>";
                                    // print_r($_SESSION);
                                    // if($query[0]['school_logo'] != "")
                                    // {
                                    ?>
                                    <img src="<?php echo base_url()."uploads/".$_SESSION['folder_name']."/".$query[0]['logo']; ?>" height="100" width="100" />
                                    <?php
                                    // }
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-offset-3" style="padding-left: 9px;">
                                <button id="main_btn" name="submit" class="btn btn-primary "><?php echo get_phrase('save'); ?></button>
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