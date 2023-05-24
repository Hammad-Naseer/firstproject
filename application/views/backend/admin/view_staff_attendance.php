<?php
if (right_granted('staffattendance_view'))
{
?>
<style>
.blue {
    color: #e99711;
}
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
           <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('view_staff_attendance');?>
        </h3>
    </div>
</div>


<form id="filter" name="filter" method="post" class="form-groups-bordered validate" style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px;">
     
    <div class="row filterContainer" data-step="1" data-position='top' data-intro="Please select the filters and press filter button to get record">
        <div class="col-lg-4 col-md-4 col-sm-4 form-group mt-4">
                    <input type="text" autocomplete="off"  name="starting" id="starting" placeholder="Select Starting Date" class="form-control datepicker" data-validate="required" 
                          data-message-required="Value Required" data-format="dd/mm/yyyy" >
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 form-group mt-4">
                    <input type="text" autocomplete="off"  name="ending" id="ending" placeholder="Select Ending Date" class="form-control datepicker" data-validate="required" 
                           data-message-required="Value Required" data-format="dd/mm/yyyy" >
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 form-group mt-4">
                <select id="staff_id" class="form-control" name="staff_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <?php  echo staff_list();  ?>
                </select>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                <input type="submit" class="modal_save_btn" id="btn_view" value="<?php echo get_phrase('filter');?>"></input>
                 <a href="<?php echo base_url(); ?>attendance_staff/view_staff_attendance" style="display: none;" class="modal_cancel_btn" id="btn_remove"> 
                 <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>
        </div>
    </div>
        
</form>

<div class="row filterContainer" style="padding-top: 14px;margin:0px;margin-right: 30px !important;margin-left: 30px !important;" id="month_div1" style="display: none;">
    <div class="col-lg-4 col-md-4 form-group">
        <select id="month_list" name="month_list" class="form-control">
            <option value=""><?php echo get_phrase('select_month'); ?></option>
        </select>
    </div>
    <div class="col-lg-4 col-md-4 form-group">
           <span class="month myttl" id="month_div"></span>
    </div>    
</div>

<div class="row">
    <div class="col-lg-12 col-md-12">
           <div id="attend_show"></div>
    </div>     
</div>


<script>
$(document).ready(function() {
    document.getElementById('filter').onsubmit = function() {
        return false;
    };

     $('#month_div1').hide();

    $('.selectpicker').on('change', function() {
        var id = $(this).attr('id');
        var selected = $('#' + id + ' :selected');
        var group = selected.parent().attr('label');
        $('#' + id + '_selection').text(group);
    });

    $("#btn_view").click(function() {
        var start_date = $('#starting').val();
        var end_date=$('#ending').val();
        var staff_id = $('#staff_id').val();
        if (start_date != "" && end_date!="" && staff_id > 0) {
            $('#btn_remove').show();
            $.ajax({
                type: 'POST',
                data: {
                    start_date: start_date,
                    end_date:end_date
                },
                url: "<?php echo base_url();?>attendance_staff/month_create",
                dataType: "html",
                success: function(response) {
                	
                var staff_id = $('#staff_id').val();
        		var starting=start_date;
        		start_array=starting.split("/");
                var start_val=start_array[2]+'-'+start_array[1]+'-'+start_array[0];
        		var ending=end_date;
        		end_array=ending.split("/");
        		var end_val=end_array[2]+'-'+end_array[1]+'-'+end_array[0];
               
                    $('#month_div1').show();
                    var obj = jQuery.parseJSON(response);
                    var month_year = obj.month_current;

                    var str = "";
                    var date_index = "";
                    str += '<option value="">Select Month</option>';
                    for (var i = 0; i < obj.month.length; i++) {
                        if (obj.month[i] == month_year) {
                            date_index = [i + 1];
                        }
                        str += '<option value="' + obj.month[i] + '">' + obj.month[i] + '</option>';
                    }
                    $("#month_list").html(str);
                    var month_year = obj.month_current;
                    var month_array = month_year.split("-");
                    var month_val = month_array[0];
                    var year_val = month_array[1];
                    $("#month_list").prop("selectedIndex", date_index);
                    $('#month_div').html(month_year);
                    var staff_id = $('#staff_id').val();
                    attendance(month_val, year_val, staff_id);
                }
            });
        }
    });

    $('#staff_id').change(function() {
        $('#attend_list').html('');
        $('#attend_show').html('');
        $('#month_div1').hide();
    });

    $('#month_list').change(function() {
        var month_list = $('#month_list').val();
        $('#month_div').html(month_list);
        var str = month_list.split("-");
        var month = str[0];
        var year = str[1];
        var staff_id = $('#staff_id').val();

        $('#get_planner2').html('<div id="message" class="loader"></div>');
        $.ajax({
            type: 'POST',
            data: {
                month: month,
                year: year,
                staff_id: staff_id
            },
            url: "<?php echo base_url();?>attendance_staff/attendance_generator",
            dataType: "html",
            success: function(response) {
                $('#attend_show').html(response);
            }
        });

    });

});


function attendance(month, year, staff_id) {

    $.ajax({
        type: 'POST',
        data: {
            month: month,
            year: year,
            staff_id: staff_id
        },
        url: "<?php echo base_url();?>attendance_staff/attendance_generator",

        dataType: "html",
        success: function(response) {
            $('#attend_show').html(response);



        }
    });




}
</script>
<?php
}
?>


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
