<?php  
if (right_granted('transferrequest'))
{

    if($this->session->flashdata('club_updated')){
    	echo '<div align="center">
    	<div class="alert alert-success alert-dismissable">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
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
            <?php echo get_phrase('transfer_request');?>
        </h3>
    </div>
</div>

<div data-step="1" data-position="top" data-intro="use this filter to get student transfer information">
    <div class="row filterContainer">
        <div class="col-md-6 col-lg-6 col-sm-6">
            <select id="section_id" name="section_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                <option value="">
                    <?php echo get_phrase('select_department');?>
                </option>
        	    <?php echo section_selector();?>         
            </select>
            <div id="d2"></div>
        </div>
         <div class="col-md-3 col-lg-3 col-sm-3">
            <div id="select" class="btn btn-primary"><?php echo get_phrase('filter');?></div>
            <a id="btn_show" href="<?php echo base_url();?>transfer_student/transfer_information" class="btn btn-danger" style="padding: 5px 8px !important; display: none;"><?php echo get_phrase('remove_filter'); ?></a>
        </div>
    </div>
  
</div>
<div class="col-md-12 col-lg-12 col-sm-12">
    <div id="table" data-step="2" data-position='top' data-intro="Transfer Infromation Record">
    </div>
</div>

<!-- DATA TABLE EXPORT CONFIGURATIONS -->
<script type="text/javascript">
$(document).ready(function() {
    get_all_rec();
    $("#select").click(function() {
        $("#d1").html("");
        $("#d2").html("");
        $("#d3").html("");
        $("#d4").html("");
        if ($('#academic_year').val() == "") {

            $("#d1").html("<?php echo get_phrase('value_required'); ?>");
        } else if ($('#departments_id').val() == "") {
            $("#d2").html("<?php echo get_phrase('value_required'); ?>");
        } else if ($('#class_id').val() == "") {
            $("#d3").html("<?php echo get_phrase('value_required'); ?>");
        } else if ($('#section_id').val() == "") {
            $("#d4").html("<?php echo get_phrase('value_required'); ?>");
        } else {

            $("#btn_show").show();
            get_all_rec();

        }

    });
});

function get_all_rec() {
	var section_id = $("#section_id").val();
    $("#loading").remove();
    $("#table").html("<div id='loading' class='loader'></div>");
    $.ajax({
        type: 'POST',
        data: {
            section_id: section_id
        },
        url: "<?php echo base_url(); ?>transfer_student/get_student_info",
        dataType: "html",
        success: function(response) {

            $("#table").html(response);

        }
    });
}
</script>

<?php } ?>
