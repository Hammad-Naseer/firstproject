<?php
if (right_granted(array('message_manage')))
{
    $subject_id = $this->uri->segment(3);
    $section_id = $this->uri->segment(4);
    $teacher_id = $this->uri->segment(5);

    if($this->session->flashdata('club_updated'))
    {
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
            <h3 class="system_name inline capitalize">
                <?php echo get_phrase('messages');?>
            </h3>
            <a href="<?php echo base_url()?>message/messages_subject_list/" class="btn btn-primary pull-right"> <?php echo get_phrase('back_to_listing');?></a>
        </div>

        <?php
        if (count($messages_data) > 0)
        {?>

<div class="col-sm-12">
    <div class="filterContainer">
        <div class="row">
    		<div class="col-sm-4 myttl">
    			 <?php echo $messages_data[0]['teacher_name'];?>
    			 <span style="font-size:12px;"> (<?php echo $messages_data[0]['designation'];?>)</span> 
    		</div>
    		<div class="col-sm-4 myttl">
    			 <?php echo $messages_data[0]['subject_name']. ' - '.$messages_data[0]['subject_code'];?>
    			
    		</div>
    		<div class="col-sm-4 myttl">
    			<?php  echo $messages_data[0]['department'].'/'.$messages_data[0]['class'].'/'.$messages_data[0]['section'];?>
    			
    		</div>
    	</div>
    </div>
</div> 
    <?php } ?>
</div>
<div class="col-lg-12 col-sm-12">
    <table class="table table-bordered table_export">
        <thead>
            <tr>
                <th width="80">
                    <div>
                        <?php echo get_phrase('photo');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('student_detail');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('unread_messages');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('options');?>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php
        /*echo '<pre>';
        print_r($messages_data);*/
        foreach($messages_data as $row)
        {
			
			
			?>
            <tr>
                <td>
                    <img class="img-circle" width="30" src="<?php echo ($row['student_image'] !='' ) ?  display_link($row['student_image'],'student'): 'uploads/default.png';?>" >
                </td>
                <td>
                    <?php echo $row['student_name'];?> 
                    <br>
                    <strong> <?php echo get_phrase('roll_no');?>: </strong><?php echo $row['roll'];?> 
                </td>
                <td>
                    <?php echo count_student_unread_messages($row['student_id'], $row['subject_id'], $row['section_id'], $row['staff_id'] ); ?>
                </td>
                <td>
                    <a href="<?php echo base_url();?>message/messages/<?php echo str_encode($row['subject_id']).'/'.str_encode($row['student_id']).'/'.str_encode($row['staff_id']).'/'.str_encode($subject_id).'/'.str_encode($section_id).'/'.str_encode($teacher_id);?>">
                        <i class="entypo-eye"></i>
                         <?php echo get_phrase('view');?>
                    </a>
                </td>    
            </tr>
        <?php 
        }
        ?>
        </tbody>
    </table>
</div>
<?php } ?>