<?php 
    $array = explode('-',$param3);
    $urlArr = explode('/',$_SERVER['REQUEST_URI']);
    $subject_id = $this->uri->segment(4);
?>

<div class="tab-pane box " id="add" style="padding: 5px">
    <div class="box-content subj-clr">
        <form action="<?=base_url()?>subject/upload_sallybus/<?= $subject_id ?>" name="add_subject_sallybus" id="add_subject_sallybus" method="post" class="form-horizontal form-groups-bordered validate" enctype="multipart/form-data">
        	
	        <div class="panel panel-primary" data-collapsed="0">
	            <div class="panel-heading">
	                <div class="panel-title black2">
	                    <i class="entypo-plus-circled"></i>
                        <?php echo get_phrase('upload_syllabus_of');?>
			            <span class="themecolor">			
						    <?php echo $array[0]."(".$array[1].".".$array[2].")"; ?>
						</span>
	                </div>
	            </div>
	            <div class="panel-body">
	                <div class="form-group">
	                    <label for="field-2" class="control-label">
	                        <?php echo get_phrase('academic_year');?><span class="star">*</span>
	                    </label>
	                    <select id="acad_year" name="academic_year_id" class="form-control" required>
                            <?php echo academic_year_option_list($qur_val[0]['pro_academic_year_id'],$status=1);?>
                        </select>
	                </div>
	                <div class="form-group">
	                    <label for="field-2" class="control-label">
	                        <?php echo get_phrase('syllabus_type');?><span class="star">*</span>
	                    </label>
                        <select class="form-control sallybus_type" name="sallybus_type" required>
                            <option>Select Syllabus Type</option>
                            <?php echo sallybus_type_option_list(); ?>
                        </select>
	                </div>     
	                <div class="form-group sallybus_content" style="display:none">
	                    <label for="field-2" class="control-label">
	                        <span class="sallybus_content_title"></span>
	                        <span class="star">*</span>
	                    </label>
	                    <div class="content_data"></div>
	                    <textarea id='ckeditor' class='form-control' style="display:none;"></textarea>
	                </div>     
	                <div class="form-group">
	                    <label for="field-2" class="control-label">
	                        <?php echo get_phrase('status');?><span class="star">*</span>
	                    </label>
                        <select class="form-control" name="status" required="">
                            <option>Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
	                </div> 
				   <div class="form-group">	
				        <div class="float-right">
                            <input type="hidden" name="subject_id" id="subject_id" value="<?php echo $param2?>">
	                        <input type="hidden" name="hidden_component_id" id="hidden_component_id" >
                            <button type="submit" id="btn_submit1" class="modal_save_btn"><?php echo get_phrase('save');?></button>
                            <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                <?php echo get_phrase('cancel');?>
                            </button>
                        </div>
	               </div> 
	            </div>
	        </div>
        </form>
    </div>
</div>

<div id="list_new"></div>
<div class="tab-pane box active" id="list1">
</div>

<script src="https://cdn.ckeditor.com/4.14.1/standard-all/ckeditor.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    // $(".sallybus_content").hide();
    $(".sallybus_type").on("change",function(){
        var type = $(this).val();
        var label = $(".sallybus_content_title");
        var content = $(".content_data");
        
        if(type == '1')
        {
            $(".sallybus_content").css("display","block");
            label.text("Content");
            content.html("");
            $("#ckeditor").css("display","block");
            $("#cke_ckeditor").css("display","block");
            $("#ckeditor").attr("name","content");
        }else if(type == '2')
        {
            $(".sallybus_content").css("display","block");
            label.text("Document / Image");
            content.html("<input type='file' name='document'>");
            $("#cke_ckeditor").css("display","none");
            $("#ckeditor").css("display","none");
            $("#ckeditor").attr("name","");
        }else if(type == '3')
        {
            $(".sallybus_content").css("display","block");
            label.text("Youtube Iframe");
            content.html("<textarea class='form-control' name='content'></textarea>");
            $("#cke_ckeditor").css("display","none");
            $("#ckeditor").css("display","none");
            $("#ckeditor").attr("name","");
        }else if(type == '4')
        {
            label.text("Viemo Iframe");
            $(".sallybus_content").show();
            content.html("<textarea class='form-control' name='content'></textarea>");
            $("#cke_ckeditor").css("display","none");
            $("#ckeditor").css("display","none");
            $("#ckeditor").attr("name","");
        }else{
            label.text('');
            $(".sallybus_content").css("display","none");
            $("#ckeditor").css("display","none");
            content.html("");
            $("#cke_ckeditor").css("display","none");
        }
    });
    
    CKEDITOR.plugins.addExternal('ckeditor_wiris', 'https://www.wiris.net/demo/plugins/ckeditor/', 'plugin.js');
    CKEDITOR.config.uiColor = '#427fa6';
    CKEDITOR.config.width = '100%'; 
     
    CKEDITOR.replace('ckeditor', {
         extraPlugins: 'ckeditor_wiris',
         height: 200,
         // Remove the redundant buttons from toolbar groups defined above.
         removeButtons: 'Styles,removeFormat,Strike,Anchor,SpellChecker,PasteFromWord,Image,Source,Text,Copy,Paste,Cut,plaintext,Undo,Redo,About'
    });
});
</script>
