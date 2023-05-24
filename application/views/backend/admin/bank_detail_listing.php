<?php  
if($this->session->flashdata('club_updated')){
    echo '<div align="center">
    <div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
    '.$this->session->flashdata('club_updated').'
    </div> 
    </div>';
}

if($this->session->flashdata('error_msg')){
    echo '<div align="center">
    <div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
    '.$this->session->flashdata('error_msg').'
    </div> 
    </div>';
}
?>
<script>
$(window).on("load",function(){
    setTimeout(function(){
        $('.alert').fadeOut(3000);
    });
});
</script>

<a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
    <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
</a>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php echo get_phrase('bank_account_details');?>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0px;">
        <table data-step="2" data-position='top' data-intro="bank record" class="table table-bordered table-hover table-condensed  datatable" id="banks">
            <thead>
            <tr>
                <th style="width: 20px;"><?php echo get_phrase('sr');?>#</th>
                <th><?php echo get_phrase('bank_details');?></th>
                <th><?php echo get_phrase('branch_details');?></th>
                <th><?php echo get_phrase('account_details');?></th>
                <th><?php echo get_phrase('description');?></th>
                <th><?php echo get_phrase('action');?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $count = 0;
            foreach ($bank_arr as $key => $value)
            {
                $count++;
            ?>
                <tr>
                    <td class="td_middle"><?php echo $count;?></td>
                    <td>
                        <strong>
                        <?php echo get_phrase('bank_name');?></strong> : <?php echo $value['bank_name'];?> <br>
                        <strong>
                        <?php echo get_phrase('bank_address');?></strong> : <?php echo $value['bank_address'];?> <br>
                        <strong>
                        <?php echo get_phrase('status');?></strong> :
                        <?php
                        $color = "";
                        if ($value['status']==0)
                        {
                            $color = "red";
                        }else{
                            $color = "green";
                        }
                        ?>
                        <span style="color: <?php echo $color;?>">
                        <?php echo status_active($value['status']); ?>
                        </span>
                          <br>
                    </td>
                    <td>
                        <strong>
                        <?php echo get_phrase('branch_name');?></strong> : <?php echo $value['branch_name'];?> <br>
                        <strong>
                        <?php echo get_phrase('branch_code');?></strong> : <?php echo $value['branch_code'];?> <br>
                    </td>
                    <td>
                        <strong>
                        <?php echo get_phrase('account_title');?></strong> : <?php echo $value['account_title'];?> <br>
                        <strong>
                        <?php echo get_phrase('account');?> #</strong> : <?php echo $value['account_number'];?> <br>
                        <strong>
                        <?php echo get_phrase('account_type');?></strong> : <?php echo $value['account_type'];?> <br>
                        <strong>
                        <?php echo get_phrase('i-ban');?> #</strong> : <?php echo $value['iban_number'];?> <br>
                    </td>
                    <td>
                        <?php echo $value['description'];?>
                    </td>
                    <td class="td_middle">
                        <div class="btn-group" data-step="3" data-position='left' data-intro="bank edit / delete options">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                <li>
                                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_cheque_book/<?php echo $value["bank_account_id"];?>');">
                                        <i class="fas fa-money-bill-alt"></i>
                                        <?php echo get_phrase('add_cheque_book');?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <?php 
                                    $check_chequebook = $this->db->where("bank_id",$value['bank_account_id'])->get(get_school_db().".bank_cheque_books")->num_rows();
                                    if($check_chequebook > 0){
                                ?>
                                <li>
                                    <a href="<?= base_url()?>bank_detail/view_cheque_book_details/<?php echo str_encode($value['bank_account_id']);?>">
                                        <i class="entypo-eye"></i>
                                        <?php echo get_phrase('view_cheque_book');?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <?php } ?>
                                <li>
                                    <a href="<?php echo base_url();?>bank_detail/add_edit_bank/<?php echo str_encode($value['bank_account_id']);?>">
                                        <i class="entypo-pencil"></i>
                                        <?php echo get_phrase('edit');?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?php echo base_url();?>bank_detail/add_edit/delete/<?php echo str_encode($value['bank_account_id']);?>" onclick="confirm_modal('<?php echo base_url();?>bank_detail/add_edit/delete/<?php echo $value['bank_account_id'];?>');">
                                        <i class="entypo-trash"></i>
                                    <?php echo get_phrase('delete');?>
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
</div>

<script>
$(document).ready(function() {
    $('#banks').DataTable({
        "aLengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "bStateSave": true
    });
});


var datatable_btn_url = '<?php echo base_url();?>bank_detail/add_edit_bank';
var datatable_btn     = "<a href="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new bank' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_bank');?></a>";    



</script>