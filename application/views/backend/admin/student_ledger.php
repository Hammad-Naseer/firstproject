<style>
    .boarder{border:1px solid #f2f2f2;height:34px}.modal-backdrop{z-index:0!important}.center{text-align:center}.no-print{position:relative;top:15px;z-index:99999;border:2px solid #fff;border-radius:20px 20px 20px 0;box-shadow:1px 0 5px 1px #ccc;outline:0}
    
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
    <!-- filter -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline">
                <?php echo get_phrase('student_ledger'); ?>
            </h3>
        </div>
    </div>

    <span id="message" style="color: red;"></span>
    <form action="<?php echo base_url();?>transection_account/student_ledger" name="add" id="search_ledger" method="POST">
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
                    <select name="student_select" id="student_select" class="form-control">
                       <?php 
                        if(isset($student_select) && !empty($student_select)){
    				   	    echo section_student($section_id,$student_select);
    				    }else{
    				    ?>
    				   	<option value=""><?php echo get_phrase("select_student");?></option>
    				  <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                   <div class="form-group">
                        <label for="coa_id">Select Chart Of Account</label>
                        <select class="form-control select2" id="coa_id" name="coa_id" required>
                            <option value=""><?php echo get_phrase('select_chart_of_account'); ?></option>
                            <?php
                               echo coa_list_h(0,$coa_id,0,0,0);
                               // echo coa_list_h(0,$edit_data[0]['dr_coa_id'] ,0 , 0 , $account_type);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                   <div class="form-group">
                        <label for="start_date">Start Date</label> 
                        <input id="start_date" autocomplete="off"  name="start_date" class="form-control datepicker" value="<?php echo date_dash($start_date);?>" placeholder="Select Start Date" required data-format="dd/mm/yyyy" style="height: 44px;"/>
                        <span style="color: red;" id="sd"></span>
                    </div>
                </div>
                <div class="col-md-6">
                   <div class="form-group">
                        <label for="end_date">End Date</label> 
                        <input id="end_date" autocomplete="off"  name="end_date" class="form-control datepicker"  value="<?php echo date_dash($end_date);?>" placeholder="Select End Date" required data-format="dd/mm/yyyy" style="height: 44px;" />
                        <span style="color: red;" id="ed"></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="filter" value="1" />
                        <button type="submit" id="select" value="submit" class="modal_save_btn" name="save"><?php echo get_phrase('filter'); ?></button>
                        <a id="btn_show" href="<?php echo base_url(); ?>transection_account/ledger" class="modal_cancel_btn" style="display:none;"><?php echo get_phrase('remove_filters'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <?php if (!empty($journal_result)){ ?>
            <!--onclick="printContent('print_area')"-->
            <form action="<?php echo base_url();?>transection_account/student_ledger_pdf" method="post">
                <input type="hidden" name="coa_id" value="<?= $coa_id ?>">
                <input type="hidden" name="section_id" value="<?= $section_id ?>">
                <input type="hidden" name="start_date" value="<?= $start_date ?>">
                <input type="hidden" name="end_date" value="<?= $end_date ?>">
                <input type="hidden" name="student_select" value="<?= $student_select ?>">
                <button type="submit" class="modal_save_btn no-print">Generate PDF Report</button>
            </form>
            <form action="<?php echo base_url();?>transection_account/trial_balance_excel" method="post">
                <input type="hidden" name="coa_id" value="<?= $coa_id ?>">
                <input type="hidden" name="section_id" value="<?= $section_id ?>">
                <input type="hidden" name="start_date" value="<?= $start_date ?>">
                <input type="hidden" name="end_date" value="<?= $end_date ?>">
                <button type="button" id="btnExport" class="modal_save_btn no-print" style="background:#008000e3 !important;">Generate Excel Report</button>
            </form>
        <?php } ?>    
        <div class="col-md-12 thisrow" id="print_area" style="border: 1px solid #cccccc9c !important;">
        <?php if (!empty($journal_result)){ ?>
            <!--<div class="center">-->
            <!--    <h1><b>Student Ledger Report</b></h1>-->
            <!--    <h4><b>Account Title : </b> <?= get_coa_name($coa_id) ?></h4>-->
            <!--    <p><b>FROM : </b> <?= date("d/m/Y", strtotime($start_date))?> - <b>To : </b> <?= date("d/m/Y", strtotime($end_date)); ?></p>-->
            <!--</div>-->
        <?php } ?>
        <table class="table table-bordered" ata-step="6" id="excel_print" data-position='top' data-intro="all ledger records">
            <thead>
                <tr style="background-color:white !important;">
                    <td style="background-color:white !important;" align="center" colspan="6">
                        <h1><b>Student Ledger Report</b></h1>        
                        <h4><b>Account Title : </b> <?= get_coa_name($coa_id) ?></h4>
                        <p><b>FROM : </b> <?= date("d/m/Y", strtotime($start_date))?> - <b>To : </b> <?= date("d/m/Y", strtotime($end_date)); ?></p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php echo get_phrase('type'); ?>
                    </th>
                    <th>
                        <?php echo get_phrase('date'); ?>
                    </th>
                    <th>
                        <?php echo get_phrase('transection_detail'); ?>
                    </th>
                    <th>
                        <?php echo get_phrase('debit'); ?>
                    </th>
                    <th>
                        <?php echo get_phrase('credit'); ?>
                    </th>
                    <th>
                        <?php echo get_phrase('balance'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($journal_result))
            {
                $j=$start_limit+1;
                foreach ($journal_result as $row)
                {
                    ?>
                <tr>
                    <td>
                      <?php echo journal_entry_type($row['entry_type']); ?>
                    </td>
                    <td width="100px;">
                        <?php
                        echo date("d/m/Y" , strtotime($row['entry_date'])); ?>
                    </td>
                    <td width="475">
                        <?php echo $row['detail'];
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($row['debit'] < 0)
                        {
                            $d = (-1) * ($row['debit']);
                            echo "(" . number_format($d) . ")";
                        } else
                        {
                            echo number_format($row['debit']);
                            //echo "=";
                            //echo $row['coa_id'];
                        }

                        $debit += $row['debit'];

                        ?>
                    </td>
                    <td>
                        <?php
                        if ($row['credit'] < 0)
                        {
                            $c = (-1) * ($row['credit']);
                            echo "(" . number_format($c) . ")";
                        } else
                        {
                            echo number_format($row['credit']);
                            //echo "=";
                            // echo $row['coa_id'];
                        }
                        $credit += $row['credit'];
                        ?>
                    </td>
                    <td><?php
                        $balance = $debit - $credit;
                        if ($balance < 0)
                        {
                            $b = (-1) * ($balance);
                            echo "(" . number_format($b) . ")";
                        } else
                        {
                            echo number_format($balance);
                        }
                        ?></td>
                </tr>
                <?php
                $j++;
                }
            }else{
            ?>
            <tr>
                <td colspan="6" class="text-center">Record Not Found</td>
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
            var coa_id = $( "#coa_id").val();
            var start_date = $( "#start_date" ).val();
            var end_date = $( "#end_date" ).val();
            var section_id_filter = $( "#section_id_filter" ).val();
            var student_select = $( "#student_select" ).val();

            if(start_date != "" || end_date != "" || coa_id != "" || section_id_filter || student_select )
            {
                $('#btn_show').show();
            }
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

        });
    </script>
    <script>
        $('#is_closed').change(function(){
            this.value = this.checked ? 1 : 0;
        });

        
        function printContent(el){
            var restorepage = $('body').html();
            var printcontent = $('#' + el).clone();
            $('body').empty().html(printcontent);
            window.print();
            $('body').html(restorepage);
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
        
    </script>

<script src="<?=base_url()?>assets/table2excel.js"></script>
<script>
    $(function () {
        $("#btnExport").click(function () {
            $("#excel_print").table2excel({
                filename: "Student Ledger From <?= $start_date ?> To <?= $end_date ?>.xls"
            });
        });
    });
</script>


<!--//***********************Date filter validation***********************-->
<script>
    $("#start_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("start_date").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
        // }
    });
    
    $("#end_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementById("end_date").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("end_date").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
</script>
<!--//********************************************************************-->