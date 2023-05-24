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
                <?php echo get_phrase('books'); ?> 
            </h3>
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i>Interactive tutorial</b>
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered cursor table-hover table_export" data-step="2" data-position='top' data-intro="Books records">
                <thead>
                    <tr>
                        <th style="width:34px;">
                            <div>#</div>
                        </th>
                        <th>
                            <div>
                                <?php echo get_phrase('book_details');?>
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
                            <div class="member-entry"> 
                                <a href="#" class="member-img"> 
                                    <img src="<?php  if($row['book_cover']!=''){
                                        echo base_url() . "uploads/".$_SESSION['folder_name']."/library_book_cover/" . $row['book_cover'];
                                    }else{
                                        echo base_url().'/assets/images/book.png';
                                    } ?>" class="img-rounded">
                                    <i class="entypo-forward"></i> 
                                </a> 
                                <div class="member-details"> 
                                    <h4> 
                                        <a href="#"><?php echo $row['book_title'];?> </a> 
                                    </h4> 
                                    <div class="row info-list"> 
                                        <div class="col-sm-4"> 
                                            <strong>Barocde:</strong>
                                            <?php echo $row['bookId'];?>
                                            <br>
                                            <strong>ISBN No:</strong>
                                            <?php echo $row['isbn_no'];?>
                                            <br>
                                            <strong>Book Type:</strong>
                                            <?php 
                                                if($row['book_type'] == '1')
                                                {
                                                    echo "<span class='badge badge-primary'>Paper Book</span>";
                                                }else if($row['book_type'] == '2')
                                                {
                                                    echo "<span class='badge badge-primary'>E-book</span>";
                                                }
                                            ?>
                                            <br>
                                            <strong>Book Status:</strong>
                                            <?php 
                                                if($row['status'] == '1')
                                                {
                                                    echo "<span class='badge badge-success'>Active</span>";
                                                }else if($row['book_type'] == '2')
                                                {
                                                    echo "<span class='badge badge-danger'>Inactive</span>";
                                                }
                                            ?>
                                        </div> 
                                        <div class="col-sm-4"> 
                                            <strong>Edition:</strong>
                                            <?php echo $row['edition']; ?>
                                            <br>
                                            <strong>Volume:</strong>
                                            <?php echo ucfirst($row['volume']);?>
                                            <br>
                                            <strong>Author:</strong>
                                            <?php echo ucfirst($row['author']);?>
                                            <br>
                                            <strong>Language:</strong>
                                            <?php echo ucfirst($row['language']);?>
                                        </div> 
                                        <div class="col-sm-4"> 
                                            <strong>Price:</strong>
                                            <?php echo $row['price']; ?>
                                            <br>
                                            <strong>Quantity:</strong>
                                            <?php echo $row['quantity']; ?>
                                            <br>
                                            <strong>Book Register Date:</strong>
                                            <?php echo convert_date(date('d-m-Y', strtotime($row['add_book_date']))); ?>
                                            <br>
                                            <?php if($row['ebook_file'] != ""): ?>
                                            <br><br>
                                            <strong>Ebook File:</strong>
                                            <a class="text-dark" href="<?=base_url()?>/uploads/<?= $_SESSION['folder_name'] ?>/library_ebooks/<?= $row['ebook_file'] ?>" download="">
                                                Download <i class="entypo-download"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div> 
                                        <div class="clear"></div> 
                                        <div class="col-sm-12 col-xs-12"> 
                                            <ul class="memeber_entry_buttons mt-4">
                                                <?php //if (right_granted('staff_manage')){ ?>
                                                <li>
                                                    <a onclick='showAjaxModal("<?php echo base_url();?>modal/popup/add_book_modal/<?php echo str_encode($row['book_id']);?>")' href="#" class="btn btn-warning btn-sm">
                                                        <i class="entypo-pencil"></i>
                                                        <?php echo get_phrase('edit');?>
                                                    </a>
                                                </li>
                                                <?php //}if (right_granted('staff_delete')){ ?>
                                                <li class="divider"></li>
                                                <li>
                                                    <a href="#" onclick="confirm_modal('<?php echo base_url();?>library/delete_book/<?php echo str_encode($row['book_id']);?>/<?php echo $row['book_cover'];?>/<?php echo $row['ebook_file'];?>');" class="btn btn-danger btn-sm">
                                                        <i class="entypo-trash"></i>
                                                        <?php echo get_phrase('delete');?>
                                                    </a>
                                                </li>
                                                <?php //} ?>
         
                                            </ul> 
                                        </div> 
                                    </div> 
                                </div> 
                            </div>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        <!--Datatables Add Button Script-->
        var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/add_book_modal/")';
        var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new book' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_book');?></a>";    
    </script>