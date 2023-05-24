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
if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-danger alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
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

    /*if ( $this->session->flashdata( 'journal_entry' ) ) {
        echo '<div align="center">
	<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	' . $this->session->flashdata( 'journal_entry' ) . '
	</div> 
	</div>';
    }*/

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

/*if($this->session->flashdata('journal_entry')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('journal_entry').'
     </div> 
    </div>';
  }*/

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

                 <?php echo get_phrase('classes_name');?>
                
            </h3>
        </div>
    </div>
    <div class="thisrow" style="padding:12px;">
    <form action="<?php echo base_url(); ?>virtualclass/vc_attendance_list" method="post" name="student_form">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-6">
            	<input type="text" name="std_search" class="form-control" value="<?php echo $std_search;?>">
                
                <label id="section_id_filter_selection"></label>
                <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                    <?php echo section_selector($section_id);?>
                </select>
                <input type="hidden" name="apply_filter" value="1">
                <input type="submit" value="Filter" class="btn btn-primary">
            </div>
            <?php
			if ($apply_filter == 1)
			{ 
			//<div id="select" class="btn btn-primary">Filter</div>
			?>
             <div class="col-md-6 col-lg-6 col-sm-6">
                <a id="btn_show" href="<?php echo base_url(); ?>virtualclass/vc_attendance_list" class="btn btn-danger" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
            </div>
            <?php
			}
			?>
        </div>
    </form>
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
            <!--<th style="width:27%;"><?php echo get_phrase('virtual_class_name');?></th>-->
            <!--<th><?php echo get_phrase('teacher_name');?></th>-->
            <!--<th><?php echo get_phrase('status');?></th>-->
            <th style="width:94px;"><?php echo get_phrase('option');?></th>
        </tr>
    </thead>
    <tbody>
        <?php 
         
$j=0;
$j=$start_limit;
foreach($students as $row)
{
   // echo '<pre>'; print_r($row['section_id']);
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
                <div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                        <li>
                            <a href="<?php echo base_url();?>virtualclass/class_attendance_list/<?php print_r($row['section_id']); ?>/<?php print_r($row['class_id']); ?>">
                               <?php echo get_phrase('view_cources'); ?>
                            </a>
                        </li>
                        <?php
                            $quer = "select  * from " . get_school_db() . ".attendance_type  where school_id=" . $_SESSION['school_id'] . "";
                                    $attendance_count = $this->db->query($quer)->result_array();
                                 $type = $attendance_count[0]['login_type'];
                                // if($type == 0){
                            ?>
                        <!--<li>-->
                        <!--    <a  href="<?php echo base_url(); ?>virtualclass/add_attendance/<?php print_r($row['section_id']); ?>/<?php print_r($row['class_id']); ?>">-->
                        <!--        <?php echo get_phrase('add_attendance'); ?>-->
                        <!--    </a>-->
                        <!--</li>-->
                        <?php //} ?>
                 </ul>
            </div>
                </div>
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>
<?php
echo $pagination;
echo "<br>";
echo "Total Records: ".$total_records;
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
