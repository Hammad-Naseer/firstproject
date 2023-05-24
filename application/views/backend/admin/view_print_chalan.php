

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
        <form id="get_report" method="post" action="https://dev.indiciedu.com.pk/monthly_fee/challans_pdf" style="display: inline;">  
                <input type="hidden" name="section_id" value="<?php echo $section_id ?>">
                <input type="hidden" name="month"      value="<?php echo $month ?>">
                <input type="hidden" name="year"       value="<?php echo $year ?>">
                <!--<input class="btn btn-primary" type="submit" id="crediential_pdf" value="Get Pdf">-->
        </form>
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

        $horizantal_url =  base_url()."monthly_fee/view_print_chalan_class/".$this->uri->segment(3)."/".$this->uri->segment(4)."/".$this->uri->segment(5);
        $vertical_url   =  base_url()."monthly_fee/view_print_chalan_class_2/".$this->uri->segment(3)."/".$this->uri->segment(4)."/".$this->uri->segment(5);
        $url_r          =  base_url()."monthly_fee/monthly_bulk_listing";
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
        td {
            vertical-align: middle;
            padding: 2px 5px 2px 5px;
        }
        .h4,h4 {
            font-size: 14px !important;
            font-weight: bold;
        }
        
        @page {
        size: A4;
        margin: 0;
    }
    .page {
        width: 297mm;
        height: 209mm;
        padding: 10mm;
        margin: 0;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        page-break-after: always;
    }
        
        @media print {
            /*.pagebreakhere {*/
            /*    page-break-after: always;*/
            /*}*/
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
                padding: 5px;
                font-size: 10px;
                font-family: arial;
                border-style: dotted;
            }
            
            html, body {
                width: 297mm;
                height: 209mm;
            }
            .page {
                margin-top:3px !important;
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
            
            /*@page {*/
            /*    size:landscape; */
            /*    margin: 0;*/
            /*}*/
            @page {size: A4 landscape; }
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
        $s_c_f_id  = $row_data['s_c_f_id'];
        $query_a   = $this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=".$_SESSION['school_id'])->result_array();

        if($row_data['status']>=4)
        {
            $copy = array( 1=>"School Copy",2=>"Student Copy",3=>"Bank Copy");
           
            for($i=1; $i<=3; $i++)
            {
                
    ?>
                <div  class="page" style="width:32%;float: left;margin-left: 10px;overflow: hidden;;border: 0px solid #eee;margin-bottom:0px;page-break-after: always;">
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
                            <td><?php echo get_phrase('month_year');?></td>
                            <td>
                                <?php
                                    echo month_of_year($row_data['s_c_f_month']);
                                    echo '-';
                                    echo $row_data['s_c_f_year'];
                                ?>
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
                        $my_month = date('m',strtotime($row_data['due_date']));
                        $my_month_year = date('Y-m',strtotime($row_data['due_date']));
                        $query_a_str = "select scd.* from ".get_school_db().".student_chalan_detail scd
                                        where scd.s_c_f_id=$s_c_f_id and scd.type != 2 and scd.school_id=".$_SESSION['school_id']." ";

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
                        $discount_calculation = 0;
                        $single_discount_calculation = 0;
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
                                
                                /******************************
                                // Chalan Discount Area
                                ********************************/
                                $get_fee_discount_types = get_fee_discount_types($rec_row1['type_id']);
                                
                                if($get_fee_discount_types != "" || $get_fee_discount_types != NULL){
                                    $check_alread_discount = $this->db->query("SELECT discount_amount_type,amount,title FROM ".get_school_db().".student_fee_settings WHERE student_id = '".$row_data['student_id']."' AND month = '$my_month' AND year = '".$row_data['s_c_f_year']."' AND fee_type = 2 AND fee_type_id IN('.$get_fee_discount_types.') ");

                                    if($check_alread_discount->num_rows() > 0){
                                        $single_discount_data_temp = $check_alread_discount->result_array();       
                                        foreach($single_discount_data_temp as $single_disco){
                                            $search_key = array_search($current_array, $arrays);
                                           if($single_disco['discount_amount_type'] == '1')
                                           {
                                               $single_discount_percent = $single_disco['amount'];
                                           }else if($single_disco['discount_amount_type'] == '0'){
                                               $single_discount_percent = round(($rec_row1['amount'] / 100) * $single_disco['amount']);   
                                           }
                                            echo '<tr>
                                                <td>'.$single_disco['title'].'</td>
                                                <td  style=" text-align:right;" >('.$single_discount_percent.')</td>
                                            <tr>';
                                            $single_discount_calculation += $single_discount_percent;
                                        }
                                    }
                                }
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
                    
                        $discount_calculation_merge = $discount_calculation+$single_discount_calculation;
                        $test_t = $totle - $discount_calculation_merge;
                    
                        echo '<tr>
                                <td><strong>Total Amount</strong></td>
                                <td  style=" text-align:right;" ><strong> '.$test_t.'</strong></td>	
                            </tr>';
                            
                        // Arrears Display'
                        $arreas_total_amount = 0;
                        $get_arrears_query = $this->db->query("SELECT * FROM ".get_school_db().".student_chalan_form WHERE student_id = '".$row_data['student_id']."' AND arrears_status = 1 AND s_c_f_id <> '$s_c_f_id'");
                        foreach($get_arrears_query->result_array() as $get_arrears_data):
                            $make_format = '01-'.$get_arrears_data["s_c_f_month"].'-'.$get_arrears_data["s_c_f_year"];
                            $arrears_month_year = date("M-Y",strtotime($make_format));
                            $arrears_amount = $get_arrears_data["arrears"];
                            echo '<tr>
                                    <td> Arrears '.$arrears_month_year.'</td>
                                    <td  style=" text-align:right;" >'.$get_arrears_data["arrears"].'</td>
                                </tr>';
                            $arreas_total_amount += $arrears_amount;    
                        endforeach;
                        // End Arrears
                        
                        $unpaid_challan_total_amount = 0;
                        $get_unpaid_challan_query = $this->db->query("SELECT * FROM ".get_school_db().".student_chalan_form WHERE student_id = '".$row_data['student_id']."' AND status = 4 AND form_type = 2 AND s_c_f_id <> '$s_c_f_id' AND DATE_FORMAT(due_date, '%Y-%m') < '$my_month_year'");
                        foreach($get_unpaid_challan_query->result_array() as $get_unpaid_challan_data):
                            $make_format = '01-'.$get_unpaid_challan_data["s_c_f_month"].'-'.$get_unpaid_challan_data["s_c_f_year"];
                            $unpaid_challan_month_year = date("M-Y",strtotime($make_format));
                            $unpaid_challan_amount = $get_unpaid_challan_data["actual_amount"];
                            echo '<tr>
                                    <td> Arrears '.$unpaid_challan_month_year.' <br> <small>Challan Form Number - 
                                    <a href="'.base_url().'class_chalan_form/edit_chalan_form/'.$get_unpaid_challan_data["s_c_f_id"].'" target="_blank" class="text-primary" title="view chalan form">
                                        '.$get_unpaid_challan_data["chalan_form_number"].'
                                        <b><i class="fas fa-info-circle"></i></b>
                                    </a>
                                    </small></td>
                                    <td  style=" text-align:right;" >'.$get_unpaid_challan_data["actual_amount"].'</td>
                                </tr>';
                            $unpaid_challan_total_amount += $unpaid_challan_amount;    
                        endforeach;
                        
                        $grand_total = 0;
                        $number_total_amount = 0;
                        if($arreas_total_amount > 0 || $unpaid_challan_total_amount > 0)
                        {
                            $grand_total = $test_t+$arreas_total_amount+$unpaid_challan_total_amount;
                            echo '<tr>
                                <td><strong>Grand Total Amount</strong></td>
                                <td  style=" text-align:right;" ><strong> '.$grand_total.'</strong></td>	
                            </tr>';
                            $number_total_amount = $grand_total;
                        }else{
                            $number_total_amount = $test_t;
                        }
                        $late_fee_fine = get_late_fee_fine($row_data['section_id'],$row_data['c_c_f_id']);
                        $this->load->helper("num_word");
                        $dues_in_words = '';
                        if($late_fee_fine->late_fee_fine != "" || $late_fee_fine->late_fee_fine != 0):
                            $late_fine = $late_fee_fine->late_fee_fine;
                            $late_fine_type = $late_fee_fine->late_fee_type;
                            if($late_fine_type == '1'):
                                $fine_amount = number_format($number_total_amount/100*$late_fine);
                            else:
                                $fine_amount = $late_fine;
                            endif;
                            $fine_total_amount = $fine_amount+$number_total_amount;
                            $dues_in_words = "<strong>In Words (After due date) : </strong>".convert_number_to_words($fine_total_amount)." Rupees<br />";
                        echo '<tr>
                                <td colspan="2" style=" text-align:center;"><strong>NOTE: After Due Date Will Apply Fine RS '.$fine_amount.'</strong></td>	
                            </tr>';
                        echo '<tr>
                                <td><strong>After Due Date</strong></td>
                                <td style=" text-align:right;"><strong>'.$fine_total_amount.'</strong></td>
                            </tr>';    
                        // echo '<tr>
                        //         <td><strong>NOTE: After Due Date</strong></td>
                        //         <td><strong>'.$fine_amount+$number_total_amount.'</strong></td>
                        //     </tr>';
                        endif;
                        
                        echo '<tr>
                                <td style="border-bottom: none !important; text-transform:capitalize;font-size: 12px;" colspan="2">
                                <strong>In Words (Within due date) : </strong>'.convert_number_to_words($number_total_amount).' Rupees<br />
                                '.$dues_in_words.'
                                <span class="term_condition">'.nl2br($query_logo[0]['terms']).'</span>
                                    </td>
                            </tr>';
                                
                        ?>

                        <tr>
                            <td colspan="2" style="border-top:none !important;   border-bottom:none !important; font-size: 12px; ">
                                <?php  $admin_req1=get_user_info($row_data['issued_by']);  ?>
                                <span><?php echo get_phrase('issued_by');?>: <?php echo $query_logo[0]['school_name'];?></span>
                               <br><br><br>
                                <center>
                                    <img src="<?php echo  display_link($row_data['bar_code'],'student'); ?>">
                                </center>
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
        else
        {
            $this->session->set_flashdata('club_updated','chalan is not approved');
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        
    ?>
    
    
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

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<script type="text/javascript">
    $(".page-container").addClass("sidebar-collapsed");
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