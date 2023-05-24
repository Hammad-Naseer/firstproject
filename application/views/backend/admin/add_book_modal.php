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
            		    if(isset($id)):
            		        $url = "update_book";
            		        echo get_phrase('edit_book');
            		    else:
            		        $url = "add_book";
            		        echo get_phrase('add_book');
            		    endif;
            		?>
            	</div>
            </div>
            <div class="panel-body">
            <div class="box-content">
                <?php echo form_open(base_url().'library/'.$url.'/' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                    <!--<div class="form-group">-->
                    <!--    <label class="control-label">-->
                    <!--    <?php echo get_phrase('departments');?> <span class="star">*</span></label>-->
                    <!--    <select id="section_id1" class="selectpicker form-control wizard_validate" name="section_id" required="required" class="form-control" required="">-->
                    <!--        <?php echo section_selector($edit_data->section_id);?>-->
                    <!--    </select>-->
                    <!--</div>-->
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('book_bar_code');?></label>
                        <input type="text" class="form-control" name="book_id" value="<?php echo $edit_data->bookId ?>"/>
                        <input type="hidden" class="form-control" name="bid" value="<?php echo $id ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                        <?php echo get_phrase('book_title');?> <span class="star">*</span></label>
                        <input maxlength="100" type="text" class="form-control" name="book_title" value="<?php echo $edit_data->book_title ?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('isbn_number');?></label>
                        <input maxlength="20" type="text" class="form-control" name="isbn_number" value="<?php echo $edit_data->isbn_no ?>" />
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('shelf_no');?></label>
                        <input maxlength="20" type="text" class="form-control" name="shelf_no" value="<?php echo $edit_data->shelf_no ?>" />
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('edition');?></label>
                        <input maxlength="20" type="text" class="form-control" name="edition" value="<?php echo $edit_data->edition ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('volume');?></label>
                        <input maxlength="20" type="text" class="form-control" name="volume" value="<?php echo $edit_data->volume ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('author');?></label>
                        <input maxlength="20" type="text" class="form-control" name="author" value="<?php echo $edit_data->author ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('language');?></label>
                        <input maxlength="20" type="text" class="form-control" name="language" value="<?php echo $edit_data->language ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('price');?></label>
                        <input type="number" class="form-control" name="price" value="<?php echo $edit_data->price ?>" />
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('quantity');?></label>
                        <input type="number" class="form-control" name="quantity" value="<?php echo $edit_data->quantity ?>" />
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('Detail');?></label>
                        <textarea id="description1" maxlength="1000" oninput="count_value('description1','description_count1','1000')" class="form-control" name="description"><?php echo $edit_data->details ?></textarea>
                        <div id="description_count1" class="col-sm-12"></div>
                    </div>
                    <div class="form-group">
						<label for="book_type" class="control-label">
						    <?php echo get_phrase('book_type');?>
						</label>
						<select name="book_type" id="book_type" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
						    <option value="">Select Book Type</option>
						    <option value="1">Paper Book</option>
						    <option value="2">E-book</option>
						</select>
					</div>
					<div class="form-group" id="ebook_file" style="display:none;">
                        <label class="control-label"><?php echo get_phrase('e-book_file');?></label>
                        <input type="file" class="" name="ebook_file" />
                        <input type="hidden" name="old_ebook" value="<?php echo $edit_data->ebook_file ?>" />
                    </div>
                    <div class="form-group">
						<label for="status" class="control-label">
						    <?php echo get_phrase('status');?>
						</label>
						<select name="status" id="status" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
						    <option>Select Status</option>
						    <option value="1">Active</option>
						    <option value="0">Inactive</option>
						</select>
					</div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('book_cover');?></label>
                        <input type="file" class="" name="book_cover" />
                        <input type="hidden" name="old_book_cover" value="<?php echo $edit_data->book_cover ?>" />
                    </div>
                    <div class="form-group">
                        <div class="float-right">
                            <button type="submit" class="modal_save_btn">
                                <?php
                        		    if(isset($id)):
                        		        echo get_phrase('edit_book');
                        		    else:
                        		        echo get_phrase('add_book');
                        		    endif;
                        		?>
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


<script>

    setTimeout(function () {
        $("#status").val('<?php echo $edit_data->status ?>');
        $("#book_type").val('<?php echo $edit_data->book_type ?>');
    }, 500);

    $("#book_type").on("change",function(){
        var type = $(this).val();
        if(type == '2')
        {
            $("#ebook_file").css("display","block");
        }else{
            $("#ebook_file").css("display","none");
        }
    });
</script>