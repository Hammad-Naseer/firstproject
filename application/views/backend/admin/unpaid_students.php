<style>
  .tile-stats{min-height:140px!important}.system_name.inline{display:inline-block;margin:0;padding:20px 0 5px;width:100%}.img-res{float:none;height:50px;width:auto}.col-mh{color:#4a8cbb;font-size:16px;font-weight:700;padding-top:20px;text-align:right;text-transform:uppercase}.blocks{margin:0 auto;text-align:right}
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
<div class="row" style="padding-left:0px !important;padding-right:0px !important;">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php echo get_phrase('unpaid_students');?>
        </h3>
    </div>

    <form action="<?php echo base_url().'reports/unpaid_students/'.$d_school_id?>" method="post" name="unpaid_student_form" id="unpaid_student_form">
    <div class="row filterContainer"> 
            <div class="col-md-4 col-lg-4 col-sm-4">
                <label><?php echo get_phrase('keyword_search');?></label>
                <input type="text" name="std_search" class="form-control" value="<?php echo $std_search;?>" placeholder="Keyword">
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4">
                <label id="section_id_filter1_selection"><?php echo get_phrase('select_department')." / ".get_phrase('class')." / ".get_phrase('section');?></label>
                <select id="section_id_filter1" class="selectpicker form-control" name="section_id">
                <option value="">
                <?php echo get_phrase('select_any_option');?>  
                </option>
                <?php echo department_class_section($section_id,$d_school_id);?>
                </select>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4">
            <label><?php echo get_phrase('select_student');?></label>
                <select id="student_select1" class="form-control"  name="student_id">
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
                <input type="hidden" name="apply_filter" value="2">
                <input type="submit" id="filter" value="<?php echo get_phrase('filter');?>" class="modal_save_btn">
            
            <?php if ($apply_filter == 2){?> 
                <a id="btn_show" href="<?php echo base_url().'reports/fee_recovery_report/'.$d_school_id?>" class="modal_cancel_btn" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
                <input class="modal_save_btn" type="submit" id="pdf" value="<?php echo get_phrase('get_pdf');?>">
                <input class="modal_save_btn" type="submit" id="excel" value="<?php echo get_phrase('get_excel');?>">
            <?php } ?> 
                
            </div>
    </div>
    <input type="hidden" id = "d_school_id" name="d_school_id" value="<?php echo $d_school_id; ?>">
</form>

    <div class="col-md-12">
    <form action="<?=base_url().'reports/fee_reminder/'?>" method="post">
        <table class="table table-bordered table-hover table-striped table-condensed table-sm" id="unpaid_table" role="grid">
        <thead>
            <tr>
                <th><?php echo get_phrase('sr');?>.</th>
                <th><?php echo get_phrase('student_details');?></th>
                <th><?php echo get_phrase('date');?></th>
                <th><?php echo get_phrase('challan_amount');?></th>
                <th><?php echo get_phrase('arrears');?></th>
                <th><?php echo get_phrase('discount');?></th>
                <th><?php echo get_phrase('net recieveable');?></th>
                <th><?php echo get_phrase('reminder') ?></th>
            </tr>
        </thead>
        <tbody style="width: 250px;">
        <?php
        $count = 0;
        $total_challan_amount = 0;
        $total_arrears = 0;
        $total_discount_amount = 0;
        $total_net_amount = 0;

        foreach ($unpaid_std_arr as $key => $value)
        {
            
            $count++;
        ?>
            <tr>
                <td><?php echo $count;?></td>
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
                    <strong><?php echo get_phrase('challan_no');?> :</strong>
                    <?php echo $value['chalan_form_number'];?></br>
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
                <b>Issue Date : </b>
                <?php
                    if (isset($value['issue_date']) && !empty($value['issue_date']))
                    {
                        $issue_date = date_view($value['issue_date']);
                        echo $issue_date;
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
                        $challan_amount = $value['actual_amount']+$total_discuont; 
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
                        $total_discount_amount += $total_discuont;
                    ?>
                </td>
                <td class="td_middle">
                    <?php 
                        $net_amount = $value['arrears'] + $value['actual_amount'];
                        $total_net_amount += $net_amount;
                        
                        echo number_format($net_amount);
                    ?>
                </td>
                <td class="td_middle">
                    <input type="checkbox" class="std_checkbox" name="student_id[]" value="<?php echo $value['student_id']; ?>--<?= $value['student_name']; ?>--<?= $value['email']; ?>--<?= $value['mob_num']; ?>--<?= $net_amount; ?>">
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="color:black !important;"><b>Total : </b> </td>
                <td style="color:black !important;"><?= number_format($total_challan_amount )?></td>
                <td style="color:black !important;"><?= number_format($total_arrears )?></td>
                <td style="color:black !important;"><?= number_format($total_discount_amount )?></td>
                <td style="color:black !important;"><?= number_format($total_net_amount) ?></td>
                <td></td>
            </tr>
        </tfoot>
        </table>
        <br><br>
        <div class="form-group">
            <label>Select Reminder Status</label>
            <select class="form-control" name="reminder_status" required>
                <option value="">Select Status</option>
                <option <?= check_sms_preference(8,"style","sms") ?> value="sms">Send Sms</option>
                <option <?= check_sms_preference(8,"style","email") ?> value="email">Send Email</option>
                <option value="notification">Send Notification</option>
            </select>
        </div>
        <div class="form-group">
            <button class="modal_save_btn" type="submit">Submit</button>
        </div>
    </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        
        let example = $('#unpaid_table').dataTable({
            stateSave: true,
            columnDefs: [{
                orderable: false,
                className: 'select-checkbox',
                // targets: 1
            }],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            "lengthMenu": [200, 300, 500, 10000],
            order: [
                [1, 'asc']
            ]
        });

        $('#pdf').click(function(){
            $('#unpaid_student_form').attr('action', '<?php echo base_url(); ?>reports/print_pdf');
            $('#unpaid_student_form').submit();
        });

        $('#excel').click(function(){
            $('#unpaid_student_form').attr('action', '<?php echo base_url(); ?>reports/database_to_excel');
            $('#unpaid_student_form').submit();
        });


        $("#section_id_filter1").change(function(){
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
                        $("#student_select1").html(response);
                    }
                    if (response == "") {
                        $("#student_select1").html('<select><option value=""><?php echo get_phrase('select_student'); ?></option></select>');
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
