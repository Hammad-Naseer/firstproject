
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
               <?php echo get_phrase('class_wise_student_summary'); ?>
        </h3>
    </div>
</div>
<div class="col-lg-12 col-sm-12">
    <table class="table table-bordered datatable table-hover cursor table_export" data-step="1" data-position='top' data-intro="class wise student summary ">
        <thead>
            <tr>
                <th style="width:20px;"><?php echo get_phrase('#');?></th>
                <th><?php echo get_phrase('class_detail');?></th>
                <th style="width:100px;"><?php echo get_phrase('student_strength');?></th>
            </tr>
        </thead>
        <tbody>
        <?php
    		$j=0;
    // 		$department_classification = array();
            foreach($student_count as $row)
    		{
    		    
                // $department_classification[$row['departments_id']][$row['section_id']]['count']=$row['section_count'];
                // $department_classification[$row['departments_id']][$row['section_id']]['name']=$row['section_name'];
    		    
                $j++;
    		?>
    		<tr>
    		    <td class="td_middle"><?php echo $j; ?></td>
    		    <td><?php echo $row['department_name']." / ".$row['class_name']." / ".$row['section_name']; ?></td>
    		    <td class="td_middle"><?php echo $row['section_count']; ?></td>
    		</tr>
    		
            <?php
            }
            ?>
        </tbody>
    </table>    
</div>
</script>