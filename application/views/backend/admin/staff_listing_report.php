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
      $d_school_id=$this->uri->segment(3);
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
                <?php echo get_phrase('staff_list_report');?>
            </h3>
        </div>
    <div class="col-lg-7 col-md-7 col-sm-7">
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
        <?php } ?>
    </div>
    </div>
</div>

<div>
<form action="<?php echo base_url().'staff_attendance_report/staff_listing/'.$d_school_id?>" method="post" name="staff_listing_form" id="staff_listing_form">
    <div class="row filterContainer">
            <div class="col-md-6 col-lg-6 col-sm-6">
                <label><?php echo get_phrase('keyword_search');?></label>
                <input type="text" name="staff_search" class="form-control" value="<?php echo $staff_search;?>" placeholder="Keyword">
            </div>
            <div class="col-md-6 col-lg-6 col-sm-6">
                <label id=""><?php echo get_phrase('select_designation');?></label>
                <select id="designation_id" class="selectpicker form-control" name="designation_id">
                    <option value="0"><?php echo get_phrase('select_designation');?></option>
                    <?php echo designation_list_h(0,$designation_id,0,$d_school_id)?>
                </select>
            </div> 
            <div class="col-md-6 col-lg-6 col-sm-6">
             <label><?php echo get_phrase('select_any_value');?></label>
             <select id="staff_type" class="selectpicker form-control" name="staff_type">
             <?php
             /*
                 <option value="1"><?php echo get_phrase('all_staff');?></option>
                 <option value="2"><?php echo get_phrase('teaching_staff');?>
                 </option>
                 <option value="3"><?php echo get_phrase('non_teaching_staff');?>
                 </option>
            */
            ?>
                <?php echo get_staff_type_h($staff_type);?>
             </select>
                <div id="d3"></div>
            </div> 
            <div class="col-md-6 col-lg-6 col-sm-6">
                <label><?php echo get_phrase('select_staff');?></label>
                <select id="staff_id" class="selectpicker form-control" name="staff_id">
                   <?php echo staff_list(0,$staff_id,0,0); ?>
                </select>
                <div id="d3"></div>
            </div> 
            <div class="col-md-6 col-lg-6 col-sm-6 pt-3"> 
                <input type="hidden" name="apply_filter" value="1">
                <input type="submit" id="filter" value="<?php echo get_phrase('filter');?>" class="btn btn-primary"> 
                <?php
                if ($apply_filter == 1)
                {
                ?>
                <div class="col-md-3 col-lg-3 col-sm-3">
                <a id="btn_show" href="<?php echo base_url().'staff_attendance_report/staff_listing/'.$d_school_id?>" class="btn btn-danger" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
                </div>
                <?php
                }
                ?> 
                <input class="btn btn-primary" type="submit" id="staff_listing_pdf" value="<?php echo get_phrase('get_pdf');?>">
                <input class="btn btn-primary" type="submit" id="staff_listing_excel" value="<?php echo get_phrase('get_excel');?>">
            </div>
    </div>
    <input type="hidden" id="d_school_id" name="d_school_id" value="<?php echo $d_school_id;?>">
</form>
</div>


    <div class="col-md-12">
        <div>
        <table class="table table-bordered table-hover table-striped table-condensed table-sm table_export" role="grid">
        <thead>
            <tr>
            <th style="width:35px;"><?php echo get_phrase('sr');?>.</th>
            <th style="width:35px;"><?php echo get_phrase('image');?></th>
            <th><?php echo get_phrase('staff_details');?></th>
                
            </tr>
        </thead>
        <tbody style="width: 250px;">
        <?php
        $count = 0;
         // echo "<pre>";
         // print_r($staff_listing_arr);

        // get school details
        // $scl_name=$this->db->query("select name from ".get_school_db().".school where school_id=$d_school_id")->result_array();
        // if(count($scl_name)>0)
        // {
        //   $branch_name =  $scl_name[0]['name'];
        //   $branch_logo =  $scl_name[0]['logo'];
        //   $branch_folder =  $scl_name[0]['folder_name'];
        // }
        // get school details
        foreach ($staff_listing_arr as $key => $value)
        {
            $count++;
        ?>
            <tr>
                <td><?php echo $count;?></td>
                <td>
                    <img src="<?php

                        if($value['staff_image']==''){
                            echo  base_url().'/uploads/default.png'; 
                        }else{
                            echo  display_link($value['staff_image'],'staff');
                        }
                    ?>" class="img-circle" width="30" />
                </td>
                <td>
                     <strong><?php echo get_phrase('name');?> :</strong>
                    <?php echo $value['name'];?></br>
                    <strong><?php echo get_phrase('emp_code');?> :</strong>
                    <?php echo $value['employee_code'];?></br>
                    <strong><?php echo get_phrase('designation');?> :</strong>
                    <?php echo $value['designation'];?></br>
                    <span style="display: none;"><?php echo $value['system_id'];?></span>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#table').DataTable({
        });

        var designation_id = "<?php echo $designation_id ?>";
        var staff_type = "<?php echo $staff_type ?>";
        var staff_id = "<?php echo $staff_id ?>";
        var d_school_id = "<?php echo $d_school_id ?>";
        if (staff_id=="")
        {
            staff_id = 0;
        }
        $("#staff_id").prop('disabled', true);
        get_staff_type(staff_type,designation_id,staff_id,d_school_id);

        $("#staff_type").change(function(){
            var staff_type = $(this).val();
            var designation_id = $('#designation_id').val();
            var d_school_id = "<?php echo $d_school_id ?>";
            // var staff_id = $('#staff_id').val();
            // if (staff_id=="")
            // {
            //     staff_id = 0;
            // }
            $("#staff_id").prop('disabled', true);
            get_staff_type(staff_type,designation_id,0,d_school_id);

        });

        $("#designation_id").change(function(){
            var designation_id = $(this).val();
            var staff_type = $('#staff_type').val();
            var d_school_id = "<?php echo $d_school_id ?>";
             $("#staff_id").prop('disabled', true);
            get_staff_type(staff_type,designation_id,0,d_school_id);

        });

        $('#staff_listing_pdf').click(function(){
            $('#staff_listing_form').attr('action', '<?php echo base_url(); ?>staff_attendance_report/staff_listing_pdf');
            $('#staff_listing_form').submit();
        });

        $('#staff_listing_excel').click(function(){
            $('#staff_listing_form').attr('action', '<?php echo base_url(); ?>staff_attendance_report/staff_listing_excel');
            $('#staff_listing_form').submit();
        });
    });

    function get_staff_type(staff_type,designation_id,staff_id,d_school_id){
            $.ajax({
            type: 'POST',
            data: {
                staff_type: staff_type,
                designation_id :designation_id,
                staff_id : staff_id,
                d_school_id : d_school_id
            },
            url: "<?php echo base_url();?>staff_attendance_report/get_staff_type",
            dataType: "html",
            success: function(response) {
                $("#icon").remove();
                if (response != "") {
                    $("#staff_id").prop('disabled', false);
                    $("#staff_id").html(response);
                }
                if (response == "") {
                    $("#staff_id").html('<select><option value=""><?php //echo get_phrase('select_staff'); ?></option></select>');
                }
            }
        }); 
    }
</script>