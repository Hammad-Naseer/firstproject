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
    $(window).load(function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
    </script>
     <?php 
    $currentDay = date("Y-m-d");
        	    $sectionV_id = $this->uri->segment(3);
                $classV_id = $this->uri->segment(4); 
                $schoolID = $_SESSION['school_id'];
        	    $q = 'SELECT student.name, class_section.title, class_section.section_id 
        	    FROM '.get_school_db().'.`class_section` JOIN '.get_school_db().'.`student` ON class_section.section_id = student.section_id
        	    WHERE student.section_id = '. "' $sectionV_id '" . 'AND class_section.school_id = '. "' $schoolID '";
        	   // echo $q;
                $classes = $this->db->query($q)->result_array();
    ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <h3 class="system_name inline">
                <!--  <i class="entypo-right-circled carrow">
                        </i>-->
                <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/student-info.png">
                <?php
	            
                  echo   get_phrase('Attendence') . ' ' .  $currentDay   . ' ' .   $classes[0]['title']; 
?>
        </div>
    </div>

   
<table class="table table-bordered datatable" id="stud_info_tbl">
    <thead>
        <tr>
            <th style="width:3%;">#</th>
            <th style="width:7%;"><?php echo get_phrase('subject_name');?></th>
            <th style="width:15%;"><?php echo get_phrase('start_time');?></th>
            <th style="width:15%;"><?php echo get_phrase('end_time');?></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $j = 0;
                foreach($classes as $row1){
                   //echo '<pre>'; print_r($row1); exit;
                $j++;
                ?>
                        <tr>
                            <td>
                                <?php  echo $j; ?>
                            </td>
                            <td>
                                 <?php  print_r($row1["name"]); ?>
                            </td>
                            <td>
                                <?php  print_r($row1["vc_start_time"]); ?>
                            </td>
                            <td>
                                <?php  print_r($row1["vc_end_time"]); ?>
                            </td>
                        </tr>
                        <?php
                    }
                        
                
        ?>
    </tbody>
</table>
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
