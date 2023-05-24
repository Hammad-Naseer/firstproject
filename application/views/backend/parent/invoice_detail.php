<style>
    .watermark {
            position: absolute;
            color: lightgray;
            opacity: 0.25;
            font-size: 3em;
            font-size: 40px !important;
            width: 100%;
            top: 8%;
            transform: rotate(305deg);
            text-align: center;
            z-index: 0;
            top: 28%;
            left: -26px;
        }
        .watermark span{
            font-size: 70px !important;
        }
</style>
<?php  

    if($this->session->flashdata('club_updated')){
    echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
  ?>
<script>
$(window).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('invoice_details'); ?>
        </h3>
    </div>
</div>
<div class="col-lg-12 col-md-12">
    <?php $challan_ids_array = array(); ?>
    <?php $total_amount = 0; $i = 1; foreach($invoice_details as $row_data):?>
    <?php 
        $total_amt = get_student_challan_fee_details_for_parent($row_data['student_id'],$row_data['s_c_f_month'], $row_data['s_c_f_year'], $row_data['s_c_f_id']);
        $total_amount += $total_amt; 
        array_push($challan_ids_array , $row_data['s_c_f_id']);
    ?>
    <div class="" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="">
        <div class="panel panel-default">
            <div class="panel-heading" style="font-size: 15px !important; border-left: 7px solid #0073b7;border-radius: 0px;padding: 18px;">
                <h4 class="panel-title" style="display: contents;"> <!-- data-parent="#accordion" -->
                    <a data-toggle="collapse"  href="#collapse<?php echo $row_data['chalan_form_number'];?>" style="font-size: 15px !important;">
                        <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        <span class="myfsize">
                            Challan No.: <strong><?php echo $row_data['chalan_form_number'];?></strong>
                            &nbsp;&nbsp;&nbsp;
                            Amount.: 
                            <strong>
                                <?php
                                    echo number_format($total_amt,2);
                                ?>
                            </strong>
                        </span>
                    </a>
                    <span style="padding-top: 4px;" class="myfsize pull-right"> 
						<a href="#" style="color: white !important;font-size: 13px;position: relative;top: -3px;left: -6px;">
						    <i class="fa fa-eye"></i>
						    <?php echo get_phrase('view_challan');?>
						</a>
                    </span>
                </h4>
            </div>
            <div id="collapse<?php echo $row_data['chalan_form_number'];?>" class="panel-collapse collapse ">
                <div class="panel-body">
                    <div class="">
                        <div class="">
                            <?php
                                        
                                        $s_c_f_id = $row_data['s_c_f_id'];
                                        $query_a=$this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=".$_SESSION['school_id'])->result_array();
                                
                                        if($row_data['status'] >= 4)
                                        {
                                            $copy = array( 1=>"School Copy",2=>"Student Copy",3=>"Bank Copy");
                                           
                                            for($i=1; $i<=3; $i++)
                                            {
                                                ?>
                                                <div style="width:32%;
                                                    float: left;
                                                    margin-left: 10px;
                                                    overflow: hidden;
                                                   /* border:1px solid red !important;*/
                                                   min-height:770px;
                                                   /*max-height: 530px !important;*/
                                                    border: 0px solid #eee;
                                                    margin-bottom:0px;
                                                    margin-top:15px;
                                                    ">
                                                    <table class="table-bordered" cellpadding="2" border="1" style="border-bottom:0px solid #FFF; margin-top:0px;width: 100%;">
                                                        <div class="watermark">
                                                            <?php
                                                                if($row_data['status']==5){
                                                                    echo "<span>Paid</span>";
                                                                }else{
                                                                    echo "<span class='text-danger'>Unpaid</span>";
                                                                }
                                                            ?>
                                                        </div>
                                                        <tr>
                                                            <td style="border-right:none !important;     width: 100px; ">
                                                                <?php 
                                                                    $query_logo=$this->db->query("select * from ".get_school_db().".chalan_settings where school_id=".$_SESSION['school_id'])->result_array();
                                                                    $logo = $query_logo[0]['logo'];
                                                                ?>
                                                                <img src="<?php echo display_link($logo) 
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
                                                                $due_days            = $query_class_challan[0]['due_days'];
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
                                                      
                                                        $count_num  =  1;
                                                        $s_c_f_id=$row_data['s_c_f_id'];
                                                        $my_month = date('m',strtotime($row_data['due_date']));
                                                        $my_month_year = date('Y-m',strtotime($row_data['due_date']));
                                                        
                                                        $query_a_str = "select scd.*,dl.discount_id from ".get_school_db().".student_chalan_detail scd
                                                                        LEFT JOIN ".get_school_db().".discount_list dl ON dl.fee_type_id = scd.type_id
                                                                        where scd.s_c_f_id=$s_c_f_id and scd.type != 2 and scd.school_id=".$_SESSION['school_id'];
                                
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
                                                        
                                                        
                                                        $chalan      = "";
                                                        $discount    = "";
                                                        $arrears     = "";
                                                        $totle       = 0;
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
                                                                //   Single Chalan Discount Area
                                                                ********************************/
                                                                if($rec_row1['discount_id'] != "" || $rec_row1['discount_id'] != NULL){
                                                                    $check_alread_discount = $this->db->query("SELECT discount_amount_type,amount,title FROM ".get_school_db().".student_fee_settings WHERE student_id = '".$row_data['student_id']."' AND month = '$my_month' AND year = '".$row_data['s_c_f_year']."' AND fee_type = 2 AND fee_type_id = '".$rec_row1['discount_id']."' ");
                                                                    
                                                                    if($check_alread_discount->num_rows() > 0){
                                                                        $single_discount_data_temp = $check_alread_discount->result_array();       
                                                                        foreach($single_discount_data_temp as $single_disco){
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
                                                        
                                                        
                                                        $my_month = date('m',strtotime($row_data['due_date']));
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
                                                ?>
                                                    
                                      
                                
                                                <?php
                                                    echo '<tr>
                                                            <td><strong>Total Amount</strong></td>
                                                            <td  style=" text-align:right;" ><strong> '.$test_t.'</strong></td> 
                                                        </tr>' ;
                        
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
                                    <td> Arrears '.$unpaid_challan_month_year.' <br> <small>Challan Form Number - '.$get_unpaid_challan_data["chalan_form_number"].'</small></td>
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
                                                                
                        $this->load->helper("num_word");
                        echo '<tr>
                                <td style="border-bottom: none !important; text-transform:capitalize;font-size: 12px;" colspan="2"><strong>In Words: </strong>'.convert_number_to_words($number_total_amount).' Rupees<br />
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
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>
    <div class="col-lg-12 col-sm-12" style="padding-left: 11px;padding-right: 11px;">
        
        <table class="table table-bordered table_export" data-step="2" data-position='top' data-intro="discount types records">
            <tfoot> 
                <th colspan="4"> 
                    <h4><b>Total Amount: RS <?= number_format($total_amount,2); ?></b></h4>
                </th>
            </tfoot>
        </table>
        
    </div> 
    
    <div class="col-lg-12 col-sm-12" style="padding-left: 11px;padding-right: 11px;">
    <?php
       if($SelectedOption == 'Over The Counter')
       {
    ?>
        <br><br>
        <table class="table table-bordered table_export" data-step="2" data-position='top' data-intro="this payment code will be used for over the counter payment">
            <tfoot>
                <th colspan="4">
                    <h4><b>Payment Code:  <?= $PaymentCode; ?></b></h4>
                    <p>
                       Please Copy This Code Or Write This Down Someplace Safe, As You Will Need It; If You Want To Pay Via Internet Banking, For Further Instructions, You Can Click <a target="_blank" class="text-primary" href="<?= base_url() ?>assets/images/push_transaction.png">Here!</a>
                    </p>
                </th>
            </tfoot>
        </table>
    <?php
       }
       else
       {
    ?>
    
        <form target="_blank" action="https://staging-ipg.blinq.pk/payment/paymentprocess.aspx" method="post">
                <input type="hidden" name="client_id"           id="client_id"           value="<?php echo BLINQ_CLIENT; ?>" />
                <input type="hidden" name="payment_via"         id="payment_via"         value="<?php echo $PaymentVia; ?>" /> 
                <input type="hidden" name="order_id"            id="order_id"            value="<?php echo $InvoiceNumber; ?>" />
                <input type="hidden" name="paymentcode"         id="paymentcode"         value="<?php echo $PaymentCode; ?>" />
                <input type="hidden" name="product_description" id="product_description" value="<?php echo $ProductionDescription; ?>" />  
                <input type="hidden" name="encrypted_form_data" id="encrypted_form_data" value="<?php echo $encryptedFormData; ?>" />
                <input type="hidden" name="return_url"          id="return_url"          value="https://indiciedu.com.pk/parents/paymentcallback" />
                <button class="modal_save_btn float-right mt-3">Pay Now</button>
        </form>
    
    <?php
       }
    ?>
    </div>
    
    
</div>

