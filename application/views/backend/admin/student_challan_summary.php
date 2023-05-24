<style>
    .boarder
    {
        border: 1px solid #f2f2f2;
        height: 34px;
    }
    .modal-backdrop
    {
        z-index: 0 !important;
    }
</style>
<?php
    if($this->session->flashdata('club_updated'))
    {
        echo '<div align="center">
         <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
          '.$this->session->flashdata('club_updated').'
         </div> 
        </div>';
    }
?>
<script>
    $( window ).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
</script>
<?php

    $school_id = $_SESSION['school_id'];

    $student_select = $this->input->post('student_select');
    $section_id = $this->input->post('section_id');
    
    if(empty($student_select))
    {
        $student_select = 0;
    }
    
    if(empty($section_id))
    {
       $section_id = 0;
    }
    
    if(empty($year))
    {
       $year = 0;
    }

    $piad_query = $this->db->query("select * from " . get_school_db() . ".student_chalan_form where student_id = $student_select and status = 5 and s_c_f_year = $year and school_id = $school_id")->result_array();
    $unpiad_query = $this->db->query("select * from " . get_school_db() . ".student_chalan_form where student_id = $student_select and status = 4 and s_c_f_year = $year and school_id = $school_id")->result_array();
    
    function get_child_coa($coa_id , $arr="")
    {

        global $arr;
        $str_ids = "";

        $CI=& get_instance();
        $CI->load->database();
        $coa_rs = "select coa.coa_id , coa.parent_id , coa.account_head from ".get_school_db().".chart_of_accounts as coa
        INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
         where coa.parent_id=$coa_id and s_coa.school_id = ".$_SESSION['school_id']."";
        $coa_query=$CI->db->query($coa_rs)->result_array();

        if(count($coa_query)>0)
        {
            foreach ($coa_query as $coa_key=>$coa_val)
            {
                $arr[] = $coa_val['coa_id'];
                get_child_coa($coa_val['coa_id'],$arr);
            }
            $str_ids = implode($arr , ",");

            return $str_ids;
        }
        else
        {
            return $coa_id;
        }
    }
?>

    <!-- filter -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline">
                <?php echo get_phrase('student_challan_summary'); ?>
            </h3>
        </div>
    </div>

    <span id="message" style="color: red;"></span>
    <form action="<?php echo base_url();?>transection_account/student_challan_summary" name="add" id="search_ledger" method="POST">
        <div>
            <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific records">
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 form-group">
                    <label class="control-label">
                    <?php echo get_phrase('class-Section');?></label>
                    <select id="section_filter" class="form-control" name="section_id" class="form-control" required="">
                        <?php echo section_selector($section_id);?>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 form-group">
                    <label class="control-label">
                    <?php echo get_phrase('student');?></label>
                    <select name="student_select" id="student_select" class="form-control" required>
                       <?php 
                        if(isset($student_select) && isset($student_select)){
    				   	    echo section_student($section_id,$student_select);
    				    }else{?>
    				   	<option value=""><?php echo get_phrase("select_student");?></option>
    				  <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                   <div class="form-group">
                        <label for="start_date">Select Year</label>
                        <select id="year" name="year" class="form-control" required>
                            <?php echo year_option_list(date('Y')-1,date('Y')+1); ?>
                        </select>
                        <script>
                            $("[name='year']").val(<?=$year?>);
                        </script>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group" style="margin-top: 10px;">
                        <button type="submit" id="select" value="submit" class="modal_save_btn" name="save"><?php echo get_phrase('filter'); ?></button>
                        <?php if(isset($student_select) && !empty($student_select)){ ?>
                        <a id="btn_show" href="<?php echo base_url(); ?>transection_account/student_challan_summary" class="btn btn-danger" style="display:none;padding:5px 8px !important;background: #f44336 !important;border: 1px solid #f44336 !important;"><?php echo get_phrase('remove_filters'); ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
    

    <div class="col-md-6">
        <h4><b>Paid Challan Records</b></h4>
        <table class="table table-bordered" data-step="6" data-position='top' data-intro="all paid fee records">
            <thead>
                <th>
                    <?php echo get_phrase('sn.no'); ?>
                </th>
                <th>
                    <?php echo get_phrase('paid_date'); ?>
                </th>
                <th>
                    <?php echo get_phrase('month'); ?>
                </th>
                <th>
                    <?php echo get_phrase('challan_no'); ?>
                </th>
                <th>
                    <?php echo get_phrase('amount'); ?>
                </th>
                
            </thead>
            <tbody>
            <?php
            if (count($piad_query) > 0)
            {
                $i = 1;
                foreach ($piad_query as $row)
                {
                    $get_month = '01-'.$row['s_c_f_month'].'-'.$row['s_c_f_year'];
            ?>
                    <tr>
                        <td>
                          <?php echo $i++; ?>
                        </td>
                        <td>
                            <?php echo convert_date($row['received_date']); ?>
                        </td>
                        <td>
                            <?php echo date('m/Y',strtotime($get_month)); ?>
                        </td>
                        <td>
                            <?php 
                                $get_scfID = get_chalan_scfid($row['chalan_form_number']);
                            ?>
                            <a href="<?=base_url()?>class_chalan_form/edit_chalan_form/<?= $get_scfID ?>" target="_blank" class="text-primary" title="view chalan form">
                                <?= $row['chalan_form_number']; ?>
                                <b><i class="fas fa-info-circle"></i></b>
                            </a>
                        </td>
                        <td>
                            <?php echo number_format($row['actual_amount']); ?>
                        </td>
                    </tr>
                    <?php
                }
            } 
            ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h4><b>Un-Paid Challan Records</b></h4>
        <table class="table table-bordered" data-step="7" data-position='top' data-intro="all un-paid fee records">
            <thead>
                <th>
                    <?php echo get_phrase('sn.no'); ?>
                </th>
                <th>
                    <?php echo get_phrase('issue_date'); ?>
                </th>
                <th>
                    <?php echo get_phrase('month'); ?>
                </th>
                <th>
                    <?php echo get_phrase('challan_no'); ?>
                </th>
                <th>
                    <?php echo get_phrase('amount'); ?>
                </th>
                
            </thead>
            <tbody>
            <?php
            if (count($unpiad_query) > 0)
            {
                $i = 1;
                foreach ($unpiad_query as $row)
                {
                    
                    $get_month = '01-'.$row['s_c_f_month'].'-'.$row['s_c_f_year'];
            ?>
                    <tr>
                        <td>
                          <?php echo $i++; ?>
                        </td>
                        <td>
                            <?php echo convert_date($row['issue_date']); ?>
                        </td>
                        <td>
                            <?php echo date('m/Y',strtotime($get_month)); ?>
                        </td>
                        <td>
                            <?php 
                                $get_scfID = get_chalan_scfid($row['chalan_form_number']);
                            ?>
                            <a href="<?=base_url()?>class_chalan_form/edit_chalan_form/<?= $get_scfID ?>" target="_blank" class="text-primary" title="view chalan form">
                                <?= $row['chalan_form_number']; ?>
                                <b><i class="fas fa-info-circle"></i></b>
                            </a>
                        </td>
                        <td>
                            <?php echo number_format($row['actual_amount']); ?>
                        </td>
                        
                    </tr>
                    <?php
                }
            } 
            ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function(){
            var coa_id = $( "#coa_id").val();
            var start_date = $( "#start_date" ).val();
            var end_date = $( "#end_date" ).val();
            var section_id_filter = $( "#section_id_filter" ).val();
            var student_select = $( "#student_select" ).val();

            if(start_date != "" || end_date != "" || section_id_filter != "" || student_select != "")
            {
                $('#btn_show').show();
            }else
            {
                $('#btn_show').hide();
            }
            
            $("#section_filter").change(function() {
                var section_id = $(this).val();
                $("#icon").remove();
                $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    
                $.ajax({
                    type: 'POST',
                    data: {
                        section_id: section_id
                    },
                    url: "<?php echo base_url();?>transection_account/get_section_student",
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
            });
        });
    </script>
    <script>
        $(document).ready(function()
        {
            /* select picker start */
            $('.selectpicker').on('change', function() {
                var id = $(this).attr('id');

                var selected = $('#' + id + ' :selected');

                var group = selected.parent().attr('label');
                $('#' + id + '_selection').text(group);
            });
            /* select picker end */


            var section_id =  $("#section_id_filter").val();
            var student_id =  $("#student_id").val();


            // alert(student_id);
            if(section_id != "")
            {

                var id = $('.selectpicker').attr('id');

                var selected = $('#' + id + ' :selected');

                var group = selected.parent().attr('label');
                $('#' + id + '_selection').text(group);


                get_student_rec(section_id , student_id);
            }


            $("#section_id_filter").change(function()
            {
                $('#get_planner').html('');
                var section_id =  $(this).val();
                var student_id =  $("#student_id").val();
                $("#icon").remove();
                $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
                get_student_rec(section_id);

            });


            function get_student_rec(section_id , student_id)
            {

                $.ajax({
                    type: 'POST',
                    data: {
                        section_id: section_id,
                        student_id: student_id//student_id
                    },
                    url: "<?php echo base_url();?>circular/get_section_student",
                    dataType: "html",
                    success: function(response) {
                        $("#icon").remove();
                        if ($.trim(response) != "") {
                            $("#student_select").html(response);
                        }
                        if ($.trim(response) == "") {
                            $("#student_select").html('<select><option value=""><?php echo get_phrase('select_student'); ?></option></select>');
                        }

                    }
                });

            }

        });
    </script>
    <script>
        $('#is_closed').change(function(){
            this.value = this.checked ? 1 : 0;
        });

        $("#start_date").change(function () {
                document.getElementById("sd").innerHTML = "";
                var startDate = s_d($("#start_date").val());
                var endDate = s_d($("#end_date").val());

                if ((Date.parse(endDate) < Date.parse(startDate)))
                {
                    document.getElementById("sd").innerHTML = "<?php echo get_phrase('start_date_should_be_less_then_end_date');?>";
                    document.getElementById("start_date").value = "";
                }
                else if ((Date.parse(startDate) < Date.parse("<?php echo $start_date_check; ?>")))
                {
                    document.getElementById("sd").innerHTML = "<?php echo get_phrase('please_select_start_date_within_academic_session');?>";
                    document.getElementById("start_date").value = "";
                }
            }
        );
        $("#end_date").change(function () {
            document.getElementById("ed").innerHTML = "";
            var startDate = s_d($("#start_date").val());
            var endDate = s_d($("#end_date").val());
            if ((Date.parse(startDate) > Date.parse(endDate))) {
                document.getElementById("ed").innerHTML = "<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>";
                document.getElementById("end_date").value = "";
            }
            else if ((Date.parse(endDate) > Date.parse("<?php echo $end_date_check; ?>"))) {

                document.getElementById("ed").innerHTML = "<?php echo get_phrase('please_select_end_date_within_academic_session');?>";
                document.getElementById("end_date").value = "";
            }
        });
        function s_d(date){
            var date_ary=date.split("/");
            return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0];
        }
    </script>

