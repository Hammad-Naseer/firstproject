<?php 
$edit_data		=	$this->db->get_where('book_request' , array('request_id' => $param3) )->result_array();
$expiry=[];
$issue=$this->db->get_where('book_issue' , array('request_id' => $param3) )->result_array();
?>

<div class="tab-pane box active" id="edit" style="padding: 5px">
	<script src="<?php echo base_url();?>/assets/js/jquery-1.11.0.min.js"></script>
    <script src="<?php echo base_url();?>/assets/js/bootstrap-datepicker.js"></script>

<script>
    				$("input[name='due_date'],input[name='collect_date']").datepicker({
    					startDate:"+0d",
    					endDate:"+0d"
				});
</script>
    <div class="box-c
    <div class="box-content">
        <?php foreach($edit_data as $row):
            foreach($issue as $book):
            $x=0;?>
        <?php echo form_open(base_url().'library/book_request/do_collect/'.$row['book_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','onSubmit'=>'return validate();'));?>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('due_date');?></label>
                    <div id="collectdate" class="col-sm-5">
 
                        <input type="text" id="expiryDate" class="form-control" name="expiry_date" value="<?php echo $book['expiry_date'];?>" readonly/>
                    </div>
                    </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('collect_date');?></label>
                    <div id="collectdate" class="col-sm-5">
                    	<input type="hidden" class="form-control" name="book_id" value="<?php echo $row['book_id']; ?>" required/>
						<input type="hidden" class="form-control" name="request_id" value="<?php echo $param3; ?>" required/>
                        <input type="hidden" class="form-control" name="issue_id" value="<?php echo $param2; ?>" required/>

                        <input type="text" id="collectDate" class="form-control" name="collect_date" required/>
                    </div>
                    </div>
        
       <script type="text/javascript">
        $(document).ready(function(){
            $('.datepicker').change(function(){
    var date=$(this).data('datepicker').getFormattedDate('yyyy-mm-dd');
    $(this).attr('value',date);
});

            $('#collectDate').change(function(){
                var collectDate=$('#collectDate').val();
                var expiryDate=$('#expiryDate').val();
                var date=expiryDate.split(' ');
                var eDate=date[0].split('-');
                var eDateFinal=new Date(eDate[0],eDate[1]-1,eDate[2]);
                var cDate=collectDate.split('/');
                var cDateFinal=new Date(cDate[2],cDate[0]-1,cDate[1]);
                    if(cDateFinal<=eDateFinal){
                        $('#fine').hide();
                        $('#collectFine').removeAttr('required');
                    }
                    else{
                    	var one_day=1000*60*60*24;
                    	var date1_ms = cDateFinal.getTime();
  			var date2_ms = eDateFinal.getTime();
  			var difference_ms = date1_ms - date2_ms;
  			difference_ms = difference_ms/1000;
  			difference_ms = difference_ms/60; 
  			difference_ms = difference_ms/60; 
  			var days = Math.floor(difference_ms/24);
  			var fine=days*30;
                        $('#fine').show();
                        $('#collectFine').attr('required');
                        $('#collectFine').attr('value',fine);
                    }

            });
            $('#fine').hide();
        });
        </script>
        
        
        <div id="fine" class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('fine imposed');?></label>
                        <div class="col-sm-5">
                        <input type="text" id="collectFine" class="form-control" name="fine" />
                    </div>
        </div>
                
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-5">
                      <button type="submit" class="btn btn-info"><?php echo get_phrase('collect_book');?></button>
                  </div>
                </div>
        </form>
        <?php
        $x++;
        endforeach;
        endforeach;?>
    </div>
</div>