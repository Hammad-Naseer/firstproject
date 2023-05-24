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
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered cursor table-hover table_export" data-step="3" data-position='top' data-intro="classes records">
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
                                            <strong>Book Status:</strong><br>
                                            <?php
                                                if($row['quantity'] > 0){
                                                    echo "<button class='badge badge-success'>Available</button>";
                                                }else{
                                                    echo "<button class='badge badge-danger'>Not Available</button>";
                                                }
                                            ?>
                                            <?php
                                                $book_reserve = $this->db->query("SELECT * FROM ".get_school_db().".book_reserve_request WHERE user_login_detail_id = '".$_SESSION['user_login_id']."' AND book_id = '".$row['book_id']."' ");
                                                if($book_reserve->num_rows() > 0):
                                                    $result = $book_reserve->result();
                                                    if($result[0]->book_collect_date > date("Y-m-d")):
                                            ?>
                                                    <span class="badge badge-danger">Already reserved</span>
                                                <?php else: ?>
                                                <button onclick='showAjaxModal("<?php echo base_url();?>modal/popup/book_reserve_modal/<?php echo $row['book_id'];?>")' class='modal_cancel_btn btn-sm'>Reserve Book</button>
                                                <?php endif; ?>
                                            <?php else: ?>
                                            <button onclick='showAjaxModal("<?php echo base_url();?>modal/popup/book_reserve_modal/<?php echo $row['book_id'];?>")' class='modal_cancel_btn btn-sm'>Reserve Book</button>
                                            <?php endif; ?>
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