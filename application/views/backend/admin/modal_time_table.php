<?php
$day='';
$period_no='';
$setting_id='';

$section_id='';

$sectionhierarchy='';
$urlArr=explode('/',$_SERVER['REQUEST_URI']);
$values=explode('-',end($urlArr));
$day=$values[0];
$period_no=$values[1];
$setting_id=$values[2];
$dep_id=$values[3];
$class_id=$values[4];
$sec_id=$values[5];
$period_start_time=$values[6];

if(!empty($setting_id))
{
    $settings=$this->db->query("select * from ".get_school_db().".class_routine_settings where c_rout_sett_id=$setting_id and school_id=".$_SESSION['school_id']."")->result_array();
    //echo $this->db->last_query();

    $section_id=$settings[0]['section_id']; 

    $start_date=$settings[0]['start_date']; 
    $end_date=$settings[0]['end_date']; 
    $sectionhierarchy=section_hierarchy($section_id);
}
 ?>
 <div class="row">
 <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="panel panel-primary" id="add" style="padding: 5px">
        <div class="panel-heading">
            <div class="panel-title" style="color:#818da1">
                <i class="entypo-plus-circled">
                </i>
                <?php echo get_phrase('add_time_table');?>
            </div>
        </div>
        <div class="panel-body">
            <div id="msg"></div>
            <form method="post" name="add_time_table" id="add_time_table" class="form-horizontal form-groups-bordered validate">
                <!--<div class="row">-->
                    <!--<div class="col-lg-6 col-sm-6">-->
                        <div class="form-group">
                            <label class="control-label">
                                <?php echo get_phrase('select_setting');?>
                            </label>
                                <input type="hidden" id="setting_id" value="<?php echo $sec_id; ?>">
                                <input type="text" class="form-control" disabled="" value="<?php
                                $dcs_arr=section_hierarchy($sec_id);
                                echo $dcs_arr['d'].' - '.$dcs_arr['c'].' - '.$dcs_arr['s'];?> ">
                        </div>
                        <div class="form-group">
                            <label class="control-label">
                                  <?php echo get_phrase('start_date');?>
                            </label>
                            <?php echo convert_date($start_date); ?>
                        </div>
                        <div class="form-group">
                            <label class="control-label">
                                  <?php echo get_phrase('end_date');?>
                            </label>
                            <?php echo convert_date($end_date); ?>
                        </div>
                        <div class="form-group">
                    <label class="control-label">
                          <?php echo get_phrase('day');?>
                    </label>
                    <?php echo ucfirst($day); ?>
                </div>
                        <div class="form-group">
                    <label class="control-label">
                         <?php echo get_phrase('period_no');?>.
                    </label>
                    <?php echo $period_no; ?>
                </div>
                        <div class="form-group">
                    <label class="control-label">
                        <?php echo get_phrase('period_duration');?>
                    </label>
                    <input type="text" name="period_duration" id="period_duration" class="form-control" value="<?php echo $settings[0]['period_duration'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                </div>
                        <input type="hidden" id="day" name="day" value="<?php echo strtolower($day); ?>">
                        <input type="hidden" value="<?php echo $period_no ?>" id="period_no_select" name="period_no_select">
                        <div class="form-group">
                    <label class="control-label">
                        <?php echo get_phrase('subject*');?>
                    </label>
                    <select id="subject_select_id" name="subject_id" class="form-control" style="width:100%;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <?php echo subject_option_list($section_id)?>
                    </select>
                    <div id="subject-err"></div>
                </div>    
                    <!--</div>-->
                    <!--<div class="col-lg-6 col-sm-6">-->
                        <div id="component-list">
                            <div class="form-group" style="margin-top:34px;">
                                <label class="col-sm-12 control-label" style="text-align: left">
                                    <?php echo get_phrase('components');?>
                                </label>
                                <div class="col-sm-12">
                                    <div id="component-input"></div>
                                </div>
                            </div>
                        </div>
                        <div id="teacher-list">
                            <div class="form-group" style="margin-top:34px;">
                                <label class="col-sm-12 control-label" style="text-align: left">
                                    <?php echo get_phrase('teachers');?>
                                </label>
                                <div class="col-sm-12">
                                    <div id="teachers-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="float-right">
                                <button type="submit" class="modal_save_btn" id="submit-btn">
                                    <?php echo get_phrase('add_time_table');?>
                                </button>
                                <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                    <?php echo get_phrase('cancel');?>
                                </button>
                            </div>
                        </div>
                    <!--</div>-->
                <!--</div>-->
            </form>
        </div>
    </div>
 </div>
 </div>
 <div id="msg_div"></div>
    <script>
    function mycl() {

        $("#submit-btn").removeClass("disabled");
    }

    $(document).ready(function() 
    {

        $('#teacher-list').hide();
        $('#component-list').hide();
        $('.selectpicker').on('change', function() {
            var id = $(this).attr('id');
            var selected = $('#' + id + ' :selected');
            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);
        });
        document.getElementById('add_time_table').onsubmit = function() {
            return false;
        };

        var setting = '<?php echo $setting_id?>';

        if (setting != '') {

            var section = '<?php echo $section_id?>';
            var day = '<?php echo $day?>';
            var period = '<?php echo $period_no?>';

            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>time_table/get_number_period",

                data: ({
                    section_id: section,
                    day: day,
                    period: period
                }),
                dataType: "html",
                success: function(html) {

                    $("#icon").remove();
                    if (html == 'equal') {
                        $('#period_no').text('<?php echo get_phrase('all_periods_are_assigned_on_this_day');?>');
                    } else {
                        $('#period_no').html(html);
                        $('#subject_select_id').load("<?php echo base_url(); ?>time_table/get_subject_list/section_id/" + section);
                    }
                }
            });
        }
var count=0;
        $('#subject_select_id').on('change', function() 
        {
           // alert('getcomponents');
            $("#icon").remove();
            $('#submit-btn').removeAttr('disabled', 'true');
            $('#subject-err').text('');
            $('#teacher-list').hide();
            $('#component-list').hide();
            $(this).after('<div id="icon" class="loader_small"></div>');
            var subject_id = $(this).val();
            var day = $('#day').val();
            var section_id = $('#section_select_id').val();
            var period_num = $('#period_no_select').val();
            var c_rout_setting_id='<?php echo $setting_id;?>';
            

            if (subject_id == '') {
                $('#teacher-list').hide();
                $('#component-list').hide();
            }

            if (subject_id != '' && day != '' && section_id != '') {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>time_table/get_available_teachers_checkbox",
                    data: ({
                        subject_id: subject_id,
                        day: day,
                        section_id: section_id,
                        period_num: period_num
                    }),
                    dataType: "html",
                    success: function(html) {
                        $("#icon").remove();
                        //console.log(html);
                        if (html != 0) {
                            if(count==1)
                            {
                                $('#teacher-list').hide();
                                $('#component-list').hide();
                                
                                
                            }
                            else if(count==0)
                            {
                                 $('#teachers-input').html(html).show();
                            $('#teacher-list').show();
                            $('#component-list').show();
                            }
                           
                        }

                    }

                });
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>time_table/subject_period_count",

                    data: ({
                        subject_id: subject_id,
                        section_id: section_id,
                        day: day,
                        c_rout_setting_id:c_rout_setting_id
                    }),
                    dataType: "json",
                    success: function(html) {
                        $("#icon").remove();
                       
                        if (html.msg != '') {
                            count=1;
                            $('#teacher-list').hide();
                            $('#component-list').hide();
                            $('#subject-err').text(html.msg);
                            $('#submit-btn').attr('disabled', 'true');
                        } else {
                            $('#teacher-list').show();
                            $('#component-list').show();
                            $('#submit-btn').removeAttr('disabled', 'true');
                            $('#subject-err').text('');

                        }
                    }

                });
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>time_table/get_subject_components",

                    data: ({
                        subject_id: subject_id,
                        /*term_id: '<?php //echo $yearlyterm?>'*/
                    }),
                    dataType: "html",
                    success: function(html) {
                        $("#icon").remove();
                        if (html != '') {
                            $('#component-list').show();
                            $('#component-input').html(html);
                        }


                    }

                });
            } else {
                $("#icon").remove();
            }

        });

        $('#day').on('change', function() 
        {
            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');
            var yearly_term = $('#yearly_term').val();
            var section_id = $('#section_select_id').val();
            console.log(yearly_term);
            console.log(section_id);
            var day = $(this).val();
            if (day == '') 
            {
                $('#teacher-list').hide();
                $('#period_no_select').html("<select><option><?php echo get_phrase('select_period'); ?></option></select>");
            }
            if (yearly_term != '' && section_id != '') 
            {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>time_table/get_number_period",

                    data: ({
                        yearly_term: yearly_term,
                        section_id: section_id,
                        day: day
                    }),
                    dataType: "html",
                    success: function(html) {
                        $("#icon").remove();
                        console.log(html);
                        if (html == 'equal') {
                            $('#period_no').text('<?php echo get_phrase('all_periods_are_assigned_on_this_day');?>');
                        } else {
                            $('#period_no').html(html);
                        }
                    }
                });
            } else {
                $("#icon").remove();
            }
        });


        $('#period_no_select').on('change', function() {
            var period = $(this).val();

            $('#teacher-list').hide();
            $('#subject_select_id').html("<select><option><?php echo get_phrase('select_subject'); ?></option></select>");

        });

        $('#submit-btn').click(function(e) 
        {

            $('#submit-btn').attr('disabled', 'true');
           // return false;
            var section_id='<?php echo $sec_id;?>';
            var period_start_time='<?php echo $period_start_time;?>';
            var c_rout_sett_id = <?php echo  $setting_id ?>;// $('#c_rout_sett_id').val();
            var subject_id = $('#subject_select_id').val();
            var day = $('#day').val();
            var period_no = $('#period_no_select').val();
            var period_duration=$('#period_duration').val();
            var subject_teacher_id = $('.teacher:checked').serializeArray();
            var component_id = $('.components:checked').serializeArray();
            
            //alert(subject_teacher_id);
            $('#add_time_table').validate();
            if (subject_id != '' && period_no != '' && day != '' && period_duration!='') 
            {

               $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>time_table/class_routine/create",
                    data: ({
                        c_rout_sett_id: c_rout_sett_id,
                        subject_id: subject_id,
                        day: day,
                        period_no: period_no,
                        period_duration:period_duration,
                        subject_teacher_id: subject_teacher_id,
                        component_id: component_id,
                        period_start_time:period_start_time,
                        section_id:section_id
                    }),

                    success: function(response) 
                    {
                        if ($.trim(response)) 
                        {
                           //alert(response);
                          // alert('comp');
                            $('#submit-btn').attr('disabled',true);
                           // alert('comp');
                            $('#msg_div').html(response);
                           var dept_id='<?php echo $dep_id;?>';
                           var sec_id='<?php echo $sec_id;?>';
                            $('#modal_ajax').modal('hide');
                           load_table('<?php echo $setting_id;?>', '<?php echo $section_id;?>',true);

                            /*dept_id = $('#department_id').val();
                            class_id = $('#class_select_id').val();
                            section_id = $('#section_select_id').val();
                            year_id = $('#academic_year').val();
                            term_id = $('#yearly_term').val();
                            //window.location.replace("<?php echo base_url(); ?>time_table/class_routine/");
                            $('#table').load("<?php echo base_url();?>time_table/get_class_routine", {
                                departments_id: dept_id,
                                class_id: class_id,
                                section_id: section_id,
                                academic_id: year_id,
                                term_id: term_id
                            });*/
                        }


                    }


                });
            }

        });

        $('#setting_id').change(function() {

            $('#period_no_select').html('<select><option><?php echo get_phrase('select_period'); ?></option></select>');
            $('#day').val($('#day').data('default'));
            $('#teacher-list').hide();
            var setting_id = $(this).val();

            if (setting_id != '') {

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>time_table/get_settings",

                    data: ({
                        setting_id: setting_id
                    }),
                    dataType: "json",
                    success: function(html) {
                        $("#icon").remove();
                        if (html != '') {
                            $('#academic_year').attr('value', html.academic_id);
                            $('#yearly_term').attr('value', html.yearly_terms_id);
                            $('#department_id').attr('value', html.department_id);
                            $('#class_select_id').attr('value', html.class_id);
                            $('#section_select_id').attr('value', html.section_id);
                            $('#subject_select_id').load("<?php echo base_url(); ?>time_table/get_subject_list/section_id/" + $('#section_select_id').val());

                        }

                    }
                });
            }
        });
    });
    </script>
