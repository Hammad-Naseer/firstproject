
<?php
//echo $acad_year;
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
            <?php echo get_phrase('manage_planner');?>
        </h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <?php
        if (right_granted('academicplanner_manage')){?>
            <a href="javascript:;" data-step="1" data-position="left" data-intro="press this button to add new to academic planner" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_acad_plan/');" class="btn btn-primary pull-right">
                <i class="entypo-plus-circled"></i>
                <?php echo get_phrase('add_task');?>
            </a>
        <?php }?>
    </div>
</div>

    <form action="" method="post" id="filter" name="filter" class="validate">
        <div class="row filterContainer mt-5" data-step="2" data-position="top" data-intro="Please select the filters and press Filter button to get specific records">
            <div class="col-lg-6 col-md-6 col-sm-6 mt-3">
                <input type="text" name="date" autocomplete="off"  id="starting" placeholder="Select Starting Date" class="form-control datepicker" data-validate="required" data-message-required="Value Required" data-format="dd/mm/yyyy">
                <span id="sd" style="color: red;"></span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 mt-3">
                <input type="text" name="ending" autocomplete="off"  id="ending" placeholder="Select Ending Date" class="form-control datepicker" data-validate="required" data-message-required="Value Required" data-format="dd/mm/yyyy">
                <span id="ed" style="color: red;"></span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 mgt10 ">
                <select id="section_id_filter" class="selectpicker form-control" name="section_id" data-validate="required" data-message-required="Value Required">
                    <?php echo section_selector();?>
                </select>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6  mgt10">
                <select id="subject_select" class="form-control">
                </select>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                <input type="submit" class="btn btn-primary" id="btn_submit" value=" <?php echo get_phrase('filter');?>">
                <a href="<?php echo base_url(); ?>academic_planner/acad_planner" style="display: none;" class="btn btn-danger" id="btn_remove"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter')?></a>

            </div>
        </div>
    </form>
    
<div id="get_planner" class="col-lg-12 col-md-12 col-sm-12">
</div>
<div id="get_planner" class="col-lg-12 col-md-12 col-sm-12">
</div>
<div class="row thisrow" id="month_div1" style="display: none;">
    <div class="col-sm-4">
        <select id="month_list" name="month_list" class="form-control">
            <option value=""><?php echo get_phrase('select_month'); ?></option>
        </select>
    </div>
    <div class="col-sm-3"><span class="month myttl" id="month_div">
		
	</span></div>
</div>
<?php if (right_granted('academic_planner_backup'))
{?>
    <div id="btn_csv"></div>
    <?php
}
?>

<div id="get_planner2" class="row">
</div>
<script>
    $(document).ready(function()
    {

        document.getElementById('filter').onsubmit = function()
        {
            return false;
        };

        $('#subject_select').html('<select data-validate="required" data-message-required="Value Required"><option value=""><?php echo get_phrase('select_subject'); ?></option></select>');
        $('.selectpicker').on('change', function()
        {
            var id = $(this).attr('id');
            var selected = $('#' + id + ' :selected');
            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);
        });
    $("#section_id_filter").change(function() {

            $('#month_div1').hide();
            $('#get_planner2').html('');
            $('#get_planner').html('');
            var section_id = $(this).val();

            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');

            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>academic_planner/get_section_subject",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();

                    $("#subject_select").html(response);
                    $("#subject_select").attr("required","required");

                }
            });



        });

        function save_data()
        {

            var myform = document.getElementById("save_form");

            $.ajax({
                url: "<?php echo base_url();?>academic_planner/save_planner",
                type: "POST",
                data: new FormData(myform),
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    //alert(data);
                    $('#modal_ajax').hide();
                    $(".close").trigger('click');
                    get_planner();
                }

            });
        }

        $(document).on('click', "#btn_submit", function()
        {
            var start_date = $('#starting').val();
            var end_date = $('#ending').val();
            var section_id_filter = $('#section_id_filter').val();
            var subject_select = $('#subject_select').val();
            $('#get_planner2').html('');
            if (start_date != "" && end_date!= "" && section_id_filter!= "" && subject_select!= "")
            {
                $('#get_planner').html('<div id="message" class="loader"></div>');
                $('#message').remove();
                $('#btn_remove').show();
                $.ajax({
                    type: 'POST',
                    data: {
                        start_date: start_date,
                        end_date:end_date
                    },
                    url:"<?php echo base_url();?>academic_planner/month_create",
                    dataType: "html",
                    success: function(response) {

                       // $("#btn_submit").attr('disabled','disabled');
                        $('#month_div1').show();
                        var obj = jQuery.parseJSON(response);
                        var month_year = obj.month_current;
                        var str = "";
                        var date_index = "";
                        str += '<option value=""><?php echo get_phrase('select_month'); ?></option>';
                        for (var i = 0; i < obj.month.length; i++) {
                            if(obj.month[i] == month_year) {
                                date_index = [i+1];
                            }
                            str += '<option value="'+obj.month[i]+'">'+obj.month[i]+'</option>';
                        }
                        $("#month_list").html(str);
                        var month_year = obj.month_current;
                        var month_array = month_year.split("-");
                        var month_val = month_array[0];
                        var year_val = month_array[1];


                        $("#month_list").prop("selectedIndex", date_index);
                        $('#month_div').html(month_year);
                        month(month_val, year_val);

                    }
                });

            } else
            {

            }

        });

        $('#month_list').change(function() {
            var month_list = $('#month_list').val();
            $('#month_div').html(month_list);
            var str = month_list.split("-");
            var month = str[0];
            var year = str[1];

            var section_id = $('#section_id_filter').val();
            var subject_id = $('#subject_select').val();

            $('#get_planner2').html('<div id="message" class="loader"></div>');
            $.ajax({
                type: 'POST',
                data: {
                    month: month,
                    year: year,
                    section_id: section_id,
                    subject_id: subject_id
                },
                url: "<?php echo base_url();?>academic_planner/academic_plan_generator",

                dataType: "html",
                success: function(response) {
                    $('#get_planner2').html(response);



                }
            });

        });



        $('#subject_select').change(function() {
            $('#month_div1').hide();
            $('#get_planner2').html('');
        });
    });

    function month(month, year)
    {
        var section_id = document.getElementById('section_id_filter').value;
        var subject_id = document.getElementById('subject_select').value;
        $.ajax({
            type: 'POST',
            data: {
                month: month,
                year: year,
                section_id: section_id,
                subject_id: subject_id
            },
            url: "<?php echo base_url();?>academic_planner/academic_plan_generator",

            dataType: "html",
            success: function(response)
            {
                $('#get_planner2').html(response);



            }
        });
    }


</script>


        <!--    Date checking script -->
    <script>
    $('#is_closed').change(function(){
        this.value = this.checked ? 1 : 0;
    });
    <!--//***********************Date filter validation***********************-->

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

<!--//********************************************************************-->
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