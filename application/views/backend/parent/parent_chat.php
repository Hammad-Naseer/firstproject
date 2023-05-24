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
vertical-align:top;width:92%}
.received_withd_msg p{background:#ebebeb none repeat scroll 0 0;border-radius:3px;color:#646464;font-size:14px;margin:0;padding:5px 10px 5px 12px;width:100%}
.received_withd_msgs p{background:burlywood none repeat scroll 0 0;border-radius:3px;color:#646464;font-size:14px;margin:0;padding:5px 10px 5px 12px;width:100%}
.time_date{color:#747474;
display:block;font-size:12px;margin:8px 0 0}.received_withd_msg{width:57%}.mesgs{float:left;padding:30px 15px 0 25px;width:60%}.sent_msg p{background:#05728f none repeat scroll 0 0;border-radius:3px;
font-size:14px;margin:0;color:#fff;padding:5px 10px 5px 12px;width:100%}.outgoing_msg{overflow:hidden;margin:26px 0}.sent_msg{float:right;width:46%}.input_msg_write input{background:rgba(0,0,0,0) none repeat scroll 0 0;
border:medium none;color:#4c4c4c;font-size:15px;min-height:48px;width:100%}.type_msg{border-top:1px solid #c4c4c4;position:relative}.msg_send_btn{background:#05728f none repeat scroll 0 0;border:medium none;
border-radius:50%;color:#fff;cursor:pointer;font-size:17px;height:33px;position:absolute;right:0;top:11px;width:33px}.messaging{padding:0 0 50px}.msg_history{height:516px;overflow-y:auto}
</style>

<body>
<div class="container-fluid mt-4">
<div class="messaging">
     <?php
        $section_id = $_SESSION['section_id'];
        $school_id = $_SESSION['school_id'];
        $yearly_term_id = $_SESSION['yearly_term_id'];
        $sub_arr = $this->db->query("select sub.* from ".get_school_db().".subject sub
        inner join ".get_school_db().".subject_section ss on sub.subject_id=ss.subject_id
        where ss.section_id = ".$section_id."
        and ss.school_id = ".$school_id." ")->result_array();
    ?>
      <div class="inbox_msg" style="height: 104vh;" >
        <div class="inbox_people">
           <div class="headind_srch">
            <div class="recent_heading">
              <!--<h4>Chat</h4>-->
            </div>
            <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                    <input type="text" id="myInput" onkeyup="search_list_teacher()"  class="form-control" placeholder="Search Teacher"/>
                </div>
          </div>
           <?php 
            $student_id= $_SESSION['student_id'];
            $school_id=$_SESSION['school_id'];
            $teacher_arr1 = $this->db->query("select staff.staff_id as teacher_id, crs.section_id, staff.user_login_detail_id as user_id,staff.name,st.teacher_id, staff.staff_image as teacher_image
            from " . get_school_db() . ".class_routine cr inner join " . get_school_db() . ".class_routine_settings crs on crs.c_rout_sett_id=cr.c_rout_sett_id and crs.section_id = $section_id
            inner join " . get_school_db() . ".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
            inner join " . get_school_db() . ".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
            inner join " . get_school_db() . ".staff on staff.staff_id = st.teacher_id
            where staff.school_id = " . $_SESSION['school_id'] . " 
            and staff.user_login_detail_id > '0'
            GROUP BY staff.name ")->result_array();
            $section_id = $_SESSION['section_id'];
            $parent_id_detail = $_SESSION['login_detail_id'];
           ?>
          <div class="inbox_chat" id="myUL" style="height: 100vh;">
               <?php  
               $chatidArray =  array();
               $teacherdetailedidArray =  array();
               $parentLoginDetailedID =  getParentLoginDetailedID($_SESSION['student_id']);
               $index = 0;
               foreach($teacher_arr1 as $teacher_arr){
                   $teach = $teacher_arr['teacher_id'];
                    $student_id = $_SESSION['student_id'];
                    $teacher_login_detail_id = $teacher_arr['user_id'];
                    $chatidArray[$index] =  chat_id($teacher_login_detail_id,$student_id); 
                    $teacherdetailedidArray[$index] =  $teacher_login_detail_id;
                    ?>
                <input type="hidden"  name="iddddd" value="<?php echo $chatID ?>">
                <?php
                    $login_detail_id = $teacher_arr['user_id'];
                    $section_id = $teacher_arr['section_id'];
                    $img_src = "";
        		    if($teacher_arr[0]['staff_image']==""){
                        $img_src = get_default_pic();
                    }
                    else{
                        $img_src = display_link($teacher_arr[0]['staff_image'],'staff');
                    }
            ?>
            <div class="chat_list active_chat">
              <div class="chat_people">
                <div class="chat_img"> <img src="<?= $img_src;?>" alt="sunil"> </div>
                <div class="chat_ib">
                    <h5 class="teacher_search">
                        <a class="text-dark" onclick='check(<?php echo $teach ?>)'><?php  echo $teacher_arr['name'] ?></a>
                     
                          <span class="chat_date badge rounded-pill float-end" id="<?php echo $chatidArray[$index] ?>" style="background-color: #f07c0c;" value=""></span>
                    </h5>
                </div>
              </div>
            </div>
             <?php
             $index++;
             
             }
             
            // print_r($studentdetailedidArray);
             ?>
          </div>
        </div>
          <div id="navbar">
          <a href="#">
              <img src="<?=$img_src;?>" width="35">
                <a href="#" class="nav_lnk"><?php  echo $teacher_arr[0]['name']; ?></a>
           <p id="teacher_name" style="color:white;text-align:center;font-size:19px!important;margin:-24px 13px 6.5px;"></p>
          </a>
          <a href="#" class="nav_lnk"><?php //echo get_subject_name($subject_id); ?></a>
        </div>
        <div class="mesgs" style="height: 100vh;">
          <div class="msg_history" id="msg_history" style="height: 89vh;">
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
               <input id="sender_id" name="sender_id" type="hidden" value="" />
		      <input id="rec_id" name="rec_id" type="hidden" value="" />
		      <input id="student_id" name="student_id" type="hidden" value="" />
		     <input type="hidden" id="chat_id" name="chat_id"  value="">
              <button class="msg_send_btn scroll_to" id="chat_btn" onclick="scrollWindow('.msg_history')" type="button"><i class="fa fa-paper-plane text-white" aria-hidden="true"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
 <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 <script src="https://www.gstatic.com/firebasejs/5.0.1/firebase.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
 
 <script type='text/javascript'>
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
    var db = firebase.firestore();
    
 
    
    $( document ).ready(function() 
    {
        var chatidArray =  <?php echo json_encode($chatidArray)  ?>;
        var parentLoginDetailedID =  <?php echo json_encode($parentLoginDetailedID)  ?>;
        var chid = 0;
        for(var t = 0; t<chatidArray.length; t++ )
        {
            chid = chatidArray[t];
            getNotificationCount(chid,parentLoginDetailedID)
        }
       
            function getNotificationCount(chatid,parentLoginDetailedID)
            {
           if(chatid != '0'){
                 db.collection('chatroom').doc(chatid).onSnapshot(function(snapshot){
                               
                               const data = snapshot.data() || null;
                                    if (data) {
                                     var send_id = parentLoginDetailedID.toString() +'unread_messages';
                                            let tmp = data[send_id];
                                        //   console.log(tmp)
                                            // var uc = tmp[send_id]
                                            if(tmp != 0)
                                            {
                                                $("#"+chatid).html(tmp);
                                            }
                                            
                                    }
                                });
            }
            }
            });


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
        scrollToEnd('#msg_history');
        //  window.scrollBy(0,100);
        window.scrollBy(0,100);
    }
    function check(data)
    {
                var teacher_id = data;
                var html = "";
                $("#myInputHidden").val(teacher_id);
                    $.ajax({
                        url:"<?php echo  base_url(); ?>Parents/chat_module",
                        type:"POST",
                        data:{teacher_id:teacher_id},
                        dataType: "json",
                        success:function(data){
                           
                            if(data){
                                // $('#msg_history').html(data);
                                // $('#chat_id').val(data);
                                var send_id = data.send_id;
                                var rec_id = data.rec_id;
                                var std_id = data.student_id;
                                var chat_id = data.chat_id;
                                var name = data.name;
                             console.log(send_id);  console.log(rec_id); console.log(std_id); console.log(chat_id); console.log(name);
                                $('#chat_id').val(chat_id);
                                $('#rec_id').val(rec_id);
                                $('#sender_id').val(send_id);
                                $('#student_id').val(std_id);
                                $('#teacher_name').html(name);
                                //  updateSenderMessages();
                                var chatId = chat_id.toString();
                                // const chat_data = db.collection('chatroom').doc(chatId).collection('chats');
                                db.collection('chatroom').doc(chatId).collection('chats').orderBy('time').onSnapshot(function(snapshot){
                                snapshot.docChanges().forEach(function(change){
                                        var data = change.doc.data();
                                        // console.log(data);
                                        // if(change.type == "added"){
                                        html += "<div class='received_withd'> ";
                                        if(data.sent_by == send_id)
                                        {
                                            html += "<div class='chat-right'>";
                                            html +="<div class='received_withd_msg'>";
                                            html += "<p >"+ data.message +"</p>";
                                            html +="<p class='time_date' style='color: black;' >"+ moment(data.time).format("YYYY-MM-DD hh:mm A") +"</p>";
                                            html += "</div>";
                                            html += "<br>"
                                            html += "</div>";
                                        }
                                        else{
                                            html += "<div class='chat-left'>";
                                            html +="<div class='received_withd_msgs'>";
                                            html += "<p>"+ data.message +"</p>";
                                            html +="<p class='time_date' style='color: black;' >"+ moment(data.time).format("YYYY-MM-DD hh:mm A") +"</p>";
                                            html += "</div>";
                                            html += "<br>";
                                            html += "</div>";
                                        }
                                        html += "</div>";
                                        $("#msg_history").html(html);
                                         scrollWindow('.msg_history');
                                    //   }
                                       
                                       
                                        
                                    });
                                    
                                });
                                        }
                                        else{
                                             $('#msg_history').html(data);
                                            $('#errorMessage').text("record not found");
                                        }
                                       
                                    },
                                });
                              
}

    function updateSenderMessages(){
        // var sender_id = document.getElementById("sender_id").value;
        var sender_id = $("#sender_id").val();
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
        $("#chat_btn").click(function(){
            sendMessage();
        });
        function sendMessage() {
            // const d = moment().format("YYYY-MM-DD HH:mm:ss a");
            const d = new Date();
            var message = document.getElementById("message_data").value;
            // var sender_id = document.getElementById("sender_id").value;
             var sender_id = $("#sender_id").val();
            var reciever_id = document.getElementById("rec_id").value;
            var student_id = document.getElementById("student_id").value;
            console.log('sender_id '+sender_id);
            
            message=message.trim();
            if(message != null && message != "")
            {
                document.getElementById("message_data").value="";
                // save in database
                var ChatID = $("#chat_id").val();
               
                // firebase.database().ref("testChat/"+ChatID).push().set({
                //     "message": message,
                //       "sent_by":sender_id,
                //       "sent_to":reciever_id,
                //       "student_id":student_id,
                //       "time":d
                // });
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
                                            console.log(jQuery.type(uc) );
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
            db.collection("chatroom").doc(ChatID).collection("chats").add({
                  "message": message,
                  "sent_by":sender_id,
                  "sent_to":reciever_id,
                  "student_id":student_id,
                  "isread":'0',
                  "time":d
            })
        }
        return false;
    }
</script>
    </body>
    </html>