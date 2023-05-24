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
    .top {
        top: 28px;
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
                <?php echo get_phrase('section_wise_students_list');?>
            </h3>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8">
        <?php
            if ($branch_name!= "")
            {
            ?>
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
                <img class="img-rounded" style="margin-top: -9px;height:
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
                        <div class="row filterContainer">
                                <?php if($hidden_name == "section"){ ?>
                                <div class="col-md-12">
                                    <form action="<?php echo base_url().'reports/section_wise_students_list/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                                          <div class="col-md-10">
                                                <label><?php echo get_phrase('select_section');?></label>
                                                <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                                                  <?php
                                                  echo section_list('',$d_school_id);
                                                  ?>
                                                </select>
                                          </div>
                                          <div class="col-md-2 top">
                                                <input type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                                          </div>
                                    </form>
                                </div>
                                <?php } ?>
                                <?php if($hidden_name == "department"){ ?>
                                <div class="col-md-12 pt-3">
                                    <form action="<?php echo base_url().'reports/section_wise_students_list/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                                          <div class="col-md-10">
                                                <label><?php echo get_phrase('select_department');?></label>
                                                <select id="department_id_filter" class="selectpicker form-control" name="department">
                                                <?php echo department_list('',$d_school_id); ?>
                                                </select>
                                          </div>
                                          <div class="col-md-2 top">
                                                <input type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                                          </div>
                                    </form>
                                </div>
                                <?php } ?>
                                <?php if($hidden_name == "academic_year"){ ?>
                                <div class="col-md-12">
                                    <form action="<?php echo base_url().'reports/section_wise_students_list/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                                          <div class="col-md-8">
                                                <label><?php echo get_phrase('select_academic_year');?></label>
                                                <select id="department_id_filter" class="selectpicker form-control" name="academic_year">
                                                <?php echo academic_year_option_list('',$d_school_id); ?>
                                                </select>
                                          </div>
                                          <div class="col-md-4 top">
                                                <input type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                                          </div>
                                    </form>
                                </div>
                                <?php } ?>
                                <?php if($hidden_name == "datewise"){ ?>
                                <div class="col-md-12">
                                    <form action="<?php echo base_url().'reports/section_wise_students_list/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
                                    <div class="col-md-4">
                                      <label><?php echo get_phrase('select_class');?></label>
                                      <select id="class_id_filter" class="selectpicker form-control" name="class">
                                        <?php echo class_list('',$d_school_id); ?>
                                      </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label><?php echo get_phrase('start_date');?></label>
                                        <input class="form-control" type="date" name="startdate" id="startdate" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                                    </div>
                                    <div class="col-md-4">
                                        <label><?php echo get_phrase('end_date');?></label>
                                        <input class="form-control" type="date" name="enddate" id="enddate" value="" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                                    </div>
                                    <div class="col-md-4" style="margin-top: 10px;">
                                      <input type="submit" id="submit" value="<?php echo get_phrase('get_report');?>" class="btn btn-primary">
                                    </div>
                                    </form>
                                  </div>
                                <?php } ?>
    <div class="row pt-3">
        <div class="col-md-6 col-lg-6 col-sm-6">
            <form action="<?php echo base_url().'reports/section_wise_students_list_to_excel/'.$d_school_id?>" method="post">
                <input class="btn btn-primary" type="submit" id="excel" value="<?php echo get_phrase('get_excel');?>">
                <input type="hidden" id = "d_school_id" name="d_school_id" value="<?php echo $d_school_id; ?>">
                <input type="hidden" name="section" value="<?php echo $section_id; ?>">
                <input type="hidden" name="check_report" value="<?=$hidden_name?>">
                <input type="hidden" name="startdate" value="<?=$sdate?>">
                <input type="hidden" name="enddate" value="<?=$edate?>">
            </form>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6">
            <form action="<?php echo base_url().'reports/section_wise_students_list_to_pdf/'.$d_school_id?>" method="post">
                <input type="hidden" id = "d_school_id" name="d_school_id" value="<?php echo $d_school_id; ?>">
                <input type="hidden" name="section" value="<?php echo $section_id; ?>">
                <input class="btn btn-primary" type="submit" id="pdf" value="<?php echo get_phrase('get_pdf');?>">
                <input type="hidden" name="check_report" value="<?=$hidden_name?>">
                <input type="hidden" name="startdate" value="<?=$sdate?>">
                <input type="hidden" name="enddate" value="<?=$edate?>">
            </form>
        </div>
    </div>
    <input type="hidden" id = "d_school_id" name="d_school_id" value="<?php echo $d_school_id; ?>">
    <input type="hidden" name="section" value="<?php echo $section_id; ?>">
</div>
<!--<div class="row">-->
    <div class="col-md-12 mt-4">
        <div>
            <table class="table table-bordered table-hover table-striped table-condensed table-sm table_export" role="grid">
        <thead>
            <tr>
            <th><?php echo get_phrase('sr');?>.</th>
            <th><?php echo get_phrase('std_name_with_fathers_name');?></th>
            <th><?php echo get_phrase('department');?></th>
            <th><?php echo get_phrase('class_section');?></th>
            <th><?php echo get_phrase('birthday');?></th>
            <th><?php echo get_phrase('gender');?></th>
            <th><?php echo get_phrase('religion');?></th>
            <th><?php echo get_phrase('address');?></th>
            <th><?php echo get_phrase('nationality');?></th>
            <th><?php echo get_phrase('date_of_admission');?></th>
            <th><?php echo get_phrase('mobile_no');?></th>
                
            </tr>
        </thead>
        <tbody style="width: 250px;">
        <?php
        $count = 0;
        foreach ($sectionwise_list_std->result() as $value)
        {
            $count++;
        ?>
            <tr>
                <td><?php echo $count;?></td>
                <td>
                    <?=$value->std?>
                </td>
                <td>
                    <?=$value->depart?>
                </td>
                <td>
                    <?=$value->class?> - <?=$value->section?>
                </td>
                <td><?=$value->birthday?></td>
                <td><?=$value->gender?></td>
                <td><?=$value->religion?></td>
                <td><?=$value->address?></td>
                <td><?=$value->nationality?></td>
                <td><?=$value->adm_date?></td>
                <td><?=$value->phone?> <br> <?=$value->mob_num?></td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        </table>
        </div>
    </div>
<!--</div>-->


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