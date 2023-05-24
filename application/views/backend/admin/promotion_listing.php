
<?php  
    if (right_granted('students_promote'))
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

<style>
    #d1{color:red}#d2{color:red}#d3{color:red}#d4{color:red}
    lable{
        color:white !Important;
    }
    .modal_save_btn{
        cursor:pointer;
    }
</style>
<br/>

<div id="msg_div" class="col-sm-12"></div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
             <?php echo get_phrase('promotion_listing');?>
        </h3>
    </div>
</div>

<div class="row filterContainer" data-step="1" data-position='top' data-intro="Select filter and click on filter button to get specific records">
   <div class="col-sm-12">
  	   <div class="col-md-6 col-lg-6 col-sm-6">
	   </div>
   	   <div class="col-md-6 col-lg-6 col-sm-6">
        <label id="section_id_filter_selection"></label>
	   </div>
   </div>
    <div class="col-md-6 col-lg-6 col-sm-6">
        <lable>Select Current Academic Year</lable>
        <select id="acad_year" name="academic_year_id" class="form-control" required>
            <?php echo academic_year_option_list($qur_val[0]['pro_academic_year_id'],$status=1);?>
        </select>
        <div id="d1"></div>
    </div>     
    <div class="col-md-6 col-lg-6 col-sm-6">
        <lable>Select Class</lable>
        <select id="section_id_filter" class="selectpicker form-control" name="section_id">
            <?php echo section_selector();?>
        </select>
        <div id="d4"></div>
    </div>
    <div class="col-md-12 col-lg-12 col-sm-12 mb-3">
        <br>
        <div id="filter" class="modal_save_btn" style="width:80px;display:inline;"> <?php echo get_phrase('filter');?></div>
        <a id='select' class='modal_save_btn' data-step="2" data-position='top' data-intro="Select promote button get next step and select promote area then submit pormotion request"><?php echo get_phrase('promote');?></a>
        <a id="btn_show" href="<?php echo base_url(); ?>promotion/promotion_listing" class="modal_cancel_btn" style="padding: 5px 4px !important; display: none;">
            <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters'); ?> </a>
        <br>
    </div>
    
</div>

<div class="row filterContainer mt-4" style="display:none;" id="promotion">
    <div class="col-sm-12">
  	   <div class="col-md-6 col-lg-6 col-sm-6">
	   </div>
   	   <div class="col-md-6 col-lg-6 col-sm-6">
        <label id="section_id_p_selection"></label>
	   </div>
   </div>
    <div class="col-md-6 col-lg-6 col-sm-6">
        <lable>Academic year to be promoted in</lable>
        <select id="acad_year_p" name="academic_year_id_p" class="form-control" required>
            <?php echo academic_year_option_list($qur_val[0]['pro_academic_year_id'],$status=1);?>
        </select>
    </div>
    <div class="col-md-6 col-lg-6 col-sm-6" style="margin-top: -10px;">
        <label style="font-weight: 100;font-size: 12px;">Class to be promoted in</label>
        <select id="section_id_p" name="section_id_p" class="form-control selectpicker" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
            <?php echo section_selector();?>
        </select>
    </div>
    <div class="col-md-12 col-lg-12 col-sm-12">
        <div id="select_p" class="modal_save_btn" style="width:118px"><?php echo get_phrase('save_request'); ?></div>
    </div>
</div>
<div class="col-md-12 col-lg-12 col-sm-12 mt-4">
    <div id="table"></div>
</div>

<!-- DATA TABLE EXPORT CONFIGURATIONS -->
<script type="text/javascript">
$(document).ready(function() {
    $('.selectpicker').on('change', function() {
        var id = $(this).attr('id');
        var selected = $('#' + id + ' :selected');
        var group = selected.parent().attr('label');
        $('#' + id + '_selection').text(group);
    });
    $("#filter").click(function() {
        $("#d1").html("");
        $("#d2").html("");
        $("#d3").html("");
        $("#d4").html("");
        if ($('#acad_year').val() == "") {
            $("#d1").html("<?php echo get_phrase('value_required'); ?>");
        } else if ($('#section_id_filter').val() == "") {
            $("#d4").html("<?php echo get_phrase('value_required'); ?>");
        } else {
            $("#btn_show").show();
            get_all_rec();
        }
    });
    
    get_all_rec();
    $("#departments_id").change(function() {
        var dep_id = $(this).val();
        $(".loader_small").remove();
        $(this).after('<div class="loader_small"></div>');
        get_class(dep_id, '#class_id');
    });

    $("#class_id").change(function() {
        var class_id = $(this).val();
        $(".loader_small").remove();
        $(this).after('<div class="loader_small"></div>');
        get_section(class_id, '#section_id');
    });

    //get_all_rec();
    $("#departments_id_p").change(function() {
        var dep_id = $(this).val();
        $(".loader_small").remove();
        $(this).after('<div class="loader_small"></div>');
        get_class(dep_id, '#class_id_p');
    });

    $("#class_id_p").change(function() {
        var class_id = $(this).val();
        $(".loader_small").remove();
        $(this).after('<div class="loader_small"></div>');
        get_section(class_id, '#section_id_p');
    });
    
    $("#select_p").click(function() {
        $("#btn_show").show();
        var section_id = $('#section_id_filter').val();
        var section_id_p = $('#section_id_p').val();
        var acad_year_p = $('#acad_year_p').val();
        var acad_year = $('#acad_year').val();
        if (section_id == section_id_p) {
            $('#msg').remove();
            $('#msg_name').before("<span id='msg'><?php echo get_phrase('you_have_selected_the_same_class'); ?></span>");
        } else {
            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id,
                    section_id_p: section_id_p,
                    acad_year_p: acad_year_p,
                    acad_year: acad_year
                },
                url: "<?php echo base_url();?>promotion/save_promotion_req",
                dataType: "html",
                success: function(response) {
                	$('#msg_div').html(response);
                    $('#msg').remove();
                    $('#msg_y').remove();
                    get_all_rec();
                }
            });
        }
    });
    
    //    $("#select").click(function()

    $('body').delegate('#select','click',function()
    {
        $('#msg').remove();
        $("#promotion").before("<div id='loading' class='loader_small'></div>");
        $('#d1').before('<span id="msg"><?php echo get_phrase('request_already_submitted'); ?></span>');
        $('#msg').remove();
        var section_id = $('#section_id_filter').val();
        var acad_year = $('#acad_year').val();
        if (section_id == "" || acad_year == "" )
        {
            $('#msg').remove();
            $('#d1').before("<span id='msg'><?php echo get_phrase('please_select_required_values'); ?></span>");
            $('#loading').remove();
            return false;
        }else{
            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>promotion/check_promotion_req",
                dataType: "html",
                success: function(response) {
                    response = $.trim(response);
                    if (response == 'yes')
                    {
                        $('#msg').remove();
                        $('#promotion').show();
                    }
                 	else if(response == 'no') 
                 	{
                        $('#promotion').hide();
                        $('#promotion').before('<span id="msg"><?php echo get_phrase('request_already_submitted'); ?></span>');
                    }
                    else if(response=='no student')
                    {
						$('#promotion').hide();
						$('#promotion').before('<span id="msg"><?php echo get_phrase('no_student_found_in_section'); ?></span>');
					}
                    $('#loading').remove();
                }
            });
        }

    });
});


function get_section(class_id, retun_id) {
    $.ajax({
        type: 'POST',
        data: {
            class_id: class_id
        },
        url: "<?php echo base_url();?>promotion/get_section",
        dataType: "html",
        success: function(response) {
            $(".loader_small").remove();
            $(retun_id).html(response);
        }
    });
}

function get_class(dep_id, retun_id) {
    $.ajax({
        type: 'POST',
        data: {
            dep_id: dep_id
        },
        url: "<?php echo base_url();?>promotion/get_class",
        dataType: "html",
        success: function(response) {
            $(".loader_small").remove();
            $(retun_id).html("<option value=''><?php echo get_phrase('select_class'); ?></option>");
            $(retun_id).html(response);
        }
    });
}
function get_all_rec() {
    var academic_year_id = $("#acad_year").val();
    var section_id = $("#section_id_filter").val();
    $("#loading").remove();
    $("#table").html("<div id='loading' class='loader'></div>");
    $.ajax({
        type: 'POST',
        data: {
            academic_year_id: academic_year_id,
            section_id: section_id
        },
        url: "<?php echo base_url();?>promotion/get_student_info",
        dataType: "html",
        success: function(response) {
            $("#table").html(response);
        }
    });
}
</script>
<?php } ?>
