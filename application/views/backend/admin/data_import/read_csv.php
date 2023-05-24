<form name="savecontent" method="post" id="savecontent" action="<?php echo base_url();?>data_import/read_csv">
<table class="table table-bordered datatable" id="table_export">
    <tr>
    <td><input type="checkbox" id="select-all" name="id[selected][]" value="<?php  echo $line[0];?>"></td>
    <?php echo "<pre>";
    $file_name;
$file = fopen('uploads/csv/'.$file_name, 'r');
$header=array();
$a= fgetcsv($file);

	for($i=0;$i<sizeof($a);$i++){
   ?>
    <td>
    <select name="col[]">
    <?php  foreach($a as $k=>$v){?>
    <option <?php if($a[$i]==$v){echo "selected=selected";} ?> value="<?php echo $v?>"><?php echo $v?></option>
    <?php }?> 
    </select>
    </td>
    <?php } ?>
   </tr>
     <?php  
while(($line = fgetcsv($file)) !== FALSE){
	?>
   <tr>
     <td><input type="checkbox" id="select-all" name="id[selected][]" value="<?php  echo $line[0];?>"></td>
   <?php for($i=0;$i<sizeof($line);$i++){?>
   <td><input  type="hidden" name="id[<?php  echo $line[0]?>][<?php echo $i;?>]" value="<?php echo $line[$i]?>"><?php echo $line[$i];?></td>
   <?php }?>
        </tr>
    <?php
}
fclose($file);
?>
<tr><td colspan="13"><input type="submit" name="submit" value="submit"></td></tr>
</table>
</form>


<!-- DATA TABLE EXPORT CONFIGURATIONS -->                      
<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		$('#select-all').click(function(event) {   
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    }
    else {
    $(':checkbox').each(function() {
          this.checked = false;
      });
  }
});

		var datatable = $("#table_export").dataTable({"sPaginationType": "bootstrap",
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
"fnSetText"	  : "Press 'esc' to return",
"fnClick": function (nButton, oConfig)
 {
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