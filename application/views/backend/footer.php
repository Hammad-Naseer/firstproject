<script>
    
    function delete_files(img_name,table_name,field_id,id,img_field,folder_name,return_id,file_type=1,db_name="",school_id="",is_root=0)
	{
		$("#"+return_id).html("<div class='loader_small'></div>");

		$.ajax({
				type: 'POST',
				data: {img_name:img_name,table_name:table_name,field_id:field_id,id:id,img_field:img_field,folder_name:folder_name,db_name:db_name,school_id:school_id,is_root:is_root},
				url: "<?php echo base_url();?>delete_files/delete_file",
				dataType: "html",
				success: function(response) {
					if(file_type==1){
					$("#"+return_id).html("<img  style='height: 100px; width: 100px;' src='<?php echo  base_url().'/uploads/default.png'; ?>'  alt='...'>");
					}

					if(file_type==2){
					$("#"+return_id).html(" ");
					}
				}
			});	
	}

    function file_validate(field_name,file_type="img",msg_return)
    {
    	var field_val=document.getElementById(field_name);
    	
    	if(field_val.files[0]!=undefined)
    	{
    		$("#"+msg_return).html("<div class='loader_small'></div>");
    		var size=field_val.files[0].size;
    		var ext=field_val.value.substring(field_val.value.lastIndexOf(".")+1);
    		
    		//var ext = field_val.value.match(/\.(.+)$/)[1];
    
    		$("#img_msgg").remove();
    
    		$.ajax({
    			type: 'POST',
    			data: {size:size,ext:ext,file_type:file_type},
    			url: "<?php echo base_url();?>attachments/upload_file",
    			dataType: "html",
    			success: function(response)
    			{
    				if($.trim(response)=="file_type_size")
    				{
    					$("#"+field_name).val("");
    					$("#"+msg_return).html("<?php echo get_phrase('please_upload_files_according_to_specified_details'); ?>."+"<br><?php echo get_phrase('current_file_size'); ?>: "+Math.round(size/1024)+" kb"+", File type: "+ext);
    					
    				}
    				else if($.trim(response)=="size")
    				{
    					$("#"+field_name).val("");
    					$("#"+msg_return).html("<?php echo get_phrase('please_upload_files_according_to_specified_details'); ?>."+"<br><?php echo get_phrase('current_file_size'); ?>: "+Math.round(size/1024)+" kb");
    					
    				}
    				else if($.trim(response)=="file_type")
    				{
    					$("#"+field_name).val("");
    					$("#"+msg_return).html("<?php echo get_phrase('please_upload_files_according_to_specified_details'); ?>."+"<br><?php echo get_phrase('current_file_type'); ?>: "+ext);
    					
    				}
    				
    				else
    				{
    					$("#"+msg_return).html("");
    				}
    
    			}
    		});
    
    	}
    	else
    	{
    	$("#"+msg_return).html("");
    	}	
    }

</script>


<style type="text/css">
    .footerPara {
      text-align:center !important;font-size: 1.1em !important;color:#cac5c5 !important  
    }
    .foter-bottm {
    background-color: #121212;
    }
    ul.footr-li-list {
        text-decoration: none;
        list-style: none; 
        margin-bottom: 12px;
        margin-top: 12px;
        padding: 0;
    }
    ul.footr-li-list li a i {
        margin: 0px 8px 0px 15px;
    }
    ul.footr-li-list li a {
        color: #fff;
    }   

.container-fluid.foter-top {
    background: #333333;
    padding: 20px 0px;
}
img.indici-edu-footr-logo {
    border-right: solid 3px #0dcaf0;
    padding-right: 24px; 
    max-width:305px;
}
.text-white {
    color: #fff !important;
}
.text-white p {
    color: #fff !important;
}

@media only screen and (min-width: 768px) {
    ul.footr-li-list {
        display:flex;
        justify-content: center;
    }
    .foter-logs {
    text-align: right;
}
}
@media only screen and (max-width: 768px) {
    img.indici-edu-footr-logo {
    border-right: solid 0px #0dcaf0;  
    width:100%; 
}
}

</style>

 <footer class="footer-main"> 

    <div class="container-fluid foter-top">
        <div class="container"> 
         <div class="row align-items-center">
          <div class="col-sm-12 col-md-6 col-lg-6 foter-logs d-none">
              <img src="https://indiciedu.com.pk/frontend/wp-content/uploads/2021/01/indici-edu-logo-SVG.svg" class="indici-edu-footr-logo">
          </div>
         <div class="col-md-12  col-md-12 col-lg-12 text-sm-start text-md-start text-white text-center">
            <p class="m-1 text-center">INDICI-EDU IS A FLAGSHIP PRODUCT OF CAMPUSNETIC SOLUTIONS (AN ICT & EDUTECH STARTUP) </p>
            <p class="text-center">WHICH IS SUPPORTED BY F3 GROUP OF TECHNOLOGY COMPANIES. © 2017-<?= date("Y") ?> ALL RIGHTS RESERVED</p>
         </div> 
         </div>
        </div>
    </div>

    <div class="container-fluid d-none">    
    <div class="row foter-bottm"> 
        <div class="col-12 text-sm-start text-md-center">
          <ul class="footr-li-list d-md-inline-flex pt-2">
            <li><a href="https://indiciedu.com.pk"><i class="fa fa-globe"></i>www.indiciedu.com.pk</a></li>
            <li><a href="https://web.whatsapp.com/send?phone=923155172825&amp;text=Hey!!!%20i%20am%20interested%20in%20indici-edu%20services."><i class="fa fa-whatsapp"></i>+92 315 5172825</a></li>
            <li><a href="mailto:info@indiciedu.com.pk"><i class="fa fa-envelope"></i>info@indiciedu.com.pk</a></li><a href="mailto:info@indiciedu.com.pk">
            </a><li><a href="mailto:info@indiciedu.com.pk"></a><a href="https://www.facebook.com/Indici.edu"><i class="fa fa-facebook-f"></i>www.facebook.com/Indici.edu</a></li>
          </ul>
        </div>
    </div>
    </div>
    
    <!--<br><br>-->
    <!--<div class="row copyrights-settig">-->
    <!--    <div class="col-md-12">-->
    <!--            <p class="footerPara">indici-edu is a flagship product of CAMPUSNETIC SOLUTIONS (an ICT & EduTech Strartup)</p>-->
    <!--            <p class="footerPara">© 2017-2021 All rights reserved. This website/portal (or any part thereof) may not be<br>reproduced in any form without the prior written permission of Campusnetic Solutions.</p>-->
    <!--            <p class="footerPara">CAMPUSNETIC SOLUTIONS is supported by F3 Group of Technology Companies.</p>-->
    <!--    </div>-->
    <!--</div>-->
    
    
    <!-- 
    <div class="row copyrights-settig">
        <div class="col-lg-12 col-md-12 col-sm-12 text-center">
             <span>Powered by:</span> <a href="http://campusnetic.com" target="_blank">CAMPUSNETIC SOLUTIONS</a> | <span>Supported by:</span> <a href="http://www.fairfactorforce.com" target="_blank">F3 Group of Technology Companies</a>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 text-center">
             <span>INDIC EDU Version: </span> <?php //echo get_current_version(); ?>
        </div>
    </div>
    -->
    
</footer>
		
