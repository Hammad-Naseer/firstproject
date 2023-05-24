<!DOCTYPE html>
 <head>
   <meta charset="utf-8" />
   <style>
       body {
 margin: 0;
 padding: 3em 0;
 color: #fff;
 background: #0080d2;
 font-family: Georgia, Times New Roman, serif;
}
 
#container {
 width: 600px;
 background: #fff;
 color: #555;
 border: 3px solid #ccc;
 -webkit-border-radius: 10px;
 -moz-border-radius: 10px;
 -ms-border-radius: 10px;
 border-radius: 10px;
 border-top: 3px solid #ddd;
 padding: 1em 2em;
 margin: 0 auto;
 -webkit-box-shadow: 3px 7px 5px #000;
 -moz-box-shadow: 3px 7px 5px #000;
 -ms-box-shadow: 3px 7px 5px #000;
 box-shadow: 3px 7px 5px #000;
}
 
ul {
 list-style: none;
 padding: 0;
}
 
ul > li {
 padding: 0.12em 1em
}
 
label {
 display: block;
 float: left;
 width: 130px;
}
 
input, textarea {
 font-family: Georgia, Serif;
}
   </style>
  </head>
  <body>
   <div id="container">
    <h1>Send Test SMS</h1>
    <form action="<?php echo base_url(); ?>test_sms_email/smstesting" method="post">
     <ul>
      <li>
       <label for="phoneNumber">Phone Number : </label>
       <input type="text" name="phoneNumber" id="phoneNumber" required placeholder="923001234567" /></li>
      <li>
       <label for="smsMessage">Message : </label>
       <textarea name="smsMessage" id="smsMessage" cols="45" required rows="10"></textarea>
      </li>
     <li><input type="submit" name="sendMessage" id="sendMessage" value="Send Message" /></li>
    </ul>
   </form>
   
   <h1>Send Test EMAIL</h1>
    <form action="<?php echo base_url(); ?>test_sms_email/emailtesting" method="post">
     <ul>
      <li>
       <label for="email">Email : </label>
       <input type="text" name="email" id="email" required placeholder="abc@example.com" /></li>
      <li>
       <label for="emailMessage">Message : </label>
       <textarea name="emailMessage" id="emailMessage" cols="45" required rows="10"></textarea>
      </li>
     <li><input type="submit" name="sendEmail" id="sendEmail" value="Send Email" /></li>
    </ul>
   </form>
  </div>
 </body>
</html>