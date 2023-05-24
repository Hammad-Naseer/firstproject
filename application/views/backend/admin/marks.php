<?php

if (right_granted('managemarks_save'))
{
?>
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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i>Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('manage_marks');?>
        </h3>
    </div>
</div>

<div>
    <form name="marksfilter" id="marksfilter" method="post" class="form-horizontal  validate">
        <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select filters and press filter button to get specific records">        
            <div class="col-md-6 col-lg-6 col-sm-6">
               <label id="exams_filter_selection"></label>
                        <select id="exams_filter" name="exams_filter" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">
                            <?php
                               $status_year=array(3);
                               $status_term=array(3);                       
                               echo yearly_term_selector_exam($exam_id,$status_year,$status_term);
                            ?>
                        </select>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-6">
                <label id="section_id_filter_selection"></label>
                <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                    <?php echo section_selector($section_id);?>
                </select>
            </div>     
            <div class="col-md-6 col-lg-6 col-sm-6 mgt10">
                <select id="subject_id" name="subject_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                    <option value=""> <?php echo get_phrase('select_subject');?></option>
                </select>   
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12 mgt10">
                <input type="submit" id="select" class="btn btn-primary" value=" <?php echo get_phrase('filter');?>">
                <a href="<?php echo base_url(); ?>exams/marks" style="display: none;" class="modal_cancel_btn" id="btn_remove"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>
                 
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div id="marks" style="width: 100%;"></div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var section_id = '<?php echo $section_id;?>';
        var subject_id = '<?php echo $subject_id;?>';
        var exam_id    = '<?php echo $exam_id;?>';

        if (section_id != '' && subject_id != '' && exam_id != '') {
            
            $('#btn_remove').show();
            $("#marks").html("<div id='loading' class='loader'></div>");
            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id,
                    subject_id: subject_id,
                    exam_id: exam_id
                },
                url: "<?php echo base_url();?>exams/get_marks",
                dataType: "html",
                success: function(response) {

                    $("#loading").remove();
                    $("#marks").html(response);
                }
            });
        }

        $('.selectpicker').on('change', function() {
            
            var id = $(this).attr('id');
            var selected = $('#' + id + ' :selected');
            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);

        });

        document.getElementById('marksfilter').onsubmit = function() {
            return false;
        };
        
        var exam_id='<?php echo $exam_id;?>';
        var section_id='<?php echo $section_id;?>';
        var subject_id='<?php echo $subject_id;?>';
    
    
        if((exam_id > 0) && (section_id > 0) && (subject_id > 0))
        {
      	        $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>exams/get_subject_exam",
    
                    data: ({
                        exam_id: exam_id,
                        section_id:section_id,
                        subject_id:subject_id
                    }),
                    dataType: "html",
                    success: function(html) {
                        if (html != '') {
                            $('#subject_id').html(html);
                        }
                    }
                });
        }
 
        $('#section_id_filter').on('change', function() {

        var section_id = $(this).val();
        var exam_id = $('#exams_filter').val();
        if (exam_id != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>exams/get_subject_exam",

                data: ({
                    exam_id: exam_id,
                    section_id:section_id
                }),
                dataType: "html",
                success: function(html) {
                	//alert(response);
                	
                    if (html != '') {
                       $('#subject_id').html(html);
                    }
                }
            });
        }

    });
    
        $('#exams_filter').on('change', function() {

        var section_id = $('#section_id_filter').val();
        var exam_id = $('#exams_filter').val();
        //$('#subject_id').html('<select><option>Select Subject</option></select>');
        if (exam_id != '' && section_id!='') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>exams/get_subject_exam",

                data: ({
                    exam_id: exam_id,
                    section_id:section_id
                }),
                dataType: "html",
                success: function(html) {
                    if (html != '') {
                        $('#subject_id').html(html);
                    }
                }
            });
        }

    });
    
        $("#select").click(function() {
        $('#marks-filter').validate();
        var section_id = $("#section_id_filter").val();
        var subject_id = $("#subject_id").val();
        var exam_id = $("#exams_filter").val();

        if (section_id != '' && subject_id != '' && exam_id != '') {
            $('#btn_remove').show();
            $("#marks").html("<div id='loading' class='loader'></div>");
            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id,
                    subject_id: subject_id,
                    exam_id: exam_id
                },
                url: "<?php echo base_url();?>exams/get_marks",
                dataType: "html",
                success: function(response) {

                    $("#loading").remove();
                    $("#marks").html(response);
                }
            });
        }
    });
    });
</script>
<style>
.loader {
    border: 16px solid #f3f3f3;
    /* Light grey */
    border-top: 16px solid #63b7e7;
    /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
    margin-right: auto;
    margin-left: auto;
}

.loader_small {
    border: 7px solid #f3f3f3;
    border-top: 7px solid #63b7e7;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    margin-right: auto;
    margin-left: auto;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
<?php
}
?>