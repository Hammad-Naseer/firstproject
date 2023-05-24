<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();

class Supplier extends CI_Controller
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
    public function supplier_listing($action = "", $id = 0)
    {
        $filter_query = "";
        if ($action == "add_edit")
        {
            $data['name'] = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            $data['contact_no'] = $this->input->post('contact_no');
            $data['location_id'] = $this->input->post('location_id');
            $data['address'] = $this->input->post('address');
            $data['ntn_number'] = $this->input->post('ntn_number');
            $data['filing_status'] = $this->input->post('filing_status');
            $data['supplier_type'] = $this->input->post('supplier_type');
            $data['supplier_percentage'] = $this->input->post('supplier_percentage');
            $data['description'] = $this->input->post('description');
            $data['nationality'] = $this->input->post('nationality');
            $data['id_type'] = $this->input->post('id_type');
            $data['id_no'] = $this->input->post('id_no');
            $data['status'] = $this->input->post('status');

            $data['coa_cash_payment'] = $this->input->post('coa_cash_payment');
            $data['coa_bank_payment'] = $this->input->post('coa_bank_payment');
            $data['coa_purchase_voucher'] = $this->input->post('coa_purchase_voucher');

            $data['supplier_id'] = $this->input->post('supplier_id');

            $school_id = $_SESSION['school_id'];
            $filename = $_FILES['attachment']['name'];

            if ($filename != "")
            {
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $data['attachment'] = file_upload_fun('attachment', 'supplier', 'pic');
                $old_attachment = $this->input->post('old_attachment');

                if ($old_attachment != "")
                {
                    $del_location = system_path($old_attachment, 'staff');
                    file_delete($del_location);
                }
            }

            $supplier_id = $this->input->post('supplier_id');
            $designation_id = $this->input->post('designation_id');

            if ($supplier_id != "")
            {

                $this->db->where('supplier_id', $supplier_id);
                $this->db->where('school_id', $school_id);
                $this->db->update(get_school_db() . '.supplier', $data);
                $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
            }
            else
            {
                $data['school_id'] = $school_id;
                $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
                $this->db->insert(get_school_db() . '.supplier', $data);
                $supplier_id = $this->db->insert_id();
                $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
            }

            redirect(base_url() . 'supplier/supplier_listing/');

        }
        else if ($action == 'delete')
        {
        
            $school_id = $_SESSION['school_id'];

            //$qur_1 = "SELECT dps.* FROM " . get_school_db() . ".deposit as dps WHERE dps.school_id = " . $_SESSION['school_id'] . " AND dps.depositor_id = " . $id . "";
            //$row_delete = $this->db->query($qur_1)->row_array();
            //if (count($row_delete) > 0)
            //{
               // $this->session->set_flashdata('club_updated', get_phrase('deposits_exist_._depositor_record_can_not_be_deleted'));
                //redirect(base_url() . 'depositor/depositor_listing');
                //exit();

            //}
            //else
            {
                //   $qur_2 = "SELECT dps.* , dpr.* FROM " . get_school_db() . ".depositor

                $qur_2 = "SELECT dpr.* FROM " . get_school_db() . ".supplier as dpr WHERE dpr.school_id = " . $_SESSION['school_id'] . " AND dpr.supplier_id = " . $id . "";
                    $row_delete = $this->db->query($qur_2)->row_array();

                $old_attachment = $row_delete['attachment'];
                  //  exit;
                if ($old_attachment != "")
                {
                   // exit('attachment_find');
                    $del_location = system_path($old_attachment, 'supplier');
                    file_delete($del_location);
                    $qur_1 = $this->db->query("Delete from " . get_school_db() . ".supplier where supplier_id= $id and school_id=" . $_SESSION['school_id']);
                    $this->session->set_flashdata('club_updated', get_phrase('record_deleted_sucessfully'));
                    redirect(base_url() . 'supplier/supplier_listing');
                    exit();
                }
                else
                {
                    $qur_1 = $this->db->query("Delete from " . get_school_db() . ".supplier where supplier_id= $id and school_id=" . $_SESSION['school_id']);
                    $this->session->set_flashdata('club_updated', get_phrase('record_deleted_sucessfully'));
                    redirect(base_url() . 'supplier/supplier_listing');
                    exit();

                }
            }
         
        }

        $filter_query = "";
        $per_page = 15;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 4;
        $config['num_links'] = 10;
        $config['use_page_numbers'] = TRUE;

        $page_num = $this->uri->segment(4);
        if (!isset($page_num) || $page_num == "") {
            $page_num = 0;
            $start_limit = 0;
        } else {
            $start_limit = ($page_num - 1) * $per_page;
        }

        $search_get = $this->input->post('keyword');

        if (!isset($search_get) || ($search_get == "")) {

            $search_get = $this->uri->segment(3);
            if ($search_get == "default") {
                $search_get = "";
            }
        }

        if (isset($search_get) && ($search_get != ""))
        {
            $filter_query = " AND
            (
                name LIKE '%" . $search_get . "%' or 
                email LIKE '%" . $search_get . "%' or
                description LIKE '%" . $search_get . "%' or
                contact_no LIKE '%" . $search_get . "%' or
                id_no LIKE '%" . $search_get . "%'
            )";
            $config['base_url'] = base_url() . "supplier/supplier_listing/" . $search_get . "";
        }
        else
        {
            $config['base_url'] = base_url() . "supplier/supplier_listing/default";
        }

        $qry_count = "SELECT count(*) as total_rows FROM " . get_school_db() . ".supplier WHERE school_id = " .
        $_SESSION['school_id'] . $filter_query . "
        ORDER BY supplier_id desc";

        $total_rows = $this->db->query($qry_count)->row();
        $total_rows = $total_rows->total_rows;  //$config['total_rows'];
        $config['total_rows'] = $total_rows;  //$total_rows->total;

        $qry = "SELECT * FROM " . get_school_db() . ".supplier WHERE school_id = " . $_SESSION['school_id'] .
        $filter_query . " ORDER BY supplier_id desc  limit " .
        $start_limit . "," . $per_page . "";

        $res_array = $this->db->query($qry)->result_array();
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $page_data['data'] = $res_array;
        $page_data['total_records'] = $total_rows;
        $page_data['search_get'] = $search_get;
        $page_data['page_name'] = 'supplier_listing';
        $page_data['page_title'] = get_phrase('supplier');
        $this->load->view('backend/index', $page_data);
    }

    function add_edit_supplier()
    {
        $page_data['page_name'] = 'supplier_add_edit';
        $page_data['page_title'] = get_phrase('supplier_add_edit');
        $this->load->view('backend/index', $page_data);
    }

}
