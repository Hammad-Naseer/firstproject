<?php
$CI=& get_instance();
//$CI->load->database();

$s_c_f_id = $this->uri->segment(4);
$s_c_d_id= $this->uri->segment(6);
$fee_id = 0;


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
    

    $fee_id = $query_edit[0]['type_id'];

	
}

	
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
				
                <?php echo form_open(base_url().'fee_types/fee_types_c/add_edit/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data','id'=>'add_fee_form'));?>

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('title');
                           echo $_test_s_c_f_id;
						?></label>
                        
						<div class="col-sm-8">
						<?php /*<select id="select_ac" class="form-control" data-validate="required" data-message-required="Value Required">*/?>
                            <select id="select_ac" class="form-control" data-validate="required" data-message-required="Value Required">
                        <?php
                         //echo fee_type_option_list($query_edit[0]['fee_type_id'],$param3 , $s_c_f_id);
                        echo add_fee_chalan_form($s_c_f_id);
                         ?>
						</select>
                        <span for="school_name" id="title_fee" style="display:none; color:#F00;"><?php echo get_phrase('value_required'); ?></span>
							
						</div>
	    				</div>

				<input id="title" type="hidden" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $query_edit[0]['fee_type_title']; ?>" readonly>

				<input type="hidden" id="s_c_f_id" name="s_c_f_id" value="<?php echo $param2;   ?>">
				<input type="hidden" id="type" name="type" value="<?php echo $param3;   ?>">
				<input type="hidden" id="s_c_d_id" name="s_c_d_id" value="<?php echo $s_c_d_id;   ?>">








                <?php if($param3 == 1) { ?>
					<div class="form-group">

                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('amount'); ?></label>
                      <div class="col-sm-8">
							<input id="amount" type="text" class="form-control" name="amount" value="<?php echo $query_edit[0]['amount']; ?>" required >
                            <span for="school_name" id="amount_validation" class="validate-has-error" style="display:none;"><?php echo get_phrase('value_required'); ?></span>
						</div> 
					</div>

                <?php }
                else if($param3 == 2)
                {?>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('percentage');
                            ?></label>
                        <div class="col-sm-8">
                            <?php /*<input id="amount" type="text" class="form-control" name="amount" value="<?php echo $query_edit[0]['amount']; ?>" >*/ ?>

                            <select id="amount" name="amount" class="form-control">

                              <?php  echo get_percentage_range($query_edit[0]['amount']); ?>

                            </select>

                            <span for="percent_amount_school_name" id="percent_amount_validation" class="validate-has-error" style="display:none;"><?php echo get_phrase('value_required'); ?></span>
                        </div>
                    </div>

                <?php } ?>


					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"></label>
                        
						<div class="col-sm-8">
<div id="save_data" class="btn btn-default"><?php echo get_phrase('save'); ?></div>		</div>
					</div>
				<?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<script>
	
	
	$('#save_data').click(function(){



	var title=$("#title").val();

	var title_split =  title.split('-');
        title = title_split[0];

	var fee_type_id=$("#select_ac").val();

	var s_c_f_id=$("#s_c_f_id").val();
	var type=$("#type").val();
	var amount=$("#amount").val();
	var s_c_d_id=$("#s_c_d_id").val();
	if(title == "") {
	//alert("title is required");
	$("#title_fee").show();
	}
	if(amount == "") {
	$("#amount_validation").show();
	}

        if ($("#add_fee_form").valid())
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
                url: "<?php echo base_url();?>class_chalan_form/save_fee_chalan_form",
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


