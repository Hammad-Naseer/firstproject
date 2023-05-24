<style>
@media (max-width:800px){.panel-body{margin-bottom:10px!important}}.title{border:1px solid #eae7e7;min-height:34px;padding-top:10px;background-color:rgba(242,242,242,.35);color:#8c8c8c;height:auto}.adv{width:50px}.tt{background-color:#f2f1f1;border:1px solid #eae7e7;min-height:26px;padding-top:4px}.panel-default>.panel-heading+.panel-collapse .panel-body{border-top-color:#21a9e1;border-top:2px solid #ccc}.panel-body{padding:9px 22px}.myfsize{font-size:11px!important}.myfsize a{font-size:11px!important}.panel-group .panel>.panel-heading>.panel-title>a{display:inline}.fa-file-o{padding-right:0!important}.panel{margin-bottom:20px;background-color:#fff;border:1px solid rgba(0,0,0,.08);border-radius:4px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(43,43,43,.15)}.panel-title{width:100%}.panel-group.joined>.panel>.panel-heading+.panel-collapse{background-color:#fff}.difl{display:inline;float:left}.bt{margin-bottom:-28px;padding-top:15px;padding-right:31px}.panel-heading>.panel-title{float:left!important;padding:10px 15px!important;color:#fff}.myterm{color:#fdfeff!important;background-color:#012b3c!important}.myfsize a{font-size:11px!important;color:#fff!important}.fs{font-size:12px!important}.ph{padding-bottom:5px;padding-top:5px}.br{border-bottom:1px solid #eee}
.yearlyterm{padding-top:10px;padding-left:10px;margin-top:10px;border-left:2px solid #00a651!important;width: 100%;}.bg{background-color:#f2f1f1}.mychild:nth-child(odd){background:#f7f7f7}.mychild:nth-child(even){background:#eee}.yearlyterm span.myfsize a,.yearlyterm span.myfsize i::before{color:#000!important}.red{font-weight:400!important}.blue{font-weight:400!important}.orange{font-weight:400!important}.green{font-weight:400!important}
</style>
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
$(window).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>



<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('academic_year_&_terms'); ?>
        </h3>
    </div>
     <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <?php
    	 if (right_granted('academicyearterms_manage'))
	     {
	    ?>
	       <a href="javascript:;" data-step="1" data-position='left' data-intro="Press this button to add academic year" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/acadmic_year_add_edit/');" class="btn btn-primary pull-right">
    	            <i class="entypo-plus-circled"></i>
    	            <?php echo get_phrase('add_Academic_year');?>
    	   </a>
        <?php
         }
        ?>
    </div>
</div>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <?php
        $school_id=$_SESSION['school_id'];
        $data_=array('school_id'=>$school_id);
        $this->db->order_by('status','desc');
		$students=$this->db->get_where(get_school_db().'.acadmic_year',$data_)->result_array();
		$counter = 0;
		
		
            foreach($students as $row)
            {
                $counter++;
              ?>
        <div class="panel-group" id="accordion<?php echo $row['academic_year_id'];?>">
            <div class="panel panel-default" style="border-radius: 0px;">
                <div class="panel-heading">
                    <h4 class="panel-title" data-step="3" data-position='bottom' data-intro="Press this icon to see academic term the selected year">
                        <a data-toggle="collapse"  href="#collapse<?php echo $row['academic_year_id'];?>" style="font-size:17px;">
                            <?php echo $row['title'];?>
                            
                            <span class="myfsize">
                                (<?php echo get_phrase('start_date'); ?>: <strong><?php echo convert_date($row['start_date']);?></strong> /<?php echo get_phrase('end_date'); ?>: <strong>
                                    <?php echo convert_date($row['end_date']);?></strong>)
                                    <?php $statsArr=year_term_status();
                                    $status=$row['status'];
										
										
                                    if(array_key_exists($status,$statsArr))
                                    {
                                        $mystatus = '';
                                        if($row['status']==1)
                                        {
                                            $mystatus = 'green';
                                        }
                                        elseif($row['status']==2)
                                        {
                                            $mystatus = 'blue';
                                        }
                                        elseif($row['status']==3)
                                        {
                                            $mystatus = 'red';
                                        }
                                        ?>
                                        <span class="<?php echo $mystatus; ?>">
                                        <?php echo $statsArr[$row['status']];
                                        if($row['is_closed']==1)
                                        {
                                            echo get_phrase('(_closed_)');
                                        }
                                    }?>	
				                        </span>
                            </span>
                            
                        </a>
                        <span class="myfsize" style="margin-left: 50px;"> 
		       <?php 
				if (right_granted('academicyearterms_manage'))
		        {

			       if($row['status']!=1 )
			       {?>
		        		<a href="#" data-step="2" data-position='left' data-intro="Press this button to add academic year & term" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/yearly_terms_add_edit/<?php echo $row['academic_year_id'];?>');">
		         	
		         
			       		<span> <i class="fa fa-plus-square" aria-hidden="true"></i><?php echo get_phrase('Add Term');?></span>
			                        </a>
			        <?php 
			        }?>
                    <a href="#" data-step="4" data-position='left' data-intro="edit academic year" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/acadmic_year_add_edit/<?php echo $row['academic_year_id'];?>');">
                        <i class="entypo-pencil"></i>
                        <?php echo get_phrase('edit');?>
                    </a>
                <?php
             	}
             	if (right_granted('academicyearterms_delete'))
             	{
             	?>
                        <a href="#" data-step="5" data-position='left' data-intro="delete academic year" onclick="confirm_modal('<?php echo base_url();?>academic_year/acadmic_year_listing/delete/<?php echo $row['academic_year_id'];?>');">
                            <i class="entypo-trash"></i>
                            <?php echo get_phrase('delete');?>
                        </a>
                <?php
            	}
                ?>
                        </span>
                    </h4>
                </div>
                
                <?php $showByDefault = ($counter == 1) ? "show" : ""; ?>
                <div id="collapse<?php echo $row['academic_year_id'];?>" class="panel-collapse collapse <?php echo $showByDefault; ?>">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <p><strong><?php echo get_phrase('academic_year_detail'); ?>:</strong></p>
                                <?php echo $row['detail'];?>
                            </div>
                            <div class="yearlyterm">
                                <p style="padding-top: 0px; "><strong><?php echo get_phrase('yearly_term'); ?>:</strong></p>
                                
                                <?php
                                    $q="SELECT * FROM ".get_school_db().".yearly_terms WHERE academic_year_id=".$row['academic_year_id']." ";
						            $termArr=$this->db->query($q)->result_array();
						            
						            foreach($termArr as $term)
						            { ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 br mychild table-striped table-hover  ">
                                <p class="panel-title ph">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                <strong><?php echo $term['title'];?></strong>
                                    <span class="myfsize"> (<?php echo get_phrase('start_date'); ?>	:<strong><?php echo convert_date($term['start_date']);?>
                                    </strong> - <?php echo get_phrase('end_date'); ?>:<strong><?php echo convert_date($term['end_date']);?></strong>) 
                                    
                                    <?php $statsArr=year_term_status();
				 
                            		 $status=$term['status'];
                            		 if(array_key_exists($status,$statsArr))
                            		 {
                        				$mystatus = '';
                        				if($term['status']==1)
                        				{
                        					$mystatus = 'green';
                        				}
                        				elseif($term['status']==2)
                        				{
                        					$mystatus = 'blue';
                        				}
                        				elseif($term['status']==3)
                        				{
                        					$mystatus = 'red';
                        				}
                        				?>
                        				 <span class="<?php echo $mystatus; ?>">
                        				 	 
                        				 <?php
                        					 echo $statsArr[$term['status']];
                        					 if($term['is_closed']==1)
                        					 {
                        					 	echo " (Closed)";
                        					 }
                            		}
                            		?>	</span>
                                        <span style="float:right;padding-top: 4px;" class="myfsize"> 
                                		 <?php
                                		 if($row['status']!=1)
                                		 {
                                		 	if (right_granted('academicyearterms_manage'))
                                             {
                                		 	?>
                                		 		<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/yearly_terms_add_edit/<?php echo $row['academic_year_id']?>/<?php echo $term['yearly_terms_id']?>');"> <i class="entypo-pencil"></i> <?php echo get_phrase('edit'); ?> </a>
                                		 	<?php
                                		 	}
                                		 	if (right_granted('academicyearterms_delete'))
                                		 	{
                                		 	?>
                                		 <a href="#" onclick="confirm_modal('<?php echo base_url();?>academic_year/yearly_terms/delete/<?php echo $term['yearly_terms_id']?>');"> <i class="entypo-trash"></i><?php echo get_phrase('Delete'); ?></a>	
                                		 <?php }
                                		}
                                		 ?>
                                		 
                                		 </span>
                                </p>
                                    <p class="fs"><?php echo $term['detail']?></p>
                                </div>
                                <?php }
                                ?>
                            </div>
                            <!-- ----yearly term---------- -->
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

<!-- ---  DATA TABLE EXPORT CONFIGURATIONS --- -->
<script>
$(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 0; // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = " Show more >>";
    var lesstext = " << Show less";


    $('.item').each(function() {
        var content = $(this).html();

        if (content.length > showChar) {

            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);

            var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span><a href="" class="morelink">' + moretext + '</a></span>';

            $(this).html(html);
        }

    });

    $(".morelink").click(function() {
        if ($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});
</script>
