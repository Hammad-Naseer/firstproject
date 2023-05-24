<?php  
 if($this->session->flashdata('flash_message')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('flash_message').'
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
        <h3 class="system_name inline">
            <?php echo get_phrase('general_inquiries');?>
        </h3>
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
    </div>
</div>
<div class="col-lg-12 col-sm-12">
    <table class="table table-bordered cursor table_export table-responsive" data-step="3" data-position='top' data-intro="General Inquiries">
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Personal Details</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $j=0;
                
                foreach($general_inquiries as $row)
                {
                    $j++;
            ?>
                    <tr>
                        <td style="width:44px;" class="td_middle">
                            <?php echo $j;?>
                        </td>
                        <td>
                            <strong>Name : </strong><?php echo $row['name'];?>
                            <br>
                            <strong>Email : </strong><?php echo $row['email'];?>
                            <br>
                            <strong>Mobile # : </strong><?php echo $row['mobile_no'];?>
                            <br>
                            <strong>Inquiry Type : </strong><?php echo inquiries_details($row['inquiry_type']);?>
                            <br>
                            <strong>Status : </strong><?php echo check_general_inquiry_status($row['s_g_i_id']);?>
                        </td>
                        <td>
                            <?php echo $row['inquiry_description'];?>
                        </td>
                        <td class="td_middle">
                            <div class="btn-group" data-step="3" data-position='left' data-intro="bank edit / delete options">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/response_inquiry_modal/<?php echo $row['s_g_i_id'];?>');">
                                            <i class="fas fa-reply"></i>
                                            <?php echo get_phrase('response_inquiry');?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo base_url();?>inquiries/complete_general_inquiry/<?php echo str_encode($row['s_g_i_id']);?>">
                                            <i class="fas fa-check-square"></i>
                                            <?php echo get_phrase('complete_inquiry');?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        
                    </tr>
            <?php  
                }
            ?>
        </tbody>
    </table>
</div>

