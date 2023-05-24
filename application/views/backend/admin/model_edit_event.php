<style>
.modal {
    display: none;
    overflow: auto;
    overflow-y: scroll;
    overflow-y: auto;
    position: fixed;
    top: 168px;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1050;
    -webkit-overflow-scrolling: touch;
    outline: 0;
}.m,
</style>
<?php 
$edit_data		=	$this->db->get_where(get_school_db().'.evenement' , array('id' => $param2) )->result_array();

?>

<div class="tab-pane box active" id="edit" style="padding: 5px" style="max-width:80%;">
 
    <div class="box-content">
        <?php foreach($edit_data as $row):?>
<?php 
echo form_open(base_url().'',array('id'=>'disable_submit_btn','class' => 'editevent form-horizontal form-groups-bordered validate','target'=>'_top','onSubmit'=>'return false;'));
?>
<div class="form-group">
<label class="col-sm-4 control-label"><?php echo get_phrase('event_title');?></label>
<div class="col-sm-8"style="margin-bottom:7px;">
<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['id'];?>"/>
<input type="text" class="form-control" id="title" name="title" value="<?php echo $row['title'];?>" required=""/>
</div>
</div>
<div class="form-group">
<label class="col-sm-4 control-label"><?php echo get_phrase('start_date');?></label>
<div class="col-sm-8"style="margin-bottom:7px;">
<input id="start_date" type="text"
   class="datepicker form-control "
 name="start_date" value="<?php  
    $start_ary=explode('-',$row['start']);
echo $start_ary[1].'/'.$start_ary[2].'/'.$start_ary[0]; ?>"></div>
    </div>
<div class="form-group">            
<label class="col-sm-4 control-label"><?php echo get_phrase('end_time');?></label>
<div class="col-sm-8"style="margin-bottom:7px;">
<input id="end_date" type="text" class="datepicker form-control "
  name="end_date"
value="<?php  
$end_ary=explode('-',$row['end']);
echo $end_ary[1].'/'.$end_ary[2].'/'.$end_ary[0]; ?>">
</div>
<div class="form-group">
<div class="col-sm-offset-3 col-sm-5">
<button type="submit" onClick="return edit();" class="btn btn-info" style="margin-left:8px;"><?php echo get_phrase('edit_event');?>
</button>
<button onClick="return deleteevent();" type="submit" class="btn btn-danger"><?php echo get_phrase('delete_event');?></button>
                  </div>
                </div>         
        </form>
        <?php endforeach;?>
    </div>
</div>
</div>
<script type="text/javascript">
$('.clockpicker').clockpicker()
	.find('input').change(function(){
		console.log(this.value);
	});
$('.single-input1').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	'default': '09:00'
});
$('.single-input2').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	'default': '09:00'
});
</script>