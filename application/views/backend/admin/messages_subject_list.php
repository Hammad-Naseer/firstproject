<?php
if (right_granted(array('message_manage')))
{
    if ( $this->session->flashdata( 'club_updated' ) ) {
	    echo '<div align="center">
         <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          ' . $this->session->flashdata( 'club_updated' ) . '
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
            <h3 class="system_name inline capitalize">
                <?php echo get_phrase('messages');?>
            </h3>
        </div>
    </div>
    <form action="<?php echo base_url();?>message/messages_subject_list" id="messages_list" method="post">
        <div class="row filterContainer" style="padding:12px;" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific records">
            <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                    <select id="teacher_id" name="teacher_id" class="form-control" data-validate="required" data-message-required="required">
                        <?php echo get_teacher_option_list(intval($teacher_id));?>
                    </select>
                    <div id="myerror" class="myerror"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                    <select id="section_id" name="section_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <?php echo section_selector(intval($section_id)); ?>
                    </select>
                </div>    
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12">
                <input type="submit" name="submit" class="btn btn-primary" value="<?php echo get_phrase('filter'); ?>">
                <?php if($filter) { ?>
                    <a href="<?php echo base_url(); ?>message/messages_subject_list" class="btn btn-danger" style="padding:5px 8px !important; ">
                        <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                    </a>
                <?php } ?>       
            </div>
        </div>
    </form>
    <div class="col-lg-12 col-sm-12">
        <table class="table table-bordered table_export table-responsive" data-step="2" data-position="top" data-intro="messages record">
        <thead>
            <tr>
                <th width="80">
                <div>
                    <?php echo get_phrase('photo');?>
                </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('message_detail');?>
                    </div>
                </th>
                <th style="width:100px;">
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
		        foreach ( $messages_data as $row ) {
			?>
                <tr>
                    <td>
                        <img class="img-circle" width="30" src="<?php echo ($row['staff_image'] !='' ) ?  display_link($row['staff_image'],'staff'): base_url().'uploads/default.png';?>">
                    </td>        
                    <td>
                        <div class="myttl">
                            <?php echo $row['teacher_name'];?><span style="font-size:12px;"> (<?php echo $row['designation'];?>)</span> </small>
                        </div>
                        <div>
                            <strong>      <?php echo get_phrase('subject');?>: </strong>
                            <?php echo $row['subject_name']. ' - '.$row['subject_code'];?>
                        </div>
                        <div>
                            <strong><?php echo get_phrase('department');?>/<?php echo get_phrase('class');?>/<?php echo get_phrase('section');?>: </strong>
                            <ul class="breadcrumb breadcrumb2">
                                <li>
                                    <?php  echo $row['department'] ; ?>
                                </li>
                                <li>
                                    <?php  echo $row['class'] ; ?>
                                </li>
                                <li>
                                    <?php  echo $row['section'] ; ?>
                                </li>
                            </ul>
                        </div>
                    </td>
                    
                    <td>
                        <div>
                            <?php echo count_subject_unread_messages($row['subject_id'], $row['section_id'], $row['staff_id']); ?>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group" data-step="3" data-position="left" data-intro="press this icon view messages list">
                            <a href="<?php echo base_url();?>message/messages_student_list/<?php echo str_encode($row['subject_id']).'/'.str_encode($row['section_id']).'/'.str_encode($row['staff_id']);?>">
                                <i class="entypo-eye"></i> <?php echo get_phrase('view');?>
                            </a>
                        </div>
                    </td>
                    
               
               
                    
                    
                    
                </tr>
                <?php 
        }
        ?>
        </tbody>
    </table>
    </div>
    
<?php } ?>