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
    /*.top{padding-top: 10px;}*/
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
        <div class="col-lg-4 col-md-4 col-sm-4">
        <h3 class="system_name inline">
            <?php echo get_phrase('unpaid_students');?>
        </h3>
        </div>
    <div class="col-lg-8 col-md-8 col-sm-8">
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
                <?php
                }

                ?>
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
<form action="<?php echo base_url().'reports/unpaid_students/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
    <div class="row filterContainer"> 
            <div class="col-md-4 col-lg-4 col-sm-4">
                <label><?php echo get_phrase('keyword_search');?></label>
                <input type="text" name="std_search" class="form-control" value="<?php echo $std_search;?>" placeholder="Keyword">
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4">
                <label id="section_id_filter_selection"><?php echo get_phrase('select_department')." / ".get_phrase('class')." / ".get_phrase('section');?></label>
                <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                <option value="">
                <?php echo get_phrase('select_any_option');?>  
                </option>
                <?php echo department_class_section($section_id,$d_school_id);?>
                </select>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4">
            <label><?php echo get_phrase('select_student');?></label>
                <select id="student_select" class="form-control"  name="student_id">
                    <option value=""><?php echo get_phrase('select_student'); ?></option>
                </select>
            </div>  
            <div class="col-md-6 col-lg-6 col-sm-6">
             <label><?php echo get_phrase('start_date');?></label>
                <input class="form-control datepicker" type="text" name="startdate" id="startdate" value="<?php echo $start_date ?>" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                    <div id="d3"></div>
            </div> 
            <div class="col-md-6 col-lg-6 col-sm-6">
                <label><?php echo get_phrase('end_date');?></label>
                <input class="form-control datepicker" type="text" name="enddate" id="enddate" value="<?php echo $end_date ?>" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                    <div id="d3"></div>
            </div>  
            <div class="col-md-6 col-lg-6 col-sm-6 pt-3">
                <input type="hidden" name="apply_filter" value="1">
                <input type="submit" id="filter" value="<?php echo get_phrase('filter');?>" class="btn btn-primary">
            
            <?php if ($apply_filter == 1){?> 
                <a id="btn_show" href="<?php echo base_url().'reports/unpaid_students/'.$d_school_id?>" class="btn btn-danger" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
             
            <?php } ?> 
                <input class="btn btn-primary" type="submit" id="pdf" value="<?php echo get_phrase('get_pdf');?>">
                <input class="btn btn-primary" type="submit" id="excel" value="<?php echo get_phrase('get_excel');?>">
            </div>
    </div>
    <input type="hidden" id = "d_school_id" name="d_school_id" value="<?php echo $d_school_id; ?>">
</form>
</div>

<div class="col-md-12">
    <table class="table table-bordered table-hover table-striped table-condensed table-sm table_export" role="grid">
        <thead>
            <tr>
            <th><?php echo get_phrase('sr');?>.</th>
            <th><?php echo get_phrase('image');?></th>
            <th><?php echo get_phrase('student_details');?></th>
            <th><?php echo get_phrase('chalan_form_no');?></th>
            <th><?php echo get_phrase('issue_date');?></th>
            <th><?php echo get_phrase('due_date');?></th>
            <th><?php echo get_phrase('amount');?></th>
            <th><?php echo get_phrase('discount');?></th>
                
            </tr>
        </thead>
        <tbody style="width: 250px;">
        <?php
        $count = 0;
         // echo "<pre>";
         // print_r($discount_amount_arr);
         //unpaid_std_arr
        foreach ($unpaid_std_arr as $key => $value)
        {
            $count++;
        ?>
            <tr>
                <td><?php echo $count;?></td>
                <td>
                    <img src="<?php
                        if($value['image']==''){
                            echo  base_url().'/uploads/default.png'; 
                        }else{
                            echo  display_link($value['image'],'student');
                        }
                    ?>" class="img-circle" width="30" />
                </td>
                <td>
                    <strong><?php echo get_phrase('name');?> :</strong>
                    <?php echo $value['student_name'];?></br>
                    <strong><?php echo get_phrase('father_name');?> :</strong>
                    <?php echo $value['father_name'];?></br>
                    <strong><?php echo get_phrase('roll_no');?> :</strong>
                    <?php echo $value['roll'];?></br>
                    <strong><?php echo get_phrase('department');?>/<?php echo get_phrase('class');?>/<?php echo get_phrase('section');?>: </strong>
                    <ul class="breadcrumb" style=" display: inline;  padding: 2px; margin-left: 5px; color: #428abd;">
                        <li>
                            <?php echo $value['department']; ?>
                        </li>
                        <li>
                            <?php echo $value['class']; ?>
                        </li>
                        <li>
                            <?php echo $value['section']; ?>
                        </li>
                    </ul></br>
                    <strong><?php echo get_phrase('cell_no');?> :</strong>
                    <?php echo $value['mob_num'];?></br>
                    <strong><?php echo get_phrase('reg_no');?> :</strong>
                    <?php echo $value['reg_num'];?></br>
                    <span style="display: none;"><?php echo $value['system_id'];?></span>
                </td>
                <td><?php echo $value['chalan_form_number'];?></td>
                <td>
                <?php
                if (isset($value['issue_date']) && !empty($value['issue_date']))
                {
                    $issue_date = date_view($value['issue_date']);
                    echo $issue_date;
                }
                ?>
                </td>
                <td>
                <?php
                if (isset($value['due_date']) && !empty($value['due_date']))
                {
                    $due_date = date_view($value['due_date']);
                    echo $due_date;
                }
                ?>  
                </td>
                <td><?php echo $value['actual_amount'];?></td>
                <td>
                <?php
                echo $discount_amount_arr[$value['s_c_f_id']];
                ?>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        </table>
</div>


<script>
    $(document).ready(function(){
        $('#table').DataTable({
            //"scrollX": true
            "ordering": false
        });

        // $(document).on('click', "#filter", function() {
        //     var std_search = $('#std_search').val();
        //     var section_id = $('#section_id_filter').val();
        //     var start_date = $('#startdate').val();
        //     var end_date = $('#enddate').val();
        //         if (std_search==""
        //         && section_id == "" 
        //         && start_date =="" 
        //         && enddate =="")
        //     {
        //         alert('please select atleast one filter');

        //     }
        // });

        // debugger;
        // var section_id = $('#section_id_filter').val();
        // var student_id = $('#student_select').val();
        // var d_school_id = $('#d_school_id').val();
        // debugger;
        // get_section_students(section_id,student_id,d_school_id);

        $('#pdf').click(function(){
            $('#unpaid_student_form').attr('action', '<?php echo base_url(); ?>reports/print_pdf');
            $('#unpaid_student_form').submit();
        });

        $('#excel').click(function(){
            $('#unpaid_student_form').attr('action', '<?php echo base_url(); ?>reports/database_to_excel');
            $('#unpaid_student_form').submit();
        });


        $("#section_id_filter").change(function(){
            var section_id = $(this).val();
            var d_school_id = $('#d_school_id').val();
            get_section_students(section_id,'',d_school_id);
        });
    });

    function get_section_students(section_id,student_id,d_school_id){
                        
            sect = section_id.substring(0,1);
            if (sect=='s') {
                section_id_length = section_id.length;
                section_id = section_id.substring(1,section_id_length);
                $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id,
                    student_id: student_id,
                    d_school_id:d_school_id
                },
                url: "<?php echo base_url();?>reports/get_section_student",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    if (response != "") {
                        $("#student_select").html(response);
                    }
                    if (response == "") {
                        $("#student_select").html('<select><option value=""><?php echo get_phrase('select_student'); ?></option></select>');
                    }
                }
            });
             }
        }

        <?php
        if (isset($section_id)&&!empty($section_id))
        {
            if (isset($student_id)&&!empty($student_id)) {
                ?>
        get_section_students('<?php echo $section_id;?>','<?php echo $student_id;?>','<?php echo $d_school_id;?>');
        <?php

            }
            else
            {
                ?>
                get_section_students('<?php echo $section_id;?>','<?php echo $d_school_id;?>');
                <?php
            }
        }
        ?>
</script>