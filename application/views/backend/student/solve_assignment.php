<style>
    .myfst{font-size:15px;color:#0a73b7;font-weight:700}.due{color:#972d2d}.mygrey{color:#a6a6a6}.fa-remove{color:#fff!important}h1{margin:0;padding-bottom:5pt;border-bottom:1px solid #ddd}input[type=text]{height:20pt;width:100%;padding:0;border:0;outline:0;font-size:14px;margin-top:5pt}.options{width:100%;height:30pt;margin-top:5pt;border-top:1px solid #ddd}iframe{border:0;width:100%;margin-bottom:5pt;height:250pt}.seperator{display:inline;border-left:1px solid #ddd;height:30pt}button{margin:0;padding:0;height:30pt;width:30pt;background-color:#fff;border:0;cursor:pointer;color:#333}button:active{color:#333}select{height:30pt;-webkit-appearance:none;border:0;padding-left:5pt;padding-right:5pt;outline:0}input[type=number]{height:30pt;border:0;padding:0;padding-left:5pt;padding-right:5pt;outline:0}button.btn.btn-default.fileinput-upload.fileinput-upload-button{width:100px;height:32px}button.btn.btn-default.fileinput-remove.fileinput-remove-button{width:100px;height:32px}button.btn.btn-default{color:#fff!important;width:100px;margin-right:5px!important}
    .wrs_tickContainer{
        display:none;
    }
    .wrs_modal_dialogContainer{
        z-index: 999999999 !important;
        top: 30%;
        left: 35%;
    }
</style>

<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/css/fileinput.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<div class="col-sm-12">
    <div class="topbar mt-3 pb-3">
        <h3 class="system_name inline">
            <?php echo get_phrase('solve_assignment'); ?>  
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 bg-white" style="line-height: 22px;border: 1px solid #CFCFDE;margin-bottom: 10px;border-radius: 5px;padding: 5px 0px 5px 10px;">
    <div><h3><strong><?php echo get_phrase('subject')?> :<?php echo get_subject_name($stud_diary[0]['subject_id']);?></strong></h3></div>
    <div class="text-dark"><strong><?php echo get_phrase('teacher')?> : <?php echo get_teacher_name($stud_diary[0]['teacher_id']);?></strong></div>
    <div class="text-dark"><strong><?php echo get_phrase('assign_date');?>:</strong><?php echo convert_date($stud_diary[0]['assign_date']);?></div>
    <div class="text-dark"><strong><?php echo get_phrase('due_date');?>:</strong><?php echo convert_date($stud_diary[0]['due_date']);?></div>
    <?php    
        $sec_arr = section_hierarchy($stud_diary[0]['section_id']);
        echo '<div class="text-dark"><strong>'.get_phrase('section').':</strong> '.$sec_arr['d'].' - '.$sec_arr['c'].' - '.$sec_arr['s'].'</div>'; 					
        echo'<span class="item">';
    ?>
    <div style="font-size:14px; color:#0A73B7;"><strong><?php echo get_phrase('title').':'.$stud_diary[0]['title'];?></strong></div>
    <?php if($stud_diary[0]['attachment']!="") {?>
    <div style="color:#972d2d;"><strong><?php echo get_phrase('due_date');?>:</strong><?php echo convert_date($stud_diary[0]['due_date']);?></div>
    <?php } ?>
    <?php
        if ($stud_diary[0]['submission_date'] != '0000-00-00 00:00:00')
        {
            echo '<br/><strong>'.get_phrase("submisstion_date").':</strong> '.convert_date($stud_diary[0]['submission_date']).' '.date('h:i:s A', strtotime($row['submission_date']));
        	echo '<br/><strong>'.get_phrase("detail").':</strong> '.$stud_diary[0]['task'];
        }
        $planner_arr = $this->db->query("select ap.* from ".get_school_db().".academic_planner_diary apd inner join ".get_school_db().".academic_planner ap on ap.planner_id = apd.planner_id where apd.diary_id = ".$stud_diary[0]['diary_id']." and apd.school_id = ".$_SESSION['school_id']." ")->result_array();
        if (count($planner_arr)>0)
        {
            echo '<br/><strong>'.get_phrase("planner").':</strong>';
            $p_count=1;
            foreach ($planner_arr as $key => $value) 
            {
                echo '<br>'.$p_count.')'.get_phrase("title").':' .$planner_arr[0]['title'];
            }
        } 
        echo'</span>';
    ?>
    <div class="text-dark"><strong><?php echo get_phrase('task');?>:</strong><?php echo $stud_diary[0]['task'];?></div>
</div>
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <form id="assignment_form" action="<?php echo base_url(); ?>student_p/submit_assignment" method="POST" enctype="multipart/form-data" style="width:100%">
        <input type="text" name="diary_id" hidden value="<?php echo $diary_id;?>">
        <div class="form-group">
            <textarea name="editor" id="ckeditor"></textarea>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group" style="width:100%;">
                <label for="input-2"><?php echo get_phrase('choose_answer_sheet');?><span class="redEsteric">*</span></label>
                <span class="btn btn-default btn-file" style="width:100%;">
                     <input id="input-2" name="documents[]" type="file" class="file" multiple="multiple" required="required" data-show-caption="true">
                </span>
            </div>
            <span style="color: red;" id="img_f_msg"></span>
        </div>
        <input style="float: right;" class="btn btn-primary" type = "button" value = "Submit Assignment" onclick = "getConfirmation();" />
    </form>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script type = "text/javascript">
    function getConfirmation() {
            $.confirm({
            title: 'Assignment Submission',
            content: 'Do you want to submit the Assignment ?',
            buttons: {
                Yes: function () {
                    // $.alert('Confirmed!');
                    $('#assignment_form').submit();
                },
                cancel: function () {
                
                }
            }
        });
    }
</script> 

<script>
    CKEDITOR.plugins.addExternal('ckeditor_wiris', 'https://www.wiris.net/demo/plugins/ckeditor/', 'plugin.js');
    CKEDITOR.config.uiColor = '#427fa6';
    CKEDITOR.config.width = '100%'; 
     
    CKEDITOR.replace('ckeditor', {
         extraPlugins: 'ckeditor_wiris',
         height: 200
    });
</script>