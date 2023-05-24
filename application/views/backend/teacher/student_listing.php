<style>
	.myrow 
	{
	    margin-bottom: 50px;
	    margin-top: 10px;
	    border-bottom: 1px solid #00859a;
	    padding-top: 10px;
	    border-top: 1px solid #00859a;
	    padding-bottom: 10px;
	    background-color: rgba(204, 204, 204, 0.14);
	}

	h3 {
	    color: #00859a;
	}

	.myfont {
	    font-size: 18px;
	}
	.message_details {
    	width: 30%;
    	border: 1px solid #e4e4e4;
    	float: left;
    	margin-right: 20px;
    	min-height: 140px;
    	padding: 15px;
    	margin-bottom: 20px;
	}
	.student_sub_detail{
		padding-top:25px;
	}
	.message_details .student_image{
		float:left;
		margin-right:20px;
	}
	.img-responsive{ max-height:70px;}

</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('Messages'); ?>
        </h3>
    </div>
</div>


<form method="post" action="<?php echo base_url();?>teacher/student_list" class="form" data-step="1" data-position='top' data-intro="Please select the filters and press Filter button to get specific records">

<div class="row filterContainer">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<select name="dep_c_s_id" id="dep_c_s_id" class="dcs_list form-control" required>
                <?php echo get_teacher_dep_class_section_list($teacher_section,$section_id);?>
                </select>
    		</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<select name="subject" id="subject_list" class="form-control" required>
                    <option value="">Select Subject</option>
                </select>
    		</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<input type="submit" name="submit" value="<?php echo get_phrase('Filter');?>" class="modal_save_btn"/>
                <?php
                if($filter)
                {
                ?>
                    <a href="<?php echo base_url()?>teacher/student_list" class="btn btn-danger" >
                        <i class="fa fa-remove"></i>Remove Filters
                    </a>
                <?php
                }
                ?>
    		</div>
		</div>
</div>
</form>

<table id="parent_student_listing">
  <?php 
    $login_detail_id= $_SESSION['login_detail_id'];
	$school_id=$_SESSION['school_id'];

	$alert  =   $this->db->query("select m.* FROM ".get_school_db().".messages m inner join ".get_school_db().".staff on staff.staff_id = m.teacher_id WHERE  staff.user_login_detail_id =$login_detail_id AND m.school_id=$school_id AND m.messages_type = 1 and m.is_viewed=0 ")->result_array();
	$alert_array=array();
	foreach($alert as $row)
    {
	    $student_id=$row['student_id'];
	    $subject_id=$row['subject_id'];
	    if (!isset( $alert_array[$student_id][$subject_id]))
	    {
	        $alert_array[$student_id][$subject_id] = 0;
	    }
	    $alert_array[$student_id][$subject_id]++;   
	}
    if ($section_id > 0)
    {
    	foreach($student_list as $row) { ?>
            <div class="col-sm-4" style="padding:5px;" >
            <div style="border:1px solid #ccc; padding:5px;">
                    <div>
                        <?php 
        		            if($row['image'] == "")
        		        {?>
        		            	<img src="<?php echo get_default_pic();?>" class="img-responsive">
        		        <?php   
        		        	}
        		            else
        		        {?>
        		                <img src="<?php echo display_link($row['image'],'student');?>" class="img-responsive">
        		        <?php
        		        }?>
                     
        			</div>
        			
        			<div class="myttl"><?php echo $row['student_name'];?></div>
        			
        			<div>
        			    <?php $sec_arr = section_hierarchy($section_id);?>
          				<?php echo $row['subject_name'].' - '.$row['subject_code'] ;?>
                    </div>	
                    
                    <div class="text-right">
                        <a href="<?php echo base_url(); ?>teacher/message/<?php  echo $row['student_id'].'/'.$row['subject_id'].'/'.intval($subject_id_selected).'/'.intval($section_id);   ?>">View/Send Message</a>
        			</div>
        			
        			<?php
            			$student_id2=$row['student_id'];
            			$subject_id2=$row['subject_id'];
            			if($alert_array[$student_id2][$subject_id2] > 0){
            			    echo '<span style="color: green;" class="glyphicon glyphicon-record">'.$alert_array[$student_id2][$subject_id2].'</span>';
            			}
        			?>
            </div>
            </div> 
        <?php } }
	
	else {
		
        $sub_in = 0;
        $time_table_t_sub = array_unique(get_time_table_teacher_subject($login_detail_id));
        if (count($time_table_t_sub) > 0)
        {
            $sub_in = implode(',', array_unique($time_table_t_sub));
        }

        $sec_in = 0;
        $time_table_t_sec = array_unique(get_time_table_teacher_section($login_detail_id));
        if (count($time_table_t_sec) > 0)
        {
            $sec_in = implode(',', array_unique($time_table_t_sec));
        }

        $student_list = $this->db->query("select s.student_id,s.image, s.name as student_name, sub.subject_id, sub.name as subject_name, sub.code as subject_code, sub.subject_id, ss.section_id 
                        from ".get_school_db().".student s
                        inner join ".get_school_db().".class_section cs on s.section_id = cs.section_id
                        inner join ".get_school_db().".subject_section ss on ss.section_id = s.section_id 
                        inner join ".get_school_db().".subject sub on sub.subject_id = ss.subject_id 
                        where
                        s.student_status in (".student_query_status().")
                        and sub.subject_id in ($sub_in)
                        and ss.section_id in ($sec_in)
                        and s.school_id=".$_SESSION['school_id']."
                        order by s.name
                    ")->result_array();
        foreach($student_list as $row)
        {
            $student_id2=$row['student_id'];
            $subject_id2=$row['subject_id'];
            if($alert_array[$student_id2][$subject_id2] > 0)
            {
            ?>
                <div class="col-sm-4"  style="border:1px solid #000;  ">
                      <div>
                        <?php 
                            if($row['image'] == "")
                            {?>
                                <img src="<?php echo get_default_pic();?>" class="img-responsive">
                            <?php   
                            }
                            else
                            {?>
                                <img src="<?php echo display_link($row['image'],'student');?>" class="img-responsive">
                            <?php
                            }?>
             		  </div> 
                 <div>
                    <strong><?php echo $row['student_name'];?></strong>
                <?php 
                    $sec_arr = section_hierarchy($row['section_id']); ?>  
                <?php echo $row['subject_name'].' - '.$row['subject_code'] ; ?>
               
                </div>
           		 <div>
                        <a href="<?php echo base_url(); ?>teacher/message/<?php  echo $row['student_id'].'/'.$row['subject_id'].'/'.intval($subject_id_selected).'/'.intval($section_id);   ?>">View/Send Message</a>
                        <?php
                            echo '<span style="color: green;" class="glyphicon glyphicon-record">'.$alert_array[$student_id2][$subject_id2].'</span>';
                        ?>
                 </div>
                </div>
            <?php
            }
        }
    }

    ?>
    
    
    
 
</table>


 <script>
    jQuery(document).ready(function () 
    {
        jQuery('.dcs_list').on('change', function (){
            var id=this.id;
            var selected = jQuery('#'+ id +' :selected');
            var group = selected.parent().attr('label');
            jQuery(this).siblings('label').text(group);
        });
    });
</script>

<script>
$(document).ready(function()
{
    $("#dep_c_s_id").change(function()
    {
        var section_id=$(this).val();
        $(".loader_small").remove();
        $(this).after('<div class="loader_small"></div>');
        $.ajax({
            type: 'POST',
            data: {section_id:section_id},
            url: "<?php echo base_url();?>teacher/get_time_table_subject_list",
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $("#subject_list").html(response);      
            }
        });
    });
    var section_id=$('#dep_c_s_id').val();
    $.ajax({
        type: 'POST',
        data: {section_id:section_id},
        url: "<?php echo base_url();?>teacher/get_time_table_subject_list/<?php echo $subject_id_selected ?>",
        dataType: "html",
        success: function(response) {
            $(".loader_small").remove();
            $("#subject_list").html(response);      
        }
    });

});
</script>




