<?php
if (right_granted('students_view'))
{
?>
<style>
.myerror {
    color: red !important;
}
</style>
<?php

if ( $this->session->flashdata( 'club_updated' ) ) {
        echo '<div align="center">
	<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	' . $this->session->flashdata( 'club_updated' ) . '
	</div> 
	</div>';
}
if ( $this->session->flashdata( 'journal_entry' ) ) {
	echo '<div align="center">
	<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	' . $this->session->flashdata( 'journal_entry' ) . '
	</div> 
	</div>';
}
if ( $this->session->flashdata( 'error_msg' ) ) {
	echo '<div align="center">
	<div class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	' . $this->session->flashdata( 'error_msg' ) . '
	</div> 
	</div>';
}


if($this->session->flashdata('delete_dis')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('delete_dis').'
     </div> 
    </div>';
}

if($this->session->flashdata('delete_challan_form')){
        echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('delete_challan_form').'
     </div> 
    </div>';
}

?>
    <script>
    $(window).("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
    </script>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline">
                 <?php echo get_phrase('virtual_classes_current_list');?>
            </h3>
        </div>
    </div>
    <div>
    <form action="<?php echo base_url(); ?>virtualclass/vc_current_list" method="post" name="student_form" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific records">
        <div class="row filterContainer">
            <div class="col-md-6 col-lg-6 col-sm-6 pb-3">
            	<input type="text" name="std_search" class="form-control" value="<?php echo $std_search;?>">
            </div>    
            <div class="col-md-6 col-lg-6 col-sm-6 pb-3">
                <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                    <?php echo section_selector($section_id);?>
                </select>
                <input type="hidden" name="apply_filter" value="1">
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12">
                <input type="submit" value="Filter" class="btn btn-primary">
            </div>
            <?php if ($apply_filter == 1) { ?>
             <div class="col-md-6 col-lg-6 col-sm-6">
                <a id="btn_show" href="<?php echo base_url(); ?>virtualclass/vc_current_list" class="btn btn-danger" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
            </div>
            <?php } ?>
        </div>
    </form>
    </div>
<div class="col-lg-12 col-sm-12">
    <table class="table table-bordered table_export table-responsive" data-step="2" data-position="top" data-intro="current virtual classes record">
    <thead>
        <tr>
            <th style="width:34px;">#</th>
            <th style="width:27%;"><?php echo get_phrase('class_name');?></th>
            <th style="width:27%;"><?php echo get_phrase('virtual_class_name');?></th>
            <th><?php echo get_phrase('teacher_name');?></th>
            <th><?php echo get_phrase('status');?></th>
            <th style="width:94px;"><?php echo get_phrase('option');?></th>
        </tr>
    </thead>
    <tbody>
        <?php 
$j=$start_limit;
foreach($students as $row)
{
$j++;
?>
        <tr>
            <td class="td_middle">
                <?php  echo $j; ?>
            </td>
            <td>
                 <?php echo $row['class_name']." - ".$row['title']; ?>
            </td>
            <td>
                <?php echo urldecode($row['virtual_class_name']); ?>
            </td>
            <td>
                <?php echo $row['name']; ?>
            </td>
            <td>
                <?php 
                    $meetingId = $row['virtual_class_id'];
                    $getUrl = ICWEBRTC_LINK."api/isMeetingRunning?";
            		$params = '&meetingID='.urlencode($meetingId).
            		'&password='.urlencode('mp');
                    $url_get = $getUrl.$params.'&checksum='.sha1("isMeetingRunning".$params.ICWEBRTC_SECRET);
                    $xmldata = simplexml_load_file($url_get) or die("Failed to load");
                    //print_r($xmldata);
                    if($xmldata->running == 'true')
                        echo "<strong><span style='color:green'>Class Active</span></strong>";
                    else
                        echo "<strong><span style='color:red'>Class Inctive</span></strong>";
                ?>
            </td>
            <td class="td_middle">
            <?php
            if (right_granted(array('students_manage', 'students_promote', 'students_delete')))
            {
            ?>
                <div>
                    <div class="btn-group" data-step="3" data-position="left" data-intro="current virtual class options: view details / join">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                        <?php $meeting_id = explode("-",$row['virtual_class_id']); ?>
                        <li>
                            <a target="_blank" href="<?php echo base_url(); ?>virtualclass/view_detail/active/<?php echo $meeting_id[1]."/".$row['virtual_class_name']; ?>">
                                <i class="entypo-eye"></i>
                                <?php echo get_phrase('view_detail'); ?>
                            </a>
                        </li>
                        <li>
                            
                            <a target="_blank" href="<?php echo base_url(); ?>virtualclass/join/<?php echo  $meeting_id[1] ."/".$_SESSION['name']."/".$_SESSION['user_login_id']; ?>">
                                <i class="entypo-mic"></i>
                                <?php echo get_phrase('join'); ?>
                            </a>
                        </li>
                 </ul>
            </div>
                </div>
                <?php
                }
                ?>
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>
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
		/*
        get_all_rec();

        //////////////////////////////////////////////////////////////////////////////////////
        $("#select").click(function() {

            get_all_rec();

        });
*/
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		<?php
		/*
        $("#academic_year").change(function() {
            if ($("#academic_year").val() != "") {
                $("#myerror").html("");
            } else {
                $("#myerror").html("Value Required");
            }
        });
		*/
		?>
        ////////////////////////////////////////////////////////////////////////////////////////

    });
	/*
    function get_all_rec() {
        var section_id = $("#section_id_filter").val();
       // var student_status = $("#student_status").val();
        if (section_id != "") {

            $("#card_create").show();
            $("#card_create").attr('href', "<?php //echo base_url(); ?>c_student/create_card/section/" + section_id);
        } else {
            $("#card_create").hide();
        }

        $("#loading").remove();
        $("#table").html("<div id='loading' class='loader'></div>");

        $.ajax({
            type: 'POST',
            data: {
                section_id: section_id,
                //student_status: student_status
            },
            url: "<?php //base_url();?>c_student/get_student_info",
            dataType: "html",
            success: function(response) {
                $("#table").html(response);
            }
        });
    }
	*/
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
<?php
}
?>
