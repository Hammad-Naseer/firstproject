<style>
    .fa-remove{color:#FFF;}
.page { 
    padding: 2mm !important;
}

</style>

<?php 
    $student_id=$_SESSION['student_id'];
    $academic_id=intval($_SESSION['academic_year_id']);
    $term_id=intval($_SESSION['yearly_term_id']);
?>
<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('examination_results');?>  
        </h3>                
    </div>
</div>    
    
    
<form name="marksheet" id="marksheet" class="form-horizontal  validate" data-step="1" data-position='top' data-intro="Use this filter to get examination result">
    <div class="row filterContainer  px-4">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <label for="exam_id">Select Exam</label>
                <select id="exam_id" name="exam_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                    <?php echo year_exam_option_list();?>
                </select>
            </div>
        </div>
    	<div class="col-lg-6 col-md-6 col-sm-6" style="padding-top: 20px;padding-left: 30px;">
            <div class="form-group">
                <button type="submit" id="submit_exam" class="modal_save_btn" >Filter</button>
                <a style="display:none;" href="<?php echo base_url();?>student_p/marks" class="modal_cancel_btn" ><i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
            </div>
    	</div>
    </div>
</form>
<hr/>
<div id="exam_result" class="text-center">
</div>
<script type="text/javascript">
$(document).ready(function() {
    document.getElementById('marksheet').onsubmit = function() {
        return false;
    };
    $('#yearly_term').on('change', function() {
        var yearly_term = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>student_p/get_exam_type",
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
    $('#submit_exam').on('click', function() {

        var exam_id = $('#exam_id').val();
        if (exam_id == '') {
            $('#exam_result').html('');
        }
        if (exam_id != '') 
        {
            $("#exam_result").html("<div class='loader'></div>");
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>student_p/get_exam_result",

                data: ({
                    exam_id: exam_id,
                    student_id: '<?php echo $student_id;?>'
                }),
                dataType: "html",
                success: function(textResult) 
                {
                    $('#exam_result').html('');
                    $('#exam_result').html(textResult);
                    $('.modal_cancel_btn').css({'display':'inline'});
                }
            });
        }
    });

});
</script>