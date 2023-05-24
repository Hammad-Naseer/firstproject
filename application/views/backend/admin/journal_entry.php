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

       $student_join =  " INNER JOIN  ". get_school_db() . ".student_chalan_form as scf ON scf.s_c_f_id = je.type_id 
                                   INNER JOIN ". get_school_db() . ".student as std ON scf.student_id = std.student_id";

       $sect_student_where =  "AND std.section_id=".$section_id."";

    }

    $journal_str = "SELECT coa.account_head, coa.account_number, je.* FROM " . get_school_db() . ".journal_entry as je
                    INNER JOIN  ". get_school_db() . ".chart_of_accounts as coa ON je.coa_id = coa.coa_id
                    INNER JOIN ".get_school_db().".school_coa as s_coa ON (s_coa.coa_id = coa.coa_id AND s_coa.school_id = ".$school_id.")
                    $student_join
                    WHERE je.school_id = ".$school_id." " . $coa_id_query . " ". $start_date_query . " " . $end_date_query." ".$sect_student_where."
                    ORDER BY je.entry_date ASC, je.journal_entry_id asc";
    $journal_result = $this->db->query($journal_str)->result_array();

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
                <?php echo get_phrase('journal_entry'); ?>
            </h3>
        </div>
    </div>

    <span id="message" style="color: red;"></span>
    <form action="<?php echo base_url();?>transection_account/journal_entry" name="add" id="search_ledger" method="POST">
        <div>
            <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific records">
                <div class="col-md-3">
                   <div class="form-group">
                        <label for="coa_id">Select Chart Of Account</label>
                        <select class="form-control select2" id="coa_id" name="coa_id">
                            <option value=""><?php echo get_phrase('select_chart_of_account'); ?></option>
                            <?php
                               echo coa_list_h(0,$coa_id,0,0,0);
                               // echo coa_list_h(0,$edit_data[0]['dr_coa_id'] ,0 , 0 , $account_type);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                   <div class="form-group">
                        <label for="start_date">Select Start Date</label> 
                        <input id="start_date" name="start_date" autocomplete="off"  class="form-control datepicker" value="<?php echo date_dash($start_date);?>" placeholder="Select Start Date" <?php /*required*/ ?> data-format="dd/mm/yyyy" />
                        <span style="color: red;" id="sd"></span>
                    </div>
                </div>
                <div class="col-md-3">
                   <div class="form-group">
                        <label for="end_date">Select End Date</label> 
                        <input id="end_date" name="end_date" autocomplete="off"  class="form-control datepicker"  value="<?php echo date_dash($end_date);?>" placeholder="Select End Date" <?php /*required*/ ?> data-format="dd/mm/yyyy" />
                        <span style="color: red;" id="ed"></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" style="margin-top: 30px;">
                        <button type="submit" id="select" value="submit" class="btn btn-primary" name="save"><?php echo get_phrase('filter'); ?></button>
                        <a id="btn_show" href="<?php echo base_url(); ?>transection_account/journal_entry" class="modal_cancel_btn" style="padding:5px 8px !important; display:none;"><?php echo get_phrase('remove_filters'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <div class="col-md-12">
    <table class="table table-bordered table_export" ata-step="6" data-position='top' data-intro="all ledger records">
        <thead>
            <tr>
            <th>
                <?php echo get_phrase('type'); ?>
            </th>
            <th>
                <?php echo get_phrase('date'); ?>
            </th>
            <th>
                <?php echo get_phrase('account_head'); ?>
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
                      <?php echo journal_entry_type($row['entry_type']); ?>
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
                         - 
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
    
    <!--//***********************Date filter validation***********************-->

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

<!--//********************************************************************-->
        
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

        
    </script>

