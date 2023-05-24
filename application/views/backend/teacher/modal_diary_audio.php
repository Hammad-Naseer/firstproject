<?php $diary_id = $this->uri->segment('5'); ?>
<style>
    #audio_msg span
    {
        background: #4caf50;
        display: block;
        color: white;
        padding: 12px;
        font-weight: bold;
    }
</style>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title  black2" >
					<i class="entypo-plus-circled"></i>
                    <?php echo get_phrase('add_audio');?>
				</div>
			</div>
			<div class="panel-body">
                <div id="audio_msg"></div>
                <form action="" method="post">
                    <div class="form-group">
                        <div id="controls">
                            <button type="button" class="modal_save_btn" id="recordButton">Record</button>
                            <button type="button" class="btn btn-warning" style="border:1px solid orange !important;background:#ff9800 !important;color:white;" id="pauseButton" disabled>Pause</button>
                            <button type="button" class="modal_cancel_btn" id="stopButton" disabled>Stop</button>
                        </div>
                        <h3>Recordings</h3>
                        <ol id="recordingsList" style="list-style:none;"></ol>
                    </div>
                    <div class="form-group">
                        <div class="float-right">
        					<button type="button" class="modal_save_btn" id="upload">
        						<?php echo get_phrase('upload_audio');?>
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
            document.getElementById("audio_msg").innerHTML = "<span>Recording Pause</span>";
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
        document.getElementById("audio_msg").innerHTML = "<span>Recording Stop</span>";
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
        // li.appendChild(link);
        //add the li element to the ordered list 
        recordingsList.appendChild(li);
        
        var filename = new Date().toISOString();
        //filename to send to server without extension 
        //upload link 
        var upload = document.getElementById("upload");
        // upload.href = "#";
        // upload.innerHTML = "Upload";
        upload.addEventListener("click", function(event) {
            var xhr = new XMLHttpRequest();
            xhr.onload = function(e) {
                if (this.readyState === 4) {
                    console.log("Server returned: ", e.target.responseText);
                    document.getElementById("audio_msg").innerHTML = "<span>" + e.target.responseText + "</span>";   
                }
            };
            var fd = new FormData();
            fd.append("diary_id", "<?= $diary_id; ?>");
            fd.append("audio_data", blob, filename);
            xhr.open("POST", "<?php echo base_url()?>teacher/add_audio_in_diary", true);
            xhr.send(fd);
        })
        li.appendChild(document.createTextNode(" ")) //add a space in between 
        // li.appendChild(upload)
        //add the upload link to li

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
        document.getElementById("audio_msg").innerHTML = "<span>Recording started...</span>";

    }).catch(function(err) {
        //enable the record button if getUserMedia() fails 
        recordButton.disabled = false;
        stopButton.disabled = true;
        pauseButton.disabled = true;
        document.getElementById("audio_msg").innerHTML = "<div class='alert alert-danger'>Microphone is not Connetced</div>"; 
        alert_dismiss();
        
    });
}

function alert_dismiss()
{
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
}

</script>