<style>
    .heading_s{
        text-align: center; 
        font-size: 16px;    
        text-decoration: underline;
    }
    .text_style{
        font-size: 12px;
    }
    .border_right{
        padding: 3px;
        border-right: 1px solid black;
    }
    .border_left{
        padding: 3px;
        border-left: 1px solid black;
    }
    .border_top{
        padding: 3px;
        border-top: 1px solid black;
    }
    .border_bottom{
        padding: 3px;
        border-bottom: 1px solid black;
    }
    .border_div{
         padding: 0px;
         border: 1px solid black;	
         margin-bottom:30px;
    }
    .glyphicon-remove {color:#E50509 !important;
        font-size: 14px;
    }
    .glyphicon-plus{color:#009B0C !important;
        font-size: 14px;
    }
    .glyphicon-pencil{    
        color: #0098d7 !important;
       	font-size: 14px;
    	padding-right: 18px;
    }
    .pipe{	
    	color:#000;font-size: 17px;
        padding-right: 3px;
    }
    .hide_div{
    	display: none;
    }
    .table th,td{
        padding:left:10px;
    }
</style>
<?php $total_fee = get_student_challan_fee_details_for_parent($query_ary[0]['student_id'],$query_ary[0]['s_c_f_month'], $query_ary[0]['s_c_f_year'], $query_ary[0]['s_c_f_id']); ?>
<?php 
    if ($this->session->flashdata('operation_info'))
    {
        echo '
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 myt mb-3">
                <div align="center">
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' . $this->session->flashdata( 'operation_info' ) . '
                    </div>        
                </div>
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
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar" style="margin-bottom:20px !Important;">
        <h3 class="system_name inline" style="border-bottom:none;margin-bottom:20px;">
          <?php echo get_phrase('chalan_form');?>
        </h3>
    </div>
</div>
<?php if($query_ary[0]['status'] > 3){ ?>
    <?php echo form_open(base_url().'class_chalan_form/student_chalan_payment/' , array('id'=>'challlan_form_recieve' , 'enctype' => 'multipart/form-data'));?>
<?php } ?>
<div class="row">
        <?php
            $return_link = $this->uri->segment(4);
            $bulk_id     = $this->uri->segment(6);
            $student_id  = $query_ary[0]['student_id'];
            $return_ary=array(
                1=>base_url().'c_student/student_pending',
                2=>base_url().'c_student/student_information',
                //3=>base_url().'c_student/student_information',
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
            }else{
                $url_r=$return_ary[$return_link];
            }
        ?>
    <!--<div class="col-lg-12 col-md-12 col-sm-12 myt topbar">-->
    <!--    <a href="<?php echo $url_r; ?>" class="btn btn-primary"><?php echo get_phrase('back_to_listing');?></a>-->
    <!--</div>-->
</div>

<div class="row">
    <div class="col-md-4 col-lg-4 col-sm-4 b-s" style="padding:5px !important;">
        <table class="table table-bordered" width="100%" style="box-shadow: none !important;">
            <tr>
                <td class="text_style border_bottom" align="center">
                    <img height="70" width="100%" style="object-fit: contain;" src="<?php echo display_link($_SESSION['school_logo'],'') ?>" />
                </td>    
                <td class="text_style border_bottom" align="center">    
                    <h4 style="position: relative;top: 18px;"><b><?php echo $query_ary[0]['school_name'];?></b></h4>
                </td>
            </tr>
            <tr>
                <td class="text_style border_bottom" colspan="2"><?php echo $query_ary[0]['school_bank_detail']; ?></td>
            </tr>
            <tr>
                <td class="text_style border_bottom border_right" ><?php echo get_phrase('chalan_no');?>: <?php echo $query_ary[0]['chalan_form_number'];  ?></td>
                <td class="text_style border_bottom border_right" ><?php echo 'Bank Copy'; ?></td>
            </tr>
            <tr>
                <td><?php echo get_phrase('month_year');?></td>
                <td>
                    <?php
                        
                        echo date("M-Y",strtotime($query_ary[0]['fee_month_year']));
                    ?>
                </td>
            </tr>
            <tr>
            	<td class="text_style border_bottom  border_right"><?php echo get_phrase('roll_no');?></td>
            	<td class="text_style border_bottom  border_right"><?php echo $query_ary[0]['roll']; ?></td>
            </tr>
            <tr>
                <td class="text_style border_bottom  border_right" >
                <?php echo get_phrase('student_name');?>
                </td>
                <td class="text_style border_bottom  border_right" >
                    <?php
                        $student_name=$query_ary[0]['student_name'];
                        echo $student_name ;
                    ?>
                </td>
            </tr>
            <tr>
                <td class="text_style border_bottom border_right" > <?php echo get_phrase('department');?> </td>
                <td class="text_style border_bottom" ><?php echo $query_ary[0]['department']; ?> </td>
            </tr>
            <tr>
                <td class="text_style border_bottom border_right" > <?php echo get_phrase('class');?> / <?php echo get_phrase('section');?></td>
                <td class="text_style border_bottom" > <?php echo $query_ary[0]['class'].' / '.$query_ary[0]['section']; ?> </td>
            </tr>
            <tr>
                <td class="text_style border_bottom border_right" ><?php echo get_phrase('issue_date');?>: </td>
                <td class="text_style border_bottom border_right"><?php  echo convert_date($query_ary[0]['issue_date']); //echo date('d-M-Y', strtotime($query_ary[0]['issue_date'])); ?></td>
            </tr>
            <tr>
                <td class="text_style border_bottom border_right" >
                	<?php echo get_phrase('due_date');?>:
                </td>
                <td class="text_style border_bottom border_right">
                    <?php
                     echo convert_date($query_ary[0]['due_date']); 
                     //echo date('Y-m-d', strtotime($issue_date.$query_ary[0]['due_days'])); 
                    ?>
                </td>
            </tr>
            <tr id="chalanshoaib">
                <td class="text_style  border_bottom border_right"><?php echo get_phrase('particulars'); ?></td>
                <td class="text_style  border_bottom"><?php echo get_phrase('amount'); ?></td>
            </tr>
            <?php
                $s_c_f_id    =  $query_ary[0]['s_c_f_id'];
                $count_num   =  1;
                $query_a_str = "select scd.* from ".get_school_db().".student_chalan_detail scd
                                        where scd.s_c_f_id=$s_c_f_id and scd.type != 2 and scd.school_id=".$_SESSION['school_id']." ";
                $query_a     =  $this->db->query($query_a_str)->result_array();
                if(count($query_a) > 0)
                {
                    $query_type_id_concat = "select GROUP_CONCAT(type_id) AS TypeID from ".get_school_db().".student_chalan_detail
                    where s_c_f_id = $s_c_f_id and type != 2 and school_id=".$_SESSION['school_id'];
                    $query_type_id_concat_run = $this->db->query($query_type_id_concat)->row();
                    $TypeID = '';
                    if($query_type_id_concat_run->TypeID == NULL )
                    {
                        $TypeID = '';
                    }else{
                        $TypeID = "AND fee_type_id NOT IN (" .$query_type_id_concat_run->TypeID . ")";
                    }
                }

                    $my_month = $query_ary[0]['s_c_f_month'];  
                    $my_month_year = date('Y-m',strtotime($query_ary[0]['due_date']));
                    
                    $chalan      = "";
                    $discount    = "";
                    $arrears     = "";
                    $totle       = 0;
                    $related_ids = array();
                    $discount_calculation = 0;
                    $fee_type_id_push = array();
                    $discount_calculation = 0;
                    $single_discount_calculation = 0;
                    foreach($query_a as $rec_row1)
                    {
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
                                    $check_alread_discount = $this->db->query("SELECT discount_amount_type,amount,title FROM ".get_school_db().".student_fee_settings WHERE student_id = '".$query_ary[0]['student_id']."' AND month = '$my_month' AND year = '".$query_ary[0]['s_c_f_year']."' AND fee_type = 2 AND fee_type_id IN('.$get_fee_discount_types.') ");
                                    // echo $this->db->last_query();
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

                            $discount=$discount.'<tr>

                            <td>'.$rec_row1['fee_type_title'].'</td>
                            <td  style=" text-align:right;" >('.$rec_row1['amount'].')</td>
                            </tr>';
                            $totle=$totle-$rec_row1['amount'];
                        }
                        
                        elseif($rec_row1['type']==3)
                        {

                            $arrears=$arrears.'<tr>

                            <td>'.$rec_row1['fee_type_title'].'</td>
                            <td  style=" text-align:right;" >'.$rec_row1['amount'].'</td>
                            </tr>';
                            $totle=$totle+$rec_row1['amount'];
                        }
                        $count_num++;
                    }

                    $check_custom_fee_student = $this->db->query("SELECT * FROM ".get_school_db().".student_fee_settings WHERE student_id = '".$query_ary[0]['student_id']."' AND month = '$my_month' AND year = '".$query_ary[0]['s_c_f_year']."' AND fee_type = 1 AND is_bulk = 0 $TypeID ")->result_array();
                    
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
    
                    // Total SumUp
                    $discount_calculation_merge = $discount_calculation+$single_discount_calculation;
                    $test_t = $totle - $discount_calculation_merge;
                    
                    echo '<tr>
                            <td><strong>Total Amount</strong></td>
                            
                            <td  style=" text-align:right;" ><strong> '.$test_t.'</strong></td>	
                        </tr>' ;
                    
                    // Arrears Display'
                        $arreas_total_amount = 0;
                        $get_arrears_query = $this->db->query("SELECT * FROM ".get_school_db().".student_chalan_form WHERE student_id = '".$query_ary[0]['student_id']."' AND arrears_status = 1 AND s_c_f_id <> '$s_c_f_id'");
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
                        $get_unpaid_challan_query = $this->db->query("SELECT * FROM ".get_school_db().".student_chalan_form WHERE student_id = '".$query_ary[0]['student_id']."' AND status = 4 AND form_type = 2 AND s_c_f_id <> '$s_c_f_id' AND DATE_FORMAT(due_date, '%Y-%m') < '$my_month_year'");
                        foreach($get_unpaid_challan_query->result_array() as $get_unpaid_challan_data):
                            $make_format = '01-'.$get_unpaid_challan_data["s_c_f_month"].'-'.$get_unpaid_challan_data["s_c_f_year"];
                            $unpaid_challan_month_year = date("M-Y",strtotime($make_format));
                            $unpaid_challan_amount = $get_unpaid_challan_data["actual_amount"];
                            echo '<tr>
                                    <td>
                                        <input type="hidden" name="prev_challan_s_c_f_id[]" value="'.$get_unpaid_challan_data["s_c_f_id"].'">
                                        Arrears '.$unpaid_challan_month_year.' <br> <small>Challan Form Number - 
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
                    
                    $this->load->helper("num_word");
                    echo '
                        <tr>
                            <td style="border-bottom: none !important; text-transform:capitalize;font-size: 12px;" colspan="2"><strong>In Words: </strong>'.convert_number_to_words($number_total_amount).' Rupees<br />
                                <span class="term_condition">'.nl2br($query_logo[0]['terms']).'</span>
                            </td>
                        </tr>'; 
                    ?>
                    
            <tr>
                <td class="text_style">
                    <span style="padding:2px;">
                        <?php
                            $admin_req1=get_user_info($query_ary[0]['issued_by']);
                            echo nl2br($query_ary[0]['school_terms']); echo "<br>";
                        ?>
                    </span>
                </td>
                <td class="text_style">
                    <span><?php echo get_phrase('issued_by');?>: <?php echo  $admin_req1[0]['name']; ?></span>                
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <p style="font-size: 8px !important; font-weight:bold; border-top:1px solid #000; margin-top:10px;"><?php echo get_phrase('note');?>: <?php echo get_phrase('this_challan_form_is_computer_generated_and_does_not_require_any_signature');?>. </p>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <img style="padding-left: 5px; " src="<?php echo  display_link($query_ary[0]['bar_code'],'student'); ?>">
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-8 col-lg-8 col-sm-8" style="margin-top:-15px;">
        <h3><?php echo get_phrase('student_detail');?></h3>
        <?php
            //$student_id=$this->uri->segment(3);
            $strd_rec_str = "select s.*, c.title as class_name from ".get_school_db().".student s inner join ".get_school_db().".class_section c on s.section_id=c.section_id  
            where s.school_id=".$_SESSION['school_id']." AND s.student_id=$student_id";
            $strd_rec=$this->db->query($strd_rec_str)->result_array();
            foreach($strd_rec  as $std_rec){
                $section_ary=section_hierarchy($std_rec['section_id']); 
            if($std_rec['image']!=""){
                $img_dis=display_link($std_rec['image'],'student');	
            }else{
                $img_dis=base_url().'/uploads/default.png';	
            }
        ?>
        <!--<div class="col-sm-12">-->
            <div class="profile-env mb-4">
    			<header class="row">
    				<div class="col-sm-2">
    					<a href="#" class="profile-picture">
        			        <img src="<?php echo $img_dis ; ?>" class="img-responsive img-circle" />
    					</a>
    				</div>
    				<div class="col-sm-10">
    					<ul class="profile-info-sections d-flex">
    						<li>
    							<div class="profile-name">
    								<strong>
    									<a href="#"><?php echo $res[0]['name'];?></a>
    									<a href="#" class="user-status is-online tooltip-primary" data-toggle="tooltip" data-placement="top" data-original-title="Online"></a>
    									<!-- User statuses available classes "is-online", "is-offline", "is-idle", "is-busy" -->						</strong>
    								<span><a href="#">Powered By Indici-Edu</a></span>
    							</div>
    						</li>
    						<li>
    							<div class="profile-stat">
    								<span><a href="#" class="text-dark"><b><?php echo get_phrase('department');?>: </b> <?php echo $section_ary['d']; ?></a></span>
    								<br>
    								<span><a href="#" class="text-dark"><b><?php echo get_phrase('class');?> : </b><?php echo $section_ary['c']; ?></a></span>
    								<br>
    								<span><a href="#" class="text-dark"><b><?php echo get_phrase('section');?> : </b><?php echo $section_ary['s']; ?></a></span>
    								<br>
    								<span><a href="#" class="text-dark"><b><?php echo get_phrase('roll_no');?> : </b><?php echo $std_rec['roll']; ?></a></span>
    							</div>
    						</li>
    					</ul>
    				</div>
			</header>
		</div>
        <!--</div>-->
<?php  } ?>
<table class="table table-bordered table-hover">
	<tr>
		<th><?php echo get_phrase('name');?></th><td> <?php echo student_detail($query_ary[0]['student_id']); ?></td>
	</tr>
	<tr>
		<th><?php echo get_phrase('generated_by');?></th>
		<td> 
    		<?php
                 $user_req=get_user_info($query_ary[0]['generated_by']);
                 echo  $user_req[0]['name']; 
            ?>
        </td>
	</tr>
	<tr>
		<th><?php echo get_phrase('generated_date');?></th>
		<td> 
    		<?php
    		 if($query_ary[0]['generation_date']=='0000-00-00 00:00:00'){
            }else{
     	        echo convert_date($query_ary[0]['generation_date'],0);
            }
            ?>
        </td>
	</tr>
	<tr>
		<th><?php echo get_phrase('approved_by');?></th>
		<td> 
		    <?php
                $user_req=get_user_info($query_ary[0]['approved_by']);
                echo  $user_req[0]['name']; ?>
        </td>
	</tr>
	<tr>
		<th><?php echo get_phrase('approval_date');?></th>
		<td>
		    <?php
		        if($query_ary[0]['approval_date']=='0000-00-00 00:00:00'){
                }else{
 	                echo convert_date($query_ary[0]['approval_date'],0);
                }
		    ?>
		</td>
	</tr>
	<tr>
		<th><?php echo get_phrase('issued_by');?></th>
		<td> 
		    <?php
                $user_req=get_user_info($query_ary[0]['issued_by']);
                echo  $user_req[0]['name']; 
            ?>
        </td>
	</tr>
	<tr>
		<th><?php echo get_phrase('issue_date');?></th>
		<td> 
		    <?php
			    if($query_ary[0]['issue_date']=='0000-00-00 00:00:00'){
			    }else{
 	                echo convert_date($query_ary[0]['issue_date'],0);
                }
		    ?>
		</td>
	</tr>
	<tr>
		<th><?php echo get_phrase('recieved_by');?></th>
		<td>
		    <?php
                $user_req=get_user_info($query_ary[0]['received_by']);
                echo  $user_req[0]['name']; 
            ?> 
        </td>
	</tr>
	<tr>
		<th><?php echo get_phrase('receivable_date');?></th>
		<td> 
		    <?php
		        if($query_ary[0]['received_date']=='0000-00-00 00:00:00'){
                }else{
 	                echo convert_date($query_ary[0]['received_date'],0);
                }
		    ?>
		</td>
	</tr>
	<tr>
		<th><?php echo get_phrase('Payment_date');?></th>
		<td>
		    <?php
				if($query_ary[0]['payment_date']=='0000-00-00'){
                }else{
 	                echo convert_date($query_ary[0]['payment_date'],0);
                }	
            ?>
        </td>
	</tr>
	<tr>
        <th><?php echo get_phrase('paid_amount');?></th>
        <td>
            <?php 
                if($query_ary[0]['received_amount']=='0'){	
                }else{	
                    echo $query_ary[0]['received_amount'];
                }	
            ?>
        </td>
    </tr>
        <?php
            //if($query_ary[0]['received_amount'] > $query_ary[0]['actual_amount']) { 
            if($query_ary[0]['received_amount'] > $total_fee) { 
        ?>
            <tr>
                <th><?php echo get_phrase('late_fee_fine');?>
                </th><td><?php echo $query_ary[0]['received_amount'] - $query_ary[0]['actual_amount'] ; ?></td>
            </tr>
        <?php } ?>
        <?php
        if($query_ary[0]['arrears']>0){ ?>
        <tr>
            <th><?php echo get_phrase('crrent_arrears');?></th>
            <td><?php echo $query_ary[0]['arrears']; ?></td>
        </tr>
        <?php } ?>
</table>


    <!--Recive Challan Form -->
	<div class="col-sm-12">
        <?php if($query_ary[0]['status']==4){ ?>	
        <div class="row" id="login_Box" style="padding-left:0px !important;padding-right:0px !important;">
            <h3><?php echo get_phrase('receive_chalan_form');?></h3>
            
            <div class="form-group col-lg-12 col-sm-12 col-xs-12">    
                <lable>
                    <?php echo get_phrase('total_amount');?>
                </lable>
                <input type="text" value="<?php echo $number_total_amount; ?>" class="form-control" readonly="">
            </div>
            
            <div class="form-group col-lg-12 col-sm-12 col-xs-12">    
                <lable>
                    <?php echo get_phrase('recieved_amount');?>
                </lable>
                <input type="hidden" name="s_c_f_id"  value="<?php echo $query_ary[0]['s_c_f_id'];  ?>"/>
                <input type="hidden" name="student_id"  value="<?php echo $query_ary[0]['student_id'];  ?>"/>
                <input type="text" class="form-control actual_amu" name="received_amount" value="<?php echo $number_total_amount; ?>" required="" id="actual_amu"  maxlength="10"/>
                <input type="hidden" class="form-control " name="actual_amu" value="<?php echo $test_t; ?>" required="" id="actual_amu" />
                <input type="hidden" class="form-control"  name="form_type" value="<?php echo $query_ary[0]['form_type'];?>" required=""/>
            </div>
            
            <div class="form-group col-lg-12 col-sm-12 col-xs-12">    
                <lable>
                    <?php echo get_phrase('arrears');?>
                </lable>
                <input id="arrears" name="arrears" class="form-control" value="0" readonly/>
            </div>
            
            <div class="form-group col-lg-12 col-sm-12 col-xs-12">    
                <lable>
                    <?php echo get_phrase('other_amount_title');?>
                </lable>
                <input id="other_title" name="other_title" class="form-control" value="<?php echo get_phrase('late_fee_fine');?>" maxlength="30" />
            </div>
            
            <div class="form-group col-lg-12 col-sm-12 col-xs-12">    
                <lable>
                    <?php echo get_phrase('other_amount');?>
                </lable>
                <input id="other_amount" name="other_amount" class="form-control" value="0"  readonly/>
            </div>
            
            <div class="form-group col-lg-12 col-sm-12 col-xs-12">    
                <lable>
                    <?php echo get_phrase('payment_date');?>
                </lable>
                <input class="form-control datepicker" name="payment_date"  value="<?php echo  date('d/m/Y'); ?>" data-format="dd/mm/yyyy" required="" />
            </div>

            <div <?= check_sms_preference(1,"style","sms") ?>>
                <div class="col-md-12 col-lg-12 col-sm-12 challan_spance" data-step="4" data-intro="If you want after paid chalan send sms then checked input">
                    <label><?php echo get_phrase('send_sms');?></label>
                    <input type="checkbox" id="send_message" name="send_message" class="" value="0"  />
                </div>
            </div>
            <div <?= check_sms_preference(1,"style","email") ?>>
                <div class="col-md-12 col-lg-12 col-sm-12 challan_spance" data-step="5" data-intro="If you want after paid chalan send email then checked input">
                    <label><?php echo get_phrase('send_email');?></label>
                    <input type="checkbox"  id="send_email" name="send_email" class="" value="0"  />
                </div>
            </div>    
            <?php if($query_ary[0]['form_type']==1 || $query_ary[0]['form_type']==3){?>
            <div class="col-md-12 col-lg-12 col-sm-12">
                <label> <?php echo get_phrase('study_pack_delivered');?> :  </label>
                <input type="checkbox" id="arrears" name="study_pack">
            </div>
            <?php } ?>
            <input type="hidden" name="status_val" value="<?php echo $query_ary[0]['status'];?>">
            <div class="col-md-12 col-lg-12  col-sm-12">
                <button <?php if($query_ary[0]['status']==5) { ?> disabled <?php } ?>  class="modal_save_btn" type="submit" style="float:right" data-step="6" data-position='left' data-intro="then submit receive button you successfully paid chalan">
                <?php echo get_phrase('recieve');?></button>
            </div>
        <?php echo form_close(); ?>
        </div>
        <?php } ?>
    </div>
    <br />

    <!--Approved Challan Form-->
    <?php if($query_ary[0]['status'] < 3){ ?>
    <?php echo form_open(base_url().'class_chalan_form/save_chalan_form/' , array('id'=>'challlan_form_save','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
        <!--<div class="row">-->
            <h3><?php echo get_phrase('approve_challan_form');?></h3>
            <div class="col-lg-12 col-sm-12 form-group">
                    <?php echo get_phrase('challan_form_status');?>:
                    <select class="form-control" name="status">
                        <?php echo student_form_status($query_ary[0]['status']); ?>
                    </select>
                </div>
            <div class="col-lg-12 col-sm-12 form-group">
                <?php echo get_phrase('comments');?>:
                <textarea class="form-control" name="comment"><?php echo $query_ary[0]['comment'];  ?></textarea>
            </div>
            <div class="col-sm-12">
                <input type="hidden" name="s_c_f_id"  value="<?php echo $query_ary[0]['s_c_f_id'];  ?>"/>
                <button type="submit" class="modal_save_btn" style="float:right;" id="save"> <?php echo get_phrase('save');?></button>
            </div>
        <!--</div>-->
    <?php echo form_close();  ?>
    <?php } ?>    
    
    <!--Issue Challan Form-->
    <?php if($query_ary[0]['status']==3){ ?>
    <!--<div class="row ">-->
        <div class="col-sm-12">
            <div id="login_Box_Div">
                <h3><b><?php echo get_phrase('issue_chalan_form');?></b></h3>
                <?php echo form_open(base_url().'class_chalan_form/save_print_chalan/' , array('id'=>'challlan_form_issue','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <label><b><?php echo get_phrase('issue_date');?></b></label>
                    <input type="hidden" name="s_c_f_id"  value="<?php echo $query_ary[0]['s_c_f_id'];  ?>"/>
                    <input type="hidden" name="student_id"  value="<?php echo $query_ary[0]['student_id'];  ?>"/>
                    <input type="text" class="form-control mb-4" readonly  value="<?php $date= date('Y-m-d H:i:s'); echo convert_date($date,1); ?>"/>
                    <input type="hidden" class="form-control " readonly name="issue_date" value="<?php echo $date= date('Y-m-d H:i:s'); ?>"/>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <label><b><?php echo get_phrase('due_date');?></b></label>
                    <input class="form-control datepicker"  name="due_date" value="<?php $days=$query_ary[0]['due_days']; echo $date= date('d/m/Y', strtotime('+'.$days.' days'));?>" data-format="dd/mm/yyyy"  required=""/>
                    <input type="hidden" class="form-control"  name="form_type" value="<?php echo $query_ary[0]['form_type'];?>" required=""/>
                </div>
                <div class="col-sm-12 mt-4">
                    <button class="modal_save_btn" type="submit" style="float:right;" ><?php echo get_phrase('issue');?></button>
                </div>
            </div>
        </div>
    <!--</div>-->
    <?php } ?>
    
    <!--Print & Cancel Button-->
    <div class="row">
        <div class="col-lg-12 mt-4">
            <?php
                if(($query_ary[0]['form_type']==10 && $query_ary[0]['status']<5)
                    || ($query_ary[0]['form_type']==1 && $query_ary[0]['status']<5)
                    || ($query_ary[0]['form_type']==3 && $query_ary[0]['status']<5 && $query_ary[0]['is_bulk']==0)
                    || ($query_ary[0]['form_type']==2 && $query_ary[0]['status']<5)
                    || ($query_ary[0]['form_type']==6 && $query_ary[0]['status']<5)
                   )
               {
            ?>
            <a id="cancel_id" href="<?php echo base_url();?>promotion/delete_chalan/<?php echo $query_ary[0]['s_c_f_id']."/0/".$query_ary[0]['form_type'];  ?>" class="modal_cancel_btn">
            <?php echo get_phrase('cancel_chalan');?> </a>
            <?php
            }else if( ($query_ary[0]['form_type']==5 && $query_ary[0]['status']<5)
                    || ($query_ary[0]['form_type']==7 && $query_ary[0]['status']<5))
            {
               $student_transfer_str = "SELECT * from " . get_school_db() . ".transfer_student WHERE s_c_f_id = ".$query_ary[0]['s_c_f_id']." AND student_id = ".$query_ary[0]['student_id']."";
               $student_transfer_query = $this->db->query($student_transfer_str)->row();
            ?>
                <a id="cancel_id" href="<?php echo base_url();?>transfer_student/cancel_transfer/<?php echo $student_transfer_query->student_id; ?>/
                    <?php echo $student_transfer_query->transfer_id; ?>" class="modal_cancel_btn">
                    <?php echo get_phrase('cancel_chalan');?> 
                </a>
            <?php }if($query_ary[0]['status']==4){ ?>
                &nbsp;&nbsp;
                <a href="<?php echo base_url();?>class_chalan_form/view_print_chalan/<?php echo $query_ary[0]['s_c_f_id']; ?>/<?php echo $query_ary[0]['form_type']; ?>" class="modal_save_btn">
                <?php echo get_phrase('print_chalan');?></a>
            <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php echo form_close();?>
<script>
$(document).ready(function(){
    $(".actual_amu").on('input',function(){
        var total_amu="<?php echo $number_total_amount; ?>";
        var current_rec1=$(this).val();
        // debugger;
        if(current_rec1==""){
            current_rec=0;
        }else{
            current_rec= parseInt(current_rec1);
        }
        if(current_rec>total_amu){
         	var arrears_o=(current_rec-total_amu);
         	$("#other_amount").val(arrears_o);
         	$("#arrears").val('0');
        }else if(current_rec<total_amu){	
         var arrears = (total_amu-current_rec);
        //   alert(arrears);
          $("#arrears").val(arrears);
          $("#other_amount").val('0');
        }else{
          $("#arrears").val('0');
          $("#other_amount").val('0');
        }
    });
});
</script>



<!-- 

<script>
    $(document).ready(function(){
        get_student_chalan();
    });
    function get_student_chalan(){
		$("#loading").remove();
		$(".s_chalan").each(function(){		
			$(this).remove();
		});
		$("#chalan").after("<div id='loading' class='loader'></div>")
    	var s_c_f_id = "<?php echo $query_ary[0]['s_c_f_id']; ?>";
    	var status   = "<?php echo $query_ary[0]['status']; ?>";
	    $.ajax({
			type: 'POST',
			data: {s_c_f_id:s_c_f_id,status:status},
			url: "<?php echo base_url();?>class_chalan_form/get_student_chalan",
			dataType: "html",
			success: function(response) { 
    			$("#loading").remove();
    			$("#chalan").after(response);
			}
		});
    }
</script>

-->


<script>
 	$(document).ready(function(){
       $('#cancel_id').click(function () {
           $('#cancel_id').attr('disabled','disabled');
       })
 	});
 </script>
 