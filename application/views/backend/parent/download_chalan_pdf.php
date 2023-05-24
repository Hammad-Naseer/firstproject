<!--BS CSS-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/optimization/bootstrap.min.css">
<style>
        /*.watermark {*/
        /*    position: absolute;*/
        /*    color: lightgray;*/
        /*    opacity: 0.25;*/
        /*    font-size: 3em;*/
        /*    font-size: 40px !important;*/
        /*    width: 100%;*/
        /*    top: 8%;*/
        /*    transform: rotate(305deg);*/
        /*    text-align: center;*/
        /*    z-index: 0;*/
        /*    top: 44%;*/
        /*    left: -26px;*/
        /*}*/
        /*.watermark span{*/
        /*    font-size: 70px !important;*/
        /*}*/
    </style>

    <div id="table_data" style="margin-top: 20px;">
        <?php
            if(count($query_ary)==0){
                $this->session->set_flashdata('club_updated','Chalan Form Not Available');
                redirect($_SERVER['HTTP_REFERER']);
                exit;
            }
            foreach($query_ary as $row_data)
            {
                
                $s_c_f_id=$row_data['s_c_f_id'];
                // $query_a=$this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=".$_SESSION['school_id'])->result_array();
                $query_a = $this->db->query("select scd.*,dl.discount_id from ".get_school_db().".student_chalan_detail scd
                            LEFT JOIN ".get_school_db().".discount_list dl ON dl.fee_type_id = scd.type_id
                            WHERE scd.s_c_f_id = $s_c_f_id and scd.type != 2 and scd.school_id =  ".$_SESSION['school_id']." ")->result_array();
                if($row_data['status']>=4)
                {
                    $copy = array( 1=>"School Copy",2=>"Student Copy",3=>"Bank Copy");
            ?>
            <table width="100%" border="0">
                <tr>
                    <?php
                        $query_logo=$this->db->query("select * from ".get_school_db().".chalan_settings where school_id=".$_SESSION['school_id'])->result_array();
                        for($i=1; $i<=3; $i++){
                    ?>
                            
                            <td style="padding-right:10px">
                            <table width="100%" border="1">
                                    <div class="watermark">
                                        <?php
                                            if($row_data['status']==5){
                                                echo "<span>Paid</span>";
                                            }else{
                                                echo "<span class='text-danger'>Unpaid</span>";
                                            }
                                        ?>
                                    </div>
                                    <tbody>
                                <tr>
                                    <td style="border-right:none !important; width: 60px;">
                                        <?php
                                            $d_school_id = $_SESSION['school_id'];
                                            $school_details = get_school_details($d_school_id);
                                            $branch_name =  $school_details['name'];
                                            $branch_logo =  $school_details['logo'];
                                            $branch_folder =  $school_details['folder_name'];
                                           if($d_school_id == $_SESSION['school_id'])
                                            {
                                                $logo=system_path($_SESSION['school_logo']);
                                                if($_SESSION['school_logo']=="" || !is_file($logo)){
                                        ?>
                                                <img style="height: 65px; width: 70px;" src="assets/images/gsims_logo.png">
                                                <?php }else{ ?>
                                                <img src="uploads/<?php echo $_SESSION['folder_name'].'/'.$_SESSION['school_logo']; ?>" style="height: 65px; width: 70px;">
                                        <?php } } ?>
                                    </td>
                                    <td style="border-left:none !important; padding-top:10px;">
                                        <h4 style="font-size:14px !important;">
                                            <?php echo $school_details['name'];?>
                                        </h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center">
                                        <p style="margin-top: 8px;"><?php echo $school_details['address'];?></p>
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
                                        <?php //echo $issue_date= $row_data['issue_date'];
                                        $date1=explode(' ',$row_data['issue_date']);
                                        echo convert_date($date1[0]);
                                        ?>
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <td><?php echo get_phrase('due_date');?>:</td>
                                    <td><?php //echo $due_date= $row_data['due_date'];
                                        $query_class_challan = $this->db->query("select due_days from ".get_school_db().".class_chalan_form where c_c_f_id = ".$row_data['c_c_f_id']." And school_id=".$_SESSION['school_id'])->result_array();
                                        $due_days = $query_class_challan[0]['due_days'];
                                        echo date_view($row_data['due_date']);
                                    ?>
                                    </td>
        
                                </tr>
                                <tr>
                                    <td width="100%">
        							<?php echo get_phrase('particulars');?></td>
                                    <td  style=" text-align:right;"><?php echo get_phrase('amount');?></td>
                                </tr>
                                <?php
                                    $count_num = 1;
                                    $s_c_f_id=$row_data['s_c_f_id'];
                                    $my_month = date('m',strtotime($row_data['due_date']));
    
                                    $chalan="";
                                    $discount="";
                                    $arrears="";
                                    $totle=0;
                                    $related_ids = array();
                                    $discount_calculation = 0;
                                    $single_discount_calculation = 0;
                                    foreach($query_a as $rec_row1)
                                    {
                                        if($rec_row1['type']==1 || $rec_row1['type']==5)
                                        {
                                            $related_ids[$rec_row1['s_c_d_id']]['amount']=$rec_row1['amount'];
                                            $related_ids[$rec_row1['s_c_d_id']]['title']=$rec_row1['fee_type_title'];
                                            echo '<tr><td>'.$rec_row1['fee_type_title'].'</td>
                                                  <td  style=" text-align:right;" >'.$rec_row1['amount'].'</td></tr>';
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
                                            echo '
                                                <td>'.$rec_row1['fee_type_title'].'</td>
                                                <td  style=" text-align:right;" >('.$rec_row1['amount'].')</td>';
                                            $totle=$totle-$rec_row1['amount'];
                                        }elseif($rec_row1['type']==3){
                                            // <td >'.$count_num.'</td>
                                            echo '
                                                <td>'.$rec_row1['fee_type_title'].'</td>
                                                <td  style=" text-align:right;" >'.$rec_row1['amount'].'</td>';
                                            $totle=$totle+$rec_row1['amount'];
                                        }
                                        $count_num++;
                                    }
                                    $discount_calculation_merge = $discount_calculation+$single_discount_calculation;
                                    $test_t = $totle - $discount_calculation_merge;
                                ?>
                                
                                <?php
                                    echo '
                                    <tr>
                                        <td><strong>Total Amount</strong></td>
                                        <td  style=" text-align:right;" ><strong> '.$test_t.'</strong></td> 
                                    </tr>' ;
                                        $this->load->helper("num_word");
                                    echo '
                                    <tr>
                                        <td style="border-bottom: none !important; text-transform:capitalize;font-size: 12px;" colspan="2"><strong>In Words: </strong>'.convert_number_to_words($test_t).' Rupees<br />
                                        <span class="term_condition">'.nl2br($query_logo[0]['terms']).'</span></td>
                                    </tr>';
                                ?>
                                <tr>
                                    <td colspan="2" style="border-top:none !important;   border-bottom:none !important; font-size: 12px; ">
                                        <?php  $admin_req1=get_user_info($row_data['issued_by']);  ?>
                                        <span><?php echo get_phrase('issued_by');?>: <?php echo $query_logo[0]['school_name'];?></span>
                                       <br><br><br>
                                       <br><br><br>
                                        <center>
                                            <img src="uploads/<?php echo $_SESSION['folder_name'] ?>/student/<?= $row_data['bar_code']; ?>">
                                        </center>
                                        <br><br>
                                        <p style="font-size: 8px !important; font-weight:bold; border-top:1px solid #000; margin-top:10px;"><?php echo get_phrase('note');?>: 
                                        <?php echo get_phrase('this_challan_form_is_computer_generated_and_does_not_require_any_signature');?>
                                       . </p>
                                       <br>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                      </td>

                    <?php
                    }
                    ?>
                   </tr>                   </table>
                    <?php
                }
                else{
                    $this->session->set_flashdata('club_updated','chalan is not approved');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
        ?>
    </div>
    
        <?php
        // print_r($row_data);
                // exit;
    ?>