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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php echo get_phrase('fee_concession_report');?>
        </h3>
    </div>
</div>
 
<form action="<?php echo base_url().'reports/fee_concession_report/'.$d_school_id?>"      method="post" name="students_fee_concession_form" 
      id="students_fee_concession_form">
    <div class="row  filterContainer"> 
            <div class="col-md-4 col-lg-4 col-sm-4">
                <label><?php echo get_phrase('keyword_search');?></label>
                <input type="text" name="std_search" class="form-control" value="<?php echo $std_search;?>" placeholder="Keyword">
            </div>
            
            <div class="col-md-4 col-lg-4 col-sm-4">
                <label id="section_id_filter_selection"><?php echo get_phrase('select_department')." / ".get_phrase('class')." / ".get_phrase('section');?></label>
                <select id="section_id_filter" class="selectpicker form-control" name="section_id" >
                <option value=""><?php echo get_phrase('select_any_option');?></option>
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
             <label>
             <?php echo get_phrase('start_date');?></label>
                <input class="form-control datepicker" type="text" autocomplete="off"  name="startdate" id="startdate" required="" value="<?php echo $start_date ?>" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                <div id="d3"></div>
            </div>

            <div class="col-md-6 col-lg-6 col-sm-6">
                <label><?php echo get_phrase('end_date');?></label>
                <input class="form-control datepicker" type="text" autocomplete="off"  name="enddate" id="enddate" required="" value="<?php echo $end_date ?>" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
                <div id="d3"></div>
            </div> 
        <div class="col-md-6 col-lg-6 col-sm-6  pt-3"> 
            <input type="hidden" name="apply_filter" value="1">
            <input type="submit" id="filter" value="<?php echo get_phrase('filter');?>" class="modal_save_btn"> 
            <?php if ($apply_filter == 1){ ?>
                <a id="btn_show" href="<?php echo base_url().'reports/fee_concession_report/'.$d_school_id?>" class="modal_cancel_btn" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
            
                <input class="modal_save_btn" type="submit" id="fee_concession_pdf" value="<?php echo get_phrase('get_pdf');?>">
                <input class="modal_save_btn" type="submit" id="fee_concession_excel" value="<?php echo get_phrase('get_excel');?>">
            <?php } ?>     
        </div>
    <input type="hidden" id="d_school_id" name="d_school_id" value="<?php echo $d_school_id; ?>">
    </div>
</form>  
    <div class="col-md-12">
        <div class="table-responsive">
        <table id="table" class="table table-bordered table-hover table-striped table-condensed table-responsive table-sm table_export" role="grid">
            <thead>
            <tr>
            <th><?php echo get_phrase('sr');?>.</th>
            <th><?php echo get_phrase('student_details');?></th>
            <?php $discount_ids = array(); $fee_type_ids = array(); foreach(get_discount_listing() as $discount_title) { $discount_ids[] = $discount_title['discount_id']; $fee_type_ids[] = $discount_title['fee_type_id']; ?>        
                <th>
                    <?php echo $discount_title['title']; ?>
                </th>
            <?php } ?>
            </tr>
        </thead>
            <tbody style="width: 250px;">
        <?php
        $count = 0;
        foreach ($fee_concession_arr as $key => $value)
        {
            $count++;
        ?>
            <tr>
                <td class='td_middle'><?php echo $count;?></td>
                <td>
                    <strong><?php echo get_phrase('name');?> :</strong>
                    <?php echo $value['student_name'];?> <b>S/O D/O</b> <?php echo $value['father_name'];?>
                    </br>
                    <strong><?php echo get_phrase('class');?> :</strong>
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
                    <strong><?php echo get_phrase('challan_number');?> :</strong>
                    <?php 
                        $get_scfID = $value['s_c_f_id'];
                    ?>
                    <a href="<?=base_url()?>class_chalan_form/edit_chalan_form/<?= $get_scfID ?>" target="_blank" class="text-primary" title="view chalan form">
                        <?= $value['chalan_form_number']; ?>
                        <b><i class="fas fa-info-circle"></i></b>
                    </a>
                    </br>
                    <strong><?php echo get_phrase('month_year');?> :</strong>
                    <?= month_of_year($value['s_c_f_month']); ?>-<?= $value['s_c_f_year']; ?>
                </td>
                <?php
                    $dis_count = count($discount_ids);
                    for($i=0;$i<$dis_count;$i++){
                        echo "<td class='td_middle'>";
                        $get_c_c_f_id = $this->db->query("SELECT c_c_f_id FROM ".get_school_db().".student_chalan_form WHERE s_c_f_id = '".$value['s_c_f_id']."' and school_id = '".$_SESSION['school_id']."' and is_cancelled = 0 and status > 3 ")->row();
                        $c_c_f_id     = $get_c_c_f_id->c_c_f_id;
                        $output = 0;
                        $query = $this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id = '".$value['s_c_f_id']."' and type != 2 and type_id = '".$fee_type_ids[$i]."' and school_id=".$_SESSION['school_id']);
                        $get_fee = $query->result_array();
                        $discount_calculation = 0;
                        $totle = $get_fee[0]['amount'];
                        $check_alread_discount = $this->db->query("SELECT * FROM ".get_school_db().".student_fee_settings WHERE student_id = '".$value['student_id']."' AND month = '".$value['s_c_f_month']."' AND year = '".$value['s_c_f_year']."' AND fee_type = 2 AND fee_type_id = '".$discount_ids[$i]."'");
                        if($check_alread_discount->num_rows() > 0)
                        {
                            $single_disco = $check_alread_discount->result_array();
                            $single_discount_calculation = 0;
                            if($single_disco[0]['discount_amount_type'] == '1')
                            {
                                $single_discount_percent = $single_disco[0]['amount'];
                                echo number_format($single_discount_percent);
                            }else if($single_disco[0]['discount_amount_type'] == '0' || $single_disco[0]['discount_amount_type'] == NULL){
                                $single_discount_percent = round(($totle / 100) * $single_disco[0]['amount']);   
                                echo number_format($single_discount_percent);
                            }
                        }
                        echo '</td>';
                    }
                ?>       
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

         $('#fee_concession_pdf').click(function(){
            $('#students_fee_concession_form').attr('action', '<?php echo base_url(); ?>reports/fee_concession_pdf');
            $('#students_fee_concession_form').submit();
        });

        $('#fee_concession_excel').click(function(){
            $('#students_fee_concession_form').attr('action', '<?php echo base_url(); ?>reports/fee_concession_excel');
            $('#students_fee_concession_form').submit();
        });

        $("#section_id_filter").change(function() {
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

<!--//***********************Date filter validation***********************-->
<script>
    $("#startdate").change(function () {
        var startDate = s_d($("#startdate").val());
        var endDate = s_d($("#enddate").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("startdate").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
        // }
    });
    
    $("#enddate").change(function () {
        var startDate = s_d($("#startdate").val());
        var endDate = s_d($("#enddate").val());
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementById("enddate").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("enddate").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
</script>
<!--//********************************************************************-->