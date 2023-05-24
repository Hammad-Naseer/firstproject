<?php
    $book_id = $this->uri->segment(4);
    $login_id = $this->uri->segment(5);
    $id = $this->uri->segment(6);
?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
            		<?php echo get_phrase('issue_book'); ?>
            	</div>
            </div>
            <div class="panel-body">
            <div class="box-content">
                <?php echo form_open(base_url().'library/book_issue_from_reserve/' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                    <div class="form-group" style="padding-bottom:0px !important;">
                        <label class="control-label"><?php echo get_phrase('issue_date');?> <span class="star">*</span></label>
                        <input type="date" class="form-control" name="issue_date" value="<?= date("d-m-Y") ?>" required/>
                        <input type="hidden" name="user_login_detail_id" value="<?= $login_id ?>">
                        <input type="hidden" name="book_id" value="<?= $book_id ?>">
                        <input type="hidden" name="id" value="<?= $id ?>">
                    </div>
                    <div class="form-group" style="padding-bottom:0px !important;">
                        <label class="control-label"><?php echo get_phrase('return_date');?> <span class="star">*</span></label>
                        <input type="date" class="form-control" name="return_date" value="<?= date("d-m-Y") ?>" required/>
                    </div>
                    <div class="form-group" style="padding-bottom:0px !important;">
                        <label class="control-label"><?php echo get_phrase('note');?></label>
                        <textarea class="form-control" name="note"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="float-right">
                            <button type="submit" class="modal_save_btn">
                                <?php echo get_phrase('book_issue'); ?>
                            </button>
                            <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                <?php echo get_phrase('cancel');?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>
