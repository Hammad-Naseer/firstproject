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
                <?php echo form_open_multipart(array('class' => 'form-horizontal validate','target'=>'_top','id'=>'diary_add_form' ));?>
                    <div class="form-group" id="class">
                        <label class="control-label">
                            <?php echo get_phrase('select_section');?><span class="required_sterik"> *</span>
                        </label>
                        <select id="section_id" class="dcs_list_add form-control" name="section_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <?php echo get_teacher_dep_class_section_list($section_arr); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            <?php echo get_phrase('select_subject');?><span class="required_sterik"> *</span>
                        </label>
                        <select name="subject_id" id="subject_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select_a_subject');?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            <?php echo get_phrase('assign_date');?><span class="required_sterik"> *</span>
                        </label>
                        <input type="date" class="form-control" name="assign_date" id="assign_date"  data-format="dd/mm/yyyy" value="<?php echo $date=Date('d/m/Y');?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                        <div id="error_start" class="col-sm-8 col-sm-offset-4"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            <?php echo get_phrase('academic_planner_task');?>
                        </label>
                        <div id="item_list"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            <?php echo get_phrase('title');?><span class="required_sterik"> *</span>
                        </label>
                        <input type="text" maxlength="50" id="title" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                        <div id="error_start" class="col-sm-8 col-sm-offset-4"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label">
                            <?php echo get_phrase('detail');?>
                        </label>
                        <textarea name="task" id="basic-example"></textarea>
                        <div id="area_count1" class="col-sm-12 "></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            <?php echo get_phrase('due_date');?><span class="required_sterik">*</span>
                        </label>
                        <input type="date" class="form-control" name="due_date" id="due_date" data-format="dd/mm/yyyy" value="" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                        <div id="error_end" class="col-sm-8 col-sm-offset-4"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            <?php echo get_phrase('attachment');?>
                        </label>
                        <input type="file" id="attachment" class="form-control" name="image1" style="height: 40px;">
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="audio_data">
                        <div id="controls">
                            <button type="button" id="recordButton">Record</button>
                            <button type="button" id="pauseButton" disabled>Pause</button>
                            <button type="button" id="stopButton" disabled>Stop</button>
                        </div>
                        <h3>Recordings</h3>
                        <ol id="recordingsList"></ol>
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
        CKEDITOR.replace('task');
    </script>
    
    <script src="https://cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script>
    <script>
        //webkitURL is deprecated but nevertheless 
        URL = window.URL || window.webkitURL;
        var gumStream;
        //stream from getUserMedia() 
        var rec;
        //Recorder.js object 
        var input;
        //MediaStreamAudioSourceNode we'll be recording 
        // shim for AudioContext when it's not avb. 
        var AudioContext = window.AudioContext || window.webkitAudioContext;
        var audioContext = new AudioContext;
        //new audio context to help us record 
        var recordButton = document.getElementById("recordButton");
        var stopButton = document.getElementById("stopButton");
        var pauseButton = document.getElementById("pauseButton");
        //add events to those 3 buttons 
        recordButton.addEventListener("click", startRecording);
        stopButton.addEventListener("click", stopRecording);
        pauseButton.addEventListener("click", pauseRecording);
        
        
        function pauseRecording() {
            console.log("pauseButton clicked rec.recording=", rec.recording);
            if (rec.recording) {
                //pause 
                rec.stop();
                pauseButton.innerHTML = "Resume";
            } else {
                //resume 
                rec.record()
                pauseButton.innerHTML = "Pause";
            }
        }
        
        function stopRecording() {
            console.log("stopButton clicked");
            //disable the stop button, enable the record too allow for new recordings 
            stopButton.disabled = true;
            recordButton.disabled = false;
            pauseButton.disabled = true;
            //reset button just in case the recording is stopped while paused 
            pauseButton.innerHTML = "Pause";
            //tell the recorder to stop the recording 
            rec.stop(); //stop microphone access 
            gumStream.getAudioTracks()[0].stop();
            //create the wav blob and pass it on to createDownloadLink 
            rec.exportWAV(createDownloadLink);
        }

        function createDownloadLink(blob) {
            var url = URL.createObjectURL(blob);
            var au = document.createElement('audio');
            var li = document.createElement('li');
            var link = document.createElement('a');
            //add controls to the <audio> element 
            au.controls = true;
            au.src = url;
            //link the a element to the blob 
            link.href = url;
            link.download = new Date().toISOString() + '.wav';
            link.innerHTML = link.download;
            //add the new audio and a elements to the li element 
            li.appendChild(au);
            li.appendChild(link);
            //add the li element to the ordered list 
            recordingsList.appendChild(li);
            
            // Upload Area
            // console.log(blob);
            // document.getElementById("audio_data").value = blob;
            var filename = new Date().toISOString();
            //filename to send to server without extension 
            //upload link
            var section_id = document.getElementById("section_id").value;
            var subject_id = document.getElementById("subject_id").value;
            var assign_date = document.getElementById("assign_date").value;
            var title = document.getElementById("title").value;
            var detail = document.getElementById("basic-example").value;
            var due_date = document.getElementById("due_date").value;
            var planner_check = document.getElementById("planner_check").value;
            
            var fileUpload = $("#attachment").get(0);  
            var files = fileUpload.files;  
            
            var upload = document.getElementById('save_btn');
            // upload.href = "#";
            // upload.innerHTML = "Upload";
            upload.addEventListener("click", function(event) {
                var xhr = new XMLHttpRequest();
                xhr.onload = function(e) {
                    if (this.readyState === 4) {
                        console.log("Server returned: ", e.target.responseText);
                    }
                };
                var fd = new FormData();
                fd.append("section_id", section_id);
                fd.append("subject_id", subject_id);
                fd.append("assign_date", assign_date);
                if(!empty(planner_check) || planner_check != '')
                {
                    fd.append("planner_check", planner_check);    
                }
                fd.append("title", title);
                fd.append("detail", detail);
                fd.append("due_date", due_date);
                fd.append("audio_data", blob, filename);
                fd.append("image1",files.name, files);
                xhr.open("POST", "<?php echo base_url()?>teacher/diary/add_diary/", true);
                xhr.send(fd);
            })
            li.appendChild(document.createTextNode(" ")) //add a space in between 
            li.appendChild(upload) //add the upload link to li

        }
        
        /* Simple constraints object, for more advanced audio features see

        https://addpipe.com/blog/audio-constraints-getusermedia/ */
    function startRecording() { 
        console.log("recordButton clicked");     
        var constraints = {
            audio: true,
            video: false
        } 
        /* Disable the record button until we get a success or fail from getUserMedia() */
        
        recordButton.disabled = true;
        stopButton.disabled = false;
        pauseButton.disabled = false
        
        /* We're using the standard promise based getUserMedia()
        
        https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia */
        
        navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
            console.log("getUserMedia() success, stream created, initializing Recorder.js ..."); 
            /* assign to gumStream for later use */
            gumStream = stream;
            /* use the stream */
            input = audioContext.createMediaStreamSource(stream);
            /* Create the Recorder object and configure to record mono sound (1 channel) Recording 2 channels will double the file size */
            rec = new Recorder(input, {
                numChannels: 1
            }) 
            //start the recording process 
            rec.record()
            console.log("Recording started");
        }).catch(function(err) {
            //enable the record button if getUserMedia() fails 
            recordButton.disabled = false;
            stopButton.disabled = true;
            pauseButton.disabled = true
        });
    }
    
    </script>