<?php
$diary_id = $this->uri->segment(4);
$student_id = $this->uri->segment(5);

$p="SELECT answer_text FROM ".get_school_db().".diary_student WHERE diary_id=".$diary_id." AND school_id=".$_SESSION['school_id']." and student_id = ".$student_id." ";
$res=$this->db->query($p)->result_array();
?>
<div class="modal-body row">
	<div class="col-lg-12">
	    <textarea id="myTextarea" class="form-control mb-4"><?php echo strip_tags($res[0]['answer_text']);?>
	    </textarea>
	    <div id="testing"></div>
	</div> 
</div>


<script>
    document.getElementById("myTextarea").readOnly = true;
</script>