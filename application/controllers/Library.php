<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Library extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
		$this->load->database();
        /*cache control*/
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
    }
    
    function books()
    {
        $book_query = $this->db->query("SELECT * FROM ".get_school_db().".books")->result_array();    
        $page_data['books'] = $book_query;
		$page_data['page_name']  = 'library/book';
        $page_data['page_title'] = get_phrase('book');
        $this->load->view('backend/index', $page_data);
    }
    
    function add_book()
    {
        $data['bookId'] = $this->input->post('book_id');
        // $data['section_id'] = $this->input->post('section_id');
        $data['school_id'] = $_SESSION['school_id'];
        $data['book_title'] = $this->input->post('book_title');
        $data['isbn_no'] = $this->input->post('isbn_number');
        $data['shelf_no'] = $this->input->post('shelf_no');
        $data['edition'] = $this->input->post('edition');
        $data['volume'] = $this->input->post('volume');
        $data['author'] = $this->input->post('author');
        $data['language'] = $this->input->post('language');
        $data['price'] = $this->input->post('price');
        $data['quantity'] = $this->input->post('quantity');
        $data['details'] = $this->input->post('description');
        $data['book_type'] = $this->input->post('book_type');
        $data['status'] = $this->input->post('status');
        $data['book_added_by'] = $_SESSION['user_login_id'];
        
        // Book Cover
        if($_FILES['book_cover'] != ""):
            $old_book_cover = $this->input->post('old_book_cover');
            $image_attachment_name_attr = 'book_cover';
            $image_url  = file_upload_fun($image_attachment_name_attr , 'library_book_cover', '');
            $data['book_cover']   =   $image_url;
            $del_location = system_path($old_book_cover,'library_book_cover');
            file_delete($del_location);
        endif;
        
        // E-Book
        if($_FILES['ebook_file'] != ""):
            $old_ebook = $this->input->post('old_ebook');
            $image_attachment_name_attr = 'ebook_file';
            $image_url1  = file_upload_fun($image_attachment_name_attr , 'library_ebooks', '');
            $data['ebook_file']   =   $image_url1;
            $del_location = system_path($old_ebook,'library_ebooks');
            file_delete($del_location);
        endif;    
        
        $this->db->insert(get_school_db().'.books',$data);
        $this->session->set_flashdata('club_updated', get_phrase('book_added_successfully'));
        $insert_id=$this->db->insert_id();
        redirect(base_url() . 'library/books');
    }
    
    function update_book()
    {
        $bookId = str_decode($this->input->post('bid'));
        // $data['section_id'] = $this->input->post('section_id');
        $data['school_id'] = $_SESSION['school_id'];
        $data['bookId'] = $this->input->post('book_id');
        $data['book_title'] = $this->input->post('book_title');
        $data['isbn_no'] = $this->input->post('isbn_number');
        $data['shelf_no'] = $this->input->post('shelf_no');
        $data['edition'] = $this->input->post('edition');
        $data['volume'] = $this->input->post('volume');
        $data['author'] = $this->input->post('author');
        $data['language'] = $this->input->post('language');
        $data['price'] = $this->input->post('price');
        $data['quantity'] = $this->input->post('quantity');
        $data['details'] = $this->input->post('description');
        $data['book_type'] = $this->input->post('book_type');
        $data['status'] = $this->input->post('status');
        
        // Book Cover
        if($_FILES['book_cover'] != ""):
            $image_attachment_name_attr = 'book_cover';
            $image_url  = file_upload_fun($image_attachment_name_attr , 'library_book_cover', '');
            $data['book_cover']   =   $image_url;
        endif;
        
        // E-Book
        if($_FILES['ebook_file'] != ""):
            $image_attachment_name_attr = 'ebook_file';
            $image_url1  = file_upload_fun($image_attachment_name_attr , 'library_ebooks', '');
            $data['ebook_file']   =   $image_url1;
        endif;    
        
        $this->db->where('bookId' , $bookId);
        $this->db->update(get_school_db().'.books',$data);
        $this->session->set_flashdata('club_updated', get_phrase('book_updated_successfully'));
        redirect(base_url() . 'library/books');
    }
    
    function delete_book()
    {
        $school_id      = $_SESSION['school_id'];
        $id = str_decode($this->uri->segment(3));
        $old_book_cover = $this->uri->segment(4);
        $old_ebook = $this->uri->segment(5);
        
        if($old_book_cover != ""){
            $del_location = system_path($old_book_cover,'library_book_cover');
            file_delete($del_location);
        }
        if($old_ebook != "")
        {
			$del_location = system_path($old_ebook,'library_ebooks');
            file_delete($del_location);
		}
        

        $delete_ary=array('school_id'=>$school_id,'book_id'=>$id);
        $this->db->where($delete_ary);
        $this->db->delete(get_school_db().'.books');
        $this->session->set_flashdata('club_updated',get_phrase('book_deleted_successfully'));

        redirect(base_url().'library/books');
    }
    
    function book_issue()
    {
        if($_POST)
        {
            $data = array(
                'member_id'             =>  $this->input->post("member"),
                'book_id'               =>  $this->input->post("library_books"),
                'book_issue_date'       =>  $this->input->post("issue_date"),
                'book_return_date'      =>  $this->input->post("return_date"),
                'note'                  =>  $this->input->post("note"),
                'issued_by'             =>  $_SESSION['user_login_id'],
            );
            $this->db->insert(get_school_db().'.book_issue',$data);
            $this->session->set_flashdata('club_updated', get_phrase('book_issue_successfully'));
            redirect(base_url() . 'library/book_issue');   
        }
        $book_query = $this->db->query("SELECT * FROM ".get_school_db().".book_issue LEFT JOIN ".get_school_db().".books ON books.book_id = book_issue.book_id")->result_array();
        $page_data['books'] = $book_query;
		$page_data['page_name']  = 'library/book_issue';
        $page_data['page_title'] = get_phrase('book_issue');
        $this->load->view('backend/index', $page_data);
    }
    
    function members()
    {
        if($_POST['library_membership_id'])
        {
            $data = array(
                'library_membership_id'        =>  $this->input->post("library_membership_id"),
                'user_id'           =>  $this->input->post("user_id"),
                'membership_fee'    =>  $this->input->post("membership_fee"),
                'status'            =>  $this->input->post("status"),
                'created_by'        =>  $_SESSION['user_login_id']
            );
            $this->db->insert(get_school_db().'.library_members',$data);
            $this->session->set_flashdata('club_updated', get_phrase('member_added_successfully'));
            redirect(base_url() . 'library/members');
        }
        $section_id = $this->input->post('section_id', TRUE);
        if (isset($section_id) || $section_id != "") {
            $quer = "select  s.*, cs.title as section_name, cc.name as class_name, d.title as department_name , sp.p_name ,sp.contact
                    from " . get_school_db() . ".student s 
                    inner join " . get_school_db() . ".class_section cs on cs.section_id=s.section_id
                    inner join " . get_school_db() . ".class cc on cc.class_id=cs.class_id 
                    inner join " . get_school_db() . ".departments d on d.departments_id=cc.departments_id
                    Left join " . get_school_db() . ".student_parent sp on s.parent_id = sp.s_p_id
                    where s.school_id=" . $_SESSION['school_id'] . " AND s.section_id = ".$section_id." 
					and s.student_status in (" . student_query_status() . ") ";

            $students_query = $this->db->query($quer)->result_array();
            $page_data['students_query'] = $students_query;
        }
        
        $member_query = $this->db->query("SELECT * FROM ".get_school_db().".library_members")->result_array();
        $page_data['members'] = $member_query;
		$page_data['page_name']  = 'library/members';
        $page_data['page_title'] = get_phrase('members');
        $this->load->view('backend/index', $page_data);
    }
    
    function update_members()
    {
        $id = str_decode($this->uri->segment("3"));
        $data = array(
            'library_membership_id'         =>  $this->input->post("library_membership_id"),
            'user_id'                       =>  $this->input->post("user_id"),
            'membership_fee'                =>  $this->input->post("membership_fee"),
            'status'                        =>  $this->input->post("status")
        );
        $this->db->where("library_member_id",$id);
        $this->db->update(get_school_db().'.library_members',$data);
        $this->session->set_flashdata('club_updated', get_phrase('member_updated_successfully'));
        redirect(base_url() . 'library/members');
    }
    
    function update_book_issue()
    {
        $data = array(
            'member_id'             =>  $this->input->post("library_id"),
            'book_id'               =>  $this->input->post("user_id"),
            'book_issue_date'       =>  $this->input->post("membership_fee"),
            'book_return_date'      =>  $this->input->post("status"),
            'note'                  =>  $this->input->post("status"),
            'issued_by'             =>  $_SESSION['user_login_id'],
        );
        $this->db->where("book_issue_id",$id);
        $this->db->update(get_school_db().'.book_issue',$data);
        $this->session->set_flashdata('club_updated', get_phrase('book_issue_updated_successfully'));
        redirect(base_url() . 'library/book_issue');   
    }
    
    function return_book($id = 0)
    {
        $data = array(
            'actual_return_date'        =>  $this->input->post("actual_return_date"),
            'fine'                      =>  $this->input->post("fine"),
            'returned_by'               =>  $_SESSION['user_login_id']
        );
        $this->db->where("book_issue_id",str_decode($id))->update(get_school_db().'.book_issue',$data);
        $this->session->set_flashdata('club_updated', get_phrase('book_return_successfully'));
        redirect(base_url() . 'library/book_issue');
    }
    
    function book_reserve_request()
    {
        $book_query = $this->db->query("SELECT brr.brr_id,brr.book_id,brr.user_login_detail_id,brr.book_collect_date,brr.status,b.book_title,b.quantity FROM ".get_school_db().".book_reserve_request brr INNER JOIN ".get_school_db().".books b ON b.book_id = brr.book_id ")->result_array();    
        $page_data['books'] = $book_query;
		$page_data['page_name']  = 'library/book_reserve_request';
        $page_data['page_title'] = get_phrase('book_reserve_request');
        $this->load->view('backend/index', $page_data);
    }
    
    function book_issue_from_reserve()
    {
        if($_POST)
        {
            $id = $this->input->post("id");
            $data = array(
                'member_id'             =>  $this->input->post("user_login_detail_id"),
                'book_id'               =>  $this->input->post("book_id"),
                'book_issue_date'       =>  $this->input->post("issue_date"),
                'book_return_date'      =>  $this->input->post("return_date"),
                'note'                  =>  $this->input->post("note"),
                'issued_by'             =>  $_SESSION['user_login_id'],
            );
            $this->db->where("brr_id",$id)->update(get_school_db().'.book_reserve_request',array('status'=>'1'));
            
            $this->db->insert(get_school_db().'.book_issue',$data);
            $this->session->set_flashdata('club_updated', get_phrase('book_issue_successfully'));
            redirect(base_url() . 'library/book_reserve_request');   
        }
    }
    
    function cancel_book_reserve_request()
    {
        $id = $this->uri->segment(3);
        $this->db->where("brr_id",$id);
        $this->db->update(get_school_db().'.book_reserve_request',array('status'=>'2'));
        $this->session->set_flashdata('club_updated',get_phrase('cancel_request_successfully'));
        redirect(base_url().'library/book_reserve_request');
    }

}
