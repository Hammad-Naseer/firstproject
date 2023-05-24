<style>
.per_day {
    border: 1px solid #EEE;
    width: 50px !important;
}

.per_week {
    border: 1px solid #EEE;
    width: 50px !important;
}
</style>
<?php 
	$urlArr=explode('/',$_SERVER['REQUEST_URI']);
	$resArr=end($urlArr);
	$section_id=$resArr;
	$section_detail=(section_hierarchy($section_id));
	/*$q="SELECT * from ".get_school_db().".subject  where school_id=".$_SESSION['school_id']." order by name ASC";
	$subject_list=$this->db->query($q)->result_array();
	$section_detail=(section_hierarchy($section_id));*/
?>
<div class="row" style="margin-bottom:10px;">
	<div class="col-sm-12">
    	<div class="panel-heading ">
            	<div class="panel-title text-white">
    	<span class="text-white">
        <b><?php echo get_phrase('assign_subjects_to');?></b>
        </span>
    	<ul class="breadcrumb breadcrumb2">
            <li>  <?php  echo $section_detail['d']?> </li> 
            <li> <?php echo $section_detail['c'] ?> </li> 
            <li> <?php echo $section_detail['s']?></li> 
        </ul>
        </div>
    </div>
        <input type="hidden" name="section_id" id="section_id" value="<?php echo $section_id; ?>">
	</div>
</div>

 <form action="" method="post" id="filter"  class="form-horizontal form-groups-bordered validate" style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px; ">
    <div class="col-lg-12 col-sm-12" style="padding: 0px;"> 
        <select id="subj_categ_select" class="form-control mb-2" name="subj_categ_select" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
            <?php echo get_subj_category($this->input->post('subj_categ_select'));?>
        </select>
        
        <div class="float-right" style="margin-bottom:10px;">
            <input type="submit"  value="<?php echo get_phrase('filter');?>" class="modal_save_btn" id="btn_sub">
            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_section_subject/<?php echo $section_id;?>')" class="btn btn-danger" id="btn_remove1" style="display:none;">			<i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>    
        </div>        
        
    </div> 
</form>       

<div id="list"></div>
<script>
$(document).ready(function() {
	$('#btn_remove1').hide();
    document.getElementById('filter').onsubmit = function() {
        return false;
    };	
    $('#list').load("<?php echo base_url(); ?>departments/sec_subj_generator"+"/"+"<?php echo $section_id;?>");
    
    $('#btn_sub').click(function(){
    	var section_id='<?php echo $section_id;?>';
    	var subj_categ_id=$('#subj_categ_select').val();
        if(subj_categ_id!="")
        {
            $('#btn_remove1').show();
            $('#list').html('<div id="message" class="loader"></div>');
            $.ajax({
                type: 'POST',
                data: {section_id:section_id,subj_categ_id:subj_categ_id},
                url: "<?php echo base_url();?>departments/sec_subj_generator",
                dataType: "html",
                success: function(response) {
             	    $('#message').remove();
                    $('#list').html(response);
                }
            });
        
        }
    });		
});	
</script>

