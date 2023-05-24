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
				<?php echo get_phrase('chalan_form_discount'); ?>
        	</div>
        </div>
	    <div class="img-thumbnail" style="width: 100%;padding: 10px;margin-bottom: 10px;">
	        <p><?php echo get_phrase('form_title'); ?>	</p>
    	    <p>
            	<?php if($edit_data[0]['status']==1)
            	{
            		$status="Active";
            	}
            	else
            	{ $status= 'Inactive';  }
            	echo $title=$edit_data[0]['title']; ?>
            	<span class="fontsizesmall" style="font-size:13px;">  (<?php  echo   $status ;?>)</span>
    	    </p>
    	    <p><?php echo get_phrase('form_type'); ?>	</p>
        	<p>
            	<?php echo $type=display_class_chalan_type($edit_data[0]['type']); ?>
        	</p>
    	    <p><?php echo get_phrase('class'); ?> / <?php echo get_phrase('section'); ?></p>
        	<div>
        	<?php
                $section_hierarchy = section_hierarchy($param2); ?>	
        		<ul class="breadcrumb">
        			<li><?php echo $section_hierarchy['d'];?></li>
        			<li><?php echo $section_hierarchy['c'];?></li>
        			<li><?php echo $section_hierarchy['s'];?></li>
        		</ul>
        	</div>
	    </div>
	
	<div>
    	<div class="panel-title black2" >
			<?php echo get_phrase('select_fee_type'); ?>
    	</div>
    </div>
	<?php echo form_open(base_url().'class_chalan_form/class_chalan_discount_save/' , array('class' => 'form-horizontal form-groups-bordered validate','id'=>'sub_formmmmm', 'enctype' => 'multipart/form-data'));?>            
    <input name="class_id" type="hidden" value="<?php echo $param2;  ?>"/>
    <input name="c_c_f_id" type="hidden" value="<?php echo $param3;  ?>"/>
	   <div class="row">
	       <div class="col-lg-6 col-sm-6">
	           <label>Percentage</label>
	           <input type="radio" name="check_discount_type" id="check_discount_type" value="1" checked="">
	       </div>
	       <div class="col-lg-6 col-sm-6">
	           <label>Value</label>
	           <input type="radio" name="check_discount_type" id="check_discount_type" value="2">
	       </div>
	   </div>
	
	<table class="table table-responsive  table-striped" width="100%">
		<tr>
			<th><?php echo get_phrase('titled'); ?></th>
            <th><?php echo get_phrase('percent_values'); ?></th>
            <th><?php echo get_phrase('amount'); ?> </th>
		</tr>
	<?php


   // $rec_str = "SELECT ccff.fee_type_id as fee_id ,
   //                    ccff.c_c_f_id, 
   //                    ccff.value as fee_value, 
   //                    ft.title as fee_title,
   //                    dl.title as discount_title,
   //                   dl.discount_id as discount_id FROM ".get_school_db().".class_chalan_form as ccf
   //              INNER JOIN ".get_school_db().".class_chalan_fee as ccff
   //              on ccf.	c_c_f_id = ccff.c_c_f_id
   //              INNER JOIN ".get_school_db().".fee_types as ft
   //              on ft.fee_type_id = ccff.fee_type_id
   //              INNER JOIN ".get_school_db().".discount_list as dl
   //              on dl.fee_type_id = ft.fee_type_id
   //              WHERE dl.school_id = $school_id
   //                  and
   //                  ccf.c_c_f_id = $param3
   //                  order by ft.title";

	$rec_str = "SELECT ccff.fee_type_id as fee_id , ccff.c_c_f_id, ccff.value as fee_value, ft.title as fee_title, dl.title as discount_title, dl.discount_id as discount_id FROM ".get_school_db().".class_chalan_form as ccf 
	INNER JOIN ".get_school_db().".class_chalan_fee as ccff on ccf.c_c_f_id = ccff.c_c_f_id 
INNER JOIN ".get_school_db().".fee_types as ft on ft.fee_type_id = ccff.fee_type_id 
INNER JOIN ".get_school_db().".discount_list as dl on dl.fee_type_id = ft.fee_type_id 
INNER join ".get_school_db().".school_discount_list sdl on sdl.discount_id = dl.discount_id
WHERE sdl.school_id = ".$school_id." and ccf.c_c_f_id = $param3 and dl.status = 1 order by ft.title";
$rec = $this->db->query($rec_str)->result_array();
    // echo "<pre>";
    // print_r($rec);
    //echo $this->db->last_query();


$i=0;
foreach($rec as $rows){
$i++;
    /*c_c_f_id
    discount_id*/

	?>
    <tr>
		<td>
           <?php /* <input type="hidden" name="fee_type_id[]" value="<?php //echo $rows['fee_type_id']; ?>" <?php //echo $checked; ?> >*/ ?>
            <?php echo $rows['discount_title']." (".$rows['fee_title']." )" ?> - <span id="fee_value<?=$i?>"><?= $rows['fee_value']; ?></span>
		</td>
        <td>
            <?php /*  <input type="number" min="0" name="c_c_f_id[]" value="<?php echo $rows['discount_id'];  ?>"  style="width:60px;"  >*/ ?>
            <?php
            $amount = 0;
            $discount_amount_str = "select * from ".get_school_db().".class_chalan_discount where c_c_f_id=$param3 and discount_id =  $rows[discount_id] and school_id=$school_id";
            $class_chalan_discount=$this->db->query($discount_amount_str)->row();
           // print_r($class_chalan_discount);
           if(count($class_chalan_discount)>0)
           {
            $amount = $class_chalan_discount->value;
           }
         //  echo $amount;
            ?>

            <input type="hidden" name="c_c_f_id" id="c_c_f_id" value="<?php echo $rows['c_c_f_id']; ?>">
            <select id="amount_<?php echo $i; ?>" name="disocunt_amount[<?php echo $rows['discount_id'];  ?>]" class="form-control eneble_percent" onchange="percent_to_value(this)">
                <?php  echo get_percentage_range($amount) ?>
            </select>
            <select class="form-control value_dropdown" name="disocunt_amount[<?php echo $rows['discount_id'];  ?>]" id="amount1_<?php echo $i; ?>"></select>
        </td>
		<td>
            <input type="text" min="0" name="actual_amount" id="actual_amount_<?php echo $i; ?>" value="<?php //echo $value;  ?>"  class="eneble_value" style="width:60px;" onkeyup="value_to_percent(this)">
		    <?php /*<input type="number" min="0" name="fee_value[<?php //echo $rows['fee_type_id']; ?>]" value="<?php //echo $value;  ?>"  style="width:60px;"  >*/ ?>
		</td>
	</tr>
<script>

    calculate_<?php echo $i; ?>();
    $("#amount_<?php echo $i; ?>").change(function(){
       // $(this).css("background-color", "#D6D6FF");
        calculate_<?php echo $i; ?>();
    });
    
    function calculate_<?php echo $i; ?>()
    {
      var fee_amount = <?php echo $rows['fee_value']; ?>;
      var discount_amount = $("#amount_<?php echo $i; ?> option:selected").val();
      if(isNaN(fee_amount) || isNaN(discount_amount))
      {
        perc="";
      }
      else
      {
        perc = ((discount_amount*fee_amount) / 100).toFixed(0);
      }
      $('#actual_amount_<?php echo $i; ?>').val(perc);
    }
    </script>
<?php
$i++;
}
?>


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
$("#btn_sss").click(function(e)
{
	e.preventDefault();
	$.ajax({	
        url: '<?php echo base_url();?>class_chalan_form/class_chalan_discount_save/',
        type: "POST",
        data:$('#sub_formmmmm').serialize(),
        mimeType:"multipart/form-data",
        success: function(data)
        {
            $( ".close" ).trigger( "click" );
            get_ajax_call();
        },
        error: function()
        {}           
    });
});

$(".value_dropdown").hide();

$(document).ready(function(){
    percent_value_blur('1');
});


function percent_value_blur(a)
{
    if(a == "1")
    {
        $(".eneble_value").attr("disabled","disabled");
        $(".eneble_percent").removeAttr("disabled");
        $(".eneble_percent").show();
        $(".value_dropdown").hide();
    }else if(a == "2"){
        // $(".eneble_percent").attr("disabled","disabled");
        $(".eneble_percent").hide();
        $(".value_dropdown").show();
        $(".eneble_value").removeAttr("disabled");
    }
}

$("[name='check_discount_type']").on('click',function(){
    var a = $(this).val();
    percent_value_blur(a);
});

function value_to_percent(select)
{
    // debugger;
    var id_split = select.id;
    var get_id = id_split.split('_');
    var id = get_id[2];
    var value = select.value;
    var amount = $("#fee_value"+id).text();
    var percent = (value*100) / amount;
    var per_get = percent.toFixed(2);
    // alert(per_get);
    $("#amount1_"+id).html("<option value='"+ per_get +"'>" + per_get + "</option>");
}


</script>