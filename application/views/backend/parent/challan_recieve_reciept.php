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
    .sch_name{
        font-size: 30px;
        font-weight: 600;
        color: #198de9;
        letter-spacing: 5px;
        padding-left: 20px;
    }
    .sch_logo{
        height: 90px;
        width: 90px;
        display: inline;
        border-radius: 50%;
        border: 1px solid #198de9;
        padding: 2px;
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
        <h3 class="system_name inline">
            <?php echo $page_title; ?>
        </h3>
    </div>
</div>
<div class="col-md-8 col-sm-offset-2">
    <br><br>
    <?php $challan_ids_array = array(); ?>
    <?php $total_amount = 0; $i = 1; foreach($invoice_details as $row_data):?>
    <?php 
        $total_amt = get_student_challan_fee_details_for_parent($row_data['student_id'],$row_data['s_c_f_month'], $row_data['s_c_f_year'], $row_data['s_c_f_id']);
        $total_amount += $total_amt; 
        $query_a=$this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id= ".$row_data['s_c_f_id']." and school_id=".$_SESSION['school_id'])->result_array();
    ?>
    <div class="jumbotron thumbnail" style="background-color:#f4f1f1 !important;">
        <center>
            <?php 
                $query_logo=$this->db->query("select * from ".get_school_db().".chalan_settings where school_id=".$_SESSION['school_id'])->result_array();
                $logo = $query_logo[0]['logo'];
            ?>
            <img src="<?php echo display_link($logo) ?>" class="sch_logo">
            <span class="sch_name"><?php echo $query_logo[0]['school_name'];?></span>
        </center>
        <hr>
        <p class="text-center"><b>Receipt</b></p>
        <p class="text-success text-center" style="font-size: 16px;">
            <b>Notice Payment has been received successfully</b>
        </p>
        <div class="row">
            <div class="col-lg-6 col-md-6 text-center">
                <b>Challan Number</b>
                <br>
                <b>Month / Year</b>
                <br>
                <b>Amount</b>
                <br>
                <b>Challan Receive Date</b>
                <br>
            </div>
            <div class="col-lg-2 col-md-2">
                <b>:</b>
                <br>
                <b>:</b>
                <br>
                <b>:</b>
                <br>
                <b>:</b>
            </div>
            <div class="col-lg-4 col-md-4">
                <b><?php echo $row_data['chalan_form_number'];?></b>
                <br>
                <b><?= $row_data['s_c_f_month'] . ' - ' . $row_data['s_c_f_year'] ?></b>
                <br>
                <b><?php echo number_format($total_amount,2);?></b>
                <br>
                <b><?php echo date("d-M-Y h:i A",strtotime($row_data['received_date']));?></b>
            </div>
        </div>
    </div>
    
    <?php endforeach;?>
    <!--
    <div class="col-lg-12 col-sm-12" style="padding-left: 11px;padding-right: 11px;">
        
        <table class="table table-bordered table_export" data-step="2" data-position='top' data-intro="discount types records">
            <tfoot class="text-center">
                <th colspan="4" style="text-align: end;">
                    <h4><b>Total Amount: RS <?= number_format($total_amount,2); ?></b></h4>
                </th>
            </tfoot>
        </table>
        
        <form action="https://staging-ipg.blinq.pk/payment/paymentprocess.aspx" method="post">
                <input type="hidden" name="client_id" id="client_id" value="<?php echo BLINQ_CLIENT; ?>" />
                <input type="hidden" name="payment_via" id="payment_via" value="PAY" />
                <input type="hidden" name="order_id" id="order_id" value="<?php echo $InvoiceNumber; ?>" />
                <input type="hidden" name="paymentcode" id="paymentcode" value="<?php echo $PaymentCode; ?>" />
                <input type="hidden" name="product_description" id="product_description" value="TestInvoice" />
                <input type="hidden" name="encrypted_form_data" id="encrypted_form_data" value="<?php echo $encryptedFormData; ?>" />
                <input type="hidden" name="return_url" id="return_url" value="https://indiciedu.com.pk/production/parents/paymentcallback" />
                <button class="modal_save_btn float-right mt-3">Pay Now</button>
        </form>
        
        
    </div>    
    -->
</div>
