<div class="row">
    <?php

    $return_link= $this->uri->segment(4);
    $student_id= $query_ary[0]['student_id'];
    $return_ary=array(
        1=>base_url().'c_student/student_pending',
        2=>base_url().'c_student/student_information',
        3=>base_url().'monthly_fee/view_detail_listing/'.$bulk_back_id,
        4=>base_url().'promotion/view_detail_listing/'.$bulk_back_id,
        5=>base_url().'c_student/withdraw_listing',
        6=>base_url().'transfer_student/transfer_information',
        6=>base_url().'transfer_student/transfer_information',
        7=>base_url().'transfer_student/receiving_transfer_list',
        8=>base_url().'payments/payment_listing/'.$student_id,
    );

    if($return_link==""){
        $url_r=$_SERVER['HTTP_REFERER'];
    }
    else{
        $url_r=$return_ary[$return_link];
    }


    //$url_r= $_SERVER['HTTP_REFERER'];
    $vertical_url = base_url()."class_chalan_form/view_print_chalan/".$this->uri->segment(3)."/".$this->uri->segment(4)."/".$this->uri->segment(5);
    $horizantal_url = base_url()."class_chalan_form/view_print_chalan_2/".$this->uri->segment(3)."/".$this->uri->segment(4)."/".$this->uri->segment(5);

    $flag_h_v = false;
    $disable_h_v = "";

    if($this->uri->segment(2) == "view_print_chalan_class") {
        $flag_h_v = true;
       // $disable_h_v = "class = disable_h__v";
    }
    ?>

    <div id="print_btn" class="btn btn-primary">
    
    <?php echo get_phrase('custom_print');?>
    </div>


    <a href="<?php echo $url_r; ?>" class="btn btn-primary">
    <?php echo get_phrase('back_to_listing');?></a>
        <?php if($flag_h_v)
        { ?>
            <i class="fa fa-bar-chart disable_h__v" aria-hidden="true"></i>
        <?php
        }else
        { ?>
            <a href="<?php echo $horizantal_url; ?>" class="disable_h__v"> <i class="fa fa-bar-chart" aria-hidden="true"></i></a>
       <?php
        }
        ?>
        <a href="<?php echo $horizantal_url; ?>"> <i class="fa fa-bars"aria-hidden="true"></i> </a>

</div>



<div class="row" id="print_form">


    <style>

        .disable_h__v
        {
            border: 1px solid #ff3a26;
            font-size:20px;

        }
        td {
            vertical-align: middle;
            padding: 2px 5px 2px 5px;


        ;
        }
        /*tr:hover {background-color: #f5f5f5; cursor: pointer;}
    tr:nth-child(even) { background-color: #F1F1F1;}*/

        .h4,
        h4 {
            font-size: 14px !important;
            font-weight: bold;
        }

        table {
            /*    border:solid #000 !important;
    border-width:1px 0 0 1px !important;*/
        }

        th,
        td {
            /* border:solid #000 !important;
    border-width:0 1px 1px 0 !important;*/
        }

        @media print {
            a {
                display: none !important;
            }
            table {
                /*    border:0px solid #000 !important;
    border-width:1px 0 0 1px !important;*/
                border-collapse: collapse;
                border-spacing: 0px;
            }
            th,
            td {
                border: 1px solid #c3c3c3 !important;
                /*   border-width:0 1px 1px 0 !important;*/
                padding: 5px;
                font-size: 10px;
                font-family: arial;
                border-style: dotted;
            }
            @page {
                size: auto;
                /* auto is the initial value */
                margin: 0;
                /* this affects the margin in the printer settings */
            }
        }
    </style>



    <?php



    if(count($query_ary)==0){
        $this->session->set_flashdata('club_updated','Chalan Form Not Available');
        redirect($_SERVER['HTTP_REFERER']);
        exit;

    }
    foreach($query_ary as $row_data){


        $s_c_f_id=$row_data['s_c_f_id'];
        $query_a=$this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=".$_SESSION['school_id'])->result_array();

        if($row_data['status']>=4){
            $copy = array(1=>"School Copy",2=>"Student Copy",3=>"Bank Copy");
            for($i=1; $i<=3; $i++){
                ?>
                <div style="width: 32%;
    float: left;
    margin-left: 10px;
    overflow: hidden;
    min-height: 800px;
  border: 1px solid #eee;
    margin-bottom:13px;">
                    <table class="table-bordered" cellpadding="2" border="1" style="border-bottom:0px solid #FFF; margin-top:10px;">
                        <tr>
                            <td style="border-right:none !important;     width: 100px; ">
                                <img src="<?php echo display_link($row_data['school_logo'],'') ?>" style="      height: 65px;
    width: 70px;">
                            </td>
                            <td style="border-left:none !important; padding-top:10px; ">
                                <h4 align="center">
                                    <?php echo $row_data['school_name'];?>
                                </h4>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <?php echo $row_data['school_bank_detail']; ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
							<?php echo get_phrase('chalan_no');?>:
                                <?php echo $row_data['chalan_form_number'];  ?>

                                <span style="float:right;">
						            <?php echo $copy[$i]; ?>
					            </span>
                            </td>
                        </tr>

                        <tr>
                            <td><?php echo get_phrase('roll_no');?></td>
                            <td>
                                <?php
                                $student_roll = $row_data[ 'student_roll' ];
                                echo $student_roll;
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td><?php echo get_phrase('name');?></td>
                            <td>
                                <?php
                                $student_name = $row_data[ 'student_name' ];
                                echo $student_name;
                                ?>
                            </td>
                        </tr>

                        <?php
                        /*
                                    <tr>
                                        <td>Department</td>


                                        <td colspan="3">
                                            <?php echo$row_data['department']; ?>
                                        </td>
                                    </tr>
                        */
                        ?>

                        <tr>
                            <td width="35%">
							<?php echo get_phrase('class');?> - <?php echo get_phrase('sec');?>.</td>
                            <td>
                                <?php echo $row_data['class'].' - '.$row_data['section'] ?>
                            </td>
                        </tr>

                        <tr>
                            <td><?php echo get_phrase('issue_date');?> :</td>
                            <td>
                                <?php echo $issue_date= $row_data['issue_date'];
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td><?php echo get_phrase('due_date');?>:</td>
                            <td><?php echo $due_date= $row_data['due_date']; ?></td>

                        </tr>
                    </table>

                    <table class="table-bordered" cellpadding="2" border="1" style="border-top: 0px #FFF !important;">
                        <?php // <td>S#</td> ?>
                        <tr>
                            <td width="65%">
							<?php echo get_phrase('particulars');?></td>
                            <td  style=" text-align:right;"><?php echo get_phrase('amount');?></td>
                        </tr>

                      <?php
                        $count_num=1;
                        $s_c_f_id=$row_data['s_c_f_id'];
                        $query_a=$this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=".$_SESSION['school_id'])->result_array();
                        $chalan="";
                        $discount="";
                        $arrears="";
                        $totle=0;

                        foreach($query_a as $rec_row1){

                            if($rec_row1['type']==1 || $rec_row1['type']==5)
                            {
                                // <td >'.$count_num.'</td>
                                $chalan=$chalan.'<tr>
                                <td>'.$rec_row1['fee_type_title'].'</td>
                                <td  style=" text-align:right;" >'.$rec_row1['amount'].'</td>
                                <tr>';
                                $totle=$rec_row1['amount']+$totle;
                            }
                            elseif($rec_row1['type']==2 || $rec_row1['type']==4)
                            {
                                // <td >'.$count_num.'</td>
                                $discount=$discount.'<tr>
	
                                <td>'.$rec_row1['fee_type_title'].'</td>
                                <td  style=" text-align:right;" >('.$rec_row1['amount'].')</td>
                                </tr>';
                                $totle=$totle-$rec_row1['amount'];
                            }
                            elseif($rec_row1['type']==3)
                            {
                                // <td >'.$count_num.'</td>
                                $arrears=$arrears.'<tr>
	
                                <td>'.$rec_row1['fee_type_title'].'</td>
                                <td  style=" text-align:right;" >'.$rec_row1['amount'].'</td>
                                </tr>';
                                $totle=$totle+$rec_row1['amount'];
                            }
                            $count_num++;
                        }

                        echo $chalan;
                        echo $arrears;
                        echo $discount;
                        echo '<tr>
                                <td><strong>Total Amount</strong></td>
                                
                                <td  style=" text-align:right;" ><strong> '.$totle.'</strong></td>	
                                </tr>' ;
                                    $this->load->helper("num_word");
                                    echo '<tr>
                                <td style="border-bottom: none !important; text-transform:capitalize;" colspan="2"><strong>In Words: </strong>'.convert_number_to_words($totle).' Rupees<br />
                                '.nl2br($row_data ['school_terms']).'
                                    </td>
                                </tr>';
                        ?>

                        <tr>
                            <td colspan="2" style="border-top:none !important;   border-bottom:none !important;  ">

                                <?php  $admin_req1=get_user_info($row_data['issued_by']);  ?>
                                <span><?php echo get_phrase('issued_by');?>: <?php echo  $admin_req1[0]['name']; ?></span>
                                <p style="font-size: 8px !important; font-weight:bold; border-top:1px solid #000; margin-top:10px;"><?php echo get_phrase('note');?>: 
                                
                 <?php echo get_phrase('this_challan form_is_computer_generated_and_does_not_require_any_signature');?>
                               . </p>
                                <center>
                                    <img src="<?php echo  display_link($row_data['bar_code'],'student'); ?>">
                                </center>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php }
        }

        else{
            $this->session->set_flashdata('club_updated','chalan is not approved');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    ?>
</div>

<!--<div id="print_btn2" class="btn btn-primary">Print</div>-->

<script>
    $( document ).ready( function ()
    {
        $( '#print_btn' ).click( function ()
        {
            var printContents = document.getElementById( 'print_form' ).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        });
    } );
</script>

<style>
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid blue;
        border-right: 16px solid green;
        border-bottom: 16px solid red;
        border-left: 16px solid pink;
        width: 100px;
        height: 100px;
        margin-left: auto;
        margin-right: auto;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 1s linear infinite;
    }

    .loader_small {
        border: 5px solid #f3f3f3;
        border-radius: 50%;
        border-top: 5px solid blue;
        border-right: 5px solid green;
        border-bottom: 5px solid red;
        border-left: 5px solid pink;
        width: 20px;
        height: 20px;
        margin-left: auto;
        margin-right: auto;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 1s linear infinite;
    }

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    @media print {
        a {
            display: none;
        }
    }

    .label {
        font-size: 10pt;
        font-weight: bold;
        font-family: Arial;
    }

    .contents {
        border: 1px dotted black;
        padding: 5px;
        width: 300px;
    }

    .name {
        color: #18B5F0;
    }

    .left {
        float: left;
        width: 0px;
        height: 0px;
    }

    .right {
        margin-left: 0px;
        line-height: 0px;
    }

    .clear {
        clear: both;
    }
</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<script type="text/javascript">
    $( function () {
        $( "#btnPrint" ).click( function () {
            var contents = $( "#print_form" ).html();
            var frame1 = $( '<iframe />');
            frame1[ 0 ].name = "frame1";
            frame1.css( {
                "position": "absolute",
                "top": "-1000000px"
            } );
            $( "body" ).append( frame1 );
            var frameDoc = frame1[ 0 ].contentWindow ? frame1[ 0 ].contentWindow : frame1[ 0 ].contentDocument.document ? frame1[ 0 ].contentDocument.document : frame1[ 0 ].contentDocument;
            frameDoc.document.open();
            //Create a new HTML document.
            frameDoc.document.write( '<html><head><title><?php echo get_phrase('DIV_contents'); ?></title>');
            frameDoc.document.write( '</head><body>');
            //Append the external CSS file.
            // frameDoc.document.write('<link href="style.css" rel="stylesheet" type="text/css" />');
            //Append the DIV contents.
            frameDoc.document.write( contents );
            frameDoc.document.write( '</body></html>');
            frameDoc.document.close();
            setTimeout( function () {
                window.frames[ "frame1" ].focus();
                window.frames[ "frame1" ].print();
                frame1.remove();
            }, 500 );
        } );
    } );
</script>