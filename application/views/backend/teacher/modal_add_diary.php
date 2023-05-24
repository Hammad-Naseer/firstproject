<style>
    .wrs_tickContainer{
        display:none;
    }
    .wrs_modal_dialogContainer{
        z-index: 999999999 !important;
        top: 30%;
        left: 35%;
    }
</style>
<?php
    $this->load->helper('teacher');
    $login_detail_id = $_SESSION['login_detail_id'];
    $yearly_term_id = $_SESSION['yearly_term_id'];
    $academic_year_id = $_SESSION['academic_year_id'];
    $section_arr = get_time_table_teacher_section($login_detail_id, $yearly_term_id);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js" integrity="sha512-nhY06wKras39lb9lRO76J4397CH1XpRSLfLJSftTeo3+q2vP7PaebILH9TqH+GRpnOhfAGjuYMVmVTOZJ+682w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <!--<b><i class="fas fa-info-circle"></i> Interactive tutorial</b>-->
            </a>
            <h3 class="system_name inline">
                   <?php echo get_phrase('add_diary'); ?>
            </h3>
        </div>
    </div>
    <div class="row">    
        <div class="panel panel-primary col-lg-12" data-collapsed="0">
            <div class="panel-heading">
                <!--<div class="panel-title black2">-->
                <!--    <i class="entypo-plus-circled"></i> <?php echo get_phrase('add_diary');?>-->
                <!--</div>-->
            </div>
            <div class="panel-body">
                <script>
                jQuery('.dcs_list_add').on('change', function() {
                    var id = this.id;
                    var selected = jQuery('#' + id + ' :selected');
                    var group = selected.parent().attr('label');
                    jQuery(this).siblings('label').text(group);
                });
            </script>
                <?php echo form_open_multipart(base_url().'teacher/diary/add_diary' , array('class' => 'form-horizontal validate','target'=>'_top','id'=>'diary_add_form' ));?>
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="">
                                <label class="control-label">
                                    <?php echo get_phrase('select_section');?><span class="required_sterik"> *</span>
                                </label>
                                <select id="section_id" class="dcs_list_add form-control" name="section_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                    <?php echo get_teacher_dep_class_section_list($section_arr); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="">
                                <label class="control-label">
                                    <?php echo get_phrase('select_subject');?><span class="required_sterik"> *</span>
                                </label>
                                <select name="subject_id" id="subject_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                    <option value=""><?php echo get_phrase('select_a_subject');?></option>
                                </select>
                            </div>
                        </div>    
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="">
                                <label class="control-label">
                                    <?php echo get_phrase('assign_date');?><span class="required_sterik"> *</span>
                                </label>
                                <input type="date" class="form-control" name="assign_date" id="assign_date"  data-format="dd/mm/yyyy" value="<?php echo $date=Date('d/m/Y');?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                                <div id="error_start" class="col-sm-8 col-sm-offset-4"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="">
                                <label class="control-label">
                                    <?php echo get_phrase('academic_planner_task');?>
                                </label>
                                <div id="item_list"></div>
                                <div id="error_start" class="col-sm-8 col-sm-offset-4"></div>
                            </div>
                        </div>   
                    </div>    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="">
                                <label class="control-label">
                                    <?php echo get_phrase('title');?><span class="required_sterik"> *</span>
                                </label>
                                <input type="text" maxlength="50" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                <div id="error_start" class="col-sm-8 col-sm-offset-4"></div>
                            </div>
                        </div>    
                    </div>    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="">
                                <label class="control-label">
                                    <?php echo get_phrase('detail');?>
                                </label>
                                <textarea name="task" id="ckeditor" class="statement mathdoxformula"></textarea>
                                <div id="area_count1" class="col-sm-12 "></div>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="">
                                <label class="control-label">
                                    <?php echo get_phrase('due_date');?><span class="required_sterik">*</span>
                                </label>
                                <input type="date" class="form-control" name="due_date" id="due_date" data-format="dd/mm/yyyy" value="" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                                <div id="error_end" class="col-sm-8 col-sm-offset-4"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="">
                                <label class="control-label">
                                    <?php echo get_phrase('attachment');?>
                                </label>
                                <input type="file" class="form-control" name="image1" style="height: 40px;">
                            </div>
                        </div>    
                    </div>    
                    <div class="form-group">
                        <div class="float-right">
                            <button type="button" class="modal_save_btn" id="save_btn" name="save">
            					<?php echo get_phrase('save');?>
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
    <style type="text/css">
    	.required_sterik{ color: red; }
    </style>
    <script src="https://cdn.ckeditor.com/4.14.1/standard-all/ckeditor.js"></script>
    <script>
        $("#section_id").change(function() 
        {
        	$('#item_list').html('');
            var section_id = $(this).val();
    
            $("#icon").remove();
            $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    
            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>teacher/get_section_student_subject",
                dataType: "html",
                success: function(response) {
                    var obj = jQuery.parseJSON(response);
                    $("#icon").remove();
                    //$("#student_box").html(obj.student);
                    $("#subject_id").html(obj.subject);
    
                }
            });
    
        });

        $("#subject_id").change(function() {
        var subject_id = $(this).val();
        var url = "<?php echo base_url(); ?>"+'teacher/get_subject_teacher';
        $("#icon").remove();
        $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

        $.ajax({
            type: 'POST',
            url: url,
            data: { subject_id: subject_id },
            dataType: "html",
            success: function(response) {

                $("#icon").remove();

                $("#teacher_id").html(response);


            }
        });

    });

        $("#assign_date").change(function() {
        var assign_date = $(this).val();
        var subject_id = $('#subject_id').val();
        if (assign_date != "" && subject_id != "") {
            $.ajax({
                type: 'POST',
                data: {
                    assign_date: assign_date,
                    subject_id: subject_id
                },
                url: "<?php echo base_url();?>diary/get_acad_checkboxes",
                dataType: "html",
                success: function(response) {
                    $('#item_list').html(response);
                }
            });
        }

    });

	    $("#subject_id").change(function() {
        var subject_id = $(this).val();
        var assign_date = $('#assign_date').val();
        if (assign_date != "" && subject_id != "") {
            $.ajax({
                type: 'POST',
                data: {
                    assign_date: assign_date,
                    subject_id: subject_id
                },
                url: "<?php echo base_url();?>diary/get_acad_checkboxes",
                dataType: "html",
                success: function(response) {
                    $('#item_list').html(response);
                }
            });
        }

    });

        $("#assign_date").change(function() {
        $('#btn_add').removeAttr('disabled', 'true');
        var startDate = document.getElementById("assign_date").value;
        var endDate = document.getElementById("due_date").value;

        if ((Date.parse(endDate) < Date.parse(startDate))) {
            $('#error_start').text("<?php echo get_phrase('assign_date_should_be_less_then_due_date');?>");
            $('#btn_add').attr('disabled', 'true');
            //document.getElementById("start_date").value = "";

        }
    });

        $("#due_date").change(function() {
        $('#btn_add').removeAttr('disabled', 'true');
        var startDate = document.getElementById("assign_date").value;
        var endDate = document.getElementById("due_date").value;

        if ((Date.parse(startDate) > Date.parse(endDate))) {
            $('#error_end').text("<?php echo get_phrase('due_date_should_be_greater_than_assign_date');?>");
            $('#btn_add').attr('disabled', 'true');
        }
    });

    </script>
    
    <script>
        // CKEDITOR.replace('task');
        CKEDITOR.plugins.addExternal('ckeditor_wiris', 'https://www.wiris.net/demo/plugins/ckeditor/', 'plugin.js');
        CKEDITOR.config.uiColor = '#427fa6';
        CKEDITOR.config.width = '100%'; 
         
        CKEDITOR.replace('ckeditor', {
             extraPlugins: 'ckeditor_wiris',
             height: 200,
             // Remove the redundant buttons from toolbar groups defined above.
             removeButtons: 'Styles,removeFormat,Strike,Anchor,SpellChecker,PasteFromWord,Image,Source,Text,Copy,Paste,Cut,plaintext,Undo,Redo,About'
        });
        
        $('#save_btn').on('click' , function(e){
	    e.preventDefault();
	    Swal.fire({
          title: 'Are you sure?',
          text: "You want to add diary ?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, add the diary!'
        }).then((result) => {
          if (result.isConfirmed) {
            $("#diary_add_form").submit();
          }
        })
	});
    </script>
    