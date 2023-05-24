<style>
    [class*=" entypo-"]:before, [class^="entypo-download"]:before{
        color:black !important;
    }
</style>
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
                <?php echo get_phrase('book_reserve_request'); ?>
            </h3>
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i>Interactive tutorial</b>
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered cursor table-hover table_export" data-step="1" data-position='top' data-intro="Book Reserve Request records">
                <thead>
                    <tr>
                        <th style="width:34px;">
                            <div>#</div>
                        </th>
                        <th>
                            <div>
                                <?php echo get_phrase('book_name');?>
                            </div>
                        </th>
                        <th>
                            <div>
                                <?php echo get_phrase('user');?>
                            </div>
                        </th>
                        <th>
                            <div>
                                <?php echo get_phrase('book_collect_date');?>
                            </div>
                        </th>
                        <th>
                            <div>
                                <?php echo get_phrase('action');?>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $j=0;
                        foreach($books as $row):
                        $j++;
                    ?>
                    <tr>
                        <td class="td_middle">
                            <?php echo $j; ?>
                        </td>
                        <td>
                            <?php echo $row['book_title']; ?>
                            <br>
                            <b>Available Quantity : </b> <?= $row['quantity'] ?>
                        </td>
                        <td>
                            <?php echo student_name_section($row['user_login_detail_id']) ?>
                        </td>
                        <td>
                            <?php 
                                echo $row['book_collect_date'];
                                if($row['book_collect_date'] < date("Y-m-d"))
                                {
                                    echo "<br><b class='text-danger'>Request Expire</b>";      
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if($row['status'] == '0'):
                            ?>
                            <button type="button" class="btn btn-primary" style="padding: 3px 10px 3px 10px !important;" onclick='showAjaxModal("<?php echo base_url();?>modal/popup/book_reserve_move_to_issue_modal/<?php echo $row['book_id'];?>/<?php echo $row['user_login_detail_id'];?>/<?php echo $row['brr_id'];?>")'>Book Issue</button>
                            <button type="button" class="btn btn-primary" onclick="confirm_modal('<?php echo base_url();?>library/cancel_book_reserve_request/<?php echo $row['brr_id'];?>');" style="background: #ec5956 !important;padding: 3px 10px 3px 10px !important;">Cancel Request</button>
                            <?php elseif($row['status'] == '1'): ?>
                            <button type="button" class="btn btn-success" style="background: #00a651 !important;padding: 3px 10px 3px 10px !important;">Book Issued</button>
                            <?php else: ?>
                            <button type="button" class="btn btn-primary" style="background: #ec5956 !important;padding: 3px 10px 3px 10px !important;">Request Canceled</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
