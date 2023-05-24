<?php
//if (right_granted('ledger_view')) {

    ?>
    <br>
    <br>
    <table class="table table-bordered datatable" id="table_export_exp">
        <thead>
        <tr>
            <th>
                <div>
                    <?php echo get_phrase('AAdate'); ?>
                </div>
            </th>

            <th>
                <div>
                    <?php echo get_phrase('transection_detail'); ?>
                </div>
            </th>
            <th>
                <div>
                    <?php echo get_phrase('debit'); ?>
                </div>
            </th>
            <th>
                <div>
                    <?php echo get_phrase('credit'); ?>
                </div>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $debit = 0;
        $credit = 0;
        $school_id = $_SESSION['school_id'];
        $qu_check = "";
        $qu_check2 = "";
        $coa_id = $coa_id;

        $start_date=date_slash($start_date);
        $end_date=date_slash( $end_date);

        if ($start_date != "" && $end_date != "") {

           // $start_date=date_slash( $data['start_date']);
         //   $end_date=date_slash( $data['end_date']);

            $qu_check .= " AND (entry_date between '$start_date' and '$end_date')";
        }
        elseif ($start_date != "" && $end_date == "") {
            $start_date1 = $start_date;
            $qu_check .= " AND entry_date >= '$start_date1' ";
        }
        elseif ($end_date != "" && $start_date == "") {
            $end_date1 = $end_date;
            $qu_check .= " AND entry_date <= '$end_date1'";
        }

        /*$data_=array('school_id'=>$school_id);
          $valll= "select
         1 as rec_type,
         scf.status as status,
         coa.account_number,
         coa.account_type,
         coa.account_head,
         scf.received_date as transection_date1,
         scf.issue_date as transection_date,
         scd.amount as total_amount,
         concat(scd.fee_type_title,' from ', scf.student_name) as trasection
         from ".get_school_db().".chart_of_accounts coa inner join ".get_school_db().".student_chalan_detail scd on scd.coa_id=coa.coa_id inner join ".get_school_db().".student_chalan_form scf on scf.s_c_f_id=scd.s_c_f_id

        where  $qu_check  coa.coa_id=$coa_id and scf.school_id=$school_id and scf.status in (4,5)

        union

        select
        2 as rec_type,
        at.type as status,
        coa.account_number,
         coa.account_type,
         coa.account_head,
         at.date as transection_date1,
         at.date as transection_date,
         at.amount as total_amount,
         at.title as trasection
        from ".get_school_db().".account_transection at inner join ".get_school_db().".chart_of_accounts coa on coa.coa_id=at.coa_id

        where

        $qu_check2  coa.coa_id=$coa_id and at.school_id=$school_id

        ";    */

        $journal_str = "SELECT * FROM " . get_school_db() . ".journal_entry where coa_id=".$coa_id." $qu_check";
        $journal_result = $this->db->query($journal_str)->result_array();

        //print_r($journal_result);
        if(count($journal_result)>0) {


            foreach ($journal_result as $row) {
                /*if($row['rec_type']==1)
                {
                    if($row['status']==5){
                        $debit+=$row['total_amount'];
                        $date = convert_date($row['transection_date1']);
                    }elseif($row['status']==4){
                        $credit+=$row['total_amount'];
                        $date = convert_date($row['transection_date']);
                    }



                }
                elseif($row['rec_type']==2){
                    $date = convert_date($row['transection_date']);
            if($row['account_type']==1){

                $credit+=$row['total_amount'];

                }elseif($row['account_type']==2){
                $debit+=$row['total_amount'];
                }
                }*/ ?>
                <tr>
                    <td>
                        <?php echo convert_date($row['entry_date']); ?>
                    </td>

                    <td>
                        <?php echo $row['detail'];
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($row['debit']<0)
                        {
                            $d = (-1)*($row['debit']);
                            echo "(".$d.")";
                        }
                        else
                        {
                            echo $row['debit'];
                        }
                        $debit += $row['debit'];
                        /*if($row['rec_type']==1)
                        {
                            if($row['status']==5){
                                echo $row['total_amount'];
                            }
                        }
                        elseif($row['rec_type']==2){

                    if($row['account_type']==2){
                        echo $row['total_amount'];
                            }


                            }*/
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($row['credit']<0)
                        {
                            $c = (-1)*($row['credit']);
                            echo "(".$c.")";
                        }
                        else
                        {
                            echo $row['credit'];
                        }


                        $credit += $row['credit'];
                        /*if($row['rec_type']==1)
                        {
                            if($row['status']==4){
                            echo $row['total_amount'];
                            }
                            }
                        elseif($row['rec_type']==2){
                    if($row['account_type']==1){
                            echo $row['total_amount']; }} ?>

                            <?php } */ ?>
                    </td>
                </tr>
                <?php
            }
            }   else
                {
                    ?><tr>
                    <td colspan="4"><center><?php echo get_phrase('no_record_found');?></center></td>
                     </tr> <?php
                }
        ?>

            <?php if(count($journal_result)>0) { ?>
                <tr>
                        <td>&nbsp;</td>

                    <td> <strong><?php echo get_phrase('total_values');?>(<?php echo get_phrase('pkr');?>)</strong></td>
                        <td><strong>
                        <?php

                            if ($debit<0)
                            {
                            $t_d = (-1)*($debit);
                            echo "(".$t_d.")";
                            }
                            else
                            {
                            echo $debit;
                            }
                            ?>

                            <?php //echo $debit; ?></strong>
                        </td>

                        <td><strong>
                            <?php
                            if ($credit<0)
                            {
                            $t_c = (-1)*($credit);
                            echo "(".$t_c.")";
                            }
                            else
                            {
                            echo $credit;
                            }
                            ?>

                            <?php //echo $credit; ?>
                            </strong>
                        </td>
                    </tr>
            <?php } ?>
                    </tbody>
                </table>
                <script type="text/javascript">
                    /*
                     jQuery(document).ready(function($)
                     {
                     var datatable = $("#table_export_exp").dataTable({
                     "sPaginationType": "bootstrap",
                     "sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
                     "oTableTools": {
                     "aButtons": [

                     {
                     "sExtends": "xls",
                     "mColumns": [0, 2, 3, 4]
                     },
                     {
                     "sExtends": "pdf",
                     "mColumns": [0, 2, 3, 4]
                     },
                     {
                     "sExtends": "print",
                     "fnSetText"	   : "Press 'esc' to return",
                     "fnClick": function (nButton, oConfig) {
                     datatable.fnSetColumnVis(1, false);
                     datatable.fnSetColumnVis(5, false);

                     this.fnPrint( true, oConfig );

                     window.print();

                     $(window).keyup(function(e) {
                     if (e.which == 27) {
                     datatable.fnSetColumnVis(1, true);
                     datatable.fnSetColumnVis(5, true);
                     }
                     });
                     },

                     },
                     ]
                     },

                     });

                     $(".dataTables_wrapper select").select2({
                     minimumResultsForSearch: -1
                     });
                     });
                     */
                </script>

                <?php
            //}
       // }  ?>

