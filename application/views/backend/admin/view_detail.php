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

                 <?php echo get_phrase('class_detail')." ( ". $class_name." ) ";?>
                
            </h3>
        </div>
    </div>

    <?php
	
// echo "<pre>";
// print_r($all_data);
// echo "<br>";
?>
<table class="table table-bordered datatable" id="stud_info_tbl">
    <thead>
        <tr>
            <th style="width:10%;">#</th>
            <th style="width:30%;"><?php echo get_phrase('name');?></th>
            <?php if($status == 'complete'){   ?>
             <th style="width:30%;"><?php echo get_phrase('timing');?></th>
            <?php } ?>
            <th style="width:30%;"><?php echo get_phrase('role');?></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $j = 0;
        if($status == 'active'){
            foreach($all_data as $row){
                foreach($row as $row1){
                $j++;
                ?>
                        <tr>
                            <td>
                                <?php  echo $j; ?>
                            </td>
                            <td>
                                 <?php echo $row1->fullName; ?>
                            </td>
                            <td>
                                <?php 
                                if($row1->role == "VIEWER")
                                    echo "Student"; 
                                else
                                    echo "Teacher"; 
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                        
                }
        }
        else{
            $c_day = date('l');
	        $c_time = date('H:i:s');
            $q1 = 'select staff.name, staff.employee_code, vc_start_time, vc_end_time  from '.get_school_db().'.class_routine JOIN '.get_school_db().'.virtual_class ON class_routine.class_routine_id = virtual_class.class_routine_id JOIN '.get_school_db().'
            .class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
             JOIN '.get_school_db().'.subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id JOIN '.get_school_db().'.staff ON subject_teacher.teacher_id = staff.staff_id 
            JOIN '.get_school_db().'.class_section ON class_routine_settings.section_id = class_section.section_id JOIN '.get_school_db().'.class ON class.class_id = class_section.class_id
             WHERE day = "'.$c_day.'" AND vc_end_time <> "0000-00-00 00:00:00" AND virtual_class.virtual_class_id = "'.$class_id.'"';
            $classes = $this->db->query($q1)->result_array();
            foreach($classes as $row){ 
            $j++;
            ?>
                 <tr>
                    <td>
                        <?php  echo $j; ?>
                    </td>
                    <td>
                         <?php echo $row['name']; ?>
                    </td>
                    <td>
                        <?php echo date('H:i', strtotime($row['vc_start_time'])) . " - " . date('H:i', strtotime($row['vc_end_time'])); ?>
                    </td>
                    <td>
                        <?php 
                            echo "Teacher"; 
                        ?>
                    </td>
                </tr>
       <?php     }
            $q2 = 'select student_name , vc_start_time, vc_end_time  from '.get_school_db().'.virtual_class_student
             WHERE virtual_class_student.virtual_class_id = "'.$class_id.'"';
            $students = $this->db->query($q2)->result_array();
            foreach($students as $row){ 
            $j++;
            ?>
                 <tr>
                    <td>
                        <?php  echo $j; ?>
                    </td>
                    <td>
                         <?php echo $row['student_name']; ?>
                    </td>
                    <td>
                        <?php echo date('H:i', strtotime($row['vc_start_time'])) . " - " . date('H:i', strtotime($row['vc_end_time'])); ?>
                    </td>
                    <td>
                        <?php 
                            echo "Student"; 
                        ?>
                    </td>
                </tr>
       <?php 
            }
       
        }?>
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
