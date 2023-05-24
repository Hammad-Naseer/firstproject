<style>
  .tile-stats {
    min-height: 140px !important;
  }

  .system_name.inline {
    display: inline-block;
    margin: 0;
    padding: 20px 0 5px;
    width: 100%;
}
  .img-res {
    float: none;
    height: 50px;
    width: auto;
}
  .col-mh{
    color: #4a8cbb;
    font-size: 16px;
    font-weight: bold;
    padding-top: 20px;
    text-align: right;
    text-transform: uppercase;
    }

    .blocks {
    margin: 0 auto;
    text-align: right;
}

</style>
<?php
      $d_school_id = $d_school_id;
      $branch_name = "";
      if($d_school_id=="")
      {
        $d_school_id = $_SESSION['school_id'];
      }
      else
      {
        $school_details = get_school_details($d_school_id);
        $branch_name =  $school_details['name'];
        $branch_logo =  $school_details['logo'];
        $branch_folder =  $school_details['folder_name'];
      }
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
    <div class="col-lg-5 col-md-5 col-sm-5">
        <h3 class="system_name inline">
            <?php echo get_phrase('subjectwise_attendance');?>
        </h3>
    </div>
    <div class="col-lg-7 col-md-7 col-sm-7">
        <?php if ($branch_name!= ""){?>
        <div class="row">
         <div class="col-md-10 blocks">
            <!-- <img class="img-res" src="<?php echo base_url();?>uploads/<?php echo $branch_folder."/".$branch_logo?>"> -->
            <?php
            $logo=system_path($branch_logo,$branch_folder,1);
            if($branch_logo=="" || !is_file($logo))
            {
            ?>
               <a href=""><img style="width: 150px;"  src="<?php echo base_url();?>assets/images/gsims_logo.png">
            </a>
            <?php
            }
            else
            {
                $img_size = getimagesize("uploads/".$branch_folder."/".$branch_logo."");
                $img_width = $img_size[0];
                $img_height = $img_size[1];

            ?>
            <a href="">
            <img class="img-rounded" style="
                margin-top: -9px;
                height:
                <?php
                    if ($img_height>60) {
                        $img_height = 60;
                    }
                    echo $img_height."px;";
                ?>
                " src="<?php echo base_url();?>uploads/<?php echo $branch_folder.'/'.$branch_logo; ?>">
            </a>
            <?php } ?>
          <span class="col-mh">
          <?php echo $branch_name ?>
          </span>
          </div>
          <div class="col-md-2 pull-right" style="margin-top: 9px;"><a href="<?php echo base_url().'reports/reports_listing/'.$d_school_id?>" class="btn btn-primary"><?php echo get_phrase('back'); ?></a>
          </div>
        </div>
        <?php
        }
        ?>
    </div>
    </div>
</div>

<div>
<form action="<?php echo base_url().'branch_reporting/subjectwise_attendance/'.$d_school_id?>" method="post" name="subject_wise_attendance_form" id="subject_wise_attendance_form">
    <div class="row filterContainer">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <span class="text-center" style="font-size:0.8em;color:red;font-weight:bold:padding:5px" id="subjectwiseattendancemsgspan"></span>
        </div>
        <div class="col-md-4">
              <label><?php echo get_phrase('start_date');?></label>
                  <input class="form-control" value="<?php echo $start_date; ?>" type="date" name="start_date_subjectwise" id="start_date_subjectwise" style="background-color:#FFF !important;" >
        </div>
        <div class="col-md-4">
              <label><?php echo get_phrase('end_date');?></label>
              <input class="form-control" value="<?php echo $end_date; ?>" type="date" name="end_date_subjectwise" id="end_date_subjectwise" style="background-color:#FFF !important;" >
        </div>
        <div class="col-md-4">
                 <label><?php echo get_phrase('section');?></label>
                <select id="section_id_subjectwise" class="selectpicker form-control" name="section_id_subjectwise">
                                <?php echo section_selector($section_id);?>
                </select>
        </div>
        <div class="col-md-8 col-lg-8 col-sm-6 pt-3"> 
            <input type="hidden" name="apply_filter" value="1">
            <input type="submit" id="subjectwiseattendancebtn" value="<?php echo get_phrase('filter');?>" class="btn btn-primary"> 
            <?php
            if ($apply_filter == 1)
            {
            ?>
               <a id="btn_show" href="<?php echo base_url().'student_attendance_report/subjectwise_attendance/'.$d_school_id?>" class="btn btn-danger" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
            <?php
            }
            ?>
            
            <!-- 
            
            <input class="btn btn-primary" type="submit" id="subject_wise_attendance_pdf" value="<?php echo get_phrase('get_pdf');?>">
            <input class="btn btn-primary" type="submit" id="subject_wise_attendance_excel" value="<?php echo get_phrase('get_excel');?>">
            
            -->
            
        </div>
    </div>
    <input type="hidden" id = "d_school_id" name="d_school_id" value="<?php echo $d_school_id; ?>">
</form>

<div class="col-md-12">
    <div class="">
        <table class="table table-bordered table_export" role="grid">
            <thead>
                    <tr class="table_header_attendance">
                        <th class="table_header_rol_number">
                                <?php echo get_phrase('Roll No.');?>
                        </th>
                        <th class="table_header_student_name">
                                <?php echo get_phrase('student_name');?>
                        </th>
                        <th class="table_header_student_name">
                                <?php echo get_phrase('subject');?>
                        </th>
                        <th class="table_header_student_name">
                                <?php echo get_phrase('date');?>
                        </th>
                        <th class="table_header_student_name">
                                <?php echo get_phrase('class_time');?>
                        </th>
                    </tr>
                </thead>
                
                <tbody>
                        <?php 
                        
                        
                        foreach($students as $row_stud)
                        {


                        ?>
                        
                            <tr class="gradeA attendence">
                                <td><?php echo $row_stud['roll_number'];  ?></td>
                                <td><?php echo $row_stud['student_name'];  ?></td>
                                <td><?php echo $row_stud['subject_name'];  ?></td>
                                <td><?php echo date('Y-m-d', strtotime($row_stud['time_started_at']));  ?></td>
                                <td><?php echo date("h:i A", strtotime($row_stud['time_started_at']))  . '  -  ' . date("h:i A", strtotime($row_stud['time_ended_at'])) ?></td>
                            </tr>    
                        
                        <?php
 

                        }
                        
                        ?>
                </tbody>        
              
            
        </table>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#subjectwiseattendancebtn').click(function(){
                var isvalid = true;
                var errors = '';
                if ( $('#section_id_subjectwise').val() == "" ){
                      isvalid = false;
                      errors += 'Please select the section <br>';
                }
              
                if ( $('#start_date_subjectwise').val() != "" &&  $('#end_date_subjectwise').val() != "" ){
                      
                    if ( $('#start_date_subjectwise').val()  >  $('#end_date_subjectwise').val() ){
                        isvalid = false;
                        errors += 'Start Date can not be greater than the End Date <br>';
                    }
                      
                }
                
                if(isvalid){
                  $('#subjectwiseattendancemsgspan').html('');    
                  return true;
                }
                else
                {
                  $('#subjectwiseattendancemsgspan').html(errors);    
                  return false;
                }
              
        });
        
        
        var today = new Date();
        var startDate = new Date( today.getFullYear() , today.getMonth ,1);
        $('.datepicker1').datepicker({
              format: 'dd/mm/yyyy',
              forceParse: false,
              autoclose:true,
              endDate: '+0d',
              defaultDate : new Date(),
              keyboardNavigation: false,
              startDate: '-3d'
        }).datepicker('setStartDate' , startDate);
        
        
        
        $('.datepicker1').click(function(){
            $(this).focus();
        });  
    });
</script>

<script>
    $(".table_export").DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
</script>