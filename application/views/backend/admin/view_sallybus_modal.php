<?php 
    $array = explode('-',$param3);
    $urlArr = explode('/',$_SERVER['REQUEST_URI']);
    $yearly_id = $this->uri->segment(4);
    $get_sallybus = $this->db->where("subject_sallybus_id",$yearly_id)->get(get_school_db().".subject_sallybus")->row();
    
     
?>

<div class="tab-pane box " id="add" style="padding: 5px">
    <div class="box-content subj-clr">
	    <div class="panel panel-primary" data-collapsed="0">
	            <div class="panel-heading">
	                <div class="panel-title black2">
	                    <i class="entypo-plus-circled"></i>
                        <?php echo get_phrase('upload_sallybus_of');?>
			            <span class="themecolor">		
						    <?php echo $array[0]."(".$array[1].".".$array[2].")"; ?>
						</span>
	                </div>
	            </div>
	            <div class="panel-body">
	                <div class="form-group text-center">
	                    <?php
	               
	                    if($get_sallybus->sallybus_type == '1'){ ?>
	                        <?= $get_sallybus->sallybus_data ?>
	                    <?php }else if($get_sallybus->sallybus_type == '2'){ ?>
	                        <a href="<?= base_url() ?>/uploads/<?= $_SESSION['folder_name'] ?>/subject_sallybus/<?= $get_sallybus->sallybus_data ?>" download>
	                            <i class="fa fa-file" style="font-size: 110px;"></i>
	                            <h4>Download File</h4>
	                        </a>
	                    <?php }else if($get_sallybus->sallybus_type == '3'){ ?>
	                        <iframe src="<?= $get_sallybus->sallybus_data ?>" width="100%"></iframe>
	                    <?php }else if($get_sallybus->sallybus_type == '4'){ ?>
	                        <iframe src="<?= $get_sallybus->sallybus_data ?>" width="100%"></iframe>
	                    <?php } ?>
	                </div> 
				    <div class="form-group">	
				        <div class="float-right">

                            <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                <?php echo get_phrase('cancel');?>
                            </button>
                        </div>
	                </div> 
	            </div>
	        </div>
    </div>
</div>

<div id="list_new"></div>
<div class="tab-pane box active" id="list1">
</div>

