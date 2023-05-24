<?php 
$department_id='';
 $class_id='';
 $section_id='';
$urlArr=explode('/',$_SERVER['REQUEST_URI']);
 $values=explode('-',end($urlArr));
 
 if(sizeof($values)>1)
 {
	 $department_id=$values[0];
	 $class_id=$values[1];
	 $section_id=$values[2];	
 }
 ?><!----CREATION FORM STARTS---->
 
<div id="code_div" class="alert alert-danger" style="display:none;"><?php echo get_phrase('subject_code_already_exists'); ?>!!</div>
	<div class="panel panel-primary" data-collapsed="0">
		<div class="panel-heading ">
        	<div class="panel-title black2">
        		<i class="entypo-plus-circled"></i>
				<?php echo get_phrase('add_subject');?>           	
			</div>
        </div>
        <div class="panel-body">	
        	<?php echo form_open(base_url().'subject/subjects/create' , array('id'=>'add_subject_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                <div class="form-group " >
                        <label class="control-label "><?php echo get_phrase('category');?><span class="star">*</span></label>
                        <select name="subj_categ" id="subj_categ" class="form-control" data-validate="required" required data-message-required="<?php echo get_phrase('value_required');?>">
                          	<?php echo get_subj_category();?>
                        </select>
                    </div>                    
                <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('subject');?><span class="star">*</span></label>
                        <input maxlength="100" type="text" class="form-control" id="name" name="name" data-validate="required" required="" data-message-required="<?php echo get_phrase('value_required');?>"/>
                        <span style="color:red;" id="error_msg"></span>
                    </div>
                <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('code');?><span class="star">*</span></label>
                        <input type="text" class="form-control" maxlength="25" id="code" name="code" data-validate="required" required="" data-message-required="<?php echo get_phrase('value_required');?>"/>
                        <div id="err" style="color:red"></div>
                    </div>
                <div class="form-group">
                        <div class="float-right">
                            <button type="submit" id="submit" class="modal_save_btn"><?php echo get_phrase('add_subject');?></button>
                            <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                <?php echo get_phrase('cancel');?>
                            </button>
                        </div>
				   </div>
            </form>              
        </div>                
	</div>

<script type="text/javascript">
    $(document).ready(function()
    {
        $("#code").change(function(){
            //alert('return');

            var code=$(this).val();
            if(code!="")
            {
                $.ajax({
                    type: 'POST',
                    data: {code:code.trim()},
                    url: "<?php echo base_url();?>subject/check_code",
                    dataType: "html",
                    success: function(response)
                    {
                        var obj = jQuery.parseJSON(response);
                        $("#icon").remove();
                        if(obj.exists==1)
                        {
                            $("#code_div").show();
                            $('#code_div').delay(2000).fadeOut('slow');
                            $("#code").focus();
                            return false;
                        }
                        else
                        {
                            $("#code_div").hide();
                            //$('#submit-btn').attr('disabled',false);
                        }


                    }
                });
            }

        });
        
        $("#name").keypress(function (e) {
        var key = e.keyCode || e.which;       
        $("#error_msg").html("");
        //Regular Expression
        var reg_exp = /^[A-Za-z0-9 ]+$/;
        //Validate Text Field value against the Regex.
        var is_valid = reg_exp.test(String.fromCharCode(key));
        if (!is_valid) {
          $("#error_msg").html("No special characters Please!");
        }
        return is_valid;
      });


    });
</script>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>