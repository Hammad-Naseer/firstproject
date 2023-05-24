<style>
    .panel-body {
        padding: 25px;
    }
</style>
<?php 
        $edit_data		=	$this->db->get_where(get_school_db().'.exam_weightage' , array(
					'weightage_id' => $param2,
					'school_id' =>$_SESSION['school_id']
					) )->result_array();
foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_weightage');?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url().'adm_assessment/exam_weightage/do_update/'.$row['weightage_id'] , array('id'=>'weightage_edit','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                    <div class="form-group">
                              <label for="yearly_term_id">Select Yearly Term</label>
                              <span style="color:red;float: right;" id="yearly_term_id_span"></span>
                              <select  class="form-control" name="yearly_term_id" id = "yearly_term_id" >
                                 <?php echo yearly_terms_option_list($_SESSION['academic_year_id'] , $row['yearly_term_id']); ?>
                              </select>
                            </div>
                    <div class="form-group">
                                <label>Select Section</label>
                                <span style="float:right; color:red;" id="section_id_span"></span>
                                <select id="section_id" class="dcs_list_add form-control" name="section_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                    <?php 
                                        $section = $row['section_id'];
                                        echo section_selector($section);
                                    ?>
                                </select>
                            </div>
                    <div class="form-group">
                                <label>Select Subject</label>
                                <span style="float:right; color:red;" id="subject_id_span"></span>
                                <!--<select name="subject_id" id="subject_id" class="dcs_list_add form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">-->
                                <!--    <option value=""><?php echo get_phrase('select_a_subject');?></option>-->
                                <!--</select>-->
                                
                                <select name="subject_id" id="subject_id" class="form-control">
                                </select>
                            </div>
                    <div class="form-group">
                        <label><?php echo get_phrase('exam_weightage_percentage');?><span class="red"> * </span></label>
                        <div>
                            <input type="number" class="form-control edit_check_one" name="exam_weightage_per" value="<?=$row['exam_percentage']?>" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo get_phrase('assessments_weightage_percentage');?><span class="red"> * </span></label>
                        <div>
                            <input type="number" class="form-control edit_check_two" name="assess_weightage_per" value="<?=$row['assessment_percentage']?>" readonly="" required/>
                        </div>
                    </div>    
                    <div class="form-group">
						<div class="float-right">
        					<button type="submit" class="modal_save_btn">
        						<?php echo get_phrase('edit_weightage');?>
        					</button>
        					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
        						<?php echo get_phrase('cancel');?>
        					</button>
        				</div>
					</div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
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
                url: "<?php echo base_url();?>academic_planner/get_section_subject",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    $("#subject_id").html(response);
                }
            });
        });
        
        $(".edit_check_one").keyup(function() {
            var percent = $(this).val();
            if(percent < 0){
                alert("Value Not Accepted Less Than Zero");
                $(this).val(0);
                $(".edit_check_two").val(0);
            }else if(percent > 100){
                alert("Value Not Accepted Greater Than 100");
                $(this).val(0);
                $(".edit_check_two").val(0);
            }else{
                var second = 100-percent;
                $(".edit_check_two").val(second);
            }
        });
        
</script>