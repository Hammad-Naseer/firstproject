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
    
    <style>
        .underline{
            text-decoration: underline;
        }
    </style>
    <div class="row ">
       <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
           <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
            </a>
            <h3 class="system_name inline capitalize"> 
                <?php echo get_phrase('teacher_experience_certificate'); ?>
            </h3>
        </div>
    </div>
    
    <div>
        <form method="post" action="<?=base_url()?>certificate/teacher_experience_certificate" id="teacher_experience_certificate" class="form-groups-bordered">
            <div class="row filterContainer" data-step="1" data-position='top' data-intro="Please select the filters and press filter button to get record">
                
                <div class="col-lg-4 col-md-4 col-sm-12 form-group">
                    <label for="staff_id"><b>Select Staff</b></label>
                    <select id="staff_id" class="form-control" name="staff_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" required>
                            <?php  echo staff_list($staff_id , $staff_id); ?>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 form-group">
                    <label for="joining_date"><b>Joining Date</b></label>
                    <input type="date" name="joining_date" id="start_date" class="form-control" placeholder="Joining Data" value="<?php echo $joining_date; ?>" required/>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 form-group">
                    <label for="leaving_date"><b>Leaving Data</b></label>
                    <input type="date" name="leaving_date" id="end_date" class="form-control" placeholder="Leaving Data" value="<?php echo $leaving_date; ?>" required/>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                    <input type="hidden" name="apply_filter" value="1">
                    <input type="submit" class="modal_save_btn" value="<?php echo get_phrase('filter');?>"></input>
                    
                    <?php if($apply_filter == 1){?>
                    <a href="<?php echo base_url(); ?>certificate/teacher_experience_certificate" class="modal_cancel_btn" id="btn_remove"> 
                    <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>
                    <?php }?>
                    <?php if ($apply_filter == 1) { ?>
                         <input class="modal_save_btn" type="submit" id="print_pdf" value="<?php echo get_phrase('get_pdf');?>">
                        
                    <?php } ?>
                </div>
                
            </div>
        </form>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        
        <?php if(count($staff_data) > 0)
        {?>
        <div class="cotainer-fluid my-5">
            <div class="leaving-certificate Container py-5">
                <div class="row  d-flex align-items-center justify-content-between">
                    <div class="col-sm-12 col-md-3">
                       <img src="<?php echo base_url();?>uploads/<?php echo $_SESSION['folder_name'].'/'.$_SESSION['school_logo']; ?>"  class="w-75 py-4">
                    </div>
                    <div class="col-sm-12 col-md-9 d-flex justify-content-center">
                       <img src="<?php echo base_url(); ?>assets/certificate_ribbons/Experience-Certificate.png" class="img-fluid w-75">
                    </div>
                    
                    <div class="col-sm-12 col-md-12 d-flex justify-content-center py-5">
                       <p style="text-align: justify;"> 
                           It is to certify that Mr./Ms. <span class="underline"><?php echo $staff_data->name; ?></span>  ID# <span class="underline"><?php echo $staff_data->id_no; ?></span> was an employee of <span class="underline"><?php echo $_SESSION['school_name'];?></span> in capacity of <span class="underline"><?php echo $staff_data->title;?></span>.
                           He/She has been with us from <span class="underline"><?php echo date_view($joining_date); ?> </span> till <span class="underlinen"><?php echo date_view($leaving_date);?> </span> .We wish him/her good luck in her future endeavours.  
                       </p>
                       
                    </div>
                    <div class="col-sm-6 col-md-6  text-center">
                        <p><span><?php echo date('d-M-Y'); ?></span></p>
                        <p>Issue Date</p>
                        
                    </div>
                    <div class="col-sm-6 col-md-6  text-center">
                        <p><span>Head Department</span></p>
                        <p>Signature</p>
                        
                    </div> 
                </div>
            </div>
        </div>
        <?php } ?>
    </div> 
<!--//***********************Date filter validation***********************-->
<script>
    $("#start_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("start_date").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
        // }
    });
    
    $("#end_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementById("end_date").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("end_date").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
     $('#print_pdf').click(function(){
        
        $('#teacher_experience_certificate').attr('action', '<?php echo base_url(); ?>certificate/teacher_experience_certificate_pdf');
        $('#teacher_experience_certificate').submit();
    });
</script>
<!--//********************************************************************-->



