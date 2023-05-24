

<table class="table table-bordered datatable" id="table_export_exp">
    <thead>
        <tr>
            <th>
                <div>
                    <?php echo get_phrase('s_no');?>
                </div>
            </th>
            <th class="span3">
                <div>
                    <?php echo get_phrase('details');?>
                </div>
            </th>
          
      
            <th style="width:108px;">
                <div>
                    <?php echo get_phrase('date');?>
                </div>
            </th>
              
      
            <th style="width:80px;">
                <div>
                    <?php echo get_phrase('amount');?>
                </div>
            </th>
            
            <th>
                <div>
                    <?php echo get_phrase('options');?>
                </div>
            </th>
            
        </tr>
    </thead>
    <tbody>
        <?php 
        $school_id=$_SESSION['school_id'];
        
        $data_=array('school_id'=>$school_id);

if($type_val!=""){

$data_['type']=$type_val;



}


if($start_date!=""){
	
 $date_1=date_slash($start_date);
	
	

	
$date_v=$this->db->where("date >= '$date_1' ");
}
if($end_date!=""){
	

	$date_2=date_slash($end_date);
	

	
$date_v=$this->db->where("date <= '$date_2' ");
}


$students=$this->db->get_where(get_school_db().'.account_transection',$data_)->result_array();

//$q=$this->db->last_query();
//echo $q;
$r=0;
foreach($students as $row)
	: $r++;?>
       
       
       
        <tr>
           
           
           
            <td>
                <?php echo $r;?>
            </td> 
             
        
            
            
            
            
            <td>
               
                <?php
				$myclass="";
				   if($row['type']==1){
					   
					   $myclass= "color:red"; 
					   
				   }
				   else if($row['type']==2) {
					   
					   
					     $myclass= "color:green";
				   }   
				?>
              
              
              
              
              
               <div class="myttl"> <?php echo $row['title'];?> <span style="font-size:12px;">(<?php echo get_phrase('voucher');?>#: <?php echo $row['voucher_num'];?>)</span><span style="font-size:12px; <?php echo $myclass ?>"> (<?php echo type_display($row['type']);?>)</span></div>
               
         
               
               
               
       
               <div>
               	
               	
               	
               <span><strong><?php echo get_phrase('chart_of_account');?>:</strong> <?php  
            
        $data_ary=get_coa($row['coa_id']);
            
     echo $data_ary['account_head'];
     echo ' <i class="fa myarrow fa-arrows-h" aria-hidden="true"></i> ';
     
     echo $data_ary['account_number'];
            
           
            
            
            
              ?></span>
               
				</div>
              
              
              
              
              <div>
               
               
               
            
                <span><strong><?php echo get_phrase('method');?>:</strong> <?php 
  
echo  method_display($row['method']);
  
      ?></span>
              	</div>
              	
              	
              	
              	

               	
               	<div>
                <span><strong><?php echo get_phrase('receipt');?>#:</strong> <?php 
  
echo  $row['receipt_num'];
  
      ?></span> 	
               	
               	
               	
               	
               	
               	
               </div>
               
 
               
               
                	<div>
                <span><strong><?php echo get_phrase('detail');?>:</strong> <?php 
  
echo  $row['detail'];
  
      ?></span>
               	</div>
               	
                
          
                
                
            </td>
       
           <td>
           	                          	
              	

               	
               	<div>
                <span><?php 
  
echo convert_date($row['date']);
  
      ?></span> 	
               	
               	
               	
               	
               	
               	
               </div>
               
           	
           	
           </td>
           
           <td>
           	         	
              	
              	<div class="text-right">
                <span class="myttl">  <?php 
  
echo  $row['amount'];
  
      ?></span>
              	</div>
              	
           	
           </td>
           
           
           
           
            <td>
                <div class="btn-group">
                    <?php
				   if($row['isprocessed']==0)
				   {
					   if (right_granted(array('managetransaction_view', 'managetransaction_manage', 'managetransaction_delete')))
						{
					    ?>
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"> <?php echo get_phrase('action');?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                            <?php
                            if (right_granted('managetransaction_manage'))
                            {
                            ?>
	                            <li>
	                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/transection_add_edit/<?php echo $row['transection_id'];?>');">
	                                    <i class="entypo-pencil"></i>
	                                    <?php echo get_phrase('edit');?>
	                                </a>
	                            </li>
                            <?php
                        	}
                        	if (right_granted('managetransaction_delete'))
                        	{
                        	?>
	                            <li class="divider"></li>
	                            <li>
	                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>transection_account/account_transection/delete/<?php echo $row['transection_id'];?>');">
	                                    <i class="entypo-trash"></i>
	                                    <?php echo get_phrase('delete');?>
	                                </a>
	                            </li>
                            <?php
                        	}
                        	?>
                        </ul>
                        <?php 
                    	}
                    }
                    ?>
                </div>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<script type="text/javascript">
jQuery(document).ready(function($) {
    var datatable = $("#table_export_exp").dataTable({
        "sPaginationType": "bootstrap",
        "sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
        "oTableTools": {
            "aButtons": [

                {
                    "sExtends": "xls",
                    "mColumns": [0, 2, 3, 4]
                }, {
                    "sExtends": "pdf",
                    "mColumns": [0, 2, 3, 4]
                }, {
                    "sExtends": "print",
                    "fnSetText": "Press 'esc' to return",
                    "fnClick": function(nButton, oConfig) {
                        datatable.fnSetColumnVis(1, false);
                        datatable.fnSetColumnVis(5, false);

                        this.fnPrint(true, oConfig);

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
