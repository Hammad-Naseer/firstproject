<?php  
 class Student_model extends CI_Model  
 {  
    function __construct()
    {
        parent::__construct();
        $table = "indicied_indiciedu_production.student";
        // $select_column = array("s.*", "s.name as student_name", "cs.title as section_name", "cc.name as class_name", "d.title as department_name","sp.p_name","sp.contact");
        $order_column = array(null, "name", "name", null, null);
    }
      
      function make_query()  
      {  
        //   $a = explode(",",student_query_status());
        //   $b = implode("','",$a);
        //   print_r($b);
           $this->db->select("s.*,s.name as student_name,cs.title as section_name,cc.name as class_name,d.title as department_name,sp.p_name,sp.contact");
           $this->db->from( get_school_db() . ".student s");  
           $this->db->join(get_school_db() .'.class_section cs', 'cs.section_id = s.section_id', 'INNER');
           $this->db->join(get_school_db() .'.class cc', 'cc.class_id = cs.class_id', 'INNER');
           $this->db->join(get_school_db() .'.departments d', 'd.departments_id = cc.departments_id', 'INNER');
           $this->db->join(get_school_db() .'.student_parent sp', 's.parent_id = sp.s_p_id', 'LEFT');
           $this->db->where("s.school_id",$_SESSION['school_id']);
           $this->db->where_in("s.student_status",student_query_status());
           
        //   select s.*, cs.title as section_name, cc.name as class_name, d.title as department_name , sp.p_name ,sp.contact from indicied_indiciedu_production.student s 
        //   inner join indicied_indiciedu_production.class_section cs on cs.section_id=s.section_id 
        //   inner join indicied_indiciedu_production.class cc on cc.class_id=cs.class_id 
        //   inner join indicied_indiciedu_production.departments d on d.departments_id=cc.departments_id 
        //   Left join indicied_indiciedu_production.student_parent sp on s.parent_id = sp.s_p_id 
        //   where s.school_id=214 and s.student_status in (10,11,12,13,14,15,16,17,18,19,20,26,27)


           if(isset($_POST["search"]["value"]))  
           {  
                $this->db->like("s.name", $_POST["search"]["value"]);  
                // $this->db->or_like("s.section_name", $_POST["search"]["value"]);  
           }  
           if(isset($_POST["order"]))  
           {  
                $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
           }  
           else  
           {  
                $this->db->order_by('student_id', 'ASC');  
           }  
      }
      
      function make_datatables(){  
           $this->make_query();  
           if($_POST["length"] != -1)  
           {  
                $this->db->limit($_POST['length'], $_POST['start']);  
           }  
           $query = $this->db->get();  
           return $query->result();  
      }  
      
      function get_filtered_data(){  
           $this->make_query();  
           $query = $this->db->get();  
           return $query->num_rows();  
      }
      
      function get_all_data()  
      {  
           $this->db->select("*");  
           $this->db->from("indicied_indiciedu_production.student");  
           return $this->db->count_all_results();  
      }  
 }  