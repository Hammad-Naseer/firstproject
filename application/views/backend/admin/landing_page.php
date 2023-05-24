<?php
if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
?>
<script>
    $( window ).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
</script>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <!--<a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">-->
        <!--    <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>-->
        <!--</a>-->
        <h3 class="system_name" data-step="1" data-position='bottom' data-intro="school configuration you can edit your school information">
            <?php echo get_phrase('landing_page');?>
        </h3>
    </div>
</div>

<div class="row">
        <!--<div class="col-md-12">-->
            <div class="panel panel-primary">
                <div class="panel-body">
                    <form action="<?=base_url()?>landing_page/update_info" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="2" data-position='bottom' data-intro="enter your school name this field is required">
                                <label class="control-label">
                                <?php echo get_phrase('mobile_no');?><span class="star">*</span></label>
                                <input type="text" class="form-control" name="mobile_num" value="<?php echo $landing_page_row->mobile_num;?>" required>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label for="field-2" class=" control-label">
                                    <?php echo get_phrase('whatsapp_number');?>
                                    <span class="star">*</span>
                                </label>
                                <input type="text" class="form-control" name="whatsapp" value="<?php echo $landing_page_row->whatsapp_num;?>" required>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('email');?>
                                    <span class="star">*</span>
                                </label>
                                <input type="email" class="form-control" name="email" value="<?php echo $landing_page_row->email;?>" required>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('facebook');?>
                                </label>
                                <input type="text" class="form-control" name="facebook" value="<?php echo $landing_page_row->facebook;?>">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('twitter');?>
                                </label>
                                <input type="text" class="form-control" name="twitter" value="<?php echo $landing_page_row->twitter;?>">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('linkedin');?>
                                </label>
                                <input type="text" class="form-control" name="linkedin" value="<?php echo $landing_page_row->linkedin;?>">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('latitude');?>
                                </label>
                                <input type="text" class="form-control" name="latitude" value="<?php echo $landing_page_row->latitude;?>">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('longitude');?>
                                </label>
                                <input type="text" class="form-control" name="longitude" value="<?php echo $landing_page_row->longitude;?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('about_us');?>
                                    <span class="star">*</span>
                                </label>
                                <textarea class="form-control" name="about_us" rows="5"><?php echo $landing_page_row->about_us;?></textarea>
                            </div>
                        </div>
                        <!--file uploading frontend coding-->             <div class="row" style="margin-top: 25px;">
                            
                            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                                <label class="custom-file-label">
                                <?php echo get_phrase('logo');?></label>
                                <input type="file" class="custom-file-input" name="logo" accept="image/*">
                                <!--<div class="fileinput fileinput-new" data-provides="fileinput">-->
                                <!--    <input type="hidden" value="" name="...">-->
                                <!--    <div class="fileinput-new " style="max-width: 200px;  max-height: 150px;" data-trigger="">-->
                                <!--    </div>-->
                                <!--    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 6px;"></div>-->
                                <!--    <div style="    margin-left: -54px;">-->
                                <!--        <span class="btn btn-white btn-file">-->
                                <!--            <span class="fileinput-new"><?php echo get_phrase('select_logo');?></span>-->
                                <!--            <span class="fileinput-exists"><?php echo get_phrase('change');?></span>-->
                                <!--            <input type="file" class="form-control" name="logo" value="<?php echo get_phrase('select_new_image_file');?>" >-->
                                <!--            <input type="file" name="" accept="image/*" value="Select" id="sysem_file" onchange="file_validate('sysem_file','img','img_f_msg')">-->
                                <!--        </span>-->
                                <!--        <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo get_phrase('remove');?></a>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <br>
                                <span style="color: green;"><?php echo get_phrase('allowed_file_size');?>: 200kb, <?php echo get_phrase('allowed_file_types');?>: png, jpg, jpeg </span>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <?php if($landing_page_row->logo !=""){ ?>
                                    <img class="img-responsive" height="70" src="<?php echo base_url().'assets/landing_pages/'.$landing_page_row->sub_domain.'/'.$landing_page_row->logo ?>" style="width:200px;display:block;text-align:center" />
                                <?php } ?>
                            </div>
                            
                            </div>
                            <div class="col-lg-2 col-md-2"></div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                                <label class="custom-file-label">
                                <?php echo get_phrase('banner_image');?></label>
                                <input type="file" class="custom-file-input" name="banner_image" accept="image/*" aria-describedby="inputGroupFileAddon01">
                                <!--<div class="fileinput fileinput-new" data-provides="fileinput">-->
                                <!--    <input type="hidden" value="" name="...">-->
                                <!--    <div class="fileinput-new " style="max-width: 200px;  max-height: 150px;" data-trigger="">-->
                                <!--    </div>-->
                                <!--    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 6px;"></div>-->
                                <!--    <div style="    margin-left: -108px;">-->
                                <!--        <span class="btn btn-white btn-file">-->
                                <!--            <span class="fileinput-new"><?php echo get_phrase('select_banner_image');?></span>-->
                                <!--            <span class="fileinput-exists"><?php echo get_phrase('change');?></span>-->
                                <!--            <input type="file" class="form-control" name="banner_image" value="<?php echo get_phrase('select_new_image_file');?>" >-->
                                <!--            <input type="file" name="" accept="image/*" value="Select" id="sysem_file" onchange="file_validate('sysem_file','img','img_f_msg')">-->
                                <!--        </span>-->
                                <!--        <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo get_phrase('remove');?></a>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <br>
                                <span style="color: green;"><?php echo get_phrase('allowed_file_size');?>: 200kb, <?php echo get_phrase('allowed_file_types');?>: png, jpg, jpeg </span>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <?php if($landing_page_row->banner_image !=""){ ?>
                                        <img class="img-responsive" height="70" src="<?php echo base_url().'assets/landing_pages/'.$landing_page_row->sub_domain.'/'.$landing_page_row->banner_image ?>" style="width:200px;display:block;text-align:center" />
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <!--<span style="color: red;" id="img_f_msg"></span>-->
                        <div class="row mt-4">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" data-step="4" data-position='top' data-intro="press this button save this information">
                            <div class="float-right">
            					<button type="submit" class="modal_save_btn">
            						<?php echo get_phrase('save');?>
            					</button>
            					<button type="button" class="modal_cancel_btn" onclick="location.reload()">
            						<?php echo get_phrase('cancel');?>
            					</button>
            				</div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        <!--</div>-->
    </div>
    <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <!--<a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">-->
        <!--    <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>-->
        <!--</a>-->
         <?php if(count($gallery_rows)<6) { ?>
	            <a href="javascript:;" data-step="3" data-position='left' data-intro="Press this button & add new Gallery Image" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_add_gallery_image/');" class="btn btn-primary pull-right">
	                <i class="entypo-plus-circled"></i>
	                <?php echo get_phrase(' Add Gallery Image');?>
	            </a>
	       <?php } ?>
        <h3 class="system_name" data-step="1" data-position='bottom' data-intro="school configuration you can edit your school information">
            <?php echo get_phrase('gallery_images');?>
        </h3>
         
    </div>

	   </div>
    <div class="row table-responsive">
       
        <!--<div class="col-md-12">-->
          
                     <div class="col-lg-12 col-md-12 col-sm-12">
            <?php if(count($gallery_rows)<6) { ?>
	           <span style="color: red;"><b>Remaing Images For Landing Page : <?= 6-count($gallery_rows) ?> </b> </span>
	       <?php } ?>
	        <?php if(count($gallery_rows)>=6) { ?>
	           <span style="color: green;"><b>Gallery Images : <?= count($gallery_rows) ?> </b> </span>
	       <?php } ?>
	        <br>
        </div>
       
                  <table class="table table-bordered table_export" width="100%" data-step="4" data-position="top" data-intro="events announcement record">
                    <thead>
                        <tr>
                            <th style="width:34px">
                                <div>#</div>
                            </th>
                            <th>
                                <div>
                                    <?php echo get_phrase('image');?>
                                </div>
                            </th>
                            <th style="width:94px">
                                <div>
                                    <?php echo get_phrase('options');?>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $r=0; 
                     
                        foreach($gallery_rows as $row)
                        {
                            $r++;
                    ?>
                        <tr>
                            <td class="td_middle">
                                <?php echo $r?>
                            </td>
                            <td>
                                <div class="myttl">
                                     <img  class="img-responsive" src="<?php echo base_url().'assets/landing_pages/'.$landing_page_row->sub_domain.'/'.$row['image']?>" style="width:100px;display:block;text-align:center" />
                                </div>
                           
                            </td>
                            <td class="td_middle">
                                
                                <div class="btn-group" data-step="3" data-position="left" data-intro="Events Assign / edit / delete">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <?php echo get_phrase('action'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                     
                                       
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_gallery_image/<?php echo $row['img_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                        <!-- DELETION LINK -->
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>landing_page/delete_gallery_image/<?php echo $row['img_id'];?>');">
                                                <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                        </li>
                                        
                                        
                                    </ul>
                                </div>
                                
                            </td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
                </div>
                
                
                
                
                
                
                
                
                
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
             <?php if(count($school_facilities)<6) { ?>
    	            <a href="javascript:;" data-step="5" data-position='left' data-intro="Press this button to add new Facility" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_add_edit_new_facility/');" class="btn btn-primary pull-right">
    	                <i class="entypo-plus-circled"></i>
    	                <?php echo get_phrase('Add Facility Info');?>
    	            </a>
    	       <?php } ?>
            <h3 class="system_name" data-step="6" data-position='bottom' data-intro="school facilities listing">
                <?php echo get_phrase('facilities');?>
            </h3>
        </div>
	</div>
	
    <div class="row table-responsive">
       
        <div class="col-lg-12 col-md-12 col-sm-12">
            <?php if(count($school_facilities) < 6) { ?>
	           <span style="color: red;"><b>Remaing Facilities For Landing Page : <?= 6 - count($school_facilities) ?> </b> </span>
	       <?php } ?>
	        <?php if(count($school_facilities) >= 6) { ?>
	           <span style="color: green;"><b>Facilities : <?= count($school_facilities) ?> </b> </span>
	       <?php } ?>
	        <br>
        </div>
       
        <table class="table table-bordered table_export" width="100%" data-step="7" data-position="top" data-intro="school facilities">
            <thead>
                <tr>
                    <th style="width:34px">
                        <div>#</div>
                    </th>
                    <th>
                        <div>
                            <?php echo get_phrase('title');?>
                        </div>
                    </th>
                    <th>
                        <div>
                            <?php echo get_phrase('url');?>
                        </div>
                    </th>
                    <th style="width:94px">
                        <div>
                            <?php echo get_phrase('options');?>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $r=0; 
                 
                    foreach($school_facilities as $row)
                    {
                        $r++;
                ?>
                        <tr>
                            <td class="td_middle">
                                <?php echo $r?>
                            </td>
                            <td>
                                <div class="myttl">
                                     <?php echo $row['title']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="myttl">
                                     <?php echo $row['url']; ?>
                                </div>
                            </td>
                            <td class="td_middle">
                                
                                <div class="btn-group" data-step="8" data-position="left" data-intro="Facility / edit / delete">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <?php echo get_phrase('action'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                     
                                       
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_edit_new_facility/<?php echo $row['id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                        <!-- DELETION LINK -->
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>landing_page/delete_school_facility/<?php echo $row['id'];?>');">
                                                <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                        </li>
                                        
                                        
                                    </ul>
                                </div>
                                
                            </td>
                        </tr>
                    <?php }?>
            </tbody>
        </table>
    
    </div>            
           