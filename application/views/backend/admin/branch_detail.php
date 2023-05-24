<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline capitalize">
            <!--  <i class="entypo-right-circled carrow">
                        </i>-->
            <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/brn.png"> <?php echo get_phrase('branch_detail'); ?>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <?php
		$student_count=$this->db->query("select count(student_id) std_cout from ".$school_db.".student where school_id=".$school_id." and student_status in (".student_query_status().")")->result_array();
		$total_stud=$student_count[0]['std_cout'];
		echo "<strong>Total Students : </strong>".$total_stud;

		echo "<br/>";
		//department
		$q="SELECT title,departments_id FROM ".$school_db.".departments WHERE school_id=".$school_id."";
		$query=$this->db->query($q)->result_array();
		if(count($query) > 0)
		{

		echo "<ul>";
		foreach($query as $row)
		{
			echo "<br/>";
			echo "<li>".$row['title']." (";
			//here
			$dept_count=$this->db->query("select count(d.departments_id) std_cout from ".$school_db.".class_section sec INNER JOIN ".$school_db.".student stud ON sec.section_id=stud.section_id
INNER JOIN ".$school_db.".class c 
ON c.class_id=sec.class_id
INNER JOIN ".$school_db.".departments d
ON d.departments_id=c.departments_id
		 where d.school_id=".$school_id." and stud.student_status in (".student_query_status().") AND d.departments_id=".$row['departments_id']." ")->result_array();
		$total_dept=$dept_count[0]['std_cout'];
		//echo $this->db->last_query();
			echo "Total Students : ".$total_dept.")</li>";

		
			//class
			$p="SELECT name,class_id FROM ".$school_db.".class WHERE school_id=".$school_id." AND departments_id=".$row['departments_id']." " ;
			$query1=$this->db->query($p)->result_array();
			if(count($query1) >0)
			{
				echo "<ul>";
			
			foreach($query1 as $row1)
			{
				echo "<li>".$row1['name']." (";
				//here
				$class_count=$this->db->query("select count(c.class_id) std_cout from ".$school_db.".class_section sec INNER JOIN ".$school_db.".student stud ON sec.section_id=stud.section_id
INNER JOIN ".$school_db.".class c ON c.class_id=sec.class_id
		 where sec.school_id=".$school_id." and student_status in (".student_query_status().") AND c.class_id=".$row1['class_id']." ")->result_array();
		$total_class=$class_count[0]['std_cout'];
			echo "Total Students : ".$total_class.")</li>";
				
				
			//section
			$r="SELECT title,section_id FROM ".$school_db.".class_section WHERE school_id=".$school_id." AND class_id=".$row1['class_id']." " ;
			$query2=$this->db->query($r)->result_array();
			if(count($query2) > 0)
			{
				echo "<ul>";
					foreach($query2 as $row2)
					{
						echo "<li>".$row2['title']." (";
						$section_count=$this->db->query("select sec.title as title,count(sec.section_id) std_cout from ".$school_db.".class_section sec INNER JOIN ".$school_db.".student stud ON sec.section_id=stud.section_id where sec.school_id=".$school_id." and student_status in (".student_query_status().") AND sec.section_id=".$row2['section_id']." ")->result_array();
		//echo $this->db->last_query();
		$total_section=$section_count[0]['std_cout'];
			
			echo "Total Students : ".$total_section.")</li>";
						
						
					}
					echo "</ul>";
				}
			}
			echo "</ul>";
			}
		} 
		echo "</ul>";
		}
		else
		{
			?>
            <?php echo get_phrase('no_department_found'); ?>
			<?php
			
		}
		?>
    </div>
</div>
