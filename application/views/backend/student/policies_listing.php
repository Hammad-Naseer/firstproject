
<style>
	@media (max-width:800px){.panel-body{margin-bottom:10px!important}}.fa{padding-right:1px!important}.fa-plus-square{color:#507895}.title{border:1px solid #eae7e7;min-height:34px;padding-top:10px;background-color:rgba(242,242,242,.35);color:#8c8c8c;height:auto}.adv{width:50px}.tt{background-color:rgba(242,242,242,.35);min-height:26px;padding-top:4px}.tt2{max-height:400px;overflow-y:auto;overflow-x:hidden}.panel-default>.panel-heading+.panel-collapse .panel-body{border-top-color:#21a9e1;border-top:2px solid #ccc}.panel-body{padding:9px 22px}.myfsize{font-size:11px!important}.panel-group .panel>.panel-heading>.panel-title>a{display:inline}.fa-file-o{padding-right:0!important}.panel{margin-bottom:20px;background-color:#fff;border:1px solid rgba(0,0,0,.08);border-radius:4px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(43,43,43,.15)}.panel-title{width:100%}.panel-group.joined>.panel>.panel-heading+.panel-collapse{background-color:#fff}.difl{display:inline;float:left}.bt{margin-bottom:-28px;padding-top:15px;padding-right:31px}.pdr43{padding-right:43px}.title_collapse{padding:5px;font-size:18px;font-color:#000;color:#000;padding:5px 15px!important;cursor:pointer;border:1px solid rgba(204,204,204,.64)}.crud a{color:#b3b3b3;font-size:12px;padding-top:5px}
</style>
    
<?php
    if ( $this->session->flashdata( 'club_updated' ) ) {
	echo '<div align="center">
	<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	' . $this->session->flashdata( 'club_updated' ) . '
	</div> 
	</div>';
}
?>
<script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
    </script>
<script>
    $(document).ready(function() {
        $('#filter_submit').on('click', function() {
            var pol_cat_id = $('#pol_cat').val();
            if (pol_cat_id != "") {
                window.location.href = "<?php echo base_url();?>policies/policies_listing/filter/" + pol_cat_id;
            }
        });
    });
    </script>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
            </a>
            <h3 class="system_name inline">
                <!--  <i class="entypo-right-circled carrow">
                    </i>-->
                <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/po.png"><?php echo get_phrase('organizational_policies');?> 
            </h3>
            
            <?php
    		if (right_granted('policycategory_manage'))
	    	{?>
                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_add_policy_category/');" class="btn btn-primary pull-right">
                    <i class="entypo-plus-circled"></i>
                    <?php echo get_phrase(' Add Policy Category');?>
                </a>
            <?php
        	}
            ?>
           
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12" style="    padding: 0px;" data-step="1" data-position='top' data-intro="Collapse the panel to see school policies details">
          
          
                <?php
                $qry = "SELECT p.*, pc.title as category_title 
                FROM " . get_school_db() . ".policies p 
                INNER JOIN " . get_school_db() . ".policy_category pc
                ON p.policy_category_id = pc.policy_category_id
                WHERE 
                p.school_id=" . $_SESSION[ 'school_id' ] . "
                and p.is_active=1 and p.student_p=1
                ORDER BY p.policies_id DESC";
                
                $data = $this->db->query( $qry )->result_array(); //$this->db->get_where('policies',$data_)->result_array();
		
	
		
            foreach ( $data as $row )
            {
            ?>
                        
<div class="" style="margin-bottom: 0px;" id="">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"> <!-- data-parent="#accordion"  -->
                                        <a data-toggle="collapse" href="#collapse<?php echo $row['policies_id'];?>" style="    font-size: 15px !important;">
                                            
                                            
                                            <?php echo $row['title'];?>
                                            <span class="myfsize">
                                            
                                            ( No. : <strong><?php echo $row['document_num'];?></strong>
                                            Ver. : <strong><?php echo $row['version_num'];?></strong>)
                                            </span>
                                        </a>
                                        
                                        <span style="float:right;padding-top: 4px;" class="myfsize"> 
									<?php
						    		if (right_granted('schoolpolicies_manage'))
							    	{?>
										<a href="<?php echo base_url();?>policies/add_edit_policies/<?php echo $row['policies_id'];?>/edit" >
										<i class="entypo-pencil"></i>
										<?php echo get_phrase('edit');?>
										</a>
									<?php
									}
						    		if (right_granted('schoolpolicies_delete'))
							    	{?>
										<a href="#" onclick="confirm_modal('<?php echo base_url();?>policies/policies_listing/delete/<?php echo $row['policies_id'].'/'.$row['attachment']; ?>');">
										<i class="entypo-trash"></i>
<?php echo get_phrase('delete'); ?>
</a>
									<?php
									}
									?>
</span>
</h4>
</div>
<div id="collapse<?php echo $row['policies_id'];?>" class="panel-collapse collapse ">
<div class="panel-body">
<div class="row tt2">
<div class="col-sm-12">
<table class="table  table-responsive" style="margin-bottom:0px;"> <tr>
                                                        <td>
                                                            <div>
                                                                <strong><?php echo get_phrase('policy_category');?>:</strong>
                                                                <?php echo $row['category_title']; ?>
                                                            </div>
                                                            <div>
                                                                <strong><?php echo get_phrase('author');?>:</strong>
                                                                <?php echo $row['author'];?>
                                                            </div>
                                                            <div>
                                                                <strong><?php echo get_phrase('approved_by');?>:</strong>
                                                                <?php echo $row['approved_by']; ?>
                                                            </div>
                                                            <div>
                                                                <strong><?php echo get_phrase('approval_date');?>:</strong>
                                                                <?php echo convert_date($row['approval_date']); ?>
                                                            </div>
                                                            <div>
                                                                <strong><?php echo get_phrase('last_updated');?>:</strong>
                                                                <?php echo convert_date($row['last_update_date']); ?>
                                                            </div>
                                                            <div>
                                                            <?php if($row['attachment']!=""){ ?>
<strong><?php echo get_phrase('download_policy');?>:</strong>
<span>
<a target="_blank" href="<?php echo display_link($row['attachment'],'policies');?>"> <i class="fa fa-download" aria-hidden="true"></i></a>

</span>

<?php } ?>
</div>
<br>
<div>
<?php  echo '<span class="">'.$row['detail'].'</span>'; ?>
</div>
</td>
</tr>
</table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
            </div>
        </div>
    </div>
<script>
    function myFunction(a) {
        $(".child" + a).slideToggle("slow");
    }
</script>
