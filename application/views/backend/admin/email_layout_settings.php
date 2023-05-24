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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline" data-step="1" data-position='bottom' data-intro="Setup the email layout for email sending">
               <?php echo get_phrase('email_layout_settings'); ?>
        </h3>
    </div>
</div>

<?php
if(count($email_layout_arr) > 0){
    
    $url = base_url().'school_setting/email_layout_update';
    $btn_text = "Update";
}else{
    $url = base_url().'school_setting/email_layout_insert';
    $btn_text = "Save";
}
?>

<div class="row pt-4">
    <!-- panel preview -->
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body form-horizontal payment-form">
                <form class="row g-3 p-0" action="<?php echo $url;?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="email_layout_id" value="<?php echo $email_layout_arr->email_layout_id ;?>"  />
                  <div class="col-sm-6">
                      <label for="school_name" class="form-label">School Name</label>
                      <input type="text" class="form-control" id="school_name"  name="school_name" value="<?php echo $email_layout_arr->school_name ;?>" placeholder="School Name">
                  </div> 
                  <div class="col-md-6">
                      <label for="address" class="form-label">School Address</label>
                      <input type="text" class="form-control"  id="address"  name="address" value="<?php echo $email_layout_arr->address ;?>" placeholder="School Address">
                  </div>   
                  <div class="col-md-12">
                      <label for="terms" class="form-label">School Terms</label>
                      <textarea class="form-control" id="terms" name="terms" rows="5"><?php echo $email_layout_arr->address ;?></textarea>
                  </div>
                  <div class="col-md-12"> 
                        <label for="logo" class="form-label">Upload Logo</label>
                        <input type="file" class="school-logo form-control p-1" name="logo" id="logo"> 
                  </div> 
                  <div class="col-12 mt-3 text-right ">
                      <button type="submit" class="btn btn-primary" id="butsave"><?php echo $btn_text;?></button>
                  </div>
                </form>
            </div>
        </div>
    </div>
    <!-- / panel preview -->
</div>