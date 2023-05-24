<?php
    $id = $this->uri->segment(4);
?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
            		<?php echo get_phrase('Reserve_your_book'); ?>
            	</div>
            </div>
            <div class="panel-body">
            <div class="box-content">
                <?php echo form_open(base_url().'student_p/book_reserve/' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('book_collection_date');?></label>
                        <input type="date" class="form-control" name="book_collect_date">
                        <input type="hidden" name="book_id" value="<?= $id ?>">
                    </div>
                    
                    <div class="form-group">
                        <div class="float-right">
                            <button type="submit" class="modal_save_btn">
                                <?php
                        		    echo get_phrase('reserve_book');
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