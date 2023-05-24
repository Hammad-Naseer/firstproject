<style>
	.err_div{
	    position: absolute;
        color: red;
        text-align: center;
	}
</style>
<?php
  if($this->session->flashdata('club_updated')){
     echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('location'); ?>
        </h3>
    </div>
</div>
<form action="" method="post" id="locationForm">
    <div class="row filterContainer">
        <div class="col-lg-4 col-md-4 col-sm-4" data-step="2" data-position='right' data-intro="if you filter location 1st Step select country">
        	<div class="form-group">
        	        <label for="loc_country"><b>Select Country</b></label>
        	        <select name="loc_country" id="loc_country" class="form-control">
        				<?php echo country_option_list($loc_country);?>
        			</select>
        			<div class="err_div"></div>
        	</div>	
        </div>	
        <div class="col-lg-4 col-md-4 col-sm-4" data-step="3" data-position='right' data-intro="2nd Step select state">
            <div class="form-group">
                <label for="loc_province"><b>Select Province</b></label>
                <select id="loc_province" class="form-control" name="loc_province" >
    				<option value="">
                        <?php echo get_phrase('select_province'); ?>
                          <?php echo get_phrase('state'); ?>
    				</option>
    			</select>
            </div>    
		 </div>
        <div class="col-lg-4 col-md-4 col-sm-4" data-step="4" data-position='left' data-intro="3rd Step select city">
             <div class="form-group">
                <label for="loc_city"><b>Select City</b></label>  
                <select id="loc_city" class="form-control" name="loc_city">
    				<option value="">
    					<?php echo get_phrase('select_city'); ?>
    				</option>
    			</select>
             </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4" data-step="5" data-position='bottom' data-intro="submit button get result">
        	<input type="hidden" name="apply_filter" value="1">		
        	<div class="btn btn-primary" id="btn_submit" data-step="5" data-position='bottom' data-intro="submit button get result">
				<?php echo get_phrase('filter'); ?> 
			</div>
			<?php if ($apply_filter == 1) { ?> 
                <a href="<?php echo base_url();?>location/location_list"  class="modal_cancel_btn" id="btn_remove" style="padding:5px 5px !important; ">			
    			       <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?>
    			</a>
            <?php } ?>	
        </div>
    </div>	        
</form>
<div class="col-lg-12 col-md-12">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export">
	<thead>
		<tr>
			<th style="width:34px !important;"><?php echo get_phrase('s_no');?>		
		</th>
		<th>
			<?php echo get_phrase('location_detail');?>
		</th>
		<th style="width:94px;">
			<?php echo get_phrase('options');?>	
		</th>
		</tr>
	</thead>
	<tbody>
		
	</tbody>
</table>
</div>

<script type="text/javascript">

$(window).on("load" , function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
    
    function fetch_data()
    {
        $.ajax({
            url:"<?php echo base_url(); ?>location/apis",
            method:"POST",
            data:{data_action:'fetch_all'},
            success:function(data)
            {
                $('tbody').html(data);
            }
        });
    }
    
    // Admission Inquiry Submit
    $("#locationForm").on('submit',function(e){
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('data_action', 'filter');
        $.ajax({
            url: '<?= base_url() ?>location/apis',
            method: 'POST',
            data:formData,  
            contentType: false,  
            cache: false,  
            processData:false,
            success:function(data)
            {
                $('tbody').html(data);
            }
            
        });
    });

    fetch_data();
    
	var loc_country  = '<?php echo $loc_country ?>'; 
	var loc_province = '<?php echo $loc_province ?>';
	var loc_city     = '<?php echo $loc_city ?>';
	if(parseInt(loc_country) > 0){
	    $.ajax({
			url:"<?php echo base_url(); ?>location/get_province_list",
			type:'post',
			data:{id:loc_country},
			dataType:'html',
			success:function(res)
			{
				$('#loc_province').html(res);
				if(parseInt(loc_province) > 0){
				    $('#loc_province').val(loc_province);
				}
			}
		});
	}
	if(parseInt(loc_province) > 0){   
	    $.ajax({
			url:"<?php echo base_url(); ?>location/get_city_list",
			type:'post',
			data:{id:loc_province},
			dataType:'html',
			success:function(res)
			{
				$('#loc_city').html(res);
				if(parseInt(loc_city) > 0){
				    $('#loc_city').val(loc_city);
				}
			}
		});
	}
		
	$('#loc_country').on('change',function()
	{
		$(".err_div").html("");
		var loc_id = $('#loc_country').val();
		$('#loc_country').after('<div id="message" class="loader_small"></div>');
		
		if(loc_id==""){
			$('#message').remove();
			$('#loc_province').html('<option value=""><?php echo get_phrase('select_province'); ?> / <?php echo get_phrase('select_state'); ?></option>');
		}else{
			$.ajax({
			
			url:"<?php echo base_url(); ?>location/get_province_list",
			type:'post',
			data:{id:loc_id},
			dataType:'html',
			success:function(res)
			{
				$('#message').remove();
				$('#loc_province').html(res);
			}
		});
		}
		$('#loc_city').html('<option value=""><?php echo get_phrase('select_city'); ?></option>');
	});
	
	$('#loc_province').change(function()
	{
		var prov_id = $('#loc_province').val();
		$('#loc_province').after('<div id="message" class="loader_small"></div>');
		
		if(prov_id==""){
			$('#message').remove();
			$('#loc_city').html('<option value=""><?php echo get_phrase('selecct_city'); ?></option>');
			
	    }else{
		    $.ajax({
			    url:"<?php echo base_url(); ?>location/get_city_list",
			    type:'post',
	    		data:{id:prov_id},
	    		dataType:'html',
	    		success:function(res)
		    	{
			    	$('#message').remove();
			    	$('#loc_city').html(res);
			    }
    		});	
		}
	});
	
	$(document).on('click',"#btn_submit",function()
	{
		$(".err_div").html("");
		var loc_country = $('#loc_country').val();
		var loc_province= $('#loc_province').val();
		var loc_city = $('#loc_city').val();
		if (loc_country== ""){
			$(".err_div").html("Value required");
		}
		else if (loc_country!= "" || loc_province!= "" || loc_city!= "")
		{
			$(".err_div").html("");
			$('#btn_remove').show();
			$('#locationForm').submit();
		}
	});
});



<?php if(right_granted('locations_manage')){ ?>
    var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_location/")';
    var datatable_btn     = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Add new location here' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_location');?></a>";    
<?php } ?>

</script>

<!--Datatables Add Button Script-->
