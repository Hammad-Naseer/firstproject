<?php  
if (right_granted('admintransferapproval'))
{

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
            <?php echo get_phrase('transfer_listing_admin');?>
        </h3>
    </div>
</div>
<div data-step="1" data-position="top" data-intro="use this filter select school main branch & school sub branch then press filter get transfer list">
    <div class="row filterContainer">
        <div class="col-md-4 col-lg-4 col-sm-4">
            <select id="from_school" name="from_school" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                <?php echo school_option_list(); ?>
            </select>
            <div id="d1"></div>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-4">
            <select id="to_school" name="to_school" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                <?php echo school_option_list(); ?>
            </select>
            <div id="d2"></div>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-4">
            <div id="select" class="btn btn-primary"><?php echo get_phrase('filter');?></div>
            <a id="btn_show" href="<?php echo base_url(); ?>transfer_student/transfer_listing_req" class="btn btn-danger" style="padding: 5px 8px !important; display: none;">
            <?php echo get_phrase('remove_filter');?></a>
        </div>
    </div>
  
</div>
<div class="col-md-12 col-lg-12 col-sm-12">
    <div id="table" data-step="2" data-position="top" data-intro="admin transfer approval record">
    </div>
</div>
<!-- DATA TABLE EXPORT CONFIGURATIONS -->
<script type="text/javascript">
$(document).ready(function() {
    get_all_rec();
    $("#departments_id").change(function() {
        var dep_id = $(this).val();

        $(".loader_small").remove();
        $(this).after('<div class="loader_small"></div>');

        $.ajax({
            type: 'POST',
            data: {
                dep_id: dep_id
            },
            url: "<?php echo base_url();?>transfer_student/get_class",
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $("#section_id").html("<option value=''><?php echo get_phrase('select_class'); ?></option>");
                $("#class_id").html(response);
            }
        });
    });

    $("#class_id").change(function() {
        var class_id = $(this).val();
        $(".loader_small").remove();
        $(this).after('<div class="loader_small"></div>');
        $.ajax({
            type: 'POST',
            data: {
                class_id: class_id
            },
            url: "<?php echo base_url();?>transfer_student/get_section",
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $("#section_id").html(response);
            }
        });
    });
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
    var from_school = $("#from_school").val();
    var to_school = $("#to_school").val();
    $("#loading").remove();
    $("#table").html("<div id='loading' class='loader'></div>");
    $.ajax({
        type: 'POST',
        data: {
            from_school: from_school,
            to_school: to_school
        },
        url: "<?php echo base_url();?>transfer_student/get_student_req",
        dataType: "html",
        success: function(response) {

            $("#table").html(response);

        }
    });
}
</script>
<?php
}
?>