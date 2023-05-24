<style>
    .panel-body {
        padding: 25px;
    }
</style>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_exam_weightage');?>
            	</div>
            </div>
			<div class="panel-body">    	          
               	    <div class="box-content">
                	    <?php echo form_open(base_url().'adm_assessment/exam_weightage/create' , array('id'=>'exam_weightage_add','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                           <div class="form-group">
                              <label for="yearly_term_id">Select Yearly Term</label>
                              <span style="color:red;float: right;" id="yearly_term_id_span"></span>
                              <select  class="form-control" name="yearly_term_id" id = "yearly_term_id" >
                                 <?php echo yearly_terms_option_list($_SESSION['academic_year_id'] , $yearly_term_id); ?>
                              </select>
                            </div>
                           <div class="form-group">
                                <label>Select Section</label>
                                <span style="float:right; color:red;" id="section_id_span"></span>
                                <select id="section_id" class="dcs_list_add form-control" name="section_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                    <?php 
                                        echo section_selector();
                                    ?>
                                </select>
                            </div>
                            <!--<div class="form-group">-->
                            <!--    <label>Select Subject</label>-->
                            <!--    <span style="float:right; color:red;" id="subject_id_span"></span>-->
                            <!--    <select name="subject_id" id="subject_id" class="form-control">-->
                            <!--    </select>-->
                            <!--</div>-->
                            <div class="subjects_table_view"></div>
                            <!--<div class="form-group">-->
                            <!--    <label><?php echo get_phrase('exam_weightage_percentage');?><span class="red"> * </span></label>-->
                            <!--    <div>-->
                            <!--        <input type="number" class="form-control" name="exam_weightage_per" maxlength="2"  required/>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="form-group">-->
                            <!--    <label><?php echo get_phrase('assessments_weightage_percentage');?><span class="red"> * </span></label>-->
                            <!--    <div>-->
                            <!--        <input type="number" class="form-control" name="assess_weightage_per" maxlength="2"  required/>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="form-group">
                                <div class="float-right">
                					<button type="submit" class="modal_save_btn">
                						<?php echo get_phrase('add_weightage');?>
                					</button>
                					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                						<?php echo get_phrase('cancel');?>
                					</button>
                				</div>
							</div>
                    </form>                
                </div>      </div>      </div>      </div>      </div>
  <script src="<?php echo base_url(); ?>assets/js/common.js"></script>
  
  <script>
        
        $("#section_id").change(function() {
            var section_id = $(this).val();

            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');

            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>adm_assessment/get_section_all_subject",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    $(".subjects_table_view").html(response);
                }
            });
        });
        var num;
        if(num == '1' || num == '2' || num == '3' || num == '4' || num == '5' || num == '6' || num == '7' || num == '8' || num == '9' || num == '10' || num == '11' || num == '12' || num == '13' || num == '14' || num == '15'){
            num = num;
        }else{
            num = 0;
        }
        
        
</script>