<style>
.toggle{position:relative;display:block;width:40px;height:20px;cursor:pointer;-webkit-tap-highlight-color:transparent;transform:translate3d(0,0,0)}.toggle:before{content:"";position:relative;top:3px;left:3px;width:34px;height:14px;display:block;background:#9a9999;border-radius:8px;transition:background .2s ease}.toggle span{position:absolute;top:0;left:0;width:20px;height:20px;display:block;background:#0992c9;border-radius:10px;box-shadow:0 3px 8px rgba(154,153,153,.5);transition:all .2s ease}.toggle span:before{content:"";position:absolute;display:block;margin:-18px;width:56px;height:56px;background:rgba(79,46,220,.5);border-radius:50%;transform:scale(0);opacity:1;pointer-events:none}.

.myRadio{display:block;position:relative;padding-left:35px;padding-top:4px;margin-bottom:12px;cursor:pointer;font-size:12px;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.myRadio input{position:absolute;opacity:0;cursor:pointer;height:0;width:0}.checkmark{display:-webkit-inline-box;margin-left:2px;position:relative;top:4px;height:17px;width:17px;background-color:#eee;border:2px solid #012b3c}.myRadio:hover input~.checkmark{background-color:#ccc}.myRadio input:checked~.checkmark{background-color:#2196f3}.checkmark:after{content:"";position:absolute;display:none}.myRadio input:checked~.checkmark:after{display:block}.myRadio .checkmark:after{left:4px;top:1px;width:5px;height:10px;border:solid #fff;border-width:0 3px 3px 0;-webkit-transform:rotate(45deg);-ms-transform:rotate(45deg);transform:rotate(45deg)}.button{padding:5px 14px}

</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('challan_form_records'); ?>
        </h3>
    </div>
</div>


<div class="row">
    <div class="col-lg-12 col-md-12">
        <?php
            if ($this->session->flashdata('club_updated'))
            {
                echo '<div align="center">
                     <div class="alert alert-success alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      ' . $this->session->flashdata('club_updated') . '
                     </div> 
                </div>';
            }
        ?>
    </div>
</div>

<div class="col-md-12">
    <div class="tab-pane box active" id="list" data-step="1" data-position='top' data-intro="all fee records">
        <div class="tbl-invoice">
            <table  class="table table-bordered table_export" >
        	<thead>
        		<tr>
            		<th><div><?php echo get_phrase('challan_number');?></div></th>
            		<th><div><?php echo get_phrase('month');?>/<?php echo get_phrase('year');?></div></th>
            		<th><div><?php echo get_phrase('total_amount');?></div></th>
            		<th><div><?php echo get_phrase('due_date');?></div></th>
            		<th><div class="text-center"><?php echo get_phrase('payable');?> <input class="select-all" type="checkbox" id="select-all" name="select-all" >(Select All)</div></th>
            		<th width="13%"><div><?php echo get_phrase('chalan_view');?></div></th>
    			</tr>
    		</thead>
            <tbody>
            <?php foreach($invoices as $row){
    		    $class_unpaid="";
        	   	if($row['status']!=5)
        		{
        			$class_unpaid="payment-due";
        		}
        	
        		$total_fee = get_student_challan_fee_details_for_parent($row['student_id'],$row['s_c_f_month'], $row['s_c_f_year'], $row['s_c_f_id']); 
    	    ?>
                <tr class="<?php echo $class_unpaid;?>">
                    <td><?php echo $row['chalan_form_number'];?></td>
                    <td><?php echo $row['s_c_f_month']." / ". $row['s_c_f_year'];?></td>
                    <td>
                        <?php 
                            echo 'Actual Amount - '.number_format($row['actual_amount'],2);
                            echo '<br>Dues - ' .number_format($total_fee,2); 
                        ?>
                    </td>
                    <td><?php echo convert_date($row['due_date']);?></td>
                    <td class="text-center">
                    <?php 
                        if($row['due_date'] < date("Y-m-d") && $row['status'] == 4):
                            echo '<span class="badge badge-danger text-center button" style="color:white !important;">Challan Due<span>';
                        else:    
                        if($row['status']== 5)
                        {
                            echo '<span class="badge badge-success text-center button">Paid<span>';
                        }else{
                            $date = date("Y-m-d");
                            $payment_status = $this->db->query("SELECT * FROM ".get_school_db().".payment_consumer WHERE challan_id = '".$row['s_c_f_id']."' AND DATE_FORMAT(inserted_at, '%Y-%m-%d') = '$date' ")->result_array();
                            if(count($payment_status) > 0)
                            {
                                echo '<span class="badge badge-warning text-center button" style="color:black !important;">Payment Pending<span>';
                            }else{
                    ?>
                            <label class="myRadio">Unpaid
                              <input type="checkbox" element-id="<?php echo $row['s_c_f_id'] ?>" amount="<?php echo $total_fee; ?>" 
                                     id="cbx_<?php echo $row['s_c_f_id'] ?>" name="challanIds[]" 
                                     value="<?php $row['s_c_f_id']  ?>" class="checkboxPayment">
                              <span class="checkmark"></span>
                            </label>
                    <?php
                            }
                        }
                        endif;
                    ?>
                    </td>
                    <td class="text-center" style="font-size:20px;cursor:pointer;">
                        <a title="View Challan" target="_blank" href="<?=base_url()?>class_chalan_form/view_print_chalan/<?= $row['s_c_f_id'] ?>/1">
                            <img src="<?=base_url()?>assets/eye.png" width="30" style="float:left;"> </a>
                        </a>
                        <a title="Download Challan" href="<?=base_url()?>parents/download_chalan_pdf/<?= $row['s_c_f_id'] ?>">
                            <img src="<?=base_url()?>assets/printer.png" width="30"> 
                        </a>        
                        <?php if($row['status']== 5){ ?>
                            <a title="Recieve Reciept" target="_blank" href="<?=base_url()?>parents/challan_recieve_reciept/<?= $row['s_c_f_id'] ?>/1">
                                <small class="badge badge-info button" style="background-color:#3f51b5;float:right;margin-top:8px;">Reciept</small>
                            </a>   
                        <?php } ?>
                    </td>
                </tr>
    
                <?php
                }
    			?>
               
            </tbody>
        </table>
        </div>
        <!-- 
        <form method="post" id="challanForm" action="<?php echo base_url() ?>parents/invoice_cart_proceed">
             <input type="hidden" id="selectedChallanIds" name="selectedChallanIds" value="" />
        </form>
        -->
        
        
    </div>
    <div class="float-right mt-4 mb-4">
        <button type="button" class="btn btn-red btn-icon btn-lg" style="width:250px;height:40px;font-weight:bold;text-align:left;border:1px solid white !important;cursor: auto;">
             Total Payable: <span id="totalAmount">0</span>
             <i class="fas fa-cart-plus" style="font-size: 21px;font-weight: 900;padding:4px 15px;"></i>
        </button>
        <br>
        <button type="button" class="modal_save_btn" id="proceedBtn" style="float:right;height:40px;font-weight:bold;text-align:left;border:1px solid white !important;">
            <b>Proceed Next</b>
            <i class="fas fa-long-arrow-alt-right"></i>
        </button>
    </div>
</div>


<div id="challanPaymentModel" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Payable Challans Details</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="data" method="post" enctype="multipart/form-data" class="form-horizontal form-groups-bordered validate">
            <div class="modal_data_pass">
                <!--Here Pass Modal Data-->
            </div>
      </div>
      <div class="modal-footer">
            <div class="float-right">
    			<button type="submit" id="save_btn" class="modal_save_btn">
    				<?php echo get_phrase('pay_now');?>
    			</button>
    			<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
    				<?php echo get_phrase('cancel');?>
    			</button>
    		</div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    
    $(document).ready(function(){
        
        $('.checkboxPayment').change(function(){
            
            var amount  = parseInt($(this).attr('amount'));
            var current = parseInt($('#totalAmount').html());
            if($(this).is(":checked")){
                current = current + amount;
                $('#totalAmount').html(current);
            }
            else
            {
                if(current > 0){
                    current = current - amount;
                    $('#totalAmount').html(current);
                }
                
                $('#select-all').prop('checked', false);
                
            }
            
        });
        
        $('#proceedBtn').click(function(){
            var IdsArray = [];
            
            $('.checkboxPayment').each(function(){
                
                var cb = $(this);
                var id = parseInt(cb.attr('element-id'));
                
                if(cb.is(":checked")){
                    if(!IdsArray.includes(id))
                    {
                        IdsArray.push(id);
                    }
                }
                
            })
            

            if(IdsArray.length > 0){
                
                
                $('#selectedChallanIds').val(IdsArray.join(','));
                
                $('#paymentModel').modal('show'); 
                
                
                
                //$('#challanForm').submit(); // commented due to change in model
                
                /*
                $.ajax({
                        type: 'POST',
                        data: {
                            ids: IdsArray.join(',')
                        },
                        url: "<?php echo base_url();?>parents/invoice_cart_proceed",
                        dataType: "html",
                        success: function(response) {
                            $('.modal_data_pass').html(response);
                            $('#challanPaymentModel').modal();
                        }
                });
                */
            
            }
            else
            {
                $('#selectedChallanIds').val('');
                
                Swal.fire({
                  icon: 'error',
                  title: 'Error...',
                  text: 'Please select at least one checkbox for Payables!'
                });
            }
            


  
        });
        
        
        $('#select-all').click(function() {
            
            if( $('#select-all').is(":checked") ){
                $('.checkboxPayment:checkbox').prop('checked', true);
            }
            else
            {
                $('.checkboxPayment:checkbox').prop('checked', false);
            }
            
        });
        
 
    });
    
</script>










<!-- Modal -->
<div class="modal fade in" id="paymentModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="font-size:25px;"><b>Payment Method</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form method="post" action="<?php echo base_url()  ?>parents/invoice_cart_proceed">
          
          <input type="hidden" id="selectedChallanIds" name="selectedChallanIds" value="" />
      
          <div class="modal-body">

                <div class="form-group">
                    <div class="col-sm-12">
                        <label for="field-2">
                            <?php echo get_phrase('select_a_payment_method');?>
                        </label>
                        <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                            <option>Select Payment Method</option>
                            <option value="Credit/Debit Card">Visa / Master Card</option>
                            <option value="Bank">Direct Bank Gateway</option>
                            <option value="Over The Counter">Online Banking & TCS Counter Payments</option>
                        </select>
                    </div>    
                </div>     
          </div>
          <div class="modal-footer">
             <button type="submit" class="modal_save_btn">Submit</button>
            <button type="button" class="modal_cancel_btn" data-dismiss="modal">Close</button>
          </div>
      
      </form>  
      
    </div>
  </div>
</div>
