<?php
$exam_id=$this->uri->segment(3);
$section_id=$this->uri->segment(4);

$type=$this->uri->segment(5);
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
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('student_evaluation');?>
        </h3>
    </div>
</div>
<br />
<form name="filter" id="filter" method="post" class="form-horizontal  validate">
    <div>
        <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select filters and press filter button to get specific records">
            <div class="col-sm-4">
                <!--<label id="evaluation_types_label"></label>-->
                <select id="evaluation_types" name="type" class="selectpicker_type form-control" data-validate="required" data-message-required="Value Required">
                    <option value=''>Select Type</option>
                    <option <?php if($type == '1') { echo 'selected';} ?> value='1'><?php echo get_phrase('exam');?></option>
                    <option <?php if($type == '2') { echo 'selected';} ?> value='2'><?php echo get_phrase('general');?></option>
                </select>
            </div>
            <div class="col-sm-4" id='exam-field' style="<?php echo ($type == '2')?'display:none':'';?>">
                <!--<label id="yearly_terms1_selection"></label>-->
                <select id="yearly_terms1" name="yearly_terms1" class="selectpicker form-control" data-validate="required" data-message-required="Value Required" >
                    <?php
                    $status_year=array(3);
                    $status_term=array(3);
                    echo yearly_term_selector_exam($exam_id,$status_year,$status_term); ?>
                </select>
            </div>
            <div class="col-sm-4">
                <!--<label id="section_id1_selection"></label>-->
                <select id="section_id1" class="selectpicker form-control" name="section_id" data-validate="required" data-message-required="Value Required">
                    <?php echo section_selector($section_id);?>
                </select>
                <div id="section-err"></div>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12 mt-3">
                <input type="submit" id="select" class="btn btn-primary" value="<?php echo get_phrase('filter');?>">
                <a href="<?php echo base_url(); ?>student_evaluation/stud_eval/" style="display: none;" class="btn btn-danger" id="btn_remove"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter');?></a>
            </div>
        </div>
    </div>
</form>

<div id="stud"></div>

<script type="text/javascript">
    $(document).ready(function() {
        if($('.selectpicker_type').val() == '2'){
            $('#exam-field').hide();
            $(this).prop('required',false);
        }
        var section_id = $("#section_id1").val();
        var exam_id = $("#yearly_terms1").val();
        var type = $("#evaluation_types").val();
        if (type != '' && section_id != '') {

            $('#btn_remove').show();
            $("#stud").html("<div id='loading' class='loader'></div>");
            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id,
                    exam_id: exam_id,
                    type: type,
                    evaluated_by:1
                },
                url: "<?php echo base_url();?>student_evaluation/get_stud_eval",
                dataType: "html",
                success: function(response) {

                    $("#loading").remove();
                    $("#stud").html(response);
                }
            });
        }



        document.getElementById('filter').onsubmit = function() {
            return false;
        };
    
        $('.selectpicker_type').on('change', function() {
            $('#evaluation_types_label').text('Types');
            if($(this).val() == '2'){
                $('#exam-field').hide();
                $(this).prop('required',false);
            }
            else{
                $('#exam-field').show();
                $(this).prop('required',true);
            }
        });
        
        $('.selectpicker').on('change', function() {
            var id = $(this).attr('id');

            var selected = $('#' + id + ' :selected');

            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);





        });

        $("#select").click(function()
        {
            $('#filter').validate();
            var section_id = $("#section_id1").val();
            var exam_id = $("#yearly_terms1").val();
            var type = $("#evaluation_types").val();
            if (section_id != '' && type != '')
            {
                $('#btn_remove').show();
                $("#stud").html("<div id='loading' class='loader'></div>");
                $.ajax({
                    type: 'POST',
                    data: {
                        section_id: section_id,
                        exam_id: exam_id,
                        type: type,
                        evaluated_by:1
                    },
                    url: "<?php echo base_url();?>student_evaluation/get_stud_eval",
                    dataType: "html",
                    success: function(response) {
                       // $("#select").attr('disabled','disabled');
                        $("#loading").remove();
                        $("#stud").html(response);
                    }
                });
            } else
            {
                $("#stud").html('');
            }
        });
    });
</script>
