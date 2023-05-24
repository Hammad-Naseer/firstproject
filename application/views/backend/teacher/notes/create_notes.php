<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/css/fileinput.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('notes'); ?>
        </h3>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-12 b-s  px-4 mx-4">
        <form id="notes_form" method="POST" enctype="multipart/form-data" >
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 uploadOpacity" data-step="1" data-position='top' data-intro="Enter notes title">
                <div class="form-group">
                  <label for="notes_title">Notes Title</label><span class="redEsteric">*</span>
                  <span style="color:red;float: right;" id="notes_title_span"></span>
                  <input type="text" class="form-control" name="notes_title" id = "notes_title" required>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 uploadOpacity" data-step="2" data-position='top' data-intro="Enter notes description">
              <div class="form-group">
              <label for="notes_description">Description</label><span class="redEsteric">*</span>
              <textarea class="form-control" id="notes_description" name="notes_description" rows="3" required></textarea>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 uploadOpacity" data-step="3" data-position='top' data-intro="Enter notes remarks">
              <div class="form-group">
              <label for="notes_remarks">Remarks</label>
              <textarea class="form-control" name="notes_remarks" id="notes_remarks" rows="4" cols="50"></textarea>
              </div>
            </div>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 uploadOpacity" data-step="4" data-position='top' data-intro="Upload Attachments">
                <div class="form-group">
                    <label for="attachments">Notes Attachments<span class="redEsteric">*</span></label>
                    <span class="btn btn-default btn-file" style="width:100%;background: #cccccca3 !important;">
                        <input id="input-2" name="documents[]" type="file" class="file" multiple="multiple" required="required" data-show-caption="true">
                    </span>
                </div>
                <span style='float: right;color: red;'>Maximum file size should be less then 256 MB</span>
            </div>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " >
                <div class="form-group" id="process" style="display:none;">
                    <div class="progress">
                      <div class="progress-bar progress-bar-striped active progressPercentage" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="">
                      </div>
                    </div>
                </div>
                <div class="form-group">
                    <button data-step="5" data-position='top' data-intro="press this button to save notes" style="margin-top:20px !important;" type="submit" id="submit" class="btn btn-default submit_btn" >Save Notes</button>
                </div>
            </div>

        </div>    
    </form>
    </div>
</div>
<script src="//cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.plugins.addExternal('ckeditor_wiris', 'https://www.wiris.net/demo/plugins/ckeditor/', 'plugin.js');
    CKEDITOR.replace('notes_description');
</script>

<script>
    $("#notes_form").on('submit',function(e){
         e.preventDefault();
        for (instance in CKEDITOR.instances) 
        {
            CKEDITOR.instances[instance].updateElement();
        }
       
        $.ajax({
            url: '<?php echo base_url(); ?>notes/save_notes',
            method: 'POST',
            data:new FormData(this),  
            contentType: false,  
            cache: false,  
            processData:false,
            beforeSend:function(){
                var percentage = 0;
                var msgs = "";
                var timer = setInterval(function(){
                   $('#process').css('display', 'block');
                   $('.uploadOpacity').css('opacity', '0.2');
                   percentage = percentage + 5;
                   progress_bar_process(percentage, timer,msgs);
                }, 1000);
            },
            success:function(msg)
            {
                
            }
        });
    });
    
    function progress_bar_process(percentage, timer,message)
    {
       $('.progress-bar').css('width', percentage + '%');
       $(".progressPercentage").text(percentage);
       if(percentage > 100)
       {
        clearInterval(timer);
        $('#process').css('display', 'none');
        $('.uploadOpacity').css('opacity', '1');
        $('.progress-bar').css('width', '0%');
        setTimeout(function(){
            window.location.href='<?=base_url()?>notes/view_notes';
        }, 2000);
       }
    }
</script>

<script>
    // $("#snow-editor").on('keyup',function(){
    //     desc = $('.rounded-0 statement mathdoxformula',this).html();
    //     $("[name='notes_description']").val(desc);
    // });
 </script>