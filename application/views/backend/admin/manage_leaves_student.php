<?php 
if (right_granted('studentleaves_manage'))
{?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i>Interactive tutorial</b>
        </a>
        <h3 class="system_name" style="display:inline;">
            	 <?php echo get_phrase('manage_student_leaves');?>
        </h3>
    </div>
</div>

<div data-step="1" data-position="top" data-intro="Please select filter and click on filter button to get specific leave records">
    <div class="col-sm-12">
        <form action="" method="post" class="validate" id="filter">
            <div class="row filterContainer">
                <div class="col-lg-6 col-md-6 col-sm-6 ">
                    <input type="text" name="starting" autocomplete="off" id="starting" placeholder="Select Starting Date" class="form-control datepicker" data-format="dd/mm/yyyy">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 ">
                   <input type="text" name="ending" autocomplete="off" id="ending" placeholder="Select Ending Date" class="form-control datepicker" data-format="dd/mm/yyyy">
                </div>    
                <div class="col-lg-6 col-md-6 col-sm-6 mgt10">
                    <select id="leave_category_id" name="leave_category_id" class="form-control">
                        <?php echo get_leave_category();?>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 mgt10">
                    <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                        <?php echo section_selector();?>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 mgt10">
                    <input type="submit" class="btn btn-primary" id="btn_submit" value=" <?php echo get_phrase('filter');?>">
                    <a href="<?php echo base_url(); ?>leave/manage_leaves_student" style="display: none;" class="modal_cancel_btn" id="btn_remove"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter');?></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tab-content">
            <div class="tab-pane box active" id="list">
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    document.getElementById('filter').onsubmit = function() {
        return false;
    };

    $('.selectpicker').on('change', function() {
        var id = $(this).attr('id');
        var selected = $('#' + id + ' :selected');
        var group = selected.parent().attr('label');
        alert(group);
        $('#' + id + '_selection').text(group);
    });
    $('#list').load("<?php echo base_url(); ?>leave/stud_leave_generator");
    $(document).on('click', "#btn_submit", function() {
        var start_date = $('#starting').val();
        var end_date=$('#ending').val();
        var section_id = $('#section_id_filter').val();
        var leave_categ_id = $('#leave_category_id').val();
        if (start_date !="" || end_date!="" || section_id!="" || leave_categ_id !="") {
            $('#btn_remove').show();
            $('#list').html("<div id='icon' class='loader'></div>");
            $.ajax({
                type: 'POST',
                data: {
                    start_date: start_date,
                    end_date:end_date,
                    section_id: section_id,
                    leave_categ_id: leave_categ_id
                },
                url: "<?php echo base_url();?>leave/stud_leave_generator",
                dataType: "html",
                success: function(response) {
                    $('#icon').remove();
                    $('#list').html(response);
                }
            });

        } else {
			$('#list').html('<?php echo get_phrase('please_select_any_filter_to_proceed'); ?>');
        }
    });

});
</script>
<?php } ?>

<!--//***********************Date filter validation***********************-->
<script>
    $("#starting").change(function () {
        var startDate = s_d($("#starting").val());
        var endDate = s_d($("#ending").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("starting").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
        // }
    });
    
    $("#ending").change(function () {
        var startDate = s_d($("#starting").val());
        var endDate = s_d($("#ending").val());
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementById("ending").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("ending").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
</script>
<!--//********************************************************************-->