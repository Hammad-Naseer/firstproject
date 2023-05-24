<style>
    .myfst {
        font-size: 15px;
        color: #0A73B7;
        font-weight: bold;
    }
    
    .due {
        color: #972d2d;
    }
    
    .mygrey {
        color: #A6A6A6;
    }
    
    .fa-remove {
        color: #FFF !important;
    }
    
    
    
    h1 {
      margin: 0;
      padding-bottom: 5pt;
      border-bottom: 1px solid #ddd;
    }
    input[type="text"] {
      height: 20pt;
      width: 100%;
      padding: 0;
      border: 0;
      outline: none;
      font-size: 14px;
      margin-top: 5pt;
    }
    .options {
      width: 100%;
      height: 30pt;
      margin-top: 5pt;
      border-top: 1px solid #ddd;
    }
    
    iframe {
      border: 0;
      width: 100%;
      margin-bottom: 5pt;
      height: 250pt;
    }
    .seperator {
      display: inline;
      border-left: 1px solid #ddd;
      height: 30pt;
    }
    button {
      margin: 0;
      padding: 0;
      height: 30pt;
      width: 30pt;
      background-color: #fff;
      border: 0;
      cursor: pointer;
      color: #333;
    }
    button:active {
      color: #333;
    }
    select {
      height: 30pt;
      -webkit-appearance: none;
      border: 0;
      padding-left: 5pt;
      padding-right: 5pt;
      outline: none;
    }
    input[type="number"] {
      height: 30pt;
      border: 0;
      padding: 0;
      padding-left: 5pt;
      padding-right: 5pt;
      outline: none;
    }
</style>


<div class="col-sm-12">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <h3 class="system_name inline">
                <!--  <i class="entypo-right-circled carrow">
                            </i>-->
              <img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/diary.png"><?php echo get_phrase('solve_assignment'); ?>  
            </h3>
        </div>
    </div>
</div>

<?php //echo "<pre>"; print_r($stud_diary); ?>
<div style="line-height: 22px;border: 1px solid #CFCFDE;width: 341px;margin-left: 30px;margin-bottom: 10px;border-radius: 5px;padding: 5px 0px 5px 10px;">
<?php  //echo "<pre>"; print_r($stud_diary);?>
<div><strong><?php echo get_phrase('subject')?> :<?php echo get_subject_name($stud_diary[0]['subject_id']);?></strong></div>
<div><strong><?php echo get_phrase('teacher')?> :<?php echo get_teacher_name($stud_diary[0]['teacher_id']);?></strong></div>

<div><strong><?php echo get_phrase('assign_date');?>:</strong><?php echo convert_date($stud_diary[0]['assign_date']);?></div>
<div style="font-size:14px; color:#0A73B7;"><strong><?php echo get_phrase('title').':'.$stud_diary[0]['title'];?></strong></div>

    <?php
    if($stud_diary[0]['attachment']!="")
    {?>
        
    <div style="color:#972d2d;"><strong><?php echo get_phrase('due_date');?>:</strong><?php echo convert_date($stud_diary[0]['due_date']);?></div>
    <?php   
    }
    
    $sec_arr = section_hierarchy($stud_diary[0]['section_id']);
    
    echo '<strong>'.get_phrase('section').':</strong> '.$sec_arr['d'].' - '.$sec_arr['c'].' - '.$sec_arr['s']; 					
    echo'<span class="item">';
    
    ?>
    <?php
    if ($stud_diary[0]['submission_date'] != '0000-00-00 00:00:00')
    {
        echo '<br/><strong>'.get_phrase("submisstion_date").':</strong> '.convert_date($stud_diary[0]['submission_date']).' '.date('h:i:s A', strtotime($row['submission_date']));
    	echo '<br/><strong>'.get_phrase("detail").':</strong> '.$stud_diary[0]['task'];
    }
    
    $planner_arr = $this->db->query("select ap.* 
        from ".get_school_db().".academic_planner_diary apd
        inner join ".get_school_db().".academic_planner ap
            on ap.planner_id = apd.planner_id
        where apd.diary_id = ".$stud_diary[0]['diary_id']." 
        and apd.school_id = ".$_SESSION['school_id']."
        ")->result_array();
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
    // if($stud_diary[0]['is_assigned'] == 0 ){
    //     echo '<br/><strong style="color:blue;">'.get_phrase("status").': '.get_phrase("draft").'</strong>';
    // }else{
    //     echo '<br/><strong style="color:green;">'.get_phrase("status").': '.get_phrase("assigned").'</strong>';
    // }
    
    ?>
</div>

<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>


<!--<form id="assignment_form" action="<?php echo base_url(); ?>parents/submit_assignment" method="POST" enctype="multipart/form-data">-->

<form  id="assignment_form" action="<?php echo base_url(); ?>parents/submit_assignment" method="POST" enctype="multipart/form-data">
    <input type="text" name="diary_id" hidden value="<?php echo $diary_id;?>">
    <div class="form-group">
        <textarea name="editor"></textarea>
    </div>
    <div class="form-group">
        <label  class="col-sm-4 control-label"><?php echo get_phrase('choose_answer_sheet');?></label>
        <div class="col-sm-8">
        <input type="file" class="form-control" name="image1" id="avatar" onchange="file_validate('avatar','doc','img_f_msg')">
        <span style="color: green;"><?php echo get_phrase('allowed_file_size');?>:  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx</span>
        <br />
        <span style="color: red;" id="img_f_msg"></span>
        
        </div>
    </div>
    <input style="float: right;" class="btn btn-primary" type = "button" value = "Submit Assignment" onclick = "getConfirmation();" />
</form>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script type = "text/javascript">
 <!--
    function getConfirmation() {
    //   var retVal = confirm("Do you want to submit the Assignment ?");
    //   if( retVal == true ) {
           
    //       $('#assignment_form').submit();
    //       //return true;
    //   } else {
    //       //document.write ("User does not want to continue!");
    //       return false;
    //   }
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
 //-->
</script> 


<script>
        CKEDITOR.replace( 'editor' );
</script>
