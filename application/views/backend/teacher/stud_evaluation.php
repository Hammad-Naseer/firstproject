<?php
$exam_id=$this->uri->segment(3);
$section_id=$this->uri->segment(4);

$type=$this->uri->segment(5);
$this->load->helper('teacher');
$login_detail_id = $_SESSION['login_detail_id'];
$d_c_s_sec = array();//get_teacher_dep_class_section($login_detail_id);
$time_table_t_sec = get_time_table_teacher_section($login_detail_id);
$teacher_section = array_unique(array_merge($d_c_s_sec, $time_table_t_sec));



if($this->session->flashdata('club_updated')){
    echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
}
?>
<script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
</script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('student_evaluation');?>
        </h3>
    </div>
</div>
<br />
<form name="filter" id="filter" method="post" class="form-horizontal  validate">
    <div>
        <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select filters and press filter button to get specific records">
            <div class="col-sm-4 mt-3">
                <!--<label id="evaluation_types_label"></label>-->
                <select id="evaluation_types" name="type" class="selectpicker_type form-control" data-validate="required" data-message-required="Value Required">
                    <option value=''>Select Type</option>
                    <option <?php if($type == '1') { echo 'selected';} ?> value='1'><?php echo get_phrase('exam');?></option>
                    <option <?php if($type == '2') { echo 'selected';} ?> value='2'><?php echo get_phrase('general');?></option>
                </select>
            </div>
            <div class="col-sm-4 mt-3" id='exam-field'>
                <!--<label id="yearly_terms1_selection"></label>-->
                <select id="yearly_terms1" name="yearly_terms1" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">
                    <?php
                    $status_year=array(3);
                    $status_term=array(3);
                    echo yearly_term_selector_exam($exam_id,$status_year,$status_term); ?>
                </select>
            </div>
            <div class="col-sm-4 mt-3">
                <!--<label id="dep_c_s_id_selection"></label>-->
                <select id="dep_c_s_id" class="selectpicker form-control" name="section_id" data-validate="required" data-message-required="Value Required">
                    <?php echo get_teacher_dep_class_section_list($teacher_section, $section);?>
                </select>
                <div id="section-err"></div>
            </div>
        	<div class="col-sm-4 mt-3">
                <select name="subject" id="subject_list" class="selectpicker form-control"  data-validate="required" data-message-required="Value Required">
                    <option value=""><?php echo get_phrase('select_subject');?></option>
                </select>	
        	</div>
            <div class="col-sm-4 mt-3">
                <input type="submit" id="select" class="modal_save_btn" value="<?php echo get_phrase('filter');?>">
                <a href="<?php echo base_url(); ?>student_evaluation/stud_eval/" style="display: none;" class="btn btn-danger" id="btn_remove"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter');?></a>
            </div>
        </div>
    </div>
</form>
<div id="stud"></div>

<script type="text/javascript">
    $(document).ready(function() {
       
        if($('.selectpicker_type').val() == '2'){
            $('#exam-field').hide();
            $(this).prop('required',false);
        }
        var section_id=$('#dep_c_s_id').val();
        var subject_id = getCookie("subject_id");
        $.ajax({
            type: 'POST',
            data: {section_id:section_id},
            url: '<?php echo base_url();?>teacher/get_time_table_subject_list/'+subject_id,
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $("#subject_list").html(response); 
                eraseCookie("subject_id");    
            }
        });
        
        
        $("#dep_c_s_id").change(function()
        {
            var section_id=$(this).val();
            var url = '<?php echo base_url();?>teacher/get_time_table_subject_list';
            $(".loader_small").remove();
            $(this).after('<div class="loader_small"></div>');
            $.ajax({
                type: 'POST',
                data: {section_id:section_id},
                url: url,
                dataType: "html",
                success: function(response) {
                    $(".loader_small").remove();
                    $("#subject_list").html(response);      
                }
            });
        });
        
        var section_id = $("#dep_c_s_id").val();
        var exam_id = $("#yearly_terms1").val();
        var type = $("#evaluation_types").val();
        if ((section_id != '' && type == '1' && exam_id != '' && subject_id != '') || (section_id != '' && type == '2' && subject_id != '')){
            $('#btn_remove').show();
            $("#stud").html("<div id='loading' class='loader'></div>");
            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id,
                    exam_id: exam_id,
                    type: type,
                    subject_id:subject_id,
                    evaluated_by:2
                },
                url: "<?php echo base_url();?>student_evaluation/get_stud_eval",
                dataType: "html",
                success: function(response) {

                    $("#loading").remove();
                    $("#stud").html(response);
                }
            });
        }



        document.getElementById('filter').onsubmit = function() {
            return false;
        };
    
        $('.selectpicker_type').on('change', function() {
            $('#evaluation_types_label').text('Types');
            if($(this).val() == '2'){
                $('#exam-field').hide();
                $(this).prop('required',false);
            }
            else{
                $('#exam-field').show();
                $(this).prop('required',true);
            }
        });
        
        $('.selectpicker').on('change', function() {
            var id = $(this).attr('id');

            var selected = $('#' + id + ' :selected');

            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);





        });
        function setCookie(cname, cvalue, exdays) {
          const d = new Date();
          d.setTime(d.getTime() + (exdays*24*60*60*1000));
          let expires = "expires="+ d.toUTCString();
          document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }
        function getCookie(cname) {
          let name = cname + "=";
          let decodedCookie = decodeURIComponent(document.cookie);
          let ca = decodedCookie.split(';');
          for(let i = 0; i <ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
              c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
              return c.substring(name.length, c.length);
            }
          }
          return "";
        }
        function eraseCookie(name) {   
            document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }
        $("#select").click(function()
        {
            $('#filter').validate();
            var section_id = $("#dep_c_s_id").val();
            var exam_id = $("#yearly_terms1").val();
            var type = $("#evaluation_types").val();
            var subject_id = $("#subject_list").val();
            if ((section_id != '' && type == '1' && exam_id != '' && subject_id != '') || (section_id != '' && type == '2' && subject_id != '')){
                $('#btn_remove').show();
                $("#stud").html("<div id='loading' class='loader'></div>");
                setCookie("subject_id", subject_id, 1);
                $.ajax({
                    type: 'POST',
                    data: {
                        section_id: section_id,
                        exam_id: exam_id,
                        type: type,
                        subject_id:subject_id,
                        evaluated_by:2
                    },
                    url: "<?php echo base_url();?>student_evaluation/get_stud_eval",
                    dataType: "html",
                    success: function(response) {
                       // $("#select").attr('disabled','disabled');
                        $("#loading").remove();
                        $("#stud").html(response);
                    }
                });
            } else{
                $("#stud").html('');
            }
        });
    });
</script>
