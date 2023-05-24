<style>
@media (max-width:800px){.panel-body{margin-bottom:10px!important}}.fa{padding-right:1px!important}.fa-plus-square{color:#507895}.myfsize{font-size:11px!important}.panel-group .panel>.panel-heading>.panel-title>a{display:inline}.panel-title a{color:#fff!important}
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
                <b><i class="fas fa-info-circle"></i>Interactive tutorial</b>
            </a>
            <h3 class="system_name inline">
                   <?php echo get_phrase('organizational_policies'); ?>
            </h3>
        </div>
            <?php if (right_granted('policycategory_manage')){ ?>
		    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
	            <a href="javascript:;" data-step="2" data-position='left' data-intro="Press this button to add new policy category" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_add_policy_category/');" class="btn btn-primary pull-right">
	                <i class="entypo-plus-circled"></i>
	                <?php echo get_phrase('Add Policy Category');?>
	            </a>
	       </div>
	        <?php } ?>
    </div>


        <div class="col-sm-12">
        <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0px;">
         <?php 
          
          if(count($query_val)>0)
          {
                $counter = 0;
                foreach($query_val as $row_val)
                {
                    $counter++;
                ?>
                    <div class="title_collapse" style="border-left:4px solid #1864C0;    font-size: 17px;">
                
                        <span style="display:inline;" onclick="myFunction(<?php echo $row_val['policy_category_id'];?>)"> <?php echo $row_val['title']; ?> <i class="fa fa-caret-down" aria-hidden="true"></i></span>
                    
                        <?php
                        if (right_granted('policycategory_delete'))
                        {?>
                        	<a class="ed" style="float:right" href="#" data-step="4" data-position='left' data-intro="policy delete button" onclick="confirm_modal('<?php echo base_url();?>policies/policy_categories/delete/<?php echo $row_val['policy_category_id']; ?>');"><i class="entypo-trash"></i>  <?php echo get_phrase('delete');?>  </a>
                        <?php
                        }
                        if (right_granted('policycategory_manage'))
                        {?>
                    
                        	<a class="ed" href="#" data-step="5" data-position='left' data-intro="policy edit button" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_policy_category/<?php echo $row_val['policy_category_id'];  ?>');" style="float:right;"> <i class="entypo-pencil"></i>  <?php echo get_phrase('edit');?> </a>
                        <?php
                        }
                        if (right_granted('schoolpolicies_manage'))
                        {?>
                            <a class="myterm" style="float:right;" data-step="3" data-position='left' data-intro="Press this button & add policy details" href="<?php echo base_url(); ?>policies/add_edit_policies/<?php echo $row_val[" policy_category_id "]; ?>/add">
                                <i class="fa fa-plus-square" aria-hidden="true"></i>
                                <?php echo get_phrase('add_policy');?>
                            </a>
                        <?php
                        }
                        ?>
                
                    </div>
                
                    <?php
                    $qry = "SELECT p.*, pc.title as category_title  FROM " . get_school_db() . ".policies p 
                	    INNER JOIN " . get_school_db() . ".policy_category pc ON p.policy_category_id = pc.policy_category_id
                	    WHERE  p.school_id=" . $_SESSION[ 'school_id' ] . " and  p.policy_category_id=" . $row_val[ 'policy_category_id' ] . " ";
                
                	$data = $this->db->query( $qry )->result_array();
                
                	if(count($data)==0)
                	{
                		?>
                            <div class="panel-group collapse child<?php echo $row_val['policy_category_id'];?> " style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo $row['policies_id'];?>">
                                <div class="panel panel-default" style="">
                                    <h4 class="panel-title" style="padding:10px; ">
                                         <?php echo get_phrase('no_policy_available');?>
                                    </h4>
                                </div>
                            </div>
                        <?php
                    }
                		    
                		    
                    foreach ( $data as $row )
                    {
                    ?>
                        <div id="" class="panel-group collapse collapseOur<?php echo $counter; ?>  child<?php echo $row_val['policy_category_id'];?> " style="margin :10px; padding:0px 10px 0px 10px;" id="accordion<?php echo $row['policies_id'];?>">
                        <div class="panel panel-default">
                        <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse"  href="#collapse<?php echo $row['policies_id'];?>" style="    font-size: 15px !important;">
                                   
                                    <?php echo $row['title'];?>
                                        <span class="myfsize">
                                        ( <?php echo get_phrase('no');?> : <strong><?php echo $row['document_num'];?></strong>
                                        <?php echo get_phrase('ver');?> : <strong><?php echo $row['version_num'];?></strong>)
                                        </span>
                                    </a>
                                    <span style="float:right;margin-right: 20px;">
                                        <?php
                                        if (right_granted('schoolpolicies_manage'))
                                        {?>
                                            <a href="<?php echo base_url();?>policies/add_edit_policies/<?php echo str_encode($row['policies_id']);?>/edit" >
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('edit');?>
                                            </a>
                                        <?php
                                        }
                                        if (right_granted('schoolpolicies_delete'))
                                        {?>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>policies/policies_listing/delete/<?php echo $row['policies_id'].'/'.$row['attachment']; ?>');"><i class="entypo-trash"></i>
                                            <?php echo get_phrase('delete'); ?>
                            	            </a>
                            			<?php
                            			}
                            			?>
                        			</span>
                            </h4>
                        </div>
                        <?php $showByDefault = ($counter == 1) ? "show" : ""; ?>
                        <div id="collapse<?php echo $row['policies_id'];?>" class="panel-collapse collapse <?php echo $showByDefault; ?>">
                            <div class="panel-body">
                                <div class="row">
                                <div class="col-sm-12" style="background: aliceblue;">
                                    <table class="table table-responsive">
                                        <tr>
                                            <td><strong><?php echo get_phrase('ploicy_category');?>:</strong></td>
                                            <td><?php echo $row['category_title']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php echo get_phrase('author');?>:</strong></td>
                                            <td><?php echo $row['author']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php echo get_phrase('approved_by');?>:</strong></td>
                                            <td><?php echo $row['approved_by']; ?></td>
                                        </tr>
                                        <tr>    
                                            <td><strong><?php echo get_phrase('approval_date');?>:</strong></td>
                                            <td><?php echo convert_date($row['approval_date']); ?></td>
                                        </tr>
                                        <tr>    
                                            <td><strong><?php echo get_phrase('last_updated');?>:</strong></td>
                                            <td><?php echo convert_date($row['last_updated']); ?></td>
                                        </tr>
                                        <tr>    
                                            <?php if($row['attachment']!=""){ ?>
                                            <td><strong><?php echo get_phrase('download_policy');?>:</strong></td>
                                            <td>
                                                <span><a target="_blank" href="<?php echo display_link($row['attachment'],'policies');?>"> <i class="fa fa-download" aria-hidden="true"></i></a></span>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <tr>    
                                            <td><strong><?php echo get_phrase('detail');?>:</strong></td>
                                            <td><?php echo $row['detail']; ?></td>
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
                
                } //  end of outer foreach loop
          }
          else{
				echo "No Record Found";
		    }
        ?>
        
        
        </div>
        </div>
        </div>
        
        <script>
            $(document).ready(function(){
                $(".collapseOur1").slideToggle("slow");
            });
        </script>
        
        
        <script>
        
            function myFunction(a) {
                $(".child" + a).slideToggle("slow");
            }
        
        </script>
