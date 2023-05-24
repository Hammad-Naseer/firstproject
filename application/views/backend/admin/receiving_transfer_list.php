<?php  
if (right_granted(array('studenttransfer')))
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
            <?php echo get_phrase('recieving_transfer_request');?>
        </h3>
    </div>
</div>

<div class="col-md-12 col-lg-12 col-sm-12">
    <div id="table" data-step="1" data-position="top" data-intro="recieving transfer request records">
    </div>
</div>

<!-- DATA TABLE EXPORT CONFIGURATIONS -->
<script type="text/javascript">

$(document).ready(function() {
    get_all_rec();
    $("#select").click(function() {
        $("#btn_show").show();
        get_all_rec();
    });
});
    function get_all_rec()
    {
        var section_id = $("#section_id").val();
        var departments_id = $("#departments_id").val();
        var class_id = $("#class_id").val();
        $("#loading").remove();
        $("#table").html("<div id='loading' class='loader'></div>");

        $.ajax({
            type: 'POST',
            data: {
                section_id: section_id,
                departments_id: departments_id,
                class_id: class_id,

            },
            url: "<?php echo base_url();?>transfer_student/get_rec_info",
            dataType: "html",
            success: function(response) {

                $("#table").html(response);

            }
        });
    }
</script>

<?php } ?>