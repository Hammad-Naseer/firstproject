

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('Challan'); ?>
        </h3>
    </div>
</div>


<div class="row" style="padding-left: 40px !important;">
    <div id="print_btn" class="modal_save_btn" data-step="1" data-position='bottom' data-intro="press this button print all chalan"><?php echo get_phrase('print');?></div>

    
    
    <?php
   // $url_r= $_SERVER['HTTP_REFERER'];
    $vertical_url = "";
    $horizantal_url = "";
    $flag_h_v = false;
    $disable_h_v = "";
    $url_r = "";


    if($page_type == 'single')
    {
        if($this->uri->segment(1) == "view_print_chalan") {
            $flag_h_v = true;
            // $disable_h_v = "class = disable_h__v";
        }
        //$vertical_url = base_url()."class_chalan_form/view_print_chalan/".$this->uri->segment(3)."/".$this->uri->segment(4)."/".$this->uri->segment(5);
        $horizantal_url = base_url()."class_chalan_form/view_print_chalan_2/".$this->uri->segment(3)."/".$this->uri->segment(4)."/".$this->uri->segment(5);
        $vertical_url = base_url()."class_chalan_form/view_print_chalan/".$this->uri->segment(3)."/".$this->uri->segment(4)."/".$this->uri->segment(5);

        $url_r = base_url()."class_chalan_form/edit_chalan_form/".$this->uri->segment(3)."/2";
        ?>
        &nbsp;&nbsp;&nbsp;
        <a href="<?php echo $url_r; ?>" class="modal_save_btn"><?php echo get_phrase('back_to_listing');?></a>
        &nbsp;&nbsp;&nbsp;
        <a href="<?php echo $vertical_url; ?>" data-step="2" data-position='bottom' data-intro="click this icon chalan view convert verticaly" style="position: relative;top: 12px;"> <img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/vertical.png"></a>
        <?php if($flag_h_v){ ?>
        <a href="<?php echo $horizantal_url; ?>" data-step="3" data-position='bottom' data-intro="click this icon chalan view convert horizontaly" style="position: relative;top: 12px;"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/horizontal.png"></a>
        <?php
        }else{ }
    }else{
        if($this->uri->segment(2) == "view_print_chalan_class") {
            $flag_h_v = true;
        }

        $horizantal_url = base_url()."monthly_fee/view_print_chalan_class/".$this->uri->segment(3)."/".$this->uri->segment(4)."/".$this->uri->segment(5);
        $vertical_url = base_url()."monthly_fee/view_print_chalan_class_2/".$this->uri->segment(3)."/".$this->uri->segment(4)."/".$this->uri->segment(5);
        $url_r = base_url()."monthly_fee/monthly_bulk_listing";
        ?>
        &nbsp;&nbsp;&nbsp;
        <a href="<?php echo $url_r; ?>" class="modal_save_btn"><?php echo get_phrase('back_to_listing');?></a>
        <?php
            if($flag_h_v){
        ?>
        &nbsp;&nbsp;&nbsp;
        <a href="<?php echo $horizantal_url; ?>" data-step="2" data-position='bottom' data-intro="click this icon chalan view convert verticaly" style="position: relative;top: 12px;"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/vertical.png"></a>
        <?php } ?>
        <a href="<?php echo $vertical_url; ?>" data-step="3" data-position='bottom' data-intro="click this icon chalan view convert horizontaly" style="position: relative;top: 12px;"><img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/horizontal.png"></a>
        <?php } ?>
</div>
<div class="row demo" id="print_form">
    <style>
        .term_condition  {
           font-size: 10px;
        }
        .disable_h__v
        {
            /*border: 1px solid #ff3a26;*/
            /*font-size:20px;*/
        }
        td {
            vertical-align: middle;
            padding: 2px 5px 2px 5px;
        }
        .h4,h4 {
            font-size: 14px !important;
            font-weight: bold;
        }

        @media print {
            a {
                display: none !important;
            }
            table {
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
                /*size: auto;
                size: A4;*/
                size:landscape;
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
    foreach($query_ary as $row_data)
    {
        
        $s_c_f_id=$row_data['s_c_f_id'];
        $query_a=$this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=".$_SESSION['school_id'])->result_array();

        if($row_data['status']>=4)
        {
            $copy = array( 1=>"School Copy",2=>"Student Copy",3=>"Bank Copy");
           
            for($i=1; $i<=3; $i++)
            {
                ?>
                
                <div style="width:32%;float: left;margin-left: 10px;overflow: hidden;min-height:770px;border: 0px solid #eee;margin-bottom:0px;margin-top:15px;">
                  
                    <table class="table-bordered" cellpadding="2" border="1" style="border-bottom:0px solid #FFF; margin-top:0px;width: 100%;">
                        <tr>
                            <td style="border-right:none !important;     width: 100px; ">
                                <?php 
                                    $query_logo=$this->db->query("select * from ".get_school_db().".chalan_settings where school_id=".$_SESSION['school_id'])->result_array();
                                    $logo = $query_logo[0]['logo'];
                                ?>
                                <img src="<?php echo display_link($logo) /*display_link($row_data['school_logo'],'')*/
                                ?>" style="height: 65px; width: 70px;">
                            </td>
                            <td style="border-left:none !important; padding-top:10px; ">
                                <h4 align="center" style="font-size:20px !important;margin-left: -60px;">
                                    <?php echo $query_logo[0]['school_name'];?>
                                </h4>
                            </td>
                        </tr>
                        <tr align="center">
                            <td colspan="2">
                                <p><?php echo $query_logo[0]['address'];?></p>
                            </td>    
                        </tr>
                        <tr align="center">
                            <td colspan="2">
                                <b><?php echo $query_logo[0]['bank_details']; ?></b>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2"><?php echo get_phrase('chalan_no');?>:
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
                                $student_roll = $row_data['roll'];
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


                        <tr>
                            <td width="35%">
							<?php echo get_phrase('class');?> - <?php echo get_phrase('sec');?>.</td>
                            <td>
                                <?php echo $row_data['class'].' - '.$row_data['section'] ?>
                            </td>
                        </tr>

                        <tr>
                            <td><?php echo get_phrase('issue_date');?>:</td>
                            <td>
                                <?php 
                                $date1=explode(' ',$row_data['issue_date']);
                                echo convert_date($date1[0]);
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td><?php echo get_phrase('due_date');?>:</td>
                            <td><?php 
                                $query_class_challan = $this->db->query("select * from ".get_school_db().".class_chalan_form where c_c_f_id = ".$row_data['c_c_f_id']." And school_id=".$_SESSION['school_id'])->result_array();
                                $due_days = $query_class_challan[0]['due_days'];
                                echo date_view($row_data['due_date']);
                            ?></td>

                        </tr>
                    </table>

                    <table class="table-bordered" cellpadding="2" border="1" style="border-top: 0px #FFF !important;">
                        <tr>
                            <td width="100%">
							<?php echo get_phrase('particulars');?></td>
                            <td  style=" text-align:right;"><?php echo get_phrase('amount');?></td>
                        </tr>

                      <?php
                        $count_num=1;
                        $s_c_f_id=$row_data['s_c_f_id'];

                        $query_a_str = "select * from ".get_school_db().".student_chalan_detail
                        where s_c_f_id=$s_c_f_id and type != 2 and school_id=".$_SESSION['school_id'];
                        $query_a=$this->db->query($query_a_str)->result_array();
                        if(count($query_a) > 0)
                        {
                            $query_type_id_concat = "select GROUP_CONCAT(type_id) AS TypeID from ".get_school_db().".student_chalan_detail
                            where s_c_f_id=$s_c_f_id and type != 2 and school_id=".$_SESSION['school_id'];
                            $query_type_id_concat_run = $this->db->query($query_type_id_concat)->row();
                            $TypeID = '';
                            if($query_type_id_concat_run->TypeID == NULL)
                            {
                                $TypeID = 0;
                            }else{
                                $TypeID = $query_type_id_concat_run->TypeID;
                            }
                        }
                        
                        $chalan="";
                        $discount="";
                        $arrears="";
                        $totle=0;
                        $related_ids = array();

                        foreach($query_a as $rec_row1)
                        {
                            $rec_row1['type_id'];
                            if($rec_row1['type']==1 || $rec_row1['type']==5)
                            {
                                $related_ids[$rec_row1['s_c_d_id']]['amount']=$rec_row1['amount'];
                                $related_ids[$rec_row1['s_c_d_id']]['title']=$rec_row1['fee_type_title'];
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
                        
                        $my_month = date('m',strtotime($row_data['due_date']));
                        // Custom Fee Setting
                        $check_custom_fee_student = $this->db->query("SELECT * FROM ".get_school_db().".student_fee_settings WHERE student_id = '".$row_data['student_id']."' AND month = '$my_month' AND year = '".$row_data['s_c_f_year']."' AND fee_type = 1 AND is_bulk = 0 AND fee_type_id NOT IN ($TypeID) ")->result_array();
                        foreach($check_custom_fee_student as $check_custom_fee_student_data)
                        {
                            $chalan = $chalan.'<tr>
                            <td>'.$check_custom_fee_student_data['title'].'</td>
                            <td  style=" text-align:right;" >'.$check_custom_fee_student_data['amount'].'</td>
                            <tr>';
                            $totle = $check_custom_fee_student_data['amount'] + $totle;
                        }

                       echo $chalan;
                       echo $arrears;
                        
                        
                        // Single Discount Query Here
                        // echo "SELECT * FROM ".get_school_db().".student_fee_settings WHERE student_id = '".$row_data['student_id']."' AND month = '$my_month' AND year = '".$row_data['s_c_f_year']."' AND fee_type = 2 AND is_bulk = 0";
                        $check_alread_discount = $this->db->query("SELECT * FROM ".get_school_db().".student_fee_settings WHERE student_id = '".$row_data['student_id']."' AND month = '$my_month' AND year = '".$row_data['s_c_f_year']."' AND fee_type = 2 AND is_bulk = 0");
                        if($check_alread_discount->num_rows() > 0){
                            /******************************
                            //   Single Chalan Discount Area
                            ***********************************/
                            $single_discount_data_temp = $this->db->query("SELECT * FROM ".get_school_db().".student_fee_settings WHERE student_id = '".$row_data['student_id']."' AND month = '$my_month' AND year = '".$row_data['s_c_f_year']."' AND fee_type = 2 AND is_bulk  = 0 ")->result_array();       
                            $single_discount_calculation = 0;
                            foreach($single_discount_data_temp as $single_disco){
                               if($single_disco['discount_amount_type'] == '1')
                               {
                                   $single_discount_percent = $single_disco['amount'];
                               }else if($single_disco['discount_amount_type'] == '0'){
                                   $single_discount_percent = round(($totle / 100) * $single_disco['amount']);   
                               }
                                echo '<tr>
                                    <td>'.$single_disco['title'].'</td>
                                    <td  style=" text-align:right;" >('.$single_discount_percent.')</td>
                                <tr>';
                                $single_discount_calculation += $single_discount_percent;
                            }
                      $single_other_discount_plus = $single_discount_calculation;    
                        
                        }else{
                        /*****************************************
                        // Temparary Discount Code By Zeesha Arain
                        *****************************************/
                         
                       $my_discount_data_temp = $this->db->query("SELECT * FROM ".get_school_db().".class_chalan_discount ccd LEFT JOIN ".get_school_db().".discount_list dl ON dl.discount_id = ccd.discount_id WHERE c_c_f_id = '".$row_data['c_c_f_id']."' ")->result_array();
                       $discount_calculation = 0;
                       foreach($my_discount_data_temp as $disco){
                        $discount_percent = round(($totle / 100) * $disco['value']);
                            echo '<tr>
                                <td>'.$disco['title'].'</td>
                                <td  style=" text-align:right;" >('.$discount_percent.')</td>
                            <tr>';
                        $discount_calculation += $discount_percent;
                       }
                       $single_other_discount_plus = $discount_calculation;
                    }
                    $test_t = $totle - $single_other_discount_plus;


                    ?>
                    
      

                        <?php
                        echo '<tr>
                                <td><strong>Total Amount</strong></td>
                                
                                <td  style=" text-align:right;" ><strong> '.$test_t.'</strong></td>	
                                </tr>' ;
                                    $this->load->helper("num_word");
                                    echo '<tr>
                                <td style="border-bottom: none !important; text-transform:capitalize;font-size: 12px;" colspan="2"><strong>In Words: </strong>'.convert_number_to_words($test_t).' Rupees<br />
                                <span class="term_condition">'.nl2br($query_logo[0]['terms']).'</span>
                                    </td>
                                </tr>';
                        ?>

                        <tr>
                            <td colspan="2" style="border-top:none !important;   border-bottom:none !important; font-size: 12px; ">
                                <?php  $admin_req1=get_user_info($row_data['issued_by']);  ?>
                                <span><?php echo get_phrase('issued_by');?>: <?php echo $query_logo[0]['school_name'];?></span>
                               <br><br><br>
                               <br><br><br>
                                <center>
                                    <img src="<?php echo  display_link($row_data['bar_code'],'student'); ?>">
                                </center>
                                <br><br>
                                <p style="font-size: 8px !important; font-weight:bold; border-top:1px solid #000; margin-top:10px;"><?php echo get_phrase('note');?>: 
                                <?php echo get_phrase('this_challan_form_is_computer_generated_and_does_not_require_any_signature');?>
                               . </p>
                               <br>
                            </td>
                        </tr>
                    </table>
                </div>
                
            <?php
            }
        }

        else{
            $this->session->set_flashdata('club_updated','chalan is not approved');
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        
    ?>
    
    <br pagebreak="true" />
    
    <?php
        
    }
    ?>
</div>

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
            location.reload();
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
        /*@page {*/
        /*    size: 7in 9.25in;*/
        /*    margin: 27mm 16mm 27mm 16mm;*/
        /*}*/
		
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
            
            frameDoc.document.write( '<html><head><title><?php echo get_phrase('DIV_contents'); ?></title>');
            frameDoc.document.write( '</head><body>');

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