<?php 
    $this->load->helper('teacher');
    $param2 = $this->uri->segment(3);
    $query="select dr.*,d.departments_id as departments_id,c.class_id as class_id, cs.section_id as section_id
     FROM ".get_school_db().".diary dr 
     INNER join ".get_school_db().".class_section cs
     ON dr.section_id=cs.section_id
    Inner JOIN ".get_school_db().".class c
    On cs.class_id=c.class_id
    Inner join ".get_school_db().".departments d
    On d.departments_id=c.departments_id
    WHERE dr.diary_id=$param2 AND dr.school_id=".$_SESSION['school_id']."";
    
    $edit_data=$this->db->query($query)->result_array();
    
    $login_detail_id = $_SESSION['login_detail_id'];
    $yearly_term_id = $_SESSION['yearly_term_id'];
    $academic_year_id = $_SESSION['academic_year_id'];
    $section_arr = get_time_table_teacher_section($login_detail_id, $yearly_term_id);
?>
<style>
    .wrs_tickContainer{
        display:none;
    }
    .wrs_modal_dialogContainer{
        z-index: 999999999 !important;
        top: 30%;
        left: 35%;
    }
</style>
<div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <!--<b><i class="fas fa-info-circle"></i> Interactive tutorial</b>-->
            </a>
            <h3 class="system_name inline">
                  <?php 
                    if($edit_data[0]['is_assigned'] == 0)
                    {
                        echo get_phrase('edit_diary');
                    }else{
                        echo get_phrase('view_diary');
                    }
                    ?>
            </h3>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js" integrity="sha512-nhY06wKras39lb9lRO76J4397CH1XpRSLfLJSftTeo3+q2vP7PaebILH9TqH+GRpnOhfAGjuYMVmVTOZJ+682w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <div class="row">    
        <div class="panel panel-primary col-lg-12" data-collapsed="0">
        <div class="panel-body">
            <?php foreach($edit_data as $row){ ?>
            <?php echo form_open_multipart(base_url().'teacher/diary/do_update/'.$row['diary_id'] , array('class' => 'form-horizontal validate','target'=>'_top', 'id'=>'diary_edit_form' ));?>
                <div class="form-group" id="class1">
                    <label class="control-label">
                        <?php echo get_phrase('select_section'); ?>
                    </label>
                    <div class="box closable-chat-box">
                            <div class="box-content padded">
                                <div class="chat-message-box">
                                    <select id="section_id1" class="form-control" name="section_id1" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                        <?php 
                           /* $class_id=$row['class_id'];
                            echo section_option_list($class_id,$row['section_id']);*/
                            echo get_teacher_dep_class_section_list($section_arr, $row['section_id']);
                            
                            ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="form-group" id="subjects1">
                    <label class="control-label">
                        <?php echo get_phrase('select_subject');?>
                    </label>
                    <select name="subject_id1" id="subject_id1" class="form-control">
                        <?php   
                            $section_id =  $row['section_id'];                               
                            echo subject_option_list($section_id,$row['subject_id']);        
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">
                        <?php echo get_phrase('assign_date');?>
                    </label>
                    <input type="date" class="form-control" name="assign_date1" id="assign_date1" value="<?php echo $row['assign_date']; ?>" 
                    data-format="dd/mm/yyyy" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                    <div id="error_start1" class="col-sm-8 col-sm-offset-4"></div>
                </div>
                <div class="form-group">
                    <label class="control-label">
                        <?php echo get_phrase('academic_planner_task');?>
                    </label>
                    <div id="item_list1">
                        <?php 
                        $query2="select planner_id FROM ".get_school_db().".academic_planner_diary 
                        WHERE diary_id=".$row['diary_id']." ";
                        $selected=$this->db->query($query2)->result_array();
                        $res_array=array();
                        foreach($selected as $res)
                        {
                            $res_array[]=$res['planner_id'];
                        }
                        $assign_date=$row['assign_date'];
                        $query1="select planner_id,title FROM ".get_school_db().".academic_planner WHERE `start`='$assign_date' AND subject_id=".$row['subject_id']." AND school_id=".$_SESSION['school_id']."";
                        $result=$this->db->query($query1)->result_array();
                  
                        foreach($result as $planner)
                        {
                            $checked = "";
                            if (in_array($planner["planner_id"],$res_array))
                            {
                                $checked = "checked";
                            }
                            
                            echo '<label><input type="checkbox" name="planner_check[]" value="'.$planner["planner_id"].'" '.$checked.'>'.$planner["title"].'</label>';
                            echo '<br/>';
                        }
                     ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">
                        <?php echo get_phrase('title');?>
                    </label>
                    <input type="text" maxlength="50" class="form-control" name="title1" value="<?php echo $row['title'];?>" maxlength="100" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                </div>
                <div class="form-group">
                    <label class="control-label">
                        <?php echo get_phrase('detail');?>
                    </label>
    
                    <textarea name="task1" id="ckeditor" rows="5" class="form-control" placeholder="<?php echo get_phrase('add_detail');?>" maxlength="1000" oninput="count_value('task1','area_count1','1000')"><?php echo $row['task'];?></textarea>
                    <div id="area_count1" class="col-sm-12 "></div>
                </div>
                <div class="form-group">
                    <label class="control-label">
                        <?php echo get_phrase('due_date');?>
                    </label>
                    <input type="date" class="form-control" name="due_date1" id="due_date1" value="<?php echo $row['due_date']; ?>" 
                    data-format="dd/mm/yyyy" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                    <div id="error_end1" class="col-sm-8 col-sm-offset-4"></div>
                </div>
                <div class="form-group">
                    <label class="control-label">
                        <?php echo get_phrase('attachment');?>
                    </label>
                    <input type="file" class="form-control" name="image2" style="height:40px">
                    <input type="hidden" name="image_old" value="<?php echo $row['attachment']; ?>" />
                    <br>
                    <strong style="margin-right:10px;"><?php echo get_phrase('attachment'); ?>:</strong><a target="_blank" href="<?php echo display_link($row['attachment'],'diary');?>"><span class="glyphicon glyphicon-download-alt"></span></a>
                    <p class='text-danger mt-3'>if you want to update attachment then choose new file</p>
                </div>
                
                <?php
                if($edit_data[0]['is_assigned'] == 0){
                ?>
                <div class="form-group">
                    <div class="float-right">
                        <input type="submit" class="modal_save_btn" name="save" id="btn_edit" value="<?php echo get_phrase('save');?>" />
                        <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
        					<?php echo get_phrase('cancel');?>
        				</button>
                        <?php
                            $selected_qry = $this->db->query("select ds.student_id from 
                            ".get_school_db().".diary_student ds
                            inner join ".get_school_db().".student s on s.student_id = ds.student_id
                            where
                            ds.diary_id = '".$row['diary_id']."'
                            and s.student_status in (".student_query_status().")
                            and s.section_id = '".$row['section_id']."'
                            and s.school_id = ".$_SESSION['school_id']."
                            ")->result_array();
                            if (count($selected_qry) > 0){    
                        ?>
                        <input type="submit" name="submit" id="submit_btn" value="<?php echo get_phrase('submit');?>" class="btn btn-info" id="btn_add" onclick="return submit_func();" />
                        <?php } ?>
                    </div>
                    <input type="hidden" name="is_submitted" id="is_submitted" value="0">
                </div>
                <?php
                }
                ?>
            <?php echo form_close(); } ?>
        </div>    
    </div>
    </div>
<script>
$('#submit_btn').click(function() {
    if (confirm('<?php echo get_phrase("if_diary_is_submitted_then_it_cannot_be_changed_are_you_sure_to_submit");?>')) {
        $('#is_submitted').val(1);
        $('#diary_edit_form').submit();
    } else {
        return false;
    }
});

$(document).ready(function() {

    $("#section_id1").change(function() {
    	$('#item_list1').html('');
        var section_id = $(this).val();

        $("#icon").remove();
        $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

        $.ajax({
            type: 'POST',
            data: {
                section_id: section_id
            },
            url: "<?php echo base_url(); ?>teacher/get_section_student_subject",
            dataType: "html",
            success: function(response) {
                var obj = jQuery.parseJSON(response);
                $("#icon").remove();

                //$("#student_box1").html(obj.student);
                $("#subject_id1").html(obj.subject);
                //$("#teacher_id1").html('<select><option>Select Section</option></select>');

            }
        });
    });

    $("#subject_id1").change(function() {
        var subject_id = $(this).val();

        $("#icon").remove();
        $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

        $.ajax({
            type: 'POST',
            data: {
                subject_id: subject_id
            },
            url: "<?php echo base_url();?>teacher/get_subject_teacher",
            dataType: "html",
            success: function(response) {
                $("#icon").remove();

                $("#teacher_id1").html(response);


            }
        });

    });

    $("#assign_date1").change(function() {
        var assign_date = $(this).val();
        var subject_id = $('#subject_id1').val();
        if (assign_date != "" && subject_id != "") {


            $.ajax({
                type: 'POST',
                data: {
                    assign_date: assign_date,
                    subject_id: subject_id
                },
                url: "<?php echo base_url(); ?>diary/get_acad_checkboxes",
                dataType: "html",
                success: function(response) {
                    $('#item_list1').html(response);
                }
            });
        }

    });

    $("#subject_id1").change(function() {
        var subject_id = $(this).val();
        var assign_date = $('#assign_date1').val();
        if (assign_date != "" && subject_id != "") {


            $.ajax({
                type: 'POST',
                data: {
                    assign_date: assign_date,
                    subject_id: subject_id
                },
                url: "<?php echo base_url();?>diary/get_acad_checkboxes",
                dataType: "html",
                success: function(response) {
                    $('#item_list1').html(response);

                }
            });
        }

    });

    $('#assign_date1').on('change', function() {
        $('#btn_edit').removeAttr('disabled', 'true');
        $('#error_start1').text('');
        var start_date = $(this).val();
        var term_id = $('#yearly_terms2').val();
        alert(term_id);
        if (start_date != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>diary/term_date_range",

                data: ({
                    start_date: start_date,
                    term_id: term_id
                }),
                dataType: "html",
                success: function(html) {
                    if (html == 0) {
                        $('#error_start1').text('<?php echo get_phrase("assign_date_should_be_between_term_dates");?>');
                        $('#btn_edit').attr('disabled', 'true');
                    }
                }
            });
        }

    });

    $('#due_date1').on('change', function() {
        $('#btn_edit').removeAttr('disabled', 'true');
        $('#error_end1').text('');
        var end_date = $(this).val();
        var term_id = $('#yearly_terms2').val();
        if (end_date != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>diary/term_date_range",

                data: ({
                    end_date: end_date,
                    term_id: term_id
                }),
                dataType: "html",
                success: function(html) {
                    if (html == 0) {
                        $('#error_end1').text('<?php echo get_phrase("due_date_should_be_between_term_dates");?>');
                        $('#btn_edit').attr('disabled', 'true');
                    }
                }
            });
        }

    });

    $("#assign_date1").change(function() {
        $('#btn_edit').removeAttr('disabled', 'true');
        var startDate = document.getElementById("assign_date1").value;
        var endDate = document.getElementById("due_date1").value;

        if ((Date.parse(endDate) < Date.parse(startDate))) {
            $('#error_start1').text("<?php echo get_phrase('assign_date_should_be_less_then_due_date');?>");
            $('#btn_edit').attr('disabled', 'true');
            //document.getElementById("start_date").value = "";

        }
    });

    $("#due_date1").change(function() {
        $('#btn_edit').removeAttr('disabled', 'true');
        var startDate = document.getElementById("assign_date1").value;
        var endDate = document.getElementById("due_date1").value;

        if ((Date.parse(startDate) > Date.parse(endDate))) {
            $('#error_end1').text("<?php echo get_phrase('due_date_should_be_greater_than_assign_date')?>");
            $('#btn_edit').attr('disabled', 'true');
           
        }
    });

});
</script>
<?php 
/*
$edit_data      =   $this->db->get_where(get_school_db().'.diary' , array('diary_id' => $param2,'school_id' =>$_SESSION['school_id']) )->result_array();
foreach ( $edit_data as $row):
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <i class="entypo-plus-circled"></i>
                    <?php echo get_phrase('edit_diary');?>
                </div>
            </div>
            <div class="panel-body">
                <?php echo form_open_multipart(base_url().'teacher/diary/do_update/'.$row['diary_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                        <?php echo get_phrase('class_name');?>
                    </label>
                    <div class="col-sm-5">
                        <?php $class_name       =   $this->db->get_where(get_school_db().'.class', array('class_id'=>$row['class_id'],'school_id' =>$_SESSION['school_id']))->row()->name;  ?>
                        <input type="hidden" class="form-control" name="class_id" value="<?php echo $row['class_id']; ?>" readonly/>
                        <input type="text" class="form-control" value="<?php echo $class_name; ?>" readonly />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                        <?php echo get_phrase('teacher');?>
                    </label>
                    <div class="col-sm-5">
                        <?php $teacher_name     =   $this->db->get_where(get_school_db().'.teacher', array('teacher_id'=>$row['teacher_id'],'school_id' =>$_SESSION['school_id']))->row()->name;    ?>
                        <input type="hidden" class="form-control" name="teacher_id" value="<?php echo $row['teacher_id']; ?>" readonly/>
                        <input type="text" class="form-control" name="teacher_name" value="<?php echo $teacher_name; ?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                        <?php echo get_phrase('subject');?>
                    </label>
                    <div class="col-sm-5">
                        <select name="subject_id" class="form-control" style="width:100%;" required>
                            <option value="">Please Select</option>
                            <?php 
                                        $subjects = $this->db->get_where(get_school_db().'.subject', array('class_id'=>$row['class_id'],'school_id' =>$_SESSION['school_id']))->result_array();
                                        foreach($subjects as $row1):
                                        ?>
                            <option value="<?php echo $row1['subject_id'];?>" <?php if($row1[ 'subject_id']==$row[ 'subject_id']) echo "selected"; ?>>
                                <?php echo $row1['name'];?>
                            </option>
                            <?php
                                        endforeach;
                                        ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                        <?php echo get_phrase('student');?>
                    </label>
                    <div class="col-sm-5">
                        <select name="student_id" id="student_id" class="form-control">
                            <option value="">Select a Student</option>
                            <?php
                           
                            $students = $this->db->query("select name,student_id from ".get_school_db().".student where school_id=".$_SESSION['school_id']." AND class_id=".$row['class_id'])->result_array();
                            foreach($students as $row2):

?>
                                <option value="<?php echo  $row2['student_id']; ?>" <?php if($row2[ 'student_id']==$edit_data[0][ 'student_id']){ echo "selected"; } ?>
                                    >
                                    <?php echo  $row2['name']; ?>
                                </option>
                                <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                        <?php echo    get_phrase('due_date');?>
                    </label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control datepicker" name="due_date" value="<?php $date= explode('-',$row['due_date']);
       echo  $date[1].'/'.$date[2].'/'.$date[0];                            
                                     ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                        <?php echo    get_phrase('title');?>
                    </label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="title" value="<?php echo $row['title'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                        <?php echo get_phrase('detail');?>
                    </label>
                    <div class="col-sm-5">
                        <textarea name="task" class="form-control">
                            <?php echo $row['task']; ?>
                        </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label">
                        <?php echo get_phrase('attachment');?>
                    </label>
                    <div class="col-sm-5">
                        <?php
                                if($row['attachment']=="")
    {?>
                            <?php }
    else
    {?>
                            <a target="_blank" href="<?php echo base_url();?>uploads/diary_image/<?php echo $row['attachment'];?>">[View attachment]</a>
                            <?php }?>
                            <input type="hidden" name="attach_hidden" value="<?php echo $row['attachment'];?>" class="form-control">
                            <div>
                                <span class="btn btn-white btn-file">
                                        <span class="fileinput-new">Select file</span>
                                <input type="file" name="attach_file">
                                </span>
                            </div>
                    </div>
                </div>
                <!--<div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('photo');?></label>
                        
                        <div class="col-sm-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div>
                                    <a target="_blank" href="<?php echo base_url();?>uploads/diary/<?php echo $row['attachment'];?>">[View]</a>
                                </div>
                                
                                <div>
                                    <span class="btn btn-white btn-file">
                                        <span class="fileinput-new"><?php echo get_phrase("Select image"); ?></span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file" name="userfile" accept="image/*">
                                    </span>
                                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>-->
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" class="btn btn-info">
                            <?php echo get_phrase('edit_task');?>
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
endforeach;
*/
?>
<script>
        // CKEDITOR.replace('task');
        CKEDITOR.plugins.addExternal('ckeditor_wiris', 'https://www.wiris.net/demo/plugins/ckeditor/', 'plugin.js');
        CKEDITOR.config.uiColor = '#427fa6';
        CKEDITOR.config.width = '100%'; 
         
        CKEDITOR.replace('ckeditor', {
             extraPlugins: 'ckeditor_wiris',
             height: 200
        });
    </script>