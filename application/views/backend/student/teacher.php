<style>
.mia {
    background-color: #2a648d !important;
    padding: 7px;
    border-radius: 5px;
    color: #f4f4f4;
    font-weight: bold;
}

.mia:hover {
    background-color: #065d85 !important;
    padding: 7px;
    border-radius: 5px;
    color: #FFF;
    font-weight: bold;
}
.view_message {
    border:1px solid #e4e4e4;
    min-height:100px;
    padding:20px;
    width:30%;
    float:left;
    margin-bottom:20px;
    margin-right:20px;
}
.sub_details{
	margin-top:15px;
	font-size:14px;
}
.teacher_icon{
	width:70px;
	height:80px;
	float:left;
	margin-right:20px;
}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <!--  <i class="entypo-right-circled carrow">
            </i>-->
           <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/message.png"> Messages
        </h3>
    </div>
    <?php
    $section_id = $_SESSION['section_id'];
    $school_id = $_SESSION['school_id'];
    $yearly_term_id = $_SESSION['yearly_term_id'];

    $sub_arr = $this->db->query("select sub.* from ".get_school_db().".subject sub
        inner join ".get_school_db().".subject_section ss on sub.subject_id=ss.subject_id 
        where ss.section_id = ".$section_id."
        and ss.school_id = ".$school_id."
        ")->result_array();
       //echo $this->db->last_query();
    ?>
        <?php /*
    <div class="col-sm-12">
    <div class="row thisrow"> 
        <form id="subject_filter" method="post" action="<?php echo base_url() ?>?parents/teacher_list" class="form-horizontal validate" novalidate>
        <div class="col-md-4 col-lg-4 col-sm-4">
            <select id="subject_id" name="subject_id" class="form-control" data-message-required="Required">
                <option value="">Select Subject</option>
                <?php
                    $sub_sel = '';
                    foreach ($sub_arr as $key => $value) 
                    {
                        if ($value['subject_id'] == $sub_filter)
                            $sub_sel = 'selected';
                        echo "<option value='".$value['subject_id']."' $sub_sel>".$value['name'].' - '.$value['code']."</option>";
                    }
                    ?>
            </select>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-4">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>
        <?php
            if($filter)
            {?>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <a href="<?php echo base_url()?>parents/teacher_list" class="btn btn-danger">
                    <i class="fa fa-remove"></i>Remove Filters
                </a>
            </div>
            <?php
            }
            ?>
                </form>
</div>
</div>
*/ ?>
<div class="col-lg-12 col-md-12 col-sm-12">

        <?php /*?><thead>
            <tr>
                <th style=" width: 60px;">
                    <?php echo get_phrase('photo');?>
                </th>
                <th>
                    <?php echo get_phrase('name');?>
                </th>
                <th>
                    <?php echo get_phrase('subject');?>
                </th>
                <th style="    width: 150px;">
                    <?php echo get_phrase('Message');?>
                </th>
            </tr>
        </thead><?php */?>
        <div class="message_box">
            <?php 
$student_id= $_SESSION['student_id'];
$school_id=$_SESSION['school_id'];

$alert  =   $this->db->query("SELECT * 
FROM ".get_school_db().".messages
WHERE student_id =$student_id
AND school_id=$school_id
AND messages_type =0
and is_viewed=0
")->result_array();

$alert_array=array();
foreach($alert as $row){
    
    $teacher_id=$row['teacher_id'];
    $subject_id=$row['subject_id'];
    if (!isset( $alert_array[$teacher_id][$subject_id]))
    {
        $alert_array[$teacher_id][$subject_id] = 0;
    }
    $alert_array[$teacher_id][$subject_id]++;//$row['is_viewed'];
    
}

//print_r($alert_array);
$school_id=$_SESSION['school_id'];
/*
$teachers   =   $this->db->query("select  t.teacher_id,t.name,t.email,t.teacher_image,s.student_id,s.school_id, st.name as subject_name ,st.subject_id 
    from ".get_school_db().".student s 
    inner join ".get_school_db().".subject st on s.section_id=st.section_id 
    inner join ".get_school_db().".teacher t on st.teacher_id=t.teacher_id
 
    where s.student_id=$student_id AND t.school_id=$school_id")->result_array();
*/


//echo $this->db->last_query();
/*
select t.teacher_id,t.name,t.email,t.teacher_image,s.student_id,s.school_id, st.name as subject_name ,st.subject_id 
from ".get_school_db().".student s 
inner join ".get_school_db().".subject_section sub_s on s.section_id=sub_s.section_id 

inner join ".get_school_db().".subject st on st.subject_id=sub_s.subject_id 

inner join ".get_school_db().".subject_teacher sub_teacher on sub_teacher.subject_id=st.subject_id
inner join ".get_school_db().".teacher t on sub_teacher.teacher_id=t.teacher_id 
where s.student_id=0 AND t.school_id=101
*/

//echo $this->db->last_query();
foreach($sub_arr as $row){

    $unread_arr = $this->db->query("select count(*) as unread
                from ".get_school_db().".messages
                WHERE student_id = ".$student_id."
                and school_id=".$_SESSION['school_id']."
                and messages_type = 0
                and is_viewed = 0 
                and subject_id = ".$row['subject_id']."
                ")->result_array();
    $unread_msgs = $unread_arr[0]['unread'];
//echo $this->db->last_query();
//echo "<br>";
    ?>
            <?php
            
                        $teacher_arr = get_subject_time_table_teacher($section_id,$row['subject_id'],$yearly_term_id);
                        //echo $this->db->last_query();
                    //print_r($teacher_arr);
               
                    
                    ?>
                <div class="view_message">
                    <div class="teacher_icon">
                        <?php 
                            if($teacher_arr[0]['teacher_image']=="")
                            {
                                //$image_url    =   base_url().'uploads/user.jpg';?>
                        <img src="<?php echo get_default_pic();?>" width="70" height="70"/>
                        <?php   }
                            else
                            {?>
                            <img src="<?php echo display_link($teacher_arr[0]['teacher_image'],'staff');?>" width="70" height="70" />
                            <?php
                            }?>
                    </div>
                    <div class="sub_details">
                        <div>
                            <strong><?php echo $teacher_arr[0]['name'];?></strong>
                        </div>
                        <div>
                            <?php echo $row['name'].' - '.$row['code']; ?>
                        </div>
                    </div>
                    <div style="float:left; margin-top:22px; clear:both;">
                        <a href="<?php echo base_url(); ?>parents/message/<?php  echo intval($teacher_arr[0]['teacher_id']).'/'.$row['subject_id'];   ?>">
                            <span>View / Send Messages</span>
                        </a>
                        <?php

$teachers_id2=$teacher_arr[0]['teacher_id'];
$subject_id2=$row['subject_id'];
if($alert_array[$teachers_id2][$subject_id2] > 0){
    
    echo '<span style="color: green;" class="glyphicon glyphicon-record">'.$alert_array[$teachers_id2][$subject_id2].'</span>';
    
}


                

?>
                    </div>
                </div>
                <?php }?>
        </div>

</div>
</div>
<!--  DATA TABLE EXPORT CONFIGURATIONS -->
<script type="text/javascript">
jQuery(document).ready(function($) {


    //var datatable = $("#table_export").dataTable();

    $(".dataTables_wrapper select").select2({
        minimumResultsForSearch: -1
    });
});
</script>
