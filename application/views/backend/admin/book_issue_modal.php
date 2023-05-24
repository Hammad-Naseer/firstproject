<?php
    $id = $this->uri->segment(4);
    $edit_data = $this->db->where("book_id",$id)->get(get_school_db().".books")->row();
?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
            		<?php
            		    if(isset($id)):
            		        $url = "update_book";
            		        echo get_phrase('edit_issue_book');
            		    else:
            		        $url = "book_issue";
            		        echo get_phrase('issue_book');
            		    endif;
            		?>
            	</div>
            </div>
            <div class="panel-body">
            <div class="box-content">
                <?php echo form_open(base_url().'library/'.$url.'/' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                    <div class="form-group" style="padding-bottom:0px !important;">
                        <label class="control-label" for="member"><?php echo get_phrase('members');?> <span class="star">*</span></label>
                        <select id="library_member" class="select2 form-control wizard_validate" name="member" required="required" class="form-control" required="">
                            <?php echo library_members();?>
                        </select>
                    </div>
                    <div class="form-group" style="padding-bottom:0px !important;">
                        <label class="control-label" for="library_books"><?php echo get_phrase('library_books');?> <span class="star">*</span></label>
                        <select id="library_books" class="select2 form-control wizard_validate" name="library_books" required="required" class="form-control" required="">
                            <?php echo library_books();?>
                        </select>
                    </div>
                    <div class="form-group" style="padding-bottom:0px !important;">
                        <label class="control-label"><?php echo get_phrase('issue_date');?> <span class="star">*</span></label>
                        <input type="date" class="form-control" name="issue_date" value="<?= date("d-m-Y") ?>" required/>
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
