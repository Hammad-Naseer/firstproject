<?php 
$urlArr=explode('/',$_SERVER['REQUEST_URI']);
$resArr=explode('-',end($urlArr));
$student_id=$resArr[0];
$academic_id=$resArr[1];
?>
<div class="row">
    <form name="marksheet" id="marksheet" class="form-horizontal  validate">
        <div class="col-sm-12">
            <h3 class="black black2 myttl">
            
            <?php echo get_phrase('mark_sheet');?>
            </h3>
        </div>
        <div class="myrow" style="    background-color: #f0f0f0;
  
    height: 50px;
    padding: 9px 0px;">
            <div class="col-md-6 col-lg-6 col-sm-6">
                <label id="exams_filter_selection"></label>
                <select id="exams_filter" name="yearly_terms1" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">
                    <?php
 $status_year=array(3);
 $status_term=array(3);                       
 echo yearly_term_selector_exam('',$status_year,$status_term);
 ?>
                </select>
                <?php
		/*
		<select id="yearly_term" name="yearly_term" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                    <?php echo yearly_terms_option_list($academic_id);?>
                    </select>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-6">
                <select id="exam_id" name="exam_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                    <?php echo "<option value=''>Select Exam Type</option>";?>
                </select>
            </div>
            */ ?>
        </div>
    </form>
</div>
<hr/>
<div id="exam_result">
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.selectpicker').on('change', function() {
        var id = $(this).attr('id');
        var selected = $('#' + id + ' :selected');

        var group = selected.parent().attr('label');
        $('#' + id + '_selection').text(group);

    });

    document.getElementById('marksheet').onsubmit = function() {
        return false;
    };
    $('#yearly_term').on('change', function() {
        var yearly_term = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>exams/get_exam_type",
            data: ({
                yearly_term: yearly_term
            }),
            dataType: "html",
            success: function(html) {
                if (html != '') {
                    $('#exam_id').html(html);
                }
            }
        });
    });
    $('#exams_filter').on('change', function() {

        var exam_id = $(this).val();
        //alert(exam_id);
        if (exam_id == '') {
            $('#exam_result').text('');
        }

        if (exam_id != '') {


            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>exams/get_exam_result",

                data: ({
                    exam_id: exam_id,
                    student_id: '<?php echo $student_id;?>'
                }),
                dataType: "html",
                success: function(html) {
                    if (html != '') {
                        $('#exam_result').html(html);
                    }
                }
            });
        }
    });

});
</script>
