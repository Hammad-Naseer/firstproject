<style>
	.boarder{
		
		  
    border: 1px solid #f2f2f2;
  height: 34px;
		
	}
.modal-backdrop {
   
   z-index: 0 !important;
}
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
	
$( window ).load(function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
    });
	
</script>
<div class="row">
	<form action="" method="post" >
	
	<div class="col-lg-5 col-md-5 col-sm-5"><?php
	echo transection_filter("transection_filter","form-control",$_POST['transection_filter']);
	
	?></div>	
	<div class="col-lg-5 col-md-5 col-sm-5"></div>	
	<div class="col-lg-2 col-md-2 col-sm-2">
		<button type="submit" class="btn btn-primary">Filter</button>
		
	</div>	
		
	</form>
	
</div>
<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/transection_add_edit/');" 
    class="btn btn-primary pull-right">
        <i class="entypo-plus-circled"></i>
        <?php echo get_phrase('Add_new_transection');?>
    </a> 
  
<br><br>
<table class="table table-bordered datatable" id="table_export">
    <thead>
        <tr>
          <th><div><?php echo get_phrase('voucher #');?></div></th>
<th class="span3"><div><?php echo get_phrase('title');?></div></th>
<th><div><?php echo get_phrase('Transection_type');?></div></th>
<th><div><?php echo get_phrase('is Processed');?></div></th>
<th><div><?php echo get_phrase('Detail');?></div></th>

<th><div><?php echo get_phrase('options');?></div></th>
   
       </tr>
    </thead>
    <tbody>
        <?php 
        $school_id=$_SESSION['school_id'];
        
        $data_=array('school_id'=>$school_id);

if(isset($_POST['transection_filter'])){
	
	
	
	$transection_filter=$_POST['transection_filter'];
	
	if($transection_filter==1){

$data_['type']=2;

$students=$this->db->get_where('account_transection',$data_)->result_array();
		
	}
	
	elseif($transection_filter==2){

$data_['type']=1;

$students=$this->db->get_where('account_transection',$data_)->result_array();
		
	}elseif($transection_filter==3){

$data_['type']=2;
$data_['isprocessed']=0;

$students=$this->db->get_where('account_transection',$data_)->result_array();
		
	}elseif($transection_filter==4){

$data_['type']=1;
$data_['isprocessed']=0;

$students=$this->db->get_where('account_transection',$data_)->result_array();
		
	}elseif($transection_filter==5){

$data_['type']=2;
$data_['isprocessed']=1;

$students=$this->db->get_where('account_transection',$data_)->result_array();
		
	}elseif($transection_filter==6){

$data_['type']=1;
$data_['isprocessed']=1;

$students=$this->db->get_where('account_transection',$data_)->result_array();
		
	}
	
	
	
	
	
	
	
	
	
	

} 

else{
	
  
$students=$this->db->get_where(get_school_db()'.account_transection',$data_)->result_array();

}





                foreach($students as $row): ?>
        <tr>
            
            <td><?php echo $row['voucher_num'];?></td>
            <td><?php echo $row['title'];?></td>
        

  <td><?php 
  
  type_display($row['type']);
      ?></td>

 <td><?php 
  
  get_isprocessed($row['isprocessed']);
  
      ?></td>


<td>
	<span>Chart Of Account : <?php  
            
        $data_ary=get_coa($row['coa_id']);
            
     echo $data_ary['account_head'];
     echo ' <i class="fa myarrow fa-arrows-h" aria-hidden="true"></i> ';
     
     echo $data_ary['account_number'];
            
           
            
            
            
              ?></span><br />
	
	<span>Method : <?php 
  
  method_display($row['method']);
  
      ?></span><br />
	
	<span>Amount : <?php 
  
echo  $row['amount'];
  
      ?></span><br />
	
	<span>Detail : <?php 
  
echo  $row['detail'];
  
      ?></span><br />
	
	<span>Receipt # : <?php 
  
echo  $row['receipt_num'];
  
      ?></span>
	
	
</td>


            <td>
                
                <div class="btn-group">
<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        Action <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                        
                     
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                        
                        <!-- STUDENT EDITING LINK -->
                        <li>
                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/transection_add_edit/<?php echo $row['transection_id'];?>');">
                                <i class="entypo-pencil"></i>
                                    <?php echo get_phrase('edit');?>
                                </a>
                                        </li>
                                        
                                        
                                  
                                        
                        <li class="divider"></li>
                        
                        <!-- STUDENT DELETION LINK -->
                        <li>
                        
                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>transection_account/account_transection/delete/<?php echo $row['transection_id'];?>');">
                                <i class="entypo-trash"></i>
                                    <?php echo get_phrase('delete');?>
                                </a>
                                        </li>
                    </ul>
                </div>
                
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ----->                      
<script type="text/javascript">
jQuery(document).ready(function($)
	{
	var datatable = $("#table_export").dataTable({
			"sPaginationType": "bootstrap",
			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
			"oTableTools": {
				"aButtons": [
					
					{
						"sExtends": "xls",
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "pdf",
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(1, false);
							datatable.fnSetColumnVis(5, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(1, true);
									  datatable.fnSetColumnVis(5, true);
								  }
							});
						},
						
					},
				]
			},
			
		});
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>