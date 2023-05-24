<?php 
$school_id=$_SESSION['school_id'];
$edit_data=$this->db->get_where(get_school_db().'.class_chalan_form' , array('c_c_f_id' => $param3,'school_id'=>$school_id))->result_array();

$fee_type_rec=$this->db->query("select * from ".get_school_db().".class_chalan_fee where c_c_f_id=$param3 and school_id=$school_id")->result_array();

foreach($fee_type_rec as $type_rec){
	
    $fee_type_id=$type_rec['fee_type_id'];
    $fee_type_rec_data[$fee_type_id]=$type_rec['value'];
    $order_num[$fee_type_id]=$type_rec['order_num'];
}

$class_chalan_discount=$this->db->query("select * from ".get_school_db().".class_chalan_discount where c_c_f_id=$param3 and school_id=$school_id")->result_array();
foreach($class_chalan_discount as $discount_rec){
$discount_id=$discount_rec['discount_id'];
$class_chalan[$discount_id]=$discount_rec['value'];
$order_num1[$discount_id]=$discount_rec['order_num'];
	
}

?>

<div class="row">
	<div class="col-md-12">
		<div class="panel-heading">
            	<div class="panel-title black2" style="width:100%">
					<?php echo get_phrase('chalan_form_settings'); ?>
            	</div>
            </div>
        <div class="img-thumbnail" style="width: 100%;padding: 10px;margin-bottom: 10px;">
	        <p><?php echo get_phrase('form_title'); ?>	
	            &nbsp;&nbsp;&nbsp;
            	<?php if($edit_data[0]['status']==1)
            	{
            		$status="Active";
            	}
            	else
            	{ 
            	    $status= 'Inactive'; 
            	}
            	echo $title=$edit_data[0]['title']; ?>
            	<span class="fontsizesmall" style="font-size:13px;">(<?php  echo $status ;?>)</span>
	        </p>
	        <p><?php echo get_phrase('form_type'); ?>
	            &nbsp;&nbsp;
            	<?php echo $type=display_class_chalan_type($edit_data[0]['type']); ?>
	        </p>
        	<p>
        	    <?php $section_hierarchy = section_hierarchy($param2); ?>	
        		<ul class="breadcrumb">	
        			<li><b><?php echo get_phrase('class'); ?> / <?php echo get_phrase('section'); ?></b></li>
        			<li><?php echo $section_hierarchy['d'];?></li>
        			<li><?php echo $section_hierarchy['c'];?></li>
        			<li><?php echo $section_hierarchy['s'];?></li>
        		</ul>
        	</p>
        </div>
	    <div>
            <div class="panel-title black2" >	
				<?php echo get_phrase('select_fee_type'); ?>
            </div>
        </div>
	    <?php echo form_open(base_url().'class_chalan_form/class_chalan_f/change/' , array('class' => 'form-horizontal form-groups-bordered validate','id'=>'sub_formmmmm', 'enctype' => 'multipart/form-data'));?>        
        <input name="class_id" type="hidden" value="<?php echo $param2;  ?>"/>
        <input name="c_c_f_id" type="hidden" value="<?php echo $param3;  ?>"/>
    	<table class="table table-responsive  table-striped" width="100%">
    		<tr>
    		    <th width="100%"><?php echo get_phrase('title'); ?></th><th  width="100%"><?php echo get_phrase('amount'); ?> </th>
    		</tr>
	<?php 
	    $rec_qur = "SELECT ft.* FROM ".get_school_db().".fee_types ft
        INNER JOIN ".get_school_db().".school_fee_types sft on sft.fee_type_id = ft.fee_type_id
        WHERE sft.school_id = ".$school_id." 
        ORDER BY ft.status desc, ft.title ASC";
        $rec = $this->db->query($rec_qur)->result_array();
        
        foreach($rec as $rows){
        	$checked="";
        	$value="";
        	$order="";
        	$fee__tp=$rows['fee_type_id'];
        	if(array_key_exists($fee__tp,$fee_type_rec_data))
        	{
            	$checked="checked";
            	$value=$fee_type_rec_data[$fee__tp];
            	$order=	$order_num[$fee__tp];
        	}
	?>
    <tr>
		<td  width="100%">
            <input type="hidden" name="fee_type_id[]" value="<?php echo $rows['fee_type_id']; ?>" <?php //echo $checked; ?> >
            <?php echo $rows['title']." "; ?>
		</td>
		<td  width="100%">
		    <input type="number" min="0" name="fee_value[<?php echo $rows['fee_type_id']; ?>]" value="<?php echo $value;  ?>"  style="width:60px;"  >
		</td>
	</tr>
    <?php } ?>	
</table>

<div class="form-group" style="margin:2px;">
    <div class="float-right">
		<button type="submit" id="btn_sss" class="modal_save_btn">
			<?php echo get_phrase('save');?>
		</button>
		<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
			<?php echo get_phrase('cancel');?>
		</button>
	</div>
</div>
							
                <?php echo form_close();?>
            </div>
        </div>

<script>


$("#btn_sss").click(function(e){
	
	e.preventDefault();
	

	$.ajax({	
url: '<?php echo base_url();?>class_chalan_form/class_chalan_f/change/',
            type: "POST",
            data:$('#sub_formmmmm').serialize(),
            mimeType:"multipart/form-data",
          
            success: function(data)
            {

            $( ".close" ).trigger( "click" );
            get_ajax_call();

             },
            error: function() 
          
            {
            	
            	
            	
            }  
                     
       });
	
	
	
	
	
});
	
	
	
	
</script>