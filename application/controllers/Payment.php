<?php
if (!defined('BASEPATH'))

exit('No direct script access allowed');

class Payment extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    public function test()
    {
        $data ='[
              {
                   "StatusCode": "00",
                   "InvoiceID" : "INV1",
                   "Description": "Invoice successfully marked as paid"
              },
              {
                   "StatusCode": "00",
                   "InvoiceID" : "1619155805",
                   "Description": "Invoice successfully marked as paid"
              }
        ]';

        $events =  json_decode($data, true);
        //echo "<pre>";
        //print_r($events);
        
        // 00 Success
        // 01 Invalid Data.
        // 02 User not Authorized.
        // 03 No Records Found
        
        foreach($events as $key=>$val){
            
            $invoice_num = $val['InvoiceID'];
            $invoice_row = $this->db->get_where($school_db.'.payment_consumer', array('InvoiceNumber' => $invoice_num))->row();
                
                if($invoice_row != null)
                {
                    
                    if($val['StatusCode'] == '00')
                    {
                       
                        $challans_ids  =   $invoice_row->challan_id;
                        $challans      =   explode("," , $challans_ids);
                        if(!empty($challans))
                        {
                            
                            for($i=0; $i<count($challans); $i++)
                            {
                                $data_challan = array(
                                   'status' => 5
                                );
                                $this->db->where('s_c_f_id', $challans[$i]);
                                $this->db->where('school_id', $invoice_row->school_id);
                                $this->db->update($school_db.'.student_chalan_form', $data_challan);
                            }
                            
                            
                            $data_consumer = array(
                                'IsPaid' => 1,
                                'Updated_at' => date('Y-m-d H:i:s')
                            );
                            
                            $this->db->where('InvoiceNumber' , $invoice_num);
                            $this->db->where('school_id', $invoice_row->school_id);
                            $this->db->update($school_db.'.payment_consumer', $data_consumer);
                            
                            $challans_amount    =   $invoice_row->InvoiceAmount;
                            $student_id         =   $invoice_row->consumer_id;
                            
                            $this->load->helper('message');
                            $sms_ary = get_sms_detail($student_id);
                            $message = "Amount of Rs. " . $challans_amount . " received from " . $sms_ary['student_name'] . ".";
                            send_sms($sms_ary['mob_num'], 'Indici Edu', $message, $student_id,1);
    
                            $to_email = $sms_ary['email'];
                            $subject  = "Fee Received";
                            $email_layout = get_email_layout($message);
                            email_send("No Reply", "Indici Edu", $to_email, $subject, $email_layout, $student_id,1);    
                            
                        } 
                    }
                    
                }   
            
        }
        
        
    }

    public function counter_payments_nift($user,$pass,$invoiceIds)
    {
        $response   =  array();
        $system_db  =  "indicied_indiciedu_gsimscom_gsims_system";
        $invoiceIds =  urldecode($invoiceIds);  
        
        if($user == "indiciedu" && $pass == "f3tech123vdsl")
        {
            $invoiceIdsArr = explode("," , $invoiceIds);
            if(!empty($invoiceIdsArr))
            {
                foreach($invoiceIdsArr as $invoice){
                    
                    $invoice = trim($invoice);
                    $system_invoice = $this->db->get_where($system_db.'.payment_consumer_system', array('InvoiceNumber' => $invoice))->row();
                    
                    if($system_invoice != null)
                    {
                        $school    = $this->db->query("select * from ".$system_db.".system_school where sys_sch_id = " .$system_invoice->sys_sch_id)->row();
                        $school_db = $school->school_db;
                        $school_id = $school->sys_sch_id;
                        $challan_id = $system_invoice->challan_id;
                        $receieved_amount_in_cash = $system_invoice->InvoiceAmount;
                        $student_id = $system_invoice->consumer_id;
                        
                        if($this->dbutil->database_exists($school_db))
			            {
			                $invoice_row = $this->db->get_where($school_db.'.payment_consumer', array('InvoiceNumber' => $invoice))->row();
			                if($invoice_row->IsPaid == 0)
                            {
                                // Financial Logic
    			                $entry_date = date("Y-m-d");
    
                                $fee_amount = 0;
                                $total_discount_amount = 0;
                    
                                $str_fee = "SELECT scf_fee.student_id,scf_fee.chalan_form_number, scf_fee.chalan_form_number, scfd_fee.*
                                            FROM ".$school_db.".student_chalan_detail as scfd_fee 
                                            INNER JOIN ".$school_db.".student_chalan_form as scf_fee ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id 
                                            WHERE scfd_fee.s_c_f_id = $challan_id AND scfd_fee.type = 1 AND scfd_fee.school_id = $school_id ";            
                                $query_fee = $this->db->query($str_fee)->result_array();
                                
                                $sum_total_amount = "SELECT SUM(scfd_fee.amount) AS total_amount FROM ".$school_db.".student_chalan_detail as scfd_fee 
                                                    INNER JOIN ".$school_db.".student_chalan_form as scf_fee ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id 
                                                    WHERE scfd_fee.s_c_f_id = $challan_id AND scfd_fee.type = 1 AND scfd_fee.school_id = $school_id ";
                                $query_sum_total_amount = $this->db->query($sum_total_amount)->row();
                                
                                $grand_total_amount = $query_sum_total_amount->total_amount;
                                
                                if(count($query_fee)>0)
                                {
                                    foreach ($query_fee as $key_fee => $value_fee)
                                    {
                                        /* create array for add fee only start */
                                        $fee_type_id_fee = $value_fee['type_id'];
                                        $fee_type_title = $value_fee['fee_type_title'];
                                        $fee_amount = $value_fee['amount'];
                                        $fee_school_id = $value_fee['school_id'];
                                        $fee_receive_dr_coa_id = $value_fee['receive_dr_coa_id'];
                                        $fee_receive_cr_coa_id = $value_fee['receive_cr_coa_id'];
                                        $fee_chalan_form_number = $value_fee['chalan_form_number'];
                                        
                                        $quer = "SELECT DISTINCT s.*, cs.title as section_name, cc.name as class_name, d.title as department_name
                                                FROM " .$school_db. ".student s 
                                                INNER JOIN " .$school_db. ".class_section cs on cs.section_id=s.section_id
                                                INNER JOIN " .$school_db. ".class cc on cc.class_id=cs.class_id 
                                                INNER JOIN " .$school_db. ".departments d on d.departments_id=cc.departments_id
                                                WHERE s.school_id=" .$school_id. "
                                                AND s.student_id = ".$value_fee['student_id']." ";
                                    
                                        $student_row = $this->db->query($quer)->row();
                                        
                                        $str = $student_row->name . " - " . $student_row->roll . " - " . $student_row->department_name . " - " . $student_row->class_name . " - " . $student_row->section_name . "";
                                        
                                        $transaction_detail = $str;
                                        
                                        $s_c_d_id = $value_fee['s_c_d_id'];
                                        $discount_amt = $this->is_discount_fee($s_c_d_id, $challan_id,$school_id,$school_db);
                                        
                    
                                        $fee_amount_temp = $fee_amount;
                                        if($discount_amt>0)
                                        {
                                            $discount_amt = round((($discount_amt * $fee_amount) / 100));
                                            $fee_amount_temp = $fee_amount_temp-$discount_amt;
                                        }
                                        
                                        // Discount Journal Entry
                                        $str_discount = "SELECT scf_discount.student_id,
                                                        scf_discount.chalan_form_number,
                                                        scfd_discount.amount,
                                                        scfd_discount.fee_type_title, 
                                                        scfd_discount.type_id as scfd_fee_id,
                                                        scfd_discount.issue_cr_coa_id,
                                                        scfd_discount.receive_dr_coa_id,
                                                        scfd_discount.issue_dr_coa_id,
                                                        scfd_discount.school_id,
                                                        d.discount_id,
                                                        f.fee_type_id as fee_id, 
                                                        d.title
                                                        FROM " . $school_db . ".discount_list as d
                                                        INNER join  " . $school_db . ".fee_types as f ON f.fee_type_id = d.fee_type_id
                                                        INNER JOIN " . $school_db . ".student_chalan_detail as scfd_discount ON scfd_discount.type_id = d.discount_id
                                                        INNER JOIN " . $school_db . ".student_chalan_form as scf_discount ON scf_discount.s_c_f_id = scfd_discount.s_c_f_id
                                                        WHERE f.fee_type_id = $fee_type_id_fee
                                                        AND scfd_discount.s_c_f_id = $challan_id
                                                        AND scfd_discount.school_id = " . $school_id . "
                                                        AND scfd_discount.type = 2";
                                        $query_discount = $this->db->query($str_discount)->result_array();
                    
                                        // Multiple Fee Entry If Discount Value is 0
                                        if(count($query_discount) == 0)
                                        {
                                           if($fee_amount>0)
                                           {
                                               // Credit Journal Entry Challan Recieved (Zeeshan)
                                               $array_ledger_fee = array(
                                                   'entry_date' => $entry_date,
                                                   'detail' => $fee_type_title
                                                       . ' - Received Challan Form - ' .$fee_chalan_form_number. ' - From - ' . $transaction_detail,
                                                   'credit' => $fee_amount,
                                                   'entry_type' => 3,
                                                   'type_id' => $challan_id,
                                                   'student_id' => $value_fee['student_id'],
                                                   'school_id' => $school_id,
                                                   'coa_id' => $fee_receive_cr_coa_id
                                               );
                                               //$this->db->insert($school_db . ".journal_entry", $array_ledger_fee);
                                           }
                                       }
                                        
                                        // Discount Entry  if There is Some Discount  
                                        if (count($query_discount) > 0)
                                        {
                                            foreach ($query_discount as $key_discount => $value_discount)
                                            {
                                                $discount_type_id_fee = $value_discount['type_id'];
                                                $discount_type_title = $value_discount['fee_type_title'];
                                                $discount_amount = round((($fee_amount * $value_discount['amount']) / 100));
                                                $total_discount_amount = $total_discount_amount + $discount_amount;
                                                $discount_school_id = $value_discount['school_id'];
                                                $discount_receive_dr_coa_id = $value_discount['issue_dr_coa_id'];
                                                $discount_chalan_form_number = $value_discount['chalan_form_number'];
                                                $transaction_detail = $str;
                                                $percentage_amount = $value_discount['amount'];
                    
                                                // $array_ledger_discount = array(
                                                //     'entry_date'    => $entry_date,
                                                //     'detail'        => $discount_type_title. ' ('.$percentage_amount.' %)'
                                                //         .' - ' . get_phrase('discount_chalan_form') .  ' - '. $discount_chalan_form_number ." - " . get_phrase('to') . " - " . $transaction_detail,
                                                //     'debit'         => $discount_amount,
                                                //     'entry_type'    => 3,
                                                //     'type_id'       => $s_c_f_id,
                                                //     'school_id'     => $discount_school_id,
                                                //     'coa_id'        => $discount_receive_dr_coa_id
                                                // );
                    
                                                // $this->db->insert($school_db.".journal_entry", $array_ledger_discount);
                                                
                                                // Credit Journal Entry Challan Recieved (Zeeshan)
                                                    $array_ledger_fee = array(
                                                       'entry_date' => $entry_date,
                                                       'detail' => $fee_type_title
                                                           . ' - Receivd Challan Form - ' . $fee_chalan_form_number . ' - From - ' . $transaction_detail,
                                                       'credit' => $fee_amount - $discount_amount,
                                                       'entry_type' => 3,
                                                       'type_id' => $challan_id,
                                                       'student_id' => $value_fee['student_id'],
                                                       'school_id' => $school_id,
                                                       'coa_id' => $fee_receive_cr_coa_id
                                                    );
                                                //   $this->db->insert($school_db . ".journal_entry", $array_ledger_fee);   
                                            }
                    
                                        }
    
                                        $remanining_amount = $grand_total_amount-$total_discount_amount;
    
                                    }//end main loop
                                    
                                    // Credit Journal Entry Challan Recieved (Zeeshan)
                                    $array_ledger_fee_debit = array(
                                        'entry_date' => $entry_date,
                                        'detail' => "Total Fee "
                                            . ' - Receive Challan Form - ' . $fee_chalan_form_number . ' - From - ' . $transaction_detail,
                                        'credit' => $receieved_amount_in_cash,
                                        'entry_type' => 3,
                                        'type_id' => $challan_id,
                                        'student_id' => $student_id,
                                        'school_id' => $school_id,
                                        'coa_id' => $fee_receive_cr_coa_id
                                    );
                                    $this->db->insert($school_db.".journal_entry", $array_ledger_fee_debit);
                                    
                                    // End Total Credit JE
                                    
                                    // Debit Journal Entry Challan Recieved (Zeeshan)
                                    $array_ledger_fee_debit = array(
                                        'entry_date' => $entry_date,
                                        'detail' => "Total Fee "
                                            . ' - Receive Challan Form - ' . $fee_chalan_form_number . ' - From - ' . $transaction_detail,
                                        'debit' => $receieved_amount_in_cash,
                                        'entry_type' => 3,
                                        'type_id' => $challan_id,
                                        'student_id' => $student_id,
                                        'school_id' => $school_id,
                                        'coa_id' => $fee_receive_dr_coa_id
                                    );
                                    $this->db->insert($school_db.".journal_entry", $array_ledger_fee_debit);
                                    // End Total Debit JE
                                }
                    
                                
                                $data_challan = array(
                                   'status' => 5
                                );
                                $this->db->where('s_c_f_id', $challan_id);
                                $this->db->where('school_id', $school_id);
                                $this->db->update($school_db.'.student_chalan_form', $data_challan);
                                
                                $data_consumer = array( 'IsPaid' => 1 , 'Updated_at' => date('Y-m-d H:i:s') );
                                
                                $successfull_array = array( 'StatusCode' => "00" , 'InvoiceID' => $invoice , 'Description' => "Invoice successfully marked as paid" );
                                $response[]        = $successfull_array;
                                
                                $this->db->where('InvoiceNumber' , $invoice);
                                $this->db->where('school_id', $invoice_row->school_id);
                                $this->db->update($school_db.'.payment_consumer', $data_consumer);
                                
                                
			                }else if($invoice_row->IsPaid == 1)
                            {
                               $successfull_array =  array( 'StatusCode' => "00" , 'InvoiceID' => $invoice , 'Description' => "Invoice successfully marked as paid" );
                               $response[]        =  $successfull_array; 
                            }  
			            }       
                    }
                    else
                    {
                        $successfull_array = array( 'StatusCode' => "03" , 'InvoiceID' => $invoice , 'Description' => "Invoice record not found" );
                        $response[]        = $successfull_array; 
                    }
                }
                
                echo json_encode($response);
            }
            
        }
        
        
    }
    
    function is_discount_fee($s_c_d_id, $s_c_f_id,$school_id,$school_db)
    {
        $str_discount = "SELECT scfd_discount.amount FROM ".$school_db.".student_chalan_detail as scfd_discount WHERE 
                        scfd_discount.s_c_f_id =$s_c_f_id AND scfd_discount.related_s_c_d_id = $s_c_d_id AND scfd_discount.school_id = $school_id
                        AND scfd_discount.type = 2";
        $query_discount = $this->db->query($str_discount)->result_array();
        if (count($query_discount) > 0) {
            $discount_amount = $query_discount[0]['amount'];
            return $discount_amount;
        } else {
            return 0;
        }
    }
}
