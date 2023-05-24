<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();

    class Deposit extends CI_Controller
    {
        private $system_db;
        private $school_db;

        function __construct()
        {
            parent::__construct();

            $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            $this->output->set_header('Pragma: no-cache');

            $this->system_db = $_SESSION['system_db'];
            $this->school_db = $_SESSION['school_db'];
            $this->designation_arr = array();
            if ($_SESSION['user_login'] != 1)
                redirect(base_url() . 'login');

        }

        /***default functin, redirects to login page if no admin logged in yet***/
        public function index()
        {
            if ($_SESSION['user_login'] != 1) {
                redirect(base_url() . 'login');
            }
            redirect(base_url() . 'user/user_type');
        }

        /*public function user_type(){
            $page_data['user_type'] = $this->db->order_by('user_group_id', 'desc')->get(get_system_db().'.user_group')->result_array();
            $page_data['page_name']  = 'user_type_list';
            $page_data['page_title'] = get_phrase('manage_user_types');
            $this->load->view('backend/index', $page_data);
        }*/
/*
        public function user_groups()
        {
            $page_data['user_group'] = $this->db->order_by('user_group_id', 'desc')->get(get_school_db() . '.user_group')->result_array();
            $page_data['page_name'] = 'user_group_list';
            $page_data['page_title'] = get_phrase('manage_user_groups');
            $this->load->view('backend/index', $page_data);
        }


        private function set_barcode($code, $path)
        {

            $this->load->library('zend');
            $this->zend->load('Zend/Barcode');
            $barcode = new Zend_Barcode();
            $file = $barcode->draw('Code128', 'image', array('text' => $code, 'barHeight' => 20, 'drawText' => TRUE, 'withQuietZones' => FALSE, 'orientation' => 0), array());

            $file_name = $code . '.png';
            $store_image = imagepng($file, "$path" . '/' . $file_name);
            return $file_name;
        }

*/

        public function deposit_listing($action = "", $id = 0)
        {


            $filter_query = "";
            if ($action == "add_edit") {

                $data['depositor_id'] = $this->input->post('depositor_id');
                $data['title'] = $this->input->post('title');
                $data['description'] = $this->input->post('description');
                $data['amount'] = $this->input->post('amount');

                $deposit_date_post = $this->input->post('deposit_date');

               // $deposit_date_post = $data['deposit_date'];
                if ($deposit_date_post != 0)
                {
                    $deposit_date=date_slash($deposit_date_post);
                    $deposit_date_post = str_replace('/','_',$deposit_date);
                }
                $data['deposit_date'] = $deposit_date_post;

                $data['status'] = $this->input->post('status');
                $status = $data['status'];
                //$data['attachment'] = $this->input->post('attachment');
                $school_id = $_SESSION['school_id'];


                $deposit_id = $this->input->post('deposit_id');
                $designation_id = $this->input->post('designation_id');

                if ($deposit_id != "") {

                    $this->db->where('deposit_id', $deposit_id);
                    $this->db->where('school_id', $school_id);
                    $this->db->update(get_school_db() . '.deposit', $data);
                    //echo $this->db->last_query();
                    $this->add_journal($deposit_id,$status);

                    $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
                } else
                {
                    $data['school_id'] = $school_id;
                   // $this->session->set_flashdata('club_updated', get_phrase('Record Saved'));
                    $this->db->insert(get_school_db() . '.deposit', $data);
                    $deposit_id = $this->db->insert_id();
                    $this->add_journal($deposit_id,$status);
                    $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
                }


                redirect(base_url() . 'deposit/deposit_listing/');
            }
            else if ($action == 'delete')
            {

                $qur_2 = $this->db->query("SELECT * FROM " . get_school_db() . ".deposit 
                                            WHERE deposit_id=$id 
                                                AND school_id=" . $_SESSION['school_id'])->result_array();

                if (count($qur_2) > 0)
                {
                        $qur_1 = $this->db->query("Delete from " . get_school_db() . ".deposit where deposit_id= $id and school_id=" . $_SESSION['school_id']);
                        $this->session->set_flashdata('club_updated', get_phrase('record_deleted_sucessfully'));
                        redirect(base_url() . 'deposit/deposit_listing');
                        exit();
                }

            }

            $keyword_query = "";
            $depositor_id_query = "";
            $per_page = 10;
            $config['per_page'] = $per_page;
            $config['uri_segment'] = 7;
            $config['num_links'] = 10;
            $config['use_page_numbers'] = TRUE;

            $page_num = $this->uri->segment(7);
            if (!isset($page_num) || $page_num == "") {
                $page_num = 0;
                $start_limit = 0;
            } else {
                $start_limit = ($page_num - 1) * $per_page;
            }



            /* filter search by title start */
            $depositor_id = $this->input->post('depositor_id');
            if($depositor_id == "" || $depositor_id ==0)
            {
                $depositor_id =  $this->uri->segment(4);
                if($depositor_id == "" || $depositor_id == 0){
                    $depositor_id = 0;
                }
            }

            if($depositor_id>0)
            {
                $depositor_id_query .= " AND (dps.depositor_id = " . $depositor_id . ")";
            }
            /* filter search by title End */


            /* filter Search by title start */
            $search_get = $this->input->post('keyword');
            if ($search_get == "")
            {
                $search_get = $this->uri->segment(3);
                if($search_get == ""){
                    $search_get = 0;
                }
            }

            if(($search_get != "") && ($search_get != '0'))
            {
                $search_get_where = $search_get;
                $keyword_query .= " AND 
                                    (dps.title LIKE '%" . $search_get_where . "%' 
                                         or dps.description LIKE '%" . $search_get_where . "%'
                                    )";
            }
            /* filter Search by title End */

            /* filter with data and time Start */

            $start_date_post = $this->input->post('start_date' , true);

            if ($start_date_post == "" || $start_date_post == 0)
            {
                $start_date_post = $this->uri->segment(5);

                if ($start_date_post == "" || $start_date_post == 0)
                {
                    $start_date_post = 0;
                }
                else
                {
                    $start_date_post = str_replace('_','/',$start_date_post);
                }
            }

            if ($start_date_post != 0)
            {
                $start_date=date_slash($start_date_post);
                $start_date_post = str_replace('/','_',$start_date_post);
            }

            $end_date_post = $this->input->post('end_date', true);

            if ($end_date_post == "" || $end_date_post == 0)
            {
                $end_date_post = $this->uri->segment(6);

                if ($end_date_post == "" || $end_date_post == 0)
                {
                    $end_date_post = 0;
                }
                else
                {
                    $end_date_post = str_replace('_','/',$end_date_post);
                }
            }

            if ($end_date_post != 0)
            {
                $end_date=date_slash($end_date_post);
                $end_date_post = str_replace('/','_',$end_date_post);
            }


            if ($start_date != "" && $end_date != "")
            {
                if ($start_date == $end_date)
                {
                    $start_date_query = " AND DATE_FORMAT(dps.deposit_date, \"%Y-%m-%d\") = '".$start_date."'";
                }
                else
                {
                    $start_date_query = " AND DATE_FORMAT(dps.deposit_date, \"%Y-%m-%d\") between '".$start_date."' AND '".$end_date."'";
                }
            }
            else
            {
                if ($start_date != "")
                {
                    $start_date_query = " AND DATE_FORMAT(dps.deposit_date, \"%Y-%m-%d\") >= '$start_date' ";
                }

                if ($end_date != "")
                {
                    $end_date_query = " AND DATE_FORMAT(dps.deposit_date, \"%Y-%m-%d\") <= '$end_date'";
                }
            }

            /* End date and time functioin */


           $config['base_url'] = base_url() . "deposit/deposit_listing/" . $search_get ."/".$depositor_id."/".$start_date_post."/".$end_date_post."";


           $qry_count = "SELECT count(*) as total_rows FROM " . get_school_db() . ".deposit as dps
                                INNER JOIN " . get_school_db() . ".depositor as dpr
                                    ON dpr.depositor_id = dps.depositor_id
                                    WHERE dps.school_id = " . $_SESSION['school_id']
                                    . $keyword_query
                                    . $depositor_id_query
                                    . $start_date_query
                                    . $start_date_query. "
                                    ORDER BY deposit_id desc";

            $total_rows = $this->db->query($qry_count)->row();
            $total_rows = $total_rows->total_rows;  //$config['total_rows'];
            $config['total_rows'] = $total_rows;  //$total_rows->total;

           $qry = "SELECT dps.*, dpr.*  FROM " . get_school_db() . ".deposit as dps
                                INNER JOIN " . get_school_db() . ".depositor as dpr
                                ON dpr.depositor_id = dps.depositor_id
                                WHERE dps.school_id = " . $_SESSION['school_id']
                                . $keyword_query
                                . $depositor_id_query
                                . $start_date_query
                                . $end_date_query. "
                                  ORDER BY status asc, deposit_date desc 
                                    limit " . $start_limit . "," . $per_page . "";

            $res_array = $this->db->query($qry)->result_array();
            $this->load->library('pagination');
            $this->pagination->initialize($config);


            $page_data['data'] = $res_array;
            $page_data['total_records'] = $total_rows;
            if($search_get != '0') {
                $page_data['search_get'] = $search_get;
            }

            $page_data['depositor_id'] = $depositor_id;
            $page_data['start_date'] = $start_date;
            $page_data['end_date'] = $end_date;
            $page_data['page_name'] = 'deposit_listing';
            $page_data['depositor_id_post'] = $depositor_id;
            $page_data['page_title'] = get_phrase('deposit');
            $this->load->view('backend/index', $page_data);

        }

        function add_edit_deposit()
        {

            $page_data['page_name'] = 'deposit_add_edit';
            $page_data['page_title'] = get_phrase('deposit_add_edit');
            $this->load->view('backend/index', $page_data);

        }
        public function add_journal($deposit_id , $status=0)
        {

            if ($status == 1)
            {
                $qry = "SELECT dps.*, dpr.*  FROM " . get_school_db() . ".deposit as dps
                                INNER JOIN " . get_school_db() . ".depositor as dpr
                                ON dpr.depositor_id = dps.depositor_id
                                WHERE dps.school_id = " . $_SESSION['school_id'] . "
                                    AND dps.deposit_id = " . $deposit_id . "
                                    AND dps.status = 1
                                    AND dps.amount>0";

                $row = $this->db->query($qry)->row_array();

                if (count($row) > 0)
                {
                    $date = new DateTime();
                    $entry_date = $date->format('Y-m-d H:i:s');

                    $data_debit_submit = array(
                        'entry_date' => $entry_date,
                        'detail' => $row['title'] . " - "
                            . "Deposited by - " . $row['name'] . "",
                        'debit' => $row['amount'],
                        'entry_type' => 5,
                        'type_id' => $row['deposit_id'],
                        'school_id' => $_SESSION['school_id'],
                        'coa_id' => $row['dr_coa_id']
                    );
                    //'entry_date' => CURDATE(),
                    $data_credit_submit = array(
                        'entry_date' => $entry_date,
                        'detail' => $row['title'] . " - "
                            . "Deposited by - " . $row['name'] . "",
                        'credit' => $row['amount'],
                        'entry_type' => 5,
                        'type_id' => $row['deposit_id'],
                        'school_id' => $_SESSION['school_id'],
                        'coa_id' => $row['cr_coa_id']
                    );

                    $this->db->insert(get_school_db() . ".journal_entry", $data_debit_submit);
                    $this->db->insert(get_school_db() . ".journal_entry", $data_credit_submit);

                }

            }
        }
    }
