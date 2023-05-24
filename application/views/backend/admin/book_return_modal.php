<?php
    $id = $this->uri->segment(4);
    $edit_data = $this->db->where("book_id",str_decode($id))->get(get_school_db().".books")->row();
?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
            		<?php
            		    echo get_phrase('book_return');
            		?>
            	</div>
            </div>
            <div class="panel-body">
            <div class="box-content">
                <?php echo form_open(base_url().'library/return_book/'.$id , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                    <div class="form-group" style="padding-bottom:0px !important;">
                        <label class="control-label"><?php echo get_phrase('actual_return_date');?> <span class="star">*</span></label>
                        <input type="date" class="form-control" name="actual_return_date" value="<?= date("d-m-Y") ?>" required/>
                    </div>
                    <div class="form-group" style="padding-bottom:0px !important;">
                        <label class="control-label"><?php echo get_phrase('fine');?> <span class="star">*</span></label>
                        <input type="number" class="form-control" name="fine" value="0" required/>
                    </div>
                    <div class="form-group">
                        <div class="float-right">
                            <button type="submit" class="modal_save_btn">
                                <?php echo get_phrase('book_return'); ?>
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
