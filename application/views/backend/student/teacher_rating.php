<style>
.mia{background-color:#2a648d!important;padding:7px;border-radius:5px;color:#f4f4f4;font-weight:700}.mia:hover{background-color:#065d85!important;padding:7px;border-radius:5px;color:#fff;font-weight:700}.view_message{border:1px solid #e4e4e4;min-height:100px;padding:20px;width:30%;float:left;margin-bottom:20px;margin-right:20px}.sub_details{margin-top:15px;font-size:14px}.teacher_icon{width:70px;height:80px;float:left;margin-right:20px}.chat_teacher_subjects{background:#012b3c;padding:10px;color:#fff;border-radius:40px;margin-bottom:10px}.chat_send{background:#02658d;color:#fff;padding:5px;border-radius:30px;position:relative;left:50%;top:-10px;border:3px solid #fff}.chat_unread_messegs{background:#02658d;color:#fff;padding:5px;border-radius:30px;position:relative;top:10px;border:1px solid #fff}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('teacher_rating'); ?>
        </h3>
    </div>
</div>
<style>
    
</style>
<div class="row">
    
    <?php
        $section_id = $_SESSION['section_id'];
        $school_id = $_SESSION['school_id'];
        $yearly_term_id = $_SESSION['yearly_term_id'];
    
        //$sub_arr = $this->db->query("select sub.* from ".get_school_db().".subject sub inner join ".get_school_db().".subject_section ss on sub.subject_id=ss.subject_id where ss.section_id = ".$section_id." and ss.school_id = ".$school_id." ")->result_array();
    ?>

    <div class="col-lg-12 col-md-12 col-sm-12" data-step="1" data-position='top' data-intro="all messages record">  
        <div class="row message_box_stdnt">
            <?php 
            $student_id= $_SESSION['student_id'];
            $school_id=$_SESSION['school_id'];
            $school_id=$_SESSION['school_id'];
            $teacher_arr1 = $this->db->query("select staff.staff_id as teacher_id, staff.user_login_detail_id as user_id,staff.name, staff.staff_image as teacher_image , cr.subject_id , sub.name as subject_name, sub.code from " . get_school_db() . ".class_routine cr
            inner join " . get_school_db() . ".class_routine_settings crs on crs.c_rout_sett_id=cr.c_rout_sett_id and crs.section_id = $section_id 
            inner join " . get_school_db() . ".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id 
            inner join " . get_school_db() . ".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
            inner join " . get_school_db() . ".staff on staff.staff_id = st.teacher_id
            inner join " . get_school_db() . ".subject sub on cr.subject_id = sub.subject_id
            
            where staff.school_id = " . $_SESSION['school_id'] . " GROUP BY staff.name ")->result_array();
            // echo "<pre>";
            // print_r($teacher_arr1);
            foreach($teacher_arr1 as $teacher_arr){
            ?>
            
            <?php //$teacher_arr = get_subject_time_table_teacher($section_id,$row['subject_id'],$yearly_term_id); ?>
                <div class="col-sm-12 col-md-6 col-lg-5" style="background-color:white">
                    <div class="msg-inner">
                    <div class="teacher_icon">
                        <?php if($teacher_arr['teacher_image']==""){ ?>
                        <img src="<?php echo get_default_pic();?>" width="70" height="70"/>
                        <?php }else{ ?>
                        <img src="<?php echo display_link($teacher_arr['teacher_image'],'staff');?>" width="70" height="70" />
                        <?php } ?>
                    </div>
                    <div class="sub_details">
                        <div>
                            <strong><?php echo $teacher_arr['name'];?></strong>
                        </div>
                        <div>
                            <strong><?php echo $teacher_arr['subject_name']."(".$teacher_arr['code'].")";?></strong>
                        </div>
                        
                    </div>
                    <hr style="margin-top: 50px;margin-bottom: -10px;">
                    <br>
                    <div class="rating-set">
                        <p class="rates m-0">
                            <?php
                                $my_rating = $this->db->query("select AVG(rating) as average_rating from " . get_school_db() . ".teacher_rating WHERE teacher_id = ".$teacher_arr['teacher_id']." AND school_id =" . $_SESSION['school_id']."")->row();
                                
                                // echo  $this->db->last_query();
                            ?>
                            Average Rating:  <strong><?php echo round($my_rating->average_rating, 2)?></strong>
                            <div class="avg">
                                <div class='rating-stars text-center'>
                                    <?php
                                        $style_avg = 0;
                                        if($my_rating->average_rating > 0 And $my_rating->average_rating < 1)
                                            $style_avg = $my_rating->average_rating*100;
                                        if($my_rating->average_rating > 1 And $my_rating->average_rating < 2)
                                            $style_avg = ($my_rating->average_rating - 1)*100;
                                        if($my_rating->average_rating > 2 And $my_rating->average_rating < 3)
                                            $style_avg = ($my_rating->average_rating - 2)*100;
                                        if($my_rating->average_rating > 3 And $my_rating->average_rating < 4)
                                            $style_avg = ($my_rating->average_rating - 3)*100;
                                        if($my_rating->average_rating > 4 And $my_rating->average_rating < 5)
                                            $style_avg = ($my_rating->average_rating - 4)*100;
                                        $style_avg = 100 - $style_avg;
                                        $style = 'background: -webkit-linear-gradient(180deg, #ccc '.$style_avg.'%, #FF912C 0%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;';
                                    ?>
                                    <ul id='stars'>
                                        <li class='star-item <?php echo ($my_rating->average_rating>0)?'selected':'';?>' title='Poor'>
                                            <i class='fa fa-star fa-fw' style="<?php echo ($my_rating->average_rating>0 And $my_rating->average_rating < 1)?$style:'';?>padding-right:0px;"></i>
                                        </li>
                                        <li class='star-item <?php echo ($my_rating->average_rating>1)?'selected':'';?>' title='Fair'>
                                            <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>1 And $my_rating->average_rating < 2)?$style:'';?> padding-right:0px;"></i>
                                        </li>
                                        <li class='star-item <?php echo ($my_rating->average_rating>2)?'selected':'';?>' title='Good'>
                                            <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>2 And $my_rating->average_rating < 3)?$style:'';?>padding-right:0px;"></i>
                                        </li>
                                        <li class='star-item <?php echo ($my_rating->average_rating>3)?'selected':'';?>' title='Excellent'>
                                            <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>3 And $my_rating->average_rating < 4)?$style:'';?>padding-right:0px;"></i>
                                        </li>
                                        <li class='star-item <?php echo ($my_rating->average_rating>4)?'selected':'';?>' title='Awesome'>
                                            <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>4 And $my_rating->average_rating < 5)?$style:'';?>padding-right:0px;"></i>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </p>
                        </div>
                        <div class="rating-set">
                        <p class="rates m-0">
                            <?php
                                $my_rating = $this->db->query("select * from " . get_school_db() . ".teacher_rating WHERE teacher_id = ".$teacher_arr['teacher_id']." AND school_id =" . $_SESSION['school_id']." AND student_id = ".$_SESSION['student_id']."")->row();
                                
                            ?>
                            Your Rating: <strong><?php echo $my_rating->rating?></strong>
                            <?php
                                if(count($my_rating)==0)
                                    echo "(unrated)";
                            ?>
                            <div class="rate">
                                <?php
                                if(count($my_rating)==0){
                                ?>
                                    <button class="btn btn-primary rate-now" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_rate_teacher/<?php echo $teacher_arr['teacher_id'];?>');" data-teacher="<?php echo $teacher_arr['teacher_id']?>"><?php echo get_phrase('rate_now');?></button>
                                <?php } 
                                else{?>
                                <div class='rating-stars text-center'>
                                    <ul id='stars'>
                                        <li class='star-item <?php echo ($my_rating->rating>0)?'selected':'';?>' title='Poor' data-value='1'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                        <li class='star-item <?php echo ($my_rating->rating>1)?'selected':'';?>' title='Fair' data-value='2'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                        <li class='star-item <?php echo ($my_rating->rating>2)?'selected':'';?>' title='Good' data-value='3'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                        <li class='star-item <?php echo ($my_rating->rating>3)?'selected':'';?>' title='Excellent' data-value='4'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                        <li class='star-item <?php echo ($my_rating->rating>4)?'selected':'';?>' title='Awesome' data-value='5'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                    </ul>
                                </div>
                                <?php } ?>
                            </div>
                        </p>
                        </div>
                </div>
                </div>
                <?php }?>
        </div>

</div>
</div>
<script>
    $(document).ready(function(){
        // $(".rate-now").click(function(){
        //     alert($(this).data('teacher'));
        //     var teacher_id = $(this).data('teacher');
        //     $.ajax({
        //             type: 'POST',
        //         data: {
        //             teacher_id: teacher_id,
        //         },
        //         url: "<?php echo base_url();?>modal/popup/modal_rate_teacher/",
        //         dataType: "html",
        //         success: function(response) {
        //           // $("#select").attr('disabled','disabled');
        //             $("#loading").remove();
        //             $("#stud").html(response);
        //         }
        //     });
        // });
    });
</script>
