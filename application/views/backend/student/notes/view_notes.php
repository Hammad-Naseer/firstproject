

<?php
if($this->session->flashdata('club_updated'))
{
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
}
  ?>
<script>
$( window ).on("load",function() 
{
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});

</script>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('notes');?>
        </h3>   
    </div> 
</div>

<div class="col-md-12">
    	<div class="tab-content">            
            <div class="tab-pane box active" id="list">
			    <table class="table table-bordered table-responsive table_export" data-step="1" data-position='top' data-intro="Lecture notes records">
                	<thead>
                		<tr>
                    		<th><?php echo get_phrase('s_#');?></th>
                    		<th style="width:250px;"><?php echo get_phrase('notes_description');?></th>
                    		<th style="width:250px;"><?php echo get_phrase('subject');?></th>
                           	<th><?php echo get_phrase('notes_remarks');?></th>
                           	<th><?php echo get_phrase('attachments');?></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php 
                    	$j = 0;
                    	$count = 1;foreach($notes as $row):
                    	$j++;
                    	$attachment_urls = "[";
                    	?>
                        <tr>
                        	<td class="td_middle"><?php echo $j; ?></td>
                        	
                        	<td>
                        	    <div style="width:100px;display:inline;float:left"><strong>Notes Title :</strong></div>
                        	    <?php echo $row['notes_title'];?>
                        	    <br>
                        	    <div style="width:100px;display:inline;float:left"><strong>Teacher Name :</strong></div>
                        	    <?php echo $row['teacher_name'];?>
                        	    <br>
                        	    <div style="width:100px;display:inline;float:left"><strong>Date :</strong></div>
                        	    <?php echo date_view($row['inserted_at']);?>
                        	</td>
                        	<td> <?php echo $row['subject_name'];?> </td> 
                        	<td> <?php echo $row['remarks'];?> </td> 

                        	<td class="td_middle">
                        	       
                        	       <button data-step="2" data-position='left' data-intro="Press this button to download attachments" class="btn btn-block btn-primary glyphicon glyphicon-download-alt" type="button" style="color:white !important" 
                        	                onclick="jsfunction('<?php echo $row['urls'] ?>')">
                        	                Download Files
                        	       </button>
                        	</td>
        				</tr>
                 <?php endforeach;?>
                 </tbody>
                </table>               
			</div>
		</div>
	</div>

<script>

function jsfunction(url){
    
    var attachment_urls =  "";
    var params_array    =  [];
    var global          =  '<?php echo base_url(); ?>';
    var links           =  url.split(',');
    
    for(var i=0; i < links.length; i++){
        var obj = {};
        obj["download"] = global+links[i];
        obj["filename"] = 'Attachment' + (i + 1);
        params_array.push(obj);
    }
    
    download_files(params_array);
  
} 

function download_files(files) {
 
  function download_next(i) {
    if (i >= files.length) {
      return;
    }
    var a = document.createElement('a');
    a.href = files[i].download;
    a.target = '_parent';
    if ('download' in a) {
      a.download = files[i].filename;
    }
    (document.body || document.documentElement).appendChild(a);
    if (a.click) {
      a.click(); 
    } else {
      $(a).click(); 
    }

    a.parentNode.removeChild(a);
    setTimeout(function() {
      download_next(i + 1);
    }, 500);
  }
  download_next(0);
}
</script>
