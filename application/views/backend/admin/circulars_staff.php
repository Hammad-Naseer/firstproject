<?php
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
    
    <div class="row ">
       <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
           <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline capitalize">
                <?php echo get_phrase('staff_circulars'); ?>
            </h3>
        </div>
    </div>
        <form id="filter" name="filter" method="post" class="form-horizontal form-groups-bordered validate" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific records" style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px;">
            <div class="row filterContainer">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <input type="text" name="starting" autocomplete="off"  id="starting" placeholder="Select Starting Date" class="form-control datepicker" data-format="dd/mm/yyyy">
                    <label style="color: red;" id="sd"></label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                <input type="text" name="ending" id="ending" autocomplete="off"  placeholder="Select Ending Date" class="form-control datepicker" data-format="dd/mm/yyyy">
                <label style="color: red;" id="ed"></label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 mgt10">
                    <select id="staff_select" class="form-control">
                        <?php
                        echo staff_list();
                        ?>
                    </select>
                </div>  
                <div class="col-lg-6 col-md-6 col-sm-6 mgt10">
                    <select id="staff_general" class="form-control">
                    <option value=""><?php echo get_phrase('general_circular');?></option>
                    <option value="hide"><?php echo get_phrase('hide_general_circular');?></option>
                    <option value="show"><?php echo get_phrase('show_general_circular');?>
                       
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 mgt10">
                    <input type="submit" value="<?php echo get_phrase('filter'); ?>" class="btn btn-primary" id="btn_submit">
                    <a href="<?php echo base_url(); ?>circular_staff/circulars_staff" style="display: none;" class="btn btn-danger" id="btn_remove">
                        <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>

                </div>
            </div>
        </form>
   
    <div id="list" class="col-lg-12 col-sm-12" data-step="3" data-position="top" data-intro="staff circulars records">
    	
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
            $('#' + id + '_selection').text(group);
        });


        $('#list').load("<?php echo base_url(); ?>circular_staff/circular_generator");

        $(document).on('click', "#btn_submit", function() {
            var start_date = $('#starting').val();
            var end_date = $('#ending').val();
            var staff_id = $('#staff_select').val();
            var staff_general = $('#staff_general').val();

            if (start_date != "" || end_date!='' || staff_id!="" || staff_general!="") {
                $('#btn_remove').show();
                $('#list').html('<div id="icon" class="loader"></div>');
                $.ajax({
                    type: 'POST',
                    data: {
                        start_date: start_date,
                        end_date:end_date,
                        staff_id: staff_id,
                        staff_general:staff_general
                    },
                    url: "<?php echo base_url();?>circular_staff/circular_generator",
                    dataType: "html",
                    success: function(response) {
                        $('#list').html(response);
                    }
                });
            } else {
                $('#list').html('<?php echo get_phrase('please_select_any_filter_to_proceed'); ?>');
            }
        });  

    });
    </script>

            <!--    Date checking script -->
    <script>
    $('#is_closed').change(function(){
    this.value = this.checked ? 1 : 0;
        });

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
