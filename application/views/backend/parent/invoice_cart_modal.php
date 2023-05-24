<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-title black2">
		<i class="entypo-plus-circled"></i>
        <?php echo get_phrase('view_cart');?>
    </div>
    <div class="panel-body">
	    <form id="data" method="post" enctype="multipart/form-data" class="form-horizontal form-groups-bordered validate">
            <div class="form-group">
                
            </div>
            <div class="form-group">
                <div class="float-right">
        			<button type="submit" id="save_btn" class="modal_save_btn">
        				<?php echo get_phrase('save');?>
        			</button>
        			<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
        				<?php echo get_phrase('cancel');?>
        			</button>
        		</div>
	        </div>
        </form>                
    </div>  
</div> 

<script>
    $(document).ready(function(){
            
    });
</script>