<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-title black2">
		<i class="entypo-plus-circled"></i>
        <?php echo get_phrase('view_cart');?>
    </div>
    <div class="panel-body">
	    <form id="data" method="post" enctype="multipart/form-data" class="form-horizontal form-groups-bordered validate">
            <div class="modal_data_pass">
                <!--Here Pass Modal Data-->
            </div>
            <div class="form-group">
                <div class="float-right">
        			<button type="submit" id="save_btn" class="modal_save_btn">
        				<?php echo get_phrase('pay_now');?>
        			</button>
        			<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
        				<?php echo get_phrase('cancel');?>
        			</button>
        		</div>
	        </div>
        </form>                
    </div>  
</div> 


<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="data" method="post" enctype="multipart/form-data" class="form-horizontal form-groups-bordered validate">
            <div class="modal_data_pass">
                <!--Here Pass Modal Data-->
            </div>
      </div>
      <div class="modal-footer">
            <div class="float-right">
    			<button type="submit" id="save_btn" class="modal_save_btn">
    				<?php echo get_phrase('pay_now');?>
    			</button>
    			<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
    				<?php echo get_phrase('cancel');?>
    			</button>
    		</div>
        </form>
      </div>
    </div>
  </div>
</div>