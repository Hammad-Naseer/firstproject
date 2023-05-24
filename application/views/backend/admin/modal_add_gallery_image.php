<?php
get_instance()->load->helper('coa');

$school_id= $_SESSION['sys_sch_id'];
if(preg_match("/\/(\d+)$/",$_SERVER['REQUEST_URI'],$matches)){
    $id=$matches[1];
   /* $edit_data=$this->db->get_where(get_school_db().'.chart_of_accounts' , array('coa_id' => $id,'school_id'=>$school_id) )->result_array();*/

    $edit_data_str = "select * from ".get_system_db().".school_gallery_images  where
                          school_id= $school_id AND img_id = $param2";
    $edit_data = $this->db->query($edit_data_str)->row();
 $edit_data =(Array)  $edit_data ;

}

if($edit_data){
    $url='landing_page/gallery_images_action/edit/';


}
else{
    $url='landing_page/gallery_images_action/create/';
}
//populate parent head
//$this->db->select('coa_id,account_head');
//$this->db->from(get_school_db().'.chart_of_accounts');
//$parent_id_array=$this->db->get()->result_array();

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title black2">
                    <i class="entypo-plus-circled"></i>
                    <?php 
                    if($edit_data){
                    echo get_phrase('Edit Gallery Image');
                    }
                    else
                    {
                         echo get_phrase('Add Gallery Image');
                    }
                    ?>
                </div>
            </div>
            <div class="panel-body">
                <?php //print_r($edit_data); ?>
                <?php echo form_open(base_url().$url , array('id'=>'gallery_image_action','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                 <?php if($edit_data){ ?>
                        <input type="hidden" id="" required  name="img_id" value="<?php echo $edit_data['img_id']?>" >
                         <input type="hidden" id=""  required name="old_image" value="<?php echo $edit_data['image']?>" >
                    <?php } ?>
                <!--Parent Head Start-->
             
                <div class="form-group">
                    <label for="field-2" class="control-label"><?php echo get_phrase('select_image'); ?> 
                    <span class="star">*</span></label>
                    <input  type="file"  class="form-control" name="image" >
                </div>
                <!--Account Number end-->

                <!--Status code start-->
         
                <!--Status code End-->

                <!--Is Actibe Start-->
          

                <!--- chart of account types End -->






                <!--Save Charts of acount start-->
                <div class="form-group">
                    <div class="float-right">
                        <button type="submit" id="btn_save" class="modal_save_btn">
    						<?php echo get_phrase('save');?>
    					</button>
    					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
    						<?php echo get_phrase('cancel');?>
    					</button>
                    </div>
                </div>
                <!--Save Charts of Account End-->
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<style>
    #message{

        color: red;


    }
    .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #63b7e7; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }

    .loader_small {
        border: 7px solid #f3f3f3;
        border-top: 7px solid #63b7e7;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }
    .form-check-label {

        padding: 0 19px 19px 0;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }


    }

</style>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>
