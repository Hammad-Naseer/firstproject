<?php  
 $this->load->helper('teacher');
        $login_detail_id    = $_SESSION['login_detail_id'];
        $yearly_term_id_a   = $_SESSION['yearly_term_id'];
        $academic_year_id   = $_SESSION['academic_year_id'];
        $section_arr        = get_time_table_teacher_section($login_detail_id, $yearly_term_id_a);
?>
<html>
<head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
</head>
<style>
.received_withd .chat-right{display:flex;flex-direction:column;align-items:flex-end}.received_withd .chat-left{display:flex;flex-direction:column;align-items:start}
.container{max-width:1170px;margin:auto}img{max-width:100%}.inbox_people{background:#f8f8f8 none repeat scroll 0 0;float:left;overflow:hidden;width:40%;border-right:1px solid #c4c4c4}
.inbox_msg{border:1px solid #c4c4c4;clear:both;overflow:hidden}.top_spac{margin:20px 0 0}.recent_heading{float:left;width:40%}.srch_bar{display:inline-block;text-align:right;width:60%}
.headind_srch{padding:10px 29px 10px 20px;overflow:hidden;border-bottom:1px solid #c4c4c4}.recent_heading h4{color:#05728f;font-size:21px;margin:auto}.srch_bar input{border:1px solid #cdcdcd;border-width:0 0 1px;
width:80%;padding:2px 0 4px 6px;background:none}.srch_bar .input-group-addon button{background:rgba(0,0,0,0) none repeat scroll 0 0;
border:medium none;padding:0;color:#707070;font-size:18px}.srch_bar .input-group-addon{margin:0 0 0 -27px}.chat_ib h5{font-size:15px;color:#464646;margin:0 0 8px}.chat_ib h5 span{font-size:13px;float:right}
.chat_ib p{font-size:14px;color:#989898;margin:auto}.chat_img{float:left;width:11%}.chat_ib{float:left;padding:0 0 0 15px;width:88%}.chat_people{overflow:hidden;clear:both}.chat_list{border-bottom:1px solid #c4c4c4;
margin:0;padding:18px 16px 10px}.inbox_chat{height:550px;overflow-y:scroll}.active_chat{background:#ebebeb}.incoming_msg_img{display:inline-block;width:6%}.received_msg{display:inline-block;padding:0 0 0 10px;
vertical-align:top;width:92%}.received_withd_msg p{background:#ebebeb none repeat scroll 0 0;border-radius:3px;color:#646464;font-size:14px;margin:0;padding:5px 10px 5px 12px;width:100%}.time_date{color:#747474;
display:block;font-size:12px;margin:8px 0 0}.received_withd_msg{width:57%}.mesgs{float:left;padding:30px 15px 0 25px;width:60%}.sent_msg p{background:#05728f none repeat scroll 0 0;border-radius:3px;
font-size:14px;margin:0;color:#fff;padding:5px 10px 5px 12px;width:100%}.outgoing_msg{overflow:hidden;margin:26px 0}.sent_msg{float:right;width:46%}.input_msg_write input{background:rgba(0,0,0,0) none repeat scroll 0 0;
border:medium none;color:#4c4c4c;font-size:15px;min-height:48px;width:100%}.type_msg{border-top:1px solid #c4c4c4;position:relative}.msg_send_btn{background:#05728f none repeat scroll 0 0;border:medium none;
border-radius:50%;color:#fff;cursor:pointer;font-size:17px;height:33px;position:absolute;right:0;top:11px;width:33px}.messaging{padding:0 0 50px}.msg_history{height:516px;overflow-y:auto}
</style>

<body>
<div class="container-fluid mt-4">

<div class="messaging">
   
      <div class="inbox_msg" style="height: 104vh;">
        <div class="inbox_people">
           <div class="headind_srch">
            <div class="recent_heading">
              <!--<h4>Chat</h4>-->
            </div>
            <div>
   <form action="<?php echo base_url().'teacher/student_chat_list' ?>" method="POST" data-step="2" data-position='top' data-intro="Please select the filters and press Filter button to get specific assessments records">
    <div class="row">
    
    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group">
            <span style="float:right; color:red;" id="section_id_span"></span>
            <select id="section_id" class="dcs_list_add form-control" name="section_id" style="height:30px;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                <?php 
                    echo get_teacher_dep_class_section_list($section_arr , $section_id);
                ?>
            </select>
        </div>
    </div>
    <!-- 
	<div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group">
                    <select name="dep_c_s_id" id="dep_c_s_id" class="dcs_list form-control" >
                        <?php echo get_teacher_dep_class_section_list($teacher_section, $section); ?>
                    </select>
		</div>
	</div>
	-->
	<!--///temp-->
	 <div class="col-lg-8">
                    <div class="form-group">
                        <input type="hidden" name="apply_filter" value="1">
                         <button type="submit" class="btn btn-primary"> Filter</button>
                        <?php
                            if ($apply_filter == 1)
                            {
                            ?>
                              <a href="<?php echo base_url(); ?>teacher/student_chat_list" class="btn btn-danger"><i class="fa fa-remove"></i> Remove</a>
                            <?php
                            }
                          ?>
                    </div>
                </div>
	<!--End temp-->
	</div>
</form>
</div>
            <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                    <input type="text" id="myInput" onkeyup="search_list_teacher()"  class="form-control" placeholder="Search Teacher"/>
                </div>
          </div>
          <div class="inbox_chat" id="myUL" style="height: 100vh;" >
               <?php
               $chatidArray = array();
               $teacher_login_detail_id = $_SESSION['login_detail_id'];
               $index = 0;
               foreach($students as $std_chat){
                $student_id =$std_chat['student_id'];
                
                
                
                
            $chatidArray[$index] =  chat_id($teacher_login_detail_id,$student_id);
           
            // echo $chatID !=0 ? $chatID : '' ;
            ?>
            <div class="chat_list active_chat">
              <div class="chat_people">
                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                <div class="chat_ib">
                    <h5 class="teacher_search">
                        <a class="text-dark" onclick='check(<?php echo $student_id ?>)'><?php echo $std_chat['name'] ?></a>
                        	 <span class="chat_date badge rounded-pill float-end" id="<?php echo $chatidArray[$index] !=0 ? $chatidArray[$index] : 'c'  ?>" style="background-color: #f07c0c;;" value=""></span>
                    </h5>
                </div>
              </div>
            </div>
           <?php  $index++; }
           
           
           
           ?>
          </div>
        </div>
         <div id="navbar">
          <a href="#">
              <img src="" width="35">
                <a href="#" class="nav_lnk"><?php echo $teacher_arr[0]['name']; ?></a>
           <p id="teacher_name" style="color:white;text-align:center;font-size:19px!important;margin:-24px 13px 6.5px;"></p>
          </a>
          <a href="#" class="nav_lnk"><?php //echo get_subject_name($subject_id); ?></a>
        </div>
        	
        <div class="mesgs sections" id="message_box"  style="height: 100vh;" >
          <div class="msg_history" id="msg_history" style="height: 89vh;" >
            <!--<div class="incoming_msg">-->
            <!--  <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>-->
            <!--  <div class="received_msg">-->
            <!--    <div class="received_withd_msg">-->
            <!--      <p>Test, which is a new approach to have</p>-->
            <!--      <span class="time_date"> 11:01 AM    |    Yesterday</span></div>-->
            <!--  </div>-->
            <!--</div>-->
            <!--<div class="outgoing_msg">-->
            <!--  <div class="sent_msg">-->
            <!--    <p>Apollo University, Delhi, India Test</p>-->
            <!--    <span class="time_date"> 11:01 AM    |    Today</span> </div>-->
            <!--</div>-->
          </div>
          <div class="type_msg">
            <div class="input_msg_write">
            <input type="text" class="message_data" id="message_data" placeholder="Type a message" />
		    <input id="sender_id" name="sender_id" type="hidden" value="<?php echo $teacher_login_detail_id; ?>" />
	        <input id="rec_id" name="rec_id" type="hidden" value="<?php echo $parent_login_detailed_id; ?>" />
        	<input id="student_id" name="student_id" type="hidden" value="<?php echo $std_login_detail_id; ?>"  />
        	<input id="chat_id" name="chat_id" type="hidden" value="<?php echo $chat_id; ?>"  />
        	<input id="std_id" name="std_id" type="hidden" value=""  />
        	<input id="std_name" name="std_name" type="hidden" value=""  />
        	<input id="class_id" name="class_id" type="hidden" value=""  />
        	<input id="class_name" name="class_name" type="hidden" value=""  />
        	<input id="sec_id" name="sec_id" type="hidden" value=""  />
        	<input id="academic_year_id" name="academic_year_id" type="hidden" value="<?php echo $_SESSION['academic_year_id']; ?>"  />
        
        	<input id="te_name" name="te_name" type="hidden" value=""  />
        
              <button class="msg_send_btn scroll_to" id="ms"  onclick="scrollWindow('.msg_history')"><i class="fa fa-paper-plane text-white " aria-hidden="true"></i></button>
              <!--<label onclick = 'sendMessage();' >hey</label>-->
            </div>
          </div>
          <?php
            if($std_login_detail_id != 0 && $parent_login_detailed_id != 0)
            {
            $chat_id = $teacher_login_detail_id.$parent_login_detailed_id.$std_login_detail_id;
            }
            else{
            $chat_id = 'error creating chat_id';
            }
            ?>
        </div>
      </div>
    </div>
    </div>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.0.1/firebase.js"></script>
    
    <script type='text/javascript'>
    
    
    
    
   
    function message_id(){
        
         window.scrollBy(0,100);
    }
     function search_list_teacher() {
            var input, filter, ul, li,div, a, i,h5,source, txtValue;
            filter = document.getElementById("myInput").value.toUpperCase();
            mytable = document.getElementById("myUL");
            h5 = mytable.getElementsByClassName("chat_list");
            for (i = 0; i < h5.length; i++) {
               a = h5[i].getElementsByTagName("a")[0];
               txtValue = a.textContent || a.innerText;
                // console.log(txtValue);
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    h5[i].style.display = "";
                } else {
                    h5[i].style.display = "none";
                }
            }
        }
         function scrollWindow(){
              let scrollToEnd = (elem) =>{
            let el = document.querySelector(elem);
            el.scrollTop = el.scrollHeight;
        }
        scrollToEnd('.msg_history');
        //  window.scrollBy(0,100);
        window.scrollBy(0,100);
    }
    
    const firebaseConfig = {
    apiKey: "AIzaSyBMiZmINtQMZRoLFQyvI8cNB1QpbyxHg38",
    authDomain: "indici-edu-87fea.firebaseapp.com",
    projectId: "indici-edu-87fea",
    storageBucket: "indici-edu-87fea.appspot.com",
    messagingSenderId: "539112834438",
    appId: "1:539112834438:web:6dfb81f227359dc3e15807",
    measurementId: "G-DGVFNY582N"
         };
        // Initialize Firebase
    var firebase = firebase.initializeApp(firebaseConfig);

    
    // console.log("firebase connected");
    var db = firebase.firestore();

    $( document ).ready(function() 
    {
         
           
        var chatidArray =  <?php echo json_encode($chatidArray)  ?>;
        var teacherLoginDetailedID =  <?php echo json_encode($teacher_login_detail_id)  ?>;
        var chid = 0;
          
      
        for(var t = 0; t<chatidArray.length; t++ )
        {
            
            chid = chatidArray[t].toString();
             
          
            // console.log(chid+' '+tchid);
            getNotificationCount(chid,teacherLoginDetailedID)
            
           
        }
       
 
        function getNotificationCount(chatid,teacherLoginDetailedID)  
        {
          
            if(chatid != '0'){
                 db.collection('chatroom').doc(chatid).onSnapshot(function(snapshot){
                               
                                    const data = snapshot.data() || null;
                                    if (data) {
                                    var send_id = teacherLoginDetailedID.toString() +'unread_messages';

                                        let tmp = data[send_id];
                                        //   console.log(tmp)
                                            // var uc = tmp[send_id]
                                           
                                        if(tmp != 0)
                                        {
                                            $("#"+chatid).html(tmp);
                                           
                                        }
                                       
                                            
                                    }
                                });
               
          
            
            var    html = '';
                   html += "<div id='user"+chatid+"' class='userchat'> ";
                   html += "</div>";
                   $("#msg_history").append(html);
                  
                   $("#user"+chatid).hide();
            
            }
        }
    
  
        
    });
    
//     $("#msg_history").scroll(function(){
        
//     updateSenderMessages();
//   });

function clearChatHistory(currentChatID)
{
   
    var userchatids = <?php  echo json_encode($chatidArray)  ?>;
        for(var t = 0; t<userchatids.length; t++ )
        {

          if(currentChatID == userchatids[t])
           {
            //   console.log("one matched "+ chatid);
            //   $("#user"+chatid).show();
            $("#user"+userchatids[t]).html('');
           }
           else{
            //   console.log("user"+userchatids[t]);
               $("#user"+userchatids[t]).html('');
           }
           
        }
}

var saveUserChatID = 0;
var saveSenderID = 0;
var saveRecieverID = 0;


function check(data)
{
   

   
                var student_id = data;
               
                    $.ajax({
                        url:"<?php echo  base_url(); ?>Teacher/teacher_chat_module",
                        type:"POST",
                        data:{student_id:student_id},
                        dataType: "json",
                        
                        success:function(data){
                           
                            var send_id = data.send_id;
                            var rec_id = data.rec_id;
                            var std_id = data.student_id;
                            var chat_id = data.chat_id;
                            
                            saveUserChatID = chat_id;
                            saveSenderID = send_id;
                            saveRecieverID = rec_id;
                            clearChatHistory(saveUserChatID);
                            
                            console.log("send_id"+send_id);
                            console.log("rec_id"+rec_id);
                            console.log("std_id"+std_id);
                            console.log("chat_id"+chat_id);
                            console.log("***************");
                          
                            var name = data.name;
                            //  $( "#msg_history" ).addClass( "userchat"+chat_id );
                            // alert(chat_id);
                          
                            if(data){
                               
                                var html = "";
                                $('#chat_id').val(chat_id);
                                $('#rec_id').val(rec_id);
                                $('#sender_id').val(send_id);
                                $('#student_id').val(std_id);
                                $('#teacher_name').html(name);
                                updateSenderMessages();
                                var chatId = chat_id.toString();
                                // console.log(send_id +'=='+ chatId);
                                
                                db.collection("chatroom").doc(saveUserChatID.toString()).collection('chats').orderBy('time').get().then((querySnapshot) => {
                                    querySnapshot.forEach((doc) => {
                                    //     console.log(`${doc.id} => ${doc.data()}`);
                                    // });
                                // db.collection('chatroom').doc(saveUserChatID.toString()).collection('chats').orderBy('time').onSnapshot(function(snapshot){
                                    
                                // snapshot.docChanges().forEach(function(change){
                                        // var data = change.doc.data();
                                        var data = doc.data();
                                      
                                  
                                        html += "<div class='received_withd'> ";
                                       
                                        if(saveUserChatID == chatId){
                                            if(data.sent_by == saveSenderID)
                                            {
                                                
                                                html += "<div class='chat-right'>";
                                                html +="<div class='received_withd_msg'>";
                                                html += "<p >"+ data.message +"</p>";
                                                html +="<p class='time_date' style='color: black;background-color:burlywood;' >"+ moment(data.time).format("YYYY-MM-DD hh:mm A") +"</p>";
                                                html += "</div>";
                                                html += "<br>"
                                                html += "</div>";
                                            }
                                            else {
                                                
                                                html += "<div class='chat-left'>";
                                                html +="<div class='received_withd_msg'>";
                                                html += "<p>"+ data.message +"</p>";
                                                html +="<p class='time_date' style='color: black;background-color:burlywood;' >"+ moment(data.time).format("YYYY-MM-DD hh:mm A") +"</p>";
                                                html += "</div>";
                                                html += "<br>";
                                                html += "</div>";
                                            }
                                        }
                                        
                                       
                                        html += "</div>";
                                        $("#user"+chatId).html(html);
                                        $("#user"+saveUserChatID).show();

                                        scrollWindow('.msg_history');
                                    });
                                     
                                });
                                    hideusersChat(saveUserChatID);
                                   
                                        }
                                        else{
                                            
                                            $('#errorMessage').text("record not found");
                                        }
                                    },
                                    
                                });
            
        var controllerUrl = '<?php echo base_url();?>Teacher/getStudentIdForChat';
        $.ajax({
            type: 'POST',
            data : {student_id, student_id},
            url: controllerUrl,
            dataType: "json",
            success: function(response) {
          
                var class_id   = response.class_id;
                var class_name   = response.class_name;
                var parent_id  = response.parent_id;
                var teacher_name  = response.teacher_name;
                var stdSectionId     = response.stdSectionId;
                var student_id     = response.student_id;
                var student_name     = response.student_name;
                 
                $('#std_id').val(student_id);
                $('#std_name').val(student_name);
                $('#class_id').val(class_id);
                $('#class_name').val(class_name);
                $('#sec_id').val(stdSectionId);
                $('#parent_id').val(parent_id);
                $('#te_name').val(teacher_name);
            }
        }); 
}

function hideusersChat(chatid)
{
      var userchatids = <?php  echo json_encode($chatidArray)  ?>;
        for(var t = 0; t<userchatids.length; t++ )
        {
            
           if(chatid == userchatids[t])
           {
            //   console.log("one matched "+ chatid);
            //   $("#user"+chatid).show();
           }
           else{
            //   console.log("user"+userchatids[t]);
               $("#user"+userchatids[t]).hide();
           }
             
          
           
            
           
        }
}

function updateSenderMessages(){
        var sender_id = document.getElementById("sender_id").value;
        var ChatID = $("#chat_id").val();
       
         $("span#"+ChatID).hide();
       
                db.collection('chatroom').doc(ChatID)
              .get().then((value) => {
                                if(value.exists) {
                                var cal = value.data()[sender_id+'unread_messages'];
                             
                                var send_id = sender_id.toString() +'unread_messages';
                                
                                 
                             
                                 db.collection('chatroom').doc(ChatID).update( { [ send_id]:  0,
                                });
            }
                });
}

</script>
<script type="module">
    var input = document.getElementById("message_data");
    input.addEventListener("keyup", function(event) {
      if (event.keyCode === 13) {
          sendMessage();
      }
    });        
    
    
    $("#ms").click(function(){
        sendMessage();
    });
    function sendMessage() {
        // const d = moment().format("YYYY-MM-DD HH:mm:ss a");
        const d = new Date();
        var message = document.getElementById("message_data").value;
        var sender_id = document.getElementById("sender_id").value;
        var reciever_id = document.getElementById("rec_id").value;
        var student_id = document.getElementById("student_id").value;
        
        var ChatID = $("#chat_id").val();
        var std_id = $("#std_id").val();
        var std_name = $("#std_name").val(); 
        var class_id = $("#class_id").val();
        var class_name = $("#class_name").val();
        var sec_id = $("#sec_id").val();
        var academic_year_id = $("#academic_year_id").val();
        var teacher_name = $("#te_name").val();
       
        // console.log(teacher_name);
        message=message.trim();
        var uc = 0;
            db.collection('chatroom').doc(ChatID)
              .get().then((value) => {
                                        if(value.exists)
                                        {
                                            var cal = value.data()[sender_id+'unread_messages'];
                                            var send_id = sender_id.toString() +'unread_messages';
                                            var re_id = reciever_id.toString() +'unread_messages';
                                           
                                            let tmp = value.data();
                                            uc = tmp[re_id]
                                            uc = uc + 1;
                                          //  console.log(jQuery.type(uc) );
                                            db.collection('chatroom').doc(ChatID).update( { [re_id]:  uc,
                                                [send_id] : 0,
                                            });
                                        }
                                        else{
                                            var send_id = sender_id.toString() +'unread_messages';
                                            var re_id = reciever_id.toString() +'unread_messages';
                                            uc = uc + 1;
                                             db.collection('chatroom').doc(ChatID).set(
                                              {
                                                [ send_id]:  0,
                                                [ re_id]:  uc,
                                              },
                                            );
                                        }
       
                                    });
        
        if(message != null && message != "")
        {
            document.getElementById("message_data").value="";
            db.collection("chatroom").doc(ChatID).collection("chats").add({
                  "message": message,
                  "sent_by":sender_id,
                  "sent_to":reciever_id,
                  "student_id":student_id,
                  "time":d
            })
        }
        var controllerUrl = '<?php echo base_url();?>mobile_webservices/getdatafornotification';
        $.ajax({
            type: 'POST',
            data : {sender_id:sender_id , rec_id:reciever_id , student_id:student_id , chat_id:ChatID , message:message, std_id:std_id, std_name:std_name, class_name:class_name, teacher_name:teacher_name, stdSectionId:sec_id, academicYearId:academic_year_id},
            url: controllerUrl,
            dataType: "html",
            success: function(response) {
            }
        }); 
        return false;
    }
    </script>
    </body>
    </html>