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

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline capitalize">
            <?php echo get_phrase('assign_substitute');?>
        </h3>
    </div>
</div>


<div class="col-lg-12 col-sm-12">
    <form action="" data-step="1" data-position='top' data-intro="select date to get absent teachers record & assign subsitute teacher" 
      method="post" id="filter" class="form-horizontal form-groups-bordered validate" style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px; "> 
        <div class="row filterContainer" style="padding-top: 14px;margin:0px;">
            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
               <input type="text" name="starting" id="starting" value="<?php
                    if(isset($current_date) && $current_date > 0)
                    {
                        $date=date_dash($current_date);
    			    }
    			    else
    				{
    					$date=date('d/m/Y');
    				}
    				echo $date;?>" placeholder="Select Date" class="form-control datepicker" data-format="dd/mm/yyyy">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 form-group text-center">
                <input type="submit" value="<?php echo get_phrase('filter');?>" class="btn btn-primary" id="btn_submit">
                <a href="<?php echo base_url(); ?>asign_substitute/listing_asign" style="display: none;" class="btn btn-danger" id="btn_remove">
                     <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter');?>
                </a>
            </div>
        </div>
    </form>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="div_msg"></div>
        <div class="tab-content">
            <div class="tab-pane box active" id="list"></div>
            <div class="tab-pane box" id="add" style="padding: 5px"></div>
        </div>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function() {
    document.getElementById('filter').onsubmit = function() {
        return false;
    };

    var date = $('#starting').val();
    $('#list').load("<?php echo base_url(); ?>asign_substitute/present_absent/"+date);


    var attend_id1='<?php echo $attend_id1;?>';
    var staff_id1='<?php echo $staff_id1;?>';
    var day1='<?php echo $day1;?>';

    if(attend_id1!="" && staff_id1!="" && day1!="")
    {
        $('#list').load("<?php echo base_url(); ?>asign_substitute/present_absent/"+date+"/"+attend_id1+"/"+staff_id1+"/"+day1);
    }	
    $(document).on('click', "#btn_submit", function() {
        var date = $('#starting').val();
        if (date != "") {
            $('#btn_remove').show();
            $('#list').html('<div id="message" class="loader"></div>');
            $.ajax({
                type: 'POST',
                data: {
                date: date
             },
                url: "<?php echo base_url();?>asign_substitute/present_absent",
                dataType: "html",
                success: function(response) {
                    $('#message').remove();
                    $('#list').html(response);
                }
            });
        } else {
            $("#list").html("<?php echo get_phrase('please_select_any_filter_to_proceed'); ?>");
        }
    });
});
</script>
