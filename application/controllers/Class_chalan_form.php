<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();


class Class_chalan_form extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    
        if($_SESSION['accountant_login'] == 1 || $_SESSION['user_login']== 1)
        {
            $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            $this->output->set_header('Pragma: no-cache');
            $this->menu_ary=array();
              
        }else
        {
            redirect('login');
        }
    }
    
    function class_chalan_f($action="", $id="",$class_id="")
    {
        $school_id=$_SESSION['school_id'];
        if ($action=="add")
        {
            $parent_c_c_f_id = $this->input->post('parent_c_c_f_id');
            if (empty($parent_c_c_f_id))
            {
                $this->db->trans_begin();
                
                $data['title']=$this->input->post('title');
                $data['section_id']=$this->input->post('section_id');
                $data['due_days']=$this->input->post('due_days');
                $data['late_fee_fine']=$this->input->post('late_fee_fine');
                $data['late_fee_type']=$this->input->post('late_fee_type');
                $data['detail']=$this->input->post('detail');
                $data['type']=$this->input->post('type');
                $data['status']=1;
                $data['school_id'] = $school_id;
                $data['parent_c_c_f_id'] = 0;
                $data['previous_c_c_f_id'] = 0;
                $data['version']=$this->input->post('version');
                
                $this->db->insert(get_school_db().'.class_chalan_form',$data);
                $c_c_f_id = $this->db->insert_id();
                $data['c_c_f_id'] = $c_c_f_id;
                $this->data_send_to_api($data);

                $update_data['parent_c_c_f_id']= $c_c_f_id;
                $this->db->where('c_c_f_id',$c_c_f_id);
                $this->db->where('school_id',$school_id);
                $this->db->update(get_school_db().'.class_chalan_form',$update_data);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    
                } else {
                    $this->db->trans_commit();
                }
                
                redirect(base_url() . 'class_chalan_form/class_chalan_f/');
            }else{
                $this->db->trans_begin();
                
                $previous_c_c_f_id = $this->input->post('previous_c_c_f_id');
                $update_data['status']= 0;
                $update_data['archive_date']= date('Y-m-d');
                $this->db->where('c_c_f_id',$previous_c_c_f_id);
                $this->db->where('school_id',$school_id);
                $this->db->update(get_school_db().'.class_chalan_form',$update_data);

                $data['title']=$this->input->post('title');
                $data['section_id']=$this->input->post('section_id');
                $data['due_days']=$this->input->post('due_days');
                $data['late_fee_fine']=$this->input->post('late_fee_fine');
                $data['late_fee_type']=$this->input->post('late_fee_type');
                $data['detail']=$this->input->post('detail');
                $data['type']=$this->input->post('type');
                $data['status']=1;
                $data['school_id'] = $school_id;
                $data['parent_c_c_f_id'] = $this->input->post('parent_c_c_f_id');
                $data['previous_c_c_f_id'] = $this->input->post('previous_c_c_f_id');
                $data['version']=$this->input->post('version');
                
                $this->db->insert(get_school_db().'.class_chalan_form',$data);
                $c_c_f_id = $this->db->insert_id();
                $data['c_c_f_id'] = $c_c_f_id;
                $this->data_send_to_api($data);

                $fee_qur = "INSERT INTO ".get_school_db().".`class_chalan_fee` (`c_c_f_id`, `fee_type_id`, `order_num`, `school_id`, `value`) SELECT ".$c_c_f_id.", `fee_type_id`, `order_num`, `school_id`, `value` FROM ".get_school_db().".`class_chalan_fee` WHERE `c_c_f_id` = ".$previous_c_c_f_id." and school_id=".$_SESSION['school_id']." ";
                $this->db->query($fee_qur);

                $discount_qur = "INSERT INTO ".get_school_db().".`class_chalan_discount`(`c_c_f_id`, `discount_id`, `order_num`, `school_id`, `value`) SELECT ".$c_c_f_id.", `discount_id`, `order_num`, `school_id`, `value` FROM ".get_school_db().".`class_chalan_discount` WHERE `c_c_f_id` = ".$previous_c_c_f_id." and school_id=".$_SESSION['school_id']." ";
                $this->db->query($discount_qur);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    
                } else {
                    $this->db->trans_commit();
                }
                redirect(base_url() . 'class_chalan_form/class_chalan_f/');

            }
        }
        if($action=="add_edit")
        {
            $this->db->trans_begin();

            $data['title']=$this->input->post('title');
            $c_c_f_id=$this->input->post('c_c_f_id');
            $data['section_id']=$this->input->post('section_id');
            $data['due_days']=$this->input->post('due_days');
            $data['detail']=$this->input->post('detail');
            $data['late_fee_fine']=$this->input->post('late_fee_fine');
            $data['late_fee_type']=$this->input->post('late_fee_type');
            $data['version']=$this->input->post('version');
            $school_id=$_SESSION['school_id'];

            if($data['status']==1)
            {
                $qq="select status from  ".get_school_db().".class_chalan_form where section_id=".$data['section_id']." and type= ".$data['type']." and school_id=$school_id and status=".$data['status'];
                $num_rows=$this->db->query($qq)->num_rows();
            }
            if($c_c_f_id!="")
            {
                if($num_rows>0)
                {
                    $data['status']=0;
                    $this->db->where('c_c_f_id',$c_c_f_id);
                    $this->db->where('school_id',$school_id);
                    $this->db->update(get_school_db().'.class_chalan_form',$data);
                    $this->session->set_flashdata('club_updated',get_phrase('status_not_updated_._active_challan_already exists'));
                    
                    $data['c_c_f_id'] = $c_c_f_id;
                    $data['school_id'] = $school_id;
                    $this->data_send_to_api($data);
                }
                else
                {
                    $this->db->where('c_c_f_id',$c_c_f_id);
                    $this->db->where('school_id',$school_id);
                    $this->db->update(get_school_db().'.class_chalan_form',$data);
                    
                    $data['c_c_f_id'] = $c_c_f_id;
                    $data['school_id'] = $school_id;
                    $this->data_send_to_api($data);
                }
            }
            else
            {
                $data['school_id']=$school_id;
                if($num_rows>0)
                {
                    $data['status']=0;
                    $this->session->set_flashdata('club_updated',get_phrase('record_saved_as_inactive_,_active_challan_already_exists'));
                    $this->db->insert(get_school_db().'.class_chalan_form',$data);
                    
                    $data['c_c_f_id'] = $c_c_f_id;
                    $data['school_id'] = $school_id;
                    $this->data_send_to_api($data);
                }
                else
                {
                    $this->db->insert(get_school_db().'.class_chalan_form',$data);
                    $this->session->set_flashdata('club_updated',get_phrase('record_saved_successfully'));
                    
                    $data['c_c_f_id'] = $c_c_f_id;
                    $data['school_id'] = $school_id;
                    $this->data_send_to_api($data);
                }
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            redirect(base_url() . 'class_chalan_form/class_chalan_f/');
        }

       /* if($action=='delete')
        {
            $c_c_f_id=$this->input->post('c_c_f_id');
            $this->db->where('c_c_f_id', $c_c_f_id);
            $this->db->where('school_id',$_SESSION['school_id']);
            $query=$this->db->delete(get_school_db().'.class_chalan_form');
            $query=$this->db->affected_rows();
            if($query > 0)
            {
                $res=true;
                $this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));
            }
            else
            {
                $res=false;
            }
            return;
                //redirect(base_url().'class_chalan_form/class_chalan_f/'.$class_id);
         }*/


          //  $data1['c_c_f_id']=$this->input->post('c_c_f_id');
          
            $this->db->trans_begin();
            //---------------------Transection starts-------------------------------
            //----------------------------------------------------------------------

            $school_id=$_SESSION['school_id'];
            $c_c_f_id=$this->input->post('c_c_f_id');
            $fee_type_id=$this->input->post('fee_type_id');
            $fee_value=$this->input->post('fee_value');

            foreach($fee_type_id as $key => $value)
            {
                $data['fee_type_id']=$value;
                $data['c_c_f_id']=$c_c_f_id;
                $var=$fee_value[$data['fee_type_id']];
                $data['value']=$var;
                $data['school_id']=$school_id;

                if($var>0 && !empty($var))
                {

                   $fee_cheack_str = "select * from ".get_school_db().".class_chalan_fee WHERE c_c_f_id=$c_c_f_id 
                                       AND fee_type_id =  ".$data[fee_type_id]."  AND school_id=$school_id";
                   $fee_check_query=$this->db->query($fee_cheack_str)->result_array();

                    if(count($fee_check_query)>0)
                    {

                        $this->db->where('c_c_f_id',  $data['c_c_f_id']);
                        $this->db->where('fee_type_id', $data['fee_type_id']);
                        $this->db->where('school_id',$_SESSION['school_id']);
                        $this->db->update(get_school_db() . '.class_chalan_fee', $data);
                    }
                    else
                    {
                        $this->db->insert(get_school_db() . '.class_chalan_fee', $data);
                    }
                }
                else
                {

                    $delete_cf_str = "SELECT cfd.c_c_f_id , dl.fee_type_id FROM ".get_school_db().".class_chalan_discount as cfd
                                      INNER JOIN ".get_school_db().".discount_list as dl on cfd.discount_id = dl.discount_id
                                      WHERE cfd.c_c_f_id = ".$data['c_c_f_id']." and cfd.school_id = $school_id and dl.fee_type_id  = ".$data['fee_type_id']."";
                    $delete_cf_query=$this->db->query($delete_cf_str)->row();

                    if(count($delete_cf_query)>0)
                    {
                    }
                    else
                    {

                        $this->db->where('c_c_f_id',  $c_c_f_id);
                        $this->db->where('fee_type_id', $data['fee_type_id']);
                        $this->db->where('school_id',$school_id);
                        $query=$this->db->delete(get_school_db().'.class_chalan_fee');

                    }

                }

            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }

        $page_data['page_name']  = 'class_chalan_form';
        $page_data['page_title'] = get_phrase('Manage_challan_form');
        $this->load->view('backend/index', $page_data);
    }
    
    function data_send_to_api($form_data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => base_url().'api/late_fee_fine',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $form_data,
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response);
        // print_r($result);
        $output = '';
        // if($result->code == '200')
        // {
        //     echo 'Run';
        // }
    }

    function class_chalan_discount_save( $id="",$class_id="")
    {
        
        $school_id=$_SESSION['school_id'];
        $discount_id =$this->input->post('disocunt_amount');
        $c_c_f_id = $this->input->post('c_c_f_id');
        
        $this->db->trans_begin();

            foreach($discount_id as $key => $value)
            {
                $data['value']=$value;
                $data['discount_id']=$key;
                $data['c_c_f_id']=$c_c_f_id;
                $data['school_id']=$school_id;

                if($data['value']>0)
                {

                  $discount_amount_str = "select * from ".get_school_db().".class_chalan_discount WHERE c_c_f_id=".$data['c_c_f_id']." 
                                          AND discount_id =  ".$data['discount_id']."  AND school_id=$school_id";
                  $class_chalan_discount=$this->db->query($discount_amount_str)->row();

                  if(count($class_chalan_discount)>0)
                   {
                       $this->db->where('c_c_f_id',  $data['c_c_f_id']);
                       $this->db->where('discount_id', $key);
                       $this->db->where('school_id',$school_id);
                       $this->db->update(get_school_db() . '.class_chalan_discount', $data);
                   }
                   else
                   {
                       echo "add";
                       $this->db->insert(get_school_db() . '.class_chalan_discount', $data);
                   }
                 }

                else
                {
                    $this->db->where('c_c_f_id',  $data['c_c_f_id']);
                    $this->db->where('discount_id', $key);
                    $this->db->where('school_id',$school_id);
                    $query=$this->db->delete(get_school_db().'.class_chalan_discount');

                }
             }
             
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }

            $page_data['page_name']  = 'class_chalan_form';
            $page_data['page_title'] = get_phrase('manage_challan_form');
            $this->load->view('backend/index', $page_data);

    }

    function get_chalan_ajax()
    {
        $data['section_id']=$this->input->post('section_id');
        $data['class_id']=$this->input->post('class_id');
        $data['departments_id']=$this->input->post('departments_id');
        $this->load->view("backend/admin/ajax/chalan_form_ajax",$data);
   }

    function chalan_save(){
        $title=$this->input->post('title');
    }

    function chalan_view($section="",$c_c_f_id=""){

        $page_data['section_id']=$section_id;
        $page_data['c_c_f_id']=$c_c_f_id;
        $page_data['page_name']  = 'chalan_form_view';
        $page_data['page_title'] = get_phrase('chalan_form');
        $this->load->view('backend/index', $page_data);

    }

    function student_chalan_view($student_id="",$student_status=""){

        $page_data['student_id']=$student_id;
        $page_data['form_type']=$student_status;
        $page_data['page_name']  = '../accountant/student_chalan_view';
        $page_data['page_title'] = get_phrase('chalan_form');
        $this->load->view('backend/index', $page_data);

    }

    function student_chalan_form($stud_id,$form_type,$return_link="",$transfer_id="")
    {
        $student_id = str_decode($stud_id);
        
       
        if($student_id=="" || $form_type=="")
        {
            $this->session->set_flashdata('club_updated',get_phrase('chalan_form_not_created_yet'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        else
        {
            $school_id=$_SESSION['school_id'];
            $qur_check=$this->db->query("select s_c_f_id from ".get_school_db().".student_chalan_form 
                                            where school_id=$school_id 
                                            and student_id=$student_id and is_cancelled = 0 
                                            and form_type=$form_type and status<5 and is_processed=0")->result_array();
            if(count($qur_check)>0)
            {
              redirect(base_url() . "class_chalan_form/edit_chalan_form/".$qur_check[0]['s_c_f_id'].'/'.$return_link);
            }
            else
            {

                $var_chalan=$this->insert_chalan($student_id,$form_type);
                if($var_chalan==0)
                {
                    $this->session->set_flashdata('club_updated',get_phrase('chalan_form_not_found'));
                    if($form_type==5)
                    {
                        $this->session->set_flashdata('club_updated',get_phrase('chalan_form_not_found'));
                        redirect(base_url() . "transfer_student/transfer_information/");
                        exit();
                    }
                    redirect($_SERVER['HTTP_REFERER']);
                }
                else
                {
                    if($form_type==5)
                    {
                        $trans_data['s_c_f_id']=$var_chalan;
                        $this->db->where("transfer_id",$transfer_id);
                        $this->db->where('from_branch',$_SESSION['school_id']);
                        $this->db->update(get_school_db().".transfer_student",$trans_data);
                    }
                    if($form_type==7)
                    {
                        $trans_data['r_s_c_f_id']=$var_chalan;
                        $trans_data['status']=9;
                        
                        $this->db->where("transfer_id",$transfer_id);
                        $this->db->update(get_school_db().".transfer_student",$trans_data);
                    }

                    redirect(base_url() . "class_chalan_form/edit_chalan_form/$var_chalan/$return_link");
                }
            }
        }
    }

    function promote_class(){

        $pro_section_id=$this->input->post('section_id');
        $pro_academic_year_id=$this->input->post('academic_year_id');
        $old_section_id=$this->input->post('old_section_id');
        $old_academic_year_id=$this->input->post('old_academic_year_id');
        $school_id=$_SESSION['school_id'];
        $data_r['section_id']=$old_section_id;
        $data_r['academic_year_id']=$old_academic_year_id;
        $data_r['pro_section_id']= $pro_section_id;
        $data_r['pro_academic_year_id']=$pro_academic_year_id;
        $data_r['school_id']=$school_id;
        $data_r['user_id']=$_SESSION['login_detail_id'];
        $data_r['activity']=$this->input->post('promotion_status');
        $data_r['status']=1;
        $query_r333=$this->db->query("  SELECT ccf.c_c_f_id ,ccf.due_days FROM  ".get_school_db().".fee_types
                                        ft inner join ".get_school_db().".class_chalan_fee ccfe on ccfe.fee_type_id=ft.fee_type_id inner join ".get_school_db().".class_chalan_form ccf on
                                        ccf.c_c_f_id= ccfe.c_c_f_id where ccf.section_id=$pro_section_id and ccf.type=3 and ccf.status=1 and ccf.school_id=$school_id   
                                        ORDER BY ccfe.order_num ")->result_array();
        $data_r['c_c_f_id']=$query_r333[0]['c_c_f_id'];
        $due_days=$query_r333[0]['due_days'];
        $query_check =$this->db->query("select * from ".get_school_db().".bulk_request where section_id=$pro_section_id and 	academic_year_id=$pro_academic_year_id and pro_section_id=$old_section_id and pro_academic_year_id=$old_academic_year_id and status=1  and school_id=$school_id")->result_array();

        if(count($query_check)==0){
            $this->db->insert("bulk_request",$data_r);
            $bulk_req_id=$this->db->insert_id();
            $this->db->query("update ".get_school_db().".student set pro_section_id=$pro_section_id , pro_academic_year_id=$pro_academic_year_id, bulk_req_id=$bulk_req_id , student_status=11 where section_id=$old_section_id and academic_year_id=$old_academic_year_id and Student_status in (10,14,18) and school_id=$school_id ");
            $this->load->helper("student");
            $query =$this->db->query("select * from ".get_school_db().".student where pro_section_id=$pro_section_id and pro_academic_year_id=$pro_academic_year_id and student_status=11 and school_id=$school_id and bulk_req_id=$bulk_req_id")->result_array();

            foreach($query as $row)
            {
                student_archive($_SESSION['login_detail_id'],$row['student_id']);
                $this->insert_chalan($row['student_id'],3,$bulk_req_id);
                $this->bulk_condition($bulk_req_id,$data_r['activity'],$due_days);
            }
        }

        else{
            $bulk_req_id =	$query_check[0]['bulk_req_id'];
            $this->db->where("bulk_req_id",$bulk_req_id);
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->update(get_school_db().".bulk_request",$data_r);
            $this->bulk_condition($bulk_req_id,$data_r['activity'],$due_days);
        }

    }

    function bulk_condition($bulk_req_id="",$activity="",$due_days=""){

        $school_id=$_SESSION['school_id'];
        if($activity==1)
        {
            $s_c_f_data['status']=$activity;
            $s_c_f_data['is_bulk']=1;
            $s_c_f_data['generated_by']=$_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date']=$date->format('Y-m-d H:i:s');
            $this->db->where("bulk_req_id",$bulk_req_id);
            $this->db->where("school_id",$school_id);
            $this->db->update(get_school_db().".student_chalan_form",$s_c_f_data);
        }
        elseif($activity==2)
        {

            $s_c_f_data_s['status']=$activity;
            $s_c_f_data['is_bulk']=1;
            $s_c_f_data['generated_by']=$_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date']=$date->format('Y-m-d H:i:s');
            $this->db->where(array("bulk_req_id"=>$bulk_req_id,'school_id'=>$school_id));
            $this->db->update(get_school_db().".student_chalan_form",$s_c_f_data_s);
            $this->db->where(array("bulk_req_id"=>$bulk_req_id,'generated_by'=>0,'school_id'=>$school_id));
            $this->db->update(get_school_db().".student_chalan_form",$s_c_f_data);

        }
        elseif($activity==3)
        {

            $s_c_f_data_s['status']=$activity;
            $s_c_f_data['is_bulk']=1;
            $s_c_f_data['generated_by']=$_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date']=$date->format('Y-m-d H:i:s');
            $this->db->where(array("bulk_req_id"=>$bulk_req_id,'school_id'=>$school_id));
            $this->db->update(get_school_db().".student_chalan_form",$s_c_f_data_s);
            //echo $this->db->last_query();
            $this->db->where(array("bulk_req_id"=>$bulk_req_id,'generated_by'=>0,'school_id'=>$school_id));
            $this->db->update(get_school_db().".student_chalan_form",$s_c_f_data);
            $date = new DateTime();
            $data_a['approval_date']= $date->format('Y-m-d H:i:s');
            $data_a['approved_by']=$_SESSION['login_detail_id'];
            $this->db->where(array("bulk_req_id"=>$bulk_req_id,'approved_by'=>0,'school_id'=>$school_id));
            $this->db->update(get_school_db().".student_chalan_form",$data_a);

        }
        elseif($activity==4)
        {

            $s_c_f_data_s['status']=$activity;
            $s_c_f_data['is_bulk']=1;
            $s_c_f_data['generated_by']=$_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date']=$date->format('Y-m-d H:i:s');
            $this->db->where(array("bulk_req_id"=>$bulk_req_id,'school_id'=>$school_id));
            $this->db->update(get_school_db().".student_chalan_form",$s_c_f_data_s);
            $this->db->where(array("bulk_req_id"=>$bulk_req_id,'generated_by'=>0,'school_id'=>$school_id));
            $this->db->update(get_school_db().".student_chalan_form",$s_c_f_data);
            $date = new DateTime();
            $data_a['approval_date']=date('m/d/Y', strtotime('+'.$due_days.' days'));;
            $data_a['approved_by']=$_SESSION['login_detail_id'];
            $this->db->where(array("bulk_req_id"=>$bulk_req_id,'approved_by'=>0,'school_id'=>$school_id));
            $this->db->update(get_school_db().".student_chalan_form",$data_a);
            $issue_date=$date->format('Y-m-d H:i:s');
            $issued_by=$_SESSION['login_detail_id'];
            $this->db->query("UPDATE  ".get_school_db().".student_chalan_form SET  `issue_date` =$issue_date, `issued_by` =$issued_by,
                              due_date` = DATE_ADD( DATE_FORMAT( NOW( ) ,  %Y-%m-%d ) , INTERVAL  `due_days` DAY ) WHERE  `bulk_req_id` =  $bulk_req_id AND  `issued_by` =0 AND  `school_id` =  $school_id");
        }
    }

    function insert_chalan($student_id,$form_type,$bulk_req_id=0,$date="")
    {
        $school_id=$_SESSION['school_id'];

        $query_re_str = " SELECT s.name as student_name,s.barcode_image,s.gender,s.roll,s.section_id,s.pro_section_id,
                          s.academic_year_id,s.pro_academic_year_id,s.section_id,s.reg_num,s.mob_num,s.image,
                          s.system_id,s.academic_year_id,s.location_id,s.student_status,s.id_no,s.email,
                          cs.title as section_nme,cc.name as class_name,dd.title as department_name
                          FROM ".get_school_db().".student s 
                          inner join ".get_school_db().".class_section cs on s.section_id=cs.section_id  
                          inner join ".get_school_db().".class cc on cc.class_id=cs.class_id
                          inner join ".get_school_db().".departments dd on dd.departments_id=cc.departments_id
                          where s.student_id= ".$student_id." AND s.school_id = "."$school_id.";
        $query_res =$this->db->query($query_re_str)->result_array();

        $query_res_p_name_str = "SELECT  sp.p_name as parent_name FROM ".get_school_db().".student s 
                                 inner join ".get_school_db().".student_relation sr on sr.student_id=s.student_id 
                                 inner join ".get_school_db().".student_parent sp on sp.s_p_id=sr.s_p_id
                                 where s.student_id = ".$student_id." AND s.school_id=$school_id and sr.relation='f'";
        $query_res_p_name =$this->db->query($query_res_p_name_str)->result_array(); //school id
        $section_id=$query_res[0]['section_id'];

        if($form_type==3)
        {
            $section_id=$query_res[0]['pro_section_id'];
        }
        ///////////
        if($form_type==10)
        {
            $qur_val_cc_str =" SELECT $student_id as sid,ccf.c_c_f_id,ccf.due_days FROM ".get_school_db().".class_chalan_form ccf 
                               where ccf.section_id=$section_id and ccf.type=$form_type and ccf.status=1 and ccf.school_id=$school_id  ";
        }
        else
        {
            //working
           $qur_val_cc_str="SELECT $student_id as sid, ft.fee_type_id, ft.title, ccfe.order_num,ccfe.value,ccf.c_c_f_id,ccf.due_days,
                            ft.generate_dr_coa_id,ft.generate_cr_coa_id,ft.issue_dr_coa_id,ft.issue_cr_coa_id,
                            ft.receive_dr_coa_id,ft.receive_cr_coa_id,ft.cancel_dr_coa_id,ft.cancel_cr_coa_id
                            FROM  ".get_school_db().".fee_types ft 
                            inner join ".get_school_db().".class_chalan_fee ccfe on ccfe.fee_type_id=ft.fee_type_id 
                            inner join ".get_school_db().".class_chalan_form ccf on ccf.c_c_f_id= ccfe.c_c_f_id 
                            where ccf.section_id=$section_id and ccf.type=$form_type and ccf.status=1 and ccf.school_id=$school_id ORDER BY ccfe.order_num";
        }

        $query_re=$this->db->query($qur_val_cc_str)->result_array();

        if($form_type==10 && count($query_re)==0)
        {
            $datacc['title']="Custom Chalan";
            $datacc['detail']="Custom Chalan";
            $datacc['status']=1;
            $datacc['type']=10;
            $datacc['school_id']=$_SESSION['school_id'];
            $datacc['section_id	']=$section_id;
            $datacc['due_days']=10;
            $this->db->insert(get_school_db().'.class_chalan_form',$datacc);
        }

        if(count($query_re)==0)
        {
            return 0;
        }
        else
        {
            $chalan_setting=$this->db->query("select * from ".get_school_db().".chalan_settings where school_id=$school_id")->result_array();

            $due_days=$query_re[0]['due_days'];
            $data=array();
            $data['c_c_f_id']=$query_re[0]['c_c_f_id'];
            $data['school_id']=$school_id;
            $data['status']=1;

            if($date=="")
            {
                $date_val=date("Y-m-d");
            }else
            {
                $date_val=$date;
            }
            
            $reg_no = $query_res[0]['reg_num'];
            if(empty($reg_no) || $reg_no == "")
            {
                $reg_no = 0;
            }
            
            $img = $query_res[0]['image'];
            if(empty($img) || $img == "")
            {
                $img = " ";
            }
            
            $location_id = $query_res[0]['location_id'];
            if(empty($location_id) || $location_id == "")
            {
                $location_id = " ";
            }
            
            
            // exit('Save');
            $data['fee_month_year']=$date_val;
            $data['generated_by']=$_SESSION['login_detail_id'];
            $date = new DateTime();
            $data['generation_date']=$date->format('Y-m-d H:i:s');
            $data['chalan_form_number']=chalan_form_number();
            $data['student_id']=$student_id;
            $data['student_name']=$query_res[0]['student_name'];
            $data['father_name']=$query_res_p_name[0]['parent_name'];
            $data['roll']=$query_res[0]['roll'];
            $data['bar_code']=$query_res[0]['barcode_image'];

            $data['section_id']=$query_res[0]['section_id'];
            $data['reg_num']=$reg_no;
            $data['mob_num']=$query_res[0]['mob_num'];
            $data['image']=$img;
            $data['system_id']=$query_res[0]['system_id'];
            $data['academic_year_id']=$query_res[0]['academic_year_id'];
            $data['location_id']=$location_id;
            $data['student_status']=$query_res[0]['student_status'];
            $data['id_no']=$query_res[0]['id_no'];
            $data['email']=$query_res[0]['email'];

            if(count($chalan_setting)>0)
            {
                $data['school_name']=$chalan_setting[0]['school_name'];
                $data['school_logo']=$chalan_setting[0]['logo'];
                $data['school_address']=$chalan_setting[0]['address'];
                $data['school_terms']=$chalan_setting[0]['terms'];
                $data['school_bank_detail']=$chalan_setting[0]['bank_details'];
            }
            else
            {
                $data['school_name']="";
                $data['school_logo']="";
                $data['school_address']="";
                $data['school_terms']="";
                $data['school_bank_detail']="";
            }

            $data['section']=$query_res[0]['section_nme'];
            $data['class']=$query_res[0]['class_name'];
            $data['bulk_req_id']=$bulk_req_id;
            $data['department']=$query_res[0]['department_name'];
            $data['form_type']=$form_type;
            $data['due_days']=$due_days;
            $data['is_cancelled']=0;
            $this->db->insert(get_school_db().'.student_chalan_form',$data);
            $s_c_f_id=$this->db->insert_id();
            
            
            $totle=0;
            //////arrears
            $get_val=$this->db->query(" select * from ".get_school_db().".misc_challan_coa_settings 
                                        where school_id=".$_SESSION['school_id']." and type='arrears_coa' 
                                        and receive_dr_coa_id > 0 and receive_cr_coa_id > 0  and generate_dr_coa_id > 0 and generate_cr_coa_id > 0 ")->result_array();

            if(count($get_val)>0)
            {
                $qur_are=$this->db->query("select arrears,s_c_f_id from ".get_school_db().".student_chalan_form 
                where school_id=$school_id and status=5 and student_id=$student_id and is_processed=0 and arrears>0 and is_cancelled=0 and arrears_status=1")->result_array();

                if(count($qur_are)>0)
                {
                    $data_arrears['s_c_f_id'] = $s_c_f_id;
                    $data_arrears['fee_type_title'] = 'Arrears';
                    $data_arrears['school_id'] = $school_id;

                    $data_arrears['generate_dr_coa_id'] = $get_val[0]['generate_dr_coa_id'];
                    $data_arrears['generate_cr_coa_id'] = $get_val[0]['generate_cr_coa_id'];
                    $data_arrears['issue_dr_coa_id']    = $get_val[0]['issue_dr_coa_id'];
                    $data_arrears['issue_cr_coa_id']    = $get_val[0]['issue_cr_coa_id'];
                    $data_arrears['receive_dr_coa_id']  = $get_val[0]['receive_dr_coa_id'];
                    $data_arrears['receive_cr_coa_id']  = $get_val[0]['receive_cr_coa_id'];
                    $data_arrears['cancel_dr_coa_id']   = $get_val[0]['cancel_dr_coa_id'];
                    $data_arrears['cancel_cr_coa_id']   = $get_val[0]['cancel_cr_coa_id'];

                    $arrears_ammount = 0;
                    foreach ($qur_are as $val_amu)
                    {
                        $arrears_ammount += $val_amu['arrears'];
                    }

                    $data_arrears['amount'] = $arrears_ammount;
                    $data_arrears['type'] = 3;
                    $data_arrears['type_id'] = 0;
                    $data_arrears['school_id']=$_SESSION['school_id'];

                    if ($data_arrears['amount'] > 0)
                    {
                        $this->db->insert(get_school_db() . ".student_chalan_detail", $data_arrears);
                    }

                }
            }

            //////arrears ends
            if($form_type!=10)
            {
                $related_s_c_d_id = array();
                $fee_type_id_arr = array();
                foreach($query_re as $rec_row1)
                {
                    $data_detail1=array();
                    $data_detail1['s_c_f_id']=$s_c_f_id;
                    $data_detail1['fee_type_title']=$rec_row1['title'];
                    $data_detail1['school_id']=$school_id;
                    $data_detail1['amount']=$rec_row1['value'];
                    $data_detail1['type']=1;
                    $data_detail1['type_id']=$rec_row1['fee_type_id'];
                    $fee_type_id_arr[] = $data_detail1['type_id'];
              
                    $data_detail1['generate_dr_coa_id'] = $rec_row1['generate_dr_coa_id'];
                    $data_detail1['generate_cr_coa_id'] =  $rec_row1['generate_cr_coa_id'];
                    $data_detail1['issue_dr_coa_id'] =  $rec_row1['issue_dr_coa_id'];
                    $data_detail1['issue_cr_coa_id'] = $rec_row1['issue_cr_coa_id'];
                    $data_detail1['receive_dr_coa_id'] = $rec_row1['receive_dr_coa_id'];
                    $data_detail1['receive_cr_coa_id'] = $rec_row1['receive_cr_coa_id'];
                    $data_detail1['cancel_dr_coa_id'] = $rec_row1['issue_cr_coa_id'];
                    $data_detail1['cancel_cr_coa_id'] = $rec_row1['issue_dr_coa_id'];

                    $totle=$rec_row1['value']+$totle;
                    $this->db->insert(get_school_db().".student_chalan_detail",$data_detail1);
                    $related_s_c_d_id[$rec_row1['fee_type_id']] = $this->db->insert_id();
                }
                $fee_type_id_in = 0;
                if(count($fee_type_id_arr)>0)
               {
                   $fee_type_id_in = implode(",",$fee_type_id_arr);
               }

                $query_rec_str ="SELECT dl.discount_id,dl.title,ccd.value,ccd.order_num, dl.fee_type_id, dl.generate_dr_coa_id,dl.generate_cr_coa_id,
                                 dl.issue_dr_coa_id,dl.issue_cr_coa_id, dl.receive_dr_coa_id,dl.receive_cr_coa_id,
                                 dl.cancel_dr_coa_id,dl.cancel_cr_coa_id FROM ".get_school_db().".discount_list dl inner join ".get_school_db().".class_chalan_discount 
                                 ccd on ccd.discount_id=dl.discount_id inner join ".get_school_db().".class_chalan_form ccf on ccf.c_c_f_id= ccd.c_c_f_id
                                 where ccf.section_id=$section_id and dl.fee_type_id in($fee_type_id_in) and ccf.type=$form_type and ccf.status=1 and ccf.school_id=$school_id 
                                 ORDER BY ccd.order_num";
                $query_rec=$this->db->query($query_rec_str)->result_array();
                foreach($query_rec as $rec_row)
                {
                    $data_detail=array();
                    $data_detail['s_c_f_id']=$s_c_f_id;
                    $data_detail['fee_type_title']=$rec_row['title'];
                    $data_detail['school_id']=$school_id;
                    $amount=$rec_row['value'];
                    $data_detail['type']=2;
                    $data_detail['type_id']=$rec_row['discount_id'];
                    $data_detail['related_s_c_d_id']=$related_s_c_d_id[$rec_row['fee_type_id']];
                    /**/
                    $data_detail['generate_dr_coa_id'] =  $rec_row['generate_dr_coa_id'];
                    $data_detail['generate_cr_coa_id'] = $rec_row['generate_cr_coa_id'];
                    $data_detail['issue_dr_coa_id'] =  $rec_row['issue_dr_coa_id'];
                    $data_detail['issue_cr_coa_id'] = $rec_row['issue_cr_coa_id'];
                    $data_detail['receive_dr_coa_id'] = $rec_row['receive_dr_coa_id'];
                    $data_detail['receive_cr_coa_id'] = $rec_row['receive_cr_coa_id'];
                    $data_detail['cancel_dr_coa_id'] = $rec_row['issue_cr_coa_id'];
                    $data_detail['cancel_cr_coa_id'] = $rec_row['issue_dr_coa_id'];
                    /**/
                    $data_detail['amount']=$rec_row['value'];
                    $totle=$totle- $data_detail['amount'];
                    $this->db->insert(get_school_db().".student_chalan_detail",$data_detail);
                }

                $update_amount['actual_amount']=$totle;
                $this->db->where("s_c_f_id",$s_c_f_id);
                $this->db->where("school_id", $school_id);
                $this->db->update(get_school_db().".student_chalan_form",$update_amount);

                return $s_c_f_id;
            }
            else
            {
                return $s_c_f_id;
            }
        }

    }
            ///////////////////////////////////////
    function insert_chalan_bulk($student_id,$form_type,$bulk_req_id,$school_id,$user_id,$db_name)
    {

        $query_res =$this->db->query(" SELECT s.name as student_name,s.barcode_image,s.gender,s.roll,s.section_id,s.pro_section_id,
                                       s.academic_year_id,s.pro_academic_year_id,sp.p_name as parent_name,cs.title as section_nme,
                                       cc.name as class_name,dd.title as department_name
                                       FROM ".$db_name.".student s 
                                       inner join ".$db_name.".student_relation sr on sr.student_id=s.student_id 
                                       inner join ".$db_name.".student_parent sp on sp.s_p_id=sr.s_p_id 
                                       inner join ".$db_name.".class_section cs on s.section_id=cs.section_id  
                                       inner join ".$db_name.".class cc on cc.class_id=cs.class_id
                                       inner join ".$db_name.".departments dd on dd.departments_id=cc.departments_id
                                       where s.student_id=$student_id and sr.relation='f' AND school_id = ".$_SESSION['school_id']." ")->result_array();
        $section_id=$query_res[0]['section_id'];

        if($form_type==3)
        {
            $section_id=$query_res[0]['pro_section_id'];
        }

        $query_re=$this->db->query(" SELECT $student_id as sid, ft.fee_type_id, ft.title, ccfe.order_num,ccfe.value,ccf.c_c_f_id,ccf.due_days FROM  ".$db_name.".fee_types
                                     ft inner join ".$db_name.".class_chalan_fee ccfe on ccfe.fee_type_id=ft.fee_type_id inner join ".$db_name.".class_chalan_form ccf on
                                     ccf.c_c_f_id= ccfe.c_c_f_id where ccf.section_id=$section_id and ccf.type=$form_type and ccf.status=1 and ccf.school_id=$school_id   
                                     ORDER BY ccfe.order_num ")->result_array();

        if(count($query_re)==0)
        {
            return 'no chalan form setting found';
        }

        else
        {
            $chalan_setting=$this->db->query("select * from ".$db_name.".chalan_settings where school_id=$school_id")->result_array();
            $due_days=$query_re[0]['due_days'];
            $data=array();
            $data['c_c_f_id']=$query_re[0]['c_c_f_id'];
            $data['school_id']=$school_id;
            $data['status']=1;
            $data['fee_month_year']=date("Y-m-d");
            $data['generated_by']=$user_id;
            $data['issued_by']=$user_id;
            $data['approved_by']=$user_id;
            $data['received_by']=$user_id;
            $data['comment']=$user_id;
            $date = new DateTime();
            $data['generation_date']=$date->format('Y-m-d H:i:s');
            $data['due_date']=$date->format('Y-m-d');
            $data['issue_date']=$date->format('Y-m-d');
            $data['payment_date']=$date->format('Y-m-d');
            $data['approval_date']=$date->format('Y-m-d');
            $data['received_date']=$date->format('Y-m-d');
            $data['chalan_form_number']=chalan_form_number();
            $data['student_id']=$student_id;
            $data['student_name']=$query_res[0]['student_name'];
            $data['father_name']=$query_res[0]['parent_name'];
            $data['roll']=$query_res[0]['roll'];
            $data['bar_code']=$query_res[0]['barcode_image'];
            $data['school_name']=$chalan_setting[0]['school_name'];
            $data['school_logo']=$chalan_setting[0]['logo'];
            $data['school_address']=$chalan_setting[0]['address'];
            $data['school_terms']=$chalan_setting[0]['terms'];
            $data['school_bank_detail']=$chalan_setting[0]['bank_details'];
            $data['section']=$query_res[0]['section_nme'];
            $data['class']=$query_res[0]['class_name'];
            $data['bulk_req_id']=$bulk_req_id;
            $data['is_bulk']=0;
            $data['is_cancelled']=0;
            $data['is_processed']=0;
            $data['department']=$query_res[0]['department_name'];
            $data['form_type']=$form_type;
            $data['due_days']=$due_days;
            $this->db->insert($db_name.'.student_chalan_form',$data);
            $s_c_f_id=$this->db->insert_id();
            $totle=0;

            foreach($query_re as $rec_row1)
            {

                $data_detail1=array();
                $data_detail1['s_c_f_id']=$s_c_f_id;
                $data_detail1['fee_type_title']=$rec_row1['title'];
                $data_detail1['school_id']=$school_id;
                $data_detail1['amount']=$rec_row1['value'];
                $data_detail1['type']=1;
                $data_detail1['type_id']=$rec_row1['type_id'];
                $totle=$rec_row1['value']+$totle;
                $this->db->insert($db_name.".student_chalan_detail",$data_detail1);
            }

            $query_rec=$this->db->query("SELECT  dl.discount_id,dl.title,ccd.value,ccd.order_num FROM ".$db_name.".discount_list dl inner join ".$db_name.".class_chalan_discount 
                                         ccd on ccd.discount_id=dl.discount_id inner join ".$db_name.".class_chalan_form ccf on ccf.c_c_f_id= ccd.c_c_f_id
                                         where ccf.section_id=$section_id and ccf.type=$form_type and ccf.status=1 and ccf.school_id=$school_id ORDER BY ccd.order_num ")->result_array();
            foreach($query_rec as $rec_row)
            {

                $data_detail=array();
                $data_detail['s_c_f_id']=$s_c_f_id;
                $data_detail['fee_type_title']=$rec_row['title'];
                $data_detail['school_id']=$school_id;
                $amount=$rec_row['value'];
                $data_detail['type']=2;
                $data_detail['type_id']=$rec_row['discount_id'];
                $data_detail['amount']=$rec_row['value'];
                $totle=$totle- $data_detail['amount'];
                $this->db->insert($db_name.".student_chalan_detail",$data_detail);

            }

            $update_amount['actual_amount']=$totle;
            $update_amount['received_amount']=$totle;
            $this->db->where("s_c_f_id",$s_c_f_id);
            $this->db->where("school_id",$_SESSION['school_id']);
            $this->db->update($db_name.".student_chalan_form",$update_amount);
            return $s_c_f_id;
        }
    }

    function school_student_list()
    {
        $form_type=1;
        $school_id=187;
        $user_id=151;
        $db_name='gsims_afss';
        $adm_term_id=31;
        $academic_year_id=19;
        $qur=$this->db->query("select student_id from ".$db_name.".student where student_status=1 AND school_id =  $school_id")->result_array();
        $this->load->helper("student");

        foreach($qur as $row){
            $student_id=$row['student_id'];
            $this->insert_chalan_bulk($student_id,$form_type,0,$school_id,$user_id,$db_name);
            $this->approve_bulk($student_id,$school_id,$db_name,$adm_term_id,$academic_year_id);
            student_archive($user_id,$student_id);
        }
        echo "completed";
    }

    function approve_bulk($student_id,$school_id,$db_name,$adm_term_id,$academic_year_id){

        $data['student_status']=10;
        $data['adm_term_id']=$adm_term_id;
        $data['academic_year_id']=$academic_year_id;
        $data['adm_date']=date('Y-m-d');
        $where_ary=array('school_id'=>$school_id, 'student_id'=>$student_id);
        $this->db->where($where_ary);
        $this->db->update($db_name.".student",$data);
        student_archive($_SESSION['login_detail_id'],$student_id);

    }

    function edit_chalan_form($s_c_f_id,$back_list="",$chalan_type_id="",$bulk_back_id=0)
    {
        
		$school_id                 =  $_SESSION['school_id'];
        $page_data["query_ary"]    =  $this->db->query("select * from ".get_school_db().".student_chalan_form  where s_c_f_id= $s_c_f_id
                                                        and is_cancelled = 0 and school_id=$school_id")->result_array();
        $page_data['bulk_back_id'] =  $bulk_back_id;
        $page_data['page_name']    =  'student_chalan_edit';
        $page_data['page_title']   =  get_phrase('chalan_form');
        $page_data['back_list']    =  $back_list;
        $page_data['back_list_id'] =  $bulk_back_id;

        $this->load->view('backend/index', $page_data);
    }
    
    function monthly_bulk_challan_paid()
    {
        
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        
        $student_ids_arr = $this->input->post('student_ids');
        $id = explode(',' , $student_ids_arr[0]);
        $url_back =  $_POST['url_challan_id'];
        $school_id = $_SESSION['school_id'];
        // print_r($id);exit;
        if(isset($id)){
            $count = count($id);
            for($i = 0; $i < $count; $i++){
                $get = $this->db->query("select * from ".get_school_db().".student_chalan_form where s_c_f_id = '".$id[$i]."' and is_cancelled=0 and school_id=$school_id")->result();
                foreach($get as $data)
                {
                    $s_c_f_id = $data->s_c_f_id;
                    $std_id = $data->student_id;
                    // $sid = $data->student_id;
                    $received_amount = $data->actual_amount;
                    $actual_amu = $data->actual_amount;
                    $other_amount = 0;
                    
                    $str = "SELECT scd.fee_type_title , scd.amount , scd.school_id , scd.receive_dr_coa_id ,scd.receive_cr_coa_id FROM 
                    ".get_school_db().".student_chalan_form as scf INNER JOIN ".get_school_db().".student_chalan_detail as scd ON 
                    scf.s_c_f_id = scd.s_c_f_id WHERE scd.s_c_f_id = ". $s_c_f_id." AND scd.school_id = ".$_SESSION['school_id']."
                    AND scf.is_cancelled = 0 AND (scd.receive_dr_coa_id = 0) AND scd.type = 1";
                    $query = $this->db->query($str);
            
                    if(count($query->result())>0)
                    {
                        $this->session->set_flashdata('journal_entry',get_phrase('challan_form_not_recieved_incomplete_chart_of_account_settings'));
                        redirect(base_url() . 'c_student/student_information/');
                    }
                    else
                    {
                        // If Other Amount > 0 Then Run This Script
                        // if ($other_amount > 0)
                        // {
                        //     $data_other['fee_type_title']=$this->input->post('other_title');
                        //     $data_other['amount']= $this->input->post('other_amount');
            
                        //     $data_other['school_id']=$_SESSION['school_id'];
                        //     $data_other['s_c_f_id']=$s_c_f_id;
                        //     $data_other['type']=6;
                        //     $get_val1 = $this->db->query("select * from " . get_school_db() . ".misc_challan_coa_settings 
                        //                                     where 
                        //                                     receive_dr_coa_id>0
                        //                                     AND receive_cr_coa_id>0
                        //                                     AND school_id=" . $_SESSION['school_id'] . " and type='late_fee_fine_coa'")->result_array();
                        //     // other_coa
                        //     /*  */
                        //     if(count($get_val1)>0)
                        //     {
            
                        //         $data_other['generate_dr_coa_id'] = $get_val1[0]['generate_dr_coa_id'];
                        //         $data_other['generate_cr_coa_id'] = $get_val1[0]['generate_cr_coa_id'];
            
                        //         $data_other['issue_dr_coa_id'] = $get_val1[0]['issue_dr_coa_id'];
                        //         $data_other['issue_cr_coa_id'] = $get_val1[0]['issue_cr_coa_id'];
            
                        //         $data_other['receive_dr_coa_id'] = $get_val1[0]['receive_dr_coa_id'];
                        //         $data_other['receive_cr_coa_id'] = $get_val1[0]['receive_cr_coa_id'];
            
                        //         $data_other['cancel_dr_coa_id'] = $get_val1[0]['cancel_dr_coa_id'];
                        //         $data_other['cancel_cr_coa_id'] = $get_val1[0]['cancel_cr_coa_id'];
                        //         $data_other['school_id'] = $_SESSION['school_id'];
            
                        //         /* */
                        //         $this->db->insert(get_school_db() . '.student_chalan_detail', $data_other);
                        //     }
                        //     /* */
                        // }
                        
                        
                        $entry_date = $this->input->post("payment_date");

                        $fee_amount = 0;
                        $total_discount_amount = 0;
            
                        $str_fee = "SELECT scf_fee.student_id,scf_fee.chalan_form_number, scf_fee.chalan_form_number, scfd_fee.* 
                                    FROM ".get_school_db().".student_chalan_detail as scfd_fee INNER JOIN ".get_school_db().".student_chalan_form as scf_fee 
                                    ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id  WHERE scfd_fee.s_c_f_id = $s_c_f_id AND scfd_fee.type = 1 AND 
                                    scfd_fee.school_id = ".$_SESSION['school_id']."";
                        $query_fee = $this->db->query($str_fee)->result_array();
                        
                        $sum_total_amount = "SELECT SUM(scfd_fee.amount) AS total_amount FROM ".get_school_db().".student_chalan_detail as scfd_fee 
                                    INNER JOIN ".get_school_db().".student_chalan_form as scf_fee ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id 
                                    WHERE scfd_fee.s_c_f_id = $s_c_f_id AND scfd_fee.type = 1 AND 
                                    scfd_fee.school_id = ".$_SESSION['school_id']."";
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
                                    $transaction_detail = student_name_section($value_fee['student_id']);

                                    $s_c_d_id = $value_fee['s_c_d_id'];
                                    $discount_amt = $this->is_discount_fee($s_c_d_id, $s_c_f_id);
                
                                    $fee_amount_temp = $fee_amount;
                
                                    if($discount_amt>0)
                                    {
                
                                        $discount_amt = round((($discount_amt * $fee_amount) / 100));
                                        $fee_amount_temp = $fee_amount_temp-$discount_amt;
                                    }
                                    
                                    //   Discount Journal Entry
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
                                                    FROM " . get_school_db() . ".discount_list as d
                                                    INNER join  " . get_school_db() . ".fee_types as f ON f.fee_type_id = d.fee_type_id
                                                    INNER JOIN " . get_school_db() . ".student_chalan_detail as scfd_discount ON scfd_discount.type_id = d.discount_id
                                                    INNER JOIN " . get_school_db() . ".student_chalan_form as scf_discount ON scf_discount.s_c_f_id = scfd_discount.s_c_f_id
                                                    WHERE f.fee_type_id = $fee_type_id_fee
                                                    AND scfd_discount.s_c_f_id =$s_c_f_id
                                                    AND scfd_discount.school_id = " . $_SESSION['school_id'] . "
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
                                                   . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                                               'credit' => $fee_amount,
                                               'entry_type' => 3,
                                               'type_id' => $s_c_f_id,
                                               'student_id' => $value_fee['student_id'],
                                               'school_id' => $fee_school_id,
                                               'coa_id' => $fee_receive_cr_coa_id
                                           );
                                        //   $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_fee);
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
                                           // $discount_issue_cr_coa_id = $value_discount['issue_cr_coa_id'];
                                            $discount_receive_dr_coa_id = $value_discount['issue_dr_coa_id'];
                                            $discount_chalan_form_number = $value_discount['chalan_form_number'];
                                            $transaction_detail = student_name_section($value_discount['student_id']);
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
                
                                            // $this->db->insert(get_school_db().".journal_entry", $array_ledger_discount);
                                            
                                            // Credit Journal Entry Challan Recieved (Zeeshan)
                                                $array_ledger_fee = array(
                                                   'entry_date' => $entry_date,
                                                   'detail' => $fee_type_title
                                                       . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                                                   'credit' => $fee_amount - $discount_amount,
                                                   'entry_type' => 3,
                                                   'type_id' => $s_c_f_id,
                                                   'student_id' => $value_fee['student_id'],
                                                   'school_id' => $fee_school_id,
                                                   'coa_id' => $fee_receive_cr_coa_id
                                                );
                                            //   $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_fee);   
                
                                        }
                
                                    }
                                    /*recent change discount end*/
                                    
                                    $remanining_amount = $grand_total_amount - $total_discount_amount;

                                }
                                
                                // Credit Journal Entry Challan Recieved (Zeeshan)
                                $array_ledger_fee_credit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => "Total Fee "
                                        . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                                    'credit' => $remanining_amount,
                                    'entry_type' => 3,
                                    'type_id' => $s_c_f_id,
                                    'student_id' => $std_id,
                                    'school_id' => $school_id,
                                    'coa_id' => $fee_receive_cr_coa_id
                                );
                                $this->db->insert(get_school_db().".journal_entry", $array_ledger_fee_credit);
                                // End Total Credit JE
                                
                                // Debit Journal Entry Challan Recieved (Zeeshan)
                                $array_ledger_fee_debit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => "Total Fee "
                                        . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                                    'debit' => $remanining_amount,
                                    'entry_type' => 3,
                                    'type_id' => $s_c_f_id,
                                    'student_id' => $std_id,
                                    'school_id' => $school_id,
                                    'coa_id' => $fee_receive_dr_coa_id
                                );
                                $this->db->insert(get_school_db().".journal_entry", $array_ledger_fee_debit);
                            }
                        }

                        $student_id = $std_id;
                        
                        $data = array();
                        $data['received_amount'] = $remanining_amount;
                        $data['arrears_status'] = 0;
                        $data['received_by'] = $_SESSION['login_detail_id'];
                        $data['received_date'] = date('Y-m-d h:i:s');
                        $payment_date = date("Y-m-d");
                        $data['payment_date'] = date_slash($payment_date);
                        $data['status'] = 5;
                        
                        // Check Previous Challan Query
                        $check_previous_chalan = $this->db->query("select * from ".get_school_db().".student_chalan_form where student_id = $student_id and s_c_f_id <> $s_c_f_id and is_cancelled = 0 and school_id = $school_id");
                        if($check_previous_chalan->num_rows() > 0)
                        {
                            // Previous Challan Paid Query
                            $this->db->where("s_c_f_id <>", $s_c_f_id);
                            $this->db->where("student_id", $student_id);
                            $this->db->where('school_id', $_SESSION['school_id']);
                            $this->db->where('is_cancelled', 0);
                            $this->db->update(get_school_db() . ".student_chalan_form", $data);
                        } 
                        
                        /*change*/
                        $this->db->where("s_c_f_id", $s_c_f_id);
                        $this->db->where('school_id', $_SESSION['school_id']);
                        $this->db->update(get_school_db() . ".student_chalan_form", $data);
                        
                        $this->load->helper('message');
                        $sms_ary = get_sms_detail($student_id);
                        $message = "Amount of Rs. " . $data['received_amount'] . " received from " . $sms_ary['student_name'] . ".";
                        send_sms($sms_ary['mob_num'], 'Indici Edu', $message, $student_id,1);

                        $to_email = $sms_ary['email'];
                        $subject = "Fee Received";
                        $email_message = "<b>Dear Parents </b><br><br> Amount of Rs. " . $data['received_amount'] . " received from " . $sms_ary['student_name'] . " <br>
                        In case of any query feel free to contact us at info@indiciedu.com.pk.";
                        $email_layout = get_email_layout($email_message);
                        email_send("No Reply", "Indici Edu", $to_email, $subject, $email_layout, $student_id,1);
                        
                }
                $msg = "done";
            }
            // if($msg)
            // {
            //     $this->session->set_flashdata('paid_bulk', 'Bulk Challan Received Successfully');
            //     redirect(base_url().'monthly_fee/view_detail_listing/'.$url_back.'/bulkpaid');
            // }
        }
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        if($msg)
        {
            $this->session->set_flashdata('paid_bulk', 'Bulk Challan Received Successfully');
            redirect(base_url().'monthly_fee/view_detail_listing/'.$url_back.'/bulkpaid');
        }
        
    }
    
    function get_student_chalan()
    {
        $count_num=1;
        $s_c_f_id=$this->input->post('s_c_f_id');
        $status=$this->input->post('status');
        $query_a=$this->db->query("select * from ".get_school_db().".student_chalan_detail  where s_c_f_id=$s_c_f_id and school_id=".$_SESSION['school_id']." order by type asc")->result_array();
        
        $chalan="";
        $discount="";
        $arrears="";
        $totle=0;
        foreach($query_a as $rec_row1)
        {
            $edit="";
            if($rec_row1['type']==1 || $rec_row1['type']==5)
            {
                if($rec_row1['type']==1 && $status<4)
                {
                    $delete_url=base_url()."class_chalan_form/delete_chalan_detail/".$s_c_f_id."/".$rec_row1['s_c_d_id']."/".$rec_row1['type_id']."/".$rec_row1['type'];

                    $edit='<a onclick="showAjaxModal(\''.base_url().'modal/popup/edit_fee_chalan_form/'.$s_c_f_id.'/1/'.$rec_row1['s_c_d_id'].'\');"  href="#">
                            <br> <span class="glyphicon glyphicon-pencil"></span></a> 
                                <span class="pipe">|</span>
                                <a href="#">
                                  <span class="glyphicon glyphicon-remove" onclick="confirm_modal(\''.$delete_url.'\');"></span>
                                </a>';
                }

                $chalan=$chalan.'<tr class="s_chalan"><td  class="text_style border_bottom  border_right">'.$rec_row1['fee_type_title'].'</td> <td  colspan="3" class="text_style border_bottom">'.$rec_row1['amount'].$edit.'</td><tr>';
                $totle=$totle+$rec_row1['amount'];
            }
            elseif($rec_row1['type']==2 || $rec_row1['type']==4)
            {

                if($rec_row1['type']==2 && $status<4)
                {
                    
                    $delete_url=base_url()."class_chalan_form/delete_chalan_detail/".$s_c_f_id."/".$rec_row1['s_c_d_id']."/".$rec_row1['fee_type_id']."/".$rec_row1['type'];
                    $edit='<a onclick="showAjaxModal(\''.base_url().'modal/popup/edit_discount_field/'.$s_c_f_id.'/2/'.$rec_row1['s_c_d_id'].'\');"  href="#">
                          <span class="glyphicon glyphicon-pencil"></span>
                          </a> <a href="#">
                          <span class="glyphicon glyphicon-remove" onclick="confirm_modal(\''.$delete_url.'\');"></span>
                          </a>';
                }

                $amount_str = "SELECT scf.amount as am, scf.fee_type_title as fee_title FROM ".get_school_db().".discount_list as d 
                               inner JOIN ".get_school_db().".student_chalan_detail as scf on d.fee_type_id = scf.type_id
                               where d.discount_id = ".$rec_row1['type_id']." and scf.s_c_f_id = ".$rec_row1['s_c_f_id']." and type = 1 and scf.school_id = ".$_SESSION['school_id']."";

                $amount_query=$this->db->query($amount_str)->row();
                $amount_current = round($rec_row1['amount']*($amount_query->am/100));
                
                if($rec_row1['settings_type_id'] == 3){
                    $discount .='<tr class="s_chalan"><td class="text_style border_bottom  border_right">'.$rec_row1['fee_type_title'].' ('.$amount_query->fee_title.') '.$rec_row1['amount'].'</td>
                    <td colspan="3" class="text_style border_bottom">('.round($rec_row1['amount']).')'.$edit.'</td><tr>';
                    $amount_current = $rec_row1['amount'];
                }
                else{
                    $discount .='<tr class="s_chalan"><td class="text_style border_bottom  border_right">'.$rec_row1['fee_type_title'].' ('.$amount_query->fee_title.') '.$rec_row1['amount'].'%'.'</td>
                    <td colspan="3" class="text_style border_bottom">('.round($amount_current).')'.$edit.'</td><tr>';
                }
                $totle=round($totle-$amount_current);

            }
            elseif($rec_row1['type']==3)
            {
                $arrears.='<tr class="s_chalan"><td class="text_style border_bottom  border_right" >'.$rec_row1['fee_type_title'].'</td> 
                <td colspan="3" class="text_style border_bottom">'.$rec_row1['amount'].'</td><tr>';
                $totle=$totle+$rec_row1['amount'];
            }
            else if($rec_row1['type']==6 && $rec_row1['amount']>0)
            {
                $arrears.='
                    <tr class="s_chalan">
                        <td class="text_style border_bottom  border_right">'.$rec_row1['fee_type_title'].'</td> 
                        <td class="text_style border_bottom">'.$rec_row1['amount'].'</td>
                    <tr>';
                $totle=$totle+$rec_row1['amount'];
            }
        
            $count_num++;
        }
        if($status>=4)
        {
            $add_2="";
            $add_1="";
        }
        else
        {
            $add_1='<a   onclick="showAjaxModal(\''.base_url().'modal/popup/add_fee_field/'.$s_c_f_id.'/1\');"  href="#">
                      <span class="glyphicon glyphicon-plus"></span>
                    </a>';
                        $add_2='<a   onclick="showAjaxModal(\''.base_url().'modal/popup/add_discount_field/'.$s_c_f_id.'/2\');"  href="#">
                      <span class="glyphicon glyphicon-plus"></span>
                    </a>';
        }
        echo '
            <tr class="s_chalan">
                <td class="text_style border_bottom  border_right" width="7%" colspan="2" >'.get_phrase('fee').' '.$add_1.'</td></tr>';
        echo $chalan;
        /* this statement befor below line in 1154 */
        echo $arrears;
        echo '<tr class="s_chalan"><td class="text_style border_bottom  border_right" width="7%" colspan="2" >'.get_phrase('discount').' '.$add_2.'</td></tr>';
        echo $discount;
        echo '<tr class="s_chalan">
            <td class="text_style border_bottom border_right">'.get_phrase('total_amount').'</td>
            <td class="text_style border_bottom"> '.$totle.'</td>	
            </tr>' ;
        $this->load->helper("num_word");
        echo '<tr class="s_chalan">
                <td class="text_style " colspan="2"><strong>'.get_phrase('in_words').': </strong>'.convert_number_to_words($totle).' '.get_phrase('currency_long').'</td>
            </tr>';
        $this->db->query("UPDATE  ".get_school_db().".student_chalan_form SET actual_amount = $totle WHERE s_c_f_id = $s_c_f_id AND school_id = ".$_SESSION['school_id']."");
    }

    function save_fee_chalan_form()
    {
        $data['fee_type_title']=$this->input->post('title');
        $data['s_c_f_id']=$this->input->post('s_c_f_id');
        $data['type']=$this->input->post('type');
        $fee_id=explode('_',$this->input->post('fee_type_id'));

        $data['type_id'] = $fee_id[0];
        $data['amount']=$this->input->post('amount');
        $data['school_id']=$_SESSION['school_id'];
        $s_c_d_id=$this->input->post('s_c_d_id');

        if($data['type'] == 1)
        {
            $this->db->where('fee_type_id', $data['type_id']);
            $this->db->where('school_id',$_SESSION['school_id']);
            $query = $this->db->get(get_school_db().'.fee_types');
            $row = $query->row();
            $data['generate_dr_coa_id'] = $row->generate_dr_coa_id;
            $data['generate_cr_coa_id'] = $row->generate_cr_coa_id;
            $data['issue_dr_coa_id'] = $row->issue_dr_coa_id;
            $data['issue_cr_coa_id'] = $row->issue_cr_coa_id;
            $data['receive_dr_coa_id'] = $row->receive_dr_coa_id;
            $data['receive_cr_coa_id'] = $row->receive_cr_coa_id;
            $data['cancel_dr_coa_id'] = $row->issue_cr_coa_id;
            $data['cancel_cr_coa_id'] = $row->issue_dr_coa_id;
        }

        if($s_c_d_id!="")
        {
            $this->db->where('s_c_d_id',$s_c_d_id);
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->update(get_school_db().'.student_chalan_detail',$data);
        }
        else
        {
            $this->db->insert(get_school_db().'.student_chalan_detail',$data);
        }

        $this->db->where('s_c_f_id',$data['s_c_f_id']);
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->update(get_school_db().'.student_chalan_form',array('status'=>1));

    }

    function update_fee_chalan_form()
    {

        $data['fee_type_title']=$this->input->post('title');
        $data['s_c_f_id']=$this->input->post('s_c_f_id');
        $s_c_f_id = $data['s_c_f_id'];
        $data['type']=$this->input->post('type');
        $fee_id=explode('_',$this->input->post('fee_type_id'));
        $data['type_id'] = $fee_id[0];
        $data['amount']=$this->input->post('amount');
        $data['school_id']=$_SESSION['school_id'];
        $s_c_d_id=$this->input->post('s_c_d_id');

        if($data['type'] == 1)
        {
            $this->db->where('fee_type_id', $data['fee_type_id']);
            $this->db->where('school_id',$_SESSION['school_id']);
            $query = $this->db->get(get_school_db().'.fee_types');
            $row = $query->row();
            $data['generate_dr_coa_id'] = $row->generate_dr_coa_id;
            $data['generate_cr_coa_id'] = $row->generate_cr_coa_id;
            $data['issue_dr_coa_id'] = $row->issue_dr_coa_id;
            $data['issue_cr_coa_id'] = $row->issue_cr_coa_id;
            $data['receive_dr_coa_id'] = $row->receive_dr_coa_id;
            $data['receive_cr_coa_id'] = $row->receive_cr_coa_id;
            $data['cancel_dr_coa_id'] = $row->issue_cr_coa_id;
            $data['cancel_cr_coa_id'] = $row->issue_dr_coa_id;
        }

        if($s_c_d_id!="")
        {

            $fee_type_id = $data['type_id'];
            $edit_chk_str = "SELECT d.discount_id FROM ".get_school_db().".discount_list as d 
                                    INNER JOIN ".get_school_db().".fee_types as f on d.fee_type_id = f.fee_type_id 
                                    INNER JOIN ".get_school_db().".student_chalan_detail as scd on (scd.type_id = d.discount_id and scd.type = 2)
                                        WHERE f.fee_type_id = $fee_type_id and scd.s_c_f_id = $s_c_f_id AND d.school_id = ".$_SESSION['school_id']."";

            $edit_chk_query =$this->db->query($edit_chk_str)->row();

            $this->db->where('s_c_d_id',$s_c_d_id);
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->update(get_school_db().'.student_chalan_detail',$data);

        }
        else
        {
            $this->db->insert(get_school_db().'.student_chalan_detail',$data);
        }

        $this->db->where('s_c_f_id',$data['s_c_f_id']);
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->update(get_school_db().'.student_chalan_form',array('status'=>1));

    }

    function save_chalan_field()
    {

        $data['fee_type_title']=$this->input->post('title');
        $data['s_c_f_id']=$this->input->post('s_c_f_id');
        $data['type']=$this->input->post('type');
        $fee_id=explode('_',$this->input->post('fee_type_id'));

        $data['fee_type_id'] = $fee_id[0];
        $data['amount']=$this->input->post('amount');
        $data['school_id']=$_SESSION['school_id'];
        $s_c_d_id=$this->input->post('s_c_d_id');

        if($data['type'] == 1)
        {
            $this->db->where('fee_type_id', $data['fee_type_id']);
            $this->db->where('school_id',$_SESSION['school_id']);
            $query = $this->db->get(get_school_db().'.fee_types');
            $row = $query->row();
            $data['generate_dr_coa_id'] = $row->generate_dr_coa_id;
            $data['generate_cr_coa_id'] = $row->generate_cr_coa_id;
            $data['issue_dr_coa_id'] = $row->issue_dr_coa_id;
            $data['issue_cr_coa_id'] = $row->issue_cr_coa_id;
            $data['receive_dr_coa_id'] = $row->receive_dr_coa_id;
            $data['receive_cr_coa_id'] = $row->receive_cr_coa_id;
            $data['cancel_dr_coa_id'] = $row->issue_cr_coa_id;
            $data['cancel_cr_coa_id'] = $row->issue_dr_coa_id;
        }
        else if($data['type'] == 2)
        {
            $data['related_s_c_d_id'] = $fee_id[1];
            $this->db->where('discount_id', $data['fee_type_id']);
            $query = $this->db->get(get_school_db().'.discount_list');
            $row = $query->row();
            $data['issue_cr_coa_id'] = $row->issue_cr_coa_id;
            $data['receive_dr_coa_id'] = $row->receive_dr_coa_id;
            $data['school_id'] = $_SESSION['school_id'];
        }
        if($s_c_d_id!="")
        {
            $this->db->where('s_c_d_id',$s_c_d_id);
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->update(get_school_db().'.student_chalan_detail',$data);
        }
        else
        {
            $this->db->insert(get_school_db().'.student_chalan_detail',$data);
        }

        $this->db->where('s_c_f_id',$data['s_c_f_id']);
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->update(get_school_db().'.student_chalan_form',array('status'=>1));

    }

    function add_discount_chalan_form()
    {

        $data['fee_type_title']=$this->input->post('title');
        $data['s_c_f_id']=$this->input->post('s_c_f_id');
        $data['type']=$this->input->post('type');
        $fee_id=explode('_',$this->input->post('fee_type_id'));

        $data['type_id'] = $fee_id[0];
        $data['amount']=$this->input->post('amount');
        $data['school_id']=$_SESSION['school_id'];
        $s_c_d_id=$this->input->post('s_c_d_id');

        if($data['type'] == 1)
        {
            $this->db->where('fee_type_id', $fee_id[0]);
            $this->db->where('school_id',$_SESSION['school_id']);
            $query = $this->db->get(get_school_db().'.fee_types');
            $row = $query->row();
            $data['generate_dr_coa_id'] = $row->generate_dr_coa_id;
            $data['generate_cr_coa_id'] = $row->generate_cr_coa_id;
            $data['issue_dr_coa_id'] = $row->issue_dr_coa_id;
            $data['issue_cr_coa_id'] = $row->issue_cr_coa_id;
            $data['receive_dr_coa_id'] = $row->receive_dr_coa_id;
            $data['receive_cr_coa_id'] = $row->receive_cr_coa_id;
            $data['cancel_dr_coa_id'] = $row->issue_cr_coa_id;
            $data['cancel_cr_coa_id'] = $row->issue_dr_coa_id;
        }
        else if($data['type'] == 2)
        {
            $data['related_s_c_d_id'] = $fee_id[1];
            $this->db->where('discount_id', $fee_id[0]);
            $query = $this->db->get(get_school_db().'.discount_list');
            $row = $query->row();

            $data['issue_cr_coa_id'] = $row->issue_cr_coa_id;
            $data['receive_dr_coa_id'] = $row->receive_dr_coa_id;
            $data['school_id'] = $_SESSION['school_id'];
        }
        if($s_c_d_id!="")
        {
            $this->db->where('s_c_d_id',$s_c_d_id);
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->update(get_school_db().'.student_chalan_detail',$data);
        }
        else
        {
            $this->db->insert(get_school_db().'.student_chalan_detail',$data);
        }

        $this->db->where('s_c_f_id',$data['s_c_f_id']);
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->update(get_school_db().'.student_chalan_form',array('status'=>1));

    }
    function edit_discount_chalan_form()
    {

        $data['fee_type_title']=$this->input->post('title');
        $data['s_c_f_id']=$this->input->post('s_c_f_id');
        $data['type']=$this->input->post('type');
        $fee_id=explode('_',$this->input->post('fee_type_id'));

        $data['type_id'] = $fee_id[0];
        $data['amount']=$this->input->post('amount');
        $data['school_id']=$_SESSION['school_id'];
        $s_c_d_id=$this->input->post('s_c_d_id');

        if($data['type'] == 1)
        {
            $this->db->where('fee_type_id', $fee_id[0]);
            $this->db->where('school_id',$_SESSION['school_id']);
            $query = $this->db->get(get_school_db().'.fee_types');
            $row = $query->row();
            $data['generate_dr_coa_id'] = $row->generate_dr_coa_id;
            $data['generate_cr_coa_id'] = $row->generate_cr_coa_id;
            $data['issue_dr_coa_id'] = $row->issue_dr_coa_id;
            $data['issue_cr_coa_id'] = $row->issue_cr_coa_id;
            $data['receive_dr_coa_id'] = $row->receive_dr_coa_id;
            $data['receive_cr_coa_id'] = $row->receive_cr_coa_id;
            $data['cancel_dr_coa_id'] = $row->issue_cr_coa_id;
            $data['cancel_cr_coa_id'] = $row->issue_dr_coa_id;
        }
        else if($data['type'] == 2)
        {
            $data['related_s_c_d_id'] = $fee_id[1];
            $this->db->where('discount_id', $fee_id[0]);
            $query = $this->db->get(get_school_db().'.discount_list');
            $row = $query->row();

            $data['issue_cr_coa_id'] = $row->issue_cr_coa_id;
            $data['receive_dr_coa_id'] = $row->receive_dr_coa_id;
            $data['school_id'] = $_SESSION['school_id'];
        }
        if($s_c_d_id!="")
        {
            $this->db->where('s_c_d_id',$s_c_d_id);
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->update(get_school_db().'.student_chalan_detail',$data);
        }
        else
        {
            $this->db->insert(get_school_db().'.student_chalan_detail',$data);
        }

        $this->db->where('s_c_f_id',$data['s_c_f_id']);
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->update(get_school_db().'.student_chalan_form',array('status'=>1));

    }
    function delete_chalan_detail($s_c_f_id,$s_c_d_id,$fee_type_id,$type=0){

        $delete_chk_str = "SELECT DISTINCT d.discount_id FROM ".get_school_db().".discount_list as d 
                            INNER JOIN ".get_school_db().".fee_types as f on d.fee_type_id = f.fee_type_id 
                            INNER JOIN ".get_school_db().".student_chalan_detail as scd
                            on (scd.type_id = d.discount_id and scd.type = 2)
                            WHERE f.fee_type_id = $fee_type_id
                                    and scd.s_c_f_id = $s_c_f_id
                                    AND d.school_id = ".$_SESSION['school_id']."";

        $delete_chk_query =$this->db->query($delete_chk_str)->num_rows();

        if($delete_chk_query>0)
        {
            $this->session->set_flashdata('operation_info',get_phrase('Please first deleted its related discount!'));
            redirect(base_url() . 'class_chalan_form/edit_chalan_form/'.$s_c_f_id.'/'.$type);
        }
        else
        {
            $this->db->where('s_c_f_id',$s_c_f_id);
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->update(get_school_db().'.student_chalan_form',array('status'=>1));
            $this->db->query("delete from ".get_school_db().".student_chalan_detail where s_c_d_id=$s_c_d_id");
            redirect($_SERVER['HTTP_REFERER']);
        }

    }

    function delete_confirm1($s_c_f_id,$s_c_d_id , $fee_type_id)
    {

        $array_discount_id = array();
        $discount_str_q = "SELECT d.discount_id FROM ".get_school_db().".discount_list as d INNER JOIN ".get_school_db().".fee_types as f on d.fee_type_id = f.fee_type_id
                           WHERE f.fee_type_id = $fee_type_id AND d.school_id = ".$_SESSION['school_id']."";
        $discount_q =$this->db->query($discount_str_q)->result_array();

        foreach($discount_q as $val)
        {
            $array_discount_id[] = $val['discount_id'];
        }
        $discount_id_str = implode($array_discount_id,",");

        $scd_chk_del = "SELECT s_c_f_id FROM ".get_school_db().".student_chalan_detail WHERE fee_type_id IN($discount_id_str) AND s_c_f_id = $s_c_f_id AND school_id = ".$_SESSION['school_id']."";
        if($scd_chk_del>0)
        {
         return "TRUE";
        }
        else
        {
          return "FALSE";
        }
    }

    function save_chalan_form()
    {
        $data['status']=$this->input->post('status');
        $data['comment']=$this->input->post('comment');
        $s_c_f_id=$this->input->post('s_c_f_id');

        $status_message = "";
        if($data['status'] == 2)
        {
            $status_message = get_phrase("Approval Needed");
        }
        else if($data['status'] == 3)
        {
            $status_message = get_phrase("Approved");
        }
        else if($data['status'] == 1)
        {
            $status_message = get_phrase("Generated");
        }
        // $amount_query_str = "SELECT sum(amount) as total_amount FROM ".get_school_db().".student_chalan_detail WHERE s_c_f_id = $s_c_f_id";
        // $amount_query=$this->db->query($amount_query_str)->row();

        // if($amount_query->total_amount>0)
        // {
            if ($data['status'] == 3)
            {
                $date = new DateTime();
                $data['approval_date'] = $date->format('Y-m-d H:i:s');
                $data['approved_by'] = $_SESSION['login_detail_id'];
            }
            $this->db->where("s_c_f_id", $s_c_f_id);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->update(get_school_db() . ".student_chalan_form", $data);
            $this->session->set_flashdata('operation_info',get_phrase('chalan form have been '.$status_message.' !'));
            redirect(base_url() . 'class_chalan_form/edit_chalan_form/'.$s_c_f_id.'/'.$data[status].'');
    }

    function save_print_chalan()
    {

       $s_c_f_id=$this->input->post('s_c_f_id');
       $status_val=$this->input->post('status_val');

       $data['issue_date']=$this->input->post('issue_date');
       $data['issued_by']=$_SESSION['login_detail_id'];
       $date_due = date('Y-m-d',strtotime($this->input->post('due_date')));
       // $due_arry=explode('/',$date_due);
       $data['due_date'] = $date_due;
       $data['status']=4;
       $date = new DateTime();
       $entry_date= $date->format('Y-m-d H:i:s');
       $fee_amount = 0;
       $total_discount_amount = 0;
       $school_id = $_SESSION['school_id'];

       $str_fee = " SELECT scf_fee.student_id,scf_fee.chalan_form_number,scf_fee.chalan_form_number, scfd_fee.*  FROM ".get_school_db().".student_chalan_detail as scfd_fee
                    INNER JOIN ".get_school_db().".student_chalan_form as scf_fee ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id  WHERE scfd_fee.s_c_f_id = $s_c_f_id
	                AND scfd_fee.type = 1 AND scfd_fee.school_id = ".$_SESSION['school_id']." ";
       $query_fee = $this->db->query($str_fee)->result_array();
        
       $sum_total_amount = "SELECT SUM(scfd_fee.amount) AS total_amount FROM ".get_school_db().".student_chalan_detail as scfd_fee 
                    INNER JOIN ".get_school_db().".student_chalan_form as scf_fee ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id 
                    WHERE scfd_fee.s_c_f_id = $s_c_f_id AND scfd_fee.type = 1 AND 
                    scfd_fee.school_id = ".$_SESSION['school_id']."";
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
                $fee_issue_dr_coa_id = $value_fee['issue_dr_coa_id'];
                $fee_issue_cr_coa_id = $value_fee['issue_cr_coa_id'];
                $fee_chalan_form_number = $value_fee['chalan_form_number'];
                $transaction_detail = student_name_section($value_fee['student_id']);
                // $s_c_f_id_fee =  $value['fee_type_id'];
                $s_c_d_id = $value_fee['s_c_d_id'];
                $discount_amt = $this->is_discount_fee($s_c_d_id, $s_c_f_id);
                $student_id = $value_fee['student_id'];
               /* latest change in issues 10-03-2018 start */

                $fee_amount_temp = $fee_amount;
                if($discount_amt>0)
                {
                    $discount_amt = round((($discount_amt * $fee_amount) / 100));
                    $fee_amount_temp = $fee_amount_temp-$discount_amt;
                }
                
                $str_discount = "SELECT scf_discount.student_id,
                                    scf_discount.chalan_form_number,
                                    scfd_discount.amount,
                                    scfd_discount.fee_type_title, 
                                    scfd_discount.type_id as scfd_fee_id,
                                    scfd_discount.issue_cr_coa_id,
                                    scfd_discount.school_id,
                                    d.discount_id,
                                    f.fee_type_id as fee_id, 
                                    d.title
                                    FROM " . get_school_db() . ".discount_list as d
                                    INNER join  " . get_school_db() . ".fee_types as f ON f.fee_type_id = d.fee_type_id
                                    INNER JOIN " . get_school_db() . ".student_chalan_detail as scfd_discount ON scfd_discount.type_id = d.discount_id
                                    INNER JOIN " . get_school_db() . ".student_chalan_form as scf_discount ON scf_discount.s_c_f_id = scfd_discount.s_c_f_id
                                    WHERE f.fee_type_id = $fee_type_id_fee
                                    AND scfd_discount.s_c_f_id =$s_c_f_id
                                    AND scfd_discount.school_id = " . $_SESSION['school_id'] . "
                                    AND scfd_discount.type = 2";
                $query_discount = $this->db->query($str_discount)->result_array();

                if (count($query_discount) == 0)
                {
                    // $array_ledger_fee = array(
                    //     'entry_date' => $entry_date,
                    //     'detail' => $fee_type_title
                    //         . ' - ' . get_phrase('issued_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('to') . " - " . $transaction_detail,
                    //     'debit' => $fee_amount - $discount_amt,
                    //     'entry_type' => 3,
                    //     'type_id' => $s_c_f_id,
                    //     'student_id' => $value_fee['student_id'],
                    //     'school_id' => $school_id,
                    //     'coa_id' => $fee_issue_dr_coa_id
                    // );
                    // $this->db->insert(get_school_db().".journal_entry", $array_ledger_fee);
                    
                    $array_ledger_reamining = array(
                        'entry_date' => $entry_date,
                        'detail' => $fee_type_title
                            . " - " . get_phrase('issued_challan_form') . " - " . $discount_chalan_form_number . " - " . get_phrase(' to ') . " - " . $transaction_detail,
                        'credit' => $fee_amount,
                        'entry_type' => 1,
                        'type_id' => $s_c_f_id,
                        'school_id' => $_SESSION['school_id'],
                        'student_id' => $value_fee['student_id'],
                        'coa_id' => $fee_issue_cr_coa_id
                    );    
                    $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_reamining);
                    
                }
                if (count($query_discount) > 0) {
                    foreach ($query_discount as $key_discount => $value_discount)
                    {
                        /*create array for add fee only start*/
                        $discount_type_id_fee = $value_discount['type_id'];
                        $discount_type_title = $value_discount['fee_type_title'];
                        $discount_amount = round((($fee_amount * $value_discount['amount']) / 100));
                        $total_discount_amount = $total_discount_amount + $discount_amount;
                        $discount_school_id = $value_discount['school_id'];
                        $discount_issue_cr_coa_id = $value_discount['issue_cr_coa_id'];
                        $discount_chalan_form_number = $value_discount['chalan_form_number'];
                        $transaction_detail = student_name_section($value_discount['student_id']);
                        $percentage_amount = $value_discount['amount'];
                        // $s_c_f_id_fee =  $value['fee_type_id'];
                        
                        $array_ledger_discount = array(
                            'entry_date' => $entry_date,
                            'detail' => $discount_type_title . ' (' . $percentage_amount . ' %)'
                                . " - " . get_phrase('Discount_chalan_form') . ' - ' . $discount_chalan_form_number . " - " . get_phrase('to') . " - " . $transaction_detail,
                            'debit' => $discount_amount,
                            'entry_type' => 1,
                            'type_id' => $s_c_f_id,
                            'student_id' => $value_fee['student_id'],
                            'school_id' => $discount_school_id,
                            'coa_id' => $discount_issue_dr_coa_id
                        );
                        $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_discount);
                        
                        // Fee Type JE    
                        $array_ledger_reamining = array(
                            'entry_date' => $entry_date,
                            'detail' => $fee_type_title
                                . " - " . get_phrase('issued_challan_form') . " - " . $discount_chalan_form_number . " - " . get_phrase(' to ') . " - " . $transaction_detail,
                            'credit' => $fee_amount,
                            'entry_type' => 1,
                            'type_id' => $s_c_f_id,
                            'school_id' => $_SESSION['school_id'],
                            'student_id' => $value_fee['student_id'],
                            'coa_id' => $fee_issue_cr_coa_id
                        );    
                        $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_reamining);

                    }

                }

                $remanining_amount = $grand_total_amount - $total_discount_amount;
            } //End Loop
            
            // Debit Journal Entry Challan Issued (Zeeshan)
            $array_ledger_fee = array(
                'entry_date' => $entry_date,
                'detail' => "Total Fee "
                    . ' - ' . get_phrase('issued_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('to') . " - " . $transaction_detail,
                'debit' => $remanining_amount,
                'entry_type' => 1,
                'type_id' => $s_c_f_id,
                'school_id' => $school_id,
                'student_id' => $value_fee['student_id'],
                'coa_id' => $fee_issue_dr_coa_id
            );
            $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_fee);
            // Debit Journal Entry CHallan Issued (Zeeshan)
            
        }
        
        //--------------Send SMS On Challan Issue-------------------
        //----------------------------------------------------------
        $this->load->helper('message');            
        $student_info = get_student_info($student_id);
        $student_name = $student_info[0]['student_name'];
        $mob_num = $student_info[0]['mob_num'];
        $email = $student_info[0]['email'];
        $student_id = $student_info[0]['student_id'];
        $message = "Dear Parent, your Child ".$student_name."'s fee is due by ".date_view($dues_date).", for more details please login to ".base_url()."login .";
        $response = send_sms($mob_num, 'Indici_edu', $message, $student_id,4);
    
        //--------------Send SMS On Challan End-------------------
        //----------------------------------------------------------
        
        // Send Email Challan Issue
        $email_message = "<b>Dear Parent</b> <br><br> Most respectfully, it is stated that an amount of ".$fee_amount_temp.", tuition fee for the month of ".date_view($dues_date)." is pending against school fee of your son/daughter ".$student_info[0] ['student_name']." We earnestly request you to kindly settle the payment as early as possible so that ".$student_info[0] ['student_name']." can smoothly continue his studies at our prestigious institute. Your prompt attention in this matter will be highly appreciated. <br> To view the monthly challan, you are requested to logon to ".base_url()."<br>
        <br> In case of any query, you may please contact with school adminitrator";
        
        // $to_email = $email;
        $to_email = $student_info[0]['email'];
        $subject = "Monthly Challan Issued";
        $email_layout = get_email_layout($email_message);
        email_send("No Reply", "Indici Edu", $to_email, $subject, $email_layout, 4);
        
        $device_id  =   get_user_device_id(6 , $student_id , $_SESSION['school_id']);
        $title      =   "Monthly Challan Issued";
        $message    =   "A New Challan Form has been issued.";
        $link       =    base_url()."parents/invoice";
        sendNotificationByUserId($device_id, $title, $message, $link , $student_id , 6);
        // Send Email Challan Issue

            $this->db->where("s_c_f_id",$s_c_f_id);
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->update(get_school_db().".student_chalan_form",$data);
            $form_type=$this->input->post('form_type');
            $student_id=$this->input->post('student_id');
            $num=0;
            if($form_type==3){
                $num=11;
            }
            elseif($form_type==4){
                $num=13;
            }
            elseif($form_type==5){

                $this->db->query("update ".get_school_db().".transfer_student set status=5 where s_c_f_id=$s_c_f_id");
            }
            elseif($form_type==7){
                $num=22;
            }
            elseif($form_type==6)
            {
                //$num=22;
                /*$s_c_f_id=$this->input->post('s_c_f_id');
                $student_id=$this->input->post('student_id');

                $status_arr=array(
                'status'=>$num
                );
                $this->db->where('student_id',$student_id);
                $this->db->where('school_id',$_SESSION['school_id']);
                $this->db->where('s_c_f_id',$s_c_f_id);
                $this->db->update(get_school_db().'.student_withdrawal',$status_arr);*/
            }
            elseif($form_type==1)
            {
                $num=6;
            }
            elseif($form_type==2)
            {
                $num=0;
            }
            if($status_val==4)
            {
                $num=22;
                $s_c_f_id=$this->input->post('s_c_f_id');
                $student_id=$this->input->post('student_id');
                $status_arr=array(
                    'status'=>$num
                );
                $this->db->where('student_id',$student_id);
                $this->db->where('school_id',$_SESSION['school_id']);
                $this->db->where('s_c_f_id',$s_c_f_id);
                $this->db->update(get_school_db().'.student_withdrawal',$status_arr);
            }

            if($num>0)
            {

                $data_student['student_status']=$num;
                $school_id=$_SESSION['school_id'];
                $where_ary=array('school_id'=>$school_id,
                    'student_id'=>$student_id
                );
                $this->db->where($where_ary);
                $this->db->update(get_school_db().".student",$data_student);
            }

            $this->load->helper("student");
            student_archive($_SESSION['login_detail_id'],$student_id);

            $this->session->set_flashdata('operation_info', get_phrase('challan_form_issued_successfully'));
            redirect($_SERVER['HTTP_REFERER']);

    }

    function is_discount_fee($s_c_d_id, $s_c_f_id)
    {
        // Commented By ZA
        // AND scfd_discount.related_s_c_d_id = $s_c_d_id
        $school_id = $_SESSION['school_id'];
        $str_discount = "SELECT scfd_discount.amount FROM " . get_school_db() . ".student_chalan_detail as scfd_discount WHERE scfd_discount.s_c_f_id =$s_c_f_id
                          AND scfd_discount.school_id = $school_id AND scfd_discount.type = 2";
        $query_discount = $this->db->query($str_discount)->result_array();

        if(count($query_discount)>0)
        {
            $discount_amount = $query_discount[0]['amount'];
            return $discount_amount;
        }
        else
        {
            return 0;
        }

    }
    
    function view_print_chalan($s_c_f_id,$typp ){

        if($s_c_f_id=="" || $typp==""){
            $this->session->set_flashdata('club_updated',get_phrase('chalan_form_not_created_yet'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        else
        {
            $school_id=$_SESSION['school_id'];
            $page_data["query_ary"]=$this->db->query("select * from ".get_school_db().".student_chalan_form 
            where s_c_f_id=$s_c_f_id and school_id=$school_id and is_cancelled = 0 and status>3")->result_array();
            $page_data['page_name']  = 'view_print_chalan';
            $page_data['page_type'] = 'single';
            $page_data['page_title'] = get_phrase('chalan_form');
            $this->load->view('backend/index', $page_data);
        }
    }
    
    

    function view_print_chalan_2($s_c_f_id,$typp){

        if($s_c_f_id=="" || $typp==""){
            $this->session->set_flashdata('club_updated',get_phrase('chalan_form_not_created_yet'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        else
        {
            $school_id=$_SESSION['school_id'];
            $page_data["query_ary"]=$this->db->query("select * from ".get_school_db().".student_chalan_form where s_c_f_id=$s_c_f_id and school_id=$school_id and is_cancelled = 0 and status>3")->result_array();
            $page_data['page_name']  = 'view_print_chalan_2';
            $page_data['page_type'] = 'single';
            $page_data['page_title'] = get_phrase('chalan_form');
            $this->load->view('backend/index', $page_data);
        }

        $page_data['page_name']  = 'unpaid_students_print';
        $page_data['page_title'] = get_phrase('unpaid_students_print');
        $this->load->library('pdf');
        $view = 'backend/admin/unpaid_students_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        
    }


    function view_print_chalan_class($section_id,$month,$year){
        if($section_id==""){
            $this->session->set_flashdata('club_updated',get_phrase('chalan_form_not_created'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        else
        {
            $school_id=$_SESSION['school_id'];
            $qur=$this->db->query("select * from ".get_school_db().".bulk_request 
            where section_id=$section_id and school_id = ".$_SESSION['school_id']." and status=1 and activity in (3,4,5)")->result_array();
            $bulk_req_id=$qur[0]['bulk_req_id'];
            $page_data["query_ary"]=$this->db->query("select * from ".get_school_db().".student_chalan_form where bulk_req_id= $bulk_req_id and school_id=$school_id and is_cancelled = 0")->result_array();
            $page_data['page_name']  = 'view_print_chalan';
            $page_data['page_title'] = get_phrase('chalan_form');
            $this->load->view('backend/index', $page_data);
        }
    }

    function student_chalan_payment()
    {
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $s_c_f_id=$this->input->post('s_c_f_id');
        $receieved_amount_in_cash = $this->input->post('received_amount');
        if($receieved_amount_in_cash == ""){
            $receieved_amount_in_cash = 0;    
        }
        $data['received_amount'] = $this->input->post('received_amount');
        $actual_amu=$this->input->post('actual_amu');
        $other_amount=$this->input->post('other_amount');
        $school_id = $_SESSION['school_id'];

        /* may be ite deleted start */
        $s_c_f_id_str = "SELECT student_id , chalan_form_number from ".get_school_db().".student_chalan_form where s_c_f_id = ".$s_c_f_id." AND is_cancelled = 0 AND school_id = " . $_SESSION['school_id']; 
        $s_c_f_id_row = $this->db->query($s_c_f_id_str)->row();
        $student_id =  $s_c_f_id_row->student_id;
        $chalan_form_number =  $s_c_f_id_row->chalan_form_number;
        
        /* may be ite deleted start */

        // Check If Challan Exists Or Not
        $str = "SELECT scd.fee_type_title , scd.amount , scd.school_id , scd.receive_dr_coa_id ,scd.receive_cr_coa_id FROM 
                    ".get_school_db().".student_chalan_form as scf INNER JOIN ".get_school_db().".student_chalan_detail as scd ON 
                    scf.s_c_f_id = scd.s_c_f_id WHERE scd.s_c_f_id = ". $s_c_f_id." AND scd.school_id = ".$_SESSION['school_id']."
                    AND scf.is_cancelled = 0 AND (scd.receive_dr_coa_id = 0) AND scd.type = 1";
        $query = $this->db->query($str);

        if(count($query->result()) > 0)
        {
            $this->session->set_flashdata('journal_entry',get_phrase('challan_form_not_issued_incomplete_chart_of_account_settings'));
            redirect(base_url() . 'c_student/student_information/');
        }
        else
        {
            // If Other Amount > 0 Then Run This Script
            if ($other_amount > 0)
            {
                $data_other['fee_type_title']=$this->input->post('other_title');
                $data_other['amount']= $this->input->post('other_amount');

                $data_other['school_id']=$_SESSION['school_id'];
                $data_other['s_c_f_id']=$s_c_f_id;
                $data_other['type']=6;
                $get_val1 = $this->db->query("select * from " . get_school_db() . ".misc_challan_coa_settings where receive_dr_coa_id>0
                                              AND receive_cr_coa_id>0 AND school_id=" . $_SESSION['school_id'] . " and type='late_fee_fine_coa'")->result_array();
                if(count($get_val1)>0)
                {
                    $rec_debit_late_fine_coa = $get_val1[0]['receive_dr_coa_id'];
                    $rec_credit_late_fine_coa = $get_val1[0]['receive_cr_coa_id'];
                    
                    $data_other['generate_dr_coa_id'] = $get_val1[0]['generate_dr_coa_id'];
                    $data_other['generate_cr_coa_id'] = $get_val1[0]['generate_cr_coa_id'];
                    $data_other['issue_dr_coa_id'] = $get_val1[0]['issue_dr_coa_id'];
                    $data_other['issue_cr_coa_id'] = $get_val1[0]['issue_cr_coa_id'];
                    $data_other['receive_dr_coa_id'] = $get_val1[0]['receive_dr_coa_id'];
                    $data_other['receive_cr_coa_id'] = $get_val1[0]['receive_cr_coa_id'];
                    $data_other['cancel_dr_coa_id'] = $get_val1[0]['cancel_dr_coa_id'];
                    $data_other['cancel_cr_coa_id'] = $get_val1[0]['cancel_cr_coa_id'];
                    $data_other['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db() . '.student_chalan_detail', $data_other);
                }
            }

            // $date = new DateTime();
            $entry_date = date_slash($this->input->post("payment_date"));

            $fee_amount = 0;
            $total_discount_amount = 0;

            $str_fee = "SELECT scf_fee.student_id,scf_fee.chalan_form_number, scf_fee.chalan_form_number, scfd_fee.* FROM ".get_school_db().".student_chalan_detail as scfd_fee 
                        INNER JOIN ".get_school_db().".student_chalan_form as scf_fee ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id 
                        WHERE scfd_fee.s_c_f_id = $s_c_f_id AND scfd_fee.type = 1 AND scfd_fee.school_id = ".$_SESSION['school_id']."";
            $query_fee = $this->db->query($str_fee)->result_array();
            
            $sum_total_amount = "SELECT SUM(scfd_fee.amount) AS total_amount FROM ".get_school_db().".student_chalan_detail as scfd_fee 
                                INNER JOIN ".get_school_db().".student_chalan_form as scf_fee ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id 
                                WHERE scfd_fee.s_c_f_id = $s_c_f_id AND scfd_fee.type = 1 AND scfd_fee.school_id = ".$_SESSION['school_id']."";
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
                    $transaction_detail = student_name_section($value_fee['student_id']);

                    /* latest change in issues 10-03-2018 start */
                    $s_c_d_id = $value_fee['s_c_d_id'];
                    $discount_amt = $this->is_discount_fee($s_c_d_id, $s_c_f_id);

                    $fee_amount_temp = $fee_amount;

                    if($discount_amt>0)
                    {
                        $discount_amt = round((($discount_amt * $fee_amount) / 100));
                        $fee_amount_temp = $fee_amount_temp-$discount_amt;
                    }

                    /* latest change in issues 10-03-2018 end */
                    
                    
                    //   Discount Journal Entry
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
                                    FROM " . get_school_db() . ".discount_list as d
                                    INNER join  " . get_school_db() . ".fee_types as f ON f.fee_type_id = d.fee_type_id
                                    INNER JOIN " . get_school_db() . ".student_chalan_detail as scfd_discount ON scfd_discount.type_id = d.discount_id
                                    INNER JOIN " . get_school_db() . ".student_chalan_form as scf_discount ON scf_discount.s_c_f_id = scfd_discount.s_c_f_id
                                    WHERE f.fee_type_id = $fee_type_id_fee
                                    AND scfd_discount.s_c_f_id =$s_c_f_id
                                    AND scfd_discount.school_id = " . $_SESSION['school_id'] . "
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
                                   . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                               'credit' => $fee_amount,
                               'entry_type' => 3,
                               'type_id' => $s_c_f_id,
                               'student_id' => $value_fee['student_id'],
                               'school_id' => $fee_school_id,
                               'coa_id' => $fee_receive_cr_coa_id
                           );
                        //   $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_fee);
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
                            // $discount_issue_cr_coa_id = $value_discount['issue_cr_coa_id'];
                            $discount_receive_dr_coa_id = $value_discount['issue_dr_coa_id'];
                            $discount_chalan_form_number = $value_discount['chalan_form_number'];
                            $transaction_detail = student_name_section($value_discount['student_id']);
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

                            // $this->db->insert(get_school_db().".journal_entry", $array_ledger_discount);
                            
                            // Credit Journal Entry Challan Recieved (Zeeshan)
                                $array_ledger_fee = array(
                                   'entry_date' => $entry_date,
                                   'detail' => $fee_type_title
                                       . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                                   'credit' => $fee_amount - $discount_amount,
                                   'entry_type' => 3,
                                   'type_id' => $s_c_f_id,
                                   'student_id' => $value_fee['student_id'],
                                   'school_id' => $fee_school_id,
                                   'coa_id' => $fee_receive_cr_coa_id
                                );
                            //   $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_fee);   
                        }

                    }
                    /*recent change discount end*/

                    $remanining_amount = $grand_total_amount-$total_discount_amount;
                    // $total_fee_amount_recieved += $remanining_amount;
                    
                    // $total_discount_amount = 0;
                    // $fee_amount = 0;
                } //end main loop
                
                // Credit Journal Entry Challan Recieved (Zeeshan)
                $array_ledger_fee_debit = array(
                    'entry_date' => $entry_date,
                    'detail' => "Total Fee "
                        . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                    'credit' => $receieved_amount_in_cash,
                    'entry_type' => 3,
                    'type_id' => $s_c_f_id,
                    'student_id' => $student_id,
                    'school_id' => $school_id,
                    'coa_id' => $fee_receive_cr_coa_id
                );
                $this->db->insert(get_school_db().".journal_entry", $array_ledger_fee_debit);
                // End Total Credit JE
                
                // Debit Journal Entry Challan Recieved (Zeeshan)
                $array_ledger_fee_debit = array(
                    'entry_date' => $entry_date,
                    'detail' => "Total Fee "
                        . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                    'debit' => $receieved_amount_in_cash,
                    'entry_type' => 3,
                    'type_id' => $s_c_f_id,
                    'student_id' => $student_id,
                    'school_id' => $school_id,
                    'coa_id' => $fee_receive_dr_coa_id
                );
                $this->db->insert(get_school_db().".journal_entry", $array_ledger_fee_debit);
                // End Total Debit JE
                
                ///////////////////////////
                //Late Fee Fine Journal Entry
                ///////////////////////////
                
                if($other_amount > 0)
                {
                
                    // Credit Journal Entry Challan Late Fee Fine
                    $array_ledger_cr_fine = array(
                       'entry_date' => $entry_date,
                       'detail' => "Late Fee Fine"
                           . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                       'credit' => $other_amount,
                       'entry_type' => 4,
                       'type_id' => $s_c_f_id,
                       'student_id' => $student_id,
                       'school_id' => $fee_school_id,
                       'coa_id' => $rec_credit_late_fine_coa
                   );
                   $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_cr_fine);
                   
                   // Debit Journal Entry Challan Late Fee Fine
                    $array_ledger_db_fine = array(
                       'entry_date' => $entry_date,
                       'detail' => "Late Fee Fine"
                           . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                       'debit' => $other_amount,
                       'entry_type' => 4,
                       'type_id' => $s_c_f_id,
                       'student_id' => $student_id,
                       'school_id' => $fee_school_id,
                       'coa_id' => $rec_debit_late_fine_coa
                   );
                   $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_db_fine);
                }
                
            }

            /* Add arears in journal enntry start */
            $arrear_str = "SELECT scf_fee.student_id,scf_fee.chalan_form_number,
                            scf_fee.chalan_form_number, scfd_fee.* 
                            FROM ".get_school_db().".student_chalan_detail as scfd_fee
                            INNER JOIN ".get_school_db().".student_chalan_form as scf_fee ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id 
                            WHERE scfd_fee.s_c_f_id = $s_c_f_id
	                        AND scfd_fee.type = 3
	                        AND scfd_fee.school_id = ".$_SESSION['school_id']."";

            $arrear_query = $this->db->query($arrear_str)->result_array();

            if(count($arrear_query)>0)
            {
                foreach ($arrear_query as $key_arears => $value_arear)
                {
                    /* create array for add fee only start */
                    $fee_type_id_fee = $value_arear['type_id'];
                    $arear_type_title = $value_arear['fee_type_title'];
                    $arear_amount = $value_arear['amount'];
                    $arear_school_id = $value_arear['school_id'];
                    $arear_receive_dr_coa_id = $value_arear['receive_dr_coa_id'];
                    $arear_receive_cr_coa_id = $value_arear['receive_cr_coa_id'];
                    $arear_chalan_form_number = $value_arear['chalan_form_number'];
                    $transaction_detail = student_name_section($value_fee['student_id']);

                    // $s_c_f_id_fee =  $value['fee_type_id'];

                    $array_ledger_arears = array(
                        'entry_date' => $entry_date,
                        'detail' => $arear_type_title
                            . ' - ' . get_phrase('arears_receive_challan_form') . " - " . $arear_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                        'credit' => $arear_amount,
                        'entry_type' => 3,
                        'type_id' => $s_c_f_id,
                        'school_id' => $arear_school_id,
                        'student_id' => $value_fee['student_id'],
                        'coa_id' => $arear_receive_cr_coa_id
                    );

                    $array_debit_arears = array(
                        'entry_date' => $entry_date,
                        'detail' => $arear_type_title
                            . ' - ' . get_phrase('arears_receive_challan_form') . " - " . $arear_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                        'debit' => $arear_amount,
                        'entry_type' => 3,
                        'type_id' => $s_c_f_id,
                        'student_id' => $value_fee['student_id'],
                        'school_id' => $arear_school_id,
                        'coa_id' => $arear_receive_dr_coa_id
                    );

                    /* create array for add fee only End */

                    // $this->db->insert(get_school_db().".journal_entry", $array_ledger_arears);
                    // $this->db->insert(get_school_db().".journal_entry", $array_debit_arears);
                }
                
                // Previous Arrea Status 0
                
            }
            /*Add arears in journal enntry end */
            /* recieve new end*/
                $student_id = $this->input->post('student_id');
                
                $this->db->set('arrears_status', 0);
                $this->db->where('school_id', $_SESSION['school_id']);
                $this->db->where('student_id', $student_id);
                $this->db->update(get_school_db() . ".student_chalan_form");
            /* End of student_chalan_form arrears status will be zero */
            if ($data['received_amount'] > $actual_amu)
            {

                //$arrears_val=$actual_amu-$data['received_amount'];
            }
            else
            {
                $arrears_val = $actual_amu - $data['received_amount'];
                if ($arrears_val == 0)
                {
                    $arrears_status = 0;
                } else
                {
                    $arrears_status = 1;
                    /* arrears setting start */
                    $geneerate_setting_val=$this->db->query("select * from ".get_school_db().".misc_challan_coa_settings where school_id=".$_SESSION['school_id']." and type='arrears_coa'")->row();
                        if ($arrears_val > 0)
                        {
                            $transaction_detail = student_name_section($student_id);
       
                            $amount = $arrears_val;
                            $data_debit = array(
                                'entry_date' => $entry_date,
                                'detail' => get_phrase('arrears_generation').' - '.get_phrase('challan_form')."- " . $row->chalan_form_number . " - ".get_phrase('from') ."-" .$transaction_detail,
                                'debit' => $amount,
                                'entry_type' => 5,
                                'type_id' => $s_c_f_id,
                                'student_id' => $value_fee['student_id'],
                                'school_id' => $school_id,
                                'coa_id' => $geneerate_setting_val->generate_dr_coa_id//$row->cancel_dr_coa_id
                            );
                            $data_credit = array(
                                'entry_date' => $entry_date,
								'detail' => get_phrase('arrears_generation').' - '.get_phrase('challan_form')." - " . $row->chalan_form_number . " - ".get_phrase('from') ."-" .$transaction_detail,
                                
                                'credit' => $amount,
                                'entry_type' => 5,
                                'type_id' => $s_c_f_id,
                                'student_id' => $value_fee['student_id'],
                                'school_id' => $school_id,
                                'coa_id' => $geneerate_setting_val->generate_cr_coa_id//$row->cancel_cr_coa_id
                            );
                            // $this->db->insert(get_school_db() . ".journal_entry", $data_debit);
                            // $this->db->insert(get_school_db() . ".journal_entry", $data_credit);
                        }

                    /* arrears setting End */
                }

                $data['arrears'] = $arrears_val;
            }
            $data['arrears_status'] = $arrears_status;
            $data['received_by'] = $_SESSION['login_detail_id'];
            $data['received_date'] = date('Y-m-d h:i:s');
            $payment_date = $this->input->post('payment_date');
            $data['payment_date'] = date_slash($payment_date);
            $data['status'] = 5;
            
            // Previous Chalan Paid
            
            $prev_scfid_data['arrears_status'] = $arrears_status;
            $prev_scfid_data['received_by'] = $_SESSION['login_detail_id'];
            $prev_scfid_data['received_date'] = date('Y-m-d h:i:s');
            $prev_scfid_data['payment_date'] = date_slash($payment_date);
            $prev_scfid_data['status'] = 5;
            $prev_scfid_data['arrears_status'] = 0;
            
            $prev_s_c_f_id = $_POST['prev_challan_s_c_f_id'];
            for($i=0; $i< count($prev_s_c_f_id); $i++)
            {
                // Previous Challan Paid Query
                $this->db->where("s_c_f_id", $prev_s_c_f_id[$i]);
                $this->db->where('school_id', $_SESSION['school_id']);
                $this->db->where('is_cancelled', 0);
                $this->db->update(get_school_db() . ".student_chalan_form", $prev_scfid_data);
            }
            
            $this->db->where("s_c_f_id", $s_c_f_id);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->update(get_school_db() . ".student_chalan_form", $data);

            $this->load->helper('message');
            // print_r($s_c_f_id_row);echo $s_c_f_id_row->student_id;exit;
            $sms_ary = get_sms_detail();
            $message = "Amount of Rs. " . $data['received_amount'] . " received from " . $sms_ary['student_name'] . ".";
            if (isset($_POST['send_message']) && $_POST['send_message'] != "") {
                send_sms($sms_ary['mob_num'], 'Indici Edu', $message, $student_id,1);
            }
 
            if (isset($_POST['send_email']) && $_POST['send_email'] != "")
            {
                $to_email = $sms_ary['email'];
                $subject = "Fee Received";
                $email_message = "<b>Dear Parents </b><br><br> Amount of Rs. " . $data['received_amount'] . " received from " . $sms_ary['student_name'] . " <br>
                In case of any query feel free to contact us at info@indiciedu.com.pk.";
                $email_layout = get_email_layout($email_message);
                email_send("No Reply", "Indici Edu", $to_email, $subject, $email_layout, $student_id,1);
            }

            $form_type = $this->input->post('form_type');
            $num = 0;
            if ($form_type == 3)
            {
                $num = 12;
                if (isset($_POST['study_pack'])) {
                    $num = 13;
                }
            }
            elseif ($form_type == 4)
            {
                $num = 16;
                if (isset($_POST['study_pack'])) {
                    $num = 17;
                }
            }
            elseif ($form_type == 5)
            {
                $this->db->query("update " . get_school_db() . ".transfer_student set status=6 where s_c_f_id=$s_c_f_id");
            }
            elseif ($form_type == 7) {
                $this->db->query("update " . get_school_db() . ".transfer_student set status=10 where status=9 and r_s_c_f_id=$s_c_f_id");
                $num = 0;
            }
            elseif ($form_type == 6)
            {
                $num = 22;
                $student_id = $this->input->post('student_id');
                $data_status['status'] = $num;
                $where = array('school_id' => $_SESSION['school_id'],'s_c_f_id' => $s_c_f_id,'student_id' => $student_id);
                $this->db->where($where);
                $this->db->update(get_school_db() . ".student_withdrawal", $data_status);
            }
            elseif ($form_type == 1)
            {
                $num = 7;
                if (isset($_POST['study_pack'])) {
                    $num = 8;
                }
            } elseif ($form_type == 2) {
                $num = 0;
            }
            if ($num > 0)
            {
                $data_student['student_status'] = $num;
                $school_id = $_SESSION['school_id'];
                $where_ary = array('school_id' => $school_id,'student_id' => $student_id);
                $this->db->where($where_ary);
                $this->db->update(get_school_db() . ".student", $data_student);
            }
            $this->load->helper("student");
            student_archive($_SESSION['login_detail_id'], $student_id);
            $this->session->set_flashdata('club_updated', get_phrase('payement_received.'));
        }
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        
        $this->session->set_flashdata('operation_info', get_phrase('challan_form_recieved_successfully'));
        redirect($_SERVER['HTTP_REFERER']);
    }

    function study_pack($student_id){

        $data['student_status']=8;
        $school_id=$_SESSION['school_id'];
        $where_ary=array('school_id'=>$school_id, 'student_id'=>$student_id);
        $this->db->where($where_ary);
        $this->db->update(get_school_db().".student",$data);
        $this->load->helper("student");
        student_archive($_SESSION['login_detail_id'],$student_id);
        $this->session->set_flashdata('club_updated',get_phrase('study_pack_delivered'));
        redirect(base_url() . 'c_student/student_pending');
    }

    function approve_admission(){

        $data['student_status']=$this->input->post('student_status');
        $data['roll']=$this->input->post('roll');
        $data['adm_term_id']=$this->input->post('adm_term_id');
        $data['reg_num']=$this->input->post('reg_num');
        $data['academic_year_id']=$this->input->post('academic_year_id');
        $student_id=$this->input->post('student_id');
        $this->session->set_flashdata('club_updated',get_phrase('student_approval_requested'));
        $data['adm_date'] = $this->input->post('adm_date');

        if($data['student_status']==10){
        	$data['date_confirmed']=date('Y-m-d h:i:sa');
        	$data['confirmed_by']=$_SESSION['login_detail_id'];

            $this->session->set_flashdata('club_updated','Student Admission Confirmed');
            $this->load->helper('message');
            $sms_ary=get_sms_detail($student_id);
            //echo '<pre>'; print_r($sms_ary); exit;
            $message="Admission approved for student ".$sms_ary['student_name']."  ";
            $message.="Parent Sign up URL: ".base_url() . 'login';
                ///sms start
            if(isset($_POST['send_message']) && $_POST['send_message']!="") {
                send_sms($sms_ary['mob_num'],'Indici Edu',$message,$student_id,4);
            }
                //Email Setting her
            if(isset($_POST['send_email']) && $_POST['send_email']!="") {
                $subject="Admission Approved";
                $email_message = "<b>Dear Parents </b><br><br> Admission approved for student ".$sms_ary['student_name']. " <br>
                In case of any query feel free to contact us at info@indiciedu.com.pk.";
                $email_layout = get_email_layout($email_message);
                email_send("No Reply","Indici Edu",$sms_ary['email'],$subject,$email_layout,$student_id,4);
            }
        }

        $school_id=$_SESSION['school_id'];
        $where_ary=array('school_id'=>$school_id,'student_id'=>$student_id );
        $this->db->where($where_ary);
        $this->db->update(get_school_db().".student",$data);
        $this->load->helper("student");
        student_archive($_SESSION['login_detail_id'],$student_id);
        redirect(base_url() . 'c_student/student_pending');

    }

    function chalan_settings($param1 = '')
    {

        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        if ($param1 == 'do_update') {

            $data['school_name']=$this->input->post('school_name');
            $data['address']=$this->input->post('address');
            $data['bank_details']=$this->input->post('bank_details');
            $data['terms'] = $this->input->post('terms');
            $data['school_id']=$_SESSION['school_id'];
            $chalan_setting_id=$this->input->post('chalan_setting_id');
            $data['chalan_setting_id']=$chalan_setting_id;
            $del_logo = $this->input->post('del_logo');
            $logo=$_FILES['logo']['name'];

            if($logo==""){

                $this->db->where('school_id',$_SESSION['school_id']);
                $this->db->where('chalan_setting_id',$chalan_setting_id);
                $this->db->update(get_school_db().'.chalan_settings',$data);
                $this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
                redirect(base_url() . 'class_chalan_form/chalan_settings/');

            }

            else
            {

                if($del_logo!=""){
                    $del_location=system_path($del_logo,'');
                    file_delete($del_location);
                }
                $data['logo'] =file_upload_fun("logo","","c_setting");
                $this->db->where('school_id',$_SESSION['school_id']);
                $this->db->where('chalan_setting_id',$chalan_setting_id);
                $this->db->update(get_school_db().'.chalan_settings',$data);
                $this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
                redirect(base_url() . 'class_chalan_form/chalan_settings/');

            }

        }

        if ($param1 == 'do_insert') {

            $data['school_name']=$this->input->post('school_name');
            $data['address']=$this->input->post('address');
            $data['bank_details']=$this->input->post('bank_details');
            $data['terms'] = $this->input->post('terms');
            $data['school_id']=$_SESSION['school_id'];
            $chalan_setting_id=$this->input->post('chalan_setting_id');
            $data['chalan_setting_id']=$chalan_setting_id;
            $del_logo = $this->input->post('del_logo');
            $logo=$_FILES['logo']['name'];

            if($del_logo!=""){

                $del_location=system_path($del_logo,'');
                file_delete($del_location);
            }

            if($logo!=""){
                $data['logo']=file_upload_fun("logo","","c_setting");
            }

            $data['school_id']=$_SESSION['school_id'];
            $this->db->insert(get_school_db().'.chalan_settings',$data);
            $this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
            redirect(base_url().'class_chalan_form/chalan_settings/');
        }

        $page_data['page_name']  = 'chalan_settings';
        $page_data['system_name']  = '';
        $page_data['page_title'] = get_phrase('challan_settings');
        $this->db->where('school_id',$_SESSION['school_id']);
        $page_data['settings']=$this->db->get(get_school_db().'.chalan_settings')->result_array();
        $this->load->view('backend/index', $page_data);

    }

    function get_class()
    {
        echo  class_option_list($this->input->post('department_id'),$this->input->post('class_id'));
    }

    function get_class_section()
    {
        echo section_option_list($this->input->post('class_id'));
    }

    function class_challan_generator()
    {
        $data['departments_id']=$this->input->post('departments_id');
        $data['class_id']=$this->input->post('class_id');
        $data['section_id']=$this->input->post('section_id');
        $this->load->view('backend/admin/ajax/class_challan_ajax.php',$data);
    }

    function accordion_generator()
    {
        $this->load->view('backend/admin/ajax/challan_inner_div.php');
    }

    function get_fee_amount($fee_id,$amount ,	$s_c_d_id)
    {

            return   $q = "select  d.discount_id as id , d.title as discount_title , fee.title as fee_title, scfd.amount as amount_detail from ".get_school_db().".fee_types as fee
            INNER JOIN ".get_school_db().".discount_list as d on d.fee_type_id = fee.fee_type_id
                    INNER JOIN ".get_school_db().".student_chalan_detail as scfd on scfd.type_id = fee.fee_type_id
                    WHERE fee.fee_type_id in($fee_id)
                    and scfd.type = 1
                    and scfd.s_c_d_id = 	s_c_d_id";

        //  exit;
        // $amount_query=$this->db->query($amount_query_str)->row();
        // $perc_amount = ((950*$amount) / 100);*/
      //  echo "hello";

    }

    function delete_bulk_ccf()
    {

        $school_id = $_SESSION['school_id'];
        $c_c_f_id=$this->input->post('c_c_f_id');
        $section_id=$this->input->post('section_id');

        $delete_query_str = "select ccf.c_c_f_id from ".get_school_db().".class_chalan_form as ccf
                            INNER JOIN ".get_school_db().".student_chalan_form as sccf on sccf.c_c_f_id = ccf.c_c_f_id
                            where ccf.c_c_f_id = $c_c_f_id AND ccf.school_id = $school_id";
        $delete_query = $this->db->query($delete_query_str)->result_array();

        if(count($delete_query) == 0)
        {

            $this->db->where('school_id',$school_id);
            $this->db->where('c_c_f_id',$c_c_f_id);
            $query1=$this->db->delete(get_school_db().'.class_chalan_discount');

            $this->db->where('school_id',$school_id);
            $this->db->where('c_c_f_id',$c_c_f_id);
            $query2=$this->db->delete(get_school_db().'.class_chalan_fee');

            $this->db->where('school_id',$school_id);
            $this->db->where('c_c_f_id',$c_c_f_id);
            $query3=$this->db->delete(get_school_db().'.class_chalan_form');

            $this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));
            redirect(base_url().'class_chalan_form/class_chalan_f/');
        }
        else
        {
            $this->session->set_flashdata('club_updated',get_phrase('record_could_not_deleted_successfully'));
            redirect(base_url().'class_chalan_form/class_chalan_f/');
        }
    }

    function get_challan_form_details()
    {
        $data_arr = array();
        $chalan_form_id = $this->input->post('chalan_form_id');
        $section_id = $this->input->post('section_id');

        $chalan_form_qur = "select * from ".get_school_db().".class_chalan_form where type = ".$chalan_form_id." and section_id = ".$section_id." and school_id = ".$_SESSION['school_id']." and status = 1";
        $chalan_form_arr = $this->db->query($chalan_form_qur)->result_array();
        if (count($chalan_form_arr)>0)
        {
            $data_arr = $chalan_form_arr[0];
        }
        echo json_encode($data_arr);
    }
}