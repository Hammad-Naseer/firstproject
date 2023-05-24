<?php
  if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
?>  
<style>
    .topbar{
        font-size: 18px !important;
    }
</style>
    <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
                <?php 
                    $row = $announce_detail[0];
                ?>
                <div><strong>
                                <?php echo get_phrase('title'); ?>
                                : </strong>
                                    <?php echo $row['event_title'];?>
                                </div>
                <div><strong>
                                <?php echo get_phrase('details'); ?>
                                : </strong>
                                    <?php echo $row['event_details'];?>
                                </div>
                                <div><strong>                
                                <?php echo get_phrase('start_date'); ?>
                                : </strong>
                                    <?php echo date_view($row['event_start_date']);?>
                                </div>
                                <div><strong>                
                                <?php echo get_phrase('end_date'); ?>
                                : </strong>
                                    <?php echo date_view($row['event_end_date']);?>
                                </div>
                                <div><strong>
								<?php echo get_phrase('status'); ?>
                                 : </strong>
                                    <?php 
                                        if($row['active_inactive'] == '1')
                                        {
                                            echo "<b class='text-success'>Active</b>";
                                        }else{
                                            echo "<b class='text-danger'>Inactive</b>";
                                        }
                                    ?>
                                </div>
                                <div><strong>
								<?php echo get_phrase('event_status'); ?>
                                 : </strong>
                                    <?php 
                                        if($row['event_status'] == '1')
                                        {
                                            echo "<b class='text-success'>Event Assigned</b>";
                                        }else if($row['event_status'] == '2')
                                        {
                                            echo "<b class='text-danger'>Event Expired</b>";
                                        }else{
                                            echo "<b class='text-danger'>Not Assigne</b>";
                                        }
                                    ?>
                                </div>
            </div>
    </div>
    
    <table class="table table-bordered table_export" id="" data-step="2" data-position="top" data-intro="events announcement record">
                    <thead>
                        <tr>
                            <th style="width:34px">
                                <div>#</div>
                            </th>
                            <th>
                                <div>
                                    <?php echo get_phrase('details');?>
                                </div>
                            </th>
                            <th>
                                <div>
                                    <?php echo get_phrase('response_text');?>
                                </div>
                            </th>
                            <th>
                                <div>
                                    <?php echo get_phrase('response');?>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $r=0;
                        $no = 0;
                        $yes = 0;
                        $no_response = 0;
                        foreach($announce_detail as $row)
                        {
                        $r++;
                        ?>
                        <tr>
                            <td class="td_middle">
                                <?php echo $r;?>
                            </td>
                            <td>
                                <div class="myttl">
                                    <?php echo $row['name'];?>
                                </div>
                                <div><strong>
                                    Class: <?=$row['dep_name']?>
                                     / <?=$row['class_name']?>
                                     / <?=$row['section_name']?>
                                </strong></div>
                            </td>
                            <td>
                                
                                                                <div>
                                    <?php if($row['response_status'] == "1" || $row['response_status'] == "2"){ ?>
                                       <strong>                
                                        <?php echo get_phrase('message'); ?>
                                        : </strong>
                                        <?php echo $row['response_text']; ?>
                                    <?php } ?>
                                </div>
                                <div>
                                    <?php if($row['response_status'] == "1" || $row['response_status'] == "2"){ ?>
                                        <strong>                
                                        <?php echo get_phrase('date'); ?>
                                        : </strong>
                                        <?php echo date_view($row['response_date']); ?>
                                    <?php } ?>
                                </div>  
                            </td>
                            <td class="td_middle">
                                <div>
                                    <?php if($row['response_status'] == '0'){ 
                                        $no_response++;
                                    ?>
                                    <b>No Response</b>
                                    <?php } ?>
                                    <?php if($row['response_status'] == '1'){ 
                                        $yes++;
                                    ?>
                                    <b style="color:green">Yes</b>
                                    <?php } ?>
                                    <?php if($row['response_status'] == '2'){ 
                                        $no++;
                                    ?>
                                    <b style="color:red">No</b>
                                    <?php } ?>
                                </div>

                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                        <th colspan="4" align="right">
                            <b class="text-dark">Total Not Responsed: <?=$no_response;?></b>
                            <br>
                            <b class="text-success">Total Yes Response: <?=$yes;?></b>
                            <br>
                            <b class="text-danger">Total No Response: <?=$no;?></b>
                        </th>
                    </tfoot>
                </table>
   
