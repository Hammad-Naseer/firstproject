<style>
  .tile-stats{min-height:140px!important}.system_name.inline{display:inline-block;margin:0;padding:20px 0 5px;width:100%}.img-res{float:none;height:50px;width:auto}.col-mh{color:#4a8cbb;font-size:16px;font-weight:700;padding-top:20px;text-align:right;text-transform:uppercase}.blocks{margin:0 auto;text-align:right}
</style>
<?php
      $d_school_id=$d_school_id;
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
<div class="row" style="padding-left:0px !important;padding-right:0px !important;">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline"><?php echo get_phrase('paid_students');?></h3>
    </div>
 
    <form action="<?php echo base_url().'reports/paid_students/'.$d_school_id?>" method="post" name="paid_student_form" id="paid_student_form">
    <div class="row filterContainer"> 
            <div class="col-md-4 col-lg-4 col-sm-4">
                <label><?php echo get_phrase('keyword_search');?></label>
                <input type="text" name="std_search" class="form-control" value="<?php echo $std_search;?>" placeholder="Keyword">
            </div>

            <div class="col-md-4 col-lg-4 col-sm-4">
                <label for="paid_section_id_filter"><?php echo get_phrase('select_department')." / ".get_phrase('class')." / ".get_phrase('section');?></label>
                <select id="paid_section_id_filter" class="selectpicker form-control" name="section_id">
                <option value="">
                <?php echo get_phrase('select_any_option');?>  
                </option>
                <?php echo department_class_section($section_id,$d_school_id);?>
                </select>
            </div>

            <div class="col-md-4 col-lg-4 col-sm-4">
                <label><?php echo get_phrase('select_student');?></label>
                <select id="paid_student_select" class="form-control"  name="student_id">
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
            <input type="submit" id="filter" value="<?php echo get_phrase('filter');?>" class="modal_save_btn"> 
            <?php if ($apply_filter == 1) { ?>
                <a id="btn_show" href="<?php echo base_url().'reports/fee_recovery_report/'.$d_school_id?>" class="modal_cancel_btn" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
                <input class="modal_save_btn" type="submit" id="paid_students_pdf" value="<?php echo get_phrase('get_pdf');?>">
                <input class="modal_save_btn" type="submit" id="paid_students_excel" value="<?php echo get_phrase('get_excel');?>">
            <?php } ?> 
        </div> 
    <input type="hidden" id="d_school_id" name="d_school_id" value="<?php echo $d_school_id; ?>">
    </div>
</form> 
 
    <div class="col-md-12">
        <div class="table-responsive">
        <table id="table" class="table table-bordered table-hover table-striped table-condensed table-responsive table-sm" role="grid">
        <thead>
            <tr>
                <th><?php echo get_phrase('sr');?>.</th>
                <th><?php echo get_phrase('student_details');?></th>
                <th><?php echo get_phrase('date');?></th>
                <th><?php echo get_phrase('challan_amount');?></th>
                <th><?php echo get_phrase('arrears');?></th>
                <th><?php echo get_phrase('discount');?></th>
                <th><?php echo get_phrase('recieved_amount');?></th>
            </tr>
        </thead>
        <tbody style="width: 250px;">
        <?php
        $count = 0;
        $total_challan_amount = 0;
        $total_arrears = 0;
        $total_discount = 0;
        $total_rec_amount = 0;
        foreach ($paid_std_arr as $key => $value)
        {
            $count++;
        ?>
            <tr>
                <td class="td_middle"><?php echo $count;?></td>
                <td>
                    <strong><?php echo get_phrase('name');?> :</strong>
                    <?php echo $value['student_name'];?></br>
                    <strong><?php echo get_phrase('father_name');?> :</strong>
                    <?php echo $value['father_name'];?></br>
                    <strong><?php echo get_phrase('roll_no');?> :</strong>
                    <?php echo $value['roll'];?></br>
                    <!--<strong><?php echo get_phrase('department');?>/<?php echo get_phrase('class');?>/<?php echo get_phrase('section');?>: </strong>-->
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
                    <strong>Challan Number :</strong> <?php echo $value['chalan_form_number'];?><br>
                    <span style="display: none;"><?php echo $value['system_id'];?></span>
                </td>
                <td class="td_middle">
                <b>Due Date : </b>
                <?php
                    if (isset($value['due_date']) && !empty($value['due_date']))
                    {
                        $due_date = date_view($value['due_date']);
                        echo $due_date;
                    }
                ?>  
                <br>
                <b>Received Date : </b>
                <?php
                    if (isset($value['received_date']) && !empty($value['received_date']))
                    {
                        $received_date = date_view($value['received_date']);
                        echo $received_date;
                    }
                ?>
                </td>
                <td class="td_middle">
                    <?php
                        $get_month = date("m",strtotime($value['due_date']));
                        $get_year = date("Y",strtotime($value['due_date']));
                        $total_discuont = get_student_challan_discount_calculation($value['student_id'] , $get_month, $get_year,$value['s_c_f_id']);
                    ?>
                    <?php 
                        $challan_amount = $value['actual_amount'] + $total_discuont;
                        $total_challan_amount += $challan_amount;
                        echo number_format($challan_amount);
                    ?>
                </td>
                <td class="td_middle">
                    <?php 
                        $arrears_amount = $value['arrears'] > 0 ? $value['arrears'] : 0;
                        $total_arrears += $arrears_amount;
                        echo number_format($arrears_amount);
                    ?>
                </td>
                <td class="td_middle">
                    <?php 
                        echo $total_discuont > 0 ? number_format($total_discuont) : "0";
                        $total_discount += $total_discuont;
                    ?>
                </td>
                <td class="td_middle">
                    <?php 
                        $rec_amount = $value['received_amount'] + $value['arrears'];
                        $total_rec_amount += $rec_amount;
                        echo number_format($rec_amount);
                        
                    ?>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="color:black !important;"><b>Total : </b> </td>
                <td style="color:black !important;"><?= number_format($total_challan_amount) ?></td>
                <td style="color:black !important;"><?= number_format($total_arrears) ?></td>
                <td style="color:black !important;"><?= number_format($total_discount) ?></td>
                <td style="color:black !important;"><?= number_format($total_rec_amount) ?></td>
            </tr>
        </tfoot>
        </table>
        </div>
    </div> 
<script>
    $(document).ready(function(){
        $('#table').DataTable({
            //"scrollX": true
            "ordering": false
        });


        $('#paid_students_pdf').click(function(){
            $('#paid_student_form').attr('action', '<?php echo base_url(); ?>reports/paid_students_pdf');
            $('#paid_student_form').submit();
        });

        $('#paid_students_excel').click(function(){
            $('#paid_student_form').attr('action', '<?php echo base_url(); ?>reports/paid_students_excel');
            $('#paid_student_form').submit();
        });

        $("#paid_section_id_filter").change(function() {
            
            var section_id = $(this).val();
            var d_school_id = $('#d_school_id').val();
            paid_get_section_students(section_id,'',d_school_id);
        });
    });

        function paid_get_section_students(section_id,student_id,d_school_id){
                        
            sect = section_id.substring(0,1);
            if (sect=='s') {
                section_id_length = section_id.length;
                section_id = section_id.substring(1,section_id_length);
                $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id,
                    student_id: student_id,
                    d_school_id: d_school_id
                },
                url: "<?php echo base_url();?>reports/get_section_student",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    console.log(response);
                    if (response != "") {
                        
                        $("#paid_student_select").html(response);
                    }
                    if (response == "") {
                        $("#paid_student_select").html('<select><option value=""><?php echo get_phrase('select_student'); ?></option></select>');
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