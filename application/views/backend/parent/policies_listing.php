    <?php if ( $this->session->flashdata( 'club_updated' ) ) {
    	echo '<div align="center">
    	<div class="alert alert-success alert-dismissable">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    	' . $this->session->flashdata( 'club_updated' ) . '
    	</div> 
    	</div>';
    }?>
    <script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
    </script>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline">
                   <?php echo get_phrase('organizational_policies'); ?>
            </h3>
        </div>
    </div>
        <div class="col-sm-12">
            <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0px;" data-step="1" data-position='top' data-intro="Collapse this heading to view school policies details">
            <?php
            $qry = "SELECT p.*, pc.title as category_title  FROM " . get_school_db() . ".policies p  INNER JOIN " . get_school_db() . ".policy_category pc ON p.policy_category_id = pc.policy_category_id WHERE  p.school_id=" . $_SESSION[ 'school_id' ] . " and p.is_active=1 and p.student_p=1 ORDER BY p.policies_id DESC";
            $data = $this->db->query( $qry )->result_array();
            foreach ( $data as $row )
			{
				?>
                <div class="" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="font-size: 15px !important; border-left: 4px solid #8b3f37;border-radius: 0px;">
                            <h4 class="panel-title"> <!-- data-parent="#accordion" -->
                                <a data-toggle="collapse"  href="#collapse<?php echo $row['policies_id'];?>" style="    font-size: 15px !important;">
                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                                        <?php echo $row['title'];?>
                                        <span class="myfsize">
                                            (No.: <strong><?php echo $row['document_num'];?></strong>
                                            Ver.: <strong><?php echo $row['version_num'];?></strong>)
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
