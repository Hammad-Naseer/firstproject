<?php

$s_c_f_id = $this->uri->segment(4);
$s_c_d_id= $this->uri->segment(6);
echo $form_status= $this->uri->segment(7);


$title = "Add Fee";
if (($param3 == 1) && ($s_c_d_id > 0))
{
	$title = "Edit Fee";
}
elseif (($param3 == 2) && ($s_c_d_id > 0))
{
	$title = "Edit Discount";
}
elseif ($param3 == 2 )
{
	$title = "Add Discount";



}
if($s_c_d_id=="")
{



}
else
{
	
$qur="select * from ".get_school_db().".student_chalan_detail where s_c_d_id=$s_c_d_id";
	$query_edit=$this->db->query($qur)->result_array();
	//echo "<pre>";
	//print_r($query_edit);


    $discount_id = $query_edit[0]['type_id'];

	
}
$fee_type_id1 = array();
$fee_qur="select type_id , amount from ".get_school_db().".student_chalan_detail where s_c_f_id=$s_c_f_id";
$query_fee_edit=$this->db->query($fee_qur)->result_array();

foreach($query_fee_edit as $value_fee)
{
    $fee_type_id1[] = $value_fee['type_id'];
}


$school_id=$_SESSION['school_id'];
	//print_r($query_fee_edit);
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase($title);?>
            	</div>
            </div>
			<div class="panel-body">
				<?php if(count($query_fee_edit)>0){ ?>
                <?php echo form_open(base_url().'fee_types/fee_types_c/add_edit/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data','id'=>'add_discount_form_id'));?>
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('title');
                           echo $_test_s_c_f_id;
						?></label>
                        
						<div class="col-sm-8">
						<?php /*<select id="select_ac" class="form-control" data-validate="required" data-message-required="Value Required">*/?>
                            <select id="select_ac" class="form-control" data-validate="required" data-message-required="Value">
                        <?php
                         //echo fee_type_option_list($query_edit[0]['fee_type_id'],$param3 , $s_c_f_id);
                        echo add_discount_chalan_form($s_c_f_id , $s_c_d_id);
                         ?>
						</select>
                        <span for="school_name" id="title_fee" style="display:none; color:#F00;"><?php echo get_phrase('value_required'); ?></span>
							
						</div>
	    				</div>

				<input id="title" type="hidden" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $query_edit[0]['fee_type_title']; ?>" readonly>

				<input type="hidden" id="s_c_f_id" name="s_c_f_id" value="<?php echo $param2;   ?>">
				<input type="hidden" id="type" name="type" value="<?php echo $param3;   ?>">
				<input type="hidden" id="s_c_d_id" name="s_c_d_id" value="<?php echo $s_c_d_id;   ?>">









               <?php if($param3 == 2)
                {?>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('percentage');
                            ?></label>
                        <div class="col-sm-8">
                            <?php /*<input id="amount" type="text" class="form-control" name="amount" value="<?php echo $query_edit[0]['amount']; ?>" >*/ ?>

                            <select id="amount" name="amount" class="form-control" required>

                              <?php  echo get_percentage_range($query_edit[0]['amount']); ?>

                            </select>

                            <span for="percent_amount_school_name" id="percent_amount_validation" class="validate-has-error" style="display:none;"><?php echo get_phrase('value_required'); ?></span>
                        </div>
                    </div>

                <?php } ?>
                <?php  if($param3 == 2) {
                    $discount = 0;
                   if(!empty($query_edit[0]['fee_type_id']))
                   {
                       $discount = $query_edit[0]['fee_type_id'];
                   }

                    $amount_str = "SELECT DISTINCT s.amount as am , s.s_c_d_id as related_id FROM ".get_school_db().".discount_list as d
                                    INNER JOIN ".get_school_db().".fee_types as f on f.fee_type_id = d.fee_type_id
                                    INNER JOIN ".get_school_db().".student_chalan_detail as s on s.type_id = f.fee_type_id
                                    where d.discount_id = $discount and (s.s_c_f_id =".$s_c_f_id." and s.type=1)";

                    $amount_query=$this->db->query($amount_str)->row();
                  //  echo "<pre>";
                  //  print_r($amount_str);
                   // echo "<br>";
                    $amount_current = ($query_edit[0]['amount']*$amount_query->am)/100;

                    ?>
                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('amount');?></label>
                    <div class="col-sm-8">
                     <input id="actual_amount" type="text" class="form-control" name="actual_amount" value="<?php echo round($amount_current); ?>" required>
                        <span for="school_name" id="amount_validation1" class="validate-has-error" style="display:none;"><?php echo get_phrase('value_required'); ?></span>
                        <span id="show1"></span>
                    </div>
                </div>
               <?php  }?>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"></label>
                        
						<div class="col-sm-8">
            <div id="save_data" class="btn btn-default"><?php echo get_phrase('save'); ?></div>		</div>
					</div>
				<?php echo form_close();?>
                <?php }
                else
                {
                    ?>
               <p style="text-align: center;"><strong>
                    <?php echo get_phrase('no_fee_found_yet!'); ?></strong>
                </p>

                <?php }?>
            </div>
        </div>
            </div>
</div>

<script>
	
	
	$('#save_data').click(function() {


        var title = $("#title").val();

        var title_split = title.split('-');
        title = title_split[0];

        var fee_type_id = $("#select_ac").val();

        var s_c_f_id = $("#s_c_f_id").val();
        var type = $("#type").val();
        var amount = $("#amount").val();
        var s_c_d_id = $("#s_c_d_id").val();
        if (title == "") {
            //alert("title is required");
            $("#title_fee").show();
        }
        if (amount == "") {
            $("#amount_validation").show();
        }

         if ($("#add_discount_form_id").valid())
        {
        $.ajax({
            type: 'POST',
            data: {
                fee_type_id: fee_type_id,
                title: title,
                s_c_f_id: s_c_f_id,
                type: type,
                amount: amount,
                s_c_d_id: s_c_d_id
            },
            url: "<?php echo base_url();?>class_chalan_form/add_discount_chalan_form",
            dataType: "html",
            success: function (response) {
                //    alert('success');

                //	get_student_chalan();
                location.reload();

                $(function () {

                    $('#modal_ajax').modal('toggle');
                });

            }
        });
    }
	
		
		
		
	});
	
	$('#select_ac').change(function()
    {


        $('#title').val($("#select_ac option:selected").text());
        calculate();


	});

    $('#amount').on('input', function() {


        calculate();
    });

    function calculate()
    {

        var actual_amount_str = $('#select_ac').find(":selected").text();
        var actual_amount = actual_amount_str.split('-');
        var amount =  $.trim(actual_amount[1] , " ");
      //  alert(amount);
        var pPos =  amount; //850; //actual_amount; //parseInt($('#pointspossible').val());
        var pEarned = parseInt($('#amount').val());
        var perc="";
        if(isNaN(pPos) || isNaN(pEarned)){
            perc=" ";
        }else{
           // perc = ((pEarned/pPos) * 100).toFixed(3);
            perc = ((pEarned*pPos) / 100).toFixed(0);
        }
         //  alert(perc);
        $('#actual_amount').val(perc);
    }



</script>


