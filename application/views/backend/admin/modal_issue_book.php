<?php 
$edit_data		=	$this->db->get_where('book_request' , array('request_id' => $param2) )->result_array();

foreach($edit_data as $book){
    $book=$edit_data['expiry_date'];
}
$d=explode('/',date('Y/m/d'));
$dateToday=$d[1].'/'.$d[2].'/'.$d[0];
?>

<div class="tab-pane box active" id="edit" style="padding: 5px">

	<script src="<?php echo base_url();?>/assets/js/jquery-2.1.4.js"></script>
	<script src="<?php echo base_url();?>/assets/js/bootstrap-datepicker.js"></script>

<script>
  var $j = $.noConflict(true); 
     $j (document).ready(function () {
     var today='+0d';
		$j ("input[name='due_date']").datepicker({
    					startDate:today
				});
     });
</script>

    <div class="box-content">
        <?php foreach ($edit_data as $row):?>
        <?php echo form_open(base_url().'admin/book_request/do_update/'.$row['request_id'] , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('request_id');?></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="request_id" value="<?php echo $row['request_id'];?>" readonly/>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('book_id');?></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="book_id" value="<?php echo $row['book_id'];?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('due_date');?></label>
                    <div id="duedate" class="col-sm-5">
                        <input type="text" class="form-control" name="due_date" required/>
                    </div>
                </div>
        <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-5">
                       <button type="submit" class="btn btn-info"><?php echo get_phrase('issue_book');?></button>
                  </div>
                </div>
        </form>
       <?php endforeach; ?>
    </div>
</div>