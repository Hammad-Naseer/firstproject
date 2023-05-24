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
$query=$this->db->get_where(get_school_db().'.chalan_settings' , array('school_id' =>$_SESSION['school_id']))->result_array();
$flag="do_insert";  
if(count($query) > 0)
{
  $flag="do_update";
}

if (right_granted('managechallanform_layout'))
{
?>
 
    
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline" data-step="1" data-position='bottom' data-intro="set chalan layout">
               <?php echo get_phrase('challan_form_layout'); ?>
        </h3>
    </div>
</div>

<form id="chalan_settings_form" data-step="2" data-position='bottom' data-intro="enter your school name , school address , bank details , terms , logo and press Save button" action="<?php echo base_url(); ?>class_chalan_form/chalan_settings/<?php echo $flag;?>" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" novalidate enctype="multipart/form-data">
    <div class="row">
        <!-- panel preview -->
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body form-horizontal payment-form">
                    <div class="form-group">
                        <label for="concept" class="control-label"><?php echo get_phrase('name'); ?><span class="star">*</span></label>
                        <input maxlength="50" type="text" class="form-control" id="school_name" name="school_name" value="<?php echo  $query[0]['school_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="concept" class="control-label"><?php echo get_phrase('display_address'); ?><span class="star">*</span></label>
                        <textarea maxlength="200" oninput="count_value('address','address_count','200')" class="form-control" id="address" id="address" name="address"><?php  echo $query[0]['address']; ?></textarea>
                        <div class="col-lg-12" id="address_count"></div>
                    </div>
                    <div class="form-group">
                        <label for="concept" class="control-label"><?php echo get_phrase('bank_details'); ?><span class="star">*</span></label>
                        <textarea maxlength="200" oninput="count_value('bank_details','bank_details_count','200')" class="form-control" id="bank_details" name="bank_details" rows="4" id="bank_details" required><?php echo $query[0]['bank_details'];?></textarea>
                        <div class="col-lg-12" id="bank_details_count"></div>
                    </div>
                    <div class="form-group">
                        <label for="concept" class="control-label"><?php echo get_phrase('terms'); ?><span class="star">*</span></label>
                        <textarea maxlength="600" oninput="count_value('terms','terms_count','600')" id="terms" class="form-control" rows="7" name="terms" required><?php echo $query[0]['terms'];?></textarea>
                        <div class="col-lg-12" id="terms_count"></div>
                    </div>
                    <div class="form-group">
                        <label for="concept" class="control-label"><?php echo get_phrase('logo'); ?><span class="star">*</span></label>
                        <div>
                        <input name="logo" id="logo" type="file"  onchange="file_validate('logo','img','img_f_msg')" name="" style="margin-top:5px;    padding-left: 9px;">
                        <span style="color: green;"><?php echo get_phrase('allowed_file_size'); ?>: 200kb, <?php echo get_phrase('allewed_file_types'); ?> : png, jpg, jpeg </span>
                        <br />
                        <span style="color: red;" id="img_f_msg"></span>	
                        <input type="hidden" class="form-control" name="del_logo" value="<?php echo  $query[0]['logo']; ?>">
                        <input type="hidden" name="chalan_setting_id" value="<?php echo $query[0]['chalan_setting_id'];?>" />
                        </div>
                        <img src="<?php echo display_link($query[0]['logo'],''); ?>" height="100" width="100" />
                    </div>
                    <div class="form-group">
                        <div class="float-right">
        					<button type="submit" class="modal_save_btn">
        						<?php echo get_phrase('save');?>
        					</button>
        					<button type="button" class="modal_cancel_btn" onclick="location.reload();">
        						<?php echo get_phrase('cancel');?>
        					</button>
        				</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / panel preview -->
    </div>
</form>
<script>
   
    function valadation(x, y) {
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