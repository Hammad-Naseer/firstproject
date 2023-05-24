<?php 
$edit_data		=	$this->db->get_where('book_request' , array('request_id' => $param2) )->result_array();
$d=explode('/',date('Y/m/d'));
$dateToday=$d[1].'/'.$d[2].'/'.$d[0];
?>

<div class="tab-pane box active" id="edit" style="padding: 5px">
	<script src="<?php echo base_url();?>/assets/js/jquery-1.11.0.min.js"></script>
        <script src="<?php echo base_url();?>/assets/js/bootstrap-datepicker.js"></script>

<script>
    				$("input[name='due_date'],input[name='collect_date']").datepicker({
    					startDate:"+0d",
				});
</script>
    <div class="box-content">
        <?php foreach($edit_data as $row):?>
        <?php echo form_open(base_url().'library/book_request/do_update/'.$row['request_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','onSubmit'=>'return validate();'));?>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('due_date');?></label>
                    <div id="duedate" class="col-sm-5">
                    	<input type="hidden" class="form-control" name="request_id" value="<?php echo $row['request_id'];?>" required/>
                        <input type="hidden" class="form-control" name="book_id" value="<?php echo $row['book_id'];?>" required/>
                        <input type="text" class="form-control" name="due_date" required/>
                    </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-5">
                      <button type="submit" class="btn btn-info"><?php echo get_phrase('issue_book');?></button>
                  </div>
                </div>
        </form>
        <?php endforeach;?>
    </div>
</div>