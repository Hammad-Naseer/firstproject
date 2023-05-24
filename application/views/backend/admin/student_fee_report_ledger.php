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
    
    $debit = 0;
    $credit = 0;

    $i = 1;
    $school_id = $_SESSION['school_id'];

    $qu_check = "";
    $qu_check2 = "";
    $select_coa_str = "";
    $coa_id = $this->input->post('coa_id');
    $select_coa_id = $coa_id;

    $section_id = "";
    $student_select = $this->input->post('student_select');
    $section_where = "";
    $sect_student_where = "";
    $student_join ="";
    $coa_str_id = "";

    $section_id = $this->input->post('section_id');

    /*if($section_id == "")
    {
        echo $section_id = $this->uri->segment(6);
    }*/

    //global $arr;
    $coa_id_search = "";
    if ($coa_id == "" || $coa_id == 0)
    {
        $coa_id = $this->uri->segment(3);

        if ($coa_id == "" || $coa_id == 0)
        {
            $coa_id = 0;

        }
        else
        {
            $coa_id_search = get_child_coa($coa_id , $arr);
        }
    }else{
        $coa_id_search = get_child_coa($coa_id , $arr);
    }

    $start_date_post = $this->input->post('start_date' , true);

    if ($start_date_post == "" || $start_date_post == 0)
    {
        $start_date_post = $this->uri->segment(4);

        if ($start_date_post == "" || $start_date_post == 0)
        {
            $start_date_post = 0;
        }
        else
        {
            $start_date_post = str_replace('_','/',$start_date_post);
        }
    }

    if ($start_date_post != 0)
    {
        $start_date=date_slash($start_date_post);
        $start_date_post = str_replace('/','_',$start_date_post);
    }

    $end_date_post = $this->input->post('end_date', true);

    if ($end_date_post == "" || $end_date_post == 0)
    {
        $end_date_post = $this->uri->segment(5);

        if ($end_date_post == "" || $end_date_post == 0)
        {
            $end_date_post = 0;
        }
        else
        {
            $end_date_post = str_replace('_','/',$end_date_post);
        }
    }

    if ($end_date_post != 0)
    {
        $end_date=date_slash($end_date_post);
        $end_date_post = str_replace('/','_',$end_date_post);
    }

    $total_rows = array();
    $coa_id_query = "";
    $start_date_query = "";
    $end_date_query = "";

    if(!empty($coa_id_search))
    {
        $coa_id_query = " AND je.coa_id in (".$coa_id_search.")";
    }

    if ($start_date != "" && $end_date != "")
    {
        if ($start_date == $end_date)
        {
            $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") = '".$start_date."'";
        }
        else
        {
            $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") between '".$start_date."' AND '".$end_date."'";
        }
    }
    else
    {
        if ($start_date != "")
        {
            $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") >= '$start_date' ";
        }

        if ($end_date != "")
        {
            $end_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") <= '$end_date'";
        }
    }



    if(!empty($student_select))
    {

        $student_join = " INNER JOIN " . get_school_db() . ".student_chalan_form as scf ON scf.s_c_f_id = je.type_id ";
        $sect_student_where = "AND scf.student_id=".$student_select."";

    }
    elseif(!empty($section_id)&& empty($student_select))
    {

       $student_join =  " INNER JOIN  ". get_school_db() . ".student_chalan_form as scf ON scf.s_c_f_id = je.type_id INNER JOIN ". get_school_db() . ".student as std ON scf.student_id = std.student_id";
       $sect_student_where =  "AND std.section_id=".$section_id."";

    }


    $journal_str = "SELECT coa.account_head, coa.account_number, je.* FROM " . get_school_db() . ".journal_entry as je
                                            INNER JOIN  ". get_school_db() . ".chart_of_accounts as coa ON je.coa_id = coa.coa_id
                                            INNER JOIN ".get_school_db().".school_coa as s_coa ON (s_coa.coa_id = coa.coa_id AND s_coa.school_id = ".$school_id.")
                                            $student_join
                                            WHERE je.school_id = ".$school_id." " . $coa_id_query . " ". $start_date_query . " " . $end_date_query." ".$sect_student_where."
                                            ORDER BY je.entry_date ASC, je.journal_entry_id ASC";


    $total_str = "SELECT count(*) as total FROM " . get_school_db() . ".journal_entry as je
                                            INNER JOIN  ". get_school_db() . ".chart_of_accounts as coa ON je.coa_id = coa.coa_id
                                            INNER JOIN ".get_school_db().".school_coa as s_coa ON (s_coa.coa_id = coa.coa_id AND s_coa.school_id = ".$school_id.")
                                            $student_join
                                            WHERE je.school_id = ".$school_id." " . $coa_id_query . " ". $start_date_query . " " . $end_date_query." ".$sect_student_where."
                                            ORDER BY je.entry_date ASC, je.journal_entry_id ASC";

    /* $total_str = "SELECT count(*) as total FROM " . get_school_db() . ".journal_entry as je
                     WHERE je.school_id = " . $school_id . " " . $coa_id_query . "		 ". $start_date_query . " " . $end_date_query;*/

    $total_rows = $this->db->query($total_str)->row();

    $config['total_rows'] = $total_rows->total;
    $total_rows = $config['total_rows'];

    $config['base_url'] = base_url() . "transection_account/ledger/". $coa_id . "/" . $start_date_post . "/" . $end_date_post . "";

    //$config['base_url'] = base_url() . "transection_account/ledger/?id=israr";

    $page_num = $this->uri->segment(6);

    if ( !isset($page_num) || $page_num == "")
    {
        $page_num = 0;
        $start_limit = 0;
    }
    else
    {
        $start_limit = ($page_num-1)*$per_page;
    }

    $this->load->library('pagination');
    $this->pagination->initialize($config);

    if ($start_limit > 0)
    {
        $journal_str_sum = "SELECT je.journal_entry_id, je.debit, je.credit  FROM " . get_school_db() . ".journal_entry as je 
                                    WHERE je.school_id = ".$school_id." " . $coa_id_query . " ". $start_date_query . " " . $end_date_query."
                                    ORDER BY je.entry_date ASC, je.journal_entry_id ASC 
                                    LIMIT 0, ". $start_limit;

        $journal_result_sum = $this->db->query($journal_str_sum)->result_array();

        if (count($journal_result_sum) > 0)
        {
            foreach ($journal_result_sum as $row_sum)
            {

                $debit += $row_sum['debit'];

                $credit += $row_sum['credit'];

            }
        }
    }


    $journal_result = $this->db->query($journal_str)->result_array();
    //echo $this->db->last_query();


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
                <?php echo get_phrase('student_fee_report'); ?>
            </h3>
        </div>
    </div>

    <span id="message" style="color: red;"></span>
    <form action="<?php echo base_url();?>transection_account/student_fee_report_ledger" name="add" id="search_ledger" method="POST">
        <div>
            <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific records">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                    <label class="control-label">
                    <?php echo get_phrase('class-Section');?></label>
                    <select id="section_filter" class="form-control" name="section_id" class="form-control" required="">
                        <?php echo section_selector($section_id);?>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                    <label class="control-label">
                    <?php echo get_phrase('student');?></label>
                    <select name="student_select" id="student_select" class="form-control">
                       <?php 
                        if(isset($student_select) && isset($student_select)){
    				   	    echo section_student($section_id,$student_select);
    				    }else{?>
    				   	<option value=""><?php echo get_phrase("select_student");?></option>
    				  <?php } ?>
                    </select>
                </div>
                <!--<div class="col-md-4">-->
                <!--   <div class="form-group">-->
                <!--        <label for="coa_id">Select Chart Of Account</label>-->
                <!--        <select class="form-control" id="coa_id" name="coa_id">-->
                <!--            <option value=""><?php echo get_phrase('select_chart_of_account'); ?></option>-->
                <!--            <?php //echo coa_list_h(0,$coa_id,0,0,0); ?>-->
                <!--        </select>-->
                <!--    </div>-->
                <!--</div>-->
                <div class="col-md-6">
                   <div class="form-group">
                        <label for="start_date">Select Start Date</label> 
                        <input id="start_date" name="start_date" class="form-control datepicker" value="<?php echo date_dash($start_date);?>" placeholder="Select Start Date" <?php /*required*/ ?> data-format="dd/mm/yyyy" />
                        <span style="color: red;" id="sd"></span>
                    </div>
                </div>
                <div class="col-md-6">
                   <div class="form-group">
                        <label for="end_date">Select End Date</label> 
                        <input id="end_date" name="end_date" class="form-control datepicker"  value="<?php echo date_dash($end_date);?>" placeholder="Select End Date" <?php /*required*/ ?> data-format="dd/mm/yyyy" />
                        <span style="color: red;" id="ed"></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group" style="margin-top: 10px;">
                        <button type="submit" id="select" value="submit" class="modal_save_btn" name="save"><?php echo get_phrase('filter'); ?></button>
                        <a id="btn_show" href="<?php echo base_url(); ?>transection_account/student_fee_report_ledger" class="btn btn-danger" style="display:none;padding:5px 8px !important;background: #f44336 !important;border: 1px solid #f44336 !important;"><?php echo get_phrase('remove_filters'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <?php
        //if (right_granted('ledger_view')) {
    ?>
    <div class="col-md-12">
    <table class="table table-bordered table_export" ata-step="6" data-position='top' data-intro="all student fee records">
        <thead>
            <tr>
            <th>
                <?php echo get_phrase('sn.no'); ?>
            </th>
            <th>
                <?php echo get_phrase('date'); ?>
            </th>
            <th>
                <?php echo get_phrase('account_head'); ?>
            </th>
            <th>
                <?php echo get_phrase('account_number'); ?>
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
        if (count($journal_result) > 0)
        {
            $j=$start_limit+1;
            foreach ($journal_result as $row)
            {
                ?>
                <tr>
                    <td>
                      <?php echo $j; ?>
                    </td>
                    <td width="100px;">
                        <?php
                        echo convert_date($row['entry_date']); ?>
                    </td>
                    <?php  /*$coa_head = $this->db->query("SELECT account_number , account_head FROM ".get_school_db().".chart_of_accounts
                                                WHERE school_id = ".$school_id." AND coa_id = ".$row['coa_id'].	"")->row(); */

                    ?>
                    <td width="100">
                        <?php echo $row['account_head']; ?>

                    </td>
                    <td>
                        <?php echo $row['account_number']; ?>
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
                            echo "(" . $d . ")";
                        } else
                        {
                            echo $row['debit'];
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
                            echo "(" . $c . ")";
                        } else
                        {
                            echo $row['credit'];
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
                            echo "(" . $b . ")";
                        } else
                        {
                            echo $balance;
                        }
                        ?></td>
                </tr>
                <?php
            $j++;
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
                            $("#student_select").html('<select><option value=""><?php echo get_phrase('selecct_student'); ?></option></select>');
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

