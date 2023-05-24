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
                <?php echo get_phrase('book_Issue'); ?> 
            </h3>
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i>Interactive tutorial</b>
            </a>
        </div>
    </div>
    
    <!--<form action="<?php echo base_url(); ?>departments/classes" method="post" data-step="2" data-position='top' data-intro="select this button to get record specific classes">-->
    <!--    <div class="row filterContainer" style="padding-top: 14px;margin:0px;">-->
    <!--        <div class="col-lg-6 col-md-6 col-sm-6 form-group">-->
    <!--            <select id="section_id1" class="selectpicker form-control wizard_validate" name="section_id" required="required" class="form-control" required="">-->
    <!--                        <?php echo section_selector();?>-->
    <!--                    </select>-->
    <!--            <div class="err_div"></div>-->
    <!--        </div>-->
    <!--        <div class="form-group">-->
    <!--            <button class="btn btn-primary" id="btn_submit" style="padding:5px 20px !important; ">-->
    <!--                <?php echo get_phrase('filter'); ?>-->
    <!--            </button>-->
    <!--            <a href="<?php echo base_url(); ?>departments/classes" <?php $val_val=$this->input->post('department_id');if(isset($val_val) && $val_val!=""){}else{?>style="display:none;"<?php } ?> class="btn btn-danger" id="btn_remove" style="padding:5px 5px !important; "><i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</form>-->
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
                                <?php echo get_phrase('details');?>
                            </div>
                        </th>
                        <th>
                            <div>
                                <?php echo get_phrase('date');?>
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
                            <b>Memeber : </b> <?php echo  student_name_section($row['member_id']) ?>
                            <br>
                            <b>Book : </b> <?php echo $row['book_title'] ?> - <?php echo $row['bookId'] ?>
                            <br>
                            <b>Note : </b> <?php echo $row['note'] ?>
                            <br>
                            <b>Fine : </b> <?php echo $row['fine'] ?>
                            <br>
                            <b>Status : </b> 
                            <?php if(date("Y-m-d",strtotime($row['book_return_date'])) < date("Y-m-d") && $row['actual_return_date'] == ""): ?>
                                <span class="badge badge-warning">Overdue</span>
                            <?php elseif(date("Y-m-d",strtotime($row['book_return_date'])) > date("Y-m-d") && $row['actual_return_date'] == ""): ?>
                                <span class="badge badge-primary">Pending</span>   
                            <?php elseif(date("Y-m-d",strtotime($row['book_return_date'])) < date("Y-m-d",strtotime($row['actual_return_date']))): ?>
                                <span class="badge badge-danger">Late Return</span>   
                            <?php elseif(date("Y-m-d",strtotime($row['book_return_date'])) == date("Y-m-d",strtotime($row['actual_return_date'])) || date("Y-m-d",strtotime($row['book_return_date'])) == date("Y-m-d",strtotime($row['actual_return_date']))): ?>
                                <span class="badge badge-success">Return On Time</span>       
                            <?php endif; ?>
                        </td>
                        <td>
                            <b>Issue Date : </b> <?php echo convert_date(date('d-m-Y', strtotime($row['book_issue_date']))); ?>
                            <br>
                            <b>Return Date : </b> <?php echo convert_date(date('d-m-Y', strtotime($row['book_return_date']))) ?>
                            <br>
                            <b>Actual Return Date : </b> <?php if($row['actual_return_date'] != ""): echo convert_date(date('d-m-Y', strtotime($row['actual_return_date']))); endif; ?>
                        </td>
                        <td>
                            <button onclick='showAjaxModal("<?php echo base_url();?>modal/popup/book_return_modal/<?= $row['book_issue_id'] ?>")' type="button" class="btn btn-primary">Return</button>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>    
        </div>
    </div>


    <script>
        <!--Datatables Add Button Script-->
        var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/book_issue_modal/")';
        var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new book' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('issue_book');?></a>";    
    </script>