<?php
if (right_granted('students_view'))
{
?>
<style>
.myerror {
    color: red !important;
}
</style>
    <script>
    $(window).load(function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
    </script>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <h3 class="system_name inline">
                <!--  <i class="entypo-right-circled carrow">
                        </i>-->
                <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/student-info.png">

                 <?php echo get_phrase('virtual_classes_list');?>
                
            </h3>
        </div>
    </div>

    <?php
	
// echo "<pre>";
// print_r($page_data);
// echo "<br>";
?>
<table class="table table-bordered datatable" id="stud_info_tbl">
    <thead>
        <tr>
            <th style="width:34px;">#</th>
            <th style="width:27%;"><?php echo get_phrase('class_name');?></th>
            <th style="width:27%;"><?php 
            if($type == 'not_complete')
                echo get_phrase('subject_name');
            else
                echo get_phrase('virtual_class_name');
            ?></th>
            <th><?php echo get_phrase('teacher_name');?></th>
            <th><?php echo get_phrase('class_time');?></th>
            <th><?php echo get_phrase('status');?></th>
            <?php
                if($type != 'not_complete'){
            ?>
            <th style="width:94px;"><?php echo get_phrase('option');?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php 
$j=0;
foreach($students as $row)
{
$j++;
?>
        <tr>
            <td>
                <?php  echo $j; ?>
            </td>
            <td>
                 <?php echo $row['class_name']." - ".$row['title']; ?>
            </td>
            <td>
                <?php 
                    if($type == 'not_complete')
                        echo $row['subject']." - ". $row['code']; 
                    else
                        echo $row['virtual_class_name']; 
                ?>
            </td>
            <td>
                <?php echo $row['name']; ?>
            </td>
            <td>
                <?php echo $row['period_start_time']." - " .$row['period_end_time']; ?>
            </td>
            <td>
                <?php 
                     if($type == 'not_complete')
                        echo "<strong><span style='color:blue'>Not Complete</span></strong>";
                     else{
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
                     }
                ?>
            </td>
            <?php
                if($type != 'not_complete'){
            ?>
            <td>
            
                <div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                <?php $meeting_id = explode("-",$row['virtual_class_id']); ?>
                                <li>
                                    <a href="<?php echo base_url(); ?>virtualclass/view_detail/<?php echo $meeting_id[1]."/".$row['virtual_class_name']; ?>">
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
            </td>
            <?php } ?>
        </tr>
        <?php }?>
    </tbody>
</table>
<?php
if($j == 0)
    echo "No Record Found";
else
    echo "Total Records: ".$j;

?>
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
