
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
	
	
	$( window ).load(function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
    });
	
	
	
	
</script>




<div class="col-md-12 col-lg-12 col-sm-12">


<h4>
 <?php echo get_phrase('promotion_chalan_detail');?>
</h4>
<div class="col-md-2 col-lg-2 col-sm-2">
	 <?php echo get_phrase('department_name');?>
	<br />
	<?php echo $query[0]['department']; ?>
</div>

<div class="col-md-2 col-lg-2 col-sm-2">
		 <?php echo get_phrase('class_name');?> 
	<br />
	<?php echo $query[0]['class']; ?>
</div>

<div class="col-md-2 col-lg-2 col-sm-2">
		 <?php echo get_phrase('section_name');?>
	<br />
	<?php echo $query[0]['section']; ?>
</div>
<div class="col-md-2 col-lg-2 col-sm-2">
		 <?php echo get_phrase('fee_month');?>/ <?php echo get_phrase('year');?>
	<br />
	<?php 
	
	echo month_of_year(date("m",$query[0]['fee_month_year'])).'-'.date("Y",$query[0]['fee_month_year']);
	
	
	
	?>
</div>
</div>



<br><br>
<table class="table table-bordered datatable  table-hover cursor" id="table_export">
    <thead>
        <tr>
          
   
            <th ><div><?php echo get_phrase('#');?></div></th>
               <th ><div><?php echo get_phrase('detail');?></div></th>
            
                     <th><div><?php echo get_phrase('options');?></div></th>

        </tr>
    </thead>
    <tbody>
        <?php 
     
$b=0;
                foreach($query as $row):
		
		
		$b++; ?>
        <tr>
            
         
            <td><?php echo $b;?></td>
            <td>
              
              
              <div class="myttl"><?php echo $row['student_name'];?><span style="font-size:11px;">( <?php echo get_phrase('chalan_form');?> #: <?php  echo $row['chalan_form_number']
              ?>)</span></div>
              <div><strong> <?php echo get_phrase('father_name');?>: </strong><?php echo $row['father_name'];?></div>
        
              
              
              
              
              
              
              </td>
            
            <td> 
               
                <div class="btn-group">
<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                         <?php echo get_phrase('action');?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                        <!-- STUDENT EDITING LINK -->
                        <li>
                            <a href="<?php echo base_url(); ?>class_chalan_form/edit_chalan_form/<?php echo $row['s_c_f_id'];  ?>/2">
                                <i class="entypo-pencil"></i>
                                    <?php echo get_phrase('View Chalan');?>
                                </a>
                                        </li>            
                 
                        

                        
                        
                        
                        
                        <?php if($row['status']<5){ ?>
                        <!-- STUDENT DELETION LINK -->
                        
                               <li class="divider"></li>
                        <li>
                        
                        
                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>monthly_fee/cancel_single_request/<?php echo  $row['s_c_f_id']; ?>');">
                                <i class="entypo-trash"></i>
                                    <?php echo get_phrase('Cancel Chalan'); ?>
                                </a>
                        </li>
                    
                    <?php } ?>
                    
                    </ul>
                </div>
                
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<a class="btn btn-primary" href="<?php echo base_url();?>monthly_fee/monthly_bulk_listing"> <?php echo get_phrase('back_to_listing');?></a>
