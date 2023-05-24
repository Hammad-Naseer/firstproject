<?php 
    $urlArr=explode('/',$_SERVER['REQUEST_URI']);
    $resArr=end($urlArr);
    $teacher_id=$resArr;
    $qry = "SELECT s.name as staff_name,s.employee_code as employee_code, d.title as designation
		FROM ".get_school_db().".staff s 
		INNER JOIN ".get_school_db().".designation d
		ON d.designation_id = s.designation_id
		WHERE s.staff_id=".$teacher_id."";

    $res_array = $this->db->query($qry)->result_array();

    $q="SELECT * from ".get_school_db().".subject  where school_id=".$_SESSION['school_id']." ";
    $subject_list=$this->db->query($q)->result_array();
    //$section_detail=(section_hierarchy($section_id));
?>

<div id="msg"></div>

    <div class="black2" style="font-size:12px; font-weight:bold; margin-bottom:20px;">
        <div class="panel-heading">
			<div class="panel-title" >
				<?php echo get_phrase('assign_subject_to');?>: 
				    <span class="text-white" style="font-size:13px;"><?php echo $res_array[0]['staff_name']."(".$res_array[0]['designation'].")-Emp-Code:".$res_array[0]['employee_code']; ?></span>
			</div>
		</div> 
    </div>

<form  method="post" id="filter"  class="form-horizontal form-groups-bordered validate" style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px; ">
    <div class="thisrow" style="padding: 15px;">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <select id="subj_categ_select" class="form-control" name="subj_categ_select" data-validate="required" data-message-required="Value Required">
                    <?php echo get_subj_category($this->input->post('subj_categ_select'));?>
                </select>
            </div>
        
            <div class="col-lg-6 col-md-6 col-sm-6">
                <input type="submit" value="<?php echo get_phrase('filter'); ?>" class="modal_save_btn" id="btn_sub" style="margin-top:0px;">
                <a href="#" style="padding:7px 16px 8px 17px !important;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_teacher_subject/<?php echo $teacher_id;?>')" class="modal_cancel_btn" id="btn_remove1" style="display:none;">			<i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>
            </div>
        </div>
    </div>
</form>

<div id="list"></div>

<script type="text/javascript">
    $(document).ready(function(){
        document.getElementById('filter').onsubmit = function() {
            return false;
        };
        $('#btn_remove1').hide();
        $('#list').load("<?php echo base_url(); ?>subject/asign_teacher_generator"+"/"+"<?php echo $teacher_id;?>");
        $('#btn_sub').click(function(){
            var teacher_id='<?php echo $teacher_id;?>';
            var subj_categ_id=$('#subj_categ_select').val();
            if(subj_categ_id!="")
            {
                $('#btn_remove1').show();
                $('#list').html('<div id="message" class="loader"></div>');
                $.ajax({
                    type: 'POST',
                    data: {teacher_id:teacher_id,subj_categ_id:subj_categ_id},
                    url: "<?php echo base_url();?>subject/asign_teacher_generator",
                    dataType: "html",
                    success: function(response) {
                        $('#message').remove();
                        $('#list').html(response);
                    }
                });
            }
        });
    });
</script>