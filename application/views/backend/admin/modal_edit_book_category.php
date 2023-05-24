<?php 
$edit_data		=	$this->db->get_where('book_category' , array('book_category_id' => $param2) )->result_array();

?>

<div class="tab-pane box active" id="edit" style="padding: 5px">
    <div class="box-content">
        <?php foreach($edit_data as $row):?>
        <?php echo form_open(base_url().'admin/book_category/do_update/'.$row['book_id'] , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('name');?></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="name" value="<?php echo $row['name'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-5">
                      <button type="submit" class="btn btn-info"><?php echo get_phrase('edit_category');?></button>
                  </div>
                </div>
        </form>
        <?php endforeach;?>
    </div>
</div>