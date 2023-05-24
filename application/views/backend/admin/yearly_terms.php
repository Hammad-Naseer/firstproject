<style>
/*	.boarder{
	border: 1px solid #f2f2f2;
  height: 34px;
	}
.modal-backdrop {
   z-index: 0 !important;
}*/
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

<div class="row thisrow2">

<form action="" method="post">

	<div class="col-lg-2 col-md-2 col-sm-2">
<h5>

<?php echo get_phrase('select_academic_year');?>
</h5>
		
	</div>
	
	<div class="col-lg-4 col-md-4 col-sm-4">

<select class="form-control" name="academic_year_id">
	<option><?php echo get_phrase('select');?></option>						
<?php 
 $uri_id= $this->uri->segment("3");
$academic_year_id= $edit_data[0]['academic_year_id'];
				
$query=$this->db->query("select * from ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." ")->result_array();




		foreach($query as $rows){
				?>	
<option value="<?php echo $rows['academic_year_id']; ?>" 

<?php
 if(isset($_POST['academic_year_id'])){
	$acadmic_id=$_POST['academic_year_id'];
	 }else{
	 $acadmic_id=$uri_id;
	 	
	 }





 if($acadmic_id==$rows['academic_year_id']){
 echo "selected";
  }  ?> 
  ><?php echo  $rows['title'];    ?></option>
				
		<?php	}
			
				?>			
				</select>

		
	</div>
	
	

	<div class="col-lg-4 col-md-4 col-sm-4">
		
	<button type="submit" class="btn btn-primary"><?php echo get_phrase('select');?></button>	
	</div>	

	</form>
	
</div>
<?php 

if((isset($acadmic_id) && $acadmic_id!="") || isset($_POST['academic_year_id'])){
	


?>


<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/yearly_terms_add_edit/add/<?php echo $acadmic_id; ?>');" 
    class="btn btn-primary pull-right">
        <i class="entypo-plus-circled"></i>
        <?php echo get_phrase('add_yearly_terms');?>
    </a> 
  
<br><br>



<table class="table table-bordered datatable  table-hover  cursor" id="table_export">
    <thead>
        <tr>
           <th style="width:100px;"><div><?php echo get_phrase('order_no');?></div></th>
   <th  style="min-width:100px;"><div><?php echo get_phrase('title');?></div></th>
   

 <th style="width:100px;"><div><?php echo get_phrase('start_date');?></div></th>
      <th  style="width:100px;"><div><?php echo get_phrase('end_date');?></div></th>
            <th><div><?php echo get_phrase('detail');?></div></th>
            <th  style="width:200px;"><div><?php echo get_phrase('academic_year');?></div></th>
            <th  style="width:80px;"><div><?php echo get_phrase('options');?></div></th>
        </tr>
    </thead>
    <tbody>
        <?php 

       if(isset($_POST['academic_year_id'])){
	   	
	   	$acadmic_id=$_POST['academic_year_id'];
	   	
	   }else{
	   		   
	   }
        
        
          $school_id=$_SESSION['school_id'];
        
        $data_=array('school_id'=>$school_id,'academic_year_id'=>$acadmic_id);
        
   $this->db->order_by('order_num',"asc");
     
$students=$this->db->get_where('yearly_terms',$data_)->result_array();

//echo $this->db->last_query();

                foreach($students as $row):?>
        <tr>
            
            <td><?php echo $row['order_num'];?></td>
            <td><?php echo $row['title'];?></td>
            <td><?php echo $row['start_date'];?></td>
            <td><?php echo $row['end_date'];?></td>
            <td><span class="item"> <?php echo $row['detail'];?></span></td>
            <td><?php $my_id= $row['academic_year_id'];
            
            
            
            
     $query_vr=$this->db->query("select * from acadmic_year where academic_year_id=$my_id")->result_array();
            echo $query_vr[0]['title'];
            
            ?></td>
           
           
       
            <td>
                
                <div class="btn-group">
<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        <?php echo get_phrase('action');?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                       
                       <!-- STUDENT EDITING LINK -->
                        <li>

<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/yearly_terms_add_edit/<?php echo $row['yearly_terms_id'].'/'.$acadmic_id;?>');">
         <i class="entypo-pencil"></i>
         
          <?php echo get_phrase('edit');?>
                               
                                </a>
                                        </li>
                                        
                                        
                                  
                                        
                        <li class="divider"></li>
                        
                        <!-- STUDENT DELETION LINK -->
                        <li>
                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>academic_year/yearly_terms/delete/<?php echo $row['yearly_terms_id'].'/'.$acadmic_id;?>');">
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

<?php } ?>





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

<script>



$(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 40;  // How many characters are shown by default
    var ellipsestext = "...";
 var moretext = " Show more >>";
    var lesstext = " << Show less";
    

    $('.item').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span><a href="" class="morelink">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
 
    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
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