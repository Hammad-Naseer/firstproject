
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
            		<?php echo get_phrase('request_a_leave'); ?>
            	</div>
            </div>
            <div class="panel-body">
            <div class="box-content">
                <?php echo form_open(base_url().'parents/manage_leaves/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                    <div class="form-group">
                        <label class="control-label">
                            <?php echo get_phrase('leave_type');?>
                        </label>
                        <select name="leave_id" class="form-control" style="width:100%;" required>
                            <option value=""><?php echo get_phrase('please_select_leave_type');?></option>
                            <?php 
                                    $this->db->where('school_id',$_SESSION['school_id']);
                                    $book = $this->db->get(get_school_db().'.leave_category')->result_array();
                                    foreach($book as $row):
                                    ?>
                            <option value="<?php echo $row['leave_category_id'];?>">
                                <?php echo $row['name'];?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('start_date');?>
                        </label>
                        <div id="start_date_message">
                            <input type="date" class="form-control start_date_val" name="start_date" id="start_date" onselect="check_leave('start_date')" data-start-view="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('end_date');?>
                        </label>
                        <div id="end_date_message">
                            <input type="date" class="form-control end_date_val" name="end_date" id="end_date" onselect="check_leave('end_date')" data-start-view="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('description');?>
                        </label>
                            <textarea class="form-control" name="reason" id="reason" rows="5" maxlength="1000" oninput="count_value('reason','area_count1','1000')" required ></textarea>
                            <div id="area_count1" class="col-sm-12" ></div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="control-label">
                            <?php echo get_phrase('file_upload');?>
                        </label>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                <div>
                                    <span class="btn btn-white btn-file">
                                        <span class="fileinput-new"><?php echo get_phrase('choose_file'); ?></span>
                                    <span class="fileinput-exists"><?php echo get_phrase('change'); ?></span>
                                    <input type="file" name="userfile" accept="image/*">
                                    </span>
                                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo get_phrase('remove'); ?></a>
                                </div>
                            </div>
                    </div>
                    <div class="form-group">
                        <div class="float-right">
                            <button type="submit" class="modal_save_btn" id="submit_leave">
        						<?php echo get_phrase('request_leave');?>
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
    </div>
</div>

<script src="https://dev.indiciedu.com.pk/assets/js/jquery-3.5.1.min.js"></script>

<script>
    $(document).ready(function(){
        $(".start_date_val").on("change",function(){

            $(".end_date_val").val($(this).val());
        });
    });
</script>