<style>
    .modal-header{
    	border-bottom:none !important;
    }
    .modal-open {
        overflow-y: hidden;
    	overflow-x:hidden;
    	height:100% !important;
    }
    .star{padding-left:2px; color:#E7090C !important;}
    .themecolor{color:#507895 !important; font-weight:bold !important;}

    .modal.fade{
      opacity:1;
    }
    .modal.fade .modal-dialog {
       -webkit-transform: translate(0);
       -moz-transform: translate(0);
       transform: translate(0);
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>
    
<script>

	function showAjaxModal(url)
	{
	   // alert(url);
		//jQuery('#modal_ajax .modal-body').html('<div style="text-align:center;margin-top:600px;"><img src="<?php echo base_url();?>assets/images/preloader.gif" /></div>');
		//jQuery('#modal_ajax').modal('show', {backdrop: 'true'});
		$('#modal_ajax').modal('show',{backdrop: 'static', keyboard: false})  
		$.ajax({
			url: url,
			success: function(response)
			{
			    jQuery('#modal_ajax .modal-body').html(response);
                		$(".datepicker").keydown(function(event){
        				event.preventDefault();
    			});
			}
		});
	}
	
	
	$(document).on('show.bs.modal','.eduModal', function () {
           var anim =    'fadeIn'; 
           applyAnimationOnModel(anim);
    });
    
    $(document).on('hide.bs.modal','.eduModal', function () {
        var anim = 'fadeOut';
        applyAnimationOnModel(anim);
    });
	
	function applyAnimationOnModel(x) {
        $('.modal .modal-dialog').attr('class', 'modal-dialog  ' + x + '  animated');
    };
    

	
</script>
	
	
	
	
    
    <!-- (Ajax Modal)-->
    
    <div class="modal fade eduModal" id="modal_ajax" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
          </div>
        </div>
      </div>
    </div>
    
    
    <!-- 
    <div id="modal_ajax"  class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
     	 <div class="modal-dialog" role="document">
             <div class="modal-content">
      			<div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    -->
    

    
    <script type="text/javascript">
	function confirm_modal(delete_url)
	{
	    $('#modal-4').modal('show',{backdrop: 'static', keyboard: false}); 
		//jQuery('#modal-4').modal('show', {backdrop: 'static'});
		document.getElementById('delete_link').setAttribute('href' , delete_url);
	}
	</script>
    
    <!-- (Normal Modal)-->
    <div class="modal fade eduModal" id="modal-4" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:100px;">
                <div class="modal-header">
                    <h4 class="modal-title" style="text-align:center;">Are you sure to Continue ?</h4>
                    <button type="button" class="close" data-dismiss="modal"aria-hidden="true">&times;</button>
                </div>
                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                    <a href="#" class="btn btn-danger" id="delete_link"><?php echo get_phrase('Yes');?></a>
                    &nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-danger" data-dismiss="modal" style="background-color:#F44336!important;border:#f34336 1px solid!important"><?php echo get_phrase('cancel');?></button>
                </div>
            </div>
        </div>
    </div>
    
    
    
    
    <script type="text/javascript">
	function confirm_modal_update(delete_url)
	{
	    $('#modal-5').modal('show',{backdrop: 'static', keyboard: false}); 
		//jQuery('#modal-5').modal('show', {backdrop: 'static'});
		document.getElementById('delete_link1').setAttribute('href' , delete_url);
	}
	</script>
    
    <!-- (Normal Modal)-->
    <div class="modal fade eduModal" id="modal-5" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:100px;">
                
                <div class="modal-header">
                    <h4 class="modal-title" style="text-align:center;">Are you sure to deny this request of book?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                
                
                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                    <a href="#" class="btn btn-danger" id="delete_link1"><?php echo get_phrase('yes');?></a>
                    <button type="button" class="btn btn-info" data-dismiss="modal"><?php echo get_phrase('no');?></button>
                </div>
            </div>
        </div>
    </div>
    
     <script type="text/javascript">
	function confirm_modal_deny(delete_url)
	{
	    $('#modal-6').modal('show',{backdrop: 'static', keyboard: false}); 
		//jQuery('#modal-6').modal('show', {backdrop: 'static'});
		document.getElementById('delete_link2').setAttribute('href' , delete_url);
	}
	</script>
    
    <!-- (Normal Modal)-->
    <div class="modal fade eduModal" id="modal-6" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:100px;">
                
                <div class="modal-header">
                    <h4 class="modal-title" style="text-align:center;">Are you sure to deny the request of leave?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                
                
                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                    <a href="#" class="btn btn-danger" id="delete_link2"><?php echo get_phrase('yes');?></a>
                    <button type="button" class="btn btn-info" data-dismiss="modal"><?php echo get_phrase('no');?></button>
                </div>
            </div>
        </div>
    </div>
  
  
    <script type="text/javascript">
	function confirm_modal_approve(delete_url)
	{
	    $('#modal-7').modal('show',{backdrop: 'static', keyboard: false}); 
		//jQuery('#modal-7').modal('show', {backdrop: 'static'});
        document.getElementById('delete_link3').setAttribute('href' , delete_url);
	}
	</script>
    
    <!-- (Normal Modal)-->
    <div class="modal fade eduModal" id="modal-7" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:100px;">
                
                <div class="modal-header">
                    <h4 class="modal-title" style="text-align:center;">Are you sure to approve the request of leave?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                
                
                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                    <a href="#" class="btn btn-danger" id="delete_link3"><?php echo get_phrase('yes');?></a>
                    <button type="button" class="btn btn-info" data-dismiss="modal"><?php echo get_phrase('no');?></button>
                </div>
            </div>
        </div>
    </div>
    
   
  







