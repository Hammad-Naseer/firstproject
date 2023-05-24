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

    <div class="row ">
       <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
           <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
            </a>
            <h3 class="system_name inline capitalize"> 
                <?php echo get_phrase('student_character_certificate'); ?>
            </h3>
        </div>
    </div>
    
    <div>
        <form id="character_certificate_student_form" name="filter" method="post" action="<?php echo base_url();?>certificate/character_certificate" class="form-horizontal form-groups-bordered validate " style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px;">
            <div class="row filterContainer">
                <div class="col-lg-6 col-md-6 col-sm-6" data-step="1" data-position="top" data-intro="Step 1: select class - section">
                    <label id="section_id_filter_selection"></label>
                    <select id="section_id_filter"  class="selectpicker form-control" name="section_id" required>
                        <?php echo section_selector($section_id);?>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 mgt10" data-step="2" data-position="top" data-intro="Step 2: select student">
                    <select id="student_id" name="student_id" class="form-control" required>
                        <?php 
                        if(isset($section_id) && isset($student_id)){
                            echo section_student($section_id , $student_id);
                        } 
                        ?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3" data-step="3" data-position="top" data-intro="Press this button to get student character certifcate">
                    
                    <input type="hidden" name="apply_filter" value="1">
                    <input type="submit" id="btn_submit" value="<?php echo get_phrase('filter');?>" class="modal_save_btn"> 
                    <?php if ($apply_filter == 1) { ?>
                        <a id="btn_show" href="<?php echo base_url().'certificate/character_certificate'?>" class="modal_cancel_btn" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
                        <input class="modal_save_btn" type="submit" id="print_pdf" value="<?php echo get_phrase('get_pdf');?>">
                        
                    <?php } ?>

                </div>
            </div>
        </form>
</div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <?php if(count($student_data) > 0)
            {?>
            <div class="cotainer-fluid my-5">
                <div class="leaving-certificate Container py-5">
                    <div class="row  d-flex align-items-center justify-content-between">
                        <div class="col-sm-12 col-md-3">
                           <img src="<?php echo base_url();?>uploads/<?php echo $_SESSION['folder_name'].'/'.$_SESSION['school_logo']; ?>"  class="w-75 py-4">
                        </div>
                        <div class="col-sm-12 col-md-9 d-flex justify-content-center">
                           <img src="<?php echo base_url(); ?>assets/certificate_ribbons/Character-Certificate.png" class="img-fluid w-75">
                           
                        </div>
                        
                        <div class="col-sm-12 col-md-12 d-flex justify-content-center py-5">
                           <p style="text-align: justify;"> 
                               It is certified that <span><?php echo $student_data->name; ?></span> S/o  <span><?php echo $student_data->father_name; ?></span> is a bonafide student of this School.
                               To the best of my knowledge, he/she bears a good moral character. his/her behaviour was good with teachers and students.
                               This certificate is being issued upon the request of the above-named student for required purpose.
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

<script>
    $(document).ready(function() 
    {
        $(".page-container").addClass("sidebar-collapsed");
    	$("#section_id_filter").change(function() {
            var section_id = $(this).val();

            $("#icon").remove();
            $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>certificate/get_section_student",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    if (response != "") {
                        $("#student_id").html(response);
                    }
                    if (response == "") {
                        $("#student_id").html('<select><option value=""><?php echo get_phrase('select_student'); ?></option></select>');
                    }
                }
            });
        });
    });
    
    $('#print_pdf').click(function(){
        
        $('#character_certificate_student_form').attr('action', '<?php echo base_url(); ?>certificate/character_certificate_pdf');
        $('#character_certificate_student_form').submit();
    });
        
</script>


